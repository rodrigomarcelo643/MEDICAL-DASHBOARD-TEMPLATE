<?php
session_start();

if (!isset($_SESSION['staffUsername'])) {
    header("Location: L.php");
    exit();
}

$staffUsername = htmlspecialchars($_SESSION['staffUsername']);
$firstName = htmlspecialchars($_SESSION['firstName']);
$lastName = htmlspecialchars($_SESSION['lastName']);
$staffEmail = htmlspecialchars($_SESSION['staffEmail']);
$profileImage = isset($_SESSION['profileImage']) ? $_SESSION['profileImage'] : ''; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../src/output.css" rel="stylesheet">
    <link href="../CSS/dashboard.css" rel="stylesheet">
    <link href="../CSS/members.css" rel="stylesheet">
    <link href="../CSS/sendSales.css" rel="stylesheet">
    <link href="../CSS/Products.css" rel="stylesheet">
    <link href="../CSS/notif.css" rel="stylesheet">
    <link href="../CSS/add_member.css" rel="stylesheet">
    <link href="../CSS/expenses.css" rel="stylesheet">
    <link href="../CSS/profile.css" rel="stylesheet">
    <link rel="icon" href="../Assets/Yokoks_logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/dist/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/dist/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.0.2/index.min.css" />
    <title>Cebu City Branch | Staff Dashboard</title>
    <link href="../CSS/showSettings.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Inter" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>
<!--=========================================Show Settings==================-->
<div id="ShowSettings" style="display: none;">
    <div class="modal-content-settings w-96 mx-auto p-6 bg-white rounded-lg shadow-lg relative">
        <span class="close-btn" onclick="HideSettings()">&#10006;</span>
        <div class="flex">
            <img src="../Assets/settings.png" style="width:35px;height:35px;margin-right:10px;">
            <h2 class="text-2xl mb-4 text-green-500 font-bold">Settings</h2>
        </div>
        <div id="settings-section-container">
            <!-- Default View -->
            <div id="default-view">
                <div class="setting-top flex justify-center items-center mb-4">
                    <img src="../Assets/settings_top.png" alt="Settings Top">
                </div>
                <div class="settings-section mb-4" style="margin-top:-40px;">
                    <div class="flex mb-5" style="margin-bottom:12px;">
                        <img src="../Assets/profile.png" style="width:35px;height:35px;margin-right:10px;">
                        <h3 class="text-xl text-green-500 font-bold mb-2" style="letter-spacing:.5px;margin-top:5px;">
                            Profile Details</h3>
                    </div>
                    <ul class="list-none p-0" style="margin-left:15px;">
                        <li class="mb-2 flex">
                            <div class="design"
                                style="width:21px;height:7px;background-color:green;border-radius:8px; margin-top:8px; margin-right:6px;">
                            </div>
                            <a href="#" onclick="showProfileDetails(); return false;"
                                class="text-greeb-600 hover:underline  font-bold edit-profile">Edit
                                Profile</a>
                        </li>
                        <li class="flex">
                            <div class="design"
                                style="width:21px;height:7px;background-color:green;border-radius:8px; margin-top:8px; margin-right:6px;">
                            </div>
                            <a href="#" onclick="showAccountInfo(); return false;"
                                class="text-green-600 hover:underline account-info ">Account Info</a>
                        </li>
                    </ul>
                </div>
                <div class="settings-section">
                    <div class="flex mb-5" style="margin-bottom:12px;">
                        <img src="../Assets/password.png" style="width:35px;height:35px;margin-right:10px;">
                        <h3 class="text-xl text-green-500 font-bold mb-2" style="letter-spacing:.5px;margin-top:5px;">
                            Profile Password</h3>
                    </div>
                    <ul class="list-none p-0 flex" style="margin-left:15px;">
                        <div class="design"
                            style="width:21px;height:7px;background-color:green;border-radius:8px; margin-top:8px; margin-right:6px;">
                        </div>
                        <li><a href="#" onclick="showChangePassword(); return false;"
                                class="text-green-600 hover:underline change-password">Change Password</a></li>
                    </ul>
                </div>
            </div>

            <!-- Profile Details View -->
            <div id="profile-details-view" class="view-content">
                <img src="../Assets/back_arrow.png" class="back-button" onclick="showDefaultView()">
                <div class="flex align-center flex justify-center align-center">
                    <img src="../Assets/edit-profile.png" style="width:35px;height:35px;margin-right:10px;">
                    <h3 class="view-title text-align:center " style="letter-spacing:.5px;">Edit Profile
                    </h3>
                </div>
                <form id="profile-edit-form" enctype="multipart/form-data" class="form-container">
                    <div class="view-info">
                        <div class="wrap-image-container">
                            <div class="image-preview-container">
                                <!-- Display the profile image or default if not set -->
                                <img src="<?php echo !empty($profileImage) ? 'data:image/jpeg;base64,' . htmlspecialchars($profileImage) : '../Assets/profile.png'; ?>"
                                    alt="Profile Image" id="profile-img" class="profile-img">
                                <label for="profileImage" class="custom-file-upload">
                                    <input type="file" name="profileImage" id="profileImage" accept="image/*"
                                        onchange="previewImage(event)">
                                    <span class="upload-icon" style="margin-top:-7px;">+</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="block">
                                <label for="firstName" style="font-weight:bold;margin-bottom:15px;">First Name</label>
                                <input type="text" name="firstName" id="firstName"
                                    value="<?php echo htmlspecialchars($firstName); ?>" class="form-input f-edit">
                            </div>
                            <span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
                            <div class="block">
                                <label for="lastName" style="font-weight:bold;margin-bottom:8px;">Last Name</label>
                                <input type="text" name="lastName" id="lastName"
                                    value="<?php echo htmlspecialchars($lastName); ?>" class="form-input f-edit">
                            </div>
                        </div>
                        <label for="staffEmail" style="font-weight:bold;margin-bottom:15px;">Email</label>
                        <input type="email" name="staffEmail" id="staffEmail"
                            value="<?php echo htmlspecialchars($staffEmail); ?>" class="form-input" readonly>
                        <input type="hidden" name="staffUsername"
                            value="<?php echo htmlspecialchars($staffUsername); ?>">
                    </div>
                    <div id="form-messages" class="form-messages"></div>
                    <button type="submit" class="submit-button">Save Changes</button>
                </form>
            </div>

            <!--============Account informtaion view ===============-->
            <div id="account-info-view" class="view-content" style="display: none;">
                <img src="../Assets/back_arrow.png" class="back-button" onclick="showDefaultView()">
                <div class="flex">
                    <img src="../Assets/account-info.png" style="width:35px;height:35px;margin-right:10px;">
                    <h3 class="view-title">Account Information</h3>
                </div>
                <div class="view-info1">
                    <div class="profile">
                        <img src="<?php echo !empty($profileImage) ? 'data:image/jpeg;base64,' . htmlspecialchars($profileImage) : '../Assets/profile.png'; ?>"
                            alt="Profile Image" id="profile-img" class="profile-img">
                    </div>
                    <div class="info-details">
                        <div class="info-row">
                            <label>Username</label>
                            <p><?php echo $staffUsername; ?></p>
                        </div>
                        <div class="info-row">
                            <label>First Name</label>
                            <p><?php echo $firstName; ?></p>
                        </div>
                        <div class="info-row">
                            <label>Last Name</label>
                            <p><?php echo $lastName; ?></p>
                        </div>
                        <div class="info-row email-row">
                            <img src="../Assets/email.png" style="width:35px;height:35px;margin-right:10px;">
                            <p><?php echo $staffEmail; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Change Password View -->
            <div id="change-password-view" class="view-content" style="display: none;">
                <img src="../Assets/back_arrow.png" class="back-button" onclick="showDefaultView()">
                <div class="flex text-center justify-center">
                    <h3 class="view-title">Change Password</h3>
                    <img src="../Assets/change_password.png" style="width:35px;height:35px;margin-left:10px;">
                </div>
                <div class="username-block" style="border-bottom:2px solid green;margin-bottom:10px;">
                </div>
                <form id="change-password-form" method="po  t" class="password-form">
                    <label for="current-password" style="margin-bottom:-7px;font-weight:bold;font-size:15px;">Current
                        Password</label>
                    <input type="password" id="current-password" name="current-password" class="input-field" required>
                    <label for="new-password" style="font-weight:bold;margin-bottom:-7px;font-size:15px;">New
                        Password</label>
                    <input type="password" id="new-password" name="new-password" class="input-field" required>
                    <div id="change-password-message" class="message"></div>
                    <button type="submit" class="update-button">Update</button>
                </form>
            </div>

        </div>
    </div>
