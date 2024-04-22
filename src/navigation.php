<nav>
    <div id="p-popup" onclick="openPopup()">
        <a href="#" class="hover-darken">@<?php echo $_SESSION['username'] ?></a>
        <span id="popup">
            <div class="popup-option">
                <a href="profileSelf.php">account</a>    
            </div>
            <hr>
            <?php if ($_SESSION['isMod']): // moderator ?> 
                <div class="popup-option">
                    <a href="#">moderation</a>
                </div>
                <hr>
            <?php endif; ?>
            <div class="popup-option">
                <a href="#">activity&nbsp;<span id="notifications">0</span></a>
            </div>
            <hr>
            <div class="popup-option">
                <form id="logout" action="logout.php">
                    <input type="submit" value="log out" />
                </form>
            </div>
        </span>
    </div>
</nav>