<?php

require_once "../src/api/api.php";

// User id for test user
const TEST_USER_ID = 1;

// Song id for test song - separate for readability
const TEST_SONG_ID = 1;

// Helper function for test_get_posts(), creates list of rows in HTML
function print_response(array $response) {
    
    // Counter variables
    $i = 0; // Used to label each post (e.g. "Post 1:")
    $j = 0; // Used to access attribute names from row
    
    // Each row will be numbered in a list
    echo "<ol>";
    
    // Iterate through each row in the response
    foreach($response as $row) {
        
        echo "<li>Post " . ++$i . ":</li><ul>";
        
        // Grab the attribute names
        $keys = array_keys($row);
        
        // List each attribute and the corresponding value
        foreach($row as $col) {
            echo "<li>" . $keys[$j++] . " -> " . $col . "</li>";
        }
        
        // Reset $j
        $j = 0;
        echo "</ul>"; // close
        
    }
    
    echo "</ol>"; // close
    
}

// Test INSERT capability (set_query)
function test_add_post(array $post_info) {
    
    global $connection;
    
    assert(sizeof($post_info) === 3, "Improper array length for post info");
    
    $query =  "INSERT INTO post ("
            . "user_id,"
            . "song_id,"
            . "content"
            . ") VALUES (?, ?, ?)";
    
    $request = $connection->prepare_request($query, $post_info);
    
    // Attempt to execute the query
    try {
        $response = $connection->set_query($request);
    }
    catch (mysqli_sql_exception $e) {
        exit("Error: " . $e);
    }
    
    assert($response, "<h3>1. Query failed.</h3>");
    
    echo "<h3>1. Post successfully submitted.</h3>";
    echo "<p>Details:</p><ol>"
         . "<li>User id: " . $post_info[0] . "</li>"
         . "<li>Song id: " . $post_info[1] . "</li>"
         . "<li>Content: " . $post_info[2] . "</li>"
         . "</ol>";
    
}

// Test SELECT capability (get_query)
function test_get_posts() {
    
    global $connection;
    
    $query = "SELECT * FROM post";
    $request = $connection->prepare_request($query, null);
    
    // Attempt to execute the query
    try {
        $response = $connection->get_query($request);
    }
    catch (mysqli_sql_exception $e) {
        exit("Error: " . $e);
    }
    
    assert($response, "<h3>2. Query failed.</h3>");
    
    echo "<h3>2. Query succeeded. " . sizeof($response) . " posts returned:</h3>";
    print_response($response);
    
}

?>