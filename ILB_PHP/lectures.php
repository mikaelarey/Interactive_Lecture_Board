<?php

session_start();

// echo $_SESSION['name'];

if (isset($_SESSION['username']) && isset($_SESSION['role']) && isset($_SESSION['student_id'])) {
	echo $_SESSION['username'];
	echo $_SESSION['role'];
	echo $_SESSION['student_id'];

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		// session_unset();
		// session_destroy();
		// header("location: index.php");
		if (isset($_POST['try'])) {
			// header("location: file:///home/rey/Downloads");
			copy('file:///home/rey/Downloads/canvas-image%20(4).png', 'test-copy.png');
		}
	}
}

else {
	session_unset();
	session_destroy();
	header("location: index.php");
	// echo "Sorry something went wrong";
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Lectures</title>
</head>
<body>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
		<button type="submit" name="submit">Logout</button>
	</form>

	<?php

		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "ilb";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT * FROM image";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		        echo '<img style="width:10rem;height:10rem;border:1px solid black;" src="ilb/images/' . $row['name'] . '"><br>';
		        echo "File name: <br>";
		        echo '<a href="ilb/images/' . $row['name'] . '" download>' . $row['name']  . '</a><br><br>';
		    }
		} else {
		    echo "0 results";
		}
		$conn->close();

	?>


	
</body>
</html>