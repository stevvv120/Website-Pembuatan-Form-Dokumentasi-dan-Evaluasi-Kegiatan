<?php
session_start();
session_destroy();
header("Location: login/loginuser.php");
exit;
?>