<html>
    <head>
        <meta charset="UTF-8">
        <title>TuneShare</title>
        <?php include 'header.php'; ?>
    </head>
    <body>
         <div class="w3-container w3-blue w3-sans-serif w3-text-black" style="width:50%; margin-left:25%; margin-top:100px; margin-bottom:50px">
             <b><center>
             <div class="home" style="padding: 20px">
                <p>
                    <?php
                    $post_id = filter_input(INPUT_POST, 'post_id');
                    $moderator_id = filter_input(INPUT_POST, 'moderator_id');
                    $description = filter_input(INPUT_POST, 'desc');
                    $action = "HIDE_POST";

                    //SQL
                    require 'DBConnect.php';
                    if($successful) {
                        //Hide the post
                        $statement = $conn->prepare("UPDATE post SET hidden = 1 WHERE id = ?");
                        $statement->bind_param("i", $post_id);
                        if ($statement->execute() == TRUE) {
                          echo "Post " .$post_id. " was hidden successfully.";
                        }else {
                          echo "Post " .$post_id. " could not be properly deleted.";
                        }
                        
                        echo "<br>";
                        
                        //Record the moderation action
                        $statement_2 = $conn->prepare("INSERT INTO moderation_record (id, post_id, moderator_id, action, description) VALUES (0, ?, ?, ?, ?)");
                        $statement_2->bind_param("iiss", $post_id, $moderator_id, $action, $description);
                        if ($statement_2->execute() == TRUE) {
                          echo "Moderation was recorded successfully.";
                        }else {
                          echo "Moderation could not be properly recorded!";
                        }

                        //Close connection
                        $statement->close();
                        $statement_2->close();
                        $conn->close();
                    }
                    ?>
                </p>
                <p>
                    <a href="./moderation.php" class="w3-bar-item w3-btn w3-black" style="width:33.3%">Back</a>
                </p>
            </div>
            </center></b>
        </div>
    </body>
</html>
