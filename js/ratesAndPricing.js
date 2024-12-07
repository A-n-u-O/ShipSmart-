document.addEventListener("DOMContentLoaded", () => {
    const destinations = {
        domestic: [
            'Nigeria',
            'Ghana',
            'Kenya',
            'South Africa',
            'Uganda',
            'Tanzania',
            'Ethiopia',
            'Senegal'
        ],
        international: [
            'Canada',
            'Mexico',
            'Europe',
            'Asia',
            'Australia',
            'South America'
        ]
    };

    const baseRates = {
        domestic: {
            usps: {
                base: 5.00,
                perKg: 0.50 // Changed to perKg
            },
            fedex: {
                base: 7.50,
                perKg: 0.75 // Changed to perKg
            },
            ups: {
                base: 8.00,
                perKg: 1.00 // Changed to perKg
            }
        },
        international: {
            usps: {
                base: 25.00,
                perKg: 3.00 // Changed to perKg
            },
            fedex: {
                base: 40.00,
                perKg: 4.50 // Changed to perKg
            },
            ups: {
                base: 35.00,
                perKg: 4.00 // Changed to perKg
            }
        }
    };

    const discounts = {
        weight: { // Changed from volume to weight
            '1-5': 0,
            '6-10': 0.10,
            '11-20': 0.15,
            '21+': 0.20
        }
    };

    function populateDestinations() {
        const type = document.getElementById('shippingType').value;
        const destSelect = document.getElementById('destination');
        destSelect.innerHTML = '<option value="">Select Destination</option>';
        destinations[type].forEach(dest => {
            const option = document.createElement('option');
            option.value = dest;
            option.textContent = dest;
            destSelect.appendChild(option);
        });
    }

    function calculateShipping() {
        const type = document.getElementById('shippingType').value;
        const weight = parseFloat(document.getElementById('weight').value);
        const courier = document.getElementById('courier').value;
        const destination = document.getElementById('destination').value;

        if (!weight || !courier || !destination) {
            alert('Please fill all fields');
            return;
        }

        const rates = baseRates[type][courier];
        const basePrice = rates.base + (weight * rates.perKg); // Changed to perKg

        let finalPrice = basePrice;
        let appliedDiscounts = [];

        // Weight discount
        Object.entries(discounts.weight).forEach(([range, discount]) => {
            if (range === '21+' && weight >= 21 ||
                range !== '21+' && weight >= parseInt(range.split('-')[0]) && weight <= parseInt(range.split('-')[1])) {
                finalPrice *= (1 - discount);
                appliedDiscounts.push(`Weight Discount (${range} kg): ${discount * 100}%`);
            }
        });

        const resultsDiv = document.getElementById('results');
        resultsDiv.innerHTML = `
                    <h2>Shipping Calculation</h2>
                    <table>
                        <tr><th>Detail</th><th>Value</th></tr>
                        <tr><td>Shipping Type</td><td>${type.charAt(0).toUpperCase() + type.slice(1)}</td></tr>
                        <tr><td>Courier</td><td>${courier.toUpperCase()}</td></tr>
                        <tr><td>Destination</td><td>${destination}</td></tr>
                        <tr><td>Weight</td><td>${weight} kg</td></tr>
                        <tr><td>Base Price</td><td>$${basePrice.toFixed(2)}</td></tr>
                        <tr><td>Discounts</td><td>${appliedDiscounts.join('<br>')}</td></tr>
                        <tr><td>Final Price</td><td>$${finalPrice.toFixed(2)}</td></tr>
                    </table>
                `;
    }

    document.getElementById('shippingType').addEventListener('change', populateDestinations);
    populateDestinations();
});