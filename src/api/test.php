<?php

require_once "Limiter.php";
session_start();

if (!isset($_SESSION['limiter'])) {
    $_SESSION['limiter'] = new Limiter(1.25, 5.0, 5);
}

?>

<form method="POST" action="">
    <button>New Request</button>
</form>

<?php 

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_UNSAFE_RAW) == 'POST') {
    $limiter = &$_SESSION['limiter'];
    $res = $limiter->make_new_req();
    $msg = "The new request was ";
    $msg .= ($res) ? "successful." : "unsuccessful.";
    echo "<p>".$msg."</p>";
    echo "<p>More details:</p>";
    echo "<p>Number of total slots: ".$limiter->get_num_slots()."</p>";
    echo "<p>Number of obsolete slots: ".$limiter->get_num_obs()."</p>";
}

?>