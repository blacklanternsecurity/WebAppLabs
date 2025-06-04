<?php
session_start();
$host = getenv('MYSQL_HOST') ?: 'db';
$user = getenv('MYSQL_USER') ?: 'vulnuser';
$pass = getenv('MYSQL_PASSWORD') ?: 'vulnpass';
$db = getenv('MYSQL_DB') ?: 'vulnweb';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die('DB connection failed: ' . $conn->connect_error);
$error = '';
$query = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // VULNERABLE: Direct interpolation
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $_SESSION['user'] = $username;
        $success = true;
    } else {
        $error = 'Invalid credentials.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <style>
    body { background: #181a1b; color: #f1f1f1; font-family: 'Segoe UI', Arial, sans-serif; display: flex; flex-direction: column; min-height: 100vh; margin: 0; justify-content: center; align-items: center; }
    .container { background: #000; padding: 2rem 2.5rem 1.5rem 2.5rem; border-radius: 12px; box-shadow: 0 4px 32px rgba(0,0,0,0.7); display: flex; flex-direction: column; align-items: center; min-width: 340px; }
    .logo-bg { background: #000; border-radius: 10px; padding: 16px 24px 12px 24px; margin-bottom: 1.2rem; display: flex; justify-content: center; align-items: center; }
    .logo { width: 120px; display: block; }
    h2 { margin-bottom: 1.2rem; font-weight: 600; letter-spacing: 1px; }
    form { display: flex; flex-direction: column; width: 100%; gap: 1rem; }
    input[type=text], input[type=password] { background: #181a1b; border: 1px solid #222; border-radius: 6px; padding: 0.7rem; color: #f1f1f1; font-size: 1rem; outline: none; transition: border 0.2s; }
    input[type=text]:focus, input[type=password]:focus { border: 1.5px solid #00bfff; }
    input[type=submit] { background: linear-gradient(90deg, #00bfff 0%, #005f87 100%); color: #fff; border: none; border-radius: 6px; padding: 0.7rem; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: 0.5rem; transition: background 0.2s; }
    input[type=submit]:hover { background: linear-gradient(90deg, #005f87 0%, #00bfff 100%); }
    .error { color: #ff4d4f; margin-top: 0.5rem; font-size: 1rem; text-align: center; }
    .footer { margin-top: 2.5rem; color: #888; font-size: 0.95rem; text-align: center; }
    .sql {
      background: #181a1b;
      border: 1px solid #444;
      border-radius: 6px;
      padding: 1rem;
      font-size: 1rem;
      margin-top: 1.2rem;
      width: 100%;
      overflow-x: auto;
    }
    .success-query {
      color: #00ffae;
    }
    .error-query {
      color: #ff4d4f;
    }
    .success { color: #00ffae; margin-top: 0.5rem; font-size: 1.1rem; text-align: center; }
    .nav { margin-top: 1.5rem; }
    .nav a { color: #00bfff; text-decoration: none; font-weight: 600; }
    .nav a:hover { text-decoration: underline; }
    .dashboard {
      background: #181a1b;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 1.5rem;
      margin: 1.5rem 0;
      width: 100%;
    }
    .dashboard h4 {
      margin: 0 0 1rem 0;
      color: #00bfff;
    }
    .dashboard-content {
      display: flex;
      flex-direction: column;
      gap: 0.8rem;
    }
    .status-active {
      color: #00ffae;
      font-weight: 600;
    }
    .logout {
      margin-left: 1rem;
      color: #ff4d4f !important;
    }
    .logout:hover {
      color: #ff7875 !important;
    }
    h3 {
      margin: 0 0 1rem 0;
      color: #00ffae;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-bg"><img src="/assets/BLS.png" alt="BLS Logo" class="logo"></div>
    <h2>Login Portal</h2>
    <?php if (!empty($success)): ?>
      <div class="success">
        <h3>Welcome, <?php echo htmlspecialchars(preg_replace('/[\'";\s-].*$/', '', $username)); ?>!</h3>
        <p>You have successfully accessed the secure area.</p>
        <div class="dashboard">
          <h4>User Dashboard</h4>
          <div class="dashboard-content">
            <p>Account Status: <span class="status-active">Active</span></p>
            <p>Last Login: <?php echo date('Y-m-d H:i:s'); ?></p>
            <p>Access Level: Administrator</p>
          </div>
        </div>
        <div class="sql success-query"><b>SQL Query:</b><br><?php echo htmlspecialchars($query); ?></div>
        <div class="nav">
          <a href="union.php">Go to Union SQLi</a>
          <a href="?logout=1" class="logout">Logout</a>
        </div>
      </div>
    <?php else: ?>
      <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
      </form>
      <?php if ($error): ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
      <?php if ($query): ?><div class="sql error-query"><b>SQL Query:</b><br><?php echo htmlspecialchars($query); ?></div><?php endif; ?>
      <div class="nav"><a href="union.php">Go to Union SQLi</a></div>
    <?php endif; ?>
  </div>
  <div class="footer">&copy; Black Lantern Security 2025</div>
</body>
</html> 