const visibilityBtn = document.getElementById("visibilityBtn")
visibilityBtn.addEventListener("click", toggleVisibility)

function toggleVisibility() {
  const passwordInput = document.getElementById("password")
  const icon = document.getElementById("icon-0")
  if (passwordInput.type === "password") {
    passwordInput.type = "text"
    icon.innerText = "visibility_off"
  } else {
    passwordInput.type = "password"
    icon.innerText = "visibility"
  }
}

if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}