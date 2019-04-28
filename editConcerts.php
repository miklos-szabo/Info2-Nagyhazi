<?php
include "database.php";
$link = getDB();
if(isset($_GET['id']))
    $id = mysqli_real_escape_string($link, $_GET['id']);

//Új létrehozása
$created = false;
if (isset($_POST['submitAdd']))
{
    //Koncert hozzáadása
    $venueid = mysqli_real_escape_string($link, $_POST['venueid']);
    //header("Location: concerts.php?venueid=".($venueid===""? "null" : $venueid).'<--');
    $date = mysqli_real_escape_string($link, $_POST['date']);
    $available_tickets = mysqli_real_escape_string($link, $_POST['available_tickets']);
    $query = sprintf("insert into concert(venueid, date, available_tickets) values (NULLIF('%s', ''), '%s', '%d')",
                                $venueid, $date, $available_tickets);
    mysqli_query($link, $query) or die(mysqli_error($link));
    $lastId = mysqli_insert_id($link);

    //Concert_has_band elemek hozzáadása
    if($_POST["band1"] !== "") mysqli_query($link, sprintf("insert into concert_has_band(concertid, bandid) VALUES('%d', '%d')", $lastId, $_POST['band1']));
    if($_POST["band2"] !== "") mysqli_query($link, sprintf("insert into concert_has_band(concertid, bandid) VALUES('%d', '%d')", $lastId, $_POST['band2']));
    if($_POST["band3"] !== "") mysqli_query($link, sprintf("insert into concert_has_band(concertid, bandid) VALUES('%d', '%d')", $lastId, $_POST['band3']));
    if($_POST["band4"] !== "") mysqli_query($link, sprintf("insert into concert_has_band(concertid, bandid) VALUES('%d', '%d')", $lastId, $_POST['band4']));

    mysqli_close($link);
    $created = true;
    header("Location: concerts.php?created=".$created);
}

