<?php

$user_flag = true;
require_once "../components/header.php";

?>


<div class="container-fluid" style="text-align: center; color: white;">
    <div class="container" id="profile">
        <h1 class="user-dname"></h1> 
        <span>@</span><p style="display: inline" class="user-uname"></p><br>
        <small>
            Account created on
            <span class="user-date">&nbsp;</span>
            at
            <span class="user-time">&nbsp;</span>.
        </small>
        <p class="user-bio"></p> <!-- Bio -->
        <div class="user-social"></div>
        <!-- <?php if (isset($profile_self)): ?>
        <button class="user-edit">Edit Info</button>
        <?php endif; ?> -->
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

<script src="../api/js/api.js"></script>

<?php

require_once "../api/api.php";
$user = filter_input(INPUT_GET, 'u', FILTER_UNSAFE_RAW);
if (!$user) { header("Location: index.php"); exit; }
echo "<script>getProfile('".$user."')</script>";
require_once "../components/footer.php";

?>

