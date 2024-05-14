<?php

$user_flag = true;
require_once "../components/header.php";

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
<p id="form-alert" style="text-align: center; color: white;"></p>
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
                <img src="../../img/like.png" 
                     alt="Like" 
                     class="post-action post-like hover-darken" 
                />
                <span class="post-karma"></span>
                <img src="../../img/dlike.png" 
                     alt="Dislike" 
                     class="post-action post-dislike hover-darken" 
                />
                <span class="divider"></span>
                <img src="../../img/comment.png" 
                     alt="Reply" 
                     class="post-action post-reply hover-darken" 
                />
                <img src="../../img/share.png" 
                     alt="Share" 
                     class="post-action post-share hover-darken" 
                /> <!-- share post func -->
            </div>
        </div>
    </div>
    </template>
</div>

<div class="container" id='create-post'>
    <div style='text-align: left;'>
        <img src='../../img/arrow.png' alt='back' onclick='toggPopups()' />
    </div>
    <p>Creating a New Post</p>
    <form method="POST" action="">
        <input name="action" value="newpost" class="secret" readonly />
        <select name="song_id" class='input-select'>
            <option value="-1" disabled selected>song</option>
            <option value="1" style='color: black;'>
                Billie Jean - Michael Jackson
            </option>
        </select>
        <textarea 
            class='input-textarea'
            name="txt_body" 
            rows="8" cols="30" 
            maxlength='255'
            placeholder='your message...'
            contenteditable
        ></textarea>
        <button class="pill-btn" type="submit">Submit</button>
    </form>
</div>
<div class="container" id='reply-post'>
    <div style='text-align: left;'>
        <img src='../../img/arrow.png' alt='back' onclick='toggPopups()' />
    </div>
    <p id='reply-user'></p>
    <div id="parent-txt" class="container hidden">
        <p></p>
    </div>
    <form method="POST" action="">
        <input name="action" value="reply" class="secret" readonly />
        <textarea 
            class='input-textarea'
            name="txt_body" 
            rows="8" cols="30" 
            maxlength='255'
            placeholder='your message...'
            contenteditable
        ></textarea>
        <button class="pill-btn" type="submit">Submit</button>
    </form>
</div>

<img 
    src="../../img/add.png" 
    id="new-post" 
    class="hover-darken" 
    onclick="popupTogg(popups.newpost)" 
/>

<script src="../api/js/api.js"></script>
<script>generateFeed(); getAvailability();</script>

<?php 

require_once "../components/footer.php"; 
require_once "../api/api.php";

?>