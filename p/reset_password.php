<?php
session_start();
include 'connection.php';

// Function to verify the token
function verifyToken($token) {
    list($encodedEmail, $timestamp) = explode('|', base64_decode($token));
    $email = base64_decode($encodedEmail);

    // Check if the token is within the 2-minute window
    if (time() - (int)$timestamp > 120) {
        return false; // Token expired
    }

    return $email; // Valid token
}

// Ensure the token is provided
if (!isset($_GET['token'])) {
    $errorType = 'invalidRequest'; // Set error type for invalid request
    $errorMessage = "Invalid request. Please ensure you have a valid token.";
} else {
    $token = $_GET['token'];
    $email = verifyToken($token);

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorType = 'expiredToken'; // Set error type for expired token
        $errorMessage = "Invalid or expired token. Please request a new link.";
    } else {
        // Function to update the password
        function updatePassword($email, $newPassword) {
            global $conn;

            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("UPDATE addstaff SET staffPassword = ? WHERE staffEmail = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);

            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }
        }

        // Handle the form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newPassword = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($newPassword !== $confirmPassword) {
                $message = "Passwords do not match.";
                $messageClass = "error-message";
            } elseif (strlen($newPassword) < 6) {
                $message = "Password must be at least 6 characters long.";
                $messageClass = "error-message";
            } else {
                if (updatePassword($email, $newPassword)) {
                    $message = "Password updated successfully.";
                    $messageClass = "success-message";
                    $redirect = "L.php"; // Redirect to a login page or another page
                } else {
                    $message = "Failed to update password. Please try again.";
                    $messageClass = "error-message";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cebu City Branch | Reset Password</title>
    <link href="../src/output.css" rel="stylesheet">
    <link href="../Assets/Yokoks_logo.png" rel="icon">
    <link href="../CSS/reset_password.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
    /* General styles for error overlays */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        animation: fadeIn 0.5s;
    }

    /* Content of the overlay */
    .overlay-content {
        background: #fff;
        padding: 20px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        text-align: center;
        width: 100%;
        height: 100%;
        animation: slideIn 0.5s;
    }

    /* Success icon styling */
    .overlay-content .icon {
        width: 80px;
        height: 80px;
        margin-bottom: 15px;
    }

    /* Heading styles for the overlay */
    .overlay-content h1 {
        font-size: 1.5rem;
        margin-bottom: 15px;
        font-weight: bold;
    }

    /* Error styles for the overlay */
    .invalid-request-content {
        color: #d9534f;
        /* Bootstrap Danger color */
    }

    .expired-token-content {
        color: #f0ad4e;
        /* Bootstrap Warning color */
    }

    /* Animation for fading in */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Animation for sliding in */
    @keyframes slideIn {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Button styling */
    .btn-primary {
        background-color: green;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        margin-top: 15px;
    }

    .btn-primary:hover {
        background-color: lightgreen;
        color: black;
    }

    #spinnerOverlay2 {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: black;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        display: none;
    }

    #spinnerOverlay2 img {
        width: 150px;
    }
    </style>
</head>

<body>
    <div id="spinnerOverlay2">
        <div class="spinner-content2">
            <div class="flex" style="margin-bottom:-35px;margin-top:10px;">
                <h1 class="text-green-500 font-bold " style="margin-top:6px;color:green;font-weight:bold">
                    Redirecting......
                </h1>
            </div>
            <div>
                <img src="../Assets/loading_L.gif" alt="Loading" class="loading-gif">
            </div>
        </div>
    </div>

    <?php if (isset($errorType)): ?>
    <div class="overlay">
        <div class="overlay-content <?php echo htmlspecialchars($errorType); ?>-content">
            <div class="flex text-center justify-center align-center">
                <img src="../Assets/404_ERROR.gif" style="width:20%;height:20%">
            </div>
            <h1 style="color:red;font-weight:bold;letter-spacing:.5px;">
                <?php echo ($errorType === 'invalidRequest') ? 'Invalid Request' : 'Token Expired'; ?>
            </h1>
            <p style="color:red;font-weight:bold;letter-spacing:.5px;"><?php echo htmlspecialchars($errorMessage); ?>
            </p>
            <button onclick="ForgotRedirect()" class="btn-primary">Proceed</button>
        </div>
    </div>
    <script>
    document.querySelector('.overlay').style.display = 'flex';

    function ForgotRedirect() {
        document.querySelector('.overlay').style.display = 'none';
        setTimeout(function() {
            document.getElementById('spinnerOverlay2').style.display = 'flex';
            setTimeout(function() {
                window.location.href = "sF.php";
            }, 6000);
        }, 10);
    }
    </script>
    <?php exit; ?>
    <?php endif; ?>

    <section class="container" style="font-family: 'Plus Jakarta Sans', sans-serif !important;">
        <div class="card">
            <div class="text-center">
                <h1 class="text-lg font-bold text-gray-800">Reset Password</h1>
                <p class="text-gray-800 mb-8">Enter a new password below.</p>
            </div>

            <?php if (isset($message)): ?>
            <p class="<?php echo isset($messageClass) ? htmlspecialchars($messageClass) : ''; ?>" id="message">
                <?php echo htmlspecialchars($message); ?>
            </p>
            <?php if (isset($redirect)): ?>
            <div id="spinnerOverlay">
                <div class="spinner-content">
                    <div class="flex" style="margin-bottom:-35px;margin-top:10px;">
                        <img src="../Assets/success_message.png" alt="Success" class="success-message"
                            style="width:30px;height:30px;margin-top:2px;">
                        <h1 class="text-green-500 font-bold " style="margin-top:6px;color:green;font-weight:bold">
                            Password Updated Successfully
                        </h1>
                    </div>
                    <div>
                        <img src="../Assets/loading_L.gif" alt="Loading" class="loading-gif">
                    </div>
                </div>
            </div>
            <script>
            setTimeout(function() {
                document.getElementById('spinnerOverlay').style.display = 'flex';
                setTimeout(function() {
                    window.location.href = "<?php echo htmlspecialchars($redirect); ?>";
                }, 4000);
            }, 10);
            </script>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
</body>

</html>