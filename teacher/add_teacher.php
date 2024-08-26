<?php
include "../vars.php";
$cateNum = 4;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php"; 
?>

<!doctype html>
<html lang="zh-TW">

<head>
    <title>新增教師</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="stylesheet" href="../style.css">
    <?php include('../css.php') ?>
    <style>
        #preview {
            max-width: 200px;
            max-height: 200px;
            display: block;
            margin-top: 10px;
        }
    </style>
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
                        <h4 class="m-0">新增教師</h4>
                    </div>
                </div>
                <form id="add-teacher-form" enctype="multipart/form-data" novalidate>
                    <div class="row g-2">
                        <!-- 圖片 -->
                        <div class="col-12 text-center border-0 px-1">
                            <img class="rounded mb-3" id="preview" alt="預覽照片">
                            <input class="form-control form-control-lg" type="file" id="photo" name="photo" onchange="previewImage(event)">
                            <label class="ms-2" for="photo"></label>
                        </div>

                        <!-- 姓名 -->
                        <div class="col-6 form-floating pb-3">
                            <input type="text" class="form-control" id="name" name="teacher_name" placeholder="輸入姓名" required>
                            <label class="ms-2" for="name"><span class="text-danger">*</span>教師姓名</label>
                            <div class="invalid-feedback">請輸入教師姓名。</div>
                        </div>

                        <!-- 性別 -->
                        <div class="col-6 form-floating pb-3">
                            <select class="form-select" id="gender" name="teacher_gender" required>
                                <option value="" disabled selected>選擇性別</option>
                                <option value="male">男</option>
                                <option value="female">女</option>
                            </select>
                            <label class="ms-2" for="gender"><span class="text-danger">*</span>性別</label>
                            <div class="invalid-feedback">請選擇性別。</div>
                        </div>

                        <!-- 手機 -->
                        <div class="col-6 form-floating pb-3">
                            <input type="text" class="form-control" id="phone" name="teacher_phone" placeholder="輸入手機號碼" required>
                            <label class="ms-2" for="phone"><span class="text-danger">*</span>手機</label>
                            <div class="invalid-feedback">請輸入手機號碼。</div>
                        </div>

                        <!-- Email -->
                        <div class="col-6 form-floating pb-3">
                            <input type="email" class="form-control" id="email" name="teacher_email" placeholder="輸入電子郵件" required>
                            <label class="ms-2" for="email"><span class="text-danger">*</span>Email</label>
                            <div class="invalid-feedback">請輸入有效的電子郵件地址。</div>
                        </div>

                        <!-- 專業 -->
                        <div class="col-6 form-floating pb-3">
                            <select class="form-select" id="major" name="major" required>
                                <option value="" disabled selected>選擇專業</option>
                                <option value="大提琴">大提琴</option>
                                <option value="中提琴">中提琴</option>
                                <option value="小提琴">小提琴</option>
                            </select>
                            <label class="ms-2" for="major"><span class="text-danger">*</span>專業</label>
                            <div class="invalid-feedback">請選擇專業。</div>
                        </div>

                        <!-- 教學科目 -->
                        <div class="col-6 form-floating pb-3">
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="輸入教學科目" required>
                            <label class="ms-2" for="subject"><span class="text-danger">*</span>教學科目</label>
                            <div class="invalid-feedback">請輸入教學科目。</div>
                        </div>

                        <!-- 最高學歷 -->
                        <div class="col-6 form-floating pb-3">
                            <input type="text" class="form-control" id="education" name="education" placeholder="輸入最高學歷" required>
                            <label class="ms-2" for="education"><span class="text-danger">*</span>最高學歷</label>
                            <div class="invalid-feedback">請輸入最高學歷。</div>
                        </div>

                        <!-- 經歷 -->
                        <div class="col-6 form-floating pb-3">
                            <input type="text" class="form-control" id="experience" name="experience" placeholder="輸入經歷" required>
                            <label class="ms-2" for="experience"><span class="text-danger">*</span>經歷</label>
                            <div class="invalid-feedback">請輸入經歷。</div>
                        </div>

                        <!-- 提交按鈕 -->
                        <div class="d-flex justify-content-center pt-3">
                            <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">新增</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- 成功提示框 -->
    <div class="modal fade" id="success-modal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">新增教師成功</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    教師資料已成功新增。
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location.href='teacherIndex.php'">關閉</button>
                </div>
            </div>
        </div>
    </div>

    <?php include('../js.php') ?>
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const preview = document.getElementById('preview');

            reader.onload = function() {
                preview.src = reader.result;
            }

            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            } else {
                preview.src = ''; // 清除預覽圖像
            }
        }

        document.getElementById('add-teacher-form').addEventListener('submit', function(event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.checkValidity()) {
                const formData = new FormData(this);

                fetch('do_add_teacher.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result === 'success') {
                            var myModal = new bootstrap.Modal(document.getElementById('success-modal'));
                            myModal.show();
                            this.reset(); // 重置表單
                            document.getElementById('preview').src = ''; // 清除预览图片
                        } else {
                            alert('教師新增失敗！請稍後再試。');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('發生錯誤，請稍後再試。');
                    });
            } else {
                this.classList.add('was-validated');
            }
        });
    </script>
</body>

</html>
