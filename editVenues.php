<?php
include "database.php";
$link = getDB();

//Ellenőrzés
if(!isset($_GET['id']))
    header("Location: venues.php");

$id = mysqli_real_escape_string($link, $_GET['id']);

//Törlés
$deleted = false;
if(isset($_GET['id']) && $_GET['mode'] == "delete")
{
    $query = sprintf("delete from venue where id = '%d'", $id);
    //Ha egy koncertben szerepel, először null-ra állítjuk a helyszínét
    $constraintQuery = sprintf("update concert set venueid = null where venueid = '%d'", $id);
    mysqli_query($link, $constraintQuery) or die(mysqli_error($link));
    mysqli_query($link, $query) or die(mysqli_error($link));
    mysqli_close($link);
    $deleted = true;
    header("Location: venues.php?deleted=".$deleted);
}

$query = sprintf("select name, address, ifnull(capacity, 0) as capacity from venue where id = '%d'", $id);
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);

//Szerkesztés
if(isset($_POST['submit']))
{
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $address = mysqli_real_escape_string($link, $_POST['address']);
    $capacity = mysqli_real_escape_string($link, $_POST['capacity']);
    $query = sprintf("update venue set name = '%s', address = '%s', capacity = '%d' where id = '%d'",
        $name, $address, $capacity, $id);
    mysqli_query($link, $query) or die(mysqli_error($link));
    mysqli_close($link);
    $updated = true;
    header("Location: venues.php?updated=".$updated);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Helyszín Szerkesztése</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="container">
    <?php include 'menu.html'; ?>
    <div id="content">
        <div class="title">Helyszín szerkesztése</div>
        <div class = editDiv>
            <form action="editVenues.php?id=<?=$id?>" method="post">
                <table class="tableEdit">
                    <tr>
                        <td><label for="name">Név</label></td>
                        <td><input type="text" name="name" id="name" value="<?=$row['name']?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="address">Cím</label></td>
                        <td><input type="text" name="address" id="address" value="<?=$row['address']?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="capacity">Kapacitás</label></td>
                        <td><input type="number" name="capacity" id="capacity" value="<?=$row['capacity']?>" required></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input class="submitButton" type="submit" name="submit" value="Elküld"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <?php include 'bottom.html'; ?>
</div>
</body>
</html>