function verifyToken() {
  const token = localStorage.getItem("token");
  if (token) {
    // fetch("http://localhost/my-app/auth.php", {
    fetch(`${ENV.API_URL}/api/auth.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
    })
      .then((response) => response.json())
      .then((data) => {
        if (
          data.message == "Token expired" ||
          data.message == "Invalid token"
        ) {
          localStorage.removeItem("token");
          setTimeout(() => {
            location.href = "login.html";
          }, 1000);
        }
      })
      .catch((error) => {
        localStorage.removeItem("token");
        setTimeout(() => {
          location.href = "login.html";
        }, 1000);
      });
  }
}
verifyToken();
