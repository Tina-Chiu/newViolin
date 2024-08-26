<?php
require_once("../db_connect.php");

$teacher_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($teacher_id > 0) {
    $sql = "UPDATE teacher_info SET valid = 0 WHERE teacher_id = $teacher_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: teacherIndex.php");
        exit();
    } else {
        echo "刪除資料錯誤: " . $conn->error;
    }
}

$conn->close();
?>
