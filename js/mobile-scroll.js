document.addEventListener('DOMContentLoaded', function () {
    // Only run on mobile/tablet widths
    if (window.innerWidth <= 768) {

        const scrollContainers = document.querySelectorAll('.mobile-horizontal-view');

        scrollContainers.forEach(container => {
            const row = container.querySelector('.row');

            // Function to update arrow visibility
            const updateArrows = () => {
                const scrollLeft = row.scrollLeft;
                const maxScroll = row.scrollWidth - row.clientWidth;
                const tolerance = 5; // small buffer

                // Left Arrow State
                if (scrollLeft > tolerance) {
                    container.classList.add('can-scroll-left');
                } else {
                    container.classList.remove('can-scroll-left');
                }

                // Right Arrow State
                if (scrollLeft < maxScroll - tolerance) {
                    container.classList.add('can-scroll-right');
                } else {
                    container.classList.remove('can-scroll-right');
                }
            };

            // Initial check
            updateArrows();

            // Check on scroll
            row.addEventListener('scroll', () => {
                // Throttle slightly if needed, but modern browsers handle this okay
                window.requestAnimationFrame(updateArrows);
            });

            // Handle Clicks
            container.addEventListener('click', function (e) {
                const rect = container.getBoundingClientRect();
                const clickX = e.clientX;

                // Define clickable zones (approx 45px from edges)
                const isLeftZone = clickX < rect.left + 45;
                const isRightZone = clickX > rect.right - 45;

                const scrollAmount = 280; // approximate card width

                if (isLeftZone && container.classList.contains('can-scroll-left')) {
                    row.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                } else if (isRightZone && container.classList.contains('can-scroll-right')) {
                    row.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                }
            });
        });
    }
});
