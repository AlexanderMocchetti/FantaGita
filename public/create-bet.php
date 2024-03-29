<?php
session_start();
if (!isset($_SESSION["id"]))
{
    header("Location: login.php");
    die;
}
require_once '../connection.php';
require_once '../functions.php';
global $conn;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create bets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
        <nav class="navbar bg-body-tertiary fixed-top">
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
<section class="vh-100 bg-image"
         style="background-color: #04243D">
    <div class="mask d-flex align-items-center h-100 gradient-custom-3">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                    <div class="card" style="border-radius: 15px; background-color: white;">
                        <div class="card-body p-5">
                            <h2 style="color: #080A0B" class="text-uppercase text-center mb-5">Crea Scommessa</h2>
                            <form action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
                                <div class="form-outline mb-4">
                                    <textarea name="description" id="description" class="form-control form-control-lg" maxlength="100" placeholder="Descrizione scommessa" required></textarea>
                                </div>
                                <div class="form-outline mb-4">
                                    <input type="number" id="points" name="points" class="form-control form-control-lg" placeholder="Punti" min="<?php echo getMinPointsValue($conn); ?>" max="<?php echo getMaxPointsValue($conn); ?>" required/>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <p>Premendo Invia invierai la richiesta di creazione della scommessa, prima di poterla selezionare dovrà essere approvata da un Admin.</p>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" style="background-color: teal;" class="btn btn-primary btn-lg">Invia</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</section>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $description = $_POST['description'];
    $points = $_POST['points'];
    if (!isset($description) || !isset($points))
    {
        exit();
    }
    insertBet($description,$points,$_SESSION["id"],$conn);

    $conn->close();
}
?>
</body>
</html>