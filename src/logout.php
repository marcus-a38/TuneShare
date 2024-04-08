<?php 
// credit to https://www.php.net/manual/en/function.session-unset.php#107089
    session_start();
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    header("Location: register.php");
?>