<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    die;
}
require_once '../connection.php';
require_once '../functions.php';
global $conn;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sql = "SELECT COUNT(*) n_scommesse
            FROM usersbets
            WHERE id_user=".$_SESSION["id"];
    $res = $conn->query($sql);
    if ($res) {
        $n_scommesse = $res->fetch_assoc()["n_scommesse"];
        $n_scommesse = intval($n_scommesse);

        if (isset($_POST["selected-items"]) && $n_scommesse < 10) {
            if (count($_POST["selected-items"]) + $n_scommesse <= 10) {
            $id_user = $_SESSION["id"];
            for ($i = 0; $i < count($_POST["selected-items"]); $i++) { 
            $id_bet = $_POST["selected-items"][$i];
            // TODO: make safer
            $sql = "SELECT id_state
                    FROM bets JOIN states
                    ON states.id = id_state
                    WHERE states.description LIKE 'approved'
                    AND bets.id = $id_bet";
            $res = $conn->query($sql);
            if ($res->num_rows == 1) {
                //TODO: make safer
                $sql = "INSERT INTO usersbets (id_user, id_bet)
                        VALUES ($id_user, $id_bet);";
                $res = $conn->query($sql);
            }
        }
        }
    }
    }
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Bacheca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #04243D;
        }
    </style>
</head>
<body>
        <nav class="navbar bg-body-tertiary fixed-top mb-5">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
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
    <form action='' method='post'>
     <div class="container-fluid mt-5 w-75 mx-auto">
        <ol class="list-group list-group-numbered">
            <?php 
            $sql = "SELECT bets.id id, bets.description description, points, username
                    FROM bets JOIN states ON bets.id_state = states.id
                    JOIN users ON id_creator = users.id
                    WHERE states.description LIKE 'approved';";
            $res = $conn->query($sql);
            $bets_indexes = get_user_bets($_SESSION["id"], $conn);
            $vuota = true;
            if ($res) {
            while ($row = $res->fetch_assoc()) {
                $bet_belongs_to_user = in_array($row["id"], $bets_indexes);
                if (!$bet_belongs_to_user) {
                    $vuota = false;
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                <input type='checkbox' name="selected-items[]" value="<?php echo $row["id"]?>">
                <div class="fw-bold"><?php echo $row["username"]; ?></div>
                <?php echo $row["description"]; ?>
                </div>
                <span class="badge text-bg-dark rounded-pill"><?php echo $row["points"]; ?>pts</span>
            </li>
            <?php
                }
            }
        }
            ?>
            </ol>
            <?php 
            if ($vuota) {
            ?>
            <h4 class='text-white'>Niente scommesse disponibili, aggiungine una!</h4>
            <?php
            } else {
                ?>
            <button type="submit" class="mt-2 btn btn-primary">Conferma</button>
            <?php
            }
            ?>
            </div>
        </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>