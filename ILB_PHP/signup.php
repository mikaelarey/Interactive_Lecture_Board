<?php

session_start();

$student_id = '';
$firstname 	= '';
$lastname 	= '';
$username 	= '';
$password 	= '';

$error 		= '';
$error_container = '';

if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {

	header("location: index.php");

}

else {

	$conn = new mysqli('localhost', 'root', '', 'ilb');

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT * FROM course";
	$courses = $conn->query($sql);

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$student_id = trim($_POST['student_id']);
		$firstname 	= trim($_POST['firstname']);
		$lastname 	= trim($_POST['lastname']);
		$username 	= trim($_POST['username']);
		$password 	= trim($_POST['password']);
		$course_id 	= trim($_POST['course']);

		if (empty($student_id) || empty($firstname) || empty($lastname) || empty($username) || empty($password) || $course_id == 0) {

			if (empty($student_id)) {
				$error .= '* Please provide your student id <br>';
			}

			if (empty($firstname)) {
				$error .= '* Please provide your first name <br>';
			}

			if (empty($lastname)) {
				$error .= '* Please provide your last name <br>';
			}

			if (empty($username)) {
				$error .= '* Please provide your username <br>';
			}

			if (empty($password)) {
				$error .= '* Please provide your password <br>';
			}

			if ($course_id == 0) {
				$error .= '* Please select your course. <br>';
			}

			$error_container = 'p-3 text-white bg-danger mb-3';

		}
		else {

			$error_container = '';

			$sql = "SELECT * FROM students WHERE student_id = '$student_id'";
			$student = $conn->query($sql);

			if ($student->num_rows > 0) {

				// student exists
				$error .= '* The student number that you are trying to register is already exists.';

			}
			else {

				// check if user exists
				$sql = "SELECT * FROM users WHERE username = '$username' OR student_id = '$student_id'";
				$user = $conn->query($sql);

				if ($user->num_rows > 0) {

					// user exists
					$error .= '* The username that you are trying to register is already exists.';

				}
				else {

					$password = password_hash($password, PASSWORD_DEFAULT);

					// insert data to users table
					$sql = "INSERT INTO users (student_id, username, password)
							VALUES ('$student_id', '$username', '$password')";

					if ($conn->query($sql) === TRUE) {
					    
						// insert data to students table
						$sql = "INSERT INTO students (student_id, firstname, lastname, course)
							VALUES ('$student_id', '$firstname', '$lastname', '$course_id')";

						if ($conn->query($sql) === TRUE) {
							echo "alert('Successfully Registered!');";
							header("location: login.php");
						}
						else {
						    $error .= "Error: " . $sql . "<br>" . $conn->error;
						    $error_container = 'p-3 text-white bg-danger mb-3';
						}
					} 
					else {
					    $error .= "Error: " . $sql . "<br>" . $conn->error;
					    $error_container = 'p-3 text-white bg-danger mb-3';
					}

					$conn->close();
				}
			}

		}

	}

}

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="bootstrap.css">
	<link rel="icon" href="favicon.ico">
	<title>Register</title>
</head>
<body class="bg-info">
	<div class="row m-5 p-5">
		<div class="col-lg-8 col-md-12 m-auto p-3">
			<form class="shadow p-3 bg-white" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

				<img src="logo.png" style="width:10rem;" class="m-auto d-block">
				<small class="d-block text-center text-info"><b>Interactive Lecture Board</b></small>

				<h5 class="text-center m-3">Register</h5>
				<div class="row">

					<div class="col-lg-12">
						<div class="<?php echo $error_container; ?>" style="opacity:0.5;border-radius:0.5rem;">
							<span>
								<?php echo $error; ?>
							</span>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="form-group">
							<small>Student ID</small>
							<input type="text" name="student_id" class="form-control" placeholder="Enter your student ID" value="<?php echo $student_id; ?>">
						</div>

						<div class="form-group">
							<small>First name</small>
							<input type="text" name="firstname" class="form-control" placeholder="Enter your first name" value="<?php echo $firstname; ?>">
						</div>

						<div class="form-group">
							<small>Last name</small>
							<input type="text" name="lastname" class="form-control" placeholder="Enter your last name" value="<?php echo $lastname; ?>">
						</div>
					</div>

					<div class="col-lg-6">
						<div class="form-group">
							<small>Course</small>
							<select class="form-control" name="course">
								<option value="0">-- Select your course --</option>
								<?php foreach($courses as $course): ?>
								<option value="<?php echo $course['id']; ?>"><?php echo $course['name']; ?></option>
								<?php endforeach;?>
							</select>
						</div>

						<div class="form-group">
							<small>Username</small>
							<input type="text" name="username" class="form-control" placeholder="Enter your username" value="<?php echo $username; ?>">
						</div>

						<div class="form-group">
							<small>Password</small>
							<input type="password" name="password" class="form-control" placeholder="Enter your password" value="<?php echo $password; ?>">
						</div>

					</div>

				</div>
						

				<hr class="m-3">
				<div class="form-group">
					<input type="submit" name="submit" class="btn btn-info m-auto d-block w-50" value="Register">
				</div>
				<small class="text-center d-block">Already have an account? <a href="login.php">Login here</a></small>
			</form>
		</div>
	</div>
			
</body>
</html>