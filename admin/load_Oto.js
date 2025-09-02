function loadOrder(query = "")
{
    fetch('get_Pesan.php?cari=' + encodeURIComponent(query))
    .then(response => response.text())
    .then(data =>
    {
        document.getElementById("order-body").innerHTML = data;
        statusWarna();
    });
}


//Event listener pencarian
document.addEventListener("DOMContentLoaded", function()
{
    const formCari = document.getElementById("formCari");
    const inputCari = document.getElementById("inputCari");

    formCari.addEventListener("submit", function(e) 
    {
        e.preventDefault();
        const query = inputCari.value.trim();
        loadOrder(query);
    });

    loadOrder();

    setInterval(() => 
    {
        if(inputCari.value.trim() === "")
        {
            loadOrder();
        }
    }, 5000);
})
