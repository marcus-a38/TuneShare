<?php
require_once "DatabaseObject.php";

$_AJAX = [];

function receive_json() {
    global $_AJAX;
    $in = file_get_contents("php://input");
    foreach (json_decode($in, true) as $key => $value) {
        $_AJAX[$key] = $value;
    }
}

/*
 * 
 * 
 * 
 * 
 */
function php_form_alert(string $msg) {
    echo '<script type="text/javascript">formAlert("'.$msg.'");</script>';
}

function length_err(string $type) {
        
        $table = [
            "Username" => [
                0 => "3",
                1 => "24"
            ],
            "Display name" => [
                0 => "3",
                1 => "48"
            ],
            "Email" => [
                0 => "3",
                1 => "255"
            ],
            "Password" => [
                0 => "8",
                1 => "72"
            ]
        ]; // $table
        
        $msg = $type // e.g. "Username"
               . " is too short or long. Try a value between "
               . $table[$type][0] // e.g. "3"
               . " and "
               . $table[$type][1] // e.g. "72"
               . ' characters.';
        
        php_form_alert($msg);

    }


/*
 * 
 * 
 * 
 * 
 */
function get_upost_params() {
    $user = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW);
    $slug = filter_input(INPUT_POST, 'post-slug', FILTER_UNSAFE_RAW);
    return [$user, $slug];
}



function get_parent_id() {
    //
}


/*
 *
 * 
 * 
 * 
 * 
 * 
 * 
 */
function register(DatabaseObject &$db) {

    // Honeypot triggered
    if (filter_input(INPUT_POST, 'phone')) {
        return php_form_alert("Suspicious registration attempt aborted.");
    }
    
    // Verify username length
    $username = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW);
    if (24 < strlen($username) || strlen($username) < 3 || !$username) {
        return length_err('Username');
    }
    
    // Next validate the email ...
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if (!$email) {
        return php_form_alert("Email is invalid. Please try again.");
    }
    // ... and verify its length
    if (255 < strlen($email)) {
        return length_err('Email');
    }

    // Now sanitize the email
    $clean_email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    // Query to check if a username or email already exists
    $check_sql = "SELECT username, email FROM user
                  WHERE username = ?
                  OR email = ?";
    $check_params = [$username, $clean_email];
    $existing = $db->get_query($check_sql, $check_params);

    // Check if the username OR email is taken
    if ($existing) {
        if ($existing['username'] == $username) {
            $val = "Username " . $existing['username'];
        } 
        else {
            $val = "Email " . $existing['email'];
        }
        return php_form_alert($val . "is taken. Try a different value.");
    }
    
    // Verify display name length
    $display = filter_input(INPUT_POST, 'display_name', FILTER_UNSAFE_RAW);
    if (48 < strlen($display) || strlen($display) < 1 || !$display) {
        return length_err('Display name');
    }
    
    // Verify PW length
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
    if (72 < strlen($password) || strlen($password) < 8 || !$password) {
        return length_err('Password');
    }
    
    // Hash the PW
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);    
        
    // Insert new user
    $register_sql = "INSERT INTO user (
                        username,
                        display_name,
                        email,
                        password
                    ) VALUES (?, ?, ?, ?)";  
    $register_params = [$username, $display, $clean_email, $hashed_password];
    
    if ($db->set_query($register_sql, $register_params)) {
        // Set session vars
        $_SESSION['userId'] = $db->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['display'] = $display;
        $_SESSION['email'] = $clean_email;
        $_SESSION['isMod'] = 1;
        $_SESSION['isPriv'] = false;
    } else {
        return php_form_alert("Error signing up. Please try again later.");
    }
            
} // function register()

/*
 *
 * 
 * 
 * 
 * 
 * 
 * 
 */
