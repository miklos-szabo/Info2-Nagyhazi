<?php
include "database.php";
$link = getDB();
$id = $_GET['id'];

//Új létrehozása
$created = false;
if (isset($_POST['submit'])) {
    $venueid = mysqli_real_escape_string($link, $_POST['venueid']);
    $date = mysqli_real_escape_string($link, $_POST['date']);
    $available_tickets = mysqli_real_escape_string($link, $_POST['available_tickets']);
    $query = sprintf("insert into concert(venueid, date, available_tickets) values ('%d', '%s', '%d')",
                                $venueid, $date, $available_tickets);
    mysqli_query($link, $query) or die(mysqli_error($link));
    mysqli_close($link);
    $created = true;
    header("Location: concerts.php?created=".$created);
}

//Törlés
$deleted = false;
if(isset($_GET['id']) && $_GET['mode'] == "delete")
{
    $query = sprintf("delete from concert where id = '%d';", $id);
    //Ha egy koncertben szerepel, először onnan ki kell törölni
    $constraintQuery = sprintf("delete concert_has_band from concert_has_band
                                        inner join concert c on concert_has_band.concertid = c.id
                                        where c.id = '%d';", $id);
    mysqli_query($link, $constraintQuery) or die(mysqli_error($link));
    mysqli_query($link, $query) or die(mysqli_error($link));
    mysqli_close($link);
    $deleted = true;
    header("Location: concerts.php?deleted=".$deleted);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Koncert Manager</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="container">
    <?php include 'menu.html'; ?>
    <div id="content">
        <div class="title">
            Hello There! editConcerts <!-- TODO content -->
        </div>
    </div>
    <?php include 'bottom.html'; ?>
</div>
</body>
</html>