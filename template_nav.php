<style>
  :root {
    --aside-width: 240px;
    --top-width: 72px;
    --aside-bg: #2D2116;
    --active-btn: #B7785F;
  }

  .left-aside {
    width: var(--aside-width);
    padding-top: var(--top-width);
    background: var(--aside-bg);
  }

  .nav-item {
    background: var(--aside-bg);
    text-decoration: none;
    color: white;
  }

  .btn-active {
    background: var(--active-btn);
    border-color: var(--active-btn);
  }

  a {
    text-decoration: none;
  }
</style>


<aside class="left-aside border-end vh-100 position-fixed top-0 start-0 overflow-auto">
  <ul class="list-unstyled">
    <li><a class="d-block px-3 py-3 nav-item rounded-0 <?= $cateNum == 0 ? "btn-active" : "" ?>" href="<?= $cateNum == -1 ? "." : ".." ?>/coupon/couponIndex.php"><i class="fa-solid fa-table-cells-large me-2 fa-fw"></i><?= $cate_ary[0] ?></a></li>
    <li><a class="d-block px-3 py-3 nav-item rounded-0 <?= $cateNum == 1 ? "btn-active" : "" ?>" href="<?= $cateNum == -1 ? "." : ".." ?>/user/users.php"><i class="fa-solid fa-user-group me-2 fa-fw"></i><?= $cate_ary[1] ?></a></li>
    <li><a class="d-block px-3 py-3 nav-item rounded-0 <?= $cateNum == 2 ? "btn-active" : "" ?>" href="<?= $cateNum == -1 ? "." : ".." ?>/product"><i class="fa-solid fa-box me-2 fa-fw"></i><?= $cate_ary[2] ?></a></li>
    <li><a class="d-block px-3 py-3 nav-item rounded-0 <?= $cateNum == 3 ? "btn-active" : "" ?>" href="<?= $cateNum == -1 ? "." : ".." ?>/course"><i class="fa-solid fa-chalkboard me-2 fa-fw"></i><?= $cate_ary[3] ?></a></li>
    <li><a class="d-block px-3 py-3 nav-item rounded-0 <?= $cateNum == 4 ? "btn-active" : "" ?>" href="<?= $cateNum == -1 ? "." : ".." ?>/teacher/teacherIndex.php"><i class="fa-solid fa-chalkboard-user me-2 fa-fw"></i><?= $cate_ary[4] ?></a></li>
    <li><a class="d-block px-3 py-3 nav-item rounded-0 <?= $cateNum == 5 ? "btn-active" : "" ?>" href="<?= $cateNum == -1 ? "." : ".." ?>/writing/writing.php"><i class="fa-solid fa-newspaper me-2 fa-fw"></i><?= $cate_ary[5] ?></a></li>
    <li><a class="d-block px-3 py-3 nav-item rounded-0 <?= $cateNum == 6 ? "btn-active" : "" ?>" href="<?= $cateNum == -1 ? "." : ".." ?>/order/orderIndex.php"><i class="fa-solid fa-list-check me-2 fa-fw"></i><?= $cate_ary[6] ?></a></li>
    <li><a class="d-block px-3 py-3 nav-item rounded-0 <?= $cateNum == 7 ? "btn-active" : "" ?>" href="<?= $cateNum == -1 ? "." : ".." ?>/product_classic"><i class="fa-solid fa-table-cells-large me-2 fa-fw"></i><?= $cate_ary[7] ?></a></li>
  </ul>
</aside>