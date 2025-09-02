document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const menu = document.getElementById('navbar-menu');

    if (hamburger && menu) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('hamburger-active');

    
            const isMenuClosed = menu.classList.contains('translate-x-full');

            if (isMenuClosed) {
                menu.classList.remove('hidden');
                setTimeout(() => {
                    menu.classList.remove('translate-x-full');
                }, 10);
            } else {
                menu.classList.add('translate-x-full');
            }
        });

        document.addEventListener('click', (event) => 
        {
          if(!menu.contains(event.target) && !hamburger.contains(event.target))
          {
            if(!menu.classList.contains('translate-x-full'))
            {
              menu.classList.add('translate-x-full');
              hamburger.classList.remove('hamburger-active');
            }
          }

        });
    }
});