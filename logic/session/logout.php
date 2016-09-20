<?php 
require "../../files/php/config/require.php";
session_destroy();
redirect(__URL);