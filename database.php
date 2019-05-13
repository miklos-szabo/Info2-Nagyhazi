<?php
function getDB()
{
    $link = mysqli_connect("localhost", "root", "")
        or die("Kapcsolódási hiba: " . mysqli_error($link));
    mysqli_select_db($link, "concertmanager");
    mysqli_query($link, "set character_set_results= 'utf8'");
    mysqli_query($link, "set character_set_client = 'utf8'");
    return $link;
}

function closeDB($link)
{
    mysqli_close($link);
}
?>