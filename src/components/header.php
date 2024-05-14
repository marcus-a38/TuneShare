<!DOCTYPE html>
<?php
    
    session_start();
    
    if (isset($_SESSION['userId']) && !isset($user_flag)) {
        header("Location: index.php");
    }
    
?>

<html>
    <head>
        <link rel="stylesheet" href="../style/style.css">
        <script src="../js/loading.js"></script>
    </head>
    <body onload="loaded()">
        <div class="load">
            <div class="spinner"></div>
        </div>
        <main class="container-fluid">
            <div class="container" id="popups-toggler" onclick='toggPopups()'></div>
            <div class="container-fluid" id="toolbar">  
                <img src="../../img/sun.png" id="toggle-dark" class="hover-darken"
                     onclick="toggleColor()" style="display:inline-block" />
                <?php if (isset($_SESSION['username'])): 
                    require_once "navigation.php"; ?>
                    </div>
                    <div style='text-align: center'>
                        <a href='index.php' id='logo'>TuneShare</a>
                    </div>
                <?php else: ?>
                    </div>
                <?php endif; ?>