#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <WiFiClient.h>
#include <EEPROM.h>
#include <SoftwareSerial.h>
#include "index.h"

#define CFG_BTN D5

char ESPSSID[20]  = "ESP8211570";
char ESPPASS[20]  = "123456789";
char HOSTNAME[25] = "192.168.2.3";
int  PORT = 81;

SoftwareSerial mcu(D1, D2);
ESP8266WebServer server(80);
WiFiClient esp;

String buffers;
bool isConfigMode;

void setup() {
  Serial.begin(9600);
  pinMode(D1, INPUT);
  pinMode(D2, OUTPUT);
  mcu.begin(9600);
  //
  pinMode(CFG_BTN, INPUT_PULLUP);
  //
  WiFi.mode(WIFI_AP_STA);
  WiFi.softAP(ESPSSID, ESPPASS); 
  //
  server.on("/", getIndex);
  server.on("/save_config", saveConfig);
  server.on("/get_config", getConfig);
  //
  EEPROM.begin(60);
  initParam();
  buffers = "";
  isConfigMode = false;
}

void loop() {
  CheckSerial();
  // check config button
  if(digitalRead(CFG_BTN) == LOW){
    isConfigMode = true;
    server.begin();
    while(isConfigMode){
      server.handleClient();
      delay(5);
    }
  }
  delay(1);
}

void CheckSerial(){
  if(mcu.available()){
    while(mcu.available()){
      byte c = mcu.read();
      if(c != '\r' && c != '\n'){
        buffers += (char) c;
      }
      else{
        if(buffers != ""){
//          Serial.println(buffers);
          String resp = SendRequest();
          if(resp != ""){
            for(int i = 0; i < resp.length(); i++){
              mcu.print(resp[i]);
              delay(5);
            }
            mcu.println();
//            Serial.println(resp);
          }
          buffers = "";
        }
      }
    }
  }
}

String SendRequest(){
  String resp;
  esp.connect(HOSTNAME, PORT);
  if(esp.connected()){
    buffers.replace("+", "%2b");
    esp.print("POST /securitylock/main.php HTTP/1.0\r\n");
    delay(1);
    esp.print("Host: ");
    delay(1); 
    esp.print(HOSTNAME);
    delay(1);
    esp.print("\r\n");
    delay(1);
    esp.print("Content-Type: application/x-www-form-urlencoded\r\n");
    delay(1);
    esp.print("Content-Length: ");
    delay(1);
    esp.print(buffers.length() + 5);
    delay(1);
    esp.print("\r\n\r\n");
    delay(1);
    esp.print("data=");
    delay(1);
    esp.print(buffers);
    unsigned long times = millis();
    resp = "";
    bool isStart = false;
    while(millis() - times <= 5000){
      if(esp.available()){
        while(esp.available()){
          char c = esp.read();
          resp += c;
        }
        break;
      }
    }
    esp.stop();
  }
  if(resp.indexOf("RespData:") != -1)
    resp = resp.substring(resp.indexOf("RespData:") + 9);
  return resp;
}

void initParam(){
  int i = 0;
  while(true){
    HOSTNAME[i] = EEPROM.read(i);
    if(i == 40 || HOSTNAME[i] == 0)
      break;
    i++;
  }
  PORT = EEPROM.read(50);
  PORT = (PORT << 8) + EEPROM.read(51);
}

void getIndex(){
  server.send(200, "text/html", index_page);
}

void saveConfig(){
  int i;
  // send notification
  server.send(200, "text/plain", "Saved successful!");
  // save wifi
  if(server.arg("ssid").length() > 0){
    WiFi.begin(server.arg("ssid"), server.arg("pass"));
  }
  // save server name and port
  if(server.arg("server").length() > 0){
    for(i = 0; i < server.arg("server").length(); i++){
      EEPROM.write(i, server.arg("server")[i]);
    }
    EEPROM.write(i, 0);
    EEPROM.commit();
  }
  if(server.arg("port").length() > 0){
    PORT = 0;
    for(int i = 0; i < server.arg("port").length(); i++){
      PORT = PORT * 10 + server.arg("port")[i] - '0';
    }
    EEPROM.write(50, PORT >> 8);
    EEPROM.write(51, PORT & 0xFF);
    EEPROM.commit();
  }
  //
  delay(1000);
  isConfigMode = false;
}

void getConfig(){
  server.send(200, "text/plain", String("Server config: ") + String(HOSTNAME) + String(":") + String(PORT));
}
