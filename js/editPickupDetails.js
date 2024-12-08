document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    const pickupDateInput = document.querySelector("#pickup_date");
    const pickupTimeInput = document.querySelector("#pickup_time");
    const pickupAddressInput = document.querySelector("#pickup_address");
    const phoneNumberInput = document.querySelector("#phone_number");
    const courierSelect = document.querySelector("#courier_id");

    const errorDate = document.querySelector("#error_date");
    const errorTime = document.querySelector("#error_time");
    const errorItemDescription = document.querySelector("#error_item_description");
    const errorPhoneNumber = document.querySelector("#error_phone_number");

    const validatePickupDate = () => {
        const today = new Date();
        const selectedDate = new Date(pickupDateInput.value);
        const maxDate = new Date(today.getFullYear(), today.getMonth() + 3, today.getDate());

        if (selectedDate < today) {
            errorDate.textContent = "Pickup date cannot be in the past.";
            return false;
        }

        if (selectedDate > maxDate) {
            errorDate.textContent = "Pickup date cannot be more than 3 months in the future.";
            return false;
        }

        if (selectedDate.getDay() === 6 || selectedDate.getDay() === 0) {
            errorDate.textContent = "Pickup date cannot be on a weekend.";
            return false;
        }

        errorDate.textContent = "";
        return true;
    };

    const validatePickupTime = () => {
        const selectedTime = pickupTimeInput.value;
        const timeParts = selectedTime.split(":");
        const hours = parseInt(timeParts[0]);
        const minutes = parseInt(timeParts[1]);

        if (hours < 9 || hours > 17 || (hours === 17 && minutes > 0)) {
            errorTime.textContent = "Pickup time must be between 9 AM and 5 PM.";
            return false;
        }

        errorTime.textContent = "";
        return true;
    };

    const validatePhoneNumber = () => {
        const phoneNumber = phoneNumberInput.value;
        const regex = /^(?:\+234|0)\d{10}$/;

        if (!regex.test(phoneNumber)) {
            errorPhoneNumber.textContent = "Invalid Nigerian phone number.";
            return false;
        }

        errorPhoneNumber.textContent = "";
        return true;
    };

    const validatePickupAddress = () => {
        if (!pickupAddressInput.value.trim()) {
            errorItemDescription.textContent = "Pickup address is required.";
            return false;
        }

        errorItemDescription.textContent = "";
        return true;
    };

    form.addEventListener("submit", (event) => {
        if (!validatePickupDate() || !validatePickupTime() || !validatePhoneNumber() || !validatePickupAddress()) {
            event.preventDefault();
        }
    });

    pickupDateInput.addEventListener("blur", validatePickupDate);
    pickupTimeInput.addEventListener("blur", validatePickupTime);
    phoneNumberInput.addEventListener("blur", validatePhoneNumber);
    pickupAddressInput.addEventListener("blur", validatePickupAddress);
});
