// Utility function for sending requests
function sendRequest(
  url,
  method,
  data = null,
  successCallback = null,
  errorCallback = null
) {
  fetch(url, {
    method: method,
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: data,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success" && successCallback) {
        successCallback(data);
      } else if (errorCallback) {
        errorCallback(data);
      }
    })
    .catch((error) => {
      if (errorCallback) {
        errorCallback({
          status: "error",
          message: "Request failed: " + error.message,
        });
      }
    });
}

// Function to toggle action menu visibility
function toggleActionMenu(event) {
  event.preventDefault();
  event.stopPropagation();

  const button = event.currentTarget;
  const actionMenu = button.nextElementSibling;

  if (!actionMenu || !actionMenu.classList.contains("action-menu")) {
    console.error("Action menu not found or is not a valid action menu.");
    return;
  }

  document.querySelectorAll(".action-menu").forEach((menu) => {
    if (menu !== actionMenu) {
      menu.classList.add("hidden");
    }
  });

  actionMenu.classList.toggle("hidden");
}

function handleActionClick(event) {
  const memberId = event.currentTarget
    .closest(".action-menu")
    .getAttribute("data-member-id");
  const action = event.currentTarget.getAttribute("data-action");

  if (action === "LogsOfMembersEdit") {
    const dataEdit = new URLSearchParams({}).toString();

    sendRequest(
      "../p/edit_member.php",
      "POST",
      dataEdit,
      () => {
        alert("Edit successful!");
        location.reload();
      },
      (error) => {
        alert("Edit failed: " + error.message);
      }
    );
  } else if (action === "delete") {
    if (confirm("Are you sure you want to delete this member?")) {
      const data = new URLSearchParams({ id: memberId }).toString();

      sendRequest(
        "../p/delete-member.php",
        "POST",
        data,
        (response) => {
          if (response.status === "success") {
            //=======Function to display the success deleted
            SuccessDeleteMember();
            setTimeout(function () {
              HideSuccessDeleteMember();
            }, 4000);
            //=======Function to display the success deleted
            document
              .querySelector(
                `.member-box .action-menu[data-member-id="${memberId}"]`
              )
              .closest(".member-box")
              .remove();
          } else {
            alert("Delete failed: " + response.message);
          }
        },
        (error) => {
          alert("Delete failed: " + error.message);
        }
      );
    }
  }
}
function SuccessDeleteMember() {
  const Renew = document.getElementById("successModalDeleteMember");
  Renew.style.display = "block";
}
function HideSuccessDeleteMember() {
  const Renew = document.getElementById("successModalDeleteMember");
  Renew.style.display = "none";
}
function attachActionButtonListeners() {
  document.querySelectorAll(".action-button").forEach((button) => {
    button.addEventListener("click", toggleActionMenu);
  });

  document
    .querySelectorAll(".edit-button, .delete-button")
    .forEach((button) => {
      button.addEventListener("click", handleActionClick);
    });
}

