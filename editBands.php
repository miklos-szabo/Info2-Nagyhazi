<?php
include "database.php";
$link = getDB();

//Ellenőrzés
if(!isset($_GET['id']))
    header("Location: bands.php");

$id = $_GET['id'];

//Törlés
$deleted = false;
if(isset($_GET['id']) && $_GET['mode'] == "delete")
{
    $query = sprintf("delete from band where id = '%d'", mysqli_real_escape_string($link, $id));
    //Ha egy koncertben szerepel, először onnan ki kell törölni
    $constraintQuery = sprintf("delete from concert_has_band where bandid = '%d'", $id);
    mysqli_query($link, $constraintQuery) or die(mysqli_error($link));
    mysqli_query($link, $query) or die(mysqli_error($link));
    mysqli_close($link);
    $deleted = true;
    header("Location: bands.php?deleted=".$deleted);
}

$query = sprintf("select name, country, formed_in from band where id = '%d'", $id);
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);

//Szerkesztés
if(isset($_POST['submit']))
{
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $country = mysqli_real_escape_string($link, $_POST['country']);
    $formed_in = mysqli_real_escape_string($link, $_POST['formed_in']);
    $query = sprintf("update band set name = '%s', country = '%s', formed_in = '%d' where id = '%d'",
                                    $name, $country, $formed_in, $id);
    mysqli_query($link, $query) or die(mysqli_error($link));
    mysqli_close($link);
    $updated = true;
    header("Location: bands.php?updated=".$updated);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Együttes szerkesztése</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="container">
    <?php include 'menu.html'; ?>
    <div id="content">
        <div class="title">Együttes szerkesztése</div>
        <div class = editDiv>
            <form action="editBands.php?id=<?=$id?>" method="post">
                <table class="tableEdit">
                    <tr>
                        <td><label for="name">Név</label></td>
                        <td><input type="text" name="name" id="name" value="<?=$row['name']?>"></td>
                    </tr>
                    <tr>
                        <td><label for="name">Ország</label></td>
                        <td><input type="text" name="country" id="country" value="<?=$row['country']?>"></td>
                    </tr>
                    <tr>
                        <td><label for="name">Születési év</label></td>
                        <td><input type="number" name="formed_in" id="formed_in" value="<?=$row['formed_in']?>"></td>
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