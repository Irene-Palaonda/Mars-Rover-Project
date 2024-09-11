// Tanmay - docs are here - https://adafruit.github.io/Adafruit_VL53L0X/html/index.html
#include <Adafruit_VL53L0X.h>
#include <Wire.h>

// Sensor configurations (see docs) Can be one of:
// VL53L0X_SENSE_DEFAULT, VL53L0X_SENSE_LONG_RANGE 
// VL53L0X_SENSE_HIGH_SPEED, VL53L0X_SENSE_HIGH_ACCURACY
Adafruit_VL53L0X::VL53L0X_Sense_config_t FRONT_CONFIG = Adafruit_VL53L0X::VL53L0X_SENSE_HIGH_ACCURACY;
Adafruit_VL53L0X::VL53L0X_Sense_config_t LEFT_CONFIG = Adafruit_VL53L0X::VL53L0X_SENSE_HIGH_ACCURACY;
Adafruit_VL53L0X::VL53L0X_Sense_config_t RIGHT_CONFIG = Adafruit_VL53L0X::VL53L0X_SENSE_HIGH_ACCURACY;

// We need to set these addresses for each sensor
#define FRONT_SENSOR_ADDR 0x55
#define LEFT_SENSOR_ADDR 0x56
#define RIGHT_SENSOR_ADDR 0x57

// Where the shutdown pins are connected for each sensor
#define FRONT_SHDN 14
#define LEFT_SHDN 16
#define RIGHT_SHDN 17

Adafruit_VL53L0X frontSensor;
Adafruit_VL53L0X leftSensor;
Adafruit_VL53L0X rightSensor;

// Arrays to hold front, left, right stuff in that order
Adafruit_VL53L0X::VL53L0X_Sense_config_t configs[3] = {FRONT_CONFIG, LEFT_CONFIG, RIGHT_CONFIG};
int shutdown_pins[3] = {FRONT_SHDN, LEFT_SHDN, RIGHT_SHDN};
int addresses[3] = {FRONT_SENSOR_ADDR, LEFT_SENSOR_ADDR, RIGHT_SENSOR_ADDR};
Adafruit_VL53L0X *sensors[3] = {&frontSensor, &leftSensor, &rightSensor};

// Initialises pins and shuts down all sensors
void tof_shutdown_all() {
  for (int pin = 0; pin < 3; pin ++) {
    digitalWrite(shutdown_pins[pin], LOW);
  }
}

bool tof_init() {
  bool success = true;
  // Shutdown all sensors first
  tof_shutdown_all();
  
  // One by one, set address and initialise
  for (int sensor = 0; sensor < 3; sensor ++) {
    digitalWrite(shutdown_pins[sensor], HIGH);
    delay(100);
    float this_success = sensors[sensor]->begin(addresses[sensor], false, &Wire, configs[sensor]);
    Serial.println("Sensor " + String(sensor) + " initialised? :" + String(this_success));

    if (!this_success) success = false;
  }

  return (success); // Should still be true if all went well
}

// Read a single sensor distance (0 = front, 1 = left, 2 = right)
void tof_read_single(int sensor, int &distance) {
  distance = sensors[sensor]->readRange();
}

// Read all and return the time taken to do this in ms
void tof_read_all(int &front_distance, int &left_distance, int &right_distance) {
  uint32_t start_time = millis();
  front_distance = frontSensor.readRange();
  left_distance = leftSensor.readRange();
  right_distance = rightSensor.readRange();
  uint32_t time_taken = millis() - start_time;
}

int front_d, left_d, right_d;

void setup() {
  Serial.begin(115200);
  Wire.begin();

  for (int pin = 0; pin < 3; pin ++) {
    pinMode(shutdown_pins[pin], OUTPUT);
    digitalWrite(shutdown_pins[pin], LOW);
  }

  bool success = tof_init();
  if (success) Serial.println("Successfully Initialised Three Sensors");
  else Serial.println("Something went wrong");
}

void loop() {
  tof_read_all(front_d, left_d, right_d);
  Serial.println(String(right_d) + ", " + String(left_d) + ", " + String(front_d));
  delay(100);
}
