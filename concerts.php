<?php
include "database.php";
$link = getDB();
$result = mysqli_query($link, "select group_concat(b.name separator ', ') as bands, v.name, c.date, c.available_tickets
                                        from venue v
                                        inner join concert c on v.id = c.venueid
                                        inner join concert_has_band chb on c.id = chb.concertid
                                        inner join band b on chb.bandid = b.id
                                        group by c.id
                                        order by c.date asc;");
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
            Hello There! Koncertek
        </div>
        <!-- TODO új, delete, edit -->
        <table class = "tableMain">
            <thead>
            <tr>
                <th>Fellépők</th>
                <th>Helyszín</th>
                <th>Időpont</th>
                <th>Vehető-e jegy</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_array($result)): ?>
                <tr>
                    <td><?=$row['bands']?></td>
                    <td><?=$row['name']?></td>
                    <td><?=$row['date']?></td>
                    <td><?=$row['available_tickets']?></td>
                </tr>
            <?php endwhile;?>
            </tbody>
        </table>
    </div>
    <?php include 'bottom.html'; ?>
</div>
</body>
</html>
