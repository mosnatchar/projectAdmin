const token = localStorage.getItem("token");
function getAllUsers() {
  document.getElementById("userType").value = "All";
  if (token) {
    fetch(
      `${ENV.API_URL}/Admin/manage_users.php?action=getAllUsers&member_type=All`,
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          Authorization: "Bearer " + token,
        },
      }
    )
      .then((res) => res.json())
      .then((data) => {
        if (data.data.length) {
          // แสดงผลข้อมูลตามประเภทที่เลือก
          const dropdown = document.getElementById("userType");
          const tableBody = document.getElementById("userTableBody");

          function renderTable(userType) {
            tableBody.innerHTML = "";
            const filtered =
              userType === "All"
                ? data.data
                : data.data.filter((u) => u.status.toLowerCase() === userType);
            filtered.forEach((user) => {
              const row = document.createElement("tr");
              row.innerHTML = `
          <td class="border px-4 py-2">${user.MemberID}</td>
          <td class="border px-4 py-2">${user.FullName}</td>
          <td class="border px-4 py-2">${user.Username}</td>
          <td class="border px-4 py-2">${user.Email}</td>
          <td class="border px-4 py-2">${user.Permission}</td>
          <td class="border px-4 py-2">
            <button class="text-blue-500 hover:underline">แก้ไข</button>
            <button class="text-red-500 hover:underline ml-4" onclick="deleteUser('${user.MemberID}')" >ลบ</button>
          </td>
        `;
              tableBody.appendChild(row);
            });
          }

          dropdown.addEventListener("change", () =>
            renderTable(dropdown.value)
          );
          renderTable("All"); // โหลดข้อมูลเริ่มต้น
        }
      });
  }
  document.getElementById("userType").addEventListener("change", function () {
    const selectedValue = document.getElementById("userType").value;
    fetch(
      `${ENV.API_URL}/Admin/manage_users.php?action=getAllUsers&member_type=${selectedValue}`,
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          Authorization: "Bearer " + token,
        },
      }
    )
      .then((res) => res.json())
      .then((data) => {
        if (data.data.length) {
          // แสดงผลข้อมูลตามประเภทที่เลือก
          const dropdown = document.getElementById("userType");
          const tableBody = document.getElementById("userTableBody");

          function renderTable(userType) {
            tableBody.innerHTML = "";
            const filtered =
              userType === "All"
                ? data.data
                : data.data.filter((u) => u.status.toLowerCase() === userType);
            filtered.forEach((user) => {
              const row = document.createElement("tr");
              row.innerHTML = `
          <td class="border px-4 py-2">${user.MemberID}</td>
          <td class="border px-4 py-2">${user.FullName}</td>
          <td class="border px-4 py-2">${user.Username}</td>
          <td class="border px-4 py-2">${user.Email}</td>
          <td class="border px-4 py-2">${user.Permission}</td>
          <td class="border px-4 py-2">
            <button class="text-blue-500 hover:underline">แก้ไข</button>
            <button class="text-red-500 hover:underline ml-4" onclick="deleteUser('${user.MemberID}')" >ลบ</button>
          </td>
        `;
              tableBody.appendChild(row);
            });
          }

          dropdown.addEventListener("change", () =>
            renderTable(dropdown.value)
          );
          renderTable("All"); // โหลดข้อมูลเริ่มต้น
        }
      });
  });
}
getAllUsers();
function deleteUser(MemberID) {
  fetch(
    `${ENV.API_URL}/Admin/manage_users.php?action=deleteUser&member_id=${MemberID}`,
    {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
      // body: JSON.stringify({ member_id: MemberID })
    }
  )
    .then((res) => res.json())
    .then((data) => {
      if (data.message == "ลบข้อมูลสำเร็จ") {
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "สำเร็จ!",
          text: data.message,
          showConfirmButton: false,
          timer: 1500,
        });
      } else {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "ไม่สำเร็จ!",
          text: data.message,
          showConfirmButton: false,
          timer: 1500,
        });
      }
      getAllUsers();
    });
}
