<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="loginStyle.css">
  <title>Login</title>

  <?php
  // Initialize the session
  session_start();

  /* Check if the user is already logged in, if yes than redirect them to welcome
  page */
  if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]==true) {
    header("location: welcome.php");
    exit;
  }

  // Include config file
  require_once "config.php";

  // define variables and set to empty values
  $username = $password = "";
  $userErr = $passErr = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["username"])) {
      if (empty($_POST["password"])) {
      } else {
        $userErr = "Username is Required";
      }
    } else {
      $username = test_input($_POST["username"]);
    }

    if (empty($_POST["password"])) {
      if (empty($_POST["username"])) {
      } else {
        $passErr = "Password is Required";
      }
    } else {
      $password = test_input($_POST["password"]);
    }

    // Validate credentials
    if (empty($userErr) && empty($passErr)) {
      // Preparing a select statement
      $sql = "Select id, username, password FROM users WHERE username = ?";

      if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // Set parameters
        $param_username = $username;

        // Attempt tp execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
          // Store Result
          mysqli_stmt_store_result($stmt);

          // Check if username exists, if yes then verify Password
          if (mysqli_stmt_num_rows($stmt) == 1) {
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

            if (mysqli_stmt_fetch($stmt)) {
              if (password_verify($password, $hashed_password)) {
                  // Password is correct so start a new session
                  session_start();

                  // Store data in session variables
                  $_SESSION["loggedin"] == true;
                  $_SESSION["id"] = $id;
                  $_SESSION["username"] = $username;

                  // Redirect user to welcome page
                  header("location: homePage/homePage.html");
              } else {
                // DIsplay an error messege if password is not valid
                $passErr = "The password you entered is not valid.";
              }
            }
          } else {
            // Display ann error messege if username doesn't exist
            $userErr = "No account found with that username";
          }
        } else {
          echo "Oops! Something went wrong. Please try again later.";
        }

        mysqli_stmt_close($stmt);
      }
    }

    // Close connection
    mysqli_close($link);
  }

    // Writing a php function to strip unnecessary characters and backslashes
  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  ?>
</head>

<body>

  <div class="header">

    <div class="header-container">

      <div class="logo">
        <img src="/Images/Pi.jpg" alt="Pi Image"
        width=75px height=75px>
      </div>

      <div class="logo-text">
        <h1>Pi In The Sky</h1>
      </div>
    </div>
  </div>

  <div class="lock">
    <img src="/Images/Lock.png" alt="Lock Image"
    width=300px height=300px>
  </div>

  <div class="authenticate">
    <h1 style="font-family:optima; font-style:normal">PITS Authenticate</h1>
  </div>

  <div class="credentials-row">

    <form method="post"
    action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

      <div class="credentials-container">
        <input type="text" placeholder="PITS Username" name="username"
        value="<?php echo $username; ?>">

        <input type="password" placeholder="Enter Password" name="password"
        value="<?php echo $password; ?>">

        <span class="error"> <?php echo $userErr; ?></span>
        <span class="error"> <?php echo $passErr; ?></span>

        <button type="submit">LOGIN</button>
      </div>
    </form>
  </div>
</body>
