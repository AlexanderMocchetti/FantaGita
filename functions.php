<?php
function get_user_bets(int $id, mysqli $conn): array
{
    $sql = "
    SELECT id_bet FROM usersbets WHERE id_user = $id;
    ";
    $res = $conn->query($sql);
    $return_array = array();
    $i = 0;
    while ($row = $res->fetch_assoc()) {
        $return_array[$i] = $row["id_bet"];
        $i++;
    }
    return $return_array;
}

function get_bets_id(): array
{
    $sql = "
    SELECT * FROM bets;
    ";
    $res = $conn->query($sql);
    $return_array = array();
    $i = 0;
    while ($row = $res->fetch_assoc()) 
    {
        $return_array[$i] = $row["id"];
        $i++;
    }
    return $return_array;
}

function existUserLogin($username,$password,$conn): bool
{
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $query = $conn->prepare($sql);
    $query->bind_param("ss", $username,$password);
    $query->execute();
    $result = $query->get_result();

    if (mysqli_num_rows($result))
    {
        return true;
    }
    return false;
}
function getUserIdByUsername($username,$conn):int
{
    $sql = "SELECT * FROM users WHERE username = ?";
    $query = $conn->prepare($sql);
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row['id'];
}

function is_user_admin(int $id, mysqli $conn): bool {
    $sql = "
        SELECT roles.description
        FROM users join roles on id_role = roles.id
        WHERE roles.description LIKE 'admin'
        AND users.id = $id;
    ";
    $res = $conn->query($sql);
    return $res->num_rows === 1;
}

function get_msg(): ?string {
    if (!isset($_GET["msg"]))
        return null;
    $output = '<div class="alert alert-primary d-flex align-items-center" role="alert">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">';
    $output .= '<path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>';
    $output .= '</svg>';
    $output .= '<div>';
    $output .= htmlspecialchars(urldecode($_GET["msg"]));
    $output .= '</div>';
    $output .= '</div>';
    return $output;
}

function getMinPointsValue($conn):int
{
    $sql = "SELECT * FROM const WHERE id = 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['min_points'];
}

function getMaxPointsValue($conn):int
{

    $sql = "SELECT * FROM const WHERE id = 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['max_points'];
}
function insertBet($description,$points,$id_creator,$conn):bool
{
    $sql = "INSERT INTO bets (description,points,id_creator)
            VALUES 
            ('$description','$points','$id_creator')";
    if ($conn->query($sql) === TRUE) {
        //addUserToBet($_SESSION['id'],getBetId($description,$points,$id_creator,$conn),$conn);
        return true;
    } else {

        return false;
    }
}

function getBetId($description,$points,$id_creator,$conn):int
{
    $sql = "SELECT * FROM bets WHERE description = ? AND points = ? AND id_creator = ?";
    $query = $conn->prepare($sql);
    $query->bind_param("sss", $description,$points,$id_creator);
    $query->execute();
    $result = $query->get_result();
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row['id'];
}
function addUserToBet($id_user,$id_bet,$conn):bool
{
    $sql = "INSERT INTO usersbets (id_user,id_bet)
            VALUES 
            ('$id_user','$id_bet')";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {

        return false;
    }
}

function insertSight($description,$id_bet,$id_observer,$conn):bool
{
    $sql = "INSERT INTO sightings (description,id_bet,id_observer)
            VALUES 
            ('$description','$id_bet','$id_observer')";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {

        return false;
    }
}

function getUserPoints($id_user,$conn):int
{
    $sql = "SELECT sum(points) as result
    FROM bets JOIN states ON bets.id_state = states.id
    JOIN usersbets ON bets.id = usersbets.id_bet
    WHERE states.description LIKE 'won'
    AND usersbets.id_user = " . $id_user . ";";

    $res = $conn->query($sql);
    $tmp = $res->fetch_assoc();
    if (isset($tmp["result"])) {
        return $tmp["result"];
    }
    return 0;
}

function deleteUser($id_user,$conn):bool
{
    $sql = "DELETE from users
    WHERE id = '$id_user'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;

    }
}

function getUsersLeaderBoard():array
{
    $sql = "SELECT users.username as username, sum(points) as result
    FROM bets JOIN states ON bets.id_state = states.id
    JOIN usersbets ON bets.id = usersbets.id_bet
    JOIN users ON users.id = userbets.id_user
    WHERE states.description LIKE 'won'
    ORDER BY result DESC;";

    $res = $conn->query($sql);
    $return_array = array();
    $i = 0;
    while ($row = $res->fetch_assoc()) {
        $return_array[$i] = $row["username"];
        $i++;
    }
    return $return_array;
}

function remouveBetFromUser($id_user,$id_bet,$conn):bool
{
    $sql = "DELETE from usersbets
    WHERE id_user = '$id_user' and id_bet = '$id_bet'";


    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;

    }
}

function canAddBet($id_user, $conn):bool
{
    $sql = "SELECT max_bets
    FROM const
    WHERE id = 1";
    $sql2 = "SELECT count(*) as result
    FROM usersbets
    WHERE id_user = $id_user";

    //return ($conn->query($sql)->fetch_assoc()["max_bets"]>$conn->query($sql2)->fetch_assoc()["result"]);//controllo dinamico da db
    return (10>$conn->query($sql2)->fetch_assoc()["result"]);
}

// Per qualche motivo non va ma la query è giusta.
function deleteBet ($bet_id, $conn):bool
{
    $sql = "DELETE FROM bets
    WHERE id = '$bet_id';";
    var_dump($sql);

    if ($conn->query($sql) === TRUE) {
        echo("query fatta correttamente");
        return true;
    } else {
        echo("query non va");
        return false;

    }
}