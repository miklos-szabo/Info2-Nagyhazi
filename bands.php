<?php
include "database.php";
$link = getDB();
//Új létrehozása
$created = false;
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $country = mysqli_real_escape_string($link, $_POST['country']);
    $formed_in = mysqli_real_escape_string($link, $_POST['formed_in']);
    $query = sprintf("insert into band(name, country, formed_in) values ('%s', '%s', '%d')", $name, $country, $formed_in);
    mysqli_query($link, $query);
    $created = true;
}

$result = mysqli_query($link, "select id, name, country, formed_in from band order by name");
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Együttesek</title>
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
            <div class="title">Együttesek</div>
            <div class=tableDiv>
                <form id="formAdd" action="bands.php" method="post"></form>
                <table class="tableMain">
                    <thead>
                    <tr>
                        <th>Név</th>
                        <th>Ország</th>
                        <th>Alakulási év</th>
                        <th colspan="2"><img src="icons/addIcon.png" height="50" width="50" class="addIcon"
                                             title="Új együttes hozzáadása"
                                             onclick="showAddRow()"</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="addingTableRow" id="addingTableRow">
                        <td><input form="formAdd" type="text" name="name" required></td>
                        <td><input form="formAdd" type="text" name="country" required></td>
                        <td><input form="formAdd" type="number" name="formed_in" required></td>
                        <td colspan="2"><input form="formAdd" type="submit" name="submit" value="Elküld"></td>
                    </tr>
                    <?php while ($row = mysqli_fetch_array($result)): ?>
                        <tr>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['country'] ?></td>
                            <td><?= $row['formed_in'] ?></td>
                            <td>
                                <form action="editBands.php?id=<?= $row['id']?>&mode=edit" method="post">
                                    <input type="image" src="icons/editIcon.png" name="edit"
                                          height="30" width="30" title="Együttes szerkesztése">
                                </form>
                            </td>
                            <td>
                                <form action="editBands.php?id=<?= $row['id']?>&mode=delete" method="post">
                                    <input type="image" src="icons/deleteIcon.png" name="delete"
                                           height="30" width="30" title="Együttes törlése">
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

