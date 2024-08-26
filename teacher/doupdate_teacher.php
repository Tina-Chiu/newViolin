<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once("../db_connect.php");

    $teacher_id = $_POST["teacher_id"];
    $teacher_name = $_POST["teacher_name"];
    $teacher_gender = $_POST["teacher_gender"];
    $teacher_phone = $_POST["teacher_phone"];
    $teacher_email = $_POST["teacher_email"];
    $major = $_POST["major"];
    $subject = $_POST["subject"];
    $education = $_POST["education"];
    $experience = $_POST["experience"];
    $existingPictureName = $_POST["existingPictureName"]; // 新增，用於處理舊圖片

    // 更新教師基本信息
    $sql = "UPDATE teacher_info 
            SET teacher_name='$teacher_name', teacher_gender='$teacher_gender', teacher_phone='$teacher_phone', teacher_email='$teacher_email', major='$major', subject='$subject', education='$education', experience='$experience'
            WHERE teacher_id='$teacher_id'";

    if ($conn->query($sql) === TRUE) {
        // 處理圖片上傳
        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
            // 設定目標資料夾路徑
            $targetDir = __DIR__ . "/../teacher_images/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileTmpPath = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $fileInfo = pathinfo($fileName);
            $fileExtension = strtolower($fileInfo['extension']);
            
            // 檢查檔案類型
            $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
            if (!in_array($fileExtension, $allowedExtensions)) {
                echo "只允許上傳 JPG, JPEG, PNG, GIF 檔案";
                $conn->close();
                exit;
            }

            // 檢查檔案是否已存在，若存在則在檔名前加上時間戳
            if (file_exists($targetDir . $fileName)) {
                $fileName = $fileInfo['filename'] . '_' . time() . '.' . $fileExtension;
            }

            $destination = $targetDir . $fileName;

            // 移動檔案
            if (move_uploaded_file($fileTmpPath, $destination)) {
                // 刪除舊圖片
                if (!empty($existingPictureName) && file_exists($targetDir . $existingPictureName)) {
                    $updatePicSql = "UPDATE teacher_images SET valid = 0 WHERE img = '$existingPictureName' AND teacher_id = '$teacher_id'";
                    if ($conn->query($updatePicSql) !== TRUE) {
                        echo "錯誤: " . $conn->error;
                        $conn->close();
                        exit;
                    }
                }

                // 插入新圖片
                $pictureSql = "INSERT INTO teacher_images (teacher_id, img, valid) VALUES ('$teacher_id', '$fileName', 1)";
                if ($conn->query($pictureSql) !== TRUE) {
                    echo "更新圖片錯誤: " . $conn->error;
                    $conn->close();
                    exit;
                }
            } else {
                echo "圖片上傳失敗";
                $conn->close();
                exit;
            }
        } else {
            // 如果沒有上傳新圖片，僅更新現有圖片為有效
            if (!empty($existingPictureName)) {
                $updatePicSql = "UPDATE teacher_images SET valid = 1 WHERE img = '$existingPictureName' AND teacher_id = '$teacher_id'";
                if ($conn->query($updatePicSql) !== TRUE) {
                    echo "錯誤: " . $conn->error;
                    $conn->close();
                    exit;
                }
            }
        }

        // 成功後返回 JSON
        $response = ['success' => true];
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // 更新資料錯誤，返回 JSON
        $response = ['success' => false, 'error' => "更新資料錯誤: " . $conn->error];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    $conn->close();
} else {
    // 無效的請求方法，返回 JSON
    $response = ['success' => false, 'error' => "無效的請求方法"];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
