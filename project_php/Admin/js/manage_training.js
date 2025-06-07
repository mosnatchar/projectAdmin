const token = localStorage.getItem("token");
function getAllTraining() {
  if (token) {
    document.getElementById("show-trianing").innerHTML = "";
    fetch(`${ENV.API_URL}/Admin/mange_training.php?action=getAllTraining`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.data.length) {
          let text = "";
          for (let index = 0; index < data.data.length; index++) {
            const element = data.data[index];
            text += `
              <div class="bg-white shadow-lg rounded-lg overflow-hidden">
              <img src=${element.Image} alt="Training Image" class="w-full h-40 object-cover">
              <div class="p-4">
                <h2 class="text-lg font-bold mb-1">${element.Title}</h2>
                <p class="text-sm text-gray-600 mb-1">${element.Date} | ${element.Owner}</p>
                <p class="text-sm text-gray-700 mb-3">${element.Description}</p>
                <div class="flex flex-wrap justify-end gap-2">
                  <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">แก้ไข</button>
                  <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm" onclick="deleteTraining('${element.PortfolioID}')">ลบ</button>
                  <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">ดูรายชื่อผู้ลงทะเบียน</button>
                </div>
              </div>
            </div>
              `;
          }
          document.getElementById("show-trianing").innerHTML = text;
        }
      });
  }
}
getAllTraining();
function deleteTraining(PortfolioID) {
  fetch(
    `${ENV.API_URL}/Admin/mange_training.php?action=deleteTraining&portfolio_id=${PortfolioID}`,
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
      if (data.message == "ลบข้อมูลสำเร็จ") {
        getAllTraining();
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
    });
}
