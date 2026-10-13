<?php
/**
 * Include at the top of any page that requires a logged-in user.
 */
session_start();

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
