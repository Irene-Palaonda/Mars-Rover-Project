#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>
#include <Arduino.h>
#include <AsyncTCP.h>
#include <ESPAsyncWebServer.h>
#include "SPI.h"
#include "FS.h"
#include <drive.hpp>

#define PWMA 2
#define AI2 15
#define AI1 4
#define STNDBY 14
#define BI1 33
#define BI2 32
#define PWMB 25

//wifi ssid & password
const char* ssid = "ONEPLUS";
const char* password =  "1300618628";

// REPLACE with your Domain name and URL path or IP address with path
const char* serverName = "http://13.41.165.183/rover/update.php";

// Keep this API Key value to be compatible with the PHP code provided in the project page. 
// If you change the apiKeyValue value, the PHP file /post-esp-data.php also needs to have the same key 
String apiKeyValue = "tPmAT5Ab3j7F9";

//set up HTTP webserver
AsyncWebServer server(80);
//set up websocket endpoint and handler
AsyncWebSocket ws("/test");

String command = ""; 
volatile bool action; 

void tokenize(std::string const &str, const char delim, std::vector<std::string> &out)
{
    size_t start;
    size_t end = 0;
 
    while ((start = str.find_first_not_of(delim, end)) != std::string::npos)
    {
        end = str.find(delim, start);
        out.push_back(str.substr(start, end - start));
    }
}
 
void isr() {
    action = true; 
}

void send_sensor(int state, int command, int speed) {
  if(WiFi.status()== WL_CONNECTED){
    WiFiClient client; 
    HTTPClient http; 
    http.begin(client, serverName);
    // Specify content-type header
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    // Prepare your HTTP POST data
    String httpRequestData = "api_key=" + apiKeyValue +"&state=" + String(state) + "&value=" + String(command) + "&speed=" + String(speed) + "";
    //Serial.print("httpRequestData: ");
    //Serial.println(httpRequestData);
    // Send HTTP POST request
    int httpResponseCode = http.POST(httpRequestData);
    /*
    Serial.println(httpResponseCode);
    if (httpResponseCode>0) {
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
    }
    else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }
    */
    http.end();
  }
  else {
    Serial.println("WiFi Disconnected");
  }
}

void UpdateCommand(AsyncWebSocket * server, AsyncWebSocketClient * client, AwsEventType type, void * arg, uint8_t *data, size_t len){
  if(type == WS_EVT_CONNECT){
    Serial.println("Websocket client connection received");
  } else if(type == WS_EVT_DISCONNECT){
    Serial.println("Client disconnected");
  } else if(type == WS_EVT_DATA){
    //Serial.println("Data received: ");
    command = "";
    for(int i=0; i < len; i++) {
      //Serial.print((char) data[i]);
      command += ((char)data[i]);
    }
    action = true; 
    //Serial.println(command); 
    //Serial.println();
  }
}

void setup(){
  Serial.begin(115200);
  /*
  //bind IP address to ESP32
  if (!WiFi.config(local_IP, gateway, subnet, primaryDNS, secondaryDNS)) {
    Serial.println("STA Failed to configure");
  }
  */
  //connect to WIFI
  WiFi.begin(ssid, password);
  Serial.print ( "connecting" );
  while ( WiFi.status() != WL_CONNECTED ) {
    delay ( 500 );
    Serial.print ( "." );
  }
  Serial.println(WiFi.localIP());
  //bind handling function to endpoint 
  ws.onEvent(UpdateCommand); 
  //register websocket object on HTTP webserver 
  server.addHandler(&ws); 
  //start listening to incoming requests
  server.begin();
  //attachInterrupt (0, isr, CHANGE);



    pinMode(PWMA, OUTPUT);
    pinMode(PWMB, OUTPUT);
    pinMode(AI1, OUTPUT);
    pinMode(AI2, OUTPUT);
    pinMode(BI1, OUTPUT);
    pinMode(BI2, OUTPUT);
    pinMode(STNDBY, OUTPUT);
    //IR camera initialize
    pinMode(PIN_SS,OUTPUT);
    pinMode(PIN_MISO,INPUT);
    pinMode(PIN_MOSI,OUTPUT);
    pinMode(PIN_SCK,OUTPUT);

  arm();
}

void loop() {
  if (action) {
    String move = command.substring(0, 1); 
    String speedstr = command.substring(1, 4); 
    command = "";
    if (speedstr.substring(0,1) == "0") {
        speedstr = speedstr.substring(1,3);
    }
    int speed = speedstr.toFloat();
    //int speed = speedstr.toInt();
    if (move == "w") {
        RightForward(speed);
        LeftForward(speed); 
        Serial.println("forward speed=" + String(speed)); 
    } else if (move == "a") {
        Serial.println("leftward speed=" + String(speed)); 
        RightForward(speed);
        LeftBackward(speed); 
    } else if (move == "s") {
        Serial.println("backward speed=" + String(speed)); 
        RightBackward(speed);
        LeftBackward(speed); 
    } else if (move == "d") {
        Serial.println("rightward speed=" + String(speed)); 
        RightBackward(speed);
        LeftForward(speed); 
    } else if (move == "n") {
        Serial.println("stop"); 
        RightStop();
        LeftStop();
    }
    action = !action; 
  }
}
