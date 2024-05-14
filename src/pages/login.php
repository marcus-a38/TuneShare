<?php require_once "../components/header.php"; ?>

<div class="container-fluid" id="login-main">
    <div class="container" id="login-inner">
        <h1>TuneShare</h1>
        <hr>
        <p id="form-alert"></p>
        <form action="" method="POST">
            <input name="action"
                   value="login" 
                   class="secret" 
                   readonly />
            <input type="text" 
                   class="input-text" 
                   name="account" 
                   placeholder="username or email"
                   maxlength="255" 
                   required />
            <div class="container" id="pword-group">
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
                will a developer ask you for your password.
            </small>
        </form>
    </div>
</div>

<script src='../js/revealPassword.js'></script>
<script src='../js/formAlerts.js'></script>

<?php 
    require_once "../components/footer.php";
    require_once "../api/api.php";
?>