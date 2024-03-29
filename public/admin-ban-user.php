<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    die;
}

require_once "../connection.php";
require_once "../functions.php";

if (!is_user_admin($_SESSION["id"], $conn)) {
    $referrer = $_SERVER['HTTP_REFERER'];
    $msg = "Non sei admin";
    $msg = urlencode($msg);
    header("Location: $referrer?msg=$msg");
    die;
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="admin-dashboard.php" method="POST">
        <button type="submit">quit</button>
    </form>
    <br>
    <form action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
        <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user']))
            {
                $id = $_POST['user'];
                if(deleteUser($id,$conn)){
                    echo("<p>user correcfutty deleted</p>");
                }else{
                    echo("<p>error</p>");
                }
                

            }else{
                echo("<br>");
            }


            $sql="SELECT id, username from users";
            $result = $conn->query($sql);
            if (mysqli_num_rows($result))
            {
                    echo '<select class="form-select" name="user">';
                    while($row = $result->fetch_assoc())
                    {
                            echo '<option value="'.$row['id'].'">'.$row['username'].'</option>';
                    }
                    echo '</select>';
            }
            
        ?>
        <button type="submit">delete</button>
        <br>
        <br>
        <p>confirm</p>
        <input type="checkbox" required>
    </form>
</body>
</html>