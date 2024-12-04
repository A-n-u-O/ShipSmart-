<?php
session_start();
include '../config/database.php';
include '../includes/functions.php'; // Include the redirect function

// Handle POST request for signup
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            redirect('signup.php?error=Email already registered.');
        }

        // Insert user into database
        $stmt = $pdo->prepare("INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Redirect to login page on successful registration
        redirect('login.php?success=Registration successful. Please log in.');
    } catch (PDOException $e) {
        redirect('signup.php?error=Failed to register. Try again later.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | ShipSmart</title>
    <style>
        /* Full Page Styling */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to bottom right, #6db1ff, #ffffff);
        }

        /* Form Container */
        .form_main {
            width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: rgb(255, 255, 255);
            padding: 40px;
            border-radius: 30px;
            box-shadow: 0px 10px 40px rgba(0, 0, 0, 0.1);
        }

        .heading {
           font-size: 2.5em;
            color: #333;
            font-weight: bold;
            margin: 15px 0 30px 0;
            display: flex;
            align-items: center; /* Align logo and text */
            flex-direction: column;
        }

        /* styling for logo  */
        .logo {
            width: 100px;
            height: auto;
            margin-right: 10px;
            /* Space between logo and text */
            vertical-align: middle;
        }

        .inputContainer {
            width: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .inputIcon {
            position: absolute;
            left: 10px;
            pointer-events: none;
            /* Prevents the SVG from blocking input */
            width: 20px;
            height: 20px
        }

        .inputField {
            width: 100%;
            height: 50px;
            background-color: transparent;
            border: none;
            border-bottom: 2px solid #ccc;
            color: #333;
            font-size: 1em;
            padding-left: 40px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            z-index: 1;
            /* Ensure inputs are clickable */
        }

        .inputField:focus {
            outline: none;
            border-bottom: 2px solid #6db1ff;
        }

        .inputField::placeholder {
            color: #999;
            font-size: 1em;
        }

        #button {
            width: 100%;
            border: none;
            background-color: #0056b3;
            color: white;
            height: 50px;
            border-radius: 25px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #button:hover {
            background-color: #003d82;
        }

        .signupContainer {
            margin-top: 20px;
            text-align: center;
        }

        .signupContainer p {
            font-size: 1em;
            font-weight: 500;
            color: #333;
        }

        .signupContainer a {
            font-size: 1em;
            font-weight: bold;
            color: #0056b3;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .signupContainer a:hover {
            color: #003d82;
        }
    </style>
</head>

<body>
    <form class="form_main" method="POST" action="signup.php">
        <p class="heading">
            <img src="../Assets/images/shipsmartLogoDark.png" alt="Logo" class="logo">
            Sign Up
        </p>

        <!-- Display error or success messages -->
        <?php if (isset($_GET['error'])) { ?>
            <div style="color: red; text-align: center; margin-bottom: 20px;">
                <?= htmlspecialchars($_GET['error']); ?>
            </div>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
            <div style="color: green; text-align: center; margin-bottom: 20px;">
                <?= htmlspecialchars($_GET['success']); ?>
            </div>
        <?php } ?>

        <!-- Username Field -->
        <div class="inputContainer">
            <svg class="inputIcon" width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.1992 12C14.9606 12 17.1992 9.76142 17.1992 7C17.1992 4.23858 14.9606 2 12.1992 2C9.43779 2 7.19922 4.23858 7.19922 7C7.19922 9.76142 9.43779 12 12.1992 12Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M3 22C3.57038 20.0332 4.74796 18.2971 6.3644 17.0399C7.98083 15.7827 9.95335 15.0687 12 15C16.12 15 19.63 17.91 21 22" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input placeholder="Username" id="username" name="username" class="inputField" type="text" required>
        </div>

        <!-- Email Field -->
        <div class="inputContainer">
            <svg class="inputIcon" width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 12C16 14.2091 14.2091 16 12 16C9.79086 16 8 14.2091 8 12C8 9.79086 9.79086 8 12 8C14.2091 8 16 9.79086 16 12ZM16 12V13.5C16 14.8807 17.1193 16 18.5 16V16C19.8807 16 21 14.8807 21 13.5V12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21H16" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input placeholder="Email" id="email" name="email" class="inputField" type="email" required>
        </div>

        <!-- Password Field -->
        <div class="inputContainer">
            <svg class="inputIcon" width="800px" height="800px" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <g id="Layer_2" data-name="Layer 2">
                    <g id="invisible_box" data-name="invisible box">
                        <rect width="48" height="48" fill="none" />
                    </g>
                    <g id="Layer_7" data-name="Layer 7">
                        <g>
                            <path d="M39,18H35V13A11,11,0,0,0,24,2H22A11,11,0,0,0,11,13v5H7a2,2,0,0,0-2,2V44a2,2,0,0,0,2,2H39a2,2,0,0,0,2-2V20A2,2,0,0,0,39,18ZM15,13a7,7,0,0,1,7-7h2a7,7,0,0,1,7,7v5H15ZM37,42H9V22H37Z" />
                            <circle cx="15" cy="32" r="3" />
                            <circle cx="23" cy="32" r="3" />
                            <circle cx="31" cy="32" r="3" />
                        </g>
                    </g>
                </g>
            </svg>
            <input placeholder="Password" id="password" name="password" class="inputField" type="password" required>
        </div>

        <button id="button" type="submit">Register</button>
        <div class="signupContainer">
            <p>Already have an account?</p>
            <a href="login.php">Login</a>
        </div>
    </form>
</body>

</html>