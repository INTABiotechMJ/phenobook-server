<?php
session_start();
if (!isset($_SESSION["user".__HASH])) {
    redirect(__URL . "index.php?loggedOut=1");
}
$__user = $_SESSION["user".__HASH];
