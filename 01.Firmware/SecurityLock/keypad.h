#ifndef _KEYPAD_H
#define _KEYPAD_H

#define KEY_COL1 52
#define KEY_COL2 50
#define KEY_COL3 53
#define KEY_ROW1 51
#define KEY_ROW2 48
#define KEY_ROW3 49
#define KEY_ROW4 46

char CheckKeypad(){
  char key = 0;
  // Scan column
  pinMode(KEY_ROW1, OUTPUT);
  pinMode(KEY_ROW2, OUTPUT);
  pinMode(KEY_ROW3, OUTPUT);
  pinMode(KEY_ROW4, OUTPUT);
  pinMode(KEY_COL1, INPUT_PULLUP);
  pinMode(KEY_COL2, INPUT_PULLUP);
  pinMode(KEY_COL3, INPUT_PULLUP);
  digitalWrite(KEY_ROW1, LOW);
  digitalWrite(KEY_ROW2, LOW);
  digitalWrite(KEY_ROW3, LOW);
  digitalWrite(KEY_ROW4, LOW);
  // Scan row
  if(digitalRead(KEY_COL1) == LOW){
    pinMode(KEY_COL1, OUTPUT);
    digitalWrite(KEY_COL1, LOW);
    pinMode(KEY_ROW1, INPUT_PULLUP);
    pinMode(KEY_ROW2, INPUT_PULLUP);
    pinMode(KEY_ROW3, INPUT_PULLUP);
    pinMode(KEY_ROW4, INPUT_PULLUP);
    if(digitalRead(KEY_ROW1) == LOW){
      key = '1';
    }
    else if(digitalRead(KEY_ROW2) == LOW){
      key = '4';
    }
    else if(digitalRead(KEY_ROW3) == LOW){
      key = '7';
    }
    else if(digitalRead(KEY_ROW4) == LOW){
      key = '*';
    }
  }
  else if(digitalRead(KEY_COL2) == LOW){
    pinMode(KEY_COL2, OUTPUT);
    digitalWrite(KEY_COL2, LOW);
    pinMode(KEY_ROW1, INPUT_PULLUP);
    pinMode(KEY_ROW2, INPUT_PULLUP);
    pinMode(KEY_ROW3, INPUT_PULLUP);
    pinMode(KEY_ROW4, INPUT_PULLUP);
    if(digitalRead(KEY_ROW1) == LOW){
      key = '2';
    }
    else if(digitalRead(KEY_ROW2) == LOW){
      key = '5';
    }
    else if(digitalRead(KEY_ROW3) == LOW){
      key = '8';
    }
    else if(digitalRead(KEY_ROW4) == LOW){
      key = '0';
    }
  }
  else if(digitalRead(KEY_COL3) == LOW){
    pinMode(KEY_COL3, OUTPUT);
    digitalWrite(KEY_COL3, LOW);
    pinMode(KEY_ROW1, INPUT_PULLUP);
    pinMode(KEY_ROW2, INPUT_PULLUP);
    pinMode(KEY_ROW3, INPUT_PULLUP);
    pinMode(KEY_ROW4, INPUT_PULLUP);
    if(digitalRead(KEY_ROW1) == LOW){
      key = '3';
    }
    else if(digitalRead(KEY_ROW2) == LOW){
      key = '6';
    }
    else if(digitalRead(KEY_ROW3) == LOW){
      key = '9';
    }
    else if(digitalRead(KEY_ROW4) == LOW){
      key = '#';
    }
  }  
  return key;
}

#endif
