<?php
require_once __DIR__ . '/auth.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Ongeldige gebruikersnaam of wachtwoord.';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — LaserStralen</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Inter', sans-serif;
      background: #0a0a0a;
      color: #fff;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }
    body::before {
      content: '';
      position: absolute;
      top: -20%;
      left: -10%;
      width: 50%;
      height: 140%;
      background: #4dff00;
      transform: skewX(-12deg);
      opacity: 0.06;
    }
    .login-box {
      background: #111;
      border: 1px solid rgba(77, 255, 0, 0.2);
      padding: 3rem;
      width: 100%;
      max-width: 400px;
      position: relative;
    }
    .login-box::before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 4px; height: 100%;
      background: #4dff00;
      box-shadow: 0 0 15px rgba(77, 255, 0, 0.3);
    }
    .login-box h1 {
      font-family: 'Russo One', sans-serif;
      font-size: 1.5rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      margin-bottom: 0.5rem;
    }
    .login-box h1 span { color: #4dff00; }
    .login-box p {
      color: rgba(255,255,255,0.4);
      font-size: 0.85rem;
      margin-bottom: 2rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .error {
      background: rgba(255, 50, 50, 0.1);
      border: 1px solid rgba(255, 50, 50, 0.3);
      color: #ff6b6b;
      padding: 0.75rem 1rem;
      font-size: 0.9rem;
      margin-bottom: 1.5rem;
    }
    label {
      display: block;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: rgba(255,255,255,0.5);
      margin-bottom: 0.5rem;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 0.8rem 1rem;
      background: #1a1a1a;
      border: 2px solid #333;
      color: #fff;
      font-size: 0.95rem;
      font-family: inherit;
      margin-bottom: 1.25rem;
      transition: border-color 0.2s;
    }
    input:focus {
      outline: none;
      border-color: #4dff00;
      box-shadow: 0 0 0 3px rgba(77, 255, 0, 0.1);
    }
    button {
      width: 100%;
      padding: 1rem;
      background: #4dff00;
      color: #0a0a0a;
      border: none;
      font-family: 'Inter', sans-serif;
      font-size: 0.85rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 2px;
      cursor: pointer;
      transform: skewX(-3deg);
      transition: all 0.2s;
    }
    button span { display: inline-block; transform: skewX(3deg); }
    button:hover {
      background: #fff;
      transform: skewX(-3deg) translateY(-2px);
      box-shadow: 0 6px 20px rgba(77, 255, 0, 0.2);
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h1><span>//</span> Admin</h1>
    <p>LaserStralen Beheer</p>
    <?php if ($error): ?>
      <div class="error"><?= e($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <label for="username">Gebruikersnaam</label>
      <input type="text" id="username" name="username" required autofocus>
      <label for="password">Wachtwoord</label>
      <input type="password" id="password" name="password" required>
      <button type="submit"><span>Inloggen</span></button>
    </form>
  </div>
</body>
</html>
