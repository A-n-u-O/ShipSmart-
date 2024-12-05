// JavaScript to toggle the dropdown visibility

// Get all the parent list items with dropdown menus
const dropdowns = document.querySelectorAll("nav ul li");

dropdowns.forEach((dropdown) => {
  const toggleIcon = dropdown.querySelector(".toggle-icon");
  const dropDownMenu = dropdown.querySelector(".drop-down");

  // Check if both toggleIcon and dropDownMenu exist
  if (toggleIcon && dropDownMenu) {
    toggleIcon.addEventListener("click", function (e) {
      e.preventDefault(); // Prevent default behavior

      // Close any open dropdowns except the current one
      document.querySelectorAll(".drop-down.show").forEach((menu) => {
        if (menu !== dropDownMenu) {
          menu.classList.remove("show");
          menu.parentNode.querySelector(".toggle-icon").textContent = "▼";
        }
      });

      // Toggle the current dropdown visibility
      dropDownMenu.classList.toggle("show");

      // Toggle the icon between '▲' and '▼'
      toggleIcon.textContent = dropDownMenu.classList.contains("show") ? "▲" : "▼";
    });
  }
});

// Close dropdowns if clicked outside
document.addEventListener("click", (e) => {
  dropdowns.forEach((dropdown) => {
    const dropDownMenu = dropdown.querySelector(".drop-down");
    const toggleIcon = dropdown.querySelector(".toggle-icon");

    if (
      dropDownMenu &&
      !dropdown.contains(e.target) && // Click outside the dropdown
      dropDownMenu.classList.contains("show")
    ) {
      dropDownMenu.classList.remove("show");
      toggleIcon.textContent = "▼";
    }
  });
});
