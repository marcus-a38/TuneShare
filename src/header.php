<!DOCTYPE html>
<?php

//$is_https=false;
//if (isset($_SERVER['HTTPS'])) $is_https=$_SERVER['HTTPS'];
//if ($is_https !== "on")
//{
 //   header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
 //   exit(1);
//}

// utility function
    function php_form_alert(string $msg) {
        echo '<script type="text/javascript">formAlert("' . $msg . '");</script>';
        exit();
    }
    
    session_start();
    
    if(isset($_SESSION['username']) && !isset($user_flag)) {
        header("Location: index.php");
    }
    
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
                <?php if (isset($_SESSION['username'])){
                    echo "<a href='index.php' id='logo'>TuneShare</a>";
                    require_once "navigation.php"; 
                }
                ?>
            </div>