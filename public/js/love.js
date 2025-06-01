document.addEventListener('DOMContentLoaded', function () {
    const loveIcons = document.querySelectorAll('.love-icon');
    
    loveIcons.forEach(icon => {
        icon.addEventListener('click', function (event) {
            event.stopPropagation(); // Mencegah klik pada ikon love memicu klik pada <a>

            const commissionId = this.dataset.commissionId;
            const loveCountSpan = this.nextElementSibling;
            
            fetch(`/commissions/love/${commissionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ commission_id: commissionId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.loved) {
                    this.classList.remove('fa-regular');
                    this.classList.add('fa-solid');
                    this.style.color = '#ff0000';
                } else {
                    this.classList.remove('fa-solid');
                    this.classList.add('fa-regular');
                    this.style.color = '#ff3300';
                }

                loveCountSpan.textContent = data.loved_count;
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
