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

    <form action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
    <?php

        $sql = "SELECT id, username
        FROM users
        WHERE 1;";

        $res = $conn->query($sql);

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {?>
                 <button value = "<?php echo($row["id"]);?>" name="user-submit"><?php echo($row["username"]);?></button>
        <?php
            }
        }
        ?>
    
    </form>
    
    
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset ($_POST["user-submit"])) {
            echo("<p>id user: ".$_POST["user-submit"] ."</p>");
            echo("<p>points: ".getUserPoints($_POST["user-submit"],$conn) ."</p>");

            echo("<p>approved----------------------------------------</p>");
            $sql = "SELECT bets.description description, points, bets.id as bet_id
            FROM bets JOIN states ON bets.id_state = states.id
            JOIN usersbets ON bets.id = usersbets.id_bet
            WHERE states.description LIKE 'approved'
            AND usersbets.id_user = " .$_POST["user-submit"] . ";";

            $res = $conn->query($sql);
            $bets_indexes = get_user_bets($_POST["user-submit"], $conn);
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $bet_belongs_to_user = in_array($row["id"], $bets_indexes); ?>
            <div class="card <?php echo ($bet_belongs_to_user) ? "bg-info" : "bg-primary" ?>" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row["points"] . "pts"; ?></h5>
                    <p class="card-text"><?php echo $row["description"]; ?></p>
                </div>
            </div>
            <form action="page-remouve-bet-from-user.php" method="POST">
                <button name="btn-send-infos" value="<?php echo($_POST["user-submit"]."_".$row["bet_id"]);?>">remouve from user</button>
                <input type="checkbox" required>
            </form>
            <?php
                }
            }
            echo("<p>pending----------------------------------------</p>");
            $sql = "SELECT bets.description description, points, bets.id as bet_id
            FROM bets JOIN states ON bets.id_state = states.id
            JOIN usersbets ON bets.id = usersbets.id_bet
            WHERE states.description LIKE 'pending'
            AND usersbets.id_user = " . $_POST["user-submit"] . ";";
            $res = $conn->query($sql);
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $bet_belongs_to_user = in_array($row["id"], $bets_indexes); ?>
            <div class="card bg-body-secondary" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row["points"] . "pts"; ?></h5>
                    <p class="card-text"><?php echo $row["description"]; ?></p>
                </div>
            </div>
            <form action="page-remouve-bet-from-user.php" method="POST">
                <button name="btn-send-infos" value="<?php echo($_POST["user-submit"]."_".$row["bet_id"]);?>">remouve from user</button>
                <input type="checkbox" required>
            </form>
            <?php
                }
            }
            echo("<p>won----------------------------------------</p>");
            $sql = "SELECT bets.description description, points, bets.id as bet_id
            FROM bets JOIN states ON bets.id_state = states.id
            JOIN usersbets ON bets.id = usersbets.id_bet
            WHERE states.description LIKE 'won'
            AND usersbets.id_user = " . $_POST["user-submit"] . ";";
            $res = $conn->query($sql);
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $bet_belongs_to_user = in_array($row["id"], $bets_indexes); ?>
            <div class="card bg-warning" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row["points"] . "pts"; ?></h5>
                    <p class="card-text"><?php echo $row["description"]; ?></p>
                </div>
            </div>
            <form action="page-remouve-bet-from-user.php" method="POST">
                <button name="btn-send-infos" value="<?php echo($_POST["user-submit"]."_".$row["bet_id"]);?>">remouve from user</button>
                <input type="checkbox" required>
            </form>
            <?php
                }
            }
            echo("<p>lost----------------------------------------</p>");
            $sql = "SELECT bets.description description, points, bets.id as bet_id
            FROM bets JOIN states ON bets.id_state = states.id
            JOIN usersbets ON bets.id = usersbets.id_bet
            WHERE states.description LIKE 'lost'
            AND usersbets.id_user = " . $_POST["user-submit"] . ";";
            $res = $conn->query($sql);
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    $bet_belongs_to_user = in_array($row["id"], $bets_indexes); ?>
            <div class="card bg-danger" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row["points"] . "pts"; ?></h5>
                    <p class="card-text"><?php echo $row["description"]; ?></p>
                </div>
            </div>
            <form action="page-remouve-bet-from-user.php" method="POST">
                <button name="btn-send-infos" value="<?php echo($_POST["user-submit"]."_".$row["bet_id"]);?>">remouve from user</button>
                <input type="checkbox" required>
            </form>
            <?php
                }
            }
        }
    }
    ?>
    
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>