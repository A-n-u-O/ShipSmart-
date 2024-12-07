document.addEventListener("DOMContentLoaded", () => {
    const pickupDateInput = document.getElementById("pickup_date");
    const pickupTimeInput = document.getElementById("pickup_time");
    const phoneNumberInput = document.getElementById("phone_number");
    const itemDescriptionInput = document.getElementById("item_description");
    const courierInput = document.getElementById("courier");
    const destinationZoneInput = document.getElementById("destination_zone");

    const errorMessages = {
        date: document.getElementById('error_date'),
        time: document.getElementById('error_time'),
        description: document.getElementById('error_item_description'),
        phone: document.getElementById('error_phone_number'),
        courier: document.getElementById('error_courier'),
        destinationZone: document.getElementById('error_destination_zone'),
    };

    // Set the minimum and maximum date for pickup
    const today = new Date();
    const minDateString = today.toISOString().split("T")[0];
    const maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 3);
    const maxDateString = maxDate.toISOString().split("T")[0];
    pickupDateInput.setAttribute("min", minDateString);
    pickupDateInput.setAttribute("max", maxDateString);

    // Validation functions
    const validateDate = (event) => {
        const selectedDate = new Date(event.target.value);
        if (selectedDate.getDay() === 0 || selectedDate.getDay() === 6) {
            errorMessages.date.textContent = "Pickup is only available Monday to Friday.";
        } else {
            errorMessages.date.textContent = "";
        }
    };

    const validateTime = (event) => {
        const [hours] = event.target.value.split(":").map(Number);
        if (hours < 9 || hours >= 17) {
            errorMessages.time.textContent = "Select a time between 9:00 AM and 5:00 PM.";
            event.target.value = "";
        } else {
            errorMessages.time.textContent = "";
        }
    };

    const validatePhoneNumber = (event) => {
        const regex = /^(?:\+234|0)\d{10}$/;
        if (!regex.test(event.target.value)) {
            errorMessages.phone.textContent = "Enter a valid Nigerian phone number.";
        } else {
            errorMessages.phone.textContent = "";
        }
    };

    const validateDescription = (event) => {
        const wordsCount = event.target.value.trim().split(/\s+/).length;
        if (wordsCount < 3) {
            errorMessages.description.textContent = "Description must be at least 3 words.";
        } else {
            errorMessages.description.textContent = "";
        }
    };

    const validateSelect = (input, errorField, message) => {
        if (!input.value) {
            errorField.textContent = message;
        } else {
            errorField.textContent = "";
        }
    };

    // Event listeners
    pickupDateInput.addEventListener("blur", validateDate);
    pickupTimeInput.addEventListener("blur", validateTime);
    phoneNumberInput.addEventListener("blur", validatePhoneNumber);
    itemDescriptionInput.addEventListener("blur", validateDescription);
    courierInput.addEventListener("change", () => validateSelect(courierInput, errorMessages.courier, "Select a courier."));
    destinationZoneInput.addEventListener("change", () => validateSelect(destinationZoneInput, errorMessages.destinationZone, "Select a destination zone."));
});
