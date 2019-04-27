<?php
include "database.php";
$link = getDB();

//Új létrehozása
$created = false;
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $address = mysqli_real_escape_string($link, $_POST['address']);
    $capacity = mysqli_real_escape_string($link, $_POST['capacity']);
    $query = sprintf("insert into venue(name, address, capacity) values ('%s', '%s', '%d')", $name, $address, $capacity);
    mysqli_query($link, $query);
    $created = true;
}

$result = mysqli_query($link, "select id, name, address, ifnull(capacity, '-') as capacity from venue");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Helyszínek</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="library.js"></script>
</head>
<body>
<div id="container">
    <?php if ($created): ?>
        <div class="alertBoxSuccess">
            <div class="alertText">Sikeres hozzáadás!</div>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alertBoxSuccess">
            <div class="alertText">Sikeres törlés!</div>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['updated'])): ?>
        <div class="alertBoxSuccess">
            <div class="alertText">Sikeres szerkesztés!</div>
        </div>
    <?php endif; ?>
    <?php include 'menu.html'; ?>
    <div id="content">
        <div class="title">
            Helyszínek
        </div>
        <div class="tableDiv">
            <form id="formAdd" action="venues.php" method="post"></form>
                <table class="tableMain">
                    <thead>
                    <tr>
                        <th>Név</th>
                        <th>Cím</th>
                        <th>Kapacitás</th>
                        <th colspan="2"><img src="icons/addIcon.png" height="50" width="50" title="Új helyszín hozzáadása"
                            onclick="showAddRow()" </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="addingTableRow" id="addingTableRow">
                        <td><input form="formAdd" type="text" name="name" required></td>
                        <td><input form="formAdd" type="text" name="address" required></td>
                        <td><input form="formAdd" type="number" name="capacity"></td>
                        <td colspan="2"><input form="formAdd" type="submit" name="submit" value="Elküld"></td>
                    </tr>
                    <?php while ($row = mysqli_fetch_array($result)): ?>
                        <tr>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['address'] ?></td>
                            <td><?= $row['capacity'] ?></td>
                            <td>
                                <form action="editVenues.php?id=<?= $row['id']?>&mode=edit" method="post">
                                    <input type="image" src="icons/editIcon.png" name="edit"
                                           height="30" width="30" title="Helyszín szerkesztése">
                                </form>
                            </td>
                            <td>
                                <form action="editVenues.php?id=<?= $row['id']?>&mode=delete" method="post">
                                    <input type="image" src="icons/deleteIcon.png" name="delete"
                                           height="30" width="30" title="Helyszín törlése">
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
        </div>
    </div>
    <?php include 'bottom.html'; ?>
</div>
</body>
</html>

<?php closeDB($link);