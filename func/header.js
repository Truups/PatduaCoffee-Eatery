let lastScroll = 0;
const header = document.querySelector('header');
const menu = document.getElementById('navbar-menu');

if (header && menu) 
{
    window.addEventListener('scroll', () => 
    {
        const isMenuClosed = menu.classList.contains('translate-x-full');
        const currentScroll = window.pageYOffset;

        if (isMenuClosed) 
        {
            if (currentScroll > lastScroll && currentScroll > 50) 
            {
                // Scroll ke bawah → sembunyikan header
                header.classList.add('-translate-y-full');
            } else 
            {
                // Scroll ke atas → munculkan header
                header.classList.remove('-translate-y-full');
            }

            lastScroll = currentScroll <= 0 ? 0 : currentScroll;
        }
    });
}
