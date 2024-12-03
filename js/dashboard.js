function showModal(bookingId) {
    // Fetch modal and modal content
    const modal = document.getElementById('modal');
    const modalDetails = document.getElementById('modal-details');

    // Display the modal
    modal.style.display = 'block';

    // Fetch booking details dynamically via an Ajax call
    fetch(`../includes/getBookingDetails.php?booking_id=${bookingId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modalDetails.innerHTML = `
                    <p><strong>Booking ID:</strong> ${data.booking_id}</p>
                    <p><strong>Pickup Location:</strong> ${data.pickup_location}</p>
                    <p><strong>Delivery Location:</strong> ${data.delivery_location}</p>
                    <p><strong>Pickup Date:</strong> ${data.pickup_date}</p>
                    <p><strong>Status:</strong> ${data.status}</p>
                `;
            } else {
                modalDetails.innerHTML = `<p>${data.error}</p>`;
            }
        })
        .catch(error => {
            modalDetails.innerHTML = `<p>An error occurred while fetching details.</p>`;
            console.error(error);
        });
}

function closeModal() {
    // Hide the modal
    const modal = document.getElementById('modal');
    modal.style.display = 'none';
}

// Close modal when clicking outside of it
window.addEventListener('click', (event) => {
    const modal = document.getElementById('modal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});
