<?php 
    require_once "header.php";
    session_start();
?>

<div class="container-fluid" id="login-main">
    <div class="container" id="login-inner">
        <h1>TuneShare</h1>
        <hr>
        <p id="form-alert"></p>
        <form action="" method="POST">
            <input type="text" 
                   class="input-text" 
                   name="account" 
                   placeholder="username or email"
                   maxlength="255" />
            <div class="container" id="pword-group">
                <input type="password" 
                       class="input-text" 
                       id="password" 
                       name="password" 
                       placeholder="password"
                       maxlength="72" />
                <img id="pword-toggle" 
                     src="../img/showpass.png"
                     onclick="togglePassVisible()" />
            </div>
            <div class="secret"> <!-- Using `phone` as a trick name -->
                <small class="secret">
                    If you see this, leave the following 
                    field blank and contact the devs.
                </small>
                <br>
                <label for="phone" class="secret"></label>
                <input type="text" 
                       name="phone" 
                       value="" 
                       tabindex="-1" 
                       class="secret" />
            </div>
            <button class="pill-btn" type="submit">Submit</button>
            <hr>
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
            <small>
                Never give out your password. Under no circumstances<br>
                will a developer ask you for your password.</a>
            </small>
        </form>
    </div>
</div>

<script src='revealPassword.js'></script>
<script src='formAlerts.js'></script>

<?php require_once "footer.php" ?>

<?php

    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) == 'POST') {

        $connection = new mysqli(
            "localhost",
            "root",
            "",
            "tuneshare"
        );

        $user = filter_input(INPUT_POST, 'account');
        $password = filter_input(INPUT_POST, 'password');
    
        // determine whether the input is a username or email
        $col = filter_var($user, FILTER_VALIDATE_EMAIL) ? "email" : "username";
    
        /* This will be replaced with API code */
        $stmt = "SELECT"
                . " username,"
                . " display_name,"
                . " email,"
                . " password,"
                . " user_type,"
                . " is_disabled,"
                . " is_private"
                . " FROM user WHERE " . $col . " = " . "?";
        
        if (!$res = $connection->execute_query($stmt, array($user))) {
            php_form_alert("Account for '" . $user . "' does not exist.");
        } 
    
        $rows = $res->fetch_array(MYSQLI_ASSOC);
    
    
        if (!password_verify($password, $rows['password'])) {
            php_form_alert("Invalid password for account '" . $user . "'.");
        }
    
        // save user data to session array
    
        $_SESSION['username'] = $rows['username'];
        $_SESSION['display'] = $rows['display_name'];
        $_SESSION['email'] = $rows['email'];
        $_SESSION['usertype'] = $rows['user_type'];
        $_SESSION['disabled'] = $rows['is_disabled'];
        $_SESSION['private'] = $rows['is_private'];
    
        echo "<h1>Login successful for:</h1>"
             . "<p>" . $_SESSION['username'] ."</p>"
             . "<p>" . $_SESSION['display'] ."</p>"
             . "<p>" . $_SESSION['email'] . "</p>"
             . "<p>" . $_SESSION['usertype'] ."</p>"
             . "<p>" . $_SESSION['disabled'] ."</p>"
             . "<p>" . $_SESSION['private'] . "</p>";
        
    }

?>