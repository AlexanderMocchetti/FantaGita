<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    die;
}
require_once '../connection.php';
require_once '../functions.php';
global $conn;



?>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Bacheca</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <style>
        body {
            background-color: #04243D;
        }
        </style>
    </head>
    <body>   
        <nav class="navbar mb-5 bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="bacheca.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-dice-6-fill" viewBox="0 0 16 16">
                <path d="M3 0a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V3a3 3 0 0 0-3-3zm1 5.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m8 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m1.5 6.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0M12 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3M5.5 12a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0M4 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/>
            </svg>
        FantaGita</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">FantaGita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./bacheca.php">Bacheca eventi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./scommesse-disponibili.php">Scommesse disponibili</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./classifica.php">Classifica</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./create-bet.php">Crea scommessa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./sighting-form.php">Conferma scommessa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    </nav>
    <br>
    <div class="container-fluid mt-5 w-75 mx-auto">
        <ol class="list-group list-group-numbered">
            <?php 
            $sql = "SELECT bets.id id, bets.description description, points, username
                    FROM bets JOIN states ON bets.id_state = states.id
                    JOIN users ON id_creator = users.id
                    JOIN usersbets ON bets.id = usersbets.id_bet
                    WHERE states.description LIKE 'approved'
                    AND usersbets.id_user = ".$_SESSION["id"].";";
            $res = $conn->query($sql);
            $bets_indexes = get_user_bets($_SESSION["id"], $conn);
            if ($res->num_rows > 0) {
                ?>
                <h5 class='text-white'>Eventi (seleziona massimo 10 scommesse)</h5>
                <?php
            while ($row = $res->fetch_assoc()) {
                $bet_belongs_to_user = in_array($row["id"], $bets_indexes);
                if ($bet_belongs_to_user) {
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                <div class="fw-bold"><?php echo $row["username"]; ?></div>
                <?php echo $row["description"]; ?>
                </div>
                <span class="badge text-bg-dark rounded-pill"><?php echo $row["points"]; ?>pts</span>
            </li>
            <?php
                }
            }
            }
            $sql = "SELECT bets.id id, bets.description description, points, username
                    FROM bets JOIN states ON bets.id_state = states.id
                    JOIN users ON id_creator = users.id
                    WHERE states.description LIKE 'pending'
                    AND bets.id_creator = ".$_SESSION["id"].";";
            $res = $conn->query($sql);
            if ($res->num_rows>0) {

            ?>
            <h5 class='text-white'>Eventi in attesa di conferma</h5>
            <?php
            
            while ($row = $res->fetch_assoc()) {
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                    <div class="fw-bold"><?php echo $row["username"]; ?></div>
                        <?php echo $row["description"]; ?>
                    </div>
                    <span class="badge text-bg-warning rounded-pill"><?php echo $row["points"]; ?>pts</span>
            </li>
            <?php
            }
            }
            $sql = "SELECT bets.id id, bets.description description, points, username
            FROM bets JOIN states ON bets.id_state = states.id
            JOIN users ON id_creator = users.id
            JOIN usersbets ON bets.id = usersbets.id_bet
            WHERE states.description LIKE 'won'
            AND usersbets.id_user = ".$_SESSION["id"].";";
            $res = $conn->query($sql);
            if ($res->num_rows > 0) {
                ?>
                <h5 class='text-white'>Eventi vinti</h5>
                <?php
            while ($row = $res->fetch_assoc()) {
                $bet_belongs_to_user = in_array($row["id"], $bets_indexes);
                if ($bet_belongs_to_user) {
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                <div class="fw-bold"><?php echo $row["username"]; ?></div>
                <?php echo $row["description"]; ?>
                </div>
                <span class="badge text-bg-success rounded-pill"><?php echo $row["points"]; ?>pts</span>
            </li>
            <?php
                }
            }
            }
            $sql = "SELECT bets.id id, bets.description description, points, username
            FROM bets JOIN states ON bets.id_state = states.id
            JOIN users ON id_creator = users.id
            JOIN usersbets ON bets.id = usersbets.id_bet
            WHERE states.description LIKE 'lost'
            AND usersbets.id_user = ".$_SESSION["id"].";";
            $res = $conn->query($sql);
            if ($res->num_rows>0) {
                ?>
                <h5 class='text-white'>Eventi persi</h5>
                <?php
            while ($row = $res->fetch_assoc()) {
                $bet_belongs_to_user = in_array($row["id"], $bets_indexes);
                if ($bet_belongs_to_user) {
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                <div class="fw-bold"><?php echo $row["username"]; ?></div>
                <?php echo $row["description"]; ?>
                </div>
                <span class="badge text-bg-danger rounded-pill"><?php echo $row["points"]; ?>pts</span>
            </li>
            <?php
                }
            }
            }
            ?>
            </ol>
            </div> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>