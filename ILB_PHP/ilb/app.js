var canvas, ctx, flag = false,
    prevX = 0,
    currX = 0,
    prevY = 0,
    currY = 0,
    dot_flag = false;

var x = "black",
    y = 2;
    
function init() {
    canvas = document.getElementById('can');
    ctx = canvas.getContext("2d");

    ctx.canvas.width  = window.innerWidth;
    ctx.canvas.height = window.innerHeight;
    w = ctx.canvas.width;
    h = ctx.canvas.height;

    // ctx.fillStyle = "white";
    // ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    canvas.addEventListener("mousemove", function (e) {
        findxy('move', e)
    }, false);

    canvas.addEventListener("mousedown", function (e) {
        findxy('down', e)
    }, false);

    canvas.addEventListener("mouseup", function (e) {
        findxy('up', e)
    }, false);

    canvas.addEventListener("mouseout", function (e) {
        findxy('out', e)
    }, false);
}
    
function color(obj) {
    switch (obj.id) {
        case "green":
            x = "green";
            break;
        case "blue":
            x = "blue";
            break;
        case "red":
            x = "red";
            break;
        case "yellow":
            x = "yellow";
            break;
        case "orange":
            x = "orange";
            break;
        case "black":
            x = "black";
            break;
        case "white":
            x = "white";
            break;
    }

    if (x == "white") y = 14;
    else y = 2;
    
}
    
function draw() {
    ctx.beginPath();
    ctx.moveTo(prevX, prevY);
    ctx.lineTo(currX, currY);
    ctx.strokeStyle = x;
    ctx.lineWidth = y;
    ctx.stroke();
    ctx.closePath();
}
    
function erase() {
    var m = confirm("Want to clear");
    if (m) {
        ctx.clearRect(0, 0, w, h);
        document.getElementById("canvasimg").style.display = "none";
    }
}
    
function save() {
    // document.getElementById("canvasimg").style.border = "2px solid";
    // var dataURL = canvas.toDataURL();
    // document.getElementById("canvasimg").src = dataURL;
    // document.getElementById("canvasimg").style.display = "inline";

    // var photo = canvas.toDataURL('image/jpeg');        
    // $.ajax({
    //   method: 'POST',
    //   url: 'photo_upload.php',
    //   data: {
    //     photo: photo
    //   }
    // }).done(function(o) {
    //    console.log('saved');
    // });


    // working but empty file
    // var dataURL = canvas.toDataURL();
    // $.ajax({
    //   type: "POST",
    //   url: "script.php",
    //   data: { 
    //      imgBase64: dataURL
    //   }
    // }).done(function(o) {
    //   console.log('saved'); 
    //   // If you want the file to be visible in the browser 
    //   // - please modify the callback in javascript. All you
    //   // need is to return the url to the file, you just saved 
    //   // and than put the image in your browser.
    // });


    // eto ung nagsasave sa download
    var today = new Date();
    var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var dateTime = date + '_' + time;

    const a = document.createElement("a");
    document.body.appendChild(a);
    a.href = canvas.toDataURL();
    a.download = dateTime + ".png";
    a.click();
    document.body.removeChild("a");
}
    
function findxy(res, e) {
    if (res == 'down') {
        prevX = currX;
        prevY = currY;
        currX = e.clientX - canvas.offsetLeft;
        currY = e.clientY - canvas.offsetTop;
    
        flag = true;
        dot_flag = true;
        if (dot_flag) {
            ctx.beginPath();
            ctx.fillStyle = x;
            ctx.fillRect(currX, currY, 2, 2);
            ctx.closePath();
            dot_flag = false;
        }
    }
    if (res == 'up' || res == "out") {
        flag = false;
    }
    if (res == 'move') {
        if (flag) {
            prevX = currX;
            prevY = currY;
            currX = e.clientX - canvas.offsetLeft;
            currY = e.clientY - canvas.offsetTop;
            draw();
        }
    }
}