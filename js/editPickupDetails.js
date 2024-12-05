document.addEventListener("DOMContentLoaded", () => {
    const pickupDateInput = document.getElementById("pickup_date");
    const pickupTimeInput = document.getElementById("pickup_time");
    const phoneNumberInput = document.getElementById("phone_number");

    // Ensure only today or future dates can be picked
    const today = new Date().toISOString().split("T")[0];
    pickupDateInput.setAttribute("min", today);

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

    // Validate Nigerian phone numbers
    const validatePhoneNumber = (event) => {
        const phoneNumber = event.target.value;
        const phoneRegex = /^(?:\+234|0)\d{10}$/;
        const errorPhone = document.getElementById('error_phone_number');
        if (!phoneRegex.test(phoneNumber)) {
            errorPhone.textContent = "Please enter a valid Nigerian phone number (e.g., +234XXXXXXXXXX or 0XXXXXXXXXX).";
            event.target.classList.add('error');
        } else {
            errorPhone.textContent = "";  // Clear error message
            event.target.classList.remove('error');
        }
    };

    pickupTimeInput.addEventListener("input", validateTime);
    pickupDateInput.addEventListener("input", validateDate);
    phoneNumberInput.addEventListener("input", validatePhoneNumber);
});
