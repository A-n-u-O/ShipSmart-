<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShipSmart | Rates and Pricing</title>
  <link rel="stylesheet" href="../css/navbar.css">
  <link rel="stylesheet" href="../css/ratesAndPricing.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }

    input,
    select {
      width: 100%;
      padding: 5px;
      margin: 5px 0;
    }

    #results {
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <?php include '../Views/navbar.php'; ?> <!-- Include the reusable navbar -->

  <h1>Shipping Rates Calculator</h1>

  <div>
    <label>Shipping Type:
      <select id="shippingType">
        <option value="domestic">Domestic</option>
        <option value="international">International</option>
      </select>
    </label>
  </div>

  <div>
    <label>Package Weight (lbs):
      <input type="number" id="weight" min="0" step="0.1">
    </label>
  </div>

  <div>
    <label>Destination:
      <select id="destination">
        <option value="">Select Destination</option>
      </select>
    </label>
  </div>

  <div>
    <label>Courier:
      <select id="courier">
        <option value="usps">USPS</option>
        <option value="fedex">FedEx</option>
        <option value="ups">UPS</option>
      </select>
    </label>
  </div>

  <button onclick="calculateShipping()">Calculate Shipping</button>

  <div id="results"></div>

  <script>
    const destinations = {
      domestic: [
        'Continental US',
        'Alaska',
        'Hawaii',
        'Puerto Rico'
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
          perLb: 0.50
        },
        fedex: {
          base: 7.50,
          perLb: 0.75
        },
        ups: {
          base: 8.00,
          perLb: 1.00
        }
      },
      international: {
        usps: {
          base: 25.00,
          perLb: 3.00
        },
        fedex: {
          base: 40.00,
          perLb: 4.50
        },
        ups: {
          base: 35.00,
          perLb: 4.00
        }
      }
    };

    const discounts = {
      volume: {
        '1-5': 0,
        '6-10': 0.10,
        '11-20': 0.15,
        '21+': 0.20
      },
      frequentShipper: 0.05
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
      const basePrice = rates.base + (weight * rates.perLb);

      let finalPrice = basePrice;
      let appliedDiscounts = [];

      // Volume discount
      Object.entries(discounts.volume).forEach(([range, discount]) => {
        if (range === '21+' && weight >= 21 ||
          range !== '21+' && weight >= parseInt(range.split('-')[0]) && weight <= parseInt(range.split('-')[1])) {
          finalPrice *= (1 - discount);
          appliedDiscounts.push(`Volume Discount (${range} lbs): ${discount * 100}%`);
        }
      });

      // Frequent shipper discount (hypothetical)
      finalPrice *= (1 - discounts.frequentShipper);
      appliedDiscounts.push(`Frequent Shipper Discount: ${discounts.frequentShipper * 100}%`);

      const resultsDiv = document.getElementById('results');
      resultsDiv.innerHTML = `
                <h2>Shipping Calculation</h2>
                <table>
                    <tr><th>Detail</th><th>Value</th></tr>
                    <tr><td>Shipping Type</td><td>${type.charAt(0).toUpperCase() + type.slice(1)}</td></tr>
                    <tr><td>Courier</td><td>${courier.toUpperCase()}</td></tr>
                    <tr><td>Destination</td><td>${destination}</td></tr>
                    <tr><td>Weight</td><td>${weight} lbs</td></tr>
                    <tr><td>Base Price</td><td>$${basePrice.toFixed(2)}</td></tr>
                    <tr><td>Discounts</td><td>${appliedDiscounts.join('<br>')}</td></tr>
                    <tr><td>Final Price</td><td>$${finalPrice.toFixed(2)}</td></tr>
                </table>
            `;
    }

    document.getElementById('shippingType').addEventListener('change', populateDestinations);
    populateDestinations();
  </script>
</body>

</html>