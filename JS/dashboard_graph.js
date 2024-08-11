document.addEventListener("DOMContentLoaded", function () {
  const patientsCtx = document.getElementById("patientsChart").getContext("2d");
  const healthcareCtx = document
    .getElementById("healthcareChart")
    .getContext("2d");

  // Patients Overview Chart
  new Chart(patientsCtx, {
    type: "line",
    data: {
      labels: ["Week 1", "Week 2", "Week 3", "Week 4"],
      datasets: [
        {
          label: "Daily Patients",
          data: [120, 150, 180, 210],
          borderColor: "rgba(34, 197, 94, 1)", // Green border
          backgroundColor: "rgba(34, 197, 94, 0.2)", // Light green fill
          borderWidth: 2,
          fill: false,
        },
        {
          label: "Weekly Patients",
          data: [800, 950, 1100, 1300],
          borderColor: "rgba(255, 87, 34, 1)", // Dark orange border
          backgroundColor: "rgba(255, 159, 64, 0.2)", // Light orange fill
          borderWidth: 2,
          fill: false,
        },
        {
          label: "Yearly Patients",
          data: [10000, 12000, 15000, 17000],
          borderColor: "rgba(54, 162, 235, 1)", // Blue border
          backgroundColor: "rgba(54, 162, 235, 0.2)", // Light blue fill
          borderWidth: 2,
          fill: false,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "top",
          labels: {
            color: "rgba(55, 65, 81, 1)", // Dark gray text for legend
            font: {
              weight: "bold", // Bold text for legend labels
            },
          },
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
      },
      scales: {
        x: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Time Period",
            color: "rgba(55, 65, 81, 1)", // Dark gray text for x-axis title
          },
          ticks: {
            color: "rgba(55, 65, 81, 0.8)", // Dark gray text for x-axis ticks
          },
        },
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Number of Patients",
            color: "rgba(55, 65, 81, 1)", // Dark gray text for y-axis title
          },
          ticks: {
            color: "rgba(55, 65, 81, 0.8)", // Dark gray text for y-axis ticks
          },
        },
      },
    },
  });

  // Healthcare Population Overview Chart
  new Chart(healthcareCtx, {
    type: "bar",
    data: {
      labels: ["Doctors", "Nurses", "Hospitals", "Midwives"],
      datasets: [
        {
          label: "Healthcare Population Count",
          data: [50, 80, 30, 10],
          backgroundColor: [
            "rgba(255, 99, 132, 0.5)", // Light red for Doctors
            "rgba(54, 162, 235, 0.5)", // Light blue for Nurses
            "rgba(255, 206, 86, 0.5)", // Light yellow for Hospitals
            "rgba(75, 192, 192, 0.5)", // Light teal for Midwives
          ],
          borderColor: [
            "rgba(255, 87, 34, 1)", // Dark orange for all
            "rgba(255, 87, 34, 1)", // Dark orange for all
            "rgba(255, 87, 34, 1)", // Dark orange for all
            "rgba(255, 87, 34, 1)", // Dark orange for all
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "top",
          labels: {
            color: "rgba(55, 65, 81, 1)", // Dark gray text for legend
            font: {
              weight: "bold", // Bold text for legend labels
            },
          },
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
      },
      scales: {
        x: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Healthcare Categories",
            color: "rgba(55, 65, 81, 1)", // Dark gray text for x-axis title
          },
          ticks: {
            color: "rgba(55, 65, 81, 0.8)", // Dark gray text for x-axis ticks
          },
        },
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Count",
            color: "rgba(55, 65, 81, 1)", // Dark gray text for y-axis title
          },
          ticks: {
            color: "rgba(55, 65, 81, 0.8)", // Dark gray text for y-axis ticks
          },
        },
      },
    },
  });
});
function searchDoctors() {
  const searchInput = document.getElementById("searchInput").value;
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "doctors.php?query=" + encodeURIComponent(searchInput), true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      document.getElementById("doctorsTableBody").innerHTML = xhr.responseText;
    } else {
      console.error("Error fetching data");
    }
  };
  xhr.send();
}
