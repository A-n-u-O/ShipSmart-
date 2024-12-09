document.getElementById('paymentForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Reset previous error messages
    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    let isValid = true;

    // Cardholder Name Validation
    const cardName = document.getElementById('cardName');
    if (cardName.value.trim().split(' ').length < 2) {
        document.getElementById('cardNameError').textContent = 'Please enter full name';
        isValid = false;
    }

    // Card Number Validation
    const cardNumber = document.getElementById('cardNumber');
    const cardNumberRegex = /^(\d{4}\s?){3}\d{4}$/;
    if (!cardNumberRegex.test(cardNumber.value.replace(/\s/g, ''))) {
        document.getElementById('cardNumberError').textContent = 'Invalid card number';
        isValid = false;
    }

    // Expiry Date Validation
    const expiryDate = document.getElementById('expiryDate');
    const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
    if (!expiryRegex.test(expiryDate.value)) {
        document.getElementById('expiryError').textContent = 'Invalid expiry (MM/YY)';
        isValid = false;
    }

    // CVV Validation
    const cvv = document.getElementById('cvv');
    if (!/^\d{3}$/.test(cvv.value)) {
        document.getElementById('cvvError').textContent = 'Invalid CVV';
        isValid = false;
    }

    // Amount Validation
    const amount = document.getElementById('amount');
    if (amount.value <= 0) {
        document.getElementById('amountError').textContent = 'Invalid amount';
        isValid = false;
    }

    if (isValid) {
        alert('Payment Processing...');

        // Send AJAX request to update booking status
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../includes/updateBookingStatus.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');

        // Example payload (replace with actual booking details)
        const bookingDetails = {
            bookingId: 12345, // Replace with dynamic booking ID
            status: 'In Progress'
        };

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert('Payment successful. Booking status updated to "In Progress".');
                } else {
                    alert('Error updating booking status: ' + response.message);
                }
            } else {
                alert('Server error. Please try again.');
            }
        };

        xhr.send(JSON.stringify(bookingDetails));
    }
});

// Auto-format card number
document.getElementById('cardNumber').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\s/g, '').replace(/(\d{4})/g, '$1 ').trim();
    e.target.value = value;
});

// Auto-format expiry date
document.getElementById('expiryDate').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }
    e.target.value = value;
});