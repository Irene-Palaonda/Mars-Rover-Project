#include <Wire.h>
#include <INA219_WE.h>

#define EXT_CURRENT_SENSOR A6
#define OFFSET -15
#define MV_TO_AMPS 188.5

INA219_WE ina219; // this is the instantiation of the library for the current sensor

float v_pv, i_pv, vref, dutyref, v_out, v_out_final;
float v_pv_prev;
float i_pv_prev;
float delta_D = 0.01;
float current_limit;
boolean Boost_mode = 1;
boolean CL_mode = 0;
unsigned int loop_trigger;
unsigned int int_count = 0; 
float Ts = 0.001;
float duty_out = 0.9;
String datastring;

void setup() {

  // put your setup code here, to run once:
  Wire.begin(); // We need this for the i2c comms for the current sensor
  Wire.setClock(700000); // set the comms speed for i2c
  ina219.init(); // this initiates the current sensor
  Serial.begin(9600); // USB Communications

  
 noInterrupts(); //disable all interrupts
  analogReference(EXTERNAL); // We are using an external analogue reference for the ADC

  //SMPS Pins
  pinMode(13, OUTPUT); // Using the LED on Pin D13 to indicate status
  pinMode(2, INPUT_PULLUP); // Pin 2 is the input from the CL/OL switch
  pinMode(6, OUTPUT); // This is the PWM Pin
  pinMode(7, OUTPUT);
  pinMode(8, OUTPUT);
  
  //Analogue input, the battery voltage (also port B voltage)
  pinMode(A0, INPUT);
  pinMode(A7, INPUT);
  pinMode(A1, INPUT);
  pinMode(EXT_CURRENT_SENSOR, INPUT); // External Current Sensor
  pinMode(A3, INPUT);

  // TimerA0 initialization for 1kHz control-loop interrupt.
  TCA0.SINGLE.PER = 999; //
  TCA0.SINGLE.CMP1 = 999; //
  TCA0.SINGLE.CTRLA = TCA_SINGLE_CLKSEL_DIV16_gc | TCA_SINGLE_ENABLE_bm; //16 prescaler, 1M.
  TCA0.SINGLE.INTCTRL = TCA_SINGLE_CMP1_bm;

  // TimerB0 initialization for PWM output
  TCB0.CTRLA = TCB_CLKSEL_CLKDIV1_gc | TCB_ENABLE_bm; //62.5kHz

  interrupts();  //enable interrupts.
  analogWrite(6, 120); //just a default state to start with

}

void loop() {
  // put your main code here, to run repeatedly:
  if(loop_trigger) {
       i_pv = ina219.getCurrent_mA();
      int_count++; //count how many interrupts since this was last reset to zero
      loop_trigger = 0; 
  }

  if (int_count == 100) {
    int_count = 0;
    sample();
    float new_duty = mppt();
    new_duty = saturation(new_duty,0.99,0.01); // saturate the duty cycle at the reference or a min of 0.01
    duty_out = new_duty;
    //datastring=String(v_pv)+","+String(i_pv)+","+String(new_duty);
    datastring=String(v_pv)+","+String(i_pv)+","+String(v_out)+","+String(new_duty)+","+String(v_out_final);
    Serial.println(datastring);
    pwm_modulate(new_duty);
    v_pv_prev = v_pv;
    i_pv_prev = i_pv;

    uv_ov_lockout();
  } 
}

ISR(TCA0_CMP1_vect) {
  loop_trigger = 1; //trigger the loop when we are back in normal flow
  TCA0.SINGLE.INTFLAGS |= TCA_SINGLE_CMP1_bm; //clear interrupt flag
}
float saturation( float sat_input, float uplim, float lowlim){ // Saturatio function
  if (sat_input > uplim) sat_input=uplim;
  else if (sat_input < lowlim ) sat_input=lowlim;
  return sat_input;
}
void pwm_modulate(float pwm_input){ // PWM function
  analogWrite(6,(int)(255-pwm_input*255)); 
}
float mppt(){
  int p_pv = i_pv*v_pv;
  int p_pv_prev = i_pv_prev*v_pv_prev;

    if ((p_pv - p_pv_prev) != 0) {
      if ((p_pv - p_pv_prev) > 0){
        if ((v_pv - v_pv_prev) > 0) {
          return (duty_out - delta_D);
        }
        else{
          return (duty_out + delta_D);
        }
      }
      else{
        if ((v_pv - v_pv_prev) > 0) {
          return (duty_out + delta_D);
        }
        else{
          return (duty_out - delta_D);
        }
      }
    }
  else{
      return duty_out;
    }   
  }




float sample() {
  v_pv = analogRead(A3) * (4.096 / 1023.0) /0.371;
  v_out = analogRead(A7)* 1.5 *(4.096 / 1023.0) ;
  v_out_final = analogRead(A1) * (1.5) * (4.096 / 1023.0);
  i_pv = samplePVCurrent();
}

float samplePVCurrent() {
  float samples = 300.0; // how many samples to take - reduces noise but takes more time!
  float mv = 0; // millivolts from A7
  float ma_raw = 0;
  float ma_corrected = 0;
  
  for (int i = 0; i < samples; i++) mv += analogRead(EXT_CURRENT_SENSOR) * 4096.0/1024.0;
  mv = 2510.0 - (mv / samples);
  
  ma_raw = (mv/MV_TO_AMPS * 1000);
  ma_corrected = ma_raw + OFFSET;
  return abs(ma_corrected);
  if (ma_corrected < 0) { return ma_corrected; } else { return 0; }
}



void uv_ov_lockout() {
  if ((4.5 < v_out_final) && (v_out_final < 5.2)) digitalWrite(7, LOW); // ACTIVE LOW LOGIC!!!
  else digitalWrite(7, HIGH);   
}
