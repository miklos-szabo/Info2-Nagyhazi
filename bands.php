<?php
    include "database.php";
    $link = getDB();
    $result = mysqli_query($link, "select name, country, formed_in from band");

    $created = false;
    $deleted = false;
if(isset($_POST['submit']))
{
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $country = mysqli_real_escape_string($link, $_POST['country']);
    $formed_in = mysqli_real_escape_string($link, $_POST['formed_in']);
    $query = sprintf("insert into band(name, country, formed_in) values ('%s', '%s', '%d')", $name, $country, $formed_in);
    mysqli_query($link, $query);
    $created = true;
}
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
    <?php if($created): ?>
    <div class="alertBoxSuccess">
        <div class="alertText">Sikeres hozzáadás!</div>
    </div>
    <?php endif; ?>
    <?php include 'menu.html'; ?>
    <div id="content">
        <div class="title">Együttesek</div>
        <!-- TODO delete, edit -->
        <div class = tableDiv>
            <form action="bands.php" method="post">
                <table class="tableMain">
                    <thead>
                    <tr>
                        <th>Név</th>
                        <th>Ország</th>
                        <th>Alakulási év</th>
                        <th colspan="2"><img src="icons/addIcon.png" height="50" width="50" title="Új együttes hozzáadása"
                            onclick="showAddRow()" </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="addingTableRow" id="addingTableRow">
                        <td><input type="text" name="name" required></td>
                        <td><input type="text" name="country" required></td>
                        <td><input type="text" name="formed_in" required></td>
                        <td colspan="2"><input type="submit" name="submit" value="Elküld"></td>
                    </tr>
                    <?php while ($row = mysqli_fetch_array($result)): ?>
                        <tr>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['country'] ?></td>
                            <td><?= $row['formed_in'] ?></td>
                            <td><img src="icons/editIcon.png" height="30" width="30" title="Együttes szerkesztése"</td>
                            <td><img src="icons/deleteIcon.png" height="30" width="30" title="Együttes törlése"</td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <?php include 'bottom.html'; ?>
</div>
</body>
</html>

<?php closeDB($link);

