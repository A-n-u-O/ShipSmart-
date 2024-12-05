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
