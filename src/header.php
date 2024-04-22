<!DOCTYPE html>
<?php

//$is_https=false;
//if (isset($_SERVER['HTTPS'])) $is_https=$_SERVER['HTTPS'];
//if ($is_https !== "on")
//{
 //   header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
 //   exit(1);
//}

    
    session_start();
    
    #if(isset($_SESSION['username']) && !isset($user_flag)) {
    #    header("Location: index.php");
    #}
    
?>

<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <main class="container-fluid">
            <div class="container-fluid" id="toolbar">  
                <img src="../img/sun.png" id="toggle-dark" class="hover-darken"
                     onclick="toggleColor()" style="display:inline-block" />
                <?php 
                    if (isset($_SESSION['username'])){
                        require_once "navigation.php"; 
                        echo "</div>";
                        echo "<div style='text-align: center'>"
                        . "<a href='index.php' id='logo'>TuneShare</a>"
                                . "</div>";
                    }
                    else {
                        echo "</div>";
                    }
                ?>
                
            </div>