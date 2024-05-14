<?php $user_flag = true; $profile_self = true; require_once "../components/header.php"; ?>

<script src="../api/js/api.js"></script>
<script>getProfileSelf(<?php echo $_SESSION['userId'] ?>);</script>

<?php require_once "../components/footer.php"; ?>