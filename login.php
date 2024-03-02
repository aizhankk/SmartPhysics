<?php 
// Purpose: Log in to the website
	if($_COOKIE['user']) {
		header("Location: index.php");
	}
	if(isset($_POST['username'])) {
		include 'libs.php';
		$username = $_POST['username'];
		$password = $_POST['password'];

		if (userExsist($username)) {
			$user = getUser($username);
			if ($user['password'] == $password) {
				$_SESSION['user'] = $user;
				echo "success";
			} else {
				echo "wrong";
			}
		} else {
			echo "wrong";
		}
		exit();

	}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Log in - Smark Physics</title>
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
			<p class="title">Log in</p>
			<input type="text" placeholder="Username" id="username">
			<input type="password" placeholder="Password" id="password">
			<button onclick="login()">Log in</button>
			<a href="#">Forgot password?</a>
			<a href="register.php">Do not have an account <u>Sign Up</u></a>
		</div>
		<div class="error" id="alert" style="display: none;">
			<p>Wrong password</p>
		</div>
	</div>
	<div class="footer">
		<p>© 2023 Smark Physics</p>
	</div>

	<script>
		function login() {
			username = $(`#username`).val()
			password = $(`#password`).val()

			// jquery check if empty
			if (username == `` || password == ``) {
				showALert(`Fill all the fields`, 1)
				console.log('err')
				return
			}

			// jquery check if correct
			$.post(`login.php`, {username: username, password: password}, function(data) {
				if (data == `success`) {
					showALert("Login Successful", 0)
					setCookie(`user`, username, 1);
					setTimeout(function () {
						window.location.href = `index.php`
					}, 500);
				} else {
					showALert(`Wrong username or password`, 1)
				}
			})
		}
	</script>
</body>
</html>