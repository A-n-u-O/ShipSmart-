// confirmPickup.js
document.addEventListener("DOMContentLoaded", () => {
    // Retrieve pickup details from localStorage
    const pickupDetails = JSON.parse(localStorage.getItem("pickupDetails"));

    // Display the pickup details
    if (pickupDetails) {
        document.getElementById("confirm-date").textContent = `Date: ${pickupDetails.pickupDate}`; // Updated key
        document.getElementById("confirm-time").textContent = `Time: ${pickupDetails.pickupTime}`; // Updated key
        document.getElementById("confirm-address").textContent = `Pickup Address: ${pickupDetails.pickupAddress}`; // Updated key
    } else {
        // Handle the case where pickupDetails is null
        document.getElementById("confirm-date").textContent = "Date: Not available";
        document.getElementById("confirm-time").textContent = "Time: Not available";
        document.getElementById("confirm-address").textContent = "Pickup Address: Not available";
    }

    // Confirm button event listener
    document.getElementById("confirm-btn").addEventListener("click", () => {
        //sending the details to the server
        fetch("./schedulePickupHandler.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(pickupDetails),
        })
        .then(response => response.json())
        .then(data => {
            showAlert(data.message);
            // display a success message
        })
        .catch(() => showAlert("Error confirming pickup. Please try again later.", true));
    });

    // Edit button event listener
    document.getElementById("edit-btn").addEventListener("click", () => {
        window.location.href = "schedulePickup.php"; // Redirect back to the scheduling page
    });

    const showAlert = (message, isError = false) => {
        const alertDiv = document.querySelector(".alert-message");
        alertDiv.style.display = "block";
        alertDiv.style.backgroundColor = isError ? "#f8d7da" : "#d4edda";
        alertDiv.style.color = isError ? "#842029" : "#155724";
        alertDiv.textContent = message;

        setTimeout(() => {
            alertDiv.style.display = "none";
        }, 3000);
    };
});