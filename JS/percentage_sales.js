// JavaScript to dynamically set the border color based on percentage
document.addEventListener("DOMContentLoaded", function () {
  const percentage = 25; // Set this to your dynamic percentage
  const percentageBorder = document.getElementById("percentage-border");
  const percentageText = document.getElementById("percentage-text");

  // Set the text
  percentageText.textContent = `+${percentage}%`;

  // Define color thresholds
  const getColorForPercentage = (percentage) => {
    if (percentage <= 25) return "border-yellow-400"; // Default color
    if (percentage <= 50) return "border-yellowgreen-400"; // Example for other thresholds
    if (percentage <= 75) return "border-green-400";
    return "border-green-600"; // High percentage color
  };

  // Set the border color based on percentage
  percentageBorder.classList.add(getColorForPercentage(percentage));
});
