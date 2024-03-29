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
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<section class="vh-100 bg-image"
         style="background-color: #04243D">
    <div class="mask d-flex align-items-center h-100 gradient-custom-3">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                    <div class="card" style="border-radius: 15px; background-color: white;">
                        <div class="card-body p-5">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="button" onclick="window.location.href = 'bacheca.php';">Torna alla bacheca</button>
                                <button class="btn btn-primary" type="button" onclick="window.location.href = 'admin-manage-bets.php';">Gestisci Scommesse</button>
                                <button class="btn btn-primary" type="button" onclick="window.location.href = 'admin-ban-user.php';">Banna Utente</button>
                                <button class="btn btn-primary" type="button" onclick="window.location.href = 'admin-see-user-bets.php';">Visualizza Scommesse Utenti</button>
                                <button class="btn btn-primary" type="button" onclick="window.location.href = 'admin-sightings.php';">Segnalazioni Vincita Scommesse</button>
                                <button class="btn btn-primary" type="button" onclick="window.location.href = 'admin-delete-bet.php';">Cancella Scommesse</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</section>
</body>
</html>