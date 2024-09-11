<?php

    $servername = "localhost";

    // REPLACE with your Database name
    $dbname = "beetle";
    // REPLACE with Database user
    $username = "root";
    // REPLACE with Database user password
    $password = "";

    // Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
    // If you change this value, the ESP32 sketch needs to match
    $api_key_value = "tPmAT5Ab3j7F9";

    $api_key = $state = $value = $speed = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $api_key = test_input($_POST["api_key"]);
        if($api_key == $api_key_value) {
            $state = test_input($_POST["state"]);
            $value = test_input($_POST["value"]);
            $speed = test_input($_POST["speed"]);
            
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            
            $sql = "INSERT INTO Rover_Instruction (state, value, speed)
            VALUES ('" . $state . "', '" . $value . "', '" . $speed . "')";
            
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } 
            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        
            $conn->close();
        }
        else {
            echo "Wrong API Key provided.";
        }

    }
    else {
        echo "No data posted with HTTP POST.";
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $action = test_input($_GET["action"]);
        if ($action == "outputs_state") {
            $board = test_input($_GET["board"]);
            $result = getAllOutputStates($board);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $rows[$row["gpio"]] = $row["state"];
                }
            }
            echo json_encode($rows);
            $result = getBoard($board);
            if($result->fetch_assoc()) {
                updateLastBoardTime($board);
            }
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html> 
<html>
    <style>
        html * {
            font-family: Monospace; 
        }
    </style>
</html>


