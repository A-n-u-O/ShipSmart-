<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
    <link rel="stylesheet" href="../css/logoutPage.css">
</head>

<body>
    <div class="form-container">
        <div class="form">
            <div class="heading">Confirm Logout</div>
            <div class="c1">Are you sure you want to log out? You will need to log in again next time.</div>

            <form action="../logout.php" method="POST">
                <div class="button-container">
                    <!-- No Button (left side) -->
                    <div class="reset-button-container">
                        <div class="reset-button">
                            <a href="dashboard.php">
                                <img src="../Assets/icons/back-arrow-left.svg" alt="Back to dashboard">
                                No, go back</a>
                        </div>
                    </div>

                    <!-- Yes Button (right side) -->
                    <div class="send-button">
                        <button type="submit" name="confirm_logout" value="1">
                            <img src="../Assets/icons/logout.svg" alt="Logout"> Yes, I'm sure
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
