<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "admin";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo json_encode(["status" => "error", "message" => "Connection error: " . $conn->connect_error]);
        exit();
    }

    $admin_username = $conn->real_escape_string($_POST['username']);
    $admin_password = $_POST['password'];

    $sql = "SELECT password FROM admin_credentials WHERE username='$admin_username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($admin_password, $row['password'])) {
            $_SESSION['username'] = $admin_username;
            echo json_encode(["status" => "success", "message" => "Login successful"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Username not found."]);
    }

    $conn->close();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="../Assets/download.png" rel="icon">
  <link href="../src/output.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .spinner1 {
      text-align: center;
      position: relative;
      left: 150px;
      width: 50px;
      height: 50px;
      border: 5px solid #f3f3f3;
      border-top: 5px solid #3498db;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="w-full max-w-sm p-8 bg-white rounded shadow-lg relative">
    <div class="flex items-center justify-center mb-6">
      <span class="text-xl font-bold">Login</span>
    </div>

    <div id="error" class="mb-4 p-2 bg-red-100 text-red-700 border border-red-400 rounded hidden">
      Error message here
    </div>

    <form id="loginForm">
      <div class="mb-4">
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input
          id="username"
          name="username"
          type="text"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          required
        />
      </div>
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input
          id="password"
          name="password"
          type="password"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          required
        />
      </div>
      <button
        type="submit"
        id="submitButton"
        class="w-full px-4 py-2 text-white font-semibold rounded-md shadow-md bg-green-600 hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
      >
        Login
      </button>
    </form>

    <div id="spinnerOverlay" class="absolute inset-0 flex items-center justify-center bg-gray-200 bg-opacity-50 hidden">
      <div class="w-full text-center">
        <div class="spinner1"></div>
      </div>
    </div>

    <p class="mt-4 text-center">
      Don't have an account? 
      <a href="/" class="text-indigo-600 hover:text-indigo-800">Register</a>
    </p>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
      event.preventDefault();

      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;

      // Show spinner and disable button
      document.getElementById('submitButton').innerText = 'Signing in...';
      document.getElementById('spinnerOverlay').classList.remove('hidden');

      // Perform AJAX request
      fetch('', { 
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          'username': username,
          'password': password
        })
      })
      .then(response => response.json())
      .then(data => {
        document.getElementById('spinnerOverlay').classList.add('hidden');
        document.getElementById('submitButton').innerText = 'Login';

        if (data.status === 'success') {
          window.location.href = 'd.php';
        } else {
          document.getElementById('error').innerText = data.message;
          document.getElementById('error').classList.remove('hidden');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        document.getElementById('spinnerOverlay').classList.add('hidden');
        document.getElementById('submitButton').innerText = 'Login';
        document.getElementById('error').innerText = 'An unexpected error occurred.';
        document.getElementById('error').classList.remove('hidden');
      });
    });
  </script>
</body>
</html>
