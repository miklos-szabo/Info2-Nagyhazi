<?php
include "database.php";
$link = getDB();
if (isset($_GET['extra']))
{
    //Üres koncertek törlése
    if ($_GET['extra'] == 'deleteEmpty') {
        mysqli_query($link, "delete from concert where venueid is null and id not in 
                                              (select concertid from concert_has_band);");
    }
    //Elmúlt koncertek törlése
    else if ($_GET['extra'] == 'deleteBeforeToday')
    {
        //Constraintek miatt előbb ki kell tötölni a köztes táblából
        mysqli_query($link, "delete concert_has_band from concert_has_band
                                        inner join concert c on concert_has_band.concertid = c.id
                                        where c.date < curdate();");
        mysqli_query($link, "delete from concert where date < curdate();");
    }
}

//Táblázat
$result = mysqli_query($link, "select c.id, ifnull(group_concat(b.name separator ', '), '-') as bands, 
                                            ifnull(v.name, '-') as name, c.date, 
                                            if(c.available_tickets, 'Igen', 'Nem') as available_tickets
                                        from concert c 
                                        left outer join venue v on c.venueid = v.id
                                        left outer join concert_has_band chb on c.id = chb.concertid
                                        left outer join band b on chb.bandid = b.id
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
    <?php if (isset($_get['created'])): ?>
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
        <div class="title">Koncertek</div>
            <div class="extraButtonsConcerts">
                <a href="concerts.php?extra=deleteEmpty" class="extraMenuButton">Üres koncertek törlése</a>
                <a href="concerts.php?extra=deleteBeforeToday" class="extraMenuButton">Elmúlt koncertek törlése</a>
            </div>
        <div class="tableDiv">
                <table class="tableMain">
                    <thead>
                    <tr>
                        <th>Fellépők</th>
                        <th>Helyszín</th>
                        <th>Időpont</th>
                        <th>Vehető-e jegy</th>
                        <th colspan="2">
                            <a href="editConcerts.php?mode=add">
                                <img src="icons/addIcon.png" height="50" width="50" title="Új koncert hozzáadása">
                            </a>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_array($result)): ?>
                        <tr>
                            <td><?= $row['bands'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['date'] ?></td>
                            <td><?= $row['available_tickets'] ?></td>
                            <td>
                                <form action="editConcerts.php?id=<?= $row['id']?>&mode=edit" method="post">
                                    <input type="image" src="icons/editIcon.png" name="edit"
                                           height="30" width="30" title="Koncert szerkesztése">
                                </form>
                            </td>
                            <td>
                                <form action="editConcerts.php?id=<?= $row['id']?>&mode=delete" method="post">
                                    <input type="image" src="icons/deleteIcon.png" name="delete"
                                           height="30" width="30" title="Koncert törlése">
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
        </div>
    </div>
    <?php include 'bottom.html';?>
</div>
</body>
</html>

<?php closeDB($link);
