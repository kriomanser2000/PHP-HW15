<?php
session_start();
session_unset();
session_destroy();
header('Location: logEntrAdminBD2.php');
exit();