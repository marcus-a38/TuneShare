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
                    $post_id = filter_input(INPUT_GET, 'post_id');

                    //SQL
                    require 'DBConnect.php';
                    if($successful) {
                        $statement = $conn->prepare("UPDATE post SET hidden = 1 WHERE id = ?");
                        $statement->bind_param("i", $post_id);
                        
                        if ($statement->execute() == TRUE) {
                          echo "Post " .$post_id. " was hidden successfully.";
                        }else {
                          echo "Post " .$post_id. " could not be properly deleted.";
                        }

                        //Close connection
                        $statement->close();
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
