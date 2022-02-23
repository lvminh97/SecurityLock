# Security Lock

### 1. Upload firmware

#### 1.1 Arduino board
- Connect `Arduino Mega board` to PC by USB cable
- Open `Arduino IDE`, go to `Tools` > `Board` > `Arduino AVR Boards` > `Arduino Mega or Mega 2560` to select for `Arduino Mega board`
- Goto `Tools` > `Port` to select the port which connects to PC
- Press combination key **Ctrl+U** to compile and upload firmware to board

#### 1.2 ESP8266 
- Connect `ESP8266 board` to PC by USB cable
- Open `Arduino IDE`, go to `Tools` > `Board` > `ESP8266 Boards` > `NodeMCU 0.9 (ESP-12 Module)` to select for `ESP8266 board`
- Goto `Tools` > `Port` to select the port which connects to PC
- Press combination key **Ctrl+U** to compile and upload firmware to board

### 2. Config ESP8266
- Press the config button on the circuit
- Let PC connect to WiFi named ***"ESP8211570"***, password is ***"123456789"***
- Open web browser and go to address http://192.168.4.1, enter the WiFi name and password of your own router, IP address and port of your server computer. Then click ***"Save config"*** button to save the configuration for `ESP8266 board`.  
  If you see the text `Saved successful!`, that means the configuration is saved.
- Now, press the reset button on ESP8266 board to restart the program and reload new configuaration.

### 3. Setup and start webserver 
>TBD