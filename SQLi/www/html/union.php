<?php
$host = getenv('MYSQL_HOST') ?: 'db';
$user = getenv('MYSQL_USER') ?: 'vulnuser';
$pass = getenv('MYSQL_PASSWORD') ?: 'vulnpass';
$db = getenv('MYSQL_DB') ?: 'vulnweb';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die('DB connection failed: ' . $conn->connect_error);
$error = '';
$query = '';
$results = [];
$headers = [];

// Get search parameters
$business_name = isset($_GET['business_name']) ? $_GET['business_name'] : '';
$business_type = isset($_GET['business_type']) ? $_GET['business_type'] : '';
$city = isset($_GET['city']) ? $_GET['city'] : '';
$country = isset($_GET['country']) ? $_GET['country'] : '';

function processQuery($conn, $query) {
    $res = $conn->query($query);
    
    // Check if this is an INTO OUTFILE query
    if (stripos($query, 'INTO OUTFILE') !== false) {
        return [
            'error' => '',
            'headers' => ['id', 'business_name', 'business_type', 'city', 'country'],
            'results' => [['1', 'File uploaded successfully', 'Success', 'Success', 'Success']]
        ];
    }
    
    if ($res === false) {
        return ['error' => $conn->error, 'headers' => [], 'results' => []];
    }
    
    $headers = [];
    $results = [];
    
    $fields = $res->fetch_fields();
    foreach ($fields as $field) {
        $headers[] = $field->name;
    }
    
    while ($row = $res->fetch_row()) {
        $results[] = $row;
    }
    
    return ['error' => '', 'headers' => $headers, 'results' => $results];
}

// Build the query with proper escaping for all fields except business_name
$query = "SELECT id, business_name, business_type, city, country FROM blind_data WHERE 1=1";
if ($business_name !== '') {
    $query = "SELECT id, business_name, business_type, city, country FROM blind_data WHERE business_name LIKE '%" . $business_name . "%'";
}
if ($business_type !== '') {
    $query .= " AND business_type = '" . $conn->real_escape_string($business_type) . "'";
}
if ($city !== '') {
    $query .= " AND city = '" . $conn->real_escape_string($city) . "'";
}
if ($country !== '') {
    $query .= " AND country = '" . $conn->real_escape_string($country) . "'";
}

$result = processQuery($conn, $query);
$error = $result['error'];
$headers = $result['headers'];
$results = $result['results'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Union SQLi Challenge</title>
  <style>
    body { background: #181a1b; color: #f1f1f1; font-family: 'Segoe UI', Arial, sans-serif; display: flex; flex-direction: column; min-height: 100vh; margin: 0; justify-content: center; align-items: center; }
    .container { background: #000; padding: 2rem 2.5rem 1.5rem 2.5rem; border-radius: 12px; box-shadow: 0 4px 32px rgba(0,0,0,0.7); display: flex; flex-direction: column; align-items: center; min-width: 340px; }
    .logo-bg { background: #000; border-radius: 10px; padding: 16px 24px 12px 24px; margin-bottom: 1.2rem; display: flex; justify-content: center; align-items: center; }
    .logo { width: 120px; display: block; }
    h2 { margin-bottom: 1.2rem; font-weight: 600; letter-spacing: 1px; }
    form { display: flex; flex-direction: column; width: 100%; gap: 1rem; }
    .search-row { display: flex; gap: 1rem; }
    input[type=text] { background: #181a1b; border: 1px solid #222; border-radius: 6px; padding: 0.7rem; color: #f1f1f1; font-size: 1rem; outline: none; transition: border 0.2s; flex: 1; }
    input[type=submit] { background: linear-gradient(90deg, #00bfff 0%, #005f87 100%); color: #fff; border: none; border-radius: 6px; padding: 0.7rem; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    input[type=submit]:hover { background: linear-gradient(90deg, #005f87 0%, #00bfff 100%); }
    table { margin-top: 1.5rem; border-collapse: collapse; width: 100%; background: #222; border-radius: 8px; overflow: hidden; }
    th, td { padding: 0.7rem 1.2rem; border-bottom: 1px solid #333; text-align: left; }
    th { background: #111; color: #00bfff; }
    tr:last-child td { border-bottom: none; }
    .footer { margin-top: 2.5rem; color: #888; font-size: 0.95rem; text-align: center; }
    .sql { background: #181a1b; border: 1px solid #444; border-radius: 6px; padding: 1rem; color: #00ffae; font-size: 1rem; margin-top: 1.2rem; width: 100%; overflow-x: auto; }
    .error { color: #ff4d4f; margin-top: 0.5rem; font-size: 1rem; text-align: center; }
    .nav { margin-top: 1.5rem; }
    .nav a { color: #00bfff; text-decoration: none; font-weight: 600; }
    .nav a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-bg"><img src="/assets/BLS.png" alt="BLS Logo" class="logo"></div>
    <h2>Union SQLi Challenge</h2>
    <form method="get">
      <div class="search-row">
        <input type="text" name="business_name" placeholder="Search business name..." value="<?php echo htmlspecialchars($business_name); ?>">
        <input type="text" name="business_type" placeholder="Search business type..." value="<?php echo htmlspecialchars($business_type); ?>">
      </div>
      <div class="search-row">
        <input type="text" name="city" placeholder="Search city..." value="<?php echo htmlspecialchars($city); ?>">
        <input type="text" name="country" placeholder="Search country..." value="<?php echo htmlspecialchars($country); ?>">
      </div>
      <input type="submit" value="Search">
    </form>
    <?php if ($error): ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if ($results && $headers): ?>
    <table>
      <tr><?php foreach ($headers as $h) echo "<th>".htmlspecialchars($h)."</th>"; ?></tr>
      <?php foreach ($results as $row): ?><tr><?php foreach ($row as $cell) echo "<td>".htmlspecialchars($cell)."</td>"; ?></tr><?php endforeach; ?>
    </table>
    <?php endif; ?>
    <?php if ($query): ?><div class="sql"><b>SQL Query:</b><br><?php echo htmlspecialchars($query); ?></div><?php endif; ?>
    <div class="nav"><a href="index.php">Back to Login</a></div>
  </div>
  <div class="footer">&copy; Black Lantern Security 2025</div>
</body>
</html> 