function setupSearch() {
  const searchInput = document.getElementById("search");
  const membersList = document.getElementById("membersList");

  async function fetchMembers(query) {
    try {
      const response = await fetch(
        `../p/search_members.php?search=${encodeURIComponent(query)}`
      );
      const members = await response.json();
      displayMembers(members);
    } catch (error) {
      console.error("Error fetching members:", error);
    }
  }

  function displayMembers(members) {
    membersList.innerHTML = ""; // Clear existing members

    const memberItemsMap = new Map();

    members.forEach((item) => {
      if (!memberItemsMap.has(item.member_id)) {
        memberItemsMap.set(item.member_id, []);
      }
      const memberItems = memberItemsMap.get(item.member_id);
      const existingItem = memberItems.find(
        (i) => i.product_name === item.product_name
      );

      if (existingItem) {
        existingItem.total_quantity = (
          parseInt(existingItem.total_quantity) + parseInt(item.total_quantity)
        ).toString();
        existingItem.total_price = (
          parseFloat(existingItem.total_price) + parseFloat(item.total_price)
        ).toFixed(2);
      } else {
        memberItems.push({
          product_name: item.product_name,
          total_quantity: item.total_quantity,
          price: item.price,
          total_price: item.total_price,
          stock: item.stock,
        });
      }
    });

    const uniqueMembers = new Set();

    members.forEach((row) => {
      if (!uniqueMembers.has(row.id)) {
        uniqueMembers.add(row.id);
        const memberBox = document.createElement("div");
        memberBox.className = "member-box";
        memberBox.innerHTML = generateMemberBoxHTML(row, memberItemsMap);
        membersList.appendChild(memberBox);
      }
    });

    attachActionButtonListeners();
  }

  function generateMemberBoxHTML(row, memberItemsMap) {
    const itemsHtml = (memberItemsMap.get(row.id) || [])
      .map(
        (item) =>
          `<li>${highlightText(
            item.product_name,
            searchInput.value
          )} - ${formatNumber(item.total_quantity)} units, $${formatNumber(
            item.total_price
          )} ${
            item.stock <= 0
              ? '<span class="out-of-stock">Out of Stock</span>'
              : ""
          }</li>`
      )
      .join("");

    return `
      <div class="member-header">
        <img src="../Assets/profile_default.png" class="profile-pic" alt="Profile Picture" />
        <div class="name-status">
          <div class="name">${highlightText(
            row.first_name + " " + row.last_name,
            searchInput.value
          )}</div>
          <div class="status-container">
            ${
              row.paid_status === "not paid"
                ? `<div class="status not-paid flex"><img src="../Assets/not-paid.png"><span>Not Paid</span></div>`
                : `<div class="status paid flex"><img src="../Assets/paid.png"><span>Paid</span></div>`
            }
            <button class="action-button" style="margin-top:-16px;">
              <img src="../Assets/action.png" alt="Actions" style="width:45px;height:45px;">
            </button>
            <div class="action-menu hidden" data-member-id="${row.id}">
              <button class="delete-button button1 "  style='background-color:red !important'data-action="delete">
                <svg viewBox="0 0 448 512" class="svgIcon">
                  <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="membership-data">
        <p class="membership-text">Membership</p>
        <div class="flex m-flex">
          <span class="membership-type-text">${highlightText(
            row.membership_type,
            searchInput.value
          )}</span>
          <div class="flex">
            <img src="../Assets/pesos.png" style="width:30px;height:30px;" />
            <span style='margin-top:5px;' id="membership_cost_${
              row.id
            }">${formatNumber(row.total_cost)}.00</span>
          </div>
        </div>
      </div>
   <div class="action-buttons flex justify-between mt-4">
        <button style='background-color: #009B7B;color:white;' class="add-item-button bg-orange-500 text-white py-2 px-4 rounded hover:bg-orange-600" onclick="ViewItems('${
          row.id
        }')">View Items</button>
        <button style='background-color: #009B7B;color:white;' class="add-item-button bg-orange-500 text-white py-2 px-4 rounded hover:bg-orange-600" onclick="ShowItems('${
          row.id
        }')">Add More Item</button>
    </div>
      <p class="item-text">Total</p>
      <div class="total-cost"'">
        <div class="cost">
          <div class="flex">
            <img src="../Assets/pesos.png" style="width:30px;height:30px;margin-right:8px;" />
            <span style='margin-top:4px;' id="total_${row.id}">${formatNumber(
      row.total_cost
    )}.00</span>
          </div>
        </div>
        <div class="payment-actions">
          ${
            row.paid_status === "not paid"
              ? `<button class="mark-paid-button">Mark as Paid</button>`
              : `<button class="mark-unpaid-button">Mark as Unpaid</button>`
          }
        </div>
      </div>
    `;
  }

  function formatNumber(number) {
    return Number(number).toLocaleString(); // Real-time comma separation
  }

  searchInput.addEventListener("input", function () {
    fetchMembers(this.value);
  });

  fetchMembers(""); // Initialize with no search query
}

