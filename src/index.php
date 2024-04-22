<?php

$user_flag = true;
require_once "header.php";

if (!isset($_SESSION['username'])) {
    header("Location: register.php");
}

$_SESSION['feedLoadRefrCt'] = 0;

?>

<div class="container-fluid" id="feed-modes">
    <a href="?ftype=friends" class="active">Friends</a>
    <span>|</span>
    <a href="?ftype=home">Suggested</a>
</div>
<div class="container-fluid" id="feed">
    
    <template id="post-template">
        <div class="post-container">
            <div class="post-user">
                <a href="#" class="post-dname hover-darken"></a>
                <span style="padding: 0 0.25rem; color: white;">&bull;</span>
                <p class="post-time"></p>
            </div>
        <div class="post">
            <div class="post-top">
                <div class="post-details">
                    <div class="post-song">
                        <a href="#" class="post-title hover-darken"></a>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <small class="post-artist"></small>
                    </div>
                    <div class="post-genres"> <!-- change to class post-genres -->
                    </div>
                </div>
            </div>
            <div class="post-txt">
                <p class="post-body"></p> <!-- change to post-text-inner -->
            </div>
            <div class="post-btm">
                <img src="../img/like.png" 
                     alt="Like" 
                     class="post-action post-like hover-darken" 
                />
                <span class="post-karma"></span>
                <img src="../img/dlike.png" 
                     alt="Dislike" 
                     class="post-action post-dislike hover-darken" 
                />
                <span class="divider"></span>
                <img src="../img/comment.png" 
                     alt="Reply" 
                     class="post-action post-comment hover-darken" 
                />
                <img src="../img/share.png" 
                     alt="Share" 
                     class="post-action post-share hover-darken" 
                     onclick="" 
                /> <!-- share post func -->
            </div>
        </div>
    </div>
    </template>
</div>
    
<div class="container hidden">
        <div id="create-post">
            <form method="POST" action="">
                <input
                    type="text" 
                    name=""
                    placeholder=""
                />
                <input 
                    type="text"
                    name=""
                    placeholder=""
                />
                <input 
                    type="text"
                    name=""
                    placeholder=""
                />
                <button type="submit" name="create_post"></button>
            </form>
    </div>
</div>
<img src="../img/add.png" id="new-post" class="hover-darken" onclick="" />

<script src="api.js"></script>
<script>generateFeed();</script>

<script>
function toggleColor() {
    var toggler = document.getElementById("toggle-dark");
    if (toggler.getAttribute('src') === "../img/moon.png") {
        toggler.src = "../img/sun.png";
    } else {
        toggler.src = "../img/moon.png";
    }
}
</script>

<?php 

require_once "footer.php"; 
require_once "api/api.php";

?>