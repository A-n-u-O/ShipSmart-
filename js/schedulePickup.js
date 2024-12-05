// Calendar and Scheduling Logic
const Calendar = (() => {
  let currentDate = new Date();
  let currentMonth = currentDate.getMonth();
  let currentYear = currentDate.getFullYear();
  let selectedDate = null;
  let selectedTime = null;
  let pickupAddress = "";

  const monthNames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  const generateCalendar = (month, year) => {
    const days = [];
    const firstDay = new Date(year, month, 1).getDay();
    const totalDays = new Date(year, month + 1, 0).getDate();
    const prevMonthDays = new Date(year, month, 0).getDate();

    const leadingDays = (firstDay + 6) % 7; // Adjust for Monday start
    for (let i = leadingDays; i > 0; i--) {
      days.push({ date: prevMonthDays - i + 1, currentMonth: false });
    }

    for (let i = 1; i <= totalDays; i++) {
      days.push({ date: i, currentMonth: true });
    }

    const trailingDays = 7 - (days.length % 7);
    for (let i = 1; i <= trailingDays && trailingDays < 7; i++) {
      days.push({ date: i, currentMonth: false });
    }

    return days;
  };

  const renderCalendar = (days) => {
    const calendarBody = document.querySelector(".calendar tbody");
    calendarBody.innerHTML = "";

    let row = document.createElement("tr");
    days.forEach((day, index) => {
      const cell = document.createElement("td");
      cell.textContent = day.date;
      cell.className = day.currentMonth ? "current-month" : "other-month";

      if (
        selectedDate &&
        selectedDate.date === day.date &&
        selectedDate.month === currentMonth &&
        selectedDate.year === currentYear
      ) {
        cell.classList.add("selected");
      }

      if (day.currentMonth) {
        cell.addEventListener("click", () => handleDateSelection(day));
      }

      row.appendChild(cell);
      if ((index + 1) % 7 === 0) {
        calendarBody.appendChild(row);
        row = document.createElement("tr");
      }
    });

    if (row.children.length > 0) {
      calendarBody.appendChild(row);
    }
  };

  const updateCalendar = (offset = 0) => {
    currentMonth += offset;
    if (currentMonth < 0) {
      currentMonth = 11;
      currentYear -= 1;
    } else if (currentMonth > 11) {
      currentMonth = 0;
      currentYear += 1;
    }

    const days = generateCalendar(currentMonth, currentYear);
    renderCalendar(days);

    document.querySelector(
      ".calendar-header span"
    ).textContent = `${monthNames[currentMonth]} ${currentYear}`;
  };

  const handleDateSelection = (day) => {
    selectedDate = {
      date: day.date,
      month: currentMonth,
      year: currentYear,
    };

    document
      .querySelectorAll(".calendar td")
      .forEach((td) => td.classList.remove("selected"));

    const days = generateCalendar(currentMonth, currentYear);
    renderCalendar(days);

    updatePreferencesDisplay(); // Update preferences display after date selection
  };

  const updatePreferencesDisplay = () => {
    const dateDisplay = document.getElementById("selected-date");
    const timeDisplay = document.getElementById("selected-time");
    const optionsDisplay = document.getElementById("selected-options");

    dateDisplay.textContent = selectedDate
      ? `Date: ${monthNames[selectedDate.month]} ${selectedDate.date}, ${
          selectedDate.year
        }`
      : "Date: Not selected";
    timeDisplay.textContent = selectedTime
      ? `Time: ${selectedTime}`
      : "Time: Not selected";
    optionsDisplay.textContent = pickupAddress
      ? `Pickup Address: ${pickupAddress}`
      : "Pickup Address: Not selected";
  };

  const showAlert = (message, isError = false) => {
    const alertDiv = document.querySelector(".alert-message");
    alertDiv.style.display = "block";
    alertDiv.style.backgroundColor = isError ? "#f8d7da" : "#d4edda";
    alertDiv.style.color = isError ? "#842029" : "#155724";
    alertDiv.textContent = message;

    setTimeout(() => {
      alertDiv.style.display = "none";
    }, 3000);
  };

  const init = () => {
    updateCalendar();

    document.querySelectorAll(".nav-btn").forEach((btn, index) => {
      btn.addEventListener("click", () => updateCalendar(index === 0 ? -1 : 1));
    });

    document.querySelectorAll(".time-btn").forEach((btn) => {
      btn.addEventListener("click", () => {
        document
          .querySelectorAll(".time-btn")
          .forEach((b) => b.classList.remove("active"));
        btn.classList.add("active");
        selectedTime = btn.textContent; // Store selected time
        updatePreferencesDisplay(); // Update preferences display
      });
    });

    const pickupAddressInput = document.getElementById("pickup-address-input");

    pickupAddressInput.addEventListener("keypress", (e) => {
      if (e.key === "Enter") {
        pickupAddress = pickupAddressInput.value;
        updatePreferencesDisplay();
      }
    });

    // Handle the submit button click event
    const submitAddressBtn = document.getElementById("submit-address-btn");
    submitAddressBtn.addEventListener("click", () => {
      pickupAddress = pickupAddressInput.value;
      updatePreferencesDisplay();
    });

    document
      .querySelector(".confirm-pickup-btn")
      .addEventListener("click", () => {
        if (!selectedDate) {
          showAlert("Please select a date!", true);
          return;
        }

        if (!selectedTime) {
          showAlert("Please select a time slot!", true);
          return;
        }

        if (!pickupAddress) {
          showAlert("Please enter a valid pickup address!", true);
          return;
        }

        // Store details in localStorage
        const formattedDate = `${monthNames[selectedDate.month]} ${
          selectedDate.date
        }, ${selectedDate.year}`;
        localStorage.setItem(
          "pickupDetails",
          JSON.stringify({
            pickupDate: formattedDate,
            pickupTime: selectedTime,
            pickupAddress: pickupAddress,
          })
        );

        // Redirect to confirmation page
        window.location.href = "../pages/confirmPickup.php";
      });
  };

  return { init };
})();

// Initialize calendar on page load
document.addEventListener("DOMContentLoaded", Calendar.init);
console.log("Page loaded, initializing calendar...");