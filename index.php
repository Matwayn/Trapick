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
      color:beige;
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
        <?php if (isset($error) && $error == "1") : ?>
            <p class="invalid">Invalid password. Please try again.</p>
        <?php endif; ?>
        <form id="loginForm" action="/login.php" method="POST">
            <input id="passwordInput" type="password" name="password" placeholder="Password" required <?php if (isset($error) && $error == "1") echo 'class="invalid"'; ?>>
            <button type="submit">Login</button>
        </form>
    </div>

</body>

</html>