document.addEventListener("DOMContentLoaded", function() 
{
    const formCari = document.getElementById("formCari");
    const inputCari = document.getElementById("inputCari");
    const tbody = document.getElementById("customer-body");

    function loadCust(query = "")
    {
        fetch('get_Cust.php?cari=' + encodeURIComponent(query))
        .then(response => response.text())
        .then(data => 
        {
            tbody.innerHTML = data;
        });
    }

    //Load customer 
    loadCust();

    // Pencarian
    formCari.addEventListener("submit", function (e) 
    {
        e.preventDefault();
        const query = inputCari.value.trim();
        loadCust(query);
    });

    let debounceTimer;
    inputCari.addEventListener("input", function()
    {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => 
        {
            loadCust(inputCari.value.trim());
        }, 500);
    });
});