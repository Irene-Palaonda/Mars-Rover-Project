
<!DOCTYPE html>
<html>

    <style>
        html * {
            font-family: Monospace; 
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

        .map {
            float: center; 
            display: block;
            margin: 20px auto; 
        }

    </style>

    <h1 class = "title"; style="font-size:45px;">
        Mars Beetle Control Panel
    </h1>

    <div class="topnav">
        <a class="home" href="home.php">Home</a>
        <a class="refresh" href="home.php">
            <img src="refresh.jpg" alt="Refresh Logo" style="float:left;width:20px;height:20px;">
        </a>
        <a href="optical.php">Optical Data</a>
        <a href="vision.php">Vision Data</a>
        <a href="radar.php">Radar Data</a>
        <a href="instr.php">Rover Instruction Data</a>
    </div>

    <div>
        <iframe src="home/map.php" frameborder="0" width="1200px" height="600px" class = "map"></iframe>
    </div>

    <p id = 'x' >
    </p>

    <p id = 'y' >
    </p>

    <script>
    </script>

</html>