</div>

<!--=========================================Show Settings=================================================-->

<!-- =====Updating Membership Renewal Modal Sucecss-->
<div id="successModalRenew" class="modal">
    <div class="modal-content modal-success">
        <span class="close">&times;</span>
        <div class="modal-header" style="display:flex;">
            <img src="../Assets/success_message.png" alt="Success Icon" class="success-icon">
            <h2 class="success-message">Membership Renewed!</h2>
        </div>
        <div class="loading-bar">
            <div id="progress" class="progress"></div>
        </div>
    </div>
</div>
<!-- =====DELETE MEMBERS  Modal Sucecss-->
<div id="successModalAddedExpenses" class="modal" style="z-index:999;">
    <div class="modal-content modal-success">
        <span class="close">&times;</span>
        <div class="modal-header" style="display:flex;">
            <img src="../Assets/success_message.png" alt="Success Icon" class="success-icon">
            <h2 class="success-message">Expense Added</h2>
        </div>
        <div class="loading-bar">
            <div id="progress" class="progress"></div>
        </div>
    </div>
</div>
<!-- =====DELETE MEMBERS  Modal Sucecss-->
<div id="successModalDeleteMember" class="modal" style="z-index:999;">
    <div class="modal-content modal-success">
        <span class="close">&times;</span>
        <div class="modal-header" style="display:flex;">
            <img src="../Assets/success_message.png" alt="Success Icon" class="success-icon">
            <h2 class="success-message">Member Deleted !</h2>
        </div>
        <div class="loading-bar">
            <div id="progress" class="progress"></div>
        </div>
    </div>
</div>
<!-- =====DELETE MEMBERS  Modal Sucecss-->
<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content modal-success">
        <span class="close">&times;</span>
        <div class="modal-header" style="display:flex;">
            <img src="../Assets/success_message.png" alt="Success Icon" class="success-icon">
            <h2 class="success-message">Product Added!</h2>
        </div>
        <div class="loading-bar">
            <div id="progress" class="progress"></div>
        </div>
    </div>
</div>
<!-- Success Modal Add Member -->
<div id="successModal1" class="modal">
    <div class="modal-content modal-success">
        <span class="close">&times;</span>
        <div class="modal-header" style="display:flex;">
            <img src="../Assets/success_message.png" alt="Success Icon" class="success-icon">
            <h2 class="success-message">Member Added</h2>
        </div>
        <div class="loading-bar">
            <div id="progress" class="progress"></div>
        </div>
    </div>
</div>


<!-- Notification Modal -->
<div id="notification-modal" class=" fixed flex items-center justify-center bg-gray-900 bg-opacity-50 hidden"
    style="overflow:scroll">
    <div class="modal-content bg-white p-6 rounded-lg shadow-lg">
        <div class="close flex justify-end" id="close-notification">
            <img src="../Assets/close.png" class="text-align-right">
        </div>
        <div class=" flex">
            <h1 class="text-lg font-bold" style="margin-right:250px;font-size:20px;width:50%;margin-top:20px;">
                Notifications</h1>
            <button class="button">
                <svg viewBox="0 0 448 512" class="bell">
                    <path
                        d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z">
                    </path>
                </svg>
            </button>
        </div>
        <!--============Today Content Goes here==============-->
        <div class="Today-container flex" id="Today-container">
            <p class="mb-4 flex" style="margin-top:30px;"> Today <span style="margin-top:10px;margin-left:10px;">
                    <img src="../Assets/chevron_green.png"></span>
            </p>
        </div>
        <div class="mb-4 flex"
            style="display: flex; align-items: center; width: 100%; overflow: hidden; height: auto; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);">
            <img src="../Assets/success_message.png" style="padding: 5px; width: 60px; height: 60px; flex-shrink: 0;">
            <h1 style="padding: 15px; margin: 0; flex: 1;">
                Daily Sales Successfully Sent to Mrs. Merlin! Transaction Details are available in your account.
            </h1>
        </div>

        <div class="mb-4 flex"
            style="display: flex; align-items: center; width: 100%; overflow: hidden; height: auto; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);">
            <img src="../Assets/announce.png" style="padding: 5px; width: 60px; height: 60px; flex-shrink: 0;">
            <h1 style="padding: 15px; margin: 0; flex: 1;">
                Please submit the daily sales report by 5PM. Check your email or reporting system for the necessary
                details.
            </h1>
        </div>
        <!--=================Yesterday Content Goes Here==========================-->

        <div class="Today-container flex" id="Today-container">
            <p class="mb-4 flex" style="margin-top:30px;color:red"> Yesterday<span
                    style="margin-top:10px;margin-left:10px;">
                    <img src="../Assets/chevron_green.png"></span>
            </p>
        </div>
        <div class="mb-4 flex"
            style="display: flex; align-items: center; width: 100%; overflow: hidden; height: auto; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);">
            <img src="../Assets/warning.png" style="padding: 5px; width: 60px; height: 60px; flex-shrink: 0;">
            <h1 style="padding: 15px; margin: 0; flex: 1;">
                Amino Tab Product is almost Out of stock!. Notification From your Gym Management
                System
            </h1>
        </div>
        <div class="mb-4 flex"
            style="display: flex; align-items: center; width: 100%; overflow: hidden; height: auto; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);">
            <img src="../Assets/warning.png" style="padding: 5px; width: 60px; height: 60px; flex-shrink: 0;">
            <h1 style="padding: 15px; margin: 0; flex: 1;">
                Sales Went Down Today to 5%
            </h1>
        </div>

        <div class="mb-4 flex"
            style="display: flex; align-items: center; width: 100%; overflow: hidden; height: auto; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);">
            <img src="../Assets/success_message.png" style="padding: 5px; width: 60px; height: 60px; flex-shrink: 0;">
            <h1 style="padding: 15px; margin: 0; flex: 1;">
                Daily Sales Successfully Sent to Mrs. Merlin! Transaction Details are available in your account.
            </h1>
        </div>
    </div>
</div>
<!-- ============================Modal For Logout================================  -->
<div id="logoutModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden"
    style="z-index: 999">
    <div class="bg-white p-6 rounded-md shadow-lg w-11/12 md:w-1/3" style="z-index: 999">
        <div class="flex justify-center align-center items-center"
            style="border-bottom:2px solid green;margin-bottom:30px;">
            <p class="mb-4" style="font-size:20px;margin-top:15px;margin-right:6px;color:green;">Are you sure you want
                to logout</p>
            <img src="../Assets/logout_bg.png" style="width:30px;height:30px;">
        </div>
        <div class="button-container">
            <button onclick="hideModalLogout()" class="cancel-button">
                Cancel
            </button>
            <button onclick="performLogout()" class="logout-button">
                Logout
            </button>
        </div>
    </div>
</div>


