#include <ArduinoJson.h>
#include <LCD_I2C.h>
#include <Servo.h>
#include <xbase64.h>
#include "aes_lib.h"
#include "keypad.h"

#define CLOSE   11
#define BUZZER  12
#define SERVO   13

LCD_I2C lcd(0x27, 16, 2);
Servo servo;

#define phone_num "0373071999"

byte aes_key[] = { 0x2B, 0x7E, 0x15, 0x16, 0x28, 0xAE, 0xD2, 0xA6, 0xAB, 0xF7, 0x15, 0x88, 0x09, 0xCF, 0x4F, 0x3C };
byte iv[] = { 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0A, 0x0C, 0x0B, 0x0D, 0x0E, 0x0F, 0xAA };
char ciphertext[160], plaintext[160], base64_text[200];

byte prev_mode, cur_mode;
bool lcd_update;
bool prev_status, cur_status;

unsigned long lock_code_time, otp_code_time, status_update_time;
char lock_code[9], otp_code[7], right_otp[7];
byte lock_code_cnt, otp_code_cnt;
byte wrong_code_cnt;
bool check_lock_code;

char buffers[200];
int buffCnt;

void setup() {
  delay(2000);
  Serial.begin(9600);  // For debugging
  Serial1.begin(9600); // UART1 connected to GSM module
  Serial2.begin(9600); // UART2 connected to ESP8266
  GPIO_Init();
  GSM_Init();
  lcd.begin();
  lcd.backlight();
  AES_Init(aes_key, 128);
  prev_mode = cur_mode = 0;
  prev_status = cur_status = false;
  lcd_update = true;
  lock_code[0] = otp_code[0] = right_otp[0] = 0;
  lock_code_cnt = otp_code_cnt = 0;
  check_lock_code = false;
  wrong_code_cnt = 0;
  //
  buffers[0] = 0;
  buffCnt = 0;
  //
  servo.attach(SERVO);
  servo.write(0);
}

void loop() {
  CheckStatus();
  if(cur_status == false){
    CheckMode();
    CheckWrong();
    CheckTimeout();
    char key = CheckKeypad();
    if (key != 0) {
      otp_code_time = millis();
      EnterCode(cur_mode, key);
      delay(300);
    }
    CheckESPSerial();
  }
  delay(1);
}

void GPIO_Init() {
  pinMode(CLOSE, INPUT_PULLUP);
  pinMode(BUZZER, OUTPUT);
  digitalWrite(BUZZER, LOW);
}

void CheckMode() {
  if (cur_mode != prev_mode || lcd_update == 1) {
    switch (cur_mode) {
      case 0:
        lcd.clear();
        lcd.print("Nhap mat ma:");
        lcd.setCursor(0, 1);
        break;
      case 1:
        lcd.clear();
        lcd.print("Nhap ma OTP:");
        lcd.setCursor(0, 1);
        break;
    };

    if (lcd_update == true)
      lcd_update = false;

    prev_mode = cur_mode;
  }
}

void CheckWrong(){
  if (wrong_code_cnt == 3) {
    lcd.clear();
    lcd.print("Nhap sai 3 lan");
    lcd.setCursor(0, 1);
    lcd.print("Vui long cho 20s");
    SendSMS("Canh bao: nhap sai ma qua 3 lan");
    for(int i = 0; i < 10; i++){
      digitalWrite(BUZZER, HIGH);
      delay(1000);
      digitalWrite(BUZZER, LOW);
      delay(1000);
    }
    wrong_code_cnt = 0;
    lcd_update = true;
  }
}

void CheckStatus(){
  if(cur_status != prev_status){
    if(cur_status == true){
      lcd.clear();
      lcd.print("Mo cua");
      servo.write(90);
      Serial2.println("GHmF9JHsY9idJ6peiC71nQ=="); // encrypted text of open status
      status_update_time = millis();
    }
    else{
      lcd.clear();
      lcd.print("Dong cua");
      lcd_update = true;
      servo.write(0);
      Serial2.println("yFlLLYHM8+Eyqmg0rguZpw=="); // encrypted text of close status 
      status_update_time = millis();
    }
    prev_status = cur_status;
  }
  if(digitalRead(CLOSE) == LOW && cur_status == true){
    cur_status = false;
  }
  if(millis() - status_update_time >= 10000){
    if(cur_status == true)
      Serial2.println("GHmF9JHsY9idJ6peiC71nQ==");
    else
      Serial2.println("yFlLLYHM8+Eyqmg0rguZpw==");
    status_update_time = millis();
  }
}

