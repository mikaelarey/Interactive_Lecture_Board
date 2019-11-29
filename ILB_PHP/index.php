<?php

session_start();

// session_unset();
// session_destroy();

if (!empty($_SESSION['username']) && isset($_SESSION['username'])) {

	if (!empty($_SESSION['role']) && isset($_SESSION['role'])) {
		if ($_SESSION['role'] == 1) {
			header("location: board.php");
		}
		else {

			header("location: lectures.php");
			
			// $student_id = $_SESSION['student_id'];
			// $conn = new mysqli('localhost', 'root', '', 'ilb');

			// // get the student id to fetch the student information
			// $sql = "SELECT * FROM students WHERE student_id = '$student_id'";
			// $result = $conn->query($sql);

			// if ($result->num_rows == 1) {
			// 	while ($row = $result->fetch_assoc()) {

			// 		$_SESSION['name'] = ucfirst(strtolower($row['firstname'])) . " " . ucfirst(strtolower($row['lastname']));

			// 		$_SESSION['course'] = $row['course'];
			// 	}

			// 	echo "redirect to lectures";
					
			// }
			// else {
			// 	header("location: login.php");
			// 	// echo "lectures 4";
			// }

			// echo "lectures";
			
		}
	}
	else {
		header("location: login.php");
		// echo "lectures 2";
	}

}

else {

	header("location: login.php");
	// echo "lectures 3";

}


?>