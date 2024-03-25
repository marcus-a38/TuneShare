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
                <div style="padding: 10px; padding-bottom: 20px">
                    <b>Posts with Reports</b>
                </div>
                <table class="w3-table-all">
                    <tr> 
                        <th>Post ID</th>
                        <th>Content</th>
                        <th>Reports</th>
                        <th>View</th>
                    </tr>
                    <?php                      
                        require 'DBConnect.php';

                        $statement = $conn->prepare(
                        "SELECT p.*, COUNT(r.post_id) AS report_count FROM post p
                        LEFT JOIN report r ON p.id = r.post_id
                        WHERE p.hidden = 0
                        GROUP BY p.id
                        HAVING report_count > 0
                        ORDER BY report_count DESC;");
                        $statement->execute();
                        $result = $statement->get_result();

                        if ($result->num_rows > 0) {
                            while ($r = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $r['id'] . '</td>';
                                echo '<td>' . $r['content'] . '</td>';
                                echo '<td>' . $r['report_count'] . '</td>';
                                echo '<td>' . '<a href="./moderatePost.php?post_id=' . $r['id'] . '" class="w3-bar-item w3-btn w3-black">More</a>' . '</td>';
                                echo '</tr>';
                            }
                        }

                        $statement->close();
                        $conn->close();
                    ?>
                </table>
                               
                <div style="padding: 20px">
                    <a href="../../index.php" class="w3-bar-item w3-btn w3-black" style="width:33.3%">Back</a>
                </div>
                </center>
            </p>
        </div>
    </body>
</html>
