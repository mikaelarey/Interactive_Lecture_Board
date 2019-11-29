<?php

session_start();

if (isset($_SESSION['course'])) {

	header("location: ilb");
	
}
else {

	$error_container = '';
	$error = '';


	$conn = new mysqli('localhost', 'root', '', 'ilb');

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT * FROM course";
	$courses = $conn->query($sql);



	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$course = trim($_POST['course']);

		if ($course == 0) {
			$error_container = 'p-3 text-white bg-danger mb-3';
			$error = '<small class="d-block text-center">* Please the course that you are going to teach.</small>';
		}
		else {
			$_SESSION['course'] = $course;
			header("location: ilb");
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

				<h4 class="text-center m-3">Hi <strong class="text-info"><?php echo $_SESSION['username']; ?></strong></h4>
				<p class="text-center">Welcome to Interactive Lecture Board Application.</p>

				<div class="form-group">

					<div class="<?php echo $error_container; ?>" style="opacity:0.5;border-radius:0.5rem;">
						<span>
							<?php echo $error; ?>
						</span>
					</div>

					<small class="d-block">Course that you are going to teach</small>
					<select class="form-control" name="course">
						<option value="0">-- Select course to teach --</option>
						<?php foreach($courses as $course): ?>
						<option value="<?php echo $course['id']; ?>"><?php echo $course['name']; ?></option>
						<?php endforeach;?>
					</select>
				</div>

				<hr class="m-3">
				<div class="form-group">
					<input type="submit" name="submit" class="btn btn-info m-auto btn-block" value="Enter the class">
				</div>
				<small class="text-center d-block"><a href="logout.php">Logout</a></small>
			</form>
		</div>
	</div>
			
</body>
</html>