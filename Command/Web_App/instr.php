<?php
?>

<!DOCTYPE html>
<html>

    <style>
        html * {
            font-family: monospace; 
        }

        body {
            background-image: url('https://mediaproxy.salon.com/width/1200/https://media.salon.com/2022/05/mars-canyons-0517221.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
        }

        .title {
            overflow: hidden;
            text-align: center;
            color: #eedecb;
            text-shadow: 1px 1px 10px #60a4a7, 1px 1px 10px #105859;
        }

        .topnav {
            overflow: hidden;
            background-color: #333;
        }

        .topnav a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

        .topnav a.home {
            background-color: #905027;
            color: white;
        }

        .topnav a.home:hover {
            background-color: #dda87b;
        }

        .topnav a.refresh {
            background-color: #A9A9A9;
            color: white;
        }

        .topnav a.refresh:hover {
            background-color: #dda87b;
        }

        .table_header {
            background-color: #905027;
            color: #f2f2f2;
            padding: 14px 16px;
            font-size: 30px;
            font-weight: bold;
            margin: 0;
        }

        table {
            width: 100%;
        }

        th {
            background-color: #d4996a;
            color: #f2f2f2;
            padding: 14px 16px;
            font-size: 20px;
        }

        tr {
            background-color: #f2f2f2;
            color: #333;
        }

        td {
            padding: 14px 16px;
            text-align: center;
            font-size: 18px;
        }

        tr:hover {
            background-color: #eedecb;
        }


    </style>

    <h1 class = "title", style="font-size:45px;">
        Mars Beetle Control Panel
    </h1>

    <div class="topnav">
        <a class="home" href="home.php">Home</a>
        <a class="refresh" href="instr.php">
            <img src="refresh.jpg" alt="Refresh Logo" style="float:left;width:20px;height:20px;">
        </a>
        <a href="optical.php">Optical Data</a>
        <a href="vision.php">Vision Data</a>
        <a href="radar.php">Radar Data</a>
        <a href="instr.php">Rover Instruction Data</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/p5@1.4.1/lib/p5.js"></script>

    <?php

        set_time_limit(0);

        //header("Refresh:1"); 

        $servername = "localhost";

        // REPLACE with your Database name
        $dbname = "beetle";
        // REPLACE with Database user
        $username = "root";
        // REPLACE with Database user password
        $password = "";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = "SELECT * FROM Rover_Instruction ORDER BY id DESC";

        $instr_data = array(); 
        $row_data = array();
        if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                array_push($row_data, $row["id"]);
                array_push($row_data, $row["state"]);
                array_push($row_data, $row["value"]);
                array_push($row_data, $row["speed"]);
                array_push($row_data, $row["reading_time"]);
                array_push($instr_data, $row_data);
                $row_data = array();
            }
            $result->free();
        }
        
        $conn->close();
    ?>


    <p id = "demo"></p>

    <p class = "table_header"> Rover Instruction Data SQL Table </p>
    <p id = "sqltable"></p>

    <script>
        //acquire sensor data reading from php segment i.e. sql
        var instr_data = <?php echo json_encode($instr_data); ?>;
        //document.getElementById("demo").innerHTML = optical_data.toString();

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        function formTable(myArray) {
            var result = "<table>";
            result += "<th> ID </th> <th> State </th> <th> Value </th> <th> Avg. Speed </th> <th> Timestamp </th>"
            for(var i=0; i<myArray.length; i++) {
                result += "<tr>";
                for(var j=0; j<myArray[i].length; j++){
                    result += "<td>"+myArray[i][j]+"</td>";
                }
                result += "</tr>";
            }
            result += "</table>";

            return result;
        }

        document.getElementById("sqltable").innerHTML = formTable(instr_data);

    </script>

</html>
