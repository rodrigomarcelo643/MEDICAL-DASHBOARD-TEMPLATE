function goBack() {
  showSection("dashboard");
}
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("hidden");
}

function showSection(sectionId) {
  const sections = document.querySelectorAll(".section");
  const sidebarItems = document.querySelectorAll(".sidebar-item");

  showLoadingSpinner();

  const loadTime = getEstimatedLoadTime();

  setTimeout(() => {
    sections.forEach((section) => {
      // Add hidden class to trigger fade-out effect
      section.classList.add("hidden");
    });

    sidebarItems.forEach((item) =>
      item.classList.remove("bg-green-500", "text-black")
    );

    // Show the selected section with fade-in effect
    const sectionToShow = document.getElementById(sectionId);
    if (sectionToShow) {
      sectionToShow.classList.remove("hidden");
      sectionToShow.classList.add("show");
    }

    // Add active class to the corresponding sidebar item
    const activeSidebarItem = document.querySelector(
      `[data-section="${sectionId}"]`
    );
    if (activeSidebarItem) {
      activeSidebarItem.classList.add("bg-green-500", "text-black");
    }

    // Hide loading spinner after content is loaded
    hideLoadingSpinner();
  }, loadTime); // Use estimated load time based on network conditions
}

// Function to get estimated load time based on network conditions
function getEstimatedLoadTime() {
  // You can use real network conditions here if needed
  const connectionType = navigator.connection
    ? navigator.connection.effectiveType
    : "4g";
  switch (connectionType) {
    case "slow-2g":
      return 2000; // 2 seconds for slow 2G
    case "2g":
      return 1500; // 1.5 seconds for 2G
    case "3g":
      return 1000; // 1 second for 3G
    case "4g":
      return 500; // 0.5 seconds for 4G
    default:
      return 500; // Default to 0.5 seconds if unknown
  }
}

// Function to show the loading spinner
function showLoadingSpinner() {
  document.getElementById("loadingSpinner").classList.add("show");
}

// Function to hide the loading spinner
function hideLoadingSpinner() {
  document.getElementById("loadingSpinner").classList.remove("show");
}

// Function to toggle the dropdown menu
function toggleDropdown() {
  const submenu = document.getElementById("submenu");
  const arrow = document.getElementById("arrow");
  submenu.classList.toggle("hidden");
  arrow.classList.toggle("rotate-0");
}

// Function to show the logout confirmation modal
function showModal() {
  document.getElementById("logoutModal").classList.add("show");
}

// Function to hide the logout confirmation modal
function hideModal() {
  document.getElementById("logoutModal").classList.remove("show");
}

// Function to handle the logout action
function performLogout() {
  // Show loading spinner before logout
  showLoadingSpinner();

  const logoutTime = getEstimatedLoadTime();

  setTimeout(() => {
    // Implement your logout logic here
    window.location.replace("L.php");

    // Hide modal and spinner after logout
    hideModal();
    hideLoadingSpinner();
  }, logoutTime); // Use estimated logout time based on network conditions
}

// Function to check screen size and adjust sidebar visibility
function checkScreenSize() {
  const sidebar = document.getElementById("sidebar");
  if (window.innerWidth >= 1024) {
    sidebar.classList.remove("hidden");
  } else {
    sidebar.classList.add("hidden");
  }
}

// Function to initialize the page
function initialize() {
  showSection("dashboard"); // Show the default section
  checkScreenSize();
}

// Attach event listeners
window.addEventListener("resize", checkScreenSize);
window.addEventListener("load", initialize);

// Attach the modal to the logout button
document
  .querySelector('.sidebar-item[data-section="logout"]')
  .addEventListener("click", showModal);
