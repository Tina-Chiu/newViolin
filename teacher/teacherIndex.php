<?php
include "../vars.php";
$cateNum = 4;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";
require_once("../db_connect.php");

// 設定每頁顯示的筆數
$itemsPerPage = 6;

// 獲取當前頁數，若無則預設為第1頁
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// 獲取搜尋關鍵字（如果有）
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

// 修改查詢以篩選符合搜尋條件且 `valid = 1` 的資料
$sqlCount = "SELECT COUNT(*) AS total FROM teacher_info WHERE valid = 1 AND (teacher_name LIKE '%$searchKeyword%' OR major LIKE '%$searchKeyword%')";
$resultCount = $conn->query($sqlCount);
$userCount = $resultCount->fetch_assoc()['total'];

// 計算總頁數
$totalPages = ceil($userCount / $itemsPerPage);

// 查詢當前頁面的資料，並根據搜尋條件和 `valid = 1` 篩選
$sql = "SELECT * FROM teacher_info WHERE valid = 1 AND (teacher_name LIKE '%$searchKeyword%' OR major LIKE '%$searchKeyword%') LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);
?>

<!doctype html>
<html lang="zh-TW">

<head>
  <title>教師列表</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link rel="stylesheet" href="../style.css">
  <style>
    .fixed-bottom-pagination {
      position: fixed;
      bottom: 0;
      width: 100%;
      background-color: #fff;
      padding: 10px 0;
      box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }

    
  </style>
</head>

