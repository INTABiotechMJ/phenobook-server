<?php
session_start();
if (!isset($_SESSION["user".__HASH])) {
    redirect(__URL . "index.php?loggedOut");
}
$__user = $_SESSION["user".__HASH];
if (!$__user->isAdmin() && !$__user->isSuperAdmin()) {
    redirect(__URL . "index.php?loggedOut");
}
?>
