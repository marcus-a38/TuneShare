<?php

$user_flag = true;
require_once "header.php";

if (!isset($_SESSION['username'])) {
    header("Location: register.php");
}

 /**
 * 
 * Post template elements that need functionality:
 * 
 *      post-container
 *      |
 *      |-- post-user (top details)
 *      |   |-- post-dname (display name)
 *      |   |-- post-time (timestamp)
 *      |
 *      |-- post (post itself)
 *          |-- post-top (first layer)
 *              |-- post-details (all song information)
 *          |       |-- post-title
 *          |       |-- post-artist
 *          |       |-- post-genres
 *          |   
 *          |-- post-text 
 *          |   |-- post-body (contains actual post text)
 *          |
 *          |-- post-btm: like, dislike, comment, share (heavily rely on slugs)
 */
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
                <p class="post-time">o</p>
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
                <img src="../img/up.png" 
                     alt="Like" 
                     class="post-action hover-darken" 
                />
                <span class="post-karma"></span>
                <img src="../img/down.png" 
                     alt="Dislike" 
                     class="post-action hover-darken" 
                />
                <span class="divider"></span>
                <img src="../img/comment.png" 
                     alt="Reply" 
                     class="post-action hover-darken" 
                />
                <img src="../img/share.png" 
                     alt="Share" 
                     class="post-action hover-darken" 
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
<img src="../img/add.png" id="new-post" class="hover-darken" onclick="newPost()" />

<script src="generateFeed.js"></script>

<script>

function toggleColor() {
    var toggler = document.getElementById("toggle-dark");
    if (toggler.getAttribute('src') === "../img/moon.png") {
        toggler.src = "../img/sun.png";
    } else {
        toggler.src = "../img/moon.png";
    }
}

function toggleFeed() {
    
}

async function sharePost(text) {
    
    try {
        await navigator.clipboard.writeText(text);
    } catch (err) {
        console.error(err.message);
    }
    
}

async function vote(is_dislike) {
    
    
    
}

</script>

<?php 

require_once "footer.php"; 

if (isset($_POST["create_post"])) {
    
}
if (isset($_POST["reply_post"])) {
    
}
if (isset($_POST["vote_post"])) {
    
}


?>

