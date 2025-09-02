function changeColor() 
{
    let select = document.getElementById("statusSelect");
    let status = select.value;

    select.classList.remove("text-yellow-600", "text-blue-600", "text-green-600", "text-red-600");
    select.classList.remove("bg-yellow-100", "bg-blue-100", "bg-green-100", "bg-red-100");

    if (status === "Pending") 
        {
        select.classList.add("text-yellow-600", "bg-yellow-100");
    } else if (status === "Diproses") 
        {
        select.classList.add("text-blue-600", "bg-blue-100");
    } else if (status === "Selesai") 
        {
        select.classList.add("text-green-600", "bg-green-100");
    } else if (status === "Dibatalkan") 
        {
        select.classList.add("text-red-600", "bg-red-100");
    }
}