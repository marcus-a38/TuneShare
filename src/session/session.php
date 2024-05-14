<?php

// From PHP docs

// Credits to Ivan Kristianto for the rand_float algorithm
// https://www.ivankristianto.com/php-snippet-code-to-generate-random-float-number/
function rand_float($min, $max) {
    
    if ($min > $max) {
        $temp = $min;
        $min = $max;
        $max = $temp;
    } else if ($min == $max) {
        $max += 0.25;
    }
    
    return ( $min + rand() / getrandmax() * ($max - $min) );
    
}

function tuneshare_session_start() {
    
    ini_set('session.use_trans_sid', 0);
    ini_set('session.use_only_cookies', 1);    
    session_start();
    
    if (isset($_SESSION['DESTROY']) && $_SESSION['DESTROY'] < time() - 60) {
        session_destroy();
        session_start();
    }
    
    if (isset($_SESSION['NEW_ID'])) {
        
        // Commit current session and 
        tuneshare_session_regenerate_id();
        session_commit();
        
        session_id($_SESSION['NEW_ID']);
        
        ini_set('session.use_strict_mode', 0);
        tuneshare_session_start();
        ini_set('session.use_strict_mode', 1);
        
    }
}

/*
 * 
 * 
 * 
 * 
 */

function tuneshare_session_regenerate_id() {
    // Call session_create_id() while session is active to 
    // make sure collision free.
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['DESTROY'] = time();
    $_SESSION['NEW_ID'] = md5(microtime(true)**rand_float(0.1, 1.0));
    
    unset($_SESSION['DESTROY']);
    unset($_SESSION['NEW_ID']);
    
}

function session_auth() {
    
    try {
        
        if ($_SESSION['ipAddr'] != get_ip()) {
            
        }
        
        if ($_SESSION['userAgt'] != get_uag()) {
            
        }
        
        if (!isset($_SESSION['userId'])) {
            
        }
        
        
    } catch (Exception $e) {
        return false;
    }
    
}