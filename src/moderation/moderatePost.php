<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>TuneShare</title>
        <?php include 'header.php'; ?>
    </head>
    <body style="background-color:#250057">
        <div class="w3-container w3-sans-serif w3-text-black" style="width:50%; margin-left:25%; margin-top:100px; margin-bottom:50px; background-color:#e3e3e3">
            <p>
                <?php
                $description = "Error!";
                ?>
                <center>
                    <div style="padding: 10px; padding-bottom: 20px">
                        <?php
                        $post_id = filter_input(INPUT_GET, 'post_id');
                        echo '<b>Post ' . $post_id . ' Reports</b>';
                        ?>
                    </div>
                <table class="w3-table-all">
                    <tr> 
                        <th>Post ID</th>
                        <th>Content</th>
                    </tr>
                    <?php                      
                        require 'DBConnect.php';

                        $post_statement = $conn->prepare("SELECT content FROM post WHERE id =?");
                        $post_statement->bind_param("i", $post_id);  
                        $post_statement->execute();
                        $post_result = $post_statement->get_result();
                        
                        while ($r = $post_result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $post_id . '</td>';
                            echo '<td>' . $r['content'] . '</td>';
                            echo '</tr>';
                        }

                        $post_statement->close();
                        $conn->close();
                    ?>
                </table>
                <br>
                <table class="w3-table-all">    
                    <tr> 
                        <th>Report ID</th>
                        <th>Reason</th>
                        <th>Details</th>
                        <th>Time</th>
                    </tr>
                    <?php                      
                        require 'DBConnect.php';

                        $report_statement = $conn->prepare("SELECT * FROM report WHERE post_id =?");
                        $report_statement->bind_param("i", $post_id);    
                        $report_statement->execute();
                        $report_result = $report_statement->get_result();

                        while ($r = $report_result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $r['id'] . '</td>';
                            echo '<td>' . $r['reason'] . '</td>';
                            echo '<td>' . $r['details'] . '</td>';
                            echo '<td>' . $r['timestamp'] . '</td>';
                            echo '</tr>';
                        }

                        $report_statement->close();
                        $conn->close();
                    ?>
                </table>
                <div style="padding: 20px; padding-bottom: 20px">
                <?php
                echo '<b>Moderation Action Description</b><br>';
                ?>
                </div>

                <form action="hidePostAction.php" method="POST">
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <input type="hidden" name="moderator_id" value="<?php echo 1; ?>">
        
                    <input type="text" class="w3-input" id="desc" name="desc">
                    <div style="padding: 20px">
                        <input type="submit" value="Hide Post" class="w3-bar-item w3-btn w3-black" style="width:20%">
                        <input type="submit" value="Clear Reports" class="w3-bar-item w3-btn w3-black" style="width:20%">
                        <a href="./moderation.php" class="w3-bar-item w3-btn w3-black" style="width:20%">Back</a>
                    <div>
                
                </form>

                </center>
            </p>
        </div>
    </body>
</html>
