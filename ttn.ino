#include <TheThingsNetwork.h>
#include "BMI088.h"

const char *appEui = "70B3D57ED001DA98";
const char *appKey = "44FDDF8C7661736769C3440933A0E58F";

float ax = 0, ay = 0, az = 0;
float gx = 0, gy = 0, gz = 0;
int16_t temp = 0;
int baseLineaX = 0;
int baseLineaY = 0;
int baseLineaZ = 0;
int baseLinegX = 0;
int baseLinegY = 0;
int baseLinegZ = 0;

#define loraSerial Serial1
#define debugSerial Serial

// Replace REPLACE_ME with TTN_FP_EU868 or TTN_FP_US915
#define freqPlan TTN_FP_EU868

TheThingsNetwork ttn(loraSerial, debugSerial, freqPlan);

void setup() {
  loraSerial.begin(57600);
  debugSerial.begin(9600);
    
  // Wait a maximum of 10s for Serial Monitor
  while (!debugSerial && millis() < 10000);
    
  debugSerial.println("-- STATUS");
  ttn.showStatus();
  
  debugSerial.println("-- JOIN");
  ttn.join(appEui, appKey);

  Wire.begin();
  Serial.begin(115200);
    
  while(!Serial);
    Serial.println("BMI088 Raw Data");

  while(1){
    if(bmi088.isConnection()){
       bmi088.initialize();
       Serial.println("BMI088 is connected");
       break;
     }else Serial.println("BMI088 is not connected");
        
     delay(2000);
  }
    
  bmi088.getAcceleration(&ax, &ay, &az);
  bmi088.getGyroscope(&gx, &gy, &gz);
  baseLineaX = ax;
  baseLineaY = ay;
  baseLineaZ = az;
  baseLinegX = gx;
  baseLinegY = gy;
  baseLinegZ = gz;
}

void loop() {
  // put your main code here, to run repeatedly:
  debugSerial.println("-- LOOP");

  bmi088.getAcceleration(&ax, &ay, &az);
  bmi088.getGyroscope(&gx, &gy, &gz);
  temp = bmi088.getTemperature()*100;

  byte payload[8];
  payload[0] = (byte)((temp & 0xFF00) >> 8);
  payload[1] = (byte)((temp & 0x00FF));
  payload[2] = (byte)((ax-baseLineaX)*0.09);
  payload[3] = (byte)((ay-baseLineaY)*0.09);
  payload[4] = (byte)((az-baseLineaZ)*0.09);
  payload[5] = (byte)((gx-baseLinegX)*0.004);
  payload[6] = (byte)((gy-baseLinegY)*0.004);
  payload[7] = (byte)((gz-baseLinegZ)*0.004);

  ttn.sendBytes(payload, sizeof(payload));
  delay(10000);
}
