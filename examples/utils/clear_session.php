<?php
session_start();
foreach (array_keys($_SESSION) as $key) {
    unset($_SESSION[$key]);
}
echo 'Session variables unset. <a href="../index.php">Home</a>'
?>