<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$flag = "Flag is only for admin users!";
if ($_SESSION['username'] === 'admin') {
    $flag = "PHXCTF{SQL_BYpass}";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - PHXCTF</title>
    <style>
        body {
            font-family: monospace;
            background-color: #1a1a1a;
            color: #00ff00;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #2b2b2b;
            padding: 2rem;
            border: 1px solid #00ff00;
            box-shadow: 0 0 10px #00ff00;
            width: 500px;
            text-align: center;
        }

        .flag {
            font-size: 1.5em;
            border: 2px dashed #00ff00;
            padding: 20px;
            margin: 20px 0;
            color: #ff00ff;
        }

        a {
            color: #00ff00;
            text-decoration: none;
            border: 1px solid #00ff00;
            padding: 5px 10px;
        }

        a:hover {
            background: #00ff00;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ACCESS GRANTED</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        
        <?php if (strpos($flag, 'PHXCTF') !== false): ?>
        <div class="flag">
            <?php echo $flag; ?>
        </div>
        <p>Congratulations! You have bypassed the authentication.</p>
        <?php else: ?>
        <p><?php echo $flag; ?></p>
        <?php endif; ?>
        
        <br><br>
        <a href="logout.php">LOGOUT</a>
    </div>
</body>
</html>
