document.addEventListener("DOMContentLoaded", () => {
  const editButtons = document.querySelectorAll(".edit-pickup-btn");
  editButtons.forEach(button => {
      button.addEventListener("click", (event) => {
          const pickupId = event.target.dataset.pickupId; // Assuming you have a data attribute for the pickup ID
          window.location.href = `editPickupDetails.php?id=${pickupId}`; // Redirect to edit page
      });
  });

document.getElementById("edit-schedule-btn").addEventListener("click", () => {
  window.location.href = "schedulePickup.php"; // Redirect back to the scheduling page
});


document.addEventListener("DOMContentLoaded", () => {
  const cancelButtons = document.querySelectorAll(".cancel-btn");
  const modal = document.getElementById("cancelModal");
  const closeModalBtn = document.querySelector(".close-modal-btn");
  const modalBookingId = document.getElementById("modalBookingId");

  // Show modal on button click
  cancelButtons.forEach(button => {
      button.addEventListener("click", () => {
          const bookingId = button.dataset.bookingId;
          modalBookingId.value = bookingId; // Set booking ID in hidden input
          modal.style.display = "block";
      });
  });

  // Close modal
  closeModalBtn.addEventListener("click", () => {
      modal.style.display = "none";
  });
});
  const deleteButtons = document.querySelectorAll(".delete-pickup-btn");
  deleteButtons.forEach(button => {
      button.addEventListener("click", (event) => {
          const pickupId = event.target.dataset.pickupId;
          if (confirm("Are you sure you want to delete this pickup?")) {
              // Perform deletion (could be an AJAX request or redirect to a deletion handler)
              window.location.href = `deletePickup.php?id=${pickupId}`; // Redirect to delete handler
          }
      });
  });
});
