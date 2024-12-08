// Get the modal
var modal = document.getElementById("courierModal");
var closeBtn = document.getElementsByClassName("close")[0];

// When the user clicks on a "View Details" button
document.querySelectorAll(".view-details-btn").forEach(function (button) {
  button.addEventListener("click", function () {
    var courierCard = button.closest(".courier-card");
    var courierData = JSON.parse(courierCard.getAttribute("data-courier-info"));
    courierData.photo_url || "default_photo.jpg";
    courierData.phone_number || "Not available";

    // Populate the modal with courier details
    var details = `
            <img src="${courierData.photo_url}" alt="Courier Photo" style="width: 100px; height: auto;">
            <p><strong>Name:</strong> ${courierData.first_name} ${courierData.last_name}</p>
            <p><strong>Phone:</strong> ${courierData.phone_number}</p>
            <p><strong>Available Time:</strong> ${courierData.available_time}</p>
            <p><strong>Rating:</strong> ${courierData.rating} ★</p>
        `;
    document.getElementById("courierDetails").innerHTML = details;

    // Show the modal
    modal.style.display = "block";
  });
});

// When the user clicks on the close button
closeBtn.onclick = function () {
  modal.style.display = "none";
};

// When the user clicks anywhere outside the modal, close it
window.onclick = function (event) {
  if (event.target === modal) {
    modal.style.display = "none";
  }
};
