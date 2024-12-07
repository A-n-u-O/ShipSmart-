document.addEventListener("DOMContentLoaded", () => {
    const pickupDateInput = document.getElementById("pickup_date");
    const pickupTimeInput = document.getElementById("pickup_time");
    const phoneNumberInput = document.getElementById("phone_number");
    const itemDescriptionInput = document.getElementById("item_description");

    // Error message elements
    const errorMessages = {
        date: document.getElementById('error_date'),
        time: document.getElementById('error_time'),
        description: document.getElementById('error_item_description'),
        phone: document.getElementById('error_phone_number')
    };

    // Set the minimum date for pickup to today and maximum to 3 months from today
    const today = new Date();
    const minDateString = today.toISOString().split("T")[0];
    
    const maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 3);
    
    const maxDateString = maxDate.toISOString().split("T")[0];

    pickupDateInput.setAttribute("min", minDateString);
    pickupDateInput.setAttribute("max", maxDateString);

    const validateTime = (event) => {
        const [hours] = event.target.value.split(":").map(Number);
        if (hours < 9 || hours >= 17) {
            errorMessages.time.textContent = "Please select a time between 9:00 AM and 5:00 PM.";
            event.target.value = ""; // Reset invalid time
        } else {
            errorMessages.time.textContent = ""; // Clear error message
        }
    };

    const validateDate = (event) => {
        const selectedDate = new Date(event.target.value);
        const dayOfWeek = selectedDate.getDay();
        
        // Check if the selected date is a weekend
        if (dayOfWeek === 0 || dayOfWeek === 6) {
            errorMessages.date.textContent = "Pickup is only available Monday to Friday.";
            event.target.value = ""; // Reset invalid date
        } else if (selectedDate < today) {
            errorMessages.date.textContent = "Pickup date cannot be in the past.";
            event.target.value = ""; // Reset invalid date
        } else {
            errorMessages.date.textContent = ""; // Clear error message
        }
    };

   const validateDescription = (event) => {
       const wordsCount = event.target.value.trim().split(/\s+/).length;
       if (wordsCount < 3) {
           errorMessages.description.textContent = "Description must be at least 3 words.";
       } else {
           errorMessages.description.textContent = ""; // Clear error message
       }
   };

   const validatePhoneNumber = (event) => {
       const phoneNumberRegex = /^(?:\+234|0)\d{10}$/;
       if (!phoneNumberRegex.test(event.target.value)) {
           errorMessages.phone.textContent =
               "Please enter a valid Nigerian phone number (e.g., +234XXXXXXXXXX or 0XXXXXXXXXX).";
           event.target.classList.add('error');
       } else {
           errorMessages.phone.textContent = ""; // Clear error message
           event.target.classList.remove('error');
       }
   };

   // Adding event listeners for time, date, description, and phone validation
   pickupTimeInput.addEventListener("input", validateTime);
   pickupDateInput.addEventListener("input", validateDate);
   itemDescriptionInput.addEventListener("input", validateDescription);
   phoneNumberInput.addEventListener("input", validatePhoneNumber);
});