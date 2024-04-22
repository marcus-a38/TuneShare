<?php $profile_self = true; require_once "header.php"; ?>

<script src="api.js"></script>
<script>getProfileSelf(<?php echo $_SESSION['userId'] ?>);</script>

<?php require_once "footer.php"; ?>