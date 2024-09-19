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
          stock: item.stock, // Add stock information here
        });
      }
    });

    const uniqueMembers = new Set();

    members.forEach((row) => {
      if (!uniqueMembers.has(row.id)) {
        uniqueMembers.add(row.id);

        const memberBox = document.createElement("div");
        memberBox.className = "member-box";

        // Constructing the items section HTML
        const itemsHtml = (memberItemsMap.get(row.id) || [])
          .map(
            (item) => `
            <li>
              ${highlightText(item.product_name, searchInput.value)}
              - ${item.total_quantity} units, 
              $${item.total_price}
              ${
                item.stock <= 0
                  ? '<span class="out-of-stock">Out of Stock</span>'
                  : ""
              }
            </li>
          `
          )
          .join("");

        memberBox.innerHTML = `
          <div class="member-header">
            <img src="../Assets/default-profile.png" class="profile-pic" alt="Profile Picture" />
            <div class="name-status">
              <div class="name">${highlightText(
                row.first_name + " " + row.last_name,
                searchInput.value
              )}</div>
              <div class="status-container">
                ${
                  row.paid_status === "not paid"
                    ? `<div class="status not-paid flex">
                        <img src="../Assets/not-paid.png">
                        <span>Not Paid</span>
                      </div>`
                    : `<div class="status paid flex">
                        <img src="../Assets/paid.png">
                        <span>Paid</span>
                      </div>`
                }
                <button class="action-button" style="margin-top:-16px;">
                  <img src="../Assets/action.png" alt="Actions" style="width:45px;height:45px;">
                </button>
                <div class="action-menu hidden" data-member-id="${row.id}">
                  <button class="delete-button button1" data-action="delete">
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
                <span id="membership_cost_${row.id}">${highlightText(
          row.total_cost,
          searchInput.value
        )}.00</span>
              </div>
            </div>
          </div>
          <div class="items-section">
            <div class="items-purchased">
              <p class="item-text">Items Purchased</p>
              <ul id="items_${row.id}">
                ${itemsHtml}
              </ul>
            </div>
            <button class="add-item-button" onclick="ShowItems('${
              row.id
            }')">Add Item</button>
          </div>
          <p class="item-text">Total</p>
          <div class="total-cost">
            <div class="cost">
              <div class="flex">
                <img src="../Assets/pesos.png" style="width:30px;height:30px;margin-right:8px;" />
                <span id="total_${row.id}">${highlightText(
          row.total_cost,
          searchInput.value
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

        membersList.appendChild(memberBox);
      }
    });

    attachActionButtonListeners();
  }

  function highlightText(text, query) {
    if (!query) return text;
    const regex = new RegExp(`(${query})`, "gi");
    return text.replace(regex, '<mark class="highlight">$1</mark>');
  }

  searchInput.addEventListener("input", function () {
    fetchMembers(this.value);
  });

  fetchMembers(""); // Initialize with no search query
}

setupSearch(); // Initialize search functionality

// Function to Show the Modal for Add Item
let currentMemberId = null;
let currentMemberName = "";

function ShowItems(memberId) {
  currentMemberId = memberId;
  //Debugging
  console.log("Current Member ID " + currentMemberId); //Member Id

  fetchMemberDetails(memberId, (firstName, lastName) => {
    currentMemberName = `${firstName} ${lastName}`;
    const modal = document.getElementById("AddItemModal");
    modal.classList.add("showing");
    modal.classList.remove("hiding");
    //Debugging
    console.log("Member Name ====== " + firstName + " " + lastName); //Member Name
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

function addToCart(id) {
  const quantityInput = document.getElementById(`quantity_${id}`);
  if (!quantityInput) {
    console.error("Quantity input not found for ID:", id);
    return;
  }

  const quantity = parseInt(quantityInput.value, 10);

  fetch(`../p/get_product.php?id=${id}`)
    .then((response) => response.json())
    .then((product) => {
      if (product && product.ProductName) {
        const productName = product.ProductName;
        const price = product.Price;
        const stock = product.Stock; // Assuming the stock is included in the response
        const total = (quantity * price).toFixed(2);

        if (stock <= 0) {
          // Show out of stock message
          const itemElement = document.getElementById(`item_${id}`);
          if (itemElement) {
            itemElement.innerHTML = `<span class="out-of-stock">Out of Stock</span>`;
          }
          alert(`The product "${productName}" is out of stock.`);
          return;
        }

        if (quantity > stock) {
          alert(
            `Only ${stock} items of "${productName}" are available in stock.`
          );
          return;
        }

        const data = new URLSearchParams({
          member_id: currentMemberId,
          product_id: id,
          product_name: productName,
          quantity: quantity,
          price: price,
          total: total,
          member_name: currentMemberName,
        });

        fetch("../p/add_to_cart.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: data.toString(),
        })
          .then((response) => response.json())
          .then((result) => {
            if (result.status === "success") {
              const itemsList = document.getElementById(
                `items_${currentMemberId}`
              );
              if (itemsList) {
                let itemFound = false;

                itemsList.querySelectorAll("li").forEach((listItem) => {
                  const itemDetails = listItem.innerHTML;
                  if (itemDetails.includes(productName)) {
                    const currentQuantityMatch =
                      itemDetails.match(/Quantity:\s*(\d+)/);
                    const currentQuantity = currentQuantityMatch
                      ? parseInt(currentQuantityMatch[1], 10)
                      : 0;
                    const newQuantity = currentQuantity + quantity;
                    const newTotal = (newQuantity * price).toFixed(2);

                    listItem.innerHTML = `${highlightText(
                      productName
                    )} - Quantity: ${highlightText(
                      newQuantity
                    )} - Total: ${highlightText(newTotal)}`;
                    itemFound = true;
                  }
                });

                if (!itemFound) {
                  const listItem = document.createElement("li");
                  listItem.id = `item_${id}`;
                  listItem.innerHTML = `${highlightText(
                    productName
                  )} - Quantity: ${highlightText(
                    quantity
                  )} - Total: ${highlightText(total)}`;
                  itemsList.appendChild(listItem);
                }

                updateMemberTotal();
                alert(
                  `Added ${quantity} item(s) of ${productName} (ID: ${id}) to the cart. Total: P ${total}`
                );
                quantityInput.value = "1"; // Reset quantity
              } else {
                console.error(
                  `Item list for member ID ${currentMemberId} not found.`
                );
              }
            } else {
              alert(`Failed to add item to cart: ${result.message}`);
            }
          })
          .catch((error) => console.error("Error adding item to cart:", error));
      } else {
        console.error(`Product details not found for ID: ${id}`);
      }
    })
    .catch((error) => console.error("Error fetching product details:", error));
}

function updateMemberTotal() {
  const itemsList = document.getElementById(`items_${currentMemberId}`);
  const membershipCostElement = document.getElementById(
    `membership_cost_${currentMemberId}`
  );
  const totalCostElement = document.querySelector(`#total_${currentMemberId}`);

  if (itemsList && membershipCostElement && totalCostElement) {
    let totalCost = parseFloat(
      membershipCostElement.innerText.replace(/[^\d.-]/g, "")
    );
    itemsList.querySelectorAll("li").forEach((listItem) => {
      const totalMatch = listItem.innerHTML.match(
        /Total:\s*<img src="[^"]+">(\d+\.\d+)/
      );
      if (totalMatch) {
        totalCost += parseFloat(totalMatch[1]);
      }
    });

    totalCostElement.innerText = totalCost.toFixed(2);
  } else {
    console.error(
      "Items list or membership cost or total cost element not found for member ID " +
        currentMemberId
    );
  }
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
