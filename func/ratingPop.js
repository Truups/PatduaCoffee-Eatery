document.addEventListener('DOMContentLoaded', function () 
{
    const menuP = JSON.parse(localStorage.getItem('menuP')) || [];

    if (menuP.length > 0) 
        {
        const allRatings = [];

        const container = document.createElement('div');
        container.className = 'fixed inset-0 z-50 flex flex-col items-center justify-start p-6 space-y-4 overflow-auto';
        container.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        document.body.appendChild(container);

        menuP.forEach((menu, index) => 
            {
            const popup = document.createElement('div');
            popup.className = 'bg-white shadow-lg p-6 rounded-xl w-full max-w-md';
            popup.innerHTML = `
                <h3 class="text-xl font-bold mb-2 text-green-700">Beri rating untuk: ${menu.nama}</h3>
                <div class="flex mb-4" id="stars-${index}">
                    ${[1, 2, 3, 4, 5].map(i => `
                        <svg data-rate="${i}" class="star w-8 h-8 cursor-pointer text-gray-400 hover:text-yellow-400 fill-current transition" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 .587l3.668 7.571 8.332 1.151-6.064 5.845 1.48 8.209L12 18.896l-7.416 4.467 1.48-8.209L0 9.309l8.332-1.151z"/>
                        </svg>
                    `).join('')}
                </div>
            `;

            container.appendChild(popup);

            const starsContainer = popup.querySelector(`#stars-${index}`);
            const stars = starsContainer.querySelectorAll('.star');
            let selectedRating = 0;

            stars.forEach(star => 
                {
                star.addEventListener('click', () => 
                    {
                    selectedRating = parseInt(star.dataset.rate);
                    stars.forEach(s => 
                        {
                        const rate = parseInt(s.dataset.rate);
                        s.classList.toggle('text-yellow-400', rate <= selectedRating);
                        s.classList.toggle('text-gray-400', rate > selectedRating);
                    });

                    // Simpan rating ke array
                    const existing = allRatings.find(r => r.id_Menu === menu.id_Menu);
                    if (existing) 
                        {
                        existing.rating = selectedRating;
                    } else {
                        allRatings.push(
                        {
                            id_Menu: menu.id_Menu,
                            id_Pelanggan: menu.id_Pelanggan,
                            rating: selectedRating
                        });
                    }
                });
            });
        });

        // Tombol Kirim Semua
        const submitAllBtn = document.createElement('button');
        submitAllBtn.textContent = 'Kirim Semua Rating';
        submitAllBtn.className = 'mt-6 bg-green-700 text-white px-6 py-3 rounded-lg hover:bg-green-800';
        submitAllBtn.addEventListener('click', () => 
            {
            if (allRatings.length < menuP.length) 
                {
                alert('Silakan isi rating untuk semua menu terlebih dahulu.');
                return;
            }

            Promise.all(allRatings.map(rating => 
                {
                const formData = new FormData();
                formData.append('id_Menu', rating.id_Menu);
                formData.append('id_Pelanggan', rating.id_Pelanggan);
                formData.append('rating', rating.rating);

                return fetch('/func/simpanRate.php', 
                    {
                    method: 'POST',
                    body: formData
                }).then(res => res.json());
            }))
            .then(results => 
                {
                    console.log('Rating Results:', results);
                const gagal = results.filter(r => r.status !== 'sukses');
                if (gagal.length === 0) 
                {
                    alert('Semua rating berhasil disimpan!');
                    localStorage.removeItem('menuP');
                    container.remove();
                } else 
                {
                    alert(`${gagal.length} rating gagal disimpan.`);
                    localStorage.removeItem('menuP');
                    container.remove();
                }
            })
            .catch(err => 
            {
                console.error('Error:', err);
                alert('Terjadi kesalahan saat mengirim rating.');
                
                  localStorage.removeItem('menuP'); 
                  if (container) container.remove();
            });
        });

        container.appendChild(submitAllBtn);
    }
});
