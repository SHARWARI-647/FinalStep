<?php
session_start();

/*
|--------------------------------------------------------------------------
| Logout based on user role
|--------------------------------------------------------------------------
| We check with isset() to avoid "Undefined array key" warnings
*/

if (isset($_SESSION['library_name'])) {

    session_unset();
    session_destroy();
    header("Location: library_login.php");
    exit();

} elseif (isset($_SESSION['department'])) {

    session_unset();
    session_destroy();
    header("Location: dept_login.php");
    exit();

} elseif (isset($_SESSION['admin'])) {

    session_unset();
    session_destroy();
    header("Location: admin_login.php");
    exit();

} elseif (isset($_SESSION['store'])) {

    session_unset();
    session_destroy();
    header("Location: store_login.php");
    exit();

} elseif (isset($_SESSION['enrollment'])) {

    session_unset();
    session_destroy();
    header("Location: std_login.php");
    exit();

} else {
    // If no session exists, redirect to home or login
    header("Location: index.php");
    exit();
}
?>
