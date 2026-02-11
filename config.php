<?php
// Vercel specific configuration source
$is_vercel = getenv('VERCEL');
if ($is_vercel) {
    session_save_path('/tmp');
    $db_file = '/tmp/ctf.db';
} else {
    $db_file = 'ctf.db';
}

session_start();
?>
