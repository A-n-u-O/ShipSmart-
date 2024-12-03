document.addEventListener("DOMContentLoaded", () => {
  const editButtons = document.querySelectorAll(".edit-pickup-btn");
  editButtons.forEach(button => {
      button.addEventListener("click", (event) => {
          const pickupId = event.target.dataset.pickupId; // Assuming you have a data attribute for the pickup ID
          window.location.href = `editPickupDetails.php?id=${pickupId}`; // Redirect to edit page
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