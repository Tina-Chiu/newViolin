<?php
include "../vars.php";
$cateNum = 6;
$pageTitle = "{$cate_ary[$cateNum]}";
include "../template_top.php";
include "../template_nav.php";
require_once("../db_connect.php");

// 設定每頁顯示的筆數
$itemsPerPage = 10;

// 獲取當前頁數，若無則預設為第1頁
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// 初始化搜尋關鍵字
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
$searchCategory = isset($_GET['category']) ? $_GET['category'] : '';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// 計算符合搜尋條件的資料總數
$sqlCount = "SELECT COUNT(*) AS total FROM Orders WHERE 1=1";
if (!empty($searchKeyword)) {
    $searchKeyword = $conn->real_escape_string($searchKeyword);
    $sqlCount .= " AND (order_id LIKE '%$searchKeyword%' OR member_account LIKE '%$searchKeyword%')";
}
if (!empty($searchCategory)) {
    $searchCategory = $conn->real_escape_string($searchCategory);
    $sqlCount .= " AND order_category = '$searchCategory'";
}
if (!empty($startDate) && !empty($endDate)) {
    $sqlCount .= " AND order_date BETWEEN '$startDate' AND '$endDate'";
}
$resultCount = $conn->query($sqlCount);
$totalItems = $resultCount->fetch_assoc()['total'];

// 計算總頁數
$totalPages = ceil($totalItems / $itemsPerPage);

// 查詢當前頁面的訂單資料
$sql = "SELECT * FROM Orders WHERE 1=1";
if (!empty($searchKeyword)) {
    $sql .= " AND (order_id LIKE '%$searchKeyword%' OR member_account LIKE '%$searchKeyword%')";
}
if (!empty($searchCategory)) {
    $sql .= " AND order_category = '$searchCategory'";
}
if (!empty($startDate) && !empty($endDate)) {
    $sql .= " AND order_date BETWEEN '$startDate' AND '$endDate'";
}
$sql .= " LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);

// 查詢訂單類別選項
$sqlCategory = "SELECT DISTINCT order_category FROM Orders";
$resultCategory = $conn->query($sqlCategory);
?>

<!doctype html>
<html lang="zh-TW">