<!--===========Modal For Adding Product===============================-->
<div id="AddProduct" class="fixed  product inset-0 flex items-center justify-center bg-black bg-opacity-60 hidden z-50">
    <div class="modal-content modal-addProduct  bg-white p-8 rounded-lg shadow-lg w-full max-w-sm transform scale-90 transition-transform duration-300 ease-in-out hover:scale-100"
        style="width:450px !important;">
        <div class="flex justify-end">
            <button id="close-notification" onclick="CloseProduct()"
                class="text-gray-600 hover:text-gray-900 transition-colors duration-150">
                <img src="../Assets/close.png" alt="Close" class="w-6 h-6">
            </button>
        </div>
        <form id="productForm" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!--===================TITLE PRODUCT ADDING =====================-->
            <div class="TitleAddingProduct flex" style="margin-bottom:25px;">
                <div class="before-div"
                    style="margin-right:15px;width:40px;height:15px;margin-top:5px;margin-bottom:20px;border-radius:20px;background-color:#259B12">
                </div>
                <h1 class="text-gray-600 font-bold" style="font-size:16px;color:gray;letter-spacing:1px;">ADD NEW
                    PRODUCT</h1>
            </div>
            <!--===================TITLE PRODUCT ADDING =====================-->
            <!--============NAME ID FLEX==========-->
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0">
                <div class="flex-1 flex flex-col">
                    <div class="input-container">
                        <input type="text" id="ProductName" placeholder=" " style="margin-right:20px;width:95%;"
                            name="ProductName" required>
                        <label for="ProductName" class="text-sm font-bold text-gray-700">Product Name</label>
                    </div>
                </div>

                <div class="flex-1 flex flex-col">
                    <div class="input-container">
                        <input type="text" id="ProductId" placeholder=" " name="ProductId" required>
                        <label for="ProductId" class="text-sm font-bold text-gray-700">Product Id</label>
                    </div>
                </div>
            </div>
            <!--========NAME ID FLEX==============-->
            <!--============UNITS PRODUCT-TYPE FLEX==========-->
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0">
                <div class="flex-1 flex flex-col">
                    <div class="input-container">
                        <input type="text" id="ProductName" placeholder=" " style="margin-right:20px;width:95%;"
                            name="Units" required>
                        <label for="ProductName" class="text-sm font-bold text-gray-700">Units</label>
                    </div>
                </div>

                <div class="flex-1 flex flex-col">
                    <div class="input-container">
                        <input type="text" id="ProductId" placeholder=" " name="ProductType" required>
                        <label for="ProductId" class="text-sm font-bold text-gray-700">Product Type</label>
                    </div>
                </div>
            </div>
            <!--============UNITS PRODUCT-TYPE FLEX==========-->
            <!--============STOCK - PRODUCT  FLEX==========-->
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0">
                <div class="flex-1 flex flex-col">
                    <div class="input-container">
                        <input type="text" id="ProductName" placeholder=" " style="margin-right:20px;width:95%;"
                            name="ProductStocks" required>
                        <label for="ProductName" class="text-sm font-bold text-gray-700">Stocks</label>
                    </div>
                </div>

                <div class="flex-1 flex flex-col">
                    <div class="input-container">
                        <input type="text" id="ProductId" placeholder=" " name="ProductPrice" required>
                        <label for="ProductId" class="text-sm font-bold text-gray-700">Price</label>
                    </div>
                </div>
            </div>
            <!--============STOCK - PRODUCT -TYPE FLEX==========-->
            <div class="flex flex-col space-y-4">

                <div class="flex flex-col">
                    <div class="upload-container">
                        <input type="file" id="UploadFile" name="UploadFile" class="hidden" onchange="updateFile()"
                            required>
                        <label class="Documents-btn upload-file-button" for="UploadFile" required>
                            <span class="folderContainer">
                                <!-- Your SVG icons here -->
                                <svg class="fileBack" width="146" height="113" viewBox="0 0 146 113" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M0 4C0 1.79086 1.79086 0 4 0H50.3802C51.8285 0 53.2056 0.627965 54.1553 1.72142L64.3303 13.4371C65.2799 14.5306 66.657 15.1585 68.1053 15.1585H141.509C143.718 15.1585 145.509 16.9494 145.509 19.1585V109C145.509 111.209 143.718 113 141.509 113H3.99999C1.79085 113 0 111.209 0 109V4Z"
                                        fill="url(#paint0_linear_117_4)"></path>
                                    <defs>
                                        <linearGradient id="paint0_linear_117_4" x1="0" y1="0" x2="72.93" y2="95.4804"
                                            gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#8F88C2"></stop>
                                            <stop offset="1" stop-color="#5C52A2"></stop>
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <svg class="filePage" width="88" height="99" viewBox="0 0 88 99" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="88" height="99" fill="url(#paint0_linear_117_6)"></rect>
                                    <defs>
                                        <linearGradient id="paint0_linear_117_6" x1="0" y1="0" x2="81" y2="160.5"
                                            gradientUnits="userSpaceOnUse">
                                            <stop stop-color="white"></stop>
                                            <stop offset="1" stop-color="#686868"></stop>
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <svg class="fileFront" width="160" height="79" viewBox="0 0 160 79" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M0.29306 12.2478C0.133905 9.38186 2.41499 6.97059 5.28537 6.97059H30.419H58.1902C59.5751 6.97059 60.9288 6.55982 62.0802 5.79025L68.977 1.18034C70.1283 0.410771 71.482 0 72.8669 0H77H155.462C157.87 0 159.733 2.1129 159.43 4.50232L150.443 75.5023C150.19 77.5013 148.489 79 146.474 79H7.78403C5.66106 79 3.9079 77.3415 3.79019 75.2218L0.29306 12.2478Z"
                                        fill="url(#paint0_linear_117_5)"></path>
                                    <defs>
                                        <linearGradient id="paint0_linear_117_5" x1="38.7619" y1="8.71323" x2="66.9106"
                                            y2="82.8317" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#C3BBFF"></stop>
                                            <stop offset="1" stop-color="#51469A"></stop>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </span>
                            <p class="text">Upload </p>
                        </label>
                        <div id="showFile" class="file-name-display">
                            <!-- Placeholder for file image or name -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="full-width-button">
                    <h1 class="text-lg text-white font-semibold">Add Product</h1>
                </button>
            </div>
        </form>


    </div>

</div>
</div>

