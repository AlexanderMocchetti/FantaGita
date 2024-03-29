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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
            list($id_bet, $id_state) = explode('_', $_POST["btn-action"]);

            $sql = "UPDATE bets SET id_state = $id_state
            WHERE bets.id = $id_bet;";

            
                if($conn->query($sql) === TRUE){
                    echo("<p>corrctfully updated</p>");
                }else{
                    echo("<p>error</p>");
                }
        }
    }else{
            echo("<br>");
    }
    
    
        $sql = "SELECT  bets.id as bet_id, bets.description as bet_descr, points, states.description as bet_state, users.username as user_name, states.id as bet_state_id
        FROM bets JOIN users ON users.id = bets.id_creator
        JOIN states ON bets.id_state = states.id
        ORDER BY users.id, bets.id;";
        $res = $conn->query($sql);


        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {?>
                <p style="padding: 2px; margin: 0px;">made by: <?php echo($row["user_name"]);?></p>
                <div class="card <?php 
                switch($row["bet_state_id"]){
                    case 1:
                        echo("bg-body-secondary");
                        break;
                    case 2:
                        echo("bg-primary");
                        break;
                    case 3:
                        echo("bg-warning");
                        break;
                    case 4:
                        echo("bg-danger");
                        break;
                    default:
                        echo("bg-body-secondary");
                        break;
                    
                    }
                    ?>" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row["points"] . "pts"; ?></h5>
                    <p class="card-text"><?php echo $row["bet_descr"]; ?></p>
                </div>
                <p style="padding: 2px; margin: 0px;">bet state: <?php echo($row["bet_state"]);?></p>
            </div>
                <form action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
                    <button type="submit" name="btn-action" value="<?php echo($row["bet_id"]."_3");?>">won</button>
                    <button type="submit" name="btn-action" value="<?php echo($row["bet_id"]."_4");?>">lost</button>
                    <button type="submit" name="btn-action" value="<?php echo($row["bet_id"]."_2");?>">approved</button>
                    <button type="submit" name="btn-action" value="<?php echo($row["bet_id"]."_1");?>">pending</button>
                </form>
                <br>
        <?php
            }
        }
        ?>
    
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>