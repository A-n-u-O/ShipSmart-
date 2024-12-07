document.addEventListener("DOMContentLoaded", () => {
    const pickupDetails = JSON.parse(localStorage.getItem("pickupDetails"));

    if (pickupDetails) {
        document.getElementById("confirm-date").textContent = `Date: ${pickupDetails.pickupDate || 'Not available'}`;
        document.getElementById("confirm-time").textContent = `Time: ${pickupDetails.pickupTime || 'Not available'}`;
        document.getElementById("confirm-address").textContent = `Pickup Address: ${pickupDetails.pickupAddress || 'Not available'}`;
    } else {
        document.getElementById("confirm-date").textContent = "Date: Not available";
        document.getElementById("confirm-time").textContent = "Time: Not available";
        document.getElementById("confirm-address").textContent = "Pickup Address: Not available";
    }

    const confirmBtn = document.getElementById("confirm-btn");
    if (confirmBtn) {
        confirmBtn.addEventListener("click", (event) => {
            event.preventDefault(); 

            if (!pickupDetails) {
                showAlert("No pickup details available to confirm.", true);
                return;
            }

            fetch("./schedulePickupHandler.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(pickupDetails),
            })
                .then((response) => {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        showAlert(data.message || "Pickup confirmed successfully!");
                        localStorage.removeItem("pickupDetails");
                    } else {
                        showAlert(data.message || "Failed to confirm pickup. Please try again.", true);
                    }
                })
                .catch(() =>
                    showAlert("Error confirming pickup. Please try again later.", true)
                );
        });
    }

    const editBtn = document.getElementById("edit-btn");
    if (editBtn) {
        editBtn.addEventListener("click", () => {
            window.location.href = "schedulePickup.php";
        });
    }

    const showAlert = (message, isError = false) => {
        const alertDiv = document.querySelector(".alert-message");
        if (alertDiv) {
            alertDiv.style.display = "block";
            alertDiv.style.backgroundColor = isError ? "#f8d7da" : "#d4edda";
            alertDiv.style.color = isError ? "#842029" : "#155724";
            alertDiv.textContent = message;

            setTimeout(() => {
                alertDiv.style.display = "none";
            }, 3000);
        }
    };
});
