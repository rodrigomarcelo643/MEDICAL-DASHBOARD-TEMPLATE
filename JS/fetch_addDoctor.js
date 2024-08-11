function submitDoctorForm(event) {
  event.preventDefault();

  const form = document.getElementById("doctorForm");
  const formData = new FormData(form);

  fetch("../ADMIN/add_doctor.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((data) => {
      document.getElementById("loadingSpinner").classList.remove("show");
      alert(data);
      if (data.includes("New record created successfully")) {
        form.reset();
      }
    })
    .catch((error) => {
      document.getElementById("loadingSpinner").classList.remove("show");
      console.error("Error:", error);
      alert("An unexpected error occurred.");
    });
}