function login(DatabaseObject &$db) {
    
    $account = filter_input(INPUT_POST, 'account', FILTER_UNSAFE_RAW);
    $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
        
    
    if (!filter_var($account, FILTER_VALIDATE_EMAIL)) {
        $type = "username";
    }
    else { // is email
        $account = filter_var($account, FILTER_SANITIZE_EMAIL);
        $type = "email";
    } 

    $login_params = [$account];
    
    $sql = "SELECT
                id,
                username,
                display_name,
                email,
                password,
                is_mod,
                is_disabled,
                is_private
            FROM user 
            WHERE ".$type." = ?";
    
    $existing = $db->get_query($sql, $login_params)[0];
    
    if (!$existing) {
        echo "Account with " . $type . " '".$account."'" . " does not exist.";
        return false;
    }
    
    if (!password_verify($password, $existing['password'])) {
        echo "Incorrect password for '".$account."'.";
        return false;
    }
    $_SESSION['userId'] = $existing['id'];
    $_SESSION['username'] = $existing['username'];
    $_SESSION['display'] = $existing['display_name'];
    $_SESSION['email'] = $existing['email'];
    $_SESSION['isMod'] = $existing['is_mod'];
    $_SESSION['isPriv'] = $existing['is_private'];
    
    return true;
    
} // function login()

/*
 *
 * 
 * 
 * 
 * 
 * 
 * 
 */
function load_feed(DatabaseObject &$db) {
    
    session_start();

    // Currently selects 50 most recent posts, will need to be updated
    // to select 50 "algorithm-produced" posts.
    $sql = "SELECT
                post.id AS post_id,
                slug_hash AS slug,
                post.user_id,
                user.display_name,
                DATEDIFF(now(), post.time_posted) AS elapsed,
                song.title AS song_title,
                artist.name AS artist_name,
                GROUP_CONCAT(genre.name) AS genres,
                post.content,
                SUM(CASE
                    WHEN vote.is_upvote = 1 THEN 1
                    WHEN vote.is_upvote IS NULL THEN 0
                    ELSE -1 END
                ) AS karma
            FROM post
    
            INNER JOIN user
                ON post.user_id = user.id
            INNER JOIN song 
                ON post.song_id = song.id
            INNER JOIN album 
                ON song.album_id = album.id
            INNER JOIN artist
                ON album.artist_id = artist.id
            INNER JOIN album_genre
                ON album_genre.album_id = album.id
            INNER JOIN genre
                ON genre.id = album_genre.genre_id
            LEFT JOIN vote
                ON vote.post_id = post.id   
        
            WHERE 
                post.parent_id IS NULL
            GROUP BY
                post.id
            ORDER BY 
                post.time_posted DESC
            LIMIT 50 OFFSET ?";
    
    $response = $db->get_query($sql, [$_SESSION['feedLoadRefrCt']++]);
    echo json_encode($response); // to AJAX
    
} // function load_feed()

/*
 *
 * 
 * 
 * 
 * 
 * 
 * 
 */

/*
 *
 * 
 * 
 * 
 * 
 * 
 * 
 */
function get_one_post(DatabaseObject &$db) {
    
    global $_AJAX;
    
    $slug = filter_var($_AJAX['slug'], FILTER_UNSAFE_RAW);
    
    $sql = "SELECT
                post.id AS post_id,
                slug_hash AS slug,
                post.user_id,
                user.display_name,
                DATEDIFF(now(), post.time_posted) AS elapsed,
                song.title AS song_title,
                artist.name AS artist_name,
                GROUP_CONCAT(genre.name) AS genres,
                post.content,
                SUM(CASE
                    WHEN vote.is_upvote = 1 THEN 1
                    WHEN vote.is_upvote IS NULL THEN 0
                    ELSE -1 END
                ) AS karma
            FROM post
    
            INNER JOIN user
                ON post.user_id = user.id
            INNER JOIN song 
                ON post.song_id = song.id
            INNER JOIN album 
                ON song.album_id = album.id
            INNER JOIN artist
                ON album.artist_id = artist.id
            INNER JOIN album_genre
                ON album_genre.album_id = album.id
            INNER JOIN genre
                ON genre.id = album_genre.genre_id
            LEFT JOIN vote
                ON vote.post_id = post.id
                
            WHERE slug_hash = ?";
    
    $response = $db->get_query($sql, [$slug])[0];
   //if (!$response) { header("Location: index.php"); }
    
    echo json_encode($response);
    
}  //function get_one_post()

