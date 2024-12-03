document.addEventListener("DOMContentLoaded", () => {
    const pickupDateInput = document.getElementById("pickup_date");
    const pickupTimeInput = document.getElementById("pickup_time");

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

    pickupTimeInput.addEventListener("input", validateTime);
    pickupDateInput.addEventListener("input", validateDate);
});