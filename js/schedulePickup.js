document.addEventListener("DOMContentLoaded", () => {
  const today = new Date();
  const minDate = today.toISOString().split("T")[0];
  document.getElementById("pickup_date").setAttribute("min", minDate);

  // Validate pickup time to ensure it's between 9:00 AM and 5:00 PM
  const validateTime = (event) => {
    const [hours] = event.target.value.split(":").map(Number);
    const errorTime = document.getElementById("error_time");
    if (hours < 9 || hours >= 17) {
      errorTime.textContent = "Please select a time between 9:00 AM and 5:00 PM.";
      event.target.value = "";
    } else {
      errorTime.textContent = "";
    }
  };

  // Validate pickup date to ensure it's a weekday (Monday to Friday)
  const validateDate = (event) => {
    const dayOfWeek = new Date(event.target.value).getDay();
    const errorDate = document.getElementById("error_date");
    if (dayOfWeek === 0 || dayOfWeek === 6) {
      errorDate.textContent = "Pickup is only available Monday to Friday.";
      event.target.value = "";
    } else {
      errorDate.textContent = "";
    }
  };

  // Validate shipping port selection
  const shippingPortInput = document.getElementById("shipping_port");
  const errorShippingPort = document.getElementById("error_shipping_port");

  shippingPortInput.addEventListener("change", () => {
    if (!shippingPortInput.value) {
      errorShippingPort.textContent = "Please select a shipping port.";
    } else {
      errorShippingPort.textContent = "";
    }
  });

  // Attach validation events
  document.getElementById("pickup_time").addEventListener("input", validateTime);
  document.getElementById("pickup_date").addEventListener("input", validateDate);

  // Handle form submission and store pickup details in localStorage
  const scheduleForm = document.getElementById("schedule_form");
  if (scheduleForm) {
    scheduleForm.addEventListener("submit", (event) => {
      event.preventDefault();

      const pickupDate = document.getElementById("pickup_date").value;
      const pickupTime = document.getElementById("pickup_time").value;
      const pickupAddress = document.getElementById("pickup_address").value;
      const deliveryAddress = document.getElementById("delivery_address").value;
      const phoneNumber = document.getElementById("phone_number").value;
      const itemDescription = document.getElementById("item_description").value;
      const itemWeight = document.getElementById("item_weight").value;
      const courier = document.getElementById("courier").value;
      const company = document.getElementById("company").value;

      const pickupDetails = {
        pickupDate,
        pickupTime,
        pickupAddress,
        deliveryAddress,
        phoneNumber,
        itemDescription,
        itemWeight,
        courier,
        company,
      };

      // Store the pickup details in localStorage
      localStorage.setItem("pickupDetails", JSON.stringify(pickupDetails));

      // Redirect to confirmation page
      window.location.href = "confirmPickup.php";
    });
  }
});
