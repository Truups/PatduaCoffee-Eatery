function statusWarna() 
{
    let statusCells = document.querySelectorAll(".statusCell");

    statusCells.forEach(cell => {
        let status = cell.textContent.trim();

        // Reset warna 
        cell.style.color = "";
        cell.style.backgroundColor = "";

        if (status === "Pending") {
            cell.style.color = "#D97706";
            cell.style.backgroundColor = "#FEF3C7";
        } else if (status === "Diproses") {
            cell.style.color = "#2563EB";
            cell.style.backgroundColor = "#DBEAFE";
        } else if (status === "Selesai") {
            cell.style.color = "#047857";
            cell.style.backgroundColor = "#D1FAE5";
        } else if (status === "Dibatalkan") {
            cell.style.color = "#DC2626";
            cell.style.backgroundColor = "#FECACA";
        }
    });
}
document.addEventListener("DOMContentLoaded", statusWarna);
