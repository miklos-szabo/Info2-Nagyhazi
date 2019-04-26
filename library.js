function showAddRow() {

    let row = document.getElementById("addingTableRow");
    if (row.style.visibility === "collapse" || row.style.visibility === '')
    {
        row.style.visibility = "visible";
    }
    else row.style.visibility = "collapse";
}