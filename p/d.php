<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: L.php"); 
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="../src/output.css" rel="stylesheet" />
    <link href="../CSS/dashboard.css" rel="stylesheet" />
    <link rel="icon" href="../Assets/download.png" />
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Inter" rel="stylesheet" />
    <style>

    </style>
</head>

<body class="bg-white flex">
    <!-- ================================Hamburger Icon to Open/Close Sidebar ======================-->
    <span class="absolute text-white text-4xl top-5 left-4 cursor-pointer toggle" onclick="toggleSidebar()"
        style="position:fixed">
        <img src="../Assets/download.png" class="w-8 h-8 rounded-md" />
    </span>

    <!-- ===================Sidebar ======================================-->
    <div class="sidebar fixed top-0 bottom-0 lg:left-0 p-2 w-64 overflow-y-auto text-center bg-green-950 hidden lg:block"
        id="sidebar">
        <div class="text-black-100 text-xl">
            <div class="p-2.5 mt-1 flex items-center logo">
                <div class="logo">
                    <img src="../Assets/medical_logo.jpg" class="medical_logo" />
                </div>
                <span class="cursor-pointer lg:hidden" style="margin-left: 60px;z-index:999;" onclick="toggleSidebar()">
                    <img src="../Assets/download.png" class="w-8 h-8 rounded-md" />
                </span>
            </div>
            <div class="my-2 bg-gray-600 h-[1px]"></div>
        </div>

        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer sidebar-item"
            data-section="dashboard" onclick="showSection('dashboard')">
            <span class="text-[15px] font-bold">Dashboard</span>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer sidebar-item"
            data-section="hospitals" onclick="showSection('hospitals')">
            <span class="text-[15px] font-bold">Hospitals</span>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer sidebar-item"
            data-section="doctors" onclick="showSection('doctors')">
            <span class="text-[15px] font-bold">Doctors</span>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer sidebar-item"
            data-section="nurses" onclick="showSection('nurses')">
            <span class="text-[15px] font-bold">Nurses</span>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer sidebar-item"
            data-section="midwife" onclick="showSection('midwife')">
            <span class="text-[15px] font-bold">Midwife</span>
        </div>

        <div class="my-4 bg-gray-600 h-[1px]"></div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white sidebar-item"
            onclick="toggleDropdown()">
            <i class="bi bi-chat-left-text-fill"></i>
            <div class="flex justify-between w-full items-center">
                <img src="../Assets/profile.png" class="dashboard_icon-2 " />
                <span class="text-[15px] ml-4 font-bold" style="margin-left:-76px;">Profile</span>
                <span class="text-sm rotate-180" id="arrow">
                    <i class="bi bi-chevron-down"></i>
                </span>
            </div>
        </div>
        <div class="text-left text-sm mt-2 w-4/5 mx-auto text-gray-200 font-bold hidden" id="submenu">
            <div class="display-flex">
                <img src="../Assets/profile.png" class="dashboard_icon-2 " />
                <h1 class="cursor-pointer p-2 rounded-md mt-1">
                    Account
                </h1>
            </div>
            <div class="display-flex">
                <img src="../Assets/profile.png" class="dashboard_icon-2 " />
                <h1 class="cursor-pointer p-2  rounded-md mt-1">
                    Personal
                </h1>
            </div>
            <div class="display-flex">
                <img src="../Assets/pass.jpg" class="dashboard_icon-2 pass " />
                <h1 class="cursor-pointer p-2  rounded-md mt-1">
                    Password
                </h1>
            </div>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white sidebar-item"
            data-section="logout" onclick="showModal()">
            <img src="../Assets/logout.png" class="dashboard_icon-4" />
            <span class="text-[15px] ml-4 text-gray-200 font-bold " style="margin-left:10px;">Logout</span>
        </div>
    </div>

    <!--================================ Main content ==============================================-->
    <div class="content flex-1 ml-64 lg:ml-80 p-4 relative">
        <div id="dashboard" class="section hidden p-6">
            <!-- =========================Welcome Box=========================== -->
            <div class="welcome-box bg-white p-6 rounded-lg shadow-lg mb-8">
                <div class="flex flex-col md:flex-row items-center md:items-start mb-4">
                    <!-- =========================Admin Picture ==================================-->
                    <img src="../Assets/admin_icon.png" alt="Admin Picture"
                        class="w-24 h-24 md:w-30 md:h-30 rounded-full mb-4 md:mb-0 md:mr-4" />
                    <!-- ====================Welcome Text ======================= -->
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold mb-2">
                            Welcome , <?php echo htmlspecialchars($username);  ?> !
                        </h1>
                        <p class="text-base md:text-lg text-gray-700">Administrator</p>
                    </div>
                </div>
            </div>

            <!-- =========================Add Staff Section ============================-->
            <div class="add-staff-section my-8">
                <div class="flex justify-around">
                    <!--======================= Add Doctor Box ===================================-->
                    <div class="add-box bg-blue-500 text-black p-6 rounded-lg shadow-lg cursor-pointer"
                        onclick="showSection('Add_Doctor')">
                        <h3 class="text-xl text-center font-bold mb-2 add-text">
                            Add Doctor
                        </h3>
                        <img src="../Assets/add.png" class="add" />
                    </div>

                    <!--========================== Add Nurse Box=============================== -->
                    <div class="add-box bg-green-500 text-black p-6 shadow-lg cursor-pointer"
                        onclick="showSection('Add_Nurse')">
                        <h3 class="text-xl font-bold mb-2 add-text">Add Nurse</h3>
                        <img src="../Assets/add.png" class="add" />
                    </div>

                    <!-- ===========================Add Midwife Box =================================== -->
                    <div class="add-box bg-purple-500 text-black p-6 rounded-lg shadow-lg cursor-pointer"
                        onclick="showSection('Add_midwife')">
                        <h3 class="text-xl font-bold mb-2 add-text">Add Midwife</h3>
                        <img src="../Assets/add.png" class="add" />
                    </div>
                </div>
            </div>
            <div class="flex-1 flex items-center Analytics">
                <img src="../Assets/analytics.png" alt="Analytics Icon" class="w-5 h-5 analytics-icon" />
                <h1 class="text-3xl font-bold mr-4">Analytics</h1>
            </div>

            <!--============================= Total Patients Overview ======================================-->
            <h2 class="text-2xl font-bold mb-4">Total Patients Overview</h2>
            <canvas id="patientsChart" class="w-full h-96 mb-8"></canvas>

            <!-- =================================Healthcare Population Overview=================================== -->
            <h2 class="text-2xl font-bold mb-4">Healthcare Population Overview</h2>
            <canvas id="healthcareChart" class="w-full h-96"></canvas>

            <!--====================================== Patient Status Overview ==========================================-->
            <h2 class="text-2xl font-bold mb-4">Patient Status Overview</h2>
            <div class="flex flex-wrap justify-around gap-8 mt-8">
                <div class="circle bg-green-300 border-2 border-green-500 shadow-lg">
                    <div class="text-lg font-bold text-gray-800">Survivors</div>
                    <div class="text-3xl font-bold text-gray-700" id="survivorsValue">
                        20
                    </div>
                </div>
                <div class="circle bg-red-300 border-2 border-red-500 shadow-lg">
                    <div class="text-lg font-bold text-gray-800">Deceased</div>
                    <div class="text-3xl font-bold text-gray-700" id="deceasedValue">
                        50
                    </div>
                </div>
                <div class="circle bg-blue-300 border-2 border-blue-500 shadow-lg">
                    <div class="text-lg font-bold text-gray-800">Under Treatment</div>
                    <div class="text-3xl font-bold text-gray-700" id="underTreatmentValue">
                        100
                    </div>
                </div>
            </div>
        </div>

        <div id="hospitals" class="section hidden">Hospitals Content</div>
        <div id="doctors" class="section hidden">
            <div class="p-4">
                <input type="text" id="searchInput" onkeyup="searchDoctors()" placeholder="Search for doctors..."
                    class="w-full p-2 border-2 border-gray-300 rounded-lg" />
            </div>
            <div class="table-container">
                <table class="doctors-table">
                    <thead>
                        <tr>

                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Specialty</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="doctorsTableBody">

                    </tbody>
                </table>
            </div>
        </div>

        <script>
        function searchDoctors() {
            const searchInput = document.getElementById('searchInput').value;
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'doctors.php?query=' + encodeURIComponent(searchInput), true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('doctorsTableBody').innerHTML = xhr.responseText;
                } else {
                    console.error('Error fetching data');
                }
            };
            xhr.send();
        }

        document.addEventListener('DOMContentLoaded', function() {
            searchDoctors();
        });
        </script>


        <div id="nurses" class="section hidden">Nurses Content</div>
        <div id="midwife" class="section hidden">Midwife Content</div>
        <div id="chatbox" class="section hidden">Chatbox Content</div>

        <!-- ===============================Add Doctor Section=============================================== -->
        <div id="Add_Doctor" class="section hidden">
            <div class="p-6 bg-white rounded-lg shadow-lg max-w-3xl mx-auto">
                <img src="../Assets/back_arrow.png" onclick="goBack()" class="back-arrow cursor-pointer mb-4"
                    alt="Back" />
                <h2 class="text-3xl font-extrabold mb-6 text-gray-800 mb-4 text-center font-bold">
                    Add New Doctor
                </h2>
                <form class="space-y-8" id="doctorForm" method="post" onsubmit="submitDoctorForm(event)">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!--====================== Last Name ================= -->
                        <div class="col-span-2" style="margin-bottom: 15px">
                            <label for="LastName" class="block text-gray-700 font-semibold mb-2 label-doctor">Last
                                Name</label>
                            <input type="text" id="LastName" name="LastName" required placeholder="Last Name"
                                class="w-full p-4 border-2 border-green-600 rounded-lg shadow-md focus:outline-none input-doctor" />
                        </div>
                        <!--==============First  Name================  -->
                        <div class="col-span-2 move-input" style="margin-bottom: 15px">
                            <label for="LastName" class="block text-gray-700 font-semibold mb-2 label-doctor">First
                                Name</label>
                            <input type="text" id="FirstName" name="FirstName" required placeholder="First Name"
                                class="w-full p-4 border-2 border-green-600 rounded-lg shadow-md focus:outline-none input-doctor" />
                        </div>
                        <!--===================Middle Name =========== -->
                        <div class="col-span-2 move-input" style="margin-bottom: 15px">
                            <label for="MiddleName" class="block text-gray-700 font-semibold mb-2 label-doctor">Middle
                                Name</label>
                            <input type="text" id="MiddleName" name="MiddleName" required placeholder="Middle Name"
                                class="w-full p-4 border-2 border-green-600 rounded-lg shadow-md focus:outline-none input-doctor" />
                        </div>

                        <!-- ===========Specialty and Contact Number (Horizontal)============ -->
                        <div style="margin-bottom: 15px">
                            <label for="specialty"
                                class="block text-gray-700 font-semibold mb-2 label-doctor">Specialty</label>
                            <input type="text" id="specialty" name="specialty" required placeholder="Enter Specialty"
                                class="w-full p-4 border-2 border-green-600 rounded-lg shadow-md focus:outline-none input-doctor" />
                        </div>

                        <div style="margin-bottom: 15px">
                            <label for="contactNumber"
                                class="block text-gray-700 font-semibold mb-2 label-doctor">Contact Number</label>
                            <input type="tel" id="contactNumber" name="contactNumber" required
                                placeholder="63+ 9319887714"
                                class="w-full p-4 border-2 border-green-600 rounded-lg shadow-md focus:outline-none input-doctor" />
                        </div>

                        <!-- ==============Email and Date of Birth (Horizontal)============-->
                        <div style="margin-bottom: 15px">
                            <label for="email" class="block text-gray-700 font-semibold mb-2 label-doctor">Email
                                Address</label>
                            <input type="email" id="email" name="email" required placeholder="dr.johndoe@example.com"
                                class="w-full p-4 border-2 border-green-600 rounded-lg shadow-md focus:outline-none input-doctor" />
                        </div>

                        <div style="margin-bottom: 15px">
                            <label for="dob" class="block text-gray-700 font-semibold mb-2 label-doctor">Date of
                                Birth</label>
                            <input type="date" id="dob" name="dob" required
                                class="w-full p-4 border-2 border-green-600 rounded-lg shadow-md focus:outline-none input-doctor" />
                        </div>

                        <!-- ==============Medical License Number (Vertical) ================-->
                        <div class="col-span-2" style="margin-bottom: 15px">
                            <label for="licenseNumber"
                                class="block text-gray-700 font-semibold mb-2 label-doctor">Medical License
                                Number</label>
                            <input type="text" id="licenseNumber" name="licenseNumber" required placeholder="ML1234567"
                                class="w-full p-4 border-2 border-green-600 rounded-lg shadow-md focus:outline-none input-doctor" />
                        </div>
                    </div>

                    <!-- =============Address (Vertical) ================-->
                    <div style="margin-bottom: 15px">
                        <label for="address" class="block text-gray-700 font-semibold mb-2 label-doctor">Address</label>
                        <textarea id="address" name="address" required
                            placeholder="123 Main St, Apt 4B, City, State, ZIP" rows="4"
                            class="w-full p-4 border-2 border-green-600 rounded-lg shadow-md focus:outline-none input-doctor"></textarea>
                    </div>

                    <!-- ==================Submit Button ===============-->
                    <div class="text-left mb-4 button-doctor">
                        <button id="submitButton" type="submit"
                            class="py-3 px-6 bg-green-600 text-gree font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-300">

                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!--======================== Add Nurse Section====================== -->
        <div id="Add_Nurse" class="section hidden">
            <div class="p-4">
                <img src="../Assets/back_arrow.png" onclick="goBack()" class="back-arrow" />
                <h2 class="text-2xl font-bold mb-4">Add Nurse</h2>
            </div>
        </div>

        <!--=================================== Add Midwife Section ====================-->
        <div id="Add_midwife" class="section hidden">
            <div class="p-4">
                <img src="../Assets/back_arrow.png" onclick="goBack()" class="back-arrow" />
                <h2 class="text-2xl font-bold mb-4">Add Midwife</h2>
            </div>
        </div>
    </div>

    <!-- ============================Modal For Logout================================  -->
    <div id="logoutModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden"
        style="z-index: 999">
        <div class="bg-white p-6 rounded-md shadow-lg w-11/12 md:w-1/3" style="z-index: 999">
            <h2 class="text-lg font-bold mb-4">Confirm Logout</h2>
            <p class="mb-4">Are you sure you want to logout?</p>
            <div class="flex justify-end">
                <button onclick="hideModal()" class="px-4 py-2 bg-gray-300 text-black rounded-md mr-2">
                    Cancel
                </button>
                <button onclick="Logout()" class="px-4 py-2 bg-red-500 text-white rounded-md">
                    Logout
                </button>
            </div>
        </div>
    </div>


    <script>
    function Logout() {
        hideModal();
        showLoadingSpinner();

        const logoutTime = getEstimatedLoadTime();

        setTimeout(() => {
            // Perform AJAX request to logout.php
            fetch('logout.php', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.replace("L.php");
                    } else {
                        // Handle error if logout failed
                        console.error("Logout failed.");
                    }
                })
                .catch(error => console.error("Error during logout:", error))
                .finally(() => hideLoadingSpinner());
        }, logoutTime);
    }
    </script>

    <!--================================= Loading Spinner ============================-->
    <div id="loadingSpinner" class="relative hidden">
        <img src="../Assets/bear_loading.gif" class="loading" />
    </div>
    <script src="../JS/dashboard.js"></script>
    <script src="../JS/fetch_addDoctor.js"></script>
    <script src="../JS/dashboard_graph.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>