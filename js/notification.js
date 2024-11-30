// Show notification for a few seconds
window.onload = function() {
    const notification = document.getElementById('notification');
    
    // Check if the notification element exists
    if (notification) {
        notification.classList.add('show'); // Add 'show' class to make it visible

        // Hide the notification after a few seconds
        setTimeout(() => {
            notification.classList.remove('show'); // Remove 'show' class to hide it
        }, 3000);
    } else {
        console.warn('Notification element not found.');
    }
};