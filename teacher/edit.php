<?php
include "../vars.php";
$cateNum = 4;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";

if (isset($_GET['id'])) {
    $teacher_id = $_GET['id'];
} else {
    echo "未提供 teacher_id";
    exit;
}

require_once("../db_connect.php");

$sql = "SELECT * FROM teacher_info WHERE teacher_id = '$teacher_id' AND valid=1";
$result = $conn->query($sql);
$userCount = $result->num_rows;
$row = $result->fetch_assoc();

// 獲取教師圖片
$sqlImg = "SELECT img FROM teacher_images WHERE teacher_id = '$teacher_id' AND valid=1";
$resultImg = $conn->query($sqlImg);
$imageRow = $resultImg->fetch_assoc();

if ($userCount > 0) {
    $title = $row["teacher_name"];
} else {
    $title = "使用者不存在";
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>編輯教師資料</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="../style.css">
    <?php include('../css.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <main class="main-content pb-3 px-5">
        <div class="pt-3">
            <div class="p-3 bg-white shadow rounded-2 mb-4 border">
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-auto">
                        <a class="btn btn-primary" href="teacherIndex.php"><i class="fa-solid fa-circle-left"></i></a>
                    </div>
                    <div class="col">
                        <h4 class="m-0">編輯教師資料</h4>
                    </div>
                </div>

                <?php if ($userCount > 0) : ?>
                    <form id="edit-teacher-form" action="doupdate_teacher.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($row["teacher_id"]) ?>">
                        <input type="hidden" name="existingPictureName" value="<?= htmlspecialchars($imageRow["img"]) ?>">
                        <div class="row g-2">
                            <!-- 現有圖片 -->
                            <div class="col-12 text-center border-0 px-1">
                                <?php if ($imageRow && $imageRow['img']) : ?>
                                    <img src="../teacher_images/<?= htmlspecialchars($imageRow['img']) ?>" alt="Teacher Image" class="rounded mb-3" style="max-width: 200px;">
                                <?php else : ?>
                                    <p>沒有上傳圖片</p>
                                <?php endif; ?>
                            </div>

                            <!-- 上傳新圖片 -->
                            <div class="col-12 text-center border-0 px-1">
                                <input class="form-control form-control-lg" type="file" id="photo" name="photo">
                                <label class="ms-2" for="photo"></label>
                            </div>

                            <!-- 姓名 -->
                            <div class="col-6 form-floating pb-3">
                                <input type="text" class="form-control" id="name" name="teacher_name" value="<?= htmlspecialchars($row["teacher_name"]) ?>" placeholder="輸入姓名" required>
                                <label class="ms-2" for="name"><span class="text-danger">*</span>教師姓名</label>
                            </div>

                            <!-- 性別 -->
                            <div class="col-6 form-floating pb-3">
                                <select class="form-select" id="gender" name="teacher_gender" required>
                                    <option value="" disabled>選擇性別</option>
                                    <option value="Male" <?= $row["teacher_gender"] == "Male" ? "selected" : "" ?>>男</option>
                                    <option value="Female" <?= $row["teacher_gender"] == "Female" ? "selected" : "" ?>>女</option>
                                </select>
                                <label class="ms-2" for="gender"><span class="text-danger">*</span>性別</label>
                            </div>

                            <!-- 手機 -->
                            <div class="col-6 form-floating pb-3">
                                <input type="text" class="form-control" id="phone" name="teacher_phone" value="<?= htmlspecialchars($row["teacher_phone"]) ?>" placeholder="輸入手機號碼" required>
                                <label class="ms-2" for="phone"><span class="text-danger">*</span>手機</label>
                            </div>

                            <!-- Email -->
                            <div class="col-6 form-floating pb-3">
                                <input type="email" class="form-control" id="email" name="teacher_email" value="<?= htmlspecialchars($row["teacher_email"]) ?>" placeholder="輸入電子郵件" required>
                                <label class="ms-2" for="email"><span class="text-danger">*</span>Email</label>
                            </div>

                            <!-- 專業 -->
                            <div class="col-6 form-floating pb-3">
                                <select class="form-select" id="major" name="major" required>
                                    <option value="" disabled>選擇專業</option>
                                    <option value="大提琴" <?= $row["major"] == "大提琴" ? "selected" : "" ?>>大提琴</option>
                                    <option value="中提琴" <?= $row["major"] == "中提琴" ? "selected" : "" ?>>中提琴</option>
                                    <option value="小提琴" <?= $row["major"] == "小提琴" ? "selected" : "" ?>>小提琴</option>
                                </select>
                                <label class="ms-2" for="major"><span class="text-danger">*</span>專業</label>
                            </div>

                            <!-- 教學科目 -->
                            <div class="col-6 form-floating pb-3">
                                <input type="text" class="form-control" id="subject" name="subject" value="<?= htmlspecialchars($row["subject"]) ?>" placeholder="輸入教學科目" required>
                                <label class="ms-2" for="subject"><span class="text-danger">*</span>教學科目</label>
                            </div>

                            <!-- 最高學歷 -->
                            <div class="col-6 form-floating pb-3">
                                <input type="text" class="form-control" id="education" name="education" value="<?= htmlspecialchars($row["education"]) ?>" placeholder="輸入最高學歷" required>
                                <label class="ms-2" for="education"><span class="text-danger">*</span>最高學歷</label>
                            </div>

                            <!-- 經歷 -->
                            <div class="col-6 form-floating pb-3">
                                <input type="text" class="form-control" id="experience" name="experience" value="<?= htmlspecialchars($row["experience"]) ?>" placeholder="輸入經歷" required>
                                <label class="ms-2" for="experience"><span class="text-danger">*</span>經歷</label>
                            </div>

                            <!-- 提交按鈕 -->
                            <div class="d-flex justify-content-center pt-3">
                                <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">儲存</button>
                            </div>
                        </div>
                    </form>
                <?php else : ?>
                    <p>使用者不存在</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- 成功提示框 -->
    <div class="modal fade" id="success-modal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">變更成功</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    教師資料已成功變更。
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location.href='teacherIndex.php'">關閉</button>
                </div>
            </div>
        </div>
    </div>

    <?php include('../js.php') ?>
    <script>
        document.getElementById('edit-teacher-form').addEventListener('submit', function(event) {
            event.preventDefault();
            event.stopPropagation();

            const form = this;
            const formData = new FormData(form);

            fetch('doupdate_teacher.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    var successModal = new bootstrap.Modal(document.getElementById('success-modal'));
                    successModal.show();
                } else {
                    alert('變更失敗，請稍後再試。');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('變更失敗，請稍後再試。');
            });
        });
    </script>
</body>

</html>
