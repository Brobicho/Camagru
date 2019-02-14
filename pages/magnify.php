<?php
    require_once("../config/db_connect.php");
    session_start();

if (isset($_SESSION['id']))
{



?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta content="Webcam Stream" name="title">
<link rel="stylesheet" type="text/css" href="../css/mgy.css">
<title>Webcam Stream</title>

<body>
    <div class="row">
        <div class="filters col-2">
            <img src="../img/testcrown.png" id="crown_pic" class="pic" onclick="selectCrown()">
            <br/>
            <br/>
            <img src="../img/blood.svg" id="blood_pic" class="pic" onclick="selectBlood()">
            <img src="../img/glasses.png" id="glasses_pic" class="pic" onclick="selectGlasses()">
        </div>
        <div class="col-8">
            <video id="video" class="video" autoplay></video>
            <canvas class="crown" id ="crown_canvas"></canvas>
            <canvas class="blood" id ="blood_canvas"></canvas>
            <canvas class="glasses" id ="glasses_canvas"></canvas>
        </div>
        <div class="col-2"><form action="../scripts/logout.php">
                <button type="submit" value="submit">
                    Déconnexion
                </button>
            </form>
            <form action="../index.php">
                <button type="submit" value="submit">
                    Menu principal
                </button>
            </form></div>
    </div>
    <div class="row">

        <div class="mini col-8">
            <button class="snap" id="snap">Smile</button>
            <label for="upload" class="label-file" id="up">Upload</label>
            <input type="file" class="upload" id="upload">
            <canvas class="canvas" id="canvas"></canvas>
            <canvas class="canvas2" id="canvas2"></canvas>
            <canvas class="canvas3" id="canvas3"></canvas>
            <p class="legend">Image Custom</p>
            <canvas class="canvas_up" id="canvas_up"></canvas>
        </div>
    </div>

    <script type="text/javascript">


   /* CREATE XHR OBJECT */


    function getXMLHttpRequest() {
        var xhr = null;
        if (window.XMLHttpRequest || window.ActiveXObject) {
            if (window.ActiveXObject) {
                try {
                    xhr = new ActiveXObject("Msxml2.XMLHTTP");
                } catch(e) {
                    xhr = new ActiveXObject("Microsoft.XMLHTTP");
                }
            } else {
                xhr = new XMLHttpRequest();
            }
        } else {
            alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
            return null;
        }
        return xhr;
    }

   /* FILTER PREVIEW SECTION */

   // Crown is selected

   function selectCrown() {
       document.getElementById("snap").style.backgroundColor = "#53af57";
       document.getElementById("up").style.backgroundColor = "#53af57";
       if (select === 1)
           crownContext.clearRect(0, 0, 5000, 5000);
       if (select === 2)
           bloodContext.clearRect(0, 0, 5000, 5000);
       else if (select === 3)
           glassesContext.clearRect(0, 0, 5000, 5000);
       crownContext.drawImage(crown_pic, 0, 0, video.videoWidth*0.2, video.videoHeight*0.2);
       select = 1;
       active = 1;
   }


   // Scar is selected

   function selectBlood() {
       document.getElementById("snap").style.backgroundColor = "#53af57";
       document.getElementById("up").style.backgroundColor = "#53af57";
       if (select === 2)
           bloodContext.clearRect(0, 0, 5000, 5000);
       if (select === 1)
           crownContext.clearRect(0, 0, 5000, 5000);
       else if (select === 3)
           glassesContext.clearRect(0, 0, 5000, 5000);
       bloodContext.drawImage(blood_pic, 0, 0, video.videoWidth*0.2, video.videoHeight*0.2);
       select = 2;
       active = 1;
   }


   // Glasses are selected

   function selectGlasses() {
       document.getElementById("snap").style.backgroundColor = "#53af57";
       document.getElementById("up").style.backgroundColor = "#53af57";
       if (select === 3)
           glassesContext.clearRect(0, 0, 5000, 5000);
       if (select === 1)
           crownContext.clearRect(0, 0, 5000, 5000);
       else if (select === 2)
           bloodContext.clearRect(0, 0, 5000, 5000);
       glassesContext.drawImage(glasses_pic, 0, 0, video.videoWidth*0.2, video.videoHeight*0.2);
       select = 3;
       active = 1;
   }


    /* CAM ACCESS SECTION */

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
        try {
            video.srcObject = stream;
        } catch (error) {
            video.src = window.URL.createObjectURL(stream); }
        video.play(); });
    }

    /* VAR SECTION */

    let pictaken = 0;                                       // canvas index
    let select = 0;                                         // filter index
    var canvas = document.getElementById('canvas');
    var canvas2 = document.getElementById('canvas2');
    var canvas3 = document.getElementById('canvas3');

    var video = document.getElementById('video');

    var blood_pic = document.getElementById('blood_pic'); 
    var crown_pic = document.getElementById('crown_pic');
    var glasses_pic =document.getElementById('glasses_pic'); 

    var crown_canvas = document.getElementById('crown_canvas');  
    var blood_canvas = document.getElementById('blood_canvas');
    var glasses_canvas = document.getElementById('glasses_canvas');

    var context = canvas.getContext('2d');
    var context2 = canvas2.getContext('2d');
    var context3 = canvas3.getContext('2d');
    var crownContext = crown_canvas.getContext('2d');
    var bloodContext = blood_canvas.getContext('2d'); 
    var glassesContext = glasses_canvas.getContext('2d');
    var url;
    var active = 0;                                        // no filter check


    /* UPLOAD SECTION */

    function drawSendXhr(canv) {
        url = canv.toDataURL("image/png");
        var xhr = getXMLHttpRequest();
        xhr.open("POST", "../scripts/upload.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("img=" + url);
    }

    // Custom upload

    document.getElementById("upload").addEventListener('change', function() {
        if (this.files[0] != null && select != null && select > 0 &&
            (this.files[0].name.split('.').pop() === "jpg" || this.files[0].name.split('.').pop() === "png")) {

        var fileReader = new FileReader();
        
        fileReader.onload = function(){
            img = new Image();
            img.onload = function () {
                var canvas_up = document.getElementById("canvas_up");
                var ctx = canvas_up.getContext("2d");
                ctx.drawImage(img, 0, 0);
                if (select === 1)
                    drawCrown(ctx);
                else if (select === 2)
                    drawScar(ctx);
                else
                    drawGlasses(ctx);
                drawSendXhr(canvas_up);
            };
            img.src = fileReader.result;
        };
        fileReader.readAsDataURL(this.files[0]);
      }
    });


    /* DRAW SECTION */

    function drawCrown(context) {
        context.drawImage(crown_pic, canvas.width*0.35, 0, canvas.width*0.3, canvas.height*0.3);
    }
    function drawScar(context) {
        context.drawImage(blood_pic, canvas.width*0.41, canvas.height*0.28, canvas.width*0.13, canvas.height*0.13);
    }
    function drawGlasses(context) {
            context.drawImage(glasses_pic, canvas.width*0.37, canvas.height*0.28, canvas.width*0.3, canvas.height*0.3);
    }

    /* TRIGGER PHOTO SNAP */

    document.getElementById("snap").addEventListener("click", function() {

        if (!active) {
            document.getElementById("upload").disable;
            document.getElementById("snap").disable;
        }
        else {                                                  // Check filter select

        if (!pictaken || pictaken > 2) {
                context.drawImage(video, 0, 0, video.videoWidth, video.videoHeight, 0, 0, canvas.width, canvas.height);
            if (select === 1)
                drawCrown(context);
            else if (select === 2)
                drawScar(context);
            else if (select === 3)
                drawGlasses(context);
            drawSendXhr(canvas);
        }
        if (pictaken === 1) {
                context2.drawImage(video, 0, 0, video.videoWidth, video.videoHeight, 0, 0, canvas2.width, canvas2.height);
            if (select === 1)
                drawCrown(context2);
            else if (select === 2)
                drawScar(context2);
            else if (select === 3)
                drawGlasses(context2);
            drawSendXhr(canvas2);
        }
        if (pictaken === 2) {
            context3.drawImage(video, 0, 0, video.videoWidth, video.videoHeight, 0, 0, canvas3.width, canvas3.height);
            if (select === 1)
                drawCrown(context3);
            else if (select === 2)
                drawScar(context3);
            else if (select === 3)
                drawGlasses(context3);
            drawSendXhr(canvas3);
    }
    pictaken++;
    if (pictaken > 2)
        pictaken = 0;
    }

});
    </script>
</body>
</html><?php
}

else {
    echo "Vous n'êtes pas connecté. Redirection vers le menu principal...\n";
    header('refresh:3;url=../index.php', TRUE, 401); }
?>