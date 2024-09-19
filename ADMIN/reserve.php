  <!-- Statistics Breakdown -->
  <div class="statistics-container flex-1">
      <div class="bg-gradient-to-r from-teal-400 to-blue-500 p-6 rounded-lg shadow-lg">
          <div class="bg-white p-4 rounded-lg shadow-md">
              <?php
                    include 'fetch_members.php';

                    $totals = [
                        'daily-basic' => 0,
                        'daily-pro' => 0,
                        'monthly-basic' => 0,
                        'monthly-pro' => 0,
                    ];

                    foreach ($members as $member) {
                        $type = $member['membership_type'];
                        if (isset($totals[$type])) {
                            $totals[$type] += $member['total_cost'];
                        }
                    }

                    // Overall Sales======================
                    $overallTotal = array_sum($totals);
                    ?>
              <div class="mb-6 p-4 border-b border-gray-200">
                  <table class="custom-table" style="margin-left:-15px;">
                      <thead>
                          <tr>
                              <th class="header-cell">Membership</th>
                              <th class="header-cell">Amount</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td class="text-lg text-gray-800">Daily Basic</td>
                              <td class="flex">
                                  <img src="../Assets/pesos.png" class="currency-icon">
                                  <span
                                      class="ml-2 text-green-600"><?php echo number_format($totals['daily-basic'], 2); ?></span>
                              </td>
                          </tr>
                          <tr>
                              <td class="text-lg text-gray-800">Daily Pro</td>
                              <td class="flex">
                                  <img src="../Assets/pesos.png" class="currency-icon">
                                  <span
                                      class="ml-2 text-green-600"><?php echo number_format($totals['daily-pro'], 2); ?></span>
                              </td>
                          </tr>
                          <tr>
                              <td class="text-lg text-gray-800">Monthly Basic</td>
                              <td class="flex">
                                  <img src="../Assets/pesos.png" class="currency-icon">
                                  <span
                                      class="ml-2 text-green-600"><?php echo number_format($totals['monthly-basic'], 2); ?></span>
                              </td>
                          </tr>
                          <tr>
                              <td class="text-lg text-gray-800">Monthly Pro</td>
                              <td class="flex">
                                  <img src="../Assets/pesos.png" class="currency-icon">
                                  <span
                                      class="ml-2 text-green-600"><?php echo number_format($totals['monthly-pro'], 2); ?></span>
                              </td>
                          </tr>
                          <tr style="border-bottom:3px solid transparent">
                              <td style="border-bottom:3px solid transparent">Total</td>
                          </tr>
                          <tr class="total-row" style="border-bottom:3px solid transparent">
                              <td class="flex" style="border-bottom:3px solid transparent;">
                                  <img src="../Assets/pesos.png" class="currency-icon">
                                  <span
                                      class="ml-2 text-green-600 text-2xl font-bold"><?php echo number_format($overallTotal, 2); ?></span>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>