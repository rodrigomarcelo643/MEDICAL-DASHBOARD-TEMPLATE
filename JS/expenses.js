function backHistory() {
  document.getElementById("infoBox").classList.remove("hidden");
  document.getElementById("expenseFormContainer").classList.add("hidden");
}

document.addEventListener("DOMContentLoaded", (event) => {
  const showFormButton = document.getElementById("showFormButton");

  //===========Form Container
  const expenseFormContainer = document.getElementById("expenseFormContainer");
  const infoBox = document.getElementById("infoBox");
  const chartContainer = document.getElementById("chartContainer");

  showFormButton.addEventListener("click", () => {
    infoBox.classList.add("hidden");
    expenseFormContainer.classList.remove("hidden");
    chartContainer.classList.remove("hidden");
  });

  document
    .getElementById("expenseForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      var formData = new FormData(this);

      fetch("../p/add_expense.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((data) => {
          ShowSuccessExpenses();
          setTimeout(function () {
            HideSuccessExpenses();
          }, 4000);
          this.reset();
          loadChart(); //============= Reload the chart after adding an expense
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    });

  let chart = null; // ==================Store the chart instance globally

  function loadChart() {
    fetch("../p/get_expenses.php")
      .then((response) => response.json())
      .then((data) => {
        console.log(data); // Debug: Check data format

        // Ensure data is in the expected format
        if (Array.isArray(data)) {
          var labels = data.map((item) => item.date);
          var amounts = data.map((item) => parseFloat(item.total_amount));

          // Add timestamp to the chart
          var lastUpdated = new Date().toLocaleString();

          var ctx = document
            .getElementById("chartContainer-expenses")
            .getContext("2d");

          if (chart) {
            // If chart instance exists, update it
            chart.data.labels = labels;
            chart.data.datasets[0].data = amounts;
            chart.options.plugins.title.text = `Last Updated: ${lastUpdated}`;
            chart.update();
          } else {
            // Create a new chart instance
            chart = new Chart(ctx, {
              type: "line",
              data: {
                labels: labels,
                datasets: [
                  {
                    label: "Daily Expenses",
                    data: amounts,
                    borderColor: "green",
                    backgroundColor: "rgba(144, 238, 144, 0.4)",
                    fill: true,
                    tension: 0.3,
                  },
                ],
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                  y: {
                    beginAtZero: true,
                  },
                },
                plugins: {
                  title: {
                    display: true,
                    text: `Last Updated: ${lastUpdated}`, // Display the last updated time
                  },
                  tooltip: {
                    callbacks: {
                      label: function (context) {
                        let label = context.dataset.label || "";
                        if (label) {
                          label += ": ";
                        }
                        if (context.parsed.y !== null) {
                          label += context.parsed.y;
                        }
                        return label;
                      },
                    },
                  },
                  legend: {
                    display: true,
                  },
                },
                layout: {
                  padding: 20,
                },
              },
            });
          }
        } else {
          console.error("Unexpected data format:", data);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }

  // Load chart on page load
  loadChart();
  console.log("chart Updated");
});

function ShowSuccessExpenses() {
  const successExpense = document.getElementById("successModalAddedExpenses");
  successExpense.style.display = "block";
}

function HideSuccessExpenses() {
  const successExpense = document.getElementById("successModalAddedExpenses");
  successExpense.style.display = "none";
}

document.addEventListener("DOMContentLoaded", function () {
  fetch("all_expenses.php")
    .then((response) => response.json())
    .then((data) => {
      const totalExpenses = data.total_amount;
      const formattedAmount = parseFloat(totalExpenses).toFixed(2);
      // Set the data-end-value attribute for the countUp function
      const element = document.getElementById("total-expenses-all");
      element.setAttribute("data-end-value", formattedAmount);
      countUp("total-expenses-all", 2000); // Adjust duration as needed
    })
    .catch((error) => {
      console.error("Error fetching total expenses:", error);
    });
});

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

//=================GET ALL EXPENSES COUNT UP OF THE FOLLOWING ===================

//========FETCCH TODAY MEMBERS ================
document.addEventListener("DOMContentLoaded", function () {
  // get the total members
  fetch("../p/fetch_today_members.php")
    .then((response) => response.json())
    .then((data) => {
      const memberCount = data.length > 0 ? data[0].count : 0;

      // Initialize the progress circle for total members
      const progressBar = new ProgressBar.Circle("#progress-circle", {
        color: "green",
        strokeWidth: 30,
        trailWidth: 30,
        easing: "easeInOut",
        duration: 1400,
        text: {
          value: "0%",
        },
        from: { color: "#4caf50", width: 30 },
        to: { color: "#4caf50", width: 30 },
        step: function (state, circle) {
          circle.path.setAttribute("stroke", state.color);
          circle.path.setAttribute("stroke-width", state.width);
          const value = Math.round(circle.value() * 100);
          circle.setText(value + "+");
        },
      });

      // Delay the progress bar animation
      setTimeout(() => {
        progressBar.animate(memberCount / 100); // Adjust 100 as needed
      }, 500); // 500ms delay

      // Counting animation for total members
      const countUp = new CountUp("progress-text", 0, memberCount, 0, 2);
      countUp.start();
    })
    .catch((error) => {
      console.error("Error fetching member data:", error);
    });

  // Fetch and display today's renewed members
  fetch("renewed_select.php") // Ensure this PHP script returns today's renewed members count
    .then((response) => response.json())
    .then((data) => {
      if (data.today_sales !== undefined) {
        const todayRenewedMembers = data.today_sales;

        // Initialize the progress circle for today's renewed members intialzing for the new members
        // with the following terms of the progres chart
        const todayRenewedProgressBar = new ProgressBar.Circle(
          "#today-renewed-progress-circle",
          {
            color: "green",
            strokeWidth: 30,
            trailWidth: 30,
            easing: "easeInOut",
            duration: 1400,
            text: {
              value: "0%",
            },
            from: { color: "#4caf50", width: 30 },
            to: { color: "#4caf50", width: 30 },
            step: function (state, circle) {
              circle.path.setAttribute("stroke", state.color);
              circle.path.setAttribute("stroke-width", state.width);
              const value = Math.round(circle.value() * 100);
              circle.setText(value + "+");
            },
          }
        );

        // Delay the progress bar animation
        setTimeout(() => {
          todayRenewedProgressBar.animate(todayRenewedMembers / 100); // Adjust 100 as needed
        }, 1000); // 500ms delay

        // Counting animation for today's renewed members
        const countUpRenewed = new CountUp(
          "today-renewed-progress-text",
          0,
          todayRenewedMembers,
          0,
          2
        );
        countUpRenewed.start();
      } else {
        console.error("Error fetching data:", data.error);
      }
    });
  // Fetch expenses data
  fetch("../p/progress_expenses.php")
    .then((response) => response.json())
    .then((data) => {
      const todayExpenses = data.today.total_amount || 0;
      const yesterdayExpenses = data.yesterday.total_amount || 0;

      // Calculate percentage
      const percentageExpenses =
        yesterdayExpenses > 0 ? todayExpenses / yesterdayExpenses : 1; // Avoid division by zero

      // Initialize the progress circle for expenses
      const expensesProgressBar = new ProgressBar.Circle(
        "#expenses-progress-circle",
        {
          color: "red",
          strokeWidth: 30,
          trailWidth: 30,
          easing: "easeInOut",
          duration: 1400,
          text: {
            value: "0%",
          },
          from: { color: "#f44336", width: 30 },
          to: { color: "#f44336", width: 30 },
          step: function (state, circle) {
            circle.path.setAttribute("stroke", state.color);
            circle.path.setAttribute("stroke-width", state.width);
            const value = Math.round(circle.value() * 100);
            circle.setText(value + "%");
          },
        }
      );

      // Delay the progress bar animation
      setTimeout(() => {
        expensesProgressBar.animate(percentageExpenses); // Percentage relative to yesterday
      }, 1500); // 1500ms delay

      // Always animate to 95% to leave space
      setTimeout(() => {
        expensesProgressBar.animate(0.95, {
          // Animate to 95% instead of 100%
          duration: 2000, // Adjust duration for the color animation
          easing: "easeInOut",
        });
      }, 3000); // Start the color animation after initial load

      // Counting animation for expenses
      const countUpExpenses = new CountUp(
        "expenses-progress-text",
        0,
        todayExpenses,
        0,
        2
      );
      countUpExpenses.start();
    })
    .catch((error) => console.error("Fetch error:", error));
});

//================CALENDAR JS ===========

document.addEventListener("DOMContentLoaded", () => {
  const today = new Date();
  const dayOfWeek = today.getDay(); // 0 (Sunday) to 6 (Saturday)

  // Update the last updated date
  const lastUpdatedElement = document.getElementById("lastUpdated");
  lastUpdatedElement.textContent = `${
    today.getMonth() + 1
  }/${today.getDate()}/${today.getFullYear()}`;

  // Reference to the calendar carousel
  const calendarCarousel = document.querySelector(".calendar-carousel");

  // Days of the week in order, where index 0 is Sunday
  const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

  // Clear any existing calendar days
  calendarCarousel.innerHTML = "";

  // Create and append day elements
  daysOfWeek.forEach((day, index) => {
    const dayElement = document.createElement("div");
    dayElement.classList.add("calendar-day");
    dayElement.textContent = day;

    // Set the active class for the current day
    if (index === dayOfWeek) {
      dayElement.classList.add("active");
    }

    calendarCarousel.appendChild(dayElement);
  });
});
