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
    
    <?php
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset ($_POST["btn-action"])) {
            list($id_sight, $id_state) = explode('_', $_POST["btn-action"]);

            $sql = "UPDATE sightings SET id_state = $id_state
            WHERE sightings.id = $id_sight;";

            
                if($conn->query($sql) === TRUE){
                    echo("<p>corrctfully updated</p>");
                }else{
                    echo("<p>error</p>");
                }
        }else{
            echo("<br>");
        }
    }
    
    
        $sql = "SELECT username, bets.description as bet_descr, states.description as state_descr, sightings.description as sight_descr, s_sight.description as sight_state, sightings.id as sight_id
        FROM users join sightings ON users.id = sightings.id_observer
        join bets on bets.id = sightings.id_bet
        join states on bets.id_state = states.id
        join states as s_sight on sightings.id_state = s_sight.id;";

        $res = $conn->query($sql);

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {?>
                <p style="padding: 2px; margin: 0px;">made by: <?php echo($row["username"]);?></p>
                <p style="padding: 2px; margin: 0px;">bet descr: <?php echo($row["bet_descr"]);?></p>
                <p style="padding: 2px; margin: 0px;">bet state: <?php echo($row["state_descr"]);?></p>
                <p style="padding: 2px; margin: 0px;">event descr: <?php echo($row["sight_descr"]);?></p>
                <p style="padding: 2px; margin: 0px;">event state: <?php echo($row["sight_state"]);?></p>
                <form action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
                    <button type="submit" name="btn-action" value="<?php echo($row["sight_id"]."_2");?>">approved</button>
                    <button type="submit" name="btn-action" value="<?php echo($row["sight_id"]."_1");?>">pending</button>
                    <button type="submit" name="btn-action" value="<?php echo($row["sight_id"]."_5");?>">declined</button>
                </form>
                <br>
        <?php
            }
        }
    
    
    
    ?>







</body>
</html>