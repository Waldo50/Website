<?php
	include("config.php");
	include_once("session.php");
	
	// If this page is requested through a POST (submit) rather than a GET, validate login details from the POST
	if (isset($_POST['submit'])) {

		if (empty($_POST['emailField']) & empty($_POST['passwordField'])) {
			// don't show an error if both the username and password are blank
		} else if (empty($_POST['emailField']) | empty($_POST['passwordField'])) {
			$error = "Email or password was blank"; // XOR: if the email field or password field (but not both) are blank, show an error
		} else {
			// Define $username and $password 
			$email = $_POST['emailField'];
			$password = $_POST['passwordField'];
			
			$password = sha1($password);

			// Create a new variable called dbQuery that stores a preparational query to PDO. Once PDO has prepared the query, execute it with the appropriate variables.
			// https://phpdelusions.net/pdo
			try {
				$dbQuery = $db->prepare('SELECT * FROM users WHERE email=:email AND password=:password');
				$dbQuery->execute([':email' => $email, ':password' => $password]);
			} catch (PDOException $e) {
				// If it fails, show the error and exit
				echo $e;
				exit;
			}

			$dbRow = $dbQuery->fetch();
			if ($dbRow) {
				// there is an entry found in the database that matches the username and password,
				// so store the username in the session cookie
				$_SESSION['email'] = $email;

				// and redirect back to the home page
				header('Location: index.php');
				exit;
			} else {
				$error = "Invalid username or password";
			}
		}
	} else {		
		// Auto-login GET requests based on session cookie
		if (isset($_SESSION['email'])) { // if the username is set within the session cookie
			if (!empty($_SESSION['email'])) { // and it's not empty
				header("Location: index.php"); // login
				exit;
			}
		}

		// If we got to this point, we haven't auto-logged-in, so we will now show the login page
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Framework styles -->
    <link rel="stylesheet" href="https://ozli.ga/Ozli-v1.8/grid.css">
    <link rel="stylesheet" href="https://ozli.ga/Ozli-v1.8/general.css">

    <!-- Site-specific styles -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="navbar">
        <nav data-grid="col-12 stack-3 container">
            <div data-grid="col-6">
                <h1 style="color: white">CheetahGlos</h1>
            </div>
            <div class="group" data-grid="col-6">
                <button class="primary large" onclick="window.location='index.html'">Home</button>
                <button class="primary large" onclick="window.location='login.html'">Login</button>
            </div>
        </nav>
    </div>
    <main data-grid="container">
		<?php
			if (isset($error)) {
				echo '<strong class="badge highlight">' . $error . '</strong>';
			}
		?>
        <form action="login.php" method="post">
            <label for="emailField">Email:</label>
            <div class="inputReveal">
                <input type="email" name="emailField"/>
            </div>
            <label for="passwordField">Password:</label>
            <div class="inputReveal">
                <input type="password" name="passwordField">
            </div>

            <br>
            <div class="group">
                <input type="submit" value="Login" name="submit"/>
                <span class="spacer">...</span>
                <a href="#">Forgot password?</a>
            </div>
        </form>
    </main>
    <footer>Cheetah Glos</footer>

    <!-- WIP: Special effects similar to Windows 10's fluent UI. -->
    <script src="https://ozli.ga/Ozli-v1.7/win10fluent.css"></script>
    <script src="https://ozli.ga/Ozli-v1.7/win10fluent.js"></script>
</body>
</html>