<?php
include "database.php";
$link = getDB();
$result = mysqli_query($link, "select name, address, capacity from venue");
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
            Hello There! Helyszínek
        </div>
        <!-- TODO új, delete, edit -->
        <table class = "tableMain">
            <thead>
            <tr>
                <th>Név</th>
                <th>Ország</th>
                <th>Alakulási év</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_array($result)): ?>
                <tr>
                    <td><?=$row['name']?></td>
                    <td><?=$row['address']?></td>
                    <td><?=$row['capacity']?></td>
                </tr>
            <?php endwhile;?>
            </tbody>
        </table>
    </div>
    <?php include 'bottom.html'; ?>
</div>
</body>
</html>