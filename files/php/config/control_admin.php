<?php
session_start();
if (!isset($_SESSION["user".__HASH])) {
    redirect(__URL . "index.php?loggedOut");
}
$__user = $_SESSION["user".__HASH];
if (!$__user->isAdmin) {
    redirect(__URL . "index.php?loggedOut");
}
