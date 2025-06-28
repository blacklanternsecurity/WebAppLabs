<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>SQL Injection Challenges - Black Lantern Security</title>
  <style>
    body { background: #181a1b; color: #f1f1f1; font-family: 'Segoe UI', Arial, sans-serif; display: flex; flex-direction: column; min-height: 100vh; margin: 0; justify-content: center; align-items: center; }
    .container { background: #000; padding: 2rem 2.5rem 1.5rem 2.5rem; border-radius: 12px; box-shadow: 0 4px 32px rgba(0,0,0,0.7); display: flex; flex-direction: column; align-items: center; min-width: 600px; max-width: 800px; }
    .logo-bg { background: #000; border-radius: 10px; padding: 16px 24px 12px 24px; margin-bottom: 1.2rem; display: flex; justify-content: center; align-items: center; }
    .logo { width: 120px; display: block; }
    h1 { margin-bottom: 0.5rem; font-weight: 600; letter-spacing: 1px; color: #00bfff; }
    h2 { margin-bottom: 1.2rem; font-weight: 600; letter-spacing: 1px; }
    .subtitle { color: #888; margin-bottom: 2rem; text-align: center; }
    .challenges { display: flex; flex-direction: column; gap: 1.5rem; width: 100%; }
    .challenge-card {
      background: #181a1b;
      border: 1px solid #333;
      border-radius: 8px;
      padding: 1.5rem;
      transition: all 0.3s ease;
    }
    .challenge-card:hover {
      border-color: #00bfff;
      transform: translateY(-2px);
      box-shadow: 0 4px 20px rgba(0,191,255,0.2);
    }
    .challenge-title {
      color: #00bfff;
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    .challenge-desc {
      color: #ccc;
      margin-bottom: 1rem;
      line-height: 1.5;
    }
    .challenge-link {
      display: inline-block;
      background: linear-gradient(90deg, #00bfff 0%, #005f87 100%);
      color: #fff;
      text-decoration: none;
      padding: 0.7rem 1.5rem;
      border-radius: 6px;
      font-weight: 600;
      transition: background 0.2s;
    }
    .challenge-link:hover {
      background: linear-gradient(90deg, #005f87 0%, #00bfff 100%);
    }
    .footer { margin-top: 2.5rem; color: #888; font-size: 0.95rem; text-align: center; }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-bg"><img src="/assets/BLS.png" alt="BLS Logo" class="logo"></div>
    <h1>SQL Injection Challenges</h1>
    <p class="subtitle">Master the art of SQL injection with these hands-on challenges</p>
    
    <div class="challenges">
      <div class="challenge-card">
        <div class="challenge-title">1. Basic Authentication Bypass</div>
        <div class="challenge-desc">
          A classic login form vulnerable to SQL injection. Practice bypassing authentication using various SQL injection techniques. 
          The application displays the SQL query used, making it perfect for learning.
        </div>
        <a href="login.php" class="challenge-link">Start Challenge</a>
      </div>
      
      <div class="challenge-card">
        <div class="challenge-title">2. Union-Based SQL Injection</div>
        <div class="challenge-desc">
          A search interface vulnerable to UNION-based SQL injection. Learn to extract data from the database using UNION queries. 
          One parameter is vulnerable while others are properly escaped.
        </div>
        <a href="union.php" class="challenge-link">Start Challenge</a>
      </div>
      
      <div class="challenge-card">
        <div class="challenge-title">3. Time-Based Blind SQL Injection</div>
        <div class="challenge-desc">
          An advanced challenge featuring time-based blind SQL injection with multi-step logic. 
          The vulnerability requires human interaction and runs payloads twice, making it resistant to automated tools.
        </div>
        <a href="time.php" class="challenge-link">Start Challenge</a>
      </div>
    </div>
  </div>
  <div class="footer">&copy; Black Lantern Security 2025</div>
</body>
</html> 