<body class="bg-white flex">
    <div class="notif notif-icon flex items-center space-x-2 cursor-pointer bg-white"
        style="position:fixed;width:100%;border-bottom:2px solid green;cursor:default;">
        <img src="../Assets/location.png" style="width:35px;height:35px;position:relative;left:200px;top:-3px;">
        <h1 class="text-left" style="flex: 1;margin-left:210px;font-size:25px;font-weight:bold;color:green;">Cebu City
            Branch</h1>
        <button class="button" id="notif-icon">
            <svg viewBox="0 0 448 512" class="bell">
                <path
                    d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z">
                </path>
            </svg>
        </button>
        <img src="<?php echo !empty($profileImage) ? 'data:image/jpeg;base64,' . htmlspecialchars($profileImage) : '../Assets/profile.png'; ?>"
            alt="Profile" class="w-8 h-8" style="width:35px;height:35px;cursor:default;border-radius:50%;">
        <span style="border-bottom:4px solid green;cursor:default;"><?php echo $firstName; ?></span>
        <img src="../Assets/chevron-down.png" class="chevron w-4 h-4" alt="Dropdown">
    </div>

    <!-- Dropdown menu -->
    <div class="dropdown-menu"
        style="position:fixed; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 200px;">
        <!-- Settings -->
        <div class="flex items-center px-4 py-2 text-gray-700">
            <a href="#" id="Setting" class="flex items-center w-full" onclick="ShowSettings()">
                <div class="flex">
                    <img src="../Assets/settings.png" style="width:20px;height:20px; margin-right: 12px;">
                    <span>Settings</span>
                </div>
            </a>
        </div>
        <div class="flex items-center px-4 py-2 text-gray-700">
            <a href="#" id="logout-button" class="flex items-center w-full" data-section="logout"
                onclick="showModalLogout(event)">
                <div class="flex">
                    <img src="../Assets/logout.png" style="width:20px;height:20px; margin-right: 12px;">
                    <span>Logout</span>
                </div>
            </a>
        </div>
    </div>
    <span class=" fixed  text-white text-4xl top-5 left-4 cursor-pointer toggle " onclick="toggleSidebar()">
        <img src="../Assets/sidebar-icon.png" class="w-8 h-8 rounded-md" alt="Toggle Sidebar">
    </span>

    <div class="sidebar fixed top-0 shadow-md bottom-0 lg:left-0 p-2 w-64 overflow-y-auto text-center bg-white hidden lg:block sidebar-custom-shadow"
        id="sidebar">
        <div class="text-black-100 text-xl logo-move">
            <div class="p-2.5 mt-1 flex items-center logo">
                <img src="../../Assets/Yokoks_logo.png" class="medical_logo" alt="Yokoks Logo">

                </span>
            </div>
        </div>
        <!-- Sidebar Items -->
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer sidebar-item"
            data-section="dashboard" onclick="showSection('dashboard')">
            <img src="../Assets/dashboard.png" class="sidebar-icon" alt="Dashboard"
                style="width: 20px; height: 20px; object-fit: contain;">
            <span class="text-[15px] font-bold" style="margin-left:10px">Dashboard</span>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer sidebar-item"
            data-section="products" onclick="showSection('products')">
            <img src="../Assets/products.png" class="sidebar-icon" alt="Dashboard"
                style="width: 20px; height: 20px; object-fit: contain;">
            <span class="text-[15px] font-bold" style="margin-left:10px">Products</span>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer sidebar-item"
            data-section="membership" onclick="showSection('membership')">
            <img src="../Assets/membership.png" class="sidebar-icon" alt="Dashboard"
                style="width: 20px; height: 20px; object-fit: contain;">
            <span class="text-[15px] font-bold" style="margin-left:10px">Membership</span>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer sidebar-item"
            data-section="sendSales" onclick="showSection('sendSales')">
            <img src="../Assets/reports.png" class="sidebar-icon" alt="Dashboard"
                style="width: 20px; height: 20px; object-fit: contain;">
            <span class="text-[15px] font-bold" style="margin-left:10px">Reports</span>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer sidebar-item"
            data-section="members" onclick="showSection('members')">
            <img src="../Assets/bill.png" class="sidebar-icon" alt="Dashboard"
                style="width: 20px; height: 20px; object-fit: contain;">
            <span class="text-[15px] font-bold" style="margin-left:10px">Billing</span>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer sidebar-item"
            data-section="Expenses" onclick="showSection('Expenses')">
            <img src="../Assets/expenses.png" class="sidebar-icon" alt="Dashboard"
                style="width: 20px; height: 20px; object-fit: contain;">
            <span class="text-[15px] font-bold" style="margin-left:10px">Expenses</span>
        </div>
    </div>

    <div class="content flex-1 ml-64 lg:ml-80 p-4 relative">

        <!--==============================Dashboard Section===================================================-->
        <div id="dashboard" class="section hidden p-6 dashboard-content">
            <div class="Welcome-flex">
                <div class="yellow-indicator"></div>
                <h1 class="text-gray-500 font-bold welcome">Welcome <?php echo htmlspecialchars($firstName); ?>
                </h1>
            </div>
            <p class="text-gray-300 font-bold analytics">Analytics and Dashboard</p>

            <!--=======Search Icon==================-->
            <div class="flex">
                <img src="../Assets/date.png" style="width:38px;height:38px; margin-right:15px;">
                <h1 class="mt-2"><span id="lastUpdated" class=" mt-2 font-semibold">N/A</span></h1>
            </div>
            <div class="calendar-container" style="margin-bottom:20px;border-bottom:2px solid green">

                <div class="calendar-carousel-wrapper" style="margin-bottom:10px;">
                    <div class="calendar-carousel">
                        <!-- Days of the week will be dynamically inserted here -->
                    </div>
                </div>
            </div>
            <!--=======Search Icon==================-->

            <!--=====================Boxes Sales Report ====================================-->
            <div class="flex flex-wrap justify-center space-x-4 mt-8" style="margin-left:-30px;margin-top:0px;">
                <!-- Box 1 -->
                <div class="box-d bg-white flex flex-col items-center justify-center shadow-lg w-full max-w-xs md:w-80 custom-shadow subtle-shadow mb-10 mr-8 box-shadow"
                    style="margin-right:30px;margin-bottom:30px;">
                    <div class="flex flex-col items-center justify-start w-full p-4">
                        <div class="flex items-center justify-between w-full mb-4">
                            <p class="text-lg font-semibold">Overall Sales</p>
                            <div id="percentage-border"
                                class="percentage-border flex items-center justify-center rounded-full h-16 w-16">
                                <span id="percentage-text1" style="font-size:12px;margin-top:-5px;"
                                    class="text-xs text-black">
                                    <span style="color:green">+</span> 25%
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center" style="margin-left:-20px;margin-top:0px;">
                            <img src="../Assets/pesos.png" class="w-5 h-5"
                                style="width:30px;margin-right:10px;margin-top:9px;" alt="pesos icon">
                            <h1 id="dailySales" class="ml-2 text-lg font-bold" data-end-value="3252.20"
                                style="margin-top:12px">3252.20</h1>
                            <img src="../Assets/sales.png" class="ml-2"
                                style="margin-left:60px;margin-top:-3px;width:75px;height:68px;" alt="sales up icon">
                        </div>
                    </div>
                </div>

                <!-- Box 2 -->
                <div class="box-d bg-white flex flex-col items-center justify-center shadow-lg w-full max-w-xs md:w-80 custom-shadow subtle-shadow mb-10 mr-8 box-shadow"
                    style="margin-right:30px;margin-bottom:30px;">
                    <div class="flex flex-col items-center justify-start w-full p-4">
                        <div class="flex items-center justify-between w-full mb-4">
                            <p class="text-lg font-semibold">Overall Expenses</p>
                            <div id="percentage-border"
                                class="percentage-border-expenses flex items-center justify-center rounded-full h-16 w-16">
                                <span id="percentage-text" style="font-size:12px;margin-top:-10px;"
                                    class="text-xs text-black">
                                    <span id="percentage-sign" style="color:green">+</span> 25%
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center" style="margin-left:-20px;margin-top:0px;">
                            <img src="../Assets/pesos.png" class="w-5 h-5"
                                style="width:30px;margin-right:10px;margin-top:9px;" alt="pesos icon">
                            <h1 id="total-expenses-all" class="ml-2 text-lg font-bold" data-end-value="0.00"
                                style="margin-top:12px">0.00</h1>
                            <img src="../Assets/expense.png" class="ml-2"
                                style="margin-left:60px;width:75px;height:75px;margin-top:-3px;" alt="expenses icon">
                        </div>
                    </div>
                </div>

                <!-- Box 3 -->
                <div class="box-d bg-white flex flex-col items-center justify-center shadow-lg w-full max-w-xs md:w-80 custom-shadow subtle-shadow mb-10 mr-8 box-shadow"
                    style="margin-right:30px;margin-bottom:30px;">
                    <div class="flex flex-col items-center justify-start w-full p-4">
                        <div class="flex items-center justify-between w-full mb-4">
                            <p class="text-lg font-semibold">Overall Debt</p>
                            <div id="percentage-border"
                                class="percentage-border-expenses flex items-center justify-center rounded-full h-16 w-16">
                                <span id="percentage-text" style="font-size:12px;margin-top:-10px;"
                                    class="text-xs text-black">
                                    <span style="color:green">+</span> 25%
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center" style="margin-left:-20px;margin-top:0px;">
                            <img src="../Assets/pesos.png" class="w-5 h-5"
                                style="width:30px;margin-right:10px;margin-top:9px;" alt="pesos icon">
                            <h1 id="dailyDebt" class="ml-2 text-lg font-bold" data-end-value="1500.20"
                                style="margin-top:12px">1500.20</h1>
                            <img src="../Assets/debt.png" class="ml-2"
                                style="margin-left:60px;margin-top:-3px;width:75px;height:70px;" alt="debt icon">
                        </div>
                    </div>
                </div>

                <!--Box3-->
            </div>

            <!--=====================Boxes Sales Report ====================================-->

            <!-- Container for Doughnut Chart and Statistics -->
            <div class="flex flex-chart" style="border-bottom:3px solid green; border-top:3px solid green; height:auto">
                <div class="flex overflow-hidden"
                    style="margin-top:35px; overflow:hidden; z-index:1; margin-left:100px;border-right: 3px solid green;margin-bottom:25px; padding:25px;">
                    <div style="display:block;overflow:hidden;font-weight:bold;letter-spacing:.5px;color:green">
                        <h1>Overall Members Population</h1>
                        <!-- Doughnut Chart Container -->
                        <div class="chart-container1 flex-1">

                            <canvas id="membershipDoughnutChart" width="800" height="400"></canvas>
                        </div>
                    </div>
                </div>
                <div class="chart-container2" style="margin-left:100px">
                    <canvas id="stockHistoryChart" width="400" height="400"></canvas>
                </div>
                <!--====================STOCK HISTORY REPORT SECTION=================================-->

            </div>
            <!--=====================Recent Customers Table ====================================-->
            <div class="recent-customers mt-8 flex">
                <table class="recent-table">
                    <thead>
                        <tr>
                            <th colspan="3" class="text-left text-gray-600" style="font-size:20px;color:green;">
                                Recent
                                Customers
                            </th>
                        </tr>
                        <tr style="white-space:nowrap;color:gray;">
                            <th class="border px-4 py-2">Membership Type</th>
                            <th class="border px-4 py-2">Customer</th>
                            <th class="border px-4 py-2">Total Cost</th>
                        </tr>
                        <?php 
                        include 'recent_customer.php';
                    ?>
                    </thead>
                </table>
                <div id="chartContainer-Expenses" class="hidden">
                    <canvas id="chartContainer-expenses" style="width:400px;"></canvas>
                </div>
            </div>

            <!--=====================Recent Customers Table ====================================-->
        </div>

        <!--==============================Dashboard Section Endpoint===================================================-->
        <?php
           include 'connection.php';
           
            $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';


            $sql = "SELECT id, ProductName, ProductId, units, Price, Stocks, ProductType FROM AddProducts";
            $result = $conn->query($sql);

            $products = [];
            $typeTotals = [
                'Supplements' => 0,
                'Snacks' => 0,
                'Other' => 0
            ];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                    if (array_key_exists($row['ProductType'], $typeTotals)) {
                        $typeTotals[$row['ProductType']]++;
                    } else {
                        $typeTotals['Other']++;
                    }
                }
            }

            // Filter products based on search query
            if (!empty($searchQuery)) {
                $searchQuery = strtolower($searchQuery);
                $filteredProducts = array_filter($products, function($product) use ($searchQuery) {
                    return strpos(strtolower($product['ProductName']), $searchQuery) !== false ||
                        strpos(strtolower($product['ProductId']), $searchQuery) !== false;
                });
            } else {
                $filteredProducts = $products;
            }

            $conn->close();

            function highlight($text, $query) {
                if (!trim($query)) return $text;
                $regex = sprintf('/(%s)/i', preg_quote($query, '/'));
                return preg_replace($regex, '<span class="highlight">$1</span>', $text);
            }
            ?>

        <!-- ============================Products Section================================-->
        <div id="products" class="section hidden">
            <h1 class="font-bold text-gray-500 hear-product-text mt-10"
                style="margin-top:40px;font-size:30px;margin-bottom:20px;">Product
                Statistics</h1>

            <div class="chart-container">
                <!-- Line Chart -->
                <div class="chart-wrapper1">
                    <canvas id="productLineChart"></canvas>
                </div>
                <!-- Doughnut Chart Container -->
                <div class="chart-wrapper">
                    <canvas id="productDoughnutChart"></canvas>
                </div>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function() {

                const supplements = <?php echo json_encode($typeTotals['Supplements']); ?>;
                const snacks = <?php echo json_encode($typeTotals['Snacks']); ?>;
                const other = <?php echo json_encode($typeTotals['Other']); ?>;

                const ctxLine = document.getElementById('productLineChart').getContext('2d');
                new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: ['Supplements', 'Snacks', 'Other'],
                        datasets: [{
                            label: 'Number of Products',
                            data: [supplements, snacks, other],
                            borderColor: [
                                '#13ce4b',
                                '#f8e006',
                                '#f84b06'
                            ],
                            backgroundColor: 'rgba(0,0,0,0)',
                            fill: false,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Product Types'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Number of Products'
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            animateScale: true,
                            duration: 1500
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });

                // Doughnut chart
                const ctxDoughnut = document.getElementById('productDoughnutChart').getContext('2d');
                new Chart(ctxDoughnut, {
                    type: 'doughnut',
                    data: {
                        labels: ['Supplements', 'Snacks', 'Other'],
                        datasets: [{
                            label: 'Number of Products',
                            data: [supplements, snacks, other],
                            backgroundColor: [
                                '#13ce4b',
                                '#f8e006',
                                '#f84b06'
                            ],
                            borderColor: [
                                'rgba(255, 255, 255, 1)'
                            ],
                            borderWidth: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return `${tooltipItem.label}: ${tooltipItem.raw}`;
                                    }
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            animateScale: true,
                            duration: 1500
                        }
                    }
                });
            });
            </script>

            <div class="container">
                <form action="" method="GET" class="search-form">
                    <img src="../Assets/search_icon.png" class="search-icon" alt="Search Icon">
                    <input type="text" name="search" placeholder="Search" class="search-input"
                        value="<?php echo htmlspecialchars($searchQuery); ?>">
                    <button type="submit" class="search-button">Search</button>
                </form>

                <button type="button" class="button-add-item" onclick="AddProduct()">
                    <span class="button__text">Add Product</span>
                    <span class="button__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2"
                            stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none"
                            class="svg">
                            <line y2="19" y1="5" x2="12" x1="12"></line>
                            <line y2="12" y1="12" x2="19" x1="5"></line>
                        </svg>
                    </span>
                </button>
            </div>

            <!-- Product Table -->
            <div class="p-4 mt-6 bg-white shadow-md rounded-md">
                <div class="table-container" style="margin-left:-15px;">
                    <!-- Table HTML -->
                    <table class="min-w-full bg-white border border-gray-200 rounded-md shadow-md">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="p-3 border-b"></th>
                                <th class="p-3 border-b">Product ID</th>
                                <th class="p-3 border-b">Product Name</th>
                                <th class="p-3 border-b">Units</th>
                                <th class="p-3 border-b">Price</th>
                                <th class="p-3 border-b">Stocks</th>
                                <th class="p-3 border-b">Type</th>
                                <th class="p-3 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600" style="border-bottom:3px solid green;">
                            <!-- Rows will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!--=================== Membership Section =================================-->
        <div id="membership" class="section hidden p-4">
            <div class="member-people text-center justify-center">
                <img src="../Assets/member_people.png">
            </div>
            <div class="flex justify-start mb-10 btn-add-member">
                <!-- Add Member Button -->
                <div class="add-member-button flex items-center">
                    <button id="openModalButton" onclick="showAddMembershipModal()"
                        class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Add Member</button>
                </div>
            </div>
            <!-- Member Tables -->
            <?php
            include 'fetch_members.php';

            $membershipTypes = ['daily-basic', 'daily-pro', 'monthly-basic', 'monthly-pro'];

            $membersByType = [];
            $totalSalesByType = [];
            $overallTotal = 0;

            foreach ($membershipTypes as $type) {
                $membersByType[$type] = [];
                $totalSalesByType[$type] = 0;
            }

            foreach ($members as $member) {
                $type = $member['membership_type'];
                if (array_key_exists($type, $membersByType)) {
                    $membersByType[$type][] = $member;
                    $totalSalesByType[$type] += $member['total_cost'];
                    $overallTotal += $member['total_cost'];
                }
            }
            ?>


            <div class="flex-container mt-6">
                <?php foreach ($membershipTypes as $type): ?>
                <?php if (count($membersByType[$type]) > 0): ?>
                <div class="flex-item mb-6">
                    <h2 class="MembershipHead"><?php echo htmlspecialchars(ucwords(str_replace('-', ' ', $type))); ?>
                    </h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Membership Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($membersByType[$type] as $member): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php echo htmlspecialchars($member['first_name']) . ' ' . htmlspecialchars($member['last_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap flex items-center">
                                    <img style="width:20px;height:20px;" src="../Assets/pesos.png" alt="Peso Sign"
                                        class="inline-block" />
                                    <span class="ml-2"><?php echo number_format($member['total_cost'], 2); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php echo $member['remaining_time']; ?>
                                    <?php if (strpos($member['remaining_time'], 'Expired') !== false): ?>
                                    <button class=" renew-btn "
                                        onclick="showRenewModal(<?php echo htmlspecialchars(json_encode($member)); ?>)">Renew</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <?php if (array_sum(array_map('count', $membersByType)) == 0): ?>
            <p class='mt-4 text-gray-600 no-members'>No members found.</p>
            <?php endif; ?>
        </div>
        <!--================================= Renew Membership Modal =======================================-->
        <div id="renewMemberModal"
            class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 hidden z-50">
            <div class="modal-content-member bg-white p-8 rounded-lg shadow-lg w-full transform scale-90 transition-transform duration-300 ease-in-out hover:scale-100"
                style="width:850px !important;">
                <div class="flex justify-end">
                    <button class="text-gray-600 hover:text-gray-900 transition-colors duration-150"
                        onclick="hideRenewModal()">
                        <img src="../Assets/close.png" alt="Close" class="w-6 h-6">
                    </button>
                </div>
                <div class="flex">
                    <div
                        style="width:40px;height:15px;border-radius:20px;background-color:#259B12;margin-right:15px;margin-top:8px;">
                    </div>
                    <h2 class="text-xl font-semibold mb-10 text-left"
                        style="margin-bottom:20px;font-weight:bold;color:gray;letter-spacing:1px;">Renew Membership</h2>
                </div>
                <div class="modal-content-member-wrap">
                    <form id="renewMemberForm" class="memberForm" method="POST" style="width:800px !important;">
                        <div class="flex">
                            <div class="input-container" style="margin-right:10px !important;">
                                <input type="text" id="renewFirstName" name="renewFirstName" placeholder=" " readonly>
                                <label for="renewFirstName" class="text-sm font-bold text-gray-700">First Name</label>
                            </div>
                            <div class="input-container">
                                <input type="text" id="renewLastName" name="renewLastName" placeholder=" " readonly>
                                <label for="renewLastName" class="text-sm font-bold text-gray-700">Last Name</label>
                            </div>
                        </div>
                        <div class="input-container">
                            <input type="text" id="renewContactNumber" name="renewContactNumber" placeholder=" "
                                readonly>
                            <label for="renewContactNumber" class="text-sm font-bold text-gray-700">Contact
                                Number</label>
                        </div>
                        <div class="flex mt-20" style="margin-top:40px;margin-bottom:-10px;">
                            <div
                                style="width:40px;height:15px;border-radius:20px;background-color:#259B12;margin-right:15px;margin-top:8px;">
                            </div>
                            <h2 class="text-xl font-semibold mb-10 text-left"
                                style="margin-bottom:20px;font-weight:bold;color:gray;letter-spacing:1px;">Membership
                                Type</h2>
                        </div>
                        <div class="custom-select" style="margin-bottom:15px;">
                            <select id="renewMembershipType" name="renewMembershipType">
                                <option value="daily-basic">Daily Basic</option>
                                <option value="daily-pro">Daily Pro</option>
                                <option value="monthly-basic">Monthly Basic</option>
                                <option value="monthly-pro">Monthly Pro</option>
                            </select>
                        </div>
                        <div class="mb-4" style="border: 2px solid #4caf50;border-radius:7px;">
                            <div style="padding:7px;">
                                <p class="text-gray-600 font-bold" style="color:gray;font-size:14px;margin-bottom:5px;">
                                    Total Amount</p>
                                <span id="renewTotalCost">$100</span>
                                <input type="hidden" id="renewTotalCostHidden" name="renewTotalCost" value="100">
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="full-width-button">
                                <h1 class="text-lg text-white font-bold">Renew Membership</h1>
                            </button>
                        </div>
                    </form>
                    <div class="right-ImageAddModal">
                        <img src="../Assets/right-add.png">
                    </div>
                </div>
            </div>
        </div>

        <!--================================= Add Member Modal =======================================-->
        <div id="addMemberModal"
            class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 hidden z-50">
            <div class="modal-content-member bg-white p-8 rounded-lg shadow-lg w-full transform scale-90 transition-transform duration-300 ease-in-out hover:scale-100"
                style="width:850px !important;">
                <div class="flex justify-end">
                    <button class="text-gray-600 hover:text-gray-900 transition-colors duration-150"
                        onclick="hideAddMembershipModal()">
                        <img src="../Assets/close.png" alt="Close" class="w-6 h-6">
                    </button>
                </div>
                <!--==============TITLE MEMBER FLEX===============-->
                <div class="flex">
                    <div
                        style="width:40px;height:15px;border-radius:20px;background-color:#259B12;margin-right:15px;margin-top:8px;">
                    </div>
                    <h2 class="text-xl font-semibold mb-10 text-left"
                        style="margin-bottom:20px;font-weight:bold;color:gray;letter-spacing:1px;">Add
                        Membership</h2>
                </div>
                <!--==============TITLE MEMBER FLEX===============-->
                <div class="modal-content-member-wrap">
                    <form id="memberForm" class="memberForm" style="width:800px !important;">
                        <div class="flex">
                            <div class="input-container" style="margin-right:10px !important;">
                                <input type="text" id="firstName" name="firstName" placeholder=" " required>
                                <label for="firstName" class="text-sm font-bold text-gray-700">First Name</label>
                            </div>

                            <div class="input-container">
                                <input type="text" id="lastName" name="lastName" placeholder=" " required>
                                <label for="lastName" class="text-sm font-bold text-gray-700">Last Name</label>
                            </div>
                        </div>
                        <div class="input-container">
                            <input type="text" id="contactNumber" name="contactNumber" placeholder=" " required>
                            <label for="contactNumber" class="text-sm font-bold text-gray-700">Contact
                                Number</label>
                        </div>

                        <!--==============TITLE MEMBER FLEX===============-->
                        <div class="flex mt-20" style="margin-top:40px;margin-bottom:-10px;">
                            <div
                                style="width:40px;height:15px;border-radius:20px;background-color:#259B12;margin-right:15px;margin-top:8px;">
                            </div>
                            <h2 class="text-xl font-semibold mb-10 text-left"
                                style="margin-bottom:20px;font-weight:bold;color:gray;letter-spacing:1px;">Membership
                                Type</h2>
                        </div>
                        <!--==============TITLE MEMBER FLEX===============-->
                        <div class="custom-select" style="margin-bottom:15px;">
                            <select id="membershipType" name="membershipType">
                                <option value="daily-basic">Daily Basic</option>
                                <option value="daily-pro">Daily Pro</option>
                                <option value="monthly-basic">Monthly Basic</option>
                                <option value="monthly-pro">Monthly Pro</option>
                            </select>

                        </div>

                        <div class="mb-4" style="  border: 2px solid #4caf50;border-radius:7px;">
                            <div style="padding:7px;">
                                <p class="text-gray-600 font-bold" style="color:gray;font-size:14px;margin-bottom:5px;">
                                    Total Amount</p>
                                <span id="totalCost">$100</span>
                                <input type="hidden" id="totalCostHidden" name="totalCost" value="100">
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit" class="full-width-button">
                                <h1 class="text-lg text-white font-bold">Add Member</h1>
                            </button>
                        </div>

                    </form>
                    <!--======IMAGE ON THE RIGHT SIDE=========-->
                    <div class="right-ImageAddModal">
                        <img src="../Assets/right-add.png">
                    </div>
                </div>
            </div>

        </div>
        <!--=================== Send Sales Section ==============================================-->
        <div id="sendSales" class="section hidden">
            <!--====================MEMBERSHIP REPORT SECTION=================================-->
            <form class="container-edit-history">

                <h1 class="Daily-Reports">Today's Reports</h1>
                <div class="report-insights w-full max-w-md mx-auto">
                    <div class="flex mb-10">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Report Insights</h2>
                        <img src="../Assets/report-insight.png" class="icon-img">
                    </div>
                    <div id="progress-container" class="progress-container">
                        <div class="progress-block">
                            <label class="progress-label">Daily Members</label>
                            <div id="progress-circle" class="progress-circle"></div>
                        </div>
                        <div class="progress-block">
                            <label class="progress-label">Renewed Members</label>
                            <div id="today-renewed-progress-circle" class="progress-circle"></div>
                        </div>
                        <div class="progress-block">
                            <label class="progress-label">Daily Expenses</label>
                            <div id="expenses-progress-circle" class="progress-circle"></div>
                        </div>
                    </div>
                </div>


                <!-- Report Insights -->
                <div class="financial-overview" style="margin-bottom:30px;margin-top:10px;cursor:pointer;">
                    <div class="financial-box bg-green block" id="viewStockHistory">
                        <h3 class="text-xl font-semibold">View Edited Stock History</h3>
                        <div class="text-center flex justify-center">
                            <img src="../Assets/view_img.png" class="text-center;" style="width:65px;height:65px;">
                        </div>
                    </div>
                    <div class="financial-box bg-red block">
                        <h3 class="text-xl font-semibold">Expenses List</h3>
                        <div class="text-center flex justify-center">
                            <img src="../Assets/expenses_list.png" class="text-center;" style="width:65px;height:65px;">
                        </div>

                    </div>
                    <div class="financial-box bg-blue block">
                        <h3 class="text-xl font-semibold">Debt Lists</h3>
                        <div class="text-center flex justify-center">
                            <img src="../Assets/debt_list.png" class="text-center;" style="width:65px;height:65px;">
                        </div>
                    </div>
                </div>

                <!--=====================Boxes Sales Report ====================================-->
                <div class="flex flex-wrap justify-center space-x-4 mt-8" style="margin-left:-30px;margin-top:0px;">
                    <!-- Box 1 -->
                    <div class="box-d bg-white flex flex-col items-center justify-center shadow-lg w-full max-w-xs md:w-80 custom-shadow subtle-shadow mb-10 mr-8 box-shadow"
                        style="margin-right:30px;margin-bottom:30px;">
                        <div class="flex flex-col items-center justify-start w-full p-4">
                            <div class="flex items-center justify-between w-full mb-4">
                                <p class="text-lg font-semibold">Daily Sales</p>
                                <div id="percentage-border"
                                    class="percentage-border flex items-center justify-center rounded-full h-16 w-16">
                                    <span id="percentage-text1" style="font-size:12px;margin-top:-5px;"
                                        class="text-xs text-black">
                                        <span style="color:green">+</span> 25%
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center" style="margin-left:-20px;margin-top:0px;">
                                <img src="../Assets/pesos.png" class="w-5 h-5"
                                    style="width:30px;margin-right:10px;margin-top:9px;" alt="pesos icon">
                                <h1 id="dailySales" class="ml-2 text-lg font-bold" data-end-value="3252.20"
                                    style="margin-top:12px">3252.20</h1>
                                <img src="../Assets/sales.png" class="ml-2"
                                    style="margin-left:60px;margin-top:-3px;width:75px;height:68px;"
                                    alt="sales up icon">
                            </div>
                        </div>
                    </div>

                    <!-- Box 2 -->
                    <div class="box-d bg-white flex flex-col items-center justify-center shadow-lg w-full max-w-xs md:w-80 custom-shadow subtle-shadow mb-10 mr-8 box-shadow"
                        style="margin-right:30px;margin-bottom:30px;">
                        <div class="flex flex-col items-center justify-start w-full p-4">
                            <div class="flex items-center justify-between w-full mb-4">
                                <p class="text-lg font-semibold">Daily Expenses</p>
                                <div id="percentage-border"
                                    class="percentage-border-expenses flex items-center justify-center rounded-full h-16 w-16">
                                    <span id="percentage-text2" style="font-size:12px;margin-top:-10px;"
                                        class="text-xs text-black">
                                        <span id="percentage-sign" style="color:green">+</span> 25%
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center" style="margin-left:-20px;margin-top:0px;">
                                <img src="../Assets/pesos.png" class="w-5 h-5"
                                    style="width:30px;margin-right:10px;margin-top:9px;" alt="pesos icon">
                                <h1 id="total-expenses" class="ml-2 text-lg font-bold" data-end-value="0.00"
                                    style="margin-top:12px">0.00</h1>
                                <img src="../Assets/expense.png" class="ml-2"
                                    style="margin-left:60px;width:75px;height:75px;margin-top:-3px;"
                                    alt="expenses icon">
                            </div>
                        </div>
                    </div>

                    <!-- Box 3 -->
                    <div class="box-d bg-white flex flex-col items-center justify-center shadow-lg w-full max-w-xs md:w-80 custom-shadow subtle-shadow mb-10 mr-8 box-shadow"
                        style="margin-right:30px;margin-bottom:30px;">
                        <div class="flex flex-col items-center justify-start w-full p-4">
                            <div class="flex items-center justify-between w-full mb-4">
                                <p class="text-lg font-semibold">Daily Debt</p>
                                <div id="percentage-border"
                                    class="percentage-border-expenses flex items-center justify-center rounded-full h-16 w-16">
                                    <span id="percentage-text" style="font-size:12px;margin-top:-10px;"
                                        class="text-xs text-black">
                                        <span style="color:green">+</span> 25%
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center" style="margin-left:-20px;margin-top:0px;">
                                <img src="../Assets/pesos.png" class="w-5 h-5"
                                    style="width:30px;margin-right:10px;margin-top:9px;" alt="pesos icon">
                                <h1 id="dailyDebt" class="ml-2 text-lg font-bold" data-end-value="1500.20"
                                    style="margin-top:12px">1500.20</h1>
                                <img src="../Assets/debt.png" class="ml-2"
                                    style="margin-left:60px;margin-top:-3px;width:75px;height:70px;" alt="debt icon">
                            </div>
                        </div>
                    </div>
                </div>
                <button class="submitReport">Submit Report</button>
            </form>

        </div>

        <!-- Modal for Displaying Stock Edit History -->
        <div id="stockHistoryModal" class="modal">
            <div class="modal-content-stocks">
                <div class="flex" style="border-bottom:3px solid green;box-shadow:0 10px 10px rgba(0,0,0,0.2)">
                    <img src="../Assets/view_img.png"
                        style="width:100px;height:100px;margin-bottom:4px;text-align:center;margin-left:30px;">
                </div>
                <div id="historyContainer" style="margin-top:20px;">
                    <div class="history-item">
                        <div class="date-time"><strong>Date and Time:</strong> 2024-09-09 14:30</div>
                        <div><strong>Product Name:</strong> Product A</div>
                        <div><strong>Changed Stocks:</strong> +10</div>
                        <div><strong>Reason:</strong> Restock</div>
                    </div>
                    <!-- More .history-item elements here -->
                </div>
            </div>
        </div>
        <!-----===========================MEMBERS SECTION==================-->
        <?php
                include 'connection.php';
                $sql = "SELECT id,  first_name, last_name, membership_type, total_cost, paid_status FROM members";
                $result = $conn->query($sql);

                ?>

        <div id="members" class="section hidden ">

            <!-- =====Search Form -->
            <div class="search-container flex mt-10" style="margin-top:40px;">
                <form method="GET" action="" class="flex">
                    <input type="text" name="search" id="search" placeholder="Search by name" style="width:400px;
                    height:50px;padding:10px;border:2px solid green;border-radius:10px"
                        value="<?php echo htmlspecialchars($searchQuery); ?>">
                    <img src="../Assets/search_icon.png"
                        style="position:absolute;width:30px;height:30px;margin-left:15px;margin-top:8px;left:350px">
                </form>
            </div>
            <!-- =====Search Form -->
            <div id="membersList" class="grid-container">
                <div class="green-circles flex">
                    <p>No members found</p>
                </div>

            </div>

            <!--===========Add Item Modal==================-->
            <div class="AddItemModal hiding" id="AddItemModal">
                <div class="modal-content-add-item">
                    <div class="left-aligned-content">
                        <!-- Search Input -->
                        <input type="text" class="search-item" id="searchInput" placeholder="Search products..."
                            onkeyup="filterProducts()">
                    </div>

                    <div class="WRAP" id="productContainer">
                        <?php
            // Function to sanitize productId for use in HTML IDs
            function sanitizeId($id) {
                return preg_replace('/[^a-zA-Z0-9_-]/', '_', $id);
            }
            function highlightSearchTerm($text, $searchTerm) {
                $searchTerm = preg_quote($searchTerm, '/');
                return preg_replace('/('.$searchTerm.')/iu', '<mark>$1</mark>', $text);
            }

            include 'connection.php';
            $sql = "SELECT * FROM AddProducts";
            $result = $conn->query($sql);

            $searchTerm = isset($_GET['search']) ? strtolower($_GET['search']) : '';

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $image = $row['image'];
                    $productName = htmlspecialchars($row['ProductName']);
                    // Format the price to two decimal places
                    $price = number_format((float)$row['Price'], 2, '.', '');
                    $stocks = htmlspecialchars($row['Stocks']);
                    $Id = sanitizeId(htmlspecialchars($row['id']));
                    $imgSrc = !empty($image) ? 'data:image/jpeg;base64,' . base64_encode($image) : 'path/to/default/image.jpg';

                    // Highlight search term in product name
                    $highlightedName = $productName;
                    if ($searchTerm) {
                        $highlightedName = highlightSearchTerm($highlightedName, $searchTerm);
                    }

                    // Determine if the product is out of stock
                    $outOfStockClass = ($stocks <= 0) ? 'out-of-stock-message' : 'hidden';

                    echo "<div class='product-box' data-product-name='" . strtolower($productName) . "'>";
                    echo "<div class='bottom-line' style='border-bottom:2px solid green;width:100%;margin-bottom:17px;'>";

                    echo "<div class='product-image-container'>
                            <div class='$outOfStockClass'>Out of Stock</div>
                            <img src='" . $imgSrc . "' alt='Product Image' class='product-image'>
                        </div>";

                    echo "<h2>" . $highlightedName . "</h2>";

                    // Price Container
                    echo "<div class='price-flex'>
                            <img src='../Assets/pesos.png' style='width:25px;height:25px;margin-top:10px;margin-right:7px;'>
                            <p class='price-text'>" . $price . "</p>
                        </div>";
                    echo "</div>";

                    // Quantity Container
                    echo "<div class='quantity-container'>";
                    echo "<button class='quantity-btn-minus' onclick='updateItemQuantity(\"$Id\", -1)'></button>";
                    echo "<input type='text' id='quantity_$Id' class='quantity-input' value='1' readonly>";
                    echo "<button class='quantity-btn-plus' onclick='updateItemQuantity(\"$Id\", 1)'></button>";
                    echo "</div>";

                    // Total Container
                    echo "<div class='total-container flex'>";
                    echo "<span class='total-label' style='margin-top:7px;font-size:17px;font-weight:bold;margin-right:13px;'>Total</span>";
                    echo "<div class='price-container flex' style='display: flex; align-items: center;'>";
                    echo "<img src='../Assets/pesos.png' class='currency-icon' style='width:25px;height:25px;margin-right:7px;'>";
                    echo "<span id='totalItem_" . $Id . "' data-price='" . $price . "' class='total-price'>" . $price . "</span>";
                    echo "</div>";
                    echo "</div>";

                    // Add to Cart Button
                    echo "<button class='add-to-cart-btn' onclick='addToCart(\"$Id\")'>Add Item</button>";

                    echo "</div>";
                }
            } else {
                echo "<p>No products found</p>";
            }

            $conn->close();
            ?>
                    </div>

                </div>

            </div>
            <!----==================Main Container OF THE SECTIONS ENDPOINT=================-->
        </div>
        <!--==========================EXPENSES SECTION =============================-->
        <div class="section hidden" id="Expenses">
            <div class="expense">
                <img src="../Assets/expense_bg.png">
            </div>
            <div id="infoBox">
                <button id="showFormButton">Add Expense</button>
            </div>
            <div id="expenseFormContainer" class="hidden">
                <img onclick="backHistory()" src="../Assets/back_arrow.png" class="expense-back">

                <form id="expenseForm">
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description" required>
                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" step="0.01" required>
                    <button type="submit">Add Expense</button>
                </form>
            </div>
        </div>

        <!--==========================EXPENSES SECTION =============================-->
        <!--=============Loading Animation-->
        <div id="loadingSpinner" class="relative hidden">
            <img src="../Assets/loading_L.gif" class="loading" alt="Loading Spinner">
        </div>
        <!--=============Loading Animation-->
        <script src="../JS/dashboard.js"></script>
        <script src="../JS/logout.js"></script>
        <script src="../JS/stocks.js"></script>
        <script src="../JS/product.js"></script>
        <script src="../JS/add_member.js"></script>
        <script src="../JS/expenses.js"></script>
        <script src="../JS/members.js"></script>
        <script src="../JS/showSettings.js"></script>
        <script src="../JS/profile.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="../JS/update_file_product.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.0.2/index.min.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var membershipData = {
                'Daily-basic': <?php echo count($membersByType['daily-basic']); ?>,
                'Daily-pro': <?php echo count($membersByType['daily-pro']); ?>,
                'monthly-basic': <?php echo count($membersByType['monthly-basic']); ?>,
                'monthly-pro': <?php echo count($membersByType['monthly-pro']); ?>
            };

            var data = {
                labels: Object.keys(membershipData).map(type => {
                    return type.replace('-', ' ').toUpperCase();
                }),
                datasets: [{
                    data: Object.values(membershipData),
                    backgroundColor: [
                        'rgba(201, 81, 107, 0.692)',
                        '#cf1a42',
                        '#0ca506',
                        'rgb(30, 128, 0)'
                    ],
                    hoverOffset: 20
                }]
            };

            var ctx = document.getElementById('membershipDoughnutChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000, // Duration of animation in milliseconds
                        easing: 'linear', // Linear easing function
                        animateRotate: true, // Rotate the chart during animation
                        animateScale: true // Scale the chart during animation
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'right',
                            align: 'start',
                            labels: {
                                boxWidth: 10,
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    var label = tooltipItem.label || '';
                                    if (label) {
                                        label += ': ' + tooltipItem.raw + ' members';
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                }
            });
        });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/progressbar.js@1.1.0/dist/progressbar.min.js"></script>

</body>

</html>