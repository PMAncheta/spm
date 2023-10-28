<?php
session_start();
session_destroy();
header("Location: /payroll/login.php");
exit();
