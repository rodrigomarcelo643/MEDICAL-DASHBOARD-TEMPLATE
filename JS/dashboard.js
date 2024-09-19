// ==========================Function to show a specific section
function showSection(sectionId, initialLoad = false) {
  const sections = document.querySelectorAll(".section");
  const sidebarItems = document.querySelectorAll(".sidebar-item");

  showLoadingSpinner();

  const loadTime = getEstimatedLoadTime();

  setTimeout(() => {
    sections.forEach((section) => {
      section.classList.add("hidden");
    });

    sidebarItems.forEach((item) =>
      item.classList.remove("bg-green-500", "text-black")
    );

    const sectionToShow = document.getElementById(sectionId);
    if (sectionToShow) {
      sectionToShow.classList.remove("hidden");
      sectionToShow.classList.add("show");
    }

    const activeSidebarItem = document.querySelector(
      `[data-section="${sectionId}"]`
    );
    if (activeSidebarItem) {
      activeSidebarItem.classList.add("bg-green-500", "text-black");
    }

    hideLoadingSpinner();
  }, loadTime);
}

// ==========================Function to get estimated load time based on network conditions
function getEstimatedLoadTime() {
  const connectionType = navigator.connection
    ? navigator.connection.effectiveType
    : "4g";
  switch (connectionType) {
    case "slow-2g":
      return 3000;
    case "2g":
      return 2500;
    case "3g":
      return 1500;
    case "4g":
      return 1000;
    default:
      return 700;
  }
}

// =================================Function to show the loading spinner
function showLoadingSpinner() {
  document.getElementById("loadingSpinner").classList.add("show");
}

// ========================Function to hide the loading spinner
function hideLoadingSpinner() {
  document.getElementById("loadingSpinner").classList.remove("show");
}

// ============================Function to toggle the sidebar visibility
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("hidden");
}

// ================================Function to toggle the dropdown menu
function toggleDropdown() {
  const dropdown = document.getElementById("dropdown-chevron");
  const chevron = document.getElementById("chevron");
  const isHidden = dropdown.classList.contains("hidden");

  if (isHidden) {
    dropdown.classList.remove("hidden");
    chevron.classList.add("rotate-180");
  } else {
    dropdown.classList.add("hidden");
    chevron.classList.remove("rotate-180");
  }
}

//======================================== Function to select an option from the dropdown
function selectOption(option) {
  const selectedOption = document.getElementById("selected-option");
  selectedOption.textContent = option;
  toggleDropdown();
}

//================================== Function to update the file name display
function updateFileName() {
  const fileInput = document.getElementById("UploadFile");
  const fileNameSpan = document.getElementById("fileName");
  if (fileInput.files.length > 0) {
    fileNameSpan.textContent = fileInput.files[0].name;
  } else {
    fileNameSpan.textContent = "Choose File";
  }
}

// ===================================Function to show the logout confirmation modal
function showModalLogout() {
  document.getElementById("logoutModal").classList.add("show");
}

//============================================= Function to hide the logout confirmation modal
function hideModalLogout() {
  document.getElementById("logoutModal").classList.remove("show");
}

// =======================================Function to perform logout
function performLogout() {
  hideModalLogout();
  showLoadingSpinner();

  const logoutTime = getEstimatedLoadTime();

  setTimeout(() => {
    fetch("../p/logout.php", {
      method: "POST",
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          window.location.replace("../p/L.php");
          console.log("Success Logged Out");
        } else {
          console.error("Logout failed:", data.message || "Unknown error");
        }
      })
      .catch((error) => console.error("Error during logout:", error))
      .finally(() => hideLoadingSpinner());
  }, logoutTime);
}

// ===================================Function to check screen size and adjust sidebar visibility
function checkScreenSize() {
  const sidebar = document.getElementById("sidebar");
  if (window.innerWidth >= 1024) {
    sidebar.classList.remove("hidden");
  } else {
    sidebar.classList.add("hidden");
  }
}

// ======================================Function to initialize the page
function initialize() {
  //========= Get the section from the URL hash, or default to "dashboard"
  const hash = window.location.hash.substring(1);
  const defaultSection = "dashboard"; // Define your default section here
  const activeSection = hash || defaultSection;
  showSection(activeSection);

  checkScreenSize();
}

// ======================================Attach event listeners
window.addEventListener("resize", checkScreenSize);
window.addEventListener("load", initialize);

// ==========================Function to handle sidebar item clicks
function handleSidebarItemClick(event) {
  const sectionId = event.target.getAttribute("data-section");
  if (sectionId) {
    //=================== Update URL hash and show the section
    window.location.hash = sectionId;
    showSection(sectionId);
  }
}

//=============== Add event listeners to sidebar items
document.querySelectorAll(".sidebar-item").forEach((item) => {
  item.addEventListener("click", handleSidebarItemClick);
});
//==================FETCHING FOR THE FIXED DAILY EXPENSES

document.addEventListener("DOMContentLoaded", function () {
  fetch("../p/track_expenses.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.total_amount !== null) {
        const totalAmount = parseFloat(data.total_amount).toFixed(2);

        document.getElementById("total-expenses").textContent = totalAmount;

        const percentageChange = calculatePercentageChange(totalAmount);
        const percentageText = document.getElementById("percentage-text");
        const percentageBorder = document.getElementById("percentage-border");
        const percentageSign = document.getElementById("percentage-sign");

        const THRESHOLD_PERCENTAGE = 25;

        if (percentageChange > THRESHOLD_PERCENTAGE) {
          percentageBorder.classList.remove("green");
          percentageBorder.classList.add("red");
          percentageSign.style.color = "red";
          percentageText.textContent = `- ${percentageChange}%`;
        } else {
          percentageBorder.classList.remove("red");
          percentageBorder.classList.add("green");
          percentageSign.style.color = "green";
          percentageText.textContent = `+ ${percentageChange}%`;
        }
      } else {
        document.getElementById("total-expenses").textContent = "0.00";
        const percentageText = document.getElementById("percentage-text");
        const percentageBorder = document.getElementById("percentage-border");
        const percentageSign = document.getElementById("percentage-sign");

        percentageBorder.classList.remove("red");
        percentageBorder.classList.remove("green");
        percentageText.textContent = "No Data";
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
});

function calculatePercentageChange(totalAmount) {
  return totalAmount > 1000 ? 30 : 10;
}

//=============================================Counting Animation for Each Data===
function countUp(elementId, duration) {
  const element = document.getElementById(elementId);
  const endValue = parseFloat(element.getAttribute("data-end-value"));
  const startValue = 0;
  const startTime = performance.now();

  function updateCount(timestamp) {
    const elapsed = timestamp - startTime;
    const progress = Math.min(elapsed / duration, 1);
    const currentValue = Math.floor(
      progress * (endValue - startValue) + startValue
    );
    element.textContent = currentValue.toLocaleString("en-US", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });

    if (progress < 1) {
      requestAnimationFrame(updateCount);
    }
  }

  requestAnimationFrame(updateCount);
}

async function fetchData() {
  try {
    const response = await fetch("../p/track_expenses.php");
    const data = await response.json();

    document
      .getElementById("total-expenses")
      .setAttribute("data-end-value", data.total_amount || "0.00");
    countUp("dailySales", 2000);
    countUp("total-expenses", 2000);
    countUp("dailyDebt", 2000);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}

window.onload = fetchData;
