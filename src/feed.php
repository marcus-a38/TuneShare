<?php

require_once "api/api.php";

$db = new DatabaseObject();
$query = "
    SELECT 
        post.id AS post_id, 
        slug_hash AS slug, 
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
    LIMIT 50 OFFSET ?;
";

$response = $db->get_query($query, [strval($post_load_ct++)]);

if ($response) {
    echo json_encode($response);
} else {
    // error
}

?>