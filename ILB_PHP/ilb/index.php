<?php

    session_start();

    $upload_msg = '';

    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] == 1) {

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                
                if (isset($_POST['logout'])) {
                    session_unset();
                    session_destroy();
                    header("location: ../");
                }

                else {
                    if (isset($_POST['upload'])) {



                        $target_dir = "images/";
                        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                        // Check if image file is a actual image or fake image
                        if(isset($_POST["submit"])) {
                            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                            if($check !== false) {
                                $upload_msg =  "<p style='text-align: center;'>File is an image - " . $check["mime"] . ".</p>";
                                $uploadOk = 1;
                            } else {
                                $upload_msg =  "<p style='text-align: center;'>File is not an image.</p>";
                                $uploadOk = 0;
                            }
                        }
                        // Check if file already exists
                        if (file_exists($target_file)) {
                            $upload_msg =  "<p style='text-align: center;'>Sorry, file already exists.</p>";
                            $uploadOk = 0;
                        }
                        // Check file size
                        if ($_FILES["fileToUpload"]["size"] > 500000) {
                            $upload_msg =  "<p style='text-align: center;'>Sorry, your file is too large.</p>";
                            $uploadOk = 0;
                        }
                        // Allow certain file formats
                        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                        && $imageFileType != "gif" ) {
                            $upload_msg =  "<p style='text-align: center;'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
                            $uploadOk = 0;
                        }
                        // Check if $uploadOk is set to 0 by an error
                        if ($uploadOk == 0) {
                            $upload_msg =  "<p style='text-align: center;'>Sorry, your file was not uploaded.</p>";
                        // if everything is ok, try to upload file
                        } else {
                            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                                $upload_msg =  "<p style='text-align: center;'>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</p>";

                                // insert data to the image table
                                $image_filename = basename( $_FILES["fileToUpload"]["name"]);
                                $image_course = $_SESSION["course"];

                                $conn = new mysqli('localhost', 'root', '', 'ilb');

                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                $sql = "INSERT INTO image (name, course)
                                        VALUES ('$image_filename', '$image_course')";

                                if ($conn->query($sql) === TRUE) {
                                    echo "New record created successfully";
                                } else {
                                    echo "Error: " . $sql . "<br>" . $conn->error;
                                }

                                $conn->close();

                            } else {
                                $upload_msg =  "<p style='text-align: center;'>Sorry, there was an error uploading your file.</p>";
                            }
                        }

                    }
                }
                
            }

        }
        else {
            header("location: ../");
        }
    }
    else {
        header("location: ../");
    }

?>
<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="app.css">
    <script src="jQuery v3.4.1.min.js"></script>
    <script src="app.js"></script>
</head>
<html>
    <body onload="init()">
        <center>
            <canvas id="can" style="position:absolute;top:0;left:0;"></canvas>
        </center>

        <div style="padding:10px; width:2.8rem; position:relative; top:.5rem;  float:right; background:lightgrey; height:25rem;z-index:2;">
            <div class="choose-color">Pen<br>Color</div>
            <div class="green" id="green" onclick="color(this)"></div>
            <div class="blue" id="blue" onclick="color(this)"></div>
            <div class="red" id="red" onclick="color(this)"></div>
            <div class="yellow" id="yellow" onclick="color(this)"></div>
            <div class="orange" id="orange" onclick="color(this)"></div>
            <div class="black" id="black" onclick="color(this)"></div>

            <div class="eraser">Eraser</div>
            <div class="eraser-button" id="white" onclick="color(this)"></div>
        </div>

        <!-- <img id="canvasimg" style="position:absolute;top:10%;left:52%;display:none;"> -->

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
            <!-- buttons -->
            <input type="button" value="Save" id="btn" size="30" onclick="save()" style="position:absolute;bottom:1rem;left:1rem;width:6rem;padding:.5rem;font-weight: bolder;color:white; border:none;background:teal;" name="submit">
            <input type="button" value="Clear" id="clr" size="23" onclick="erase()" style="position:absolute;bottom:1rem;left:8rem;width:6rem;padding:.5rem;font-weight: bolder;color:white; border:none;background:red;">


            <input type="submit" value="Logout" id="clr" size="23" style="position:absolute;bottom:1rem;right:1rem;width:6rem;padding:.5rem;font-weight: bolder;color:white; border:none;background:red;" name="logout">

            <div style="padding: 0.5rem; background:white; height:4rem;position:relative;color:teal;">
                <img src="logo.png" style="width:4rem;"/>
                <h1 style="transform:translateY(-170%);padding:0;margin:0;margin-left:4.5rem;">Colegio De Montalban</h1>
                <p style="transform:translateY(-450%);margin-left:9rem;"><small>Interactive Lecture Board</small></p>
            </div>
        </form>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data" style="bottom:1rem;position:absolute;right:10rem;">
            <span><?php echo $upload_msg;?></span>
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="upload">
            
        </form>
    </body>
</html>