// Modify the existing ViewItems function
async function ViewItems(memberId) {
  const modal = document.getElementById("cartItemsModal");
  const modalMemberName = document.getElementById("modalMemberName");
  const cartItemsList = document.getElementById("cartItemsListView");

  // Clear the previous items
  cartItemsList.innerHTML = "";

  // Fetch the member's cart items
  try {
    const response = await fetch(
      `../p/get_cart_items.php?member_id=${memberId}`
    );
    const items = await response.json();

    // Set the member name in the modal
    modalMemberName.textContent =
      items.length > 0 ? `${items[0].member_name}` : "No items found";

    let grandTotal = 0; // Initialize grand total

    // Populate the list with cart items
    if (items.length > 0) {
      items.forEach((item) => {
        const listItem = document.createElement("li");
        listItem.textContent = `${item.product_name} - ${formatNumber(
          item.quantity
        )} units, ₱${formatNumber(item.total)}`;
        cartItemsList.appendChild(listItem);
        grandTotal += parseFloat(item.total); // Accumulate total as a number
      });

      // Display the grand total
      const totalItem = document.createElement("li");
      totalItem.innerHTML = `<strong>Grand Total: ₱${formatNumber(
        grandTotal
      )}</strong>`;
      cartItemsList.appendChild(totalItem);

      // Create a container for the payment buttons
      const paymentButtonContainer = document.createElement("div");
      paymentButtonContainer.className =
        "payment-button-container flex justify-between mt-4"; // Flexbox to align buttons

      // Check the payment status and create the appropriate buttons
      const paymentStatus = items[0].paid_status; // Assuming all items have the same paid status
      if (paymentStatus === "not paid") {
        const markPaidButton = document.createElement("button");
        markPaidButton.textContent = "Mark as Paid";
        markPaidButton.className = "mark-paid-button";
        markPaidButton.onclick = async () => {
          const result = await markAsPaid(memberId, items); // Pass items for insertion
          alert(result.message); // Notify user of the result
          if (result.success) {
            ViewItems(memberId); // Refresh the items after marking as paid
          }
        };
        paymentButtonContainer.appendChild(markPaidButton);
      } else {
        const markDebtButton = document.createElement("button");
        markDebtButton.textContent = "Mark as Debt";
        markDebtButton.className = "mark-debt-button";
        markDebtButton.onclick = async () => {
          const result = await markAsDebt(memberId, items); // Pass items for insertion
          alert(result.message); // Notify user of the result
          if (result.success) {
            ViewItems(memberId); // Refresh the items after marking as debt
          }
        };

        paymentButtonContainer.appendChild(markDebtButton);
      }

      // Create the opposite button
      const oppositeButton = document.createElement("button");
      oppositeButton.textContent =
        paymentStatus === "not paid" ? "Mark as Debt" : "Mark as Paid";
      oppositeButton.className =
        paymentStatus === "not paid" ? "mark-debt-button" : "mark-paid-button";
      oppositeButton.onclick = async () => {
        const result =
          paymentStatus === "not paid"
            ? await markAsDebt(memberId)
            : await markAsPaid(memberId, items); // Pass items for insertion
        alert(result.message); // Notify user of the result
        if (result.success) {
          ViewItems(memberId); // Refresh the items after the action
        }
      };
      paymentButtonContainer.appendChild(oppositeButton);

      cartItemsList.appendChild(paymentButtonContainer);
    } else {
      // Display a message if there are no items
      cartItemsList.innerHTML = "<li>No items found for this member.</li>";
    }

    // Display the modal
    modal.style.display = "block";
  } catch (error) {
    console.error("Error fetching cart items:", error);
  }
}

function formatNumber(number) {
  return Number(number).toLocaleString(); // Real-time comma separation
}

// Function to close the modal
function closeModal() {
  document.getElementById("cartItemsModal").style.display = "none";
}

// Close the modal when clicking outside of the modal content
window.onclick = function (event) {
  const modal = document.getElementById("cartItemsModal");
  if (event.target === modal) {
    closeModal();
  }
};

// Function to mark items as paid
async function markAsPaid(memberId, items) {
  try {
    const response = await fetch(
      `../p/mark_as_paid.php?member_id=${memberId}`, // Ensure this URL is correct
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(items), // Pass the items array
      }
    );

    // Log the status of the response
    console.log("Response Status:", response.status);

    // Check if the response is ok
    if (!response.ok) {
      const errorText = await response.text();
      console.error("Error Response Text:", errorText);
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const result = await response.json();

    // Log the result for debugging
    console.log("Response Result:", result);

    return result;
  } catch (error) {
    console.error("Error marking items as paid:", error.message);
    return {
      success: false,
      message: "An error occurred while marking as paid.",
      error: error.message,
    };
  }
}

// Function to mark items as debt
async function markAsDebt(memberId, items) {
  try {
    const response = await fetch(
      `../p/mark_as_debt.php?member_id=${memberId}`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(items), // Pass the items array in the body
      }
    );

    // Log the status of the response
    console.log("Response Status:", response.status);

    // Check if the response is ok
    if (!response.ok) {
      const errorText = await response.text();
      console.error("Error Response Text:", errorText);
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const result = await response.json();

    // Log the result for debugging
    console.log("Response Result:", result);

    return result;
  } catch (error) {
    console.error("Error marking items as debt:", error.message);
    return {
      success: false,
      message: "An error occurred while marking as debt.",
      error: error.message,
    };
  }
}

// Initialize search functionality
setupSearch();

