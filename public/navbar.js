// JavaScript to toggle the dropdown visibility

// Get all the parent list items with dropdown menus
const dropdowns = document.querySelectorAll("nav ul li");

dropdowns.forEach(dropdown => {
  const toggleIcon = dropdown.querySelector(".toggle-icon");
  const dropDownMenu = dropdown.querySelector(".drop-down");

  // Check if both toggleIcon and dropDownMenu exist
  if (toggleIcon && dropDownMenu) {
    toggleIcon.addEventListener("click", function(e) {
      e.preventDefault(); // Prevent the link's default behavior

      // Toggle the dropdown visibility by adding/removing the 'show' class
      dropDownMenu.classList.toggle("show");

      // Toggle the icon between '▲' and '▼'
      toggleIcon.textContent = dropDownMenu.classList.contains("show") ? "▲" : "▼";
    });
  }
});
