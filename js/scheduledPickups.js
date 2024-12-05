document.addEventListener("DOMContentLoaded", () => {
  // Edit pickup button functionality
  const editButtons = document.querySelectorAll(".edit-pickup-btn");
  editButtons.forEach(button => {
      button.addEventListener("click", (event) => {
          const pickupId = event.target.dataset.pickupId; // Assuming you have a data attribute for the pickup ID
          window.location.href = `editPickupDetails.php?id=${pickupId}`; // Redirect to edit page
      });
  });

  // Redirect back to the scheduling page when the "Edit Schedule" button is clicked
  document.getElementById("edit-schedule-btn").addEventListener("click", () => {
    window.location.href = "schedulePickup.php"; // Redirect back to the scheduling page
  });

  // Cancel pickup modal functionality
  const cancelButtons = document.querySelectorAll(".cancel-btn");
  const modal = document.getElementById("cancelModal");
  const closeModalBtn = document.querySelector(".close-modal-btn");
  const modalBookingId = document.getElementById("modalBookingId");

  cancelButtons.forEach(button => {
      button.addEventListener("click", () => {
          const bookingId = button.dataset.bookingId;
          modalBookingId.value = bookingId; // Set booking ID in hidden input
          modal.style.display = "block"; // Show the modal
      });
  });

  // Close the modal when the close button is clicked
  closeModalBtn.addEventListener("click", () => {
      modal.style.display = "none"; // Hide the modal
  });

  // Delete pickup functionality
  const deleteButtons = document.querySelectorAll(".delete-pickup-btn");
  deleteButtons.forEach(button => {
      button.addEventListener("click", (event) => {
          const pickupId = event.target.dataset.pickupId;
          if (confirm("Are you sure you want to delete this pickup?")) {
              // Redirect to delete handler
              window.location.href = `deletePickup.php?id=${pickupId}`; 
          }
      });
  });
});