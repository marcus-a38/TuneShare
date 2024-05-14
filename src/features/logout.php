<?php 
    session_start();
    session_unset();
    session_destroy();
    session_commit();
    //setcookie(session_id(), '', 0,'/');
    header("Location: ../pages/register.php");
?>