<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cebu City Branch | Yokok's Staff Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="../Assets/Yokoks_logo.png">
    <link rel="stylesheet" href="../CSS/loginForm.css">
</head>

<!--============Modal for no Internet==============-->
<div id="NoInternetModal" style="display: none;">
    <div class="modal-content-no">
        <div class="close-no float-right text-right flex justify-right cursor-pointer" onclick="closeNoInternet()">
            <img src="../Assets/close-no.png" style="width:30px;height:30px;">
        </div>
        <div class="ImageNoInternet text-center flex justify-center">
            <img src="../Assets/lost.jpg" alt="No Internet">
        </div>
        <div class="flex text-center flex justify-center">
            <h1 class="font-bold text-center mt-5 font-xl" style="font-size:20px;color:red">
                Connect To the Internet....
            </h1>
            <img src="../Assets/no_internet.gif" alt="No Internet" style="margin-top:-20px;margin-left:-25px;">
        </div>
    </div>
</div>
<!--============Modal for no Internet==============-->

<body class="flex h-screen">
    <!-- Left Side: Login Form -->
    <div class="w-1/2 flex items-center justify-center bg-gray-100 p-8">
        <div class="block">
            <div class="flex ml-4">
                <img src="../Assets/location.png"
                    style="width:40px;height:40px;position:relative;margin-right:25px;left:17px;">
                <h1 class="text-center mb-10 title">Cebu City Branch</h1>
            </div>

            <div class="w-full max-w-full bg-white p-8 rounded-lg shadow-lg">
                <div class="text-center mb-6">
                    <div class="bg-red-600 text-white p-4 rounded-lg shadow-lg flex items-center justify-center mb-4">
                        <i class="fas fa-exclamation-triangle text-2xl mr-2"></i>
                        <span class="text-lg font-bold">Authorized Personnel Only</span>
                    </div>
                </div>
                <form id="loginForm" method="post">
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 font-medium mb-1">Username</label>
                        <div id="usernameError" class="text-red-500 mb-1"></div> <!-- Error message placeholder -->
                        <input type="text" id="username" name="staffUsername"
                            class="w-full border border-gray-300 p-2 rounded-lg" required>
                    </div>
                    <div class="mb-4" style="margin-bottom:8px;">
                        <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                        <div id="passwordError" class="text-red-500 mb-1"></div> <!-- Error message placeholder -->
                        <div class="flex justify-between items-center">
                            <input type="password" id="password" name="staffPassword"
                                class="w-full border border-gray-300 p-2 rounded-lg" required>
                        </div>
                    </div>
                    <a href="sF.php" class="text-blue-500 hover:underline">Forgot your password?</a>
                    <button type="submit" id="submitButton"
                        class="w-full bg-green-500 text-white mt-5 py-2 rounded-lg flex items-center justify-center hover:bg-green-600">
                        <span id="buttonText">Sign In</span>
                        <div id="spinnerContainer" class="hidden ml-2">
                            <img src="../Assets/loading.gif" style="width:15px;height:15px;border-radius:50%;">
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Side: Image -->
    <div class="w-1/2 flex items-center justify-center bg-white relative">
        <img src="../Assets/login_bg.jpg" alt="Login Image" class="object-cover w-full h-full">
        <div class="image-overlay"></div>
        <!-- Text Overlay -->
        <div class="text-overlay" style="width:90% !important;">
            <img src="../Assets/Yokoks_logo.png" alt="Yokok's Logo" class="mx-auto mb-4">
            <h1>Your Personalized Fitness Gym</h1>
            <p>Your journey to a healthier life starts here. Join us for personalized workouts, expert guidance, and a
                supportive community.</p>
        </div>
    </div>

    <div id="spinnerOverlay" style="display: none;">
        <div class="block">
            <div class="text-center bg-white shadow-md flex align-center" class="redirect-message"
                style="width:350px;height:56px;align-content:center;border-radius:10px;">
                <div class="mt-2 ml-2 flex">
                    <img src="../Assets/success_message.png" class="ml-3 mt-.5" alt="Success Message"
                        style="width:30px;height:30px;margin-right:20px;">
                    <h1 class="text-black font-bold mt-1 ">You are Authorized Redirecting....</h1>
                </div>
            </div>
            <img class="text-center" style="position:relative;left:100px;" src="../Assets/loading_L.gif" alt="Loading">
        </div>
    </div>

    <script src="../JS/login.js"></script>
</body>

</html>