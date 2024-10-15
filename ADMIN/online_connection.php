      <!-- Container for Doughnut Chart and Statistics -->
      <div class="flex flex-chart" style="border-bottom:3px solid green; border-top:3px solid green; height:auto">
          <div class="flex overflow-hidden"
              style="margin-top:35px; overflow:hidden; z-index:1; margin-left:100px;border-right: 3px solid green;margin-bottom:25px; padding:25px;">
              <div style="display:block;overflow:hidden;font-weight:bold;letter-spacing:.5px;color:green">
                  <h1>Overall Members Population</h1>
                  <?php include 'membership_chart.php'?>

                  <canvas class="overall" id="membershipPieChart"></canvas>
              </div>
          </div>
      </div>

      <script>
// Ensure that membershipData is available from the included PHP
if (typeof membershipData !== 'undefined') {
    // Prepare data for the chart
    const labels = Object.keys(membershipData);
    const data = Object.values(membershipData);

    // Create the chart
    const ctx = document.getElementById('membershipPieChart').getContext('2d');
    const membershipPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            return `${label}: ${value} members`;
                        }
                    }
                }
            }
        }
    });
} else {
    console.error('No membership data available!');
}
      </script>