<body>
  <main class="main-content ">
    <div class="pt-3">
      <div class="p-3 bg-white shadow rounded-2 mb-4 border">
        <div class="py-2">
          <h4>教師列表</h4>
          <form method="GET" action="">
            <div class="row g-2">
              <div class="col-3 form-floating">
                <input type="text" class="form-control" name="search" placeholder="搜尋關鍵字" value="<?= htmlspecialchars($searchKeyword); ?>">
                <label for="search">搜尋關鍵字</label>
              </div>
              <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-lg">
                  <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="teacherIndex.php" class="btn btn-dark btn-lg">
                  <i class="fa-solid fa-xmark"></i>
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="bg-white shadow rounded-2 border">
        <div class="table-title mb-3 d-flex justify-content-between align-items-center p-2 rounded-top">
          <h6 class="m-0 text-primary ms-2">查詢結果</h6>
          <a class="btn btn-primary me-2" href="add_teacher.php">新增</a>
        </div>
        <div class="p-3">
          <!-- 列表 -->
          <table class="coupon-table table table-bordered">
            <thead>
              <tr>
                <th>編號</th>
                <th>教師姓名</th>
                <th>專業</th>
                <th>最高學歷</th>
                <th>功能項目</th>
              </tr>
            </thead>
            <tbody id="main_h">
              <?php if ($result->num_rows > 0): ?>
                <?php while ($user = $result->fetch_assoc()): ?>
                  <tr>
                    <td scope="row"><?= $user['teacher_id']; ?></td>
                    <td><?= $user['teacher_name']; ?></td>
                    <td><?= $user['major']; ?></td>
                    <td><?= $user['education']; ?></td>
                    <td>
                      <a class="text-primary" href="edit.php?id=<?= $user['teacher_id']; ?>"><i class="fa-solid fa-pen"></i></a>
                      <button type="button" class="btn text-primary" data-bs-toggle="modal" data-bs-target="#teacherModal"
                        data-name="<?= htmlspecialchars($user['teacher_name']) ?>"
                        data-gender="<?= htmlspecialchars($user['teacher_gender']) ?>"
                        data-major="<?= htmlspecialchars($user['major']) ?>"
                        data-phone="<?= htmlspecialchars($user['teacher_phone']) ?>"
                        data-email="<?= htmlspecialchars($user['teacher_email']) ?>"
                        data-education="<?= htmlspecialchars($user['education']) ?>"
                        data-subject="<?= htmlspecialchars($user['subject']) ?>"
                        data-experience="<?= htmlspecialchars($user['experience']) ?>">
                        <i class="fa-solid fa-eye"></i>
                      </button>
                      <a class="text-danger" href="#!" data-bs-toggle="modal" data-bs-target="#deleteModal"
                        data-delete-id="<?= htmlspecialchars($user['teacher_id']) ?>"
                        data-teacher-name="<?= htmlspecialchars($user['teacher_name']) ?>">
                        <i class="fa-solid fa-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5">沒有找到符合條件的教師</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>


    <!-- 固定分頁導航 -->
    <div class="container mt-3 mb-5">
      <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page - 1; ?>&search=<?= urlencode($searchKeyword); ?>">&laquo; Previous</a>
          </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
            <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($searchKeyword); ?>"><?= $i; ?></a>
          </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page + 1; ?>&search=<?= urlencode($searchKeyword); ?>">Next &raquo;</a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </main>
  <!-- 教師詳細模態框 -->
  <div class="modal fade " id="teacherModal" tabindex="-1" aria-labelledby="teacherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h4 class="modal-title text-white" id="modalName"></h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-4">
            <div class="col-6">
              <div>
                <p><i class="me-1 fa-solid fa-venus-mars "></i>性別: <span id="modalGender"></span></p>
              </div>
            </div>
            <div class="col-6">
              <div>
                <p><i class="me-1 fa-solid fa-music"></i>專業: <span id="modalMajor"></span></p>
              </div>
            </div>
          </div>
          <div class="row g-4 mt-1">
            <div class="col-6">
              <div>
                <p><i class="me-1 fa-solid fa-phone"></i>手機:<br> <span id="modalPhone"></span></p>
              </div>
            </div>
            <div class="col-6">
              <div>
                <p><i class="me-1 fa-solid fa-envelope"></i>Email: <br><span id="modalEmail"></span></p>
              </div>
            </div>
            <div class="col-6">
              <div>
                <h6><i class="me-1 fa-solid fa-graduation-cap"></i>學歷</h6>
                <p id="modalEducation"></p>
              </div>
            </div>
            <div class="col-6">
              <div>
                <h6><i class="me-1 fa-solid fa-book-open"></i>教授科目</h6>
                <p id="modalSubject"></p>
              </div>
            </div>
            <div class="col-12">
              <div>
                <h6><i class="me-1 fa-solid fa-briefcase"></i>經歷</h6>
                <p id="modalExperience"></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- 刪除確認模態框 -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">確認刪除</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p id="deleteConfirmationText"></p>
        </div>
        <div class="modal-footer">
          <a id="confirmDeleteBtn" href="#!" class="btn btn-danger">刪除</a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>

  <?php include('../js.php') ?>

  <script>
    // 配置模態框數據
    var teacherModal = document.getElementById('teacherModal');
    teacherModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var name = button.getAttribute('data-name');
      var gender = button.getAttribute('data-gender');
      var major = button.getAttribute('data-major');
      var phone = button.getAttribute('data-phone');
      var email = button.getAttribute('data-email');
      var education = button.getAttribute('data-education');
      var subject = button.getAttribute('data-subject');
      var experience = button.getAttribute('data-experience');

      var modalName = teacherModal.querySelector('#modalName');
      var modalGender = teacherModal.querySelector('#modalGender');
      var modalMajor = teacherModal.querySelector('#modalMajor');
      var modalPhone = teacherModal.querySelector('#modalPhone');
      var modalEmail = teacherModal.querySelector('#modalEmail');
      var modalEducation = teacherModal.querySelector('#modalEducation');
      var modalSubject = teacherModal.querySelector('#modalSubject');
      var modalExperience = teacherModal.querySelector('#modalExperience');

      modalName.textContent = name;
      modalGender.textContent = gender;
      modalMajor.textContent = major;
      modalPhone.textContent = phone;
      modalEmail.textContent = email;
      modalEducation.textContent = education;
      modalSubject.textContent = subject;
      modalExperience.textContent = experience;
    });

    // 刪除確認框
    document.addEventListener('DOMContentLoaded', function() {
      var deleteModal = document.getElementById('deleteModal');
      deleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var teacherId = button.getAttribute('data-delete-id');
        var teacherName = button.getAttribute('data-teacher-name');

        // 更新確認訊息
        var confirmationMessage = "確定要刪除此教師嗎？";
        document.getElementById('deleteConfirmationText').innerHTML = confirmationMessage;

        // 設置確認刪除按鈕的連結
        var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        confirmDeleteBtn.href = "delete.php?id=" + teacherId;
      });
    });
  </script>
</body>

</html>