/*
 *
 * 
 * 
 * 
 * 
 * 
 * 
 */
function delete_post(DatabaseObject &$db) {
    $sql = "DELETE FROM post
            WHERE username = ? AND slug = ?";
    $params = get_upost_params();
    return $db->set_query($sql, $params);
}

/*
 *
 * 
 * 
 * 
 * 
 * 


/*
 *
 * 
 * 
 * 
 * 
 * 
 * 
 */
function vote_post(DatabaseObject &$db) {
    
    session_start(); // to be changed
    
    global $_AJAX;
    
    $vote_type = filter_var($_AJAX['vote'], FILTER_SANITIZE_NUMBER_INT);
    $post_slug = filter_var($_AJAX['slug'], FILTER_UNSAFE_RAW);
    
    // Select the post being voted on using its slug
    $post_params = [$post_slug];
    $post_sql = "SELECT id FROM post
                   WHERE slug_hash = ?";
    $pid = $db->get_query($post_sql, $post_params)[0]['id'];
    
    $uid = $_SESSION['userId'];
    
    // Check if the vote already exists
    $existing_sql = "SELECT id FROM vote
                     WHERE user_id = ? AND post_id = ?";
    $existing_params = [$uid, $pid];
    $vid = $db->get_query($existing_sql, $existing_params);
    
    if ($vote_type === "0") { // Unset an existing vote when user revokes it
        $delete_params = [$vid[0]['id']];
        $delete_sql = "DELETE FROM vote
                       WHERE id = ?";
        $db->query_with($delete_sql, $delete_params);
    } 
    
    else {
        $vote_type = (intval($vote_type)+1) ? 0 : 1;
        
        if ($vid) { // Vote exists, so update it.
            $vid = $vid[0]['id'];
            $update_params = [$vote_type, $vid];
            $update_sql = "UPDATE vote
                           SET is_upvote = ?
                           WHERE post_id = ?";
            $db->query_with($update_sql, $update_params);
        }
        else { // Vote does not exist, so insert it.
            $insert_params = [$uid, $pid, $vote_type];
            $insert_sql = "INSERT INTO vote(
                                user_id,
                                post_id,
                                is_upvote
                            ) VALUES (?, ?, ?)";
            $db->set_query($insert_sql, $insert_params);
        }
    }
    
    echo json_encode([true]);
}

function get_user_details(DatabaseObject &$db) {
    
    global $_AJAX;
    
    $sql = "SELECT
                username,
                display_name,
                DATE(creation_date) as date,
                TIME(creation_date) as time
            FROM user
            WHERE username = ?";
    $params = [$_AJAX['username']];
    
    $response = $db->get_query($sql, $params)[0];
    echo json_encode($response);
    
}

/*
 * For access to own profile
 * 
 * 
 */
function get_username(DatabaseObject &$db) {
    
    global $_AJAX;
    
    $sql = "SELECT username
            FROM user
            WHERE id = ?";
    $user_id = $_AJAX['user_id'];
    $response = $db->get_query($sql, [$user_id])[0];
    echo json_encode($response);
    
}

$conn = new DatabaseObject();

$actions = [
    "signup" => "register",
    "login" => "login",
    "feed" => "load_feed",
    "vote" => "vote_post",
    "post" => "get_one_post",
    "user" => "get_user_details",
    "userself" => "get_username"
];

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_UNSAFE_RAW) == 'POST'){
    $action = filter_input(INPUT_POST, 'action', FILTER_UNSAFE_RAW);
    if (!$action) {
        receive_json();
        $action = $_AJAX['action'];
    }
    call_user_func_array($actions[$action], [&$conn]);
    exit;
} 

?>