void CheckTimeout(){
  if(cur_mode == 0 && check_lock_code == true && millis() - lock_code_time >= 5000){
    check_lock_code = false;
    lock_code[0] = 0;
    lock_code_cnt = 0;
    lcd_update = true;
  }
  if(cur_mode == 1 && millis() - otp_code_time >= 30000){
    cur_mode = 0;
  }
}

void EnterCode(int mode, char key) {
  char *code;
  byte *code_cnt, limit;
  if (mode == 0) {
    code = lock_code;
    code_cnt = &lock_code_cnt;
    limit = 8;
  }
  else if (mode == 1) {
    code = otp_code;
    code_cnt = &otp_code_cnt;
    limit = 6;
  }

  if ('0' <= key && key <= '9') {
    if (*code_cnt < limit) {
      code[*code_cnt] = key;
      (*code_cnt)++;
      code[*code_cnt] = 0;
      lcd.print(key);
    }
  }
  else if (key == '*') {
    if (*code_cnt > 0) {
      (*code_cnt)--;
      code[*code_cnt] = 0;
      lcd.setCursor(*code_cnt, 1);
      lcd.print(' ');
      lcd.setCursor(*code_cnt, 1);
    }
  }
  else if (key == '#') {
    if (cur_mode == 0 && check_lock_code == false && lock_code[0] != 0) {
      // create encrypted text to send lock code to server
      sprintf(plaintext, "{\"cmd\":\"lock_code\",\"code\":\"%s\"}", lock_code);
      AES_Encrypt(plaintext, strlen(plaintext), ciphertext, (char*) iv);
      base64_encode(base64_text, ciphertext, strlen(ciphertext));
      Serial2.println(base64_text);
      //
      lcd.setCursor(0, 1);
      lcd.print("Verifying...   ");
      check_lock_code = true;
      lock_code_time = millis();
    }
    else if (cur_mode == 1) {
      if(strcmp(otp_code, right_otp) == 0){
        cur_status = true;
        wrong_code_cnt = 0; // reset wrong count
      }
      else{
        lcd.clear();
        lcd.print("Sai OTP");
        delay(5000);
        lcd_update = true;
        wrong_code_cnt++;
      }
      otp_code[0] = 0;
      otp_code_cnt = 0;
      right_otp[0] = 0;
      cur_mode = 0;
    }
  }
}

void CheckESPSerial() {
  if (Serial2.available()) {
    while (Serial2.available()) {
      char c = Serial2.read();
      if (c != '\r' && c != '\n'){
        buffers[buffCnt] = c;
        buffCnt++;
        buffers[buffCnt] = 0;
      }
      else if (buffCnt > 0) {
        ProcessBuffer();
        buffers[0] = 0;
        buffCnt = 0;
      }
    }
  }
}

void ProcessBuffer() {
  // decrypt the response from server to check lock code and get otp code if lock code is right
  base64_decode(ciphertext, buffers, strlen(buffers));
  AES_Decrypt(ciphertext, strlen(ciphertext), plaintext, (char*) iv);
  StaticJsonDocument<100> doc;
  deserializeJson(doc, plaintext);

  if (check_lock_code == true && strcmp(doc["cmd"], "lock_code") == 0) {
    if (strcmp(doc["result"], "true") == 0) {
      cur_mode = 1;
      strcpy(right_otp, doc["otp"]);
      char tmp[40];
      sprintf(tmp, "Ma OTP la: %s", right_otp);
      SendSMS(tmp);
      wrong_code_cnt = 0; // reset wrong count
      otp_code_time = millis();
    }
    else {
      lcd.clear();
      lcd.print("Sai mat ma");
      delay(5000);
      lcd_update = true;
      wrong_code_cnt++;
    }
    lock_code[0] = 0;
    lock_code_cnt = 0;
    check_lock_code = false;
  }
}

void GSM_Init(){
  Serial1.print("AT\r\n");
  delay(1000);
  Serial1.print("AT+CMGF=1\r\n");
  delay(1000);
  Serial1.print("AT+CNMI=1,2,0,0,0\r\n");
  delay(1000);
  Serial1.print("AT+CREG=1\r\n");
  delay(1000);
}

void SendSMS(char mess[]) {
  Serial1.print("AT+CMGS=\"");
  delay(1);
  Serial1.print(phone_num);
  delay(1);
  Serial1.print("\"\r\n");
  delay(500);
  Serial1.print(mess);
  delay(1);
  Serial1.print("\x1A");
}
