<!DOCTYPE html>
<?php

// utility function
function php_form_alert(string $msg) {
        echo '<script type="text/javascript">formAlert("' . $msg . '");</script>';
        exit();
    }
?>

<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container-fluid" id="viewport">
            <div class="container-fluid" id="toolbar">
            
                <button aria-label="Toggle Dark Mode" onclick="toggleColor()">
                    <span id="color-mode"></span>
                </button>
            </div>