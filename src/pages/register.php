<?php 
    require_once "../components/header.php"; 
?>

        <div class="container-fluid" id="register-main">
    
            <!-- Splash logo (left side) -->
            <div class="container" id="splash">
        
                <img src="../../img/musicnotes.png" class="img" id="notes" />
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
                    <input name="action"
                           value="signup" 
                           class="secret" 
                           readonly />
                    <input type="text" 
                           accept=""class="input-text" 
                           name="email" 
                           placeholder="email"
                           maxlength="255" 
                           required />
                    <input type="text" 
                           class="input-text" 
                           name="username" 
                           placeholder="username"
                           maxlength="24"
                           required />
                    <input type="text" 
                           class="input-text" 
                           name="display_name" 
                           placeholder="display name"
                           maxlength="48" 
                           required />
                    <span class="container" id="pword-group">
                        <input type="password" 
                               class="input-text" 
                               id="password" 
                               name="password" 
                               placeholder="password"
                               maxlength="72"
                               required />
                        <img id="pword-toggle" 
                             src="../../img/showpass.png"
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
                        Already have an account? <a href="login.php">Log in</a>
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
        <script src='../js/revealPassword.js'></script>
        <script src='../js/formAlerts.js'></script>

<?php 
    require_once "../components/footer.php";
    require_once "../api/api.php" 
?>


<?php // connect to API later

   
?>