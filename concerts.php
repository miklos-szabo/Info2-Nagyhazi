<?php
include "database.php";
$link = getDB();
$result = mysqli_query($link, "select group_concat(b.name separator ', ') as bands, v.name, c.date, if(c.available_tickets, 'Igen', 'Nem') as available_tickets
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
    <title>Koncertek</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="library.js"></script>
</head>
<body>
<div id="container">
    <?php include 'menu.html'; ?>
    <div id="content">
        <div class="title">
            Koncertek
        </div>
        <!-- TODO új, delete, edit -->
        <div class="tableDiv">
            <form action="concerts.php" method="post">
                <table class="tableMain">
                    <thead>
                    <tr>
                        <th>Fellépők</th>
                        <th>Helyszín</th>
                        <th>Időpont</th>
                        <th>Vehető-e jegy</th>
                        <th colspan="2"><img src="icons/addIcon.png" height="50" width="50" title="Új koncert hozzáadása"
                            onclick="showAddRow()"</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="addingTableRow" id="addingTableRow">
                        <td><input type="text" name="bands" required></td>
                        <td><input type="text" name="venueName" required></td>
                        <td><input type="text" name="date" required></td>
                        <td><input type="text" name="available_tickets" required></td>
                        <td colspan="2"><input type="submit" name="submit" value="Elküld"></td>
                    </tr>
                    <?php while ($row = mysqli_fetch_array($result)): ?>
                        <tr>
                            <td><?= $row['bands'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['date'] ?></td>
                            <td><?= $row['available_tickets'] ?></td>
                            <td><img src="icons/editIcon.png" height="30" width="30" title="Koncert szerkesztése"</td>
                            <td><img src="icons/deleteIcon.png" height="30" width="30" title="Koncert törlése"</td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <?php include 'bottom.html';?>
</div>
</body>
</html>

<?php closeDB($link);
