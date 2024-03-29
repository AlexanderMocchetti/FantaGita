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
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>leader board</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<nav class="navbar bg-body-tertiary fixed-top">
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
    <div class="container" style="margin: 2px;margin-top:70px;display:flex;align-items:center;">
    <table class="table"> 
                <tr> 
                        <td>Ranking</td> 
                        <td>username</td> 
                        <td>points</td> 
                </tr> 
        <?php 
        $sql = "SELECT users.username as username, COALESCE(sum(points), 0) as result
        FROM users 
        LEFT JOIN usersbets ON users.id = usersbets.id_user
        LEFT JOIN bets ON usersbets.id_bet = bets.id
        LEFT JOIN states ON bets.id_state = states.id
        WHERE states.description LIKE 'won' OR states.id IS NULL
        GROUP BY username
        ORDER BY result DESC;
        ";

        $result = mysqli_query($conn,$sql); 

        /* First rank will be 1 and 
                second be 2 and so on */
        $ranking = 1; 

        /* Fetch Rows from the SQL query */
        if (mysqli_num_rows($result)) 
        { 
                while ($row = mysqli_fetch_array($result))
                {
                        $color = 1;
                        switch ($ranking)
                        {
                            case 1:
                                $color = "#FAFF11";
                                break;
                            case 2:
                                $color = "#E1E0DE";
                                break;
                            case 3:
                                $color = "#F18900";
                                break;
                            default:
                                $color = "#F0F0F0";
                                break;
                        }
                        echo '<tr>';
                        echo "<td style=\"background-color: ".$color.";\">{$ranking}</td> 
                        <td style=\"background-color: ".$color.";\">{$row['username']}</td> 
                        <td style=\"background-color: ".$color.";\">{$row['result']}</td>"; 
                        $ranking++; 
                        echo '</tr>';
                } 
        } 
        ?> 
    </div>
        
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>