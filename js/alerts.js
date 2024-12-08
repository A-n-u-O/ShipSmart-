document.addEventListener('DOMContentLoaded', function () {
    // Example of alert notification (can be extended)
    const alerts = document.querySelectorAll('.alert-item.unread');
    alerts.forEach(alert => {
        alert.style.backgroundColor = "#f1f8e9"; // Highlight unread alerts
    });
});
