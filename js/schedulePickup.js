document.addEventListener("DOMContentLoaded", () => {
  // Set the minimum date for pickup to today
  const today = new Date();
  const minDate = today.toISOString().split("T")[0];
  document.getElementById("pickup_date").setAttribute("min", minDate);

  // Validate pickup time to ensure it's between 9 AM and 5 PM
  const validateTime = (event) => {
      const [hours] = event.target.value.split(":").map(Number);
      const errorTime = document.getElementById('error_time');
      if (hours < 9 || hours >= 17) {
          errorTime.textContent = "Please select a time between 9:00 AM and 5:00 PM.";
          event.target.value = ""; // Reset invalid time
      } else {
          errorTime.textContent = ""; // Clear error message
      }
  };

  // Validate pickup date to ensure it's not on the weekend
  const validateDate = (event) => {
      const dayOfWeek = new Date(event.target.value).getDay();
      const errorDate = document.getElementById('error_date');
      if (dayOfWeek === 0 || dayOfWeek === 6) {
          errorDate.textContent = "Pickup is only available Monday to Friday.";
          event.target.value = ""; // Reset invalid date
      } else {
          errorDate.textContent = ""; // Clear error message
      }
  };

  // Confirm pickup and validate form before submission
  const confirmPickup = document.getElementById("confirm-pickup-btn");
  confirmPickup.addEventListener("click", (event) => {
    event.preventDefault();
    const form = confirmPickup.closest("form");
    const formData = new FormData(form);
    const formInputs = form.querySelectorAll("input");
    let isValid = true;

    formInputs.forEach((input) => {
      if (input.required && input.value.trim() === "") {
        input.classList.add("error");
        isValid = false;
      } else {
        input.classList.remove("error");
      }
    });

    if (isValid) {
      form.submit();
    }
  });

  // Add event listeners for time and date validation
  document.getElementById("pickup_time").addEventListener("input", validateTime);
  document.getElementById("pickup_date").addEventListener("input", validateDate);
});
