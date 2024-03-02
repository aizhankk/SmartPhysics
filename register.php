<?php 
	if($_COOKIE['user']) {
		header("Location: index.php");
	}
	if (isset($_POST['username'])) {
		include 'libs.php';
		$username = $_POST['username'];
		$password = $_POST['password'];
		$role = $_POST['role'];

		if (userExsist($username)) {
			echo "User already exists";
		} else {
			newUser($username, $password, $role, 0);
			echo "success";
		}
		exit();

	}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sign up - Smark Physics</title>
	<link rel="stylesheet" href="css/style.css?das=<?=rand(1, 100000)?>">
    <script src="js/script.js?das=<?=rand(1, 100000)?>"></script>
    <script src="js/jquery-3.4.1.min.js"></script>
</head>
<body>
	<div class="header">
		<p></p>
		<p>Smark Physics</p>
		<p></p>
	</div>
	<div class="content">
		<div class="login">
			<p class="title">Sign up</p>
			<input type="text" placeholder="Username" id="username">
			<input type="password" placeholder="Password" id="password">
			<input type="password" placeholder="Confirm password" id="password2">

			<div class="toggle">
				<input type="radio" name="role" value="0" id="sizeWeight" checked="checked" />
				<label for="sizeWeight">Student</label>
				<input type="radio" name="role" value="1" id="sizeDimensions" />
				<label for="sizeDimensions">Teacher</label>
			</div>
			<button onclick="register()">Sign up</button>
			<a href="login.php">Already have an account <u>Sign In</u></a>
		</div>
		<div class="error" id="alert" style="display: none;">
			<p>Wrong password</p>
		</div>
	</div>
	<div class="footer">
		<p>© 2023 Smark Physics</p>
	</div>

	<script>
		function register() {
			username = $(`#username`).val()
			password = $(`#password`).val()
			password2 = $(`#password2`).val()
			role = $(`input[name=role]:checked`).val()
			console.log(role)

			// jquery check if empty
			if (username == `` || password == `` || password2 == ``) {
				$(`#alert`).show()
				return
			} else {
				$.post("register.php", {username: username, password: password, role: role}, function(data) {
					if (data == `success`) {
						showALert("Registration Successful", 0)
						setTimeout(function () {
							window.location.href = "login.php"
						}, 500)
					} else {
						showALert(data, 1)
					}
				})

			}
			
		}

	</script>	
</body>
</html>