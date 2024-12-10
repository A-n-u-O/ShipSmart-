document.addEventListener("DOMContentLoaded", () => {
  const today = new Date();
  const minDate = today.toISOString().split("T")[0];
  document.getElementById("pickup_date").setAttribute("min", minDate);

  // Validate pickup time to ensure it's between 9:00 AM and 5:00 PM
  const validateTime = (event) => {
    const [hours] = event.target.value.split(":").map(Number);
    const errorTime = document.getElementById("error_time");
    if (hours < 9 || hours >= 17) {
      errorTime.textContent =
        "Please select a time between 9:00 AM and 5:00 PM.";
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
  document
    .getElementById("pickup_time")
    .addEventListener("input", validateTime);
  document
    .getElementById("pickup_date")
    .addEventListener("input", validateDate);

  // Fetch couriers based on selected delivery company
  function fetchCouriers() {
    const companySelect = document.getElementById("fk_delivery_company_id");
    const courierSelect = document.getElementById("courier_id");
    const companyId = companySelect.value;

    // Clear existing options
    courierSelect.innerHTML = "<option value=''>Select a Courier</option>";

    // If no company is selected, return
    if (companyId === "") {
      return;
    }

    function fetchCouriers() {
      var companySelect = document.getElementById("fk_delivery_company_id");
      var courierSelect = document.getElementById("courier_id");
      var companyId = companySelect.value;

      // Clear existing options
      courierSelect.innerHTML = "<option value=''>Select a Courier</option>";

      // If no company is selected, return
      if (companyId === "") {
        return;
      }

      // Use fetch for more modern AJAX
      fetch(`get_couriers.php?delivery_company_id=${companyId}`)
        .then((response) => {
          if (!response.ok) {
            throw new Error(
              `Network response was not ok: ${response.statusText}`
            );
          }
          return response.json();
        })
        .then((couriers) => {
          couriers.forEach((courier) => {
            const option = document.createElement("option");
            option.value = courier.courier_id;
            option.textContent = `${courier.first_name} ${courier.last_name} (Rating: ${courier.rating})`;
            courierSelect.appendChild(option);
          });
        })
        .catch((error) => {
          console.error("Error fetching couriers:", error);
          alert("Failed to load couriers. Please try again.");
        });
    }

    // Attach event listener to delivery company select
    document
      .getElementById("fk_delivery_company_id")
      .addEventListener("change", fetchCouriers);
  }

  // Handle form submission and store pickup details in localStorage
  const scheduleForm = document.getElementById("schedule_form");
  if (scheduleForm) {
    scheduleForm.addEventListener("submit", (event) => {
      event.preventDefault();

      // Validate form fields
      const pickupDate = document.getElementById("pickup_date").value;
      const pickupTime = document.getElementById("pickup_time").value;
      const pickupAddress = document.getElementById("pickup_address").value;
    });
  }
});
