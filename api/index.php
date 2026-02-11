<?php
require_once 'config.php';

// Database setup
$db = new SQLite3($db_file);

// Create table if not exists
$db->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, username TEXT, password TEXT)");

// Check if admin exists, if not create
$check = $db->querySingle("SELECT count(*) FROM users WHERE username = 'admin'");
if ($check == 0) {
    $db->exec("INSERT INTO users (username, password) VALUES ('admin', 'sUp3r_s3cr3t_P4ssw0rd_Th4t_Y0u_W0nt_Gu3ss')");
    $db->exec("INSERT INTO users (username, password) VALUES ('guest', 'guest')");
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // VULNERABLE CODE: Direct string concatenation
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // For educational debugging (uncomment to see query)
    // echo "<!-- Debug Query: " . htmlspecialchars($query) . " -->";

    try {
        $result = $db->query($query);
        if ($result) {
            $row = $result->fetchArray(SQLITE3_ASSOC);
            if ($row) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid credentials";
            }
        } else {
             $error = "Database Error";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHXCTF Login Challenge (PHP)</title>
    <style>
        body { font-family: monospace; background-color: #1a1a1a; color: #00ff00; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: #2b2b2b; padding: 2rem; border: 1px solid #00ff00; box-shadow: 0 0 10px #00ff00; width: 400px; }
        h2 { text-align: center; margin-top: 0; }
        input { width: 100%; padding: 10px; margin: 10px 0; background: #000; border: 1px solid #00ff00; color: #00ff00; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #00ff00; color: #000; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background: #00cc00; }
        .error { color: #ff3333; text-align: center; margin-top: 10px; }
        .hint { color: #666; font-size: 0.8em; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>SECURE LOGIN SYSTEM v1.0</h2>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" placeholder="admin">
            <label>Password:</label>
            <input type="password" name="password" placeholder="********">
            <button type="submit">AUTHENTICATE</button>
        </form>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <div class="hint">
            <!-- Hint: source code analysis might reveal a query structure like: -->
            <!-- SELECT * FROM users WHERE username = '$username' AND password = '$password' -->
        </div>
    </div>
</body>
</html>
