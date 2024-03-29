<?php
session_start();

if (!isset($_SESSION["id"])) 
{
    header("Location: login.php");
    die;
}

require_once "../connection.php";
require_once "../functions.php";
global $conn;

if (!is_user_admin($_SESSION["id"], $conn)) {
    $referrer = $_SERVER['HTTP_REFERER'];
    $msg = "Non sei admin";
    $msg = urlencode($msg);
    header("Location: $referrer?msg=$msg");
    die;
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Delete bets</title>
</head>
<body>
<form action="admin-dashboard.php" method="POST">
        <button type="submit">quit</button>
    </form>
    <br>
<section class="vh-100 bg-image"
         style="background-color: #04243D">
    <div class="mask d-flex align-items-center h-100 gradient-custom-3">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                    <div class="card" style="border-radius: 15px; background-color: white;">
                        <div class="card-body p-5">
                            <h2 style="color: #080A0B" class="text-uppercase text-center mb-5">Cancella una scommessa</h2>
                            <form action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
                                <h5 style="color: #080A0B" class="text-uppercase text-center mb-5">Scommessa da cancellare</h5>
                                <div class="form-outline mb-4">
                                        <?php 
                                                $sql='select id, description from bets';
                                                $result = $conn->query($sql);
                                                if (mysqli_num_rows($result))
                                                {
                                                        echo '<select class="form-select" name="bet">';
                                                        while($row = $result->fetch_assoc())
                                                        {
                                                                echo '<option value="'.$row['id'].'">'.$row['description'].'</option>';
                                                        }
                                                        echo '</select>';
                                                }
                                        ?>
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
    $bet = $_POST['bet'];

    if (!isset($bet))
    {
        exit();
    }
    deleteBet($bet,$conn);
    $conn->close();
}
?>
</body>
</html>