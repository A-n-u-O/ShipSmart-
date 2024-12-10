document.addEventListener('DOMContentLoaded', function () {
    const trackingForm = document.querySelector('form');
    const trackingInput = document.querySelector('#tracking_id');
    
    trackingForm.addEventListener('submit', function (event) {
        if (!trackingInput.value.trim()) {
            alert('Please enter a valid tracking ID.');
            event.preventDefault();
        }
    });
});
