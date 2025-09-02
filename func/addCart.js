function refreshKeranjang()
{
  fetch('/func/lihatCart.php')
  .then(response => response.text())
  .then(html => 
    {
      const konCart = document.getElementById('modal-content');
      if(konCart)
      {
        konCart.innerHTML = html;
      }
      updateKeranjang();
  })
  .catch(error => console.error('Fetch error: ', error));
}

//Update jumlah item di cart badge
function updateKeranjang() 
{
  fetch('/func/hitungCart.php')
    .then(response => response.json())
    .then(data => 
    {
      const count = data.count || 0;

      // Update semua elemen dengan class badge-cart
      document.querySelectorAll('.badge-cart').forEach(el =>
      {
        el.textContent = count;
      });
    })
    .catch(error => console.error('Fetch error:', error));
}



function addToCart(menuId) 
{
    console.log('addToCart → menuId =', menuId);
    fetch('/func/cart.php', 
      {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id_menu: menuId }).toString()
    })
    .then(r => r.json())
    .then(data => 
      {
      if (data.status === 'success') 
        {
          updateKeranjang();
        } else 
        {
          alert('Gagal menambahkan item: ' + data.message);
        }
  })
    .catch(err => console.error('fetch error', err));
}

function kurangiItem(menuId)
{
    fetch('/func/cart.php',
        {
            method: 'POST',
            credentials: 'same-origin',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=decrease&id_menu=${encodeURIComponent(menuId)}`
        })
    .then(response => response.json())
    .then(data =>
    {
        if(data.status === 'success')
        {
          refreshKeranjang();
        }
        else
        {
            alert('Gagal mengurangi item: ' + data.message);
        }
    })
    .catch(error => console.error('Fetch error:', error));
}

function hapusCart()
{
    fetch('/func/cart.php',
        {
            method: 'POST',
            credentials: 'same-origin',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=clear`
            
        })
        .then(response => response.json())
        .then(data => 
        {
          if(data.status === 'success')
          {
            refreshKeranjang();
          } else 
          {
            alert('Gagal mengosongkan keranjang: ' + data.message);
          }
        })
        .catch(error => console.error('Fetch error:', error));
}

function coCart()
{
  window.location.href = 'checkout';
}

document.addEventListener('DOMContentLoaded', () =>
{
  updateKeranjang();
});