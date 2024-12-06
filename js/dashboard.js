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
                    <p><strong>Pickup Time:</strong> ${data.pickup_time}</p>
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

// Set greeting based on the time of day
function setGreeting() {
    const greetingElement = document.getElementById('greeting');
    const greetingIcon = document.getElementById('greeting-icon'); // Define greetingIcon here
    const currentHour = new Date().getHours();
    let greeting;

    if (currentHour < 12) {
        greeting = "Good Morning, ";
        greetingIcon.src = "../Assets/icons/morningIcon.svg"; // Path to morning SVG
    } else if (currentHour < 18) {
        greeting = "Good Afternoon, ";
        greetingIcon.src = "../Assets/icons/afternoonIcon.svg"; // Path to afternoon SVG
    } else {
        greeting = "Good Evening, ";
        greetingIcon.src = "../Assets/icons/nightIcon.svg"; // Path to night SVG
    }

    greetingElement.textContent = greeting;
}


// Call the setGreeting function when the page loads
window.onload = setGreeting;