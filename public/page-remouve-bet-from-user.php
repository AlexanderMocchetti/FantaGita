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
    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-send-infos']))
        {
            list($id_user, $id_bet) = explode('_', $_POST["btn-send-infos"]);

            remouveBetFromUser($id_user,$id_bet,$conn);

        }else{
            echo("<br>");
        }

        header("Location: admin-see-user-bets.php");
        die;
    ?>
</body>
</html>