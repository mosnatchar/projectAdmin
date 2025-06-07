function Navbar() {
  const text = `
     <nav class="navbar">
    <div class="logo">
      <a href="#"><img src="image/Unknown-5.jpg" alt="LRU Logo" /></a>
    </div>
    <ul class="flex flex-row items-center justify-around w-1/2">
      <li><a href="index.html">หน้าหลัก</a></li>
      <li><a href="courses.html">คอร์สอบรม</a></li>
      <li><a href="performance.html">ผลงานและบริการ</a></li>
      <li id="index-login"><a href="login.html">เข้าสู่ระบบ / ลงทะเบียน</a></li>
      <li id="index-profile" hidden>
        <div class="dropdown">
          <button class="dropbtn"><img src="" alt="" srcset="" id="image-profile">
            <p id="display-fullname"></p>
          </button>
          <div class="dropdown-content">
            <a href="userprofile.html">ดูโปรไฟล์</a>
            <a style="cursor: pointer;" onclick="logOut()">ออกจากระบบ</a>
          </div>
        </div>
      </li>
    </ul>
  </nav>
    `;
  document.getElementById("navbar").innerHTML = text;
  const token = localStorage.getItem("token");
  if (token) {
    document.getElementById("index-login").hidden = true;
    document.getElementById("index-profile").hidden = false;
    const base64Url = token.split(".")[1];
    const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
    const jsonPayload = decodeURIComponent(
      atob(base64)
        .split("")
        .map(function (c) {
          return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
        })
        .join("")
    );

    const jsonData = JSON.parse(jsonPayload);
    // fetch('http://localhost/my-app/User/get_profile.php', {  // ส่งข้อมูลไป process.php
    fetch(`${ENV.API_URL}/User/get_profile.php`, {
      // ส่งข้อมูลไป process.php
      // fetch('https://my-php-api-1.onrender.com/api/User/get_profile.php', {  // ส่งข้อมูลไป process.php
      method: "POST",
      body: JSON.stringify(jsonData),
    })
      .then((response) => response.json()) // หรือ .json() ถ้า PHP ส่ง JSON
      .then((data) => {
        document.getElementById("display-fullname").innerText =
          data.data.FullName;
        document.getElementById(
          "image-profile"
        ).src = `${ENV.API_URL}/User/uploads/${data.data.ProfileImage}`;

        // Swal.fire({
        //   position: "top-end",
        //   icon: "success",
        //   title: "สำเร็จ!",
        //   showConfirmButton: false,
        //   timer: 1500
        // });
      })
      .catch((error) => {
        // Swal.fire({
        //   position: "top-end",
        //   icon: "error",
        //   title: "ไม่พบข้อมูล!",
        //   text: error,
        //   showConfirmButton: false,
        //   timer: 1500
        // });
        console.error("Error:", error);
      });
  }
}
Navbar();
