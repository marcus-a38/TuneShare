<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>TuneShare</title>
        <?php include 'header.php'; ?>
    </head>
    <body style="background-color:#250057">
        <div class="w3-container w3-text-black" style="width:50%; margin-left:25%; margin-top:100px; margin-bottom:50px; background-color:#e3e3e3;">
            <p>
                <center>
                 <table class="w3-table-all">
                    <tr> 
                        <th>Action ID</th>
                        <th>Moderator ID</th>
                        <th>Moderator Username</th>
                        <th>Action Type</th>
                        <th>Description</th>
                        <th>Time</th>
                    </tr>
                    <?php                      
                        require 'DBConnect.php';

                        $statement = $conn->prepare(
                        "SELECT moderation_record.*, user.username FROM moderation_record JOIN user ON moderation_record.moderator_id = user.id ORDER BY moderation_record.date DESC;");
                        $statement->execute();
                        $result = $statement->get_result();

                        if ($result->num_rows > 0) {
                            while ($r = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $r['id'] . '</td>';
                                echo '<td>' . $r['moderator_id'] . '</td>';
                                echo '<td>' . $r['username'] . '</td>';
                                echo '<td>' . $r['action'] . '</td>';
                                echo '<td>' . $r['description'] . '</td>';
                                echo '<td>' . $r['date'] . '</td>';
                                echo '</tr>';
                            }
                        }

                        $statement->close();
                        $conn->close();
                    ?>
                </table>
                               
                <div style="padding: 20px">
                    <a href="moderation.php" class="w3-bar-item w3-btn w3-black" style="width:33.3%">Back</a>
                </div>
                </center>
            </p>
        </div>
    </body>
</html>
