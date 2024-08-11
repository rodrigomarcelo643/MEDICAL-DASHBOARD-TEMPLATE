<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Admin</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="admin-container">
    <form id="createAdminForm" class="admin-form">
      <h2>Create Admin Account</h2>
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
      <button type="submit">Create Admin</button>
    </form>
    <div id="createMessage" class="message"></div>
  </div>
  <script src="../JS/create_admin.js"></script>
</body>
</html>
