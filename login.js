document.addEventListener("DOMContentLoaded", () => {
    
    const loginBox = document.querySelector(".loginbox");
    if (loginBox) {
        loginBox.style.opacity = "0";
        loginBox.style.transition = "opacity 5s ease";
        setTimeout(() => {
            loginBox.style.opacity = "1";
        }, 900);
    }

    
    const overlay = document.querySelector(".overlay");
    if (overlay) {
        setTimeout(() => {
            overlay.style.transition = "opacity 2s ease";
            overlay.style.opacity = "0";
            overlay.style.pointerEvents = "none";
        }, 1000);
    }

        
    const loginButton = document.querySelector(".loginbtn");
    if (loginButton) {
        loginButton.addEventListener("mouseover", () => {
            loginButton.style.color = "#3498db";
        });

        loginButton.addEventListener("mouseout", () => {
            loginButton.style.color = "white";
        });
    }

    
});
