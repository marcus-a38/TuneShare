<?php

$user_flag = true;
require_once "header.php";

?>


<div class="container-fluid" style="text-align: center; color: white;">
    
    <div class="container">
        <h1><?php echo $_SESSION['display'] ?></h1> <!-- Username -->
        <p>@<?php echo $_SESSION['username'] ?></p>
        <small>Account created on ? at ?.</small>
    </div>
    
    <div class="container">
        
        <h1>Recent Posts</h1>
        
        <template id="">
            
            <div class="post-preview">
                
                <h3 class="preview-song"></h3>
                <p class="preview-blurb"></p>
                <a class="preview-link"></a>
                
            </div>
            
        </template>
        
        <div id="account-posts">
            <div id="row1">
                <div class="post-preview"></div>
                <div class="post-preview"></div>
                <div class="post-preview"></div>
            </div>
            <div id="row2">
                <div class="post-preview"></div>
                <div class="post-preview"></div>
                <div class="post-preview"></div>
            </div>
        </div>
        
        <a href="#" style="color: white;">See more</a>
        
    </div>
    
</div>


<?php

require_once "footer.php";

?>

