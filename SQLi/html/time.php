<?php
session_start();
$host = getenv('MYSQL_HOST') ?: 'db';
$user = getenv('MYSQL_USER') ?: 'vulnuser';
$pass = getenv('MYSQL_PASSWORD') ?: 'vulnpass';
$db = getenv('MYSQL_DB') ?: 'vulnweb';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die('DB connection failed.');
$input = isset($_GET['input']) ? $_GET['input'] : '';
$delay = false;
$challenge_ready = isset($_SESSION['sqli_ready']) && $_SESSION['sqli_ready'] === true;

// Step 1: Start challenge (set session state)
if (isset($_GET['stage']) && $_GET['stage'] === 'init') {
    $_SESSION['sqli_ready'] = true;
    header('Location: time.php');
    exit;
}

// Step 2: Only run vulnerable code if state is set, then unset
if ($challenge_ready && $input !== '') {
    // VULNERABLE: Direct interpolation, run twice
    $query = "SELECT '$input' AS test";
    $start = microtime(true);
    $conn->query($query);
    $conn->query($query); // Run twice
    $elapsed = microtime(true) - $start;
    if ($elapsed > 8) { // 2x SLEEP(5) = 10s, so threshold >8s
        $delay = true;
    }
    unset($_SESSION['sqli_ready']); // One-shot
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Time-based SQLi Challenge</title>
  <style>
    body { background: #181a1b; color: #f1f1f1; font-family: 'Segoe UI', Arial, sans-serif; display: flex; flex-direction: column; min-height: 100vh; margin: 0; justify-content: center; align-items: center; }
    .container { background: #000; padding: 2rem 2.5rem 1.5rem 2.5rem; border-radius: 12px; box-shadow: 0 4px 32px rgba(0,0,0,0.7); display: flex; flex-direction: column; align-items: center; min-width: 340px; }
    .logo-bg { background: #000; border-radius: 10px; padding: 16px 24px 12px 24px; margin-bottom: 1.2rem; display: flex; justify-content: center; align-items: center; }
    .logo { width: 120px; display: block; }
    h2 { margin-bottom: 1.2rem; font-weight: 600; letter-spacing: 1px; }
    form { display: flex; flex-direction: column; width: 100%; gap: 1rem; }
    input[type=text] { background: #181a1b; border: 1px solid #222; border-radius: 6px; padding: 0.7rem; color: #f1f1f1; font-size: 1rem; outline: none; transition: border 0.2s; }
    input[type=submit] { background: linear-gradient(90deg, #00bfff 0%, #005f87 100%); color: #fff; border: none; border-radius: 6px; padding: 0.7rem; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    input[type=submit]:hover { background: linear-gradient(90deg, #005f87 0%, #00bfff 100%); }
    .footer { margin-top: 2.5rem; color: #888; font-size: 0.95rem; text-align: center; }
    .nav { margin-top: 1.5rem; }
    .nav a { color: #00bfff; text-decoration: none; font-weight: 600; }
    .nav a:hover { text-decoration: underline; }
    .delay { color: #00ffae; margin-top: 1rem; font-size: 1.1rem; text-align: center; }
    .challenge-btn { margin-top: 2rem; }
    .challenge-btn form { display: inline; }
    .challenge-btn button { background: linear-gradient(90deg, #00bfff 0%, #005f87 100%); color: #fff; border: none; border-radius: 6px; padding: 0.7rem 1.5rem; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    .challenge-btn button:hover { background: linear-gradient(90deg, #005f87 0%, #00bfff 100%); }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-bg"><img src="/assets/BLS.png" alt="BLS Logo" class="logo"></div>
    <h2>Time-based SQLi Challenge</h2>
    <?php if (!$challenge_ready): ?>
      <div class="challenge-btn">
        <form method="get">
          <input type="hidden" name="stage" value="init">
          <button type="submit">Start Challenge</button>
        </form>
      </div>
    <?php else: ?>
      <form method="get">
        <input type="text" name="input" placeholder="Enter value..." value="<?php echo htmlspecialchars($input); ?>">
        <input type="submit" value="Submit">
      </form>
    <?php endif; ?>
    <?php if ($input !== '' && !$challenge_ready): ?>
      <div class="delay">Please start the challenge first.</div>
    <?php endif; ?>
    <?php if ($input !== '' && $challenge_ready): ?>
      <div class="delay">
        <?php if ($delay): ?>
          Request processed.<br>Thank you for your submission.
        <?php else: ?>
          Request processed.<br>Thank you for your submission.
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <div class="nav">
      <a href="index.php">Back to Challenges</a> |
      <a href="union.php">Go to Union SQLi</a>
    </div>
  </div>
  <div class="footer">&copy; Black Lantern Security 2025</div>
</body>
</html> 