function ShowItems(memberId) {
  currentMemberId = memberId;

  fetchMemberDetails(memberId, (firstName, lastName) => {
    currentMemberName = `${firstName} ${lastName}`;
    const modal = document.getElementById("AddItemModal");
    modal.classList.add("showing");
    modal.classList.remove("hiding");

    console.log("Member Name ====== " + firstName + " " + lastName);

    // Fetch products related to this member
    fetch(`../p/get_products.php?memberId=${memberId}`)
      .then((response) => response.json())
      .then((products) => {
        const productsList = document.getElementById("productsList"); // Assuming you have a list to show products
        productsList.innerHTML = "";

        products.forEach((product) => {
          const isOutOfStock = product.stock <= 0;

          const productHtml = `
            <div class="product-box" data-product-name="${
              product.product_name
            }">
              <div>${product.product_name} - P${product.price}</div>
              <div>Stock: ${
                product.stock <= 0 ? "Out of Stock" : product.stock
              }</div>
              <button 
                class="add-item-button" 
                onclick="addToCart(${product.id})" 
                ${
                  isOutOfStock
                    ? "disabled style='opacity: 0.5; cursor: not-allowed;'"
                    : ""
                }
              >
                ${isOutOfStock ? "Out of Stock" : "Add Item"}
              </button>
            </div>
          `;

          productsList.innerHTML += productHtml;
        });
      })
      .catch((error) => console.error("Error fetching products:", error));
  });
}

function HideItems() {
  const modal = document.getElementById("AddItemModal");
  modal.classList.add("hiding");
  modal.classList.remove("showing");
  currentMemberId = null;
  currentMemberName = "";
}

function fetchMemberDetails(memberId, callback) {
  fetch("../p/getMemberDetails.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({ memberId: memberId }).toString(),
  })
    .then((response) => response.json())
    .then((response) => {
      if (response.status === "success") {
        callback(response.first_name, response.last_name);
      } else {
        alert("Failed to fetch member information: " + response.message);
      }
    })
    .catch((error) => console.error("Fetch error:", error));
}

function setupModalEvents() {
  const modal = document.getElementById("AddItemModal");
  const modalContent = document.querySelector(".modal-content-add-item");

  modal.addEventListener("click", function (event) {
    if (event.target === modal) {
      HideItems();
      console.log(" Showed Items Being Hidden");
    }
  });

  if (modalContent) {
    modalContent.addEventListener("click", function (event) {
      event.stopPropagation();
    });
  }
}

document.addEventListener("DOMContentLoaded", function () {
  attachActionButtonListeners();
  setupSearch();
  setupModalEvents();
});

function updateItemQuantity(id, change) {
  const quantityInput = document.getElementById(`quantity_${id}`);
  let quantity = parseInt(quantityInput.value, 10);
  quantity = Math.max(1, quantity + change);
  quantityInput.value = quantity;

  const price = parseFloat(
    document.querySelector(`#totalItem_${id}`).dataset.price
  );
  const total = (quantity * price).toFixed(2);
  document.querySelector(`#totalItem_${id}`).innerText = total;
}

// Add to Cart Functionality
let cartItems = [];
let currentMemberId = null;
let currentMemberName = "";

// Function to add an item to the cart
function addToCart(id) {
  const quantityInput = document.getElementById(`quantity_${id}`);
  const totalItem = document.getElementById(`totalItem_${id}`);

  if (!quantityInput || !totalItem) {
    console.error(
      "Quantity input or total price element not found for ID:",
      id
    );
    return;
  }

  const quantity = parseInt(quantityInput.value, 10);
  const originalPrice = parseFloat(totalItem.getAttribute("data-price"));

  fetch(`../p/get_product.php?id=${id}`)
    .then((response) => response.json())
    .then((product) => {
      if (product && product.ProductName) {
        const productName = product.ProductName;
        const price = product.Price; // Keep original price
        const stock = product.Stock; // Assuming stock is included in the response
        const total = (quantity * price).toFixed(2);

        if (stock <= 0) {
          alert(`The product "${productName}" is out of stock.`);
          return; // Prevent adding to cart
        }

        if (quantity > stock) {
          alert(
            `Only ${stock} items of "${productName}" are available in stock.`
          );
          return; // Prevent adding to cart
        }

        // Check if product is already in the cart
        const existingItemIndex = cartItems.findIndex(
          (item) => item.product_id === id
        );

        if (existingItemIndex !== -1) {
          // Accumulate quantity if already in the cart
          cartItems[existingItemIndex].quantity += quantity;
          cartItems[existingItemIndex].total = (
            cartItems[existingItemIndex].quantity * price
          ).toFixed(2);
        } else {
          // Store new item in the cartItems array
          const cartItem = {
            product_id: id,
            product_name: productName,
            quantity: quantity,
            price: price, // Original price remains the same
            total: total,
          };
          cartItems.push(cartItem);
        }

        // Update the cart display
        updateCartDisplay();

        // Reset quantity input
        quantityInput.value = "1"; // Reset quantity

        // Reset the total price in the UI to the original price
        totalItem.innerText = originalPrice.toFixed(2);
      } else {
        console.error(`Product details not found for ID: ${id}`);
      }
    })
    .catch((error) => console.error("Error fetching product details:", error));
}

