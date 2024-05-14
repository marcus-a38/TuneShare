var passwordToggle = document.getElementById("pword-toggle");
var passwordField = document.getElementById("password");
    
    function togglePassVisible() {
     
        if (passwordField.type === "password") {
            passwordField.type = "text";
            passwordToggle.src = "../../img/hidepass.png";
        } else {
            passwordField.type = "password";
            passwordToggle.src = "../../img/showpass.png"; 
        }
    }