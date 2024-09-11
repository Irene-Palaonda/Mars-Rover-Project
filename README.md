# Balancing-Rover-Project

This design details an autonomous Mars rover capable of mapping an unknown arena. The rover makes use of a suite of sensors to determine its position with respect to the arena walls and a camera for detection of six ‘aliens’ that must be identified. The rover integrates several subsystems which interface to a main microcontroller that controls the rover’s movement.

![image01](https://github.com/user-attachments/assets/5b5e0043-df38-457b-ab37-8a7bfcab0b7e)
![image 02](https://github.com/user-attachments/assets/8f1c7e12-aec5-42cb-923b-378af9d75894)

# Scenario & Objectives

The scenario is an alien colony on Mars. The rover is placed into an arena with randomly arranged props for ‘aliens’ and their structures. The aliens are six differently coloured table-tennis balls on stands while the structures include several vertically striped black and white ‘buildings’, as well as one spinning radar reflector underneath the arena. The arena is walled on all sides with the Martian landscape and is open at the top.

The rover provided as built from the starter kit is a vehicle with two driven wheels. An optical flow sensor is mounted at the rear and a camera is mounted facing forwards. A Doppler radar module is mounted facing rearwards and angled towards the ground.
The rover must be able to…
• locate all six ‘aliens’ and the one underground fan.
• avoid buildings, aliens, and the walls of the arena.
• return to its original position once the exploration is complete.
• communicate with a base station to send data and receive commands if necessary.

# High Level Design

<img width="920" alt="image" src="https://github.com/user-attachments/assets/ce0a04d3-ea67-4942-90bf-8b3ad3c0ea79">

- The Power Subsystem aims to maximize energy harvested from the solar cells using switch mode power supplies to charge the battery bank.
- The Drive Subsystem provides a platform to accurately translate and rotate the rover by using a proportional controller and optical flow sensor.
- The Control Subsystem incorporates the other systems, executes the search algorithm, sends data to the server, and receives commands from the web app.
- The Command Subsystem deploys a web app that displays the information sent to the database and send commands to the rover using a web socket.
- The Radar Subsystems uses the radar sensor and various amplifiers and filters to detect underground vents (fans).
- The Vision Subsystem captures and processes information of aliens by using colour conversion, bounding boxes then outputs to the ESP32 using UART.

