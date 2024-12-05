document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.querySelector(".overlay");
    setTimeout(() => {
        overlay.style.transition = "opacity 2s ease";
        overlay.style.opacity = "0";
        overlay.style.pointerEvents = "none";
    }, 1000);

    const inputs = document.querySelectorAll(".signupform input");
    inputs.forEach(input => {
        input.addEventListener("focus", () => {
            input.style.borderBottom = "2px solid #3498db";
        });
        input.addEventListener("blur", () => {
            input.style.borderBottom = "2px solid white";
        });
    });

    const button = document.querySelector(".signupbtn");
    button.addEventListener("mouseover", () => {
        button.style.color = "#3498db";
    });
    button.addEventListener("mouseout", () => {
        button.style.color = "white";
    });
});
