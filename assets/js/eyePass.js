const eye = document.getElementById("eye");
const passwordInput = document.getElementById("password");

eye.addEventListener("click", () => {
    const isPasswordVisible = passwordInput.type === "text";
    passwordInput.type = isPasswordVisible ? "password" : "text";
    eye.src = isPasswordVisible ? "./assets/images/eye-open.svg" : "./assets/images/eye-closed.svg";
});