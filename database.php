<?php
function getDB()
{
    $link = mysqli_connect("localhost", "root", "")
        or die("Kapcsolódási hiba: " . mysqli_error());
    mysqli_select_db($link, "concertmanager");
    mysqli_query($link, "set character_set_results='utf-8'");
    mysqli_query($link, "set character_set_client = 'utf-8'");
    return $link;
}

function closeDB($link)
{
    mysqli_close($link);
}
?>