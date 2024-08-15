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