// Function to update the cart display
function updateCartDisplay() {
  const cartItemsList = document.getElementById("cartItemsList");
  const cartTotalElement = document.getElementById("cartTotal");
  const memberNameDisplay = document.getElementById("memberNameDisplay");
  const cartDisplay = document.getElementById("cartDisplay");

  // Clear previous cart display
  cartItemsList.innerHTML = "";

  // Set the member name in the cart display
  memberNameDisplay.innerText = currentMemberName; // Display member's name

  let currentTotal = 0;

  cartItems.forEach((cartItem, index) => {
    // Create new list item for the cart
    const listItem = document.createElement("li");
    listItem.innerHTML = `${cartItem.product_name} - Quantity: ${cartItem.quantity} - Total: P ${cartItem.total} 
    <button  style='background-color:transparent;margin-top:-10px;color:red;' onclick="removeCartItem(${index})" style="color: red;">&#10006;</button>`;
    cartItemsList.appendChild(listItem);

    // Update total
    currentTotal += parseFloat(cartItem.total);
  });

  // Update the cart total
  cartTotalElement.innerText = currentTotal.toFixed(2);

  // Show the cart display area
  cartDisplay.style.display = "block"; // Show cart display
}

// Function to remove a cart item
function removeCartItem(index) {
  cartItems.splice(index, 1); // Remove item from the array
  updateCartDisplay(); // Update cart display after removal
}

// Function to submit the cart
function submitCart() {
  if (cartItems.length === 0) {
    return;
  }

  // Prepare the data for submission
  const data = new URLSearchParams({
    member_id: currentMemberId,
    member_name: currentMemberName,
    items: JSON.stringify(cartItems), // Convert cartItems array to a JSON string
  });

  fetch("../p/add_to_cart.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: data.toString(),
  })
    .then((response) => {
      // Check if the response is OK
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then((result) => {
      if (result.status === "success") {
        // Use SweetAlert2 with custom check image
        Swal.fire({
          html: `<img src="../Assets/success_message.png" style="width: 50px; height: 50px; display: block; margin: 0 auto;" alt="Success Check"> Order added successfully!`,
          confirmButtonText: "OK",
        });

        // Deduct stock for each item in the cart (if needed)
        cartItems = []; // Clear the cart after successful submission
        document.getElementById("cartItemsList").innerHTML = ""; // Clear the displayed cart items
        document.getElementById("cartTotal").innerText = " P 0.00"; // Reset the total
        document.getElementById("cartDisplay").style.display = "none"; // Hide the cart display
      } else {
        Swal.fire({
          title: "Error!",
          text: `Failed to submit cart: ${result.message}`,
          icon: "error",
          confirmButtonText: "OK",
        });
      }
    })
    .catch((error) => {
      Swal.fire({
        title: "Error!",
        text: "Error submitting cart: " + error.message,
        icon: "error",
        confirmButtonText: "OK",
      });
    });
}

function filterProducts() {
  const input = document.getElementById("searchInput");
  const filter = input.value.toLowerCase();
  const products = document.querySelectorAll(".product-box");

  products.forEach((product) => {
    product.classList.remove("highlight");
    product.style.display = "";
  });

  let hasVisibleProduct = false;

  if (filter) {
    products.forEach((product) => {
      const productName = product
        .getAttribute("data-product-name")
        .toLowerCase();
      if (productName.indexOf(filter) > -1) {
        product.style.display = "";
        product.classList.add("highlight");
        hasVisibleProduct = true;
      } else {
        product.style.display = "none";
      }
    });
  }
}

// Function to highlight text in search results
function highlightText(text, query) {
  if (!query) return text;
  const regex = new RegExp(`(${query})`, "gi");
  return text.replace(regex, '<mark class="highlight">$1</mark>');
}

// Add event listener for product filter
document
  .getElementById("searchInput")
  .addEventListener("input", filterProducts);
