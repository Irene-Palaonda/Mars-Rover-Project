



<!DOCTYPE html>
<html> 

    <style>
        canvas {
            display: block;
            position: absolute; 
            left: 0;
            top: 0;
        }

        .slider {
            width: 80px;
            background-color: transparent;
            -webkit-appearance: none;
        }  

        .slider:focus {
            outline: none;
        }
        
        .slider::-webkit-slider-runnable-track {
            background: #848484;
            height: 3px;
            -webkit-appearance: none;
        }

        .slider::-webkit-slider-thumb {
            width: 15px;
            height: 15px;
            background: #b18561;
            cursor: pointer;
            -webkit-appearance: none;
            margin-top: -6px;
            border-radius: 3px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/p5@1.4.1/lib/p5.js"></script>

    <?php

        //header("Refresh:1");

        $servername = "localhost";

        // REPLACE with your Database name
        $dbname = "testdb";
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

        $sql = "SELECT id, value1,value2, reading_time FROM sensordata ORDER BY id DESC";
        
        $total_x = array(); 
        $total_y = array();
        if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                array_push($total_x, $row["value1"]);
                array_push($total_y, $row["value2"]);
            }
            $result->free();
        }
        
        $conn->close();
    ?>

    <script>

        function reset_bg(){
            push();
            strokeWeight(4); 
            //mars map bg
            stroke('#744221'); 
            fill(173,105,64);
            rect(2, 2, 796, 596); 
            stroke('#bcbcbc'); 
            fill(221,221,221);
            rect(802, 402, 396, 196);   
            stroke(116,66,33); 
            fill('#eedecb');
            rect(800, 2, 398, 396);
            pop();
        }
        
        function press_within(sx, ex, sy, ey) {
            if((mouseIsPressed == true) && (sx <= mouseX) && (mouseX <= ex) && (sy <= mouseY) && (mouseY <= ey)) {
                return true;
            } else {
                return false; 
            }
        }

        function within(sx, ex, sy, ey) {
            if((sx <= mouseX) && (mouseX <= ex) && (sy <= mouseY) && (mouseY <= ey)) {
                return true;
            } else {
                return false; 
            }
        }

        function alien_draw(x, y, colour) { 
            push(); //saves original brush settings 
            noStroke();
            radius = 33; // size of the alien 
            fill(colour); //sets the colour of the circle element 
            circle(x, y, radius); //draw da alien 
            pop(); //restore original brush settings
        }

        function line_draw(ix, iy, dx, dy) { 
            push(); //saves original brush settings 
            strokeWeight(2); 
            stroke(0); 
            line(ix, iy, dx, dy); 
            pop(); //restore original brush settings
        }

        function point_draw(x, y) {
            push(); 
            strokeWeight(4); 
            stroke('yellow'); 
            point(x, y); 
            pop(); 
        }

        function cor_draw(x, y) {
            push();
            cor = "(" + String(x) + ", " + String(y) + ")"; 
            stroke(0);
            strokeWeight(0); 
            textSize(15);
            fill(0);
            textStyle(BOLD); 
            text(cor, x + 10, y + 10);
            pop();
        }

        function construct_map() {
            push();
            //draw lines
            noFill();
            let angle = 0, x = 0, y = 0; 
            for (let i = 0; i < rover_translate.length; i++) {
                if (rover_translate[i] == 0) {
                    angle += rover_rotate[i];
                } else {
                    rmov = p5.Vector.fromAngle( - radians(angle), rover_translate[i]);
                    strokeWeight(2);
                    stroke('#000000');
                    line(x, y, x + rmov.x, y + rmov.y);
                    strokeWeight(4);
                    stroke('#ffff00');
                    point(x, y); 
                    point()
                    x += rmov.x; 
                    y += rmov.y; 
                }
            }
            //draw aliens 
            stroke(0);
            strokeWeight(6);
            for (let i = 0; i < aliens.length; i++){
                alien_draw(aliens[i][0], aliens[i][1], aliens[i][2]);
            }
            //putting the beetle image in front
            translate(x, y);
            rotate(PI*3/2 - radians(angle));
            imageMode(CENTER);
            image(beetle, 0, 0, 40, 40);
            pop();
        }

        function construct_detailed_map() {
            push();
            //draw lines
            noFill();
            let angle = 0, x = 0, y = 0; 
            for (let i = 0; i < rover_translate.length; i++) {
                if (rover_translate[i] == 0) {
                    angle += rover_rotate[i];
                } else {
                    rmov = p5.Vector.fromAngle( - radians(angle), rover_translate[i]);
                    strokeWeight(2);
                    stroke('#000000');
                    line(x, y, x + rmov.x, y + rmov.y);
                    strokeWeight(4);
                    stroke('#ffff00');
                    point(x, y); 
                    point()
                    x += rmov.x; 
                    y += rmov.y; 
                }
            }
            strokeWeight(6);
            stroke('#ff0000');
            point(x, y);
            cor_draw(parseInt(x), parseInt(y));
            //draw aliens 
            stroke('#000000');
            strokeWeight(6);
            for (let i = 0; i < aliens.length; i++){
                alien_draw(aliens[i][0], aliens[i][1], aliens[i][2]);
                cor_draw(Number(aliens[i][0]), Number(aliens[i][1]));
                point(Number(aliens[i][0]), Number(aliens[i][1]));
            }
            pop();
        }

        function draw_wasd(x, y, letter, hover) {
            push();
            strokeWeight(2);
            if (hover) {
                stroke('#dda87b');
                fill('#b18561');
            } else {
                stroke(188); 
                fill('#848484');
            }
            rect(x + 775, y + 375, 50, 50, 3);  
            noStroke();
            textSize(32);
            fill(255);
            textStyle(BOLD);
            textAlign(CENTER, CENTER);
            text(letter, x + 800, y + 401);
            pop();
        }

        function draw_button(flag) {
            push();
            strokeWeight(2);
            if (flag) {
                stroke('#dda87b');
                fill('#b18561');
                mode = "Manual Control"; 
            } else {
                stroke(188); 
                fill('#848484');
                mode = "Automatic"; 
            }
            rect(835, 515, 160, 40, 3); 
            stroke('#848484');
            fill(flag ? '#53dd89' : '#eb7470');  
            circle(805, 535, 20);
            noStroke();
            textSize(18);
            fill(255);
            textStyle(BOLD);
            textAlign(CENTER, CENTER);
            text(mode, 915, 535);
            pop();
        }

        function draw_diagram(press_w, press_a, press_s, press_d) {
            push(); 
            strokeWeight(4);
            stroke('#bcbcbc'); 
            fill(221,221,221);
            rect(1025, 395, 105, 105, 3); 
            if (btn_flag) {
                imageMode(CENTER);
                image(press_w ? f : 
                press_a ? l : 
                press_s ? b : 
                press_d ? r : 
                stop , 1077, 447, 60, 60);
                textAlign(CENTER); 
                textSize(30);
                stroke('#848484');
                strokeWeight(2);
                fill('#848484');
                textStyle(BOLD);
                text(press_a ? "L" : press_d ? "R" : "", 1077, 455);
            }
            pop();
        }

        function draw_slider(speed) {
            push();
            noStroke();
            fill('#848484');
            textSize(30);
            textStyle(BOLD);
            textAlign(CENTER);
            text(speed, 1135, 545);
            pop();
        }

        function control_panel() {
            push();
            //let press_w = press_within(900, 950, 420, 470) || (keyIsPressed && key == 'w'); 
            //let press_a = press_within(860, 910, 475, 525) || (keyIsPressed && key == 'a');
            //let press_s = press_within(917, 967, 475, 525)|| (keyIsPressed && key == 's');
            //let press_d = press_within(974, 1024, 475, 525)|| (keyIsPressed && key == 'd');
            draw_wasd(100, 20, "W", press_w);
            draw_wasd(60, 75, "A", press_a);
            draw_wasd(117, 75,  "S", press_s);
            draw_wasd(174, 75, "D", press_d);
            
            draw_button(btn_flag);

            draw_diagram(press_w, press_a, press_s, press_d);

            if(!btn_flag){
                slider.hide();
            } else {
                slider.show();
                draw_slider(String(slider.value()));
            }
            pop();
        }

        function draw_angle_info(angle, speed, curdur, fulldur) {
            push(); 
            let vangle = p5.Vector.fromAngle(- radians(angle), 50);
            let vspeed = p5.Vector.fromAngle(PI + (speed / 100 * PI), 50);

            //draw angle sphere
            strokeWeight(2);
            stroke('#744221'); 
            fill('#eedecb');
            circle(860, 50, 100);
            circle(860, 50, 50);
            line(860, 0, 860, 100);
            line(810, 50,910, 50);
            strokeWeight(6);
            point(860, 50); 
            strokeWeight(3);
            noFill();
            stroke('#fe6e00');
            line(860, 50, vangle.x + 860 , vangle.y + 50); 

            //draw speed arc
            noFill();
            strokeWeight(2);
            stroke('#744221'); 
            strokeCap(SQUARE);
            arc(860, 210, 100, 100, PI, 0);
            strokeWeight(15); 
            stroke('#fe6e00');
            point(860 + vspeed.x, 210 + vspeed.y);

            //draw duration bar
            noStroke();
            let bg = color('#744221')
            bg.setAlpha(130);
            fill(bg);
            rect(810, 290, 100, 20);
            fill('#fe6e00');
            rect(810, 290, 100 * (curdur / fulldur), 20); 

            //draw angle text
            noStroke();
            fill('#744221');
            textStyle(BOLD);
            textAlign(LEFT,CENTER);
            textSize(32);
            let reada = "Angle:" + String(angle) + "°";
            text(reada, 940, 50);

            //draw speed text
            let reads = "Speed:" + String(speed);
            text(reads, 940, 185);

            //draw duration text
            let readd = String(curdur) + "/" + String(fulldur);
            text(readd, 940, 300);
            pop(); 
        }

        //acquire sensor data reading from php segment i.e. sql
        const sensor_total_x = <?php echo json_encode($total_x); ?>;
        const sensor_total_y = <?php echo json_encode($total_y); ?>;
        
        //applying scalar factor to fit the map
        const total_x = sensor_total_x.map(x => x * 3); 
        const total_y = sensor_total_y.map(x => x * 2); 
        //  document.getElementById("x").innerHTML = total_x.toString();
        //document.getElementById("y").innerHTML = total_y.toString();

        const rover_translate = [100, 0, 100, 0, 100, 0, 100, 0, 100];
        const rover_rotate =    [0, -60, 0, 60, 0, 120, 0, -120, 0]; 

        //record data as global variables
        var aliens = [];
        aliens.push(["300", "200", 'blue']);
        aliens.push(["400", "500", 'red']);

        //setup websocket
        var socket;
        

        var btn_flag = false;
        var press_w = press_a = press_s = press_d = false; 
        var conn = false; 
        var msg = ""; 
        let slider, speed; 

        function preload() {
            //preloads elements
            beetle = loadImage('assets/beetle.png'); 
            f = loadImage("assets/f.png");
            b = loadImage("assets/b.png");
            l = loadImage("assets/l.png");
            r = loadImage("assets/r.png");
            stop = loadImage("assets/stop.png");
            connected = loadImage("assets/connected.png");
        }
        
        function setup() {
            //setup function for the canvas that runs once 
            createCanvas(1200, 600);
            frameRate(90);
            angleMode(RADIANS); 
            textFont('monospace')
            //create slider object
            slider = createSlider(0, 100, 60, 5); // min:0 max:100 default:60 incr:5
            slider.position(1040, 565); 
            slider.addClass("slider"); //used to style it in CSS
            socket = new WebSocket('ws://192.168.43.108/test');
            socket.onopen = setOpen;
        } 

        function draw() { 
            //the function that loops and draws on the canvas 
            reset_bg();
            if (conn) {
                image(connected, 810, 410, 40, 40);
            }
            translate(30,30);
            if (press_within(0, 800, 0, 600)) {
                construct_detailed_map();
            } else {
                
                construct_map();
            }
            
            control_panel();

            draw_angle_info(45, slider.value(), 600, 1000);
        }

        function mouseClicked() {
            if ((860 <= mouseX) && (mouseX <= 1020) && (540 <= mouseY) && (mouseY <= 580)){
                btn_flag = !btn_flag;
            }
            //prevent default behaviour
            return false; 
        }

        function keyPressed() {
            if (key == 'w') {
                press_w = true; 
                send_command("w");
            } else if (key == 'a') {
                press_a = true; 
                send_command("a");
            } else if (key == 's') {
                press_s = true; 
                send_command("s");
            } else if (key == 'd') {
                press_d = true; 
                send_command("d");
            }
            return false; 
        }

        function keyReleased() {
            if (press_w || press_a || press_s || press_d) {
                press_w = false; 
                press_a = false; 
                press_s = false; 
                press_d = false; 
                send_command("n");
            }
            return false; 
        }

        function send_command(msg) {
            if (btn_flag) {
                socket.send(msg + ((String(slider.value()).length == 2) ? "0" : "") + String(slider.value()));
            }
        }

        function setOpen() {
            conn = true; 
        }

    </script>

</html>