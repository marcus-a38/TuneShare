<?php 
    require_once "header.php"; 
?>

        <div class="container-fluid" id="register-main">
    
            <!-- Splash logo (left side) -->
            <div class="container" id="splash">
        
                <img src="../img/musicnotes.png" class="img" id="notes" />
                <h1>TuneShare</h1>
                <h3>
                    Connect with friends and expand your musical interests
                </h3>
        
            </div>
    
            <!-- Registration container (right side) -->
            <div class="container-fluid-vertical" id="register">
                <h1 id="form-heading">
                    Join Now
                </h1>
                <hr>
                <p id="form-alert"></p>
                <form action="" method="POST">
                    <!-- Visible fields -->
                    <input type="text" 
                           accept=""class="input-text" 
                           name="email" 
                           placeholder="email"
                           maxlength="255" />
                    <input type="text" 
                           class="input-text" 
                           name="username" 
                           placeholder="username"
                           maxlength="24" />
                    <input type="text" 
                           class="input-text" 
                           name="display-name" 
                           placeholder="display name"
                           maxlength="48" />
                    <span class="container" id="pword-group">
                        <input type="password" 
                               class="input-text" 
                               id="password" 
                               name="password" 
                               placeholder="password"
                               maxlength="72" />
                        <img id="pword-toggle" 
                             src="../img/showpass.png"
                             onclick="togglePassVisible()" />
                    </span>
                    <!-- Simple spam prevention -->
                    <div class="secret">
                        <small class="secret">
                            If you see this, leave the following 
                            field blank and contact the devs.
                        </small>
                        <label for="phone" class="secret"></label>
                        <input type="text" 
                               name="phone" 
                               value="" 
                               tabindex="-1" 
                               class="secret" />
                    </div>
                    <button class="pill-btn" type="submit">Submit</button>
                    <hr>
                    <p>
                        Already have an account? <a href="./login.php">Log in</a>
                    </p>
                    <small>
                        Ensure that you've reviewed our 
                        <a href="">Terms of Service</a> and 
                        <a href="#">Privacy Policy</a>
                    </small>
                </form>
            </div> <!-- div id="register" -->
        </div> <!-- div id="register-main" -->

        <!-- Functionality for show/hide password -->
        <script src='revealPassword.js'></script>
        <script src='formAlerts.js'></script>

<?php require_once "footer.php"; ?>


<?php // connect to API later

    const MYSQLI_DUPLICATE_ERRNO = 1062;

    function length_err(string $type) {
        
        $table = array(
            "Username" => array(
                0 => "3",
                1 => "24"
            ),
            "Display" => array(
                0 => "3",
                1 => "48"
            ),
            "Email" => array(
                0 => "3",
                1 => "255"
            ),
            "Password" => array(
                0 => "8",
                1 => "72"
            )
        ); // $table
        
        $msg = $type // e.g. "Username"
               . " is too short or long. Try a value between "
               . $table[$type][0] // e.g. "3"
               . " and "
               . $table[$type][1] // e.g. "72"
               . ' characters.';
        
        php_form_alert($msg);

    }

    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING) == 'POST') {
        
        require_once "./api/api.php";
        
        $db = new DatabaseObject();

        $honeypot = filter_input(INPUT_POST, 'phone');
    
        if ($honeypot) {
            exit ("Security measure failed.");
        }

        $username = filter_input(INPUT_POST, 'username');
        $display = filter_input(INPUT_POST, 'display-name');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        
        /* Validate the inputs */
        if (24 < strlen($username) || strlen($username) < 3 || !$username) {
            length_err("Username");
        }
        
        if (48 < strlen($display) || strlen($display) < 1 || !$display) {
            length_err("Display");
        }
        
        if (255 < strlen($email)) {
            length_err("Email");
        }
        
        if (72 < strlen($password) || strlen($password) < 8 || !$password) {
            length_err("Password");
        }
    
        if (!$email) {
            php_form_alert("Invalid email, please try again.");
        }
        
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        /* This will be replaced with API code */
        $query = "INSERT INTO user ("
                . "username,"
                . "display_name,"
                . "email,"
                . "password"
                . ") VALUES (?, ?, ?, ?)";
        
        $params = [$username, $display, $email, $hashed_password];
        
        try {
            $db->set_query($query, $params);
        }
        catch (mysqli_sql_exception) {
            if ($db->errno === MYSQLI_DUPLICATE_ERRNO) {
                php_form_alert("Username or email already exists.");
            }
            php_form_alert("Unspecified MySQL error.");
        }
        
        // put user data in session array
        $_SESSION['username'] = $username;
        $_SESSION['display'] = $display;
        $_SESSION['email'] = $email;
        $_SESSION['usertype'] = 1;
        $_SESSION['disabled'] = false;
        $_SESSION['private'] = false;
        
        #header("Location: index.php");
            
    }
   
?>