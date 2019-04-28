<?php
include "database.php";
$link = getDB();
$result = mysqli_query($link, "select ifnull(group_concat(b.name separator ', '), '-') as bands, 
                                            ifnull(v.name, '-') as name, c.date, 
                                            if(c.available_tickets, 'Igen', 'Nem') as available_tickets
                                        from concert c 
                                        left outer join venue v on c.venueid = v.id
                                        left outer join concert_has_band chb on c.id = chb.concertid
                                        left outer join band b on chb.bandid = b.id
                                        where c.date >= curdate()
                                        group by c.id
                                        order by c.date asc
                                        limit 1;");
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
        <div class="title">Koncert Manager</div>
        <h2>Következő koncert</h2>
        <div class="indexTableDiv">
            <table class="tableMain">
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
                    <td><?= $row['bands'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['date'] ?></td>
                    <td><?= $row['available_tickets'] ?></td>
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