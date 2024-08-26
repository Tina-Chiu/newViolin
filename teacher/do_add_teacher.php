<?php
require_once("../db_connect.php");

// 確保資料已正確提交
if (!isset($_POST["teacher_name"]) || !isset($_POST["teacher_gender"]) || !isset($_POST["teacher_phone"]) || !isset($_POST["teacher_email"]) || !isset($_POST["major"]) || !isset($_POST["subject"]) || !isset($_POST["education"]) || !isset($_POST["experience"])) {
    echo "表單資料不完整";
    exit;
}

// 接收表單資料
$teacher_name = $conn->real_escape_string($_POST["teacher_name"]);
$teacher_gender = $conn->real_escape_string($_POST["teacher_gender"]);
$teacher_phone = $conn->real_escape_string($_POST["teacher_phone"]);
$teacher_email = $conn->real_escape_string($_POST["teacher_email"]);
$major = $conn->real_escape_string($_POST["major"]);
$subject = $conn->real_escape_string($_POST["subject"]);
$education = $conn->real_escape_string($_POST["education"]);
$experience = $conn->real_escape_string($_POST["experience"]);
$hire_date = date('Y-m-d H:i:s');

// 處理圖片上傳
$target_dir = "../teacher_images/"; // 使用相對路徑
$uploaded_image = null;

if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // 檢查檔案是否為圖片
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check === false) {
        echo "檔案不是圖片。";
        exit;
    }

    // 限制檔案格式
    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_types)) {
        echo "僅允許 JPG, JPEG, PNG 和 GIF 檔案格式。";
        exit;
    }

    // 檢查檔案大小
    if ($_FILES["photo"]["size"] > 500000) { // 限制為 500KB
        echo "檔案過大。";
        exit;
    }

    // 嘗試上傳檔案
    $new_file_name = md5(time() . $_FILES["photo"]["name"]) . '.' . $imageFileType;
    $target_file = $target_dir . $new_file_name;

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $uploaded_image = $new_file_name;
    } else {
        echo "上傳檔案時發生錯誤。";
        exit;
    }
}

// 插入教師資料
$sql = "INSERT INTO teacher_info (teacher_name, teacher_gender, teacher_phone, teacher_email, major, subject, education, hire_date, experience) 
        VALUES ('$teacher_name', '$teacher_gender', '$teacher_phone', '$teacher_email', '$major', '$subject', '$education', '$hire_date', '$experience')";

if ($conn->query($sql) === TRUE) {
    $teacher_id = $conn->insert_id; // 獲取剛剛插入的教師ID

    // 如果有上傳圖片，插入圖片資料
    if ($uploaded_image) {
        $sqlImg = "INSERT INTO teacher_images (teacher_id, img) 
                   VALUES ('$teacher_id', '$uploaded_image')";

        if ($conn->query($sqlImg) !== TRUE) {
            echo "更新圖片錯誤: " . $conn->error;
            exit;
        }
    }

    // 返回成功訊息
    echo 'success';
} else {
    echo "新增資料錯誤: " . $conn->error;
}

$conn->close();
?>
