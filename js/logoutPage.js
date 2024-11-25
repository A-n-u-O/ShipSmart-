function confirmBack() {
    const userConfirmed = confirm("Are you sure you want to go back? Any unsaved changes will be lost.");
    if (userConfirmed) {
        window.history.back(); // Go back to the previous page
    }
}