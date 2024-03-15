<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    die;
}
require_once 'connection.php';
require_once 'functions.php';
global $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["bet-submit"])) {
        $id_bet = $_POST["bet-submit"];
        $id_user = $_SESSION["id"];
        $sql = "SELECT id_state
                FROM bets JOIN states
                ON states.id = id_state
                WHERE states.description LIKE 'approved'
                AND bets.id = $id_bet";
        $res = $conn->query($sql);
        if ($res->num_rows == 1) {
            $sql = "INSERT INTO usersbets (id_user, id_bet)
                    VALUES ($id_user, $id_bet);";
            $res = $conn->query($sql);
        }
    }
}

?>
<html lang="it">
    <head>
        <title>Bacheca</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
    <form action="" method="POST">
    <?php
    $sql = "SELECT bets.id id, bets.description description, points
            FROM bets JOIN states ON bets.id_state = states.id
            WHERE states.description LIKE 'approved';";
    $res = $conn->query($sql);
    $bets_indexes = get_user_bets($_SESSION["id"], $conn);
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $bet_belongs_to_user = in_array($row["id"], $bets_indexes); ?>
            <div class="card <?php echo ($bet_belongs_to_user) ? "bg-info" : "bg-primary"?>" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row["points"]."pts"; ?></h5>
                    <p class="card-text"><?php echo $row["description"];?></p>
                    <input type="submit" name="bet-submit" value="<?php echo $row["id"];?>" <?php echo ($bet_belongs_to_user) ? "disabled" : ""; ?>>
                </div>
            </div>
            <?php
        }
    }
    $sql = "SELECT bets.description description, points
            FROM bets JOIN states ON bets.id_state = states.id
            JOIN usersbets ON bets.id = userbets.id_bets
            WHERE states.description LIKE 'pending'
            AND usersbets.id_user = ".$_SESSION["id"].";";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $bet_belongs_to_user = in_array($row["id"], $bets_indexes); ?>
            <div class="card bg-body-secondary" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row["points"]."pts"; ?></h5>
                    <p class="card-text"><?php echo $row["description"];?></p>
                </div>
            </div>
            <?php
        }
    }
    $sql = "SELECT bets.description description, points
            FROM bets JOIN states ON bets.id_state = states.id
            JOIN usersbets ON bets.id = userbets.id_bets
            WHERE states.description LIKE 'won'
            AND usersbets.id_user = ".$_SESSION["id"].";";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $bet_belongs_to_user = in_array($row["id"], $bets_indexes); ?>
            <div class="card bg-warning" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row["points"]."pts"; ?></h5>
                    <p class="card-text"><?php echo $row["description"];?></p>
                </div>
            </div>
            <?php
        }
    }
    $sql = "SELECT bets.description description, points
            FROM bets JOIN states ON bets.id_state = states.id
            JOIN usersbets ON bets.id = userbets.id_bets
            WHERE states.description LIKE 'lost'
            AND usersbets.id_user = ".$_SESSION["id"].";";
    $res = $conn->query($sql);
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $bet_belongs_to_user = in_array($row["id"], $bets_indexes); ?>
            <div class="card bg-danger" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row["points"]."pts"; ?></h5>
                    <p class="card-text"><?php echo $row["description"];?></p>
                </div>
            </div>
            <?php
        }
    }
    ?>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>