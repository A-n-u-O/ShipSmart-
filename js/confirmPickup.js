document.addEventListener("DOMContentLoaded", () => {
    // Retrieve the pickup details from localStorage
    const pickupDetails = JSON.parse(localStorage.getItem("pickupDetails"));
  
    // Display the pickup details or show 'Not available' if data is missing
    if (pickupDetails) {
      document.getElementById("confirm-date").textContent = `Date: ${pickupDetails.pickupDate || 'Not available'}`;
      document.getElementById("confirm-time").textContent = `Time: ${pickupDetails.pickupTime || 'Not available'}`;
      document.getElementById("confirm-address").textContent = `Pickup Address: ${pickupDetails.pickupAddress || 'Not available'}`;
      document.getElementById("confirm-delivery-address").textContent = `Delivery Address: ${pickupDetails.deliveryAddress || 'Not available'}`;
      document.getElementById("confirm-phone-number").textContent = `Phone Number: ${pickupDetails.phoneNumber || 'Not available'}`;
      document.getElementById("confirm-item-description").textContent = `Item Description: ${pickupDetails.itemDescription || 'Not available'}`;
      document.getElementById("confirm-item-weight").textContent = `Item Weight: ${pickupDetails.itemWeight || 'Not available'}`;
      document.getElementById("confirm-courier").textContent = `Courier: ${pickupDetails.courier || 'Not available'}`;
      document.getElementById("confirm-company").textContent = `Company: ${pickupDetails.company || 'Not available'}`;
    } else {
      // Show 'Not available' if no details are found in localStorage
      document.getElementById("confirm-date").textContent = "Date: Not available";
      document.getElementById("confirm-time").textContent = "Time: Not available";
      document.getElementById("confirm-address").textContent = "Pickup Address: Not available";
      document.getElementById("confirm-delivery-address").textContent = "Delivery Address: Not available";
      document.getElementById("confirm-phone-number").textContent = "Phone Number: Not available";
      document.getElementById("confirm-item-description").textContent = "Item Description: Not available";
      document.getElementById("confirm-item-weight").textContent = "Item Weight: Not available";
      document.getElementById("confirm-courier").textContent = "Courier: Not available";
      document.getElementById("confirm-company").textContent = "Company: Not available";
    }
  
    // Add event listener for the confirm button to send the pickup details
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
  
    // Edit button redirects to the schedulePickup page
    const editBtn = document.getElementById("edit-btn");
    if (editBtn) {
      editBtn.addEventListener("click", () => {
        window.location.href = "schedulePickup.php";
      });
    }
  
    // Show alert function
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
  
    // Validation for pickup time and date
    const validateTime = (event) => {
      const [hours] = event.target.value.split(":").map(Number);
      const errorTime = document.getElementById("error_time");
      if (hours < 9 || hours >= 17) {
        errorTime.textContent =
          "Please select a time between 9:00 AM and 5:00 PM.";
        event.target.value = "";
      } else {
        errorTime.textContent = "";
      }
    };
  
    const validateDate = (event) => {
      const dayOfWeek = new Date(event.target.value).getDay();
      const errorDate = document.getElementById("error_date");
      if (dayOfWeek === 0 || dayOfWeek === 6) {
        errorDate.textContent = "Pickup is only available Monday to Friday.";
        event.target.value = "";
      } else {
        errorDate.textContent = "";
      }
    };
  
    // Listen for input events on pickup date and time
    document.getElementById("pickup_time").addEventListener("input", validateTime);
    document.getElementById("pickup_date").addEventListener("input", validateDate);
  });
  