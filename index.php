<?php
session_start();
$password = 'trapick';

$random1 = '1ce95035aebe86f4cd7cb299da862ae5';
$random2 = '2t0eifijr9ermgvl6vq2ur9xy';

$hash = md5($random1.$password.$random2);

if (isset($_SESSION['login']) && $_SESSION['login'] == $hash) {

	header("Location: main.php");
	die();
}
else if (isset($_POST['submit'])) {

	if ($_POST['password'] == $password) {
		$_SESSION["login"] = $hash;
		header("Location: $_SERVER[PHP_SELF]");

	} else {

		$error = 1;
		display_login_form($error);
	}
} else {
	$error = null;
	display_login_form($error);
}


function display_login_form($error)
{ ?>
	<!DOCTYPE html>
	<html lang="fr">

	<head>
		<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
		<title>-TkP-</title>

		<style>
			h1,
			h2,
			h3,
			h4,
			h5,
			h6 {
				color: white;
				font-size: 2em;
			}

			body {
				font-family: Arial, sans-serif;
				background-color: #252525;
				margin: 0;
				padding: 0;
				display: flex;
				justify-content: center;
				align-items: center;
				height: 100vh;
			}

			.login-container {
				background-color: #3f3f3f;
				border-radius: 10px;
				box-shadow: 0 30px 10px rgba(0, 0, 0, 0.1);
				padding: 60px;
				width: 500px;
				height: auto;
				max-width: 100%;
				text-align: center;
			}

			.login-container h2 {
				margin-bottom: 20px;
			}

			.login-container input {
				width: calc(100% - 20px);
				padding: 10px;
				color: beige;
				margin-bottom: 20px;
				border: 1px solid #605454;
				border-radius: 5px;
				background-color: #252525;
			}

			.login-container button {
				background-color: #4CAF50;
				color: white;
				padding: 10px 20px;
				border: none;
				border-radius: 5px;
				cursor: pointer;
				transition: background-color 0.3s;
			}

			.login-container button:hover {
				background-color: #067d0c;
			}

			.shake {
				animation: shake 0.5s;
			}

			@keyframes shake {
				0% {
					transform: translateX(0);
				}

				20% {
					transform: translateX(-5px);
				}

				40% {
					transform: translateX(10px);
				}

				60% {
					transform: translateX(-5px);
				}

				80% {
					transform: translateX(5px);
				}

				100% {
					transform: translateX(0);
				}
			}

			.invalid {
				animation: shake 0.4s ease-in-out;
				color: #d24343 !important;
				border: 2px solid #9b0a0a !important;
				border-radius: 5px;
			}
		</style>
	</head>

	<body>
		<div class="login-container">
			<h2>Login</h2>
			<form action="" method='post'>
				<?php if (isset($error)) { ?>
					<input class="invalid" type="password" placeholder="Password" name="password" id="password" required>
				<?php } else { ?>
					<input type="password" placeholder="Password" name="password" id="password">
				<?php } ?>

				<button type="submit" name="submit" value="submit">Login</button>

			</form>
		</div>

	</body>

	</html>



<?php } 