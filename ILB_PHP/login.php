<?php

session_start();

if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {

	header("location: index.php");

}

else {

	$username = '';
	$password = '';
	$error_container = '';
	$error = '';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);

		if (empty($username) && empty($password)) {
			$error_container = 'p-3 text-white bg-danger mb-3';
			$error = '<small>* Please provide your username <br> * Please provide your password</small>';
		}
		else {
			if (empty($username) || empty($password)) {
				if (empty($username)) {
					$error_container = 'p-3 text-white bg-danger mb-3';
					$error = '<small>* Please provide your username</small>';
				}
				else {
					$error_container = 'p-3 text-white bg-danger mb-3';
					$error = '<small>* Please provide your password</small>';
				}
			}
			else {

				$conn = new mysqli('localhost', 'root', '', 'ilb');

				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}

				$sql = "SELECT * FROM users WHERE username = '$username'";
				$result = $conn->query($sql);

				if ($result->num_rows == 1) {
					while ($row = $result->fetch_assoc()) {
						if (password_verify($password ,$row['password'])) {
							$_SESSION['username'] = $row['username'];
							$_SESSION['role'] = $row['role'];
							$_SESSION['student_id'] = $row['student_id'];
							header("location: index.php");
						}
						else {
							$error_container = 'p-3 text-white bg-danger mb-3';
							$error = '<small>* Username and password did not match.</small>';
						}
					}
				}
				else {
					if ($result->num_rows > 0 && $result->num_rows > 1) {
						$error_container = 'p-3 text-white bg-danger mb-3';
						$error = '<small>* Conflict with you entered the credentials. Please contact your administrator</small>';
					}
					else {
						$error_container = 'p-3 text-white bg-danger mb-3';
						$error = '<small>* Username and password did not match.</small>';
					}
				}
				$conn->close();
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
	<title>Login</title>
</head>
<body class="bg-info">
	<div class="row m-5 p-5">
		<div class="col-lg-4 col-md-6 m-auto p-3">
			<form class="shadow p-3 bg-white" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

				<img src="logo.png" style="width:10rem;" class="m-auto d-block">
				<small class="d-block text-center text-info"><b>Interactive Lecture Board</b></small>

				<h5 class="text-center m-3">Login</h5>

				<div class="<?php echo $error_container; ?>" style="opacity:0.5;border-radius:0.5rem;">
					<span>
						<?php echo $error; ?>
					</span>
				</div>

				<div class="form-group">
					<small>Student ID / Username</small>
					<input type="text" name="username" class="form-control" placeholder="Enter your username" value="<?php echo $username; ?>">
				</div>

				<div class="form-group">
					<small>Password</small>
					<input type="password" name="password" class="form-control" placeholder="Enter your password" value="<?php echo $password; ?>">
				</div>

				<hr class="m-3">
				<div class="form-group">
					<input type="submit" name="submit" class="btn btn-info m-auto d-block w-50" value="Login">
				</div>
				<small class="text-center d-block">Don't have an account? <a href="signup.php">Signup here</a></small>
			</form>
		</div>
	</div>
			
</body>
</html>