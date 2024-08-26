const modal = document.getElementById("notification-modal");
const notifIcon = document.getElementById("notif-icon");
const closeNotificationButton = document.getElementById("close-notification");

notifIcon.addEventListener("click", function () {
  modal.classList.remove("hidden");
});

// Close the notification modal
closeNotificationButton.addEventListener("click", function () {
  modal.classList.add("hidden");
});

window.addEventListener("click", function (event) {
  if (event.target === modal) {
    modal.classList.add("hidden");
  }
});
function Logout() {
  hideModal();
  showLoadingSpinner();

  const logoutTime = getEstimatedLoadTime();

  setTimeout(() => {
    fetch("logout.php", {
      method: "POST",
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          window.location.replace("L.php");
        } else {
          console.error("Logout failed.");
        }
      })
      .catch((error) => console.error("Error during logout:", error))
      .finally(() => hideLoadingSpinner());
  }, logoutTime);
}
