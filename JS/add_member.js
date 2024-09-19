function showAddMembershipModal() {
  const showElement = document.getElementById("addMemberModal");
  showElement.style.display = "block";
}

function hideAddMembershipModal() {
  const hideElement = document.getElementById("addMemberModal");
  hideElement.style.display = "none";
}

document.addEventListener("DOMContentLoaded", function () {
  const membershipTypeSelect = document.getElementById("membershipType");
  const totalCostElement = document.getElementById("totalCost");
  const totalCostHidden = document.getElementById("totalCostHidden");

  const membershipCosts = {
    "daily-basic": 100,
    "daily-pro": 110,
    "monthly-basic": 1000,
    "monthly-pro": 1100,
  };

  function updateTotalCost() {
    const selectedValue = membershipTypeSelect.value;
    const totalCost = membershipCosts[selectedValue] || 100;

    totalCostElement.innerHTML = `
          <span class="total-cost-container">
            <img src="../Assets/pesos.png" alt="Pesos" class="peso-icon" style="width:28px;height:28px;"/>
            <span class="cost-number" style="font-size:19px;">${totalCost}.00</span>
          </span>`;
    totalCostHidden.value = totalCost;
  }

  membershipTypeSelect.addEventListener("change", updateTotalCost);
  updateTotalCost();
});

//==============INSERT MEMBERS================
function hideMemberForm() {
  const formMember = document.getElementById("addMemberModal");
  formMember.style.display = "none";
}
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("memberForm");

  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const formData = new FormData(form);

    try {
      const response = await fetch("../p/insert_member.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.text();
      hideMemberForm();
      showModalAddMember(result);
      console.log("Success MEMBER ADDED ");
      setTimeout(function () {
        hideModalAddMember();
      }, 4000);

      form.reset();
    } catch (error) {
      console.error("Error:", error);
      alert("There was an error submitting the form.");
    }
  });
});

function hideModalAddMember() {
  const modalSuccessMember = document.getElementById("successModal1");
  modalSuccessMember.style.display = "none";
}
//Show sa modal progreess
function showModalAddMember(message) {
  const modal = document.getElementById("successModal1");
  const closeBtn = document.querySelector("#successModal1 .close");
  const progress = document.getElementById("progress");

  modal.style.display = "block";

  const hideModal = () => {
    modal.style.display = "none";
    progress.removeEventListener("animationend", hideModal);
  };

  progress.addEventListener("animationend", hideModal);

  closeBtn.onclick = () => {
    modal.style.display = "none";
    progress.removeEventListener("animationend", hideModal);
  };

  window.onclick = (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
      progress.removeEventListener("animationend", hideModal);
    }
  };
}

//========CUSTOM SELECT DESIGN==========
const customSelect = document.querySelector(".custom-select");
const selectElement = customSelect.querySelector("select");

selectElement.addEventListener("focus", () => {
  customSelect.classList.add("open");
});

selectElement.addEventListener("blur", () => {
  customSelect.classList.remove("open");
});

//////////===================RENEWAL====================
function showRenewModal(member) {
  const modal = document.getElementById("renewMemberModal");
  modal.style.display = "block";

  document.getElementById("renewFirstName").value = member.first_name;
  document.getElementById("renewLastName").value = member.last_name;
  document.getElementById("renewContactNumber").value = member.contact_number;

  const membershipTypeSelect = document.getElementById("renewMembershipType");
  membershipTypeSelect.value = member.membership_type;

  updateRenewTotalCost();
}

function hideRenewModal() {
  const modal = document.getElementById("renewMemberModal");
  modal.style.display = "none";
}

//==============Function for Success for submitting the Renew Form

function SuccessRenew() {
  const Renew = document.getElementById("successModalRenew");
  Renew.style.display = "block";
}
function HideSuccessRenew() {
  const Renew = document.getElementById("successModalRenew");
  Renew.style.display = "none";
}
document
  .getElementById("renewMembershipType")
  .addEventListener("change", updateRenewTotalCost);

function updateRenewTotalCost() {
  const membershipTypeSelect = document.getElementById("renewMembershipType");
  const totalCostElement = document.getElementById("renewTotalCost");
  const totalCostHidden = document.getElementById("renewTotalCostHidden");

  const membershipCosts = {
    "daily-basic": 100,
    "daily-pro": 110,
    "monthly-basic": 1000,
    "monthly-pro": 1100,
  };

  const selectedValue = membershipTypeSelect.value;
  const totalCost = membershipCosts[selectedValue] || 100;

  totalCostElement.innerHTML = `
      <span class="total-cost-container">
          <img src="../Assets/pesos.png" alt="Pesos" class="peso-icon" style="width:28px;height:28px;"/>
          <span class="cost-number" style="font-size:19px;">${totalCost}.00</span>
      </span>`;
  totalCostHidden.value = totalCost;
}

document
  .getElementById("renewMemberForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch("../P/renew_member.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          alert("Error: " + data.error);
        } else {
          hideRenewModal();
          SuccessRenew();
          setTimeout(function () {
            HideSuccessRenew();
          }, 4000);
          setTimeout;
        }
      })
      .catch((error) => console.error("Error:", error));
  });

//========Function for Members
