// Retrieve pickup details from localStorage
const pickupDetails = JSON.parse(localStorage.getItem("pickupDetails"));
if (pickupDetails) {
  document.getElementById(
    "scheduled-details"
  ).textContent = `Date: ${pickupDetails.date}, Time: ${pickupDetails.time}, Address: ${pickupDetails.address}`;
}

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
