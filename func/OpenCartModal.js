function openCartModal()
{
    fetch('/func/lihatCart.php',
        {
           credentials: 'same-origin' 
        }
    )
    .then(res => res.text())
    .then(html =>
    {
        document.getElementById('modal-content').innerHTML = html;
        document.getElementById('cart-modal').classList.remove('hidden');
    }
    );
}

function closeCartModal()
{
    document.getElementById('cart-modal').classList.add('hidden');
}

document.getElementById('cart-modal').addEventListener('click', e => 
    {
    if (e.target.id === 'cart-modal') closeCartModal();
  });
        


