const token = localStorage.getItem("token");

function showTraining() {
  if (token) {
    let text = "ยังไม่มีโครงเปิดรับสมัคร";
    fetch(`${ENV.API_URL}/User/mange_training.php?action=getAllTraining`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.data.length) {
          text = "";
          for (let index = 0; index < data.data.length; index++) {
            const element = data.data[index];
            if (element.Type == "Now") {
              text += `
                    <div class="featured-content">
                <div class="featured-image">
                    <img src=${element.Image}
                    alt="รูปโครงการอบรม" />
                </div>
                <div class="featured-details">
                    <h2>${element.Title}</h2>
                    <p>${element.Description}</p>
                    <p><strong>สถานที่:</strong> ${element.Location}</p>
                    <!-- ปุ่มเดิม -->
                    <a href="#" class="register-btn" onclick="openModal()">ลงทะเบียน</a>

                    <!--  Modal Register Form -->
                    <div id="registerModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <h2>ฟอร์มลงทะเบียนเข้าร่วมอบรม</h2>
                        <form>
                        <label for="fullname" style="color: black;">ชื่อ-นามสกุล</label>
                        <input type="text" id="fullname" name="fullname" required>

                        <label for="phone" style="color: black;">เบอร์โทรศัพท์</label>
                        <input type="tel" id="phone" name="phone" required>

                        <label for="email" style="color: black;">อีเมล</label>
                        <input type="email" id="email" name="email" required>

                        <button type="submit">ส่งข้อมูล</button>
                        </form>
                    </div>
                    </div>
                </div>
                </div>
        `;
            }
          }
        }
        document.getElementById("show-training").innerHTML = text;
      });
  }
}
showTraining();
function showTrainingComingSoon() {
  if (token) {
    let text = "ยังไม่มีโครงเปิดรับสมัคร";
    fetch(`${ENV.API_URL}/User/mange_training.php?action=getAllTraining`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.data.length) {
          text = "";
          for (let index = 0; index < data.data.length; index++) {
            const element = data.data[index];
            if (element.Type == "Coming soon") {
              text += `
                     <div class="training-card">
          <img
            src=${element.Image}
            alt="training image" class="training-img" />
          <div class="training-content">
            <h3>${element.Title}</h3>
            <p>${element.Description}</p>
            <p><strong>สถานที่:</strong> ${element.Location}</p>
            <a href="#details" class="btn">ดูรายละเอียด</a>
          </div>
        </div>
        `;
            }
          }
        }
        document.getElementById("show-training-comming-soon").innerHTML = text;
      });
  }
}
showTrainingComingSoon();