<head>
    <title>訂單列表</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <style>
        .dialog {
            border-radius: 25px;
            border: 1px solid transparent;
            background-image: linear-gradient(to top, #fff 0%, #C8D2E4 100%);
        }

        .close {
            background-color: transparent;
            border: 1px solid transparent;
        }

        .box {
            background-color: #94AFDA;
            height: 100px;
            border-radius: 15px;
            color: #FFF;
            line-height: 80px;
        }

        .info-section {
            border-radius: 15px;
            height: 100px;
            background-color: #94AFDA;
            color: #FFF;
        }

        .info-section2 {
            height: 120px;
        }

        .fixed-bottom-pagination {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #fff;
            padding: 10px 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .main-content {
            padding-bottom: 60px;
            /* 留出底部空間給固定分頁 */
        }
    </style>
</head>

<body>
    <main class="main-content">
        <div class="pt-3 pb-3">
            <div class="p-3 bg-white shadow rounded-2 mb-4 border">
                <div class="py-2">
                    <h4>訂單列表</h4>
                    <form method="GET" action="">
                        <div class="row g-2">
                            <div class="col-3 form-floating">
                                <input type="text" class="form-control" name="search" placeholder="搜尋關鍵字" value="<?= htmlspecialchars($searchKeyword); ?>">
                                <label for="search">搜尋關鍵字</label>
                            </div>
                            <div class="col-3 form-floating">
                                <select class="form-select" name="category" aria-label="訂單類別">
                                    <option value="">所有訂單類別</option>
                                    <?php while ($category = $resultCategory->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars($category['order_category']); ?>" <?= $searchCategory == $category['order_category'] ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($category['order_category']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <label for="category">訂單類別</label>
                            </div>
                            <div class="col-2 form-floating">
                                <input type="text" class="form-control" name="start_date" id="start_date" placeholder="開始日期" value="<?= htmlspecialchars($startDate); ?>">
                                <label for="start_date">開始日期</label>
                            </div>
                            <div class="col-2 form-floating">
                                <input type="text" class="form-control" name="end_date" id="end_date" placeholder="結束日期" value="<?= htmlspecialchars($endDate); ?>">
                                <label for="end_date">結束日期</label>
                            </div>
                            <div class="col-auto d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="orderIndex.php" class="btn btn-dark btn-lg ms-2">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="bg-white shadow rounded-2 border">
                <div class="table-title mb-3 d-flex justify-content-between align-items-center p-2 rounded-top">
                    <h6 class="m-0 text-primary ms-2">訂單列表</h6>
                </div>
                <div class="p-3">
                    <table class="coupon-table table table-bordered">
                        <thead>
                            <tr>
                                <th>編號</th>
                                <th>訂單編號</th>
                                <th>會員帳號</th>
                                <th>訂單類別</th>
                                <th>訂單成立時間</th>
                                <th>訂單詳細資訊</th>
                            </tr>
                        </thead>
                        <tbody id="main_h">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($order = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($order['id']); ?></td>
                                        <td><?= htmlspecialchars($order['order_id']); ?></td>
                                        <td><?= htmlspecialchars($order['member_account']); ?></td>
                                        <td><?= htmlspecialchars($order['order_category']); ?></td>
                                        <td><?= htmlspecialchars($order['order_date']); ?></td>
                                        <td>
                                            <a href="#" class="fa-solid fa-eye" data-bs-toggle="modal" data-bs-target="#orderModal"
                                               data-order_id="<?= htmlspecialchars($order['order_id']); ?>"
                                               data-member_account="<?= htmlspecialchars($order['member_account']); ?>"
                                               data-order_category="<?= htmlspecialchars($order['order_category']); ?>"
                                               data-order_date="<?= htmlspecialchars($order['order_date']); ?>"
                                               data-product_name="<?= htmlspecialchars($order['product_name']); ?>"
                                               data-price="<?= htmlspecialchars($order['price']); ?>"
                                               data-delivery_address="<?= htmlspecialchars($order['delivery_address']); ?>"></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">0 result</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 固定分頁導航 -->
            <div class="container">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1; ?><?= !empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : ''; ?><?= !empty($searchCategory) ? '&category=' . urlencode($searchCategory) : ''; ?><?= !empty($startDate) ? '&start_date=' . urlencode($startDate) : ''; ?><?= !empty($endDate) ? '&end_date=' . urlencode($endDate) : ''; ?>">
                                &laquo; Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?><?= !empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : ''; ?><?= !empty($searchCategory) ? '&category=' . urlencode($searchCategory) : ''; ?><?= !empty($startDate) ? '&start_date=' . urlencode($startDate) : ''; ?><?= !empty($endDate) ? '&end_date=' . urlencode($endDate) : ''; ?>">
                                <?= $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1; ?><?= !empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : ''; ?><?= !empty($searchCategory) ? '&category=' . urlencode($searchCategory) : ''; ?><?= !empty($startDate) ? '&start_date=' . urlencode($startDate) : ''; ?><?= !empty($endDate) ? '&end_date=' . urlencode($endDate) : ''; ?>">
                            Next &raquo;
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
    </main>

    <!-- 訂單詳情模態框 -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog  ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">訂單詳情</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <tr>
                            <th>訂單編號</th>
                            <td id="modal_order_id"></td>
                        </tr>
                        <tr>
                            <th>會員帳號</th>
                            <td id="modal_member_account"></td>
                        </tr>
                        <tr>
                            <th>訂單類別</th>
                            <td id="modal_order_category"></td>
                        </tr>
                        <tr>
                            <th>訂單成立時間</th>
                            <td id="modal_order_date"></td>
                        </tr>
                        <tr>
                            <th>產品名稱</th>
                            <td id="modal_product_name"></td>
                        </tr>
                        <tr>
                            <th>價格</th>
                            <td id="modal_price"></td>
                        </tr>
                        <tr>
                            <th>送貨地址</th>
                            <td id="modal_delivery_address"></td>
                        </tr>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var orderModal = document.getElementById('orderModal');
            orderModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var order_id = button.getAttribute('data-order_id');
                var member_account = button.getAttribute('data-member_account');
                var order_category = button.getAttribute('data-order_category');
                var order_date = button.getAttribute('data-order_date');
                var product_name = button.getAttribute('data-product_name');
                var price = button.getAttribute('data-price');
                var delivery_address = button.getAttribute('data-delivery_address');

                document.getElementById('modal_order_id').textContent = order_id;
                document.getElementById('modal_member_account').textContent = member_account;
                document.getElementById('modal_order_category').textContent = order_category;
                document.getElementById('modal_order_date').textContent = order_date;
                document.getElementById('modal_product_name').textContent = product_name;
                document.getElementById('modal_price').textContent = price;
                document.getElementById('modal_delivery_address').textContent = delivery_address;
            });

            $('#start_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#end_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });
    </script>
</body>

</html>
