



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

    <script>

        var btn_flag = false;
        let slider, speed; 

        function reset_bg(){
            push();
            strokeWeight(4); 
            //mars map bg
            stroke('#bcbcbc'); 
            fill(221,221,221);
            rect(2, 2, 396, 196);   
        }
        
        function press_within(sx, ex, sy, ey) {
            if((mouseIsPressed == true) && (sx <= mouseX) && (mouseX <= ex) && (sy <= mouseY) && (mouseY <= ey)) {
                return true;
            } else {
                return false; 
            }
        }

        function draw_wasd(x, y, sx, sy, letter, hover) {
            push();
            strokeWeight(2);
            if (hover) {
                stroke('#dda87b');
                fill('#b18561');
            } else {
                stroke(188); 
                fill('#848484');
            }
            rect(x, y, 50, 50, 3);  
            noStroke();
            textSize(32);
            fill(255);
            textStyle(BOLD);
            textAlign(CENTER, CENTER);
            text(letter, x + 25, y + 26);
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
            rect(60, 140, 160, 40, 3); 
            stroke('#848484');
            fill(flag ? '#53dd89' : '#eb7470');  
            circle(30, 160, 20);
            noStroke();
            textSize(18);
            fill(255);
            textStyle(BOLD);
            textAlign(CENTER, CENTER);
            text(mode, 140, 160);
            pop();
        }

        function draw_diagram(press_w, press_a, press_s, press_d) {
            push(); 
            rect(250, 20, 105, 105, 3); 
            if (btn_flag) {
                imageMode(CENTER);
                image(press_w ? f : 
                press_a ? l : 
                press_s ? b : 
                press_d ? r : 
                stop , 302, 72, 60, 60);
                textAlign(CENTER); 
                textSize(30);
                stroke('#848484');
                strokeWeight(2);
                fill('#848484');
                textStyle(BOLD);
                text(press_a ? "L" : press_d ? "R" : "", 302, 80);
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
            text(speed, 360, 170);
            pop();
        }
        
        function preload() {
            //preloads elements
            f = loadImage("assets/f.png");
            b = loadImage("assets/b.png");
            l = loadImage("assets/l.png");
            r = loadImage("assets/r.png");
            stop = loadImage("assets/stop.png");
        }

        function setup() {
            //setup function for the canvas that runs once 
            createCanvas(400, 200);
            //high pull rate from keyboard
            frameRate(90);
            angleMode(RADIANS); 
            textFont('monospace');
            //create slider object
            slider = createSlider(0, 100, 60, 5); // min:0 max:100 default:60 incr:5
            slider.position(240, 155); 
            slider.addClass("slider"); //used to style it in CSS
        } 

        function draw() { 
            //the function that loops and draws on the canvas 
            reset_bg();
            let press_w = press_within(100, 150, 20, 70) || (keyIsPressed && key == 'w'); 
            let press_a = press_within(60, 110, 75, 125) || (keyIsPressed && key == 'a');
            let press_s = press_within(117, 167, 75, 125)|| (keyIsPressed && key == 's');
            let press_d = press_within(174, 224, 75, 125)|| (keyIsPressed && key == 'd');
            draw_wasd(100, 20, 50, 50, "W", press_w);
            draw_wasd(60, 75, 50, 50, "A", press_a);
            draw_wasd(117, 75, 50, 50, "S", press_s);
            draw_wasd(174, 75, 50, 50, "D", press_d);
            
            draw_button(btn_flag);

            draw_diagram(press_w, press_a, press_s, press_d);

            if(!btn_flag){
                slider.hide();
            } else {
                slider.show();
                draw_slider(String(slider.value()));
            }
            
        }

        function mouseClicked() {
            if ((60 <= mouseX) && (mouseX <= 220) && (140 <= mouseY) && (mouseY <= 180)){
                btn_flag = !btn_flag;
            }
        }


    </script>

</html>