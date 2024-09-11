#include <Arduino.h>
#include "SPI.h"
#include "FS.h"
#include <WiFi.h>
#include <Wire.h>
#include <NewPing.h>


#define TRIGGER_PIN_A 14
#define TRIGGER_PIN_B 12
#define MAX_DISTANCE 400

class Sonar : public NewPing
{
	// Class Member Variables
	// Initialized
	int trigPin;	// Trigger
	int echoPin;	// Echo 

	// Current Readings
	int distance;					// measured distance
	unsigned long previousMillis;	// last time we did a reading

	// Constructor - creates a sonar
	// and initialize member variables
	public : Sonar(int trig, int echo) : NewPing(trig, echo, 300), trigPin(trig), echoPin(echo), previousMillis(0) 
	{

	}

	void updateSonar()
	{
		// check if the time has passed (more than 30ms)
		unsigned long currentMillis = millis();

		// if it has passed more than 30ms
		if(currentMillis - previousMillis >= 20)
		{
			previousMillis = millis();
			// read current distance
			distance = ping_cm();
		}
	}

	int currentDistance() { return distance; }
};

// Create the instances for our sonars
Sonar sonar1(12, 12);
Sonar sonar2(14, 14);

void setup()
{
    Serial.begin(115200);
}

void loop()
{
	sonar1.updateSonar();
	sonar2.updateSonar();
    /*
	int distance1 = sonar1.currentDistance();
	int distance2 = sonar2.currentDistance();
    /*

	Serial.print(distance1);
	Serial.print(" - ");
	Serial.println(distance2);
    */

   Serial.println(millis());
   delay(2);
}