//Törlés
$deleted = false;
if(isset($_GET['id']) && $_GET['mode'] == "delete")
{
    $query = sprintf("delete from concert where id = '%d';", $id);
    //Ha egy koncertben szerepel, először onnan ki kell törölni
    $constraintQuery = sprintf("delete concert_has_band from concert_has_band
                                        inner join concert c on concert_has_band.concertid = c.id
                                        where c.id = '%d';", $id);
    mysqli_query($link, $constraintQuery) or die(mysqli_error($link));
    mysqli_query($link, $query) or die(mysqli_error($link));
    mysqli_close($link);
    $deleted = true;
    header("Location: concerts.php?deleted=".$deleted);
}

if (isset($_GET['id']))
{
    $query = sprintf("select c.id, v.id as venueid, ifnull(group_concat(b.name separator ', '), '-') as bands,
                                                group_concat(b.id separator ', ') as bandids,
                                                ifnull(v.name, '-') as name, c.date, 
                                                if(c.available_tickets, 'Igen', 'Nem') as available_tickets
                                            from concert c 
                                            left outer join venue v on c.venueid = v.id
                                            left outer join concert_has_band chb on c.id = chb.concertid
                                            left outer join band b on chb.bandid = b.id
                                            where c.id = '%d'
                                            group by c.id;", $id);
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
}

//Szerkesztés
if(isset($_POST['submitEdit']))
{
    //Kapcsolattábla
    //Szerkesztés előtti koncertek törlése
    mysqli_query($link, sprintf("delete concert_has_band from concert_has_band
                                    inner join concert c on concert_has_band.concertid = c.id
                                    where c.id = '%d';", $_GET['id']));
    //Szerkesztés utáni koncertek hozzáadása
    if($_POST["band1"] !== "") mysqli_query($link, sprintf("insert into concert_has_band(concertid, bandid) VALUES('%d', '%d')", $_GET['id'], $_POST['band1']));
    if($_POST["band2"] !== "") mysqli_query($link, sprintf("insert into concert_has_band(concertid, bandid) VALUES('%d', '%d')", $_GET['id'], $_POST['band2']));
    if($_POST["band3"] !== "") mysqli_query($link, sprintf("insert into concert_has_band(concertid, bandid) VALUES('%d', '%d')", $_GET['id'], $_POST['band3']));
    if($_POST["band4"] !== "") mysqli_query($link, sprintf("insert into concert_has_band(concertid, bandid) VALUES('%d', '%d')", $_GET['id'], $_POST['band4']));

    //Koncert
    $venueid = mysqli_real_escape_string($link, $_POST['venueid']);
    $date = mysqli_real_escape_string($link, $_POST['date']);
    $available_tickets = mysqli_real_escape_string($link, $_POST['available_tickets']);
    $query = sprintf("update concert set venueid = NULLIF('%s', ''), date = '%s', available_tickets = '%d' where id = '%d'",
                                    $venueid, $date, $available_tickets, $id);
    mysqli_query($link, $query) or die(mysqli_error($link));
    mysqli_close($link);
    $updated = true;
    header("Location: concerts.php?updated=".$updated);
}
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
        <?php if(isset($_GET['mode'])):
            if($_GET['mode'] === 'edit'):?>
        <div class="title">Koncert szerkesztése</div>
        <div class = editDiv>
            <form action="editConcerts.php?id=<?=$id?>" method="post">
                <table class="tableEdit">
                    <tr>
                        <td><label for="bands1">Együttesek</label></td>
                        <?php $bandsExploded = explode(", ", $row['bands']); ?>
                        <?php $bandIdsExploded = explode(", ", $row['bandids']); ?>
                        <td style="display: inline-block">
                            <select class="bandSelect" name="band1" id="band1">
                                <option value=""></option>
                                <?php
                                $bandQuery = mysqli_query($link, "select id, name from band");
                                while ($bandRow = mysqli_fetch_array($bandQuery)):?>
                                    <option value="<?=$bandRow['id']?>"
                                    <?php if(isset($bandsExploded[0])) if($bandRow['id'] === $bandIdsExploded[0]) echo'selected'?>>
                                        <?=$bandRow['name']?></option>
                                <?php endwhile;?>
                            </select>
                            <select class="bandSelect" name="band2" id="band2">
                                <option value=""></option>
                                <?php
                                $bandQuery = mysqli_query($link, "select id, name from band");
                                while ($bandRow = mysqli_fetch_array($bandQuery)):?>
                                <option value="<?=$bandRow['id']?>"
                                    <?php if(isset($bandsExploded[1])) if($bandRow['id'] === $bandIdsExploded[1]) echo'selected'?>>
                                    <?=$bandRow['name']?></option>
                                <?php endwhile;?>
                            </select>
                            <select class="bandSelect" name="band3" id="band3">
                                <option value=""></option>
                                <?php
                                $bandQuery = mysqli_query($link, "select id, name from band");
                                while ($bandRow = mysqli_fetch_array($bandQuery)):?>
                                <option value="<?=$bandRow['id']?>"
                                    <?php if(isset($bandsExploded[2])) if($bandRow['id'] === $bandIdsExploded[2]) echo'selected'?>>
                                    <?=$bandRow['name']?></option>
                                <?php endwhile;?>
                            </select>
                            <select class="bandSelect" name="band4" id="band4">
                                <option value=""></option>
                                <?php
                                $bandQuery = mysqli_query($link, "select id, name from band");
                                while ($bandRow = mysqli_fetch_array($bandQuery)):?>
                                <option value="<?=$bandRow['id']?>"
                                    <?php if(isset($bandsExploded[3])) if($bandRow['id'] === $bandIdsExploded[3]) echo'selected'?>>
                                    <?=$bandRow['name']?></option>
                                <?php endwhile;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="venueid">Helyszín</label></td>
                        <td>
                            <select name="venueid" id="venueid">
                                <?php
                                $venueQuery = mysqli_query($link, "select id, name from venue") or die(mysqli_error());
                                $selectThisVenue = $row['venueid'];?>
                                <option value=""></option>
                                <?php while ($venueRow = mysqli_fetch_array($venueQuery)):?>
                                <option value="<?=$venueRow['id']?>" <?php if($venueRow['id'] === $selectThisVenue) echo'selected'?> >
                                    <?=$venueRow['name']?></option>
                                <?php endwhile;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="date">Idő</label></td>
                        <td><input type="date" name="date" id="date" value="<?=$row['date']?>"></td>
                    </tr>
                    <tr>
                        <td><label for="available_tickets">Vehető-e jegy</label></td>
                        <td>
                            <select name="available_tickets" id="available_tickets">
                                <option value="1" <?php if($row['available_tickets']==="Igen") echo'selected'?>>Igen</option>
                                <option value="0" <?php if($row['available_tickets']==="Nem") echo'selected'?>>Nem</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><input class="submitButton" type="submit" name="submitEdit" value="Elküld"></td>
                    </tr>
                </table>
            </form>
        </div>
        <?php endif; endif;?>
        <?php if(isset($_GET['mode'])):
            if($_GET['mode'] === 'add'):?>
                <div class="title">Koncert hozzáadása</div>
                <div class = editDiv>
                    <form action="editConcerts.php" method="post">
                        <table class="tableEdit">
                            <tr>
                                <td><label for="bands1">Együttesek</label></td>
                                <td style="display: inline-block">
                                    <select class="bandSelect" name="band1" id="band1">
                                        <option value="" selected></option>
                                        <?php
                                        $bandQuery = mysqli_query($link, "select id, name from band");
                                        while ($bandRow = mysqli_fetch_array($bandQuery)):?>
                                            <option value="<?=$bandRow['id']?>"><?=$bandRow['name']?></option>
                                        <?php endwhile;?>
                                    </select>
                                    <select class="bandSelect" name="band2" id="band2">
                                        <option value="" selected></option>
                                        <?php
                                        $bandQuery = mysqli_query($link, "select id, name from band");
                                        while ($bandRow = mysqli_fetch_array($bandQuery)):?>
                                            <option value="<?=$bandRow['id']?>"><?=$bandRow['name']?></option>
                                        <?php endwhile;?>
                                    </select>
                                    <select class="bandSelect" name="band3" id="band3">
                                        <option value="" selected></option>
                                        <?php
                                        $bandQuery = mysqli_query($link, "select id, name from band");
                                        while ($bandRow = mysqli_fetch_array($bandQuery)):?>
                                            <option value="<?=$bandRow['id']?>"><?=$bandRow['name']?></option>
                                        <?php endwhile;?>
                                    </select>
                                    <select class="bandSelect" name="band4" id="band4">
                                        <option value=""selected></option>
                                        <?php
                                        $bandQuery = mysqli_query($link, "select id, name from band");
                                        while ($bandRow = mysqli_fetch_array($bandQuery)):?>
                                            <option value="<?=$bandRow['id']?>"><?=$bandRow['name']?></option>
                                        <?php endwhile;?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="venueid">Helyszín</label></td>
                                <td>
                                    <select name="venueid" id="venueid">
                                        <?php
                                        $venueQuery = mysqli_query($link, "select id, name from venue") or die(mysqli_error($link));?>
                                        <option value="" selected></option>
                                        <?php while ($venueRow = mysqli_fetch_array($venueQuery)):?>
                                            <option value="<?=$venueRow['id']?>"><?=$venueRow['name']?></option>
                                        <?php endwhile;?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="date">Idő</label></td>
                                <td><input type="date" name="date" id="date"></td>
                            </tr>
                            <tr>
                                <td><label for="available_tickets">Vehető-e jegy</label></td>
                                <td>
                                    <select name="available_tickets" id="available_tickets">
                                        <option value="1" selected>Igen</option>
                                        <option value="0" >Nem</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><input class="submitButton" type="submit" name="submitAdd" value="Elküld"></td>
                            </tr>
                        </table>
                    </form>
                </div>
            <?php endif; endif;?>
    </div>
    <?php include 'bottom.html'; ?>
</div>
</body>
</html>