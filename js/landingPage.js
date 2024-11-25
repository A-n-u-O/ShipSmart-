// ShipSmart.js - Functionality for scrolling testimonials and section visibility

document.addEventListener("DOMContentLoaded", function () {
    const testimonials = document.querySelector('.testimonial-container');
    if (!testimonials) return; // Ensure testimonials container exists

    // Removed scroll button creation and insertion
    // const leftButton = createScrollButton('◀', 'left-button');
    // const rightButton = createScrollButton('▶', 'right-button');
    // insertScrollButtons(testimonials, leftButton, rightButton);

    // Initialize section visibility observer
    observeSections();
});

// Function to observe sections for visibility
function observeSections() {
    const sections = document.querySelectorAll('section');

    const options = {
        root: null,
        threshold: 0.5,
        rootMargin: "0px 0px -50% 0px"
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible'); // Add class when in view
            }
        });
    }, options);

    sections.forEach(section => {
        observer.observe(section);
    });
}