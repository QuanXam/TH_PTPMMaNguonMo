<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Navbar cố định trên đầu */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        /* Hiệu ứng hover cho navbar */
        .nav-link:hover {
            color: #ffcc00 !important;
            transition: all 0.3s ease-in-out;
        }

        /* Bóng đổ và hiệu ứng card */
        .card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* Gradient cho tiêu đề */
        .title-bg {
            background: linear-gradient(45deg, #007bff, #00c6ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2rem;
        }

        /* Hiệu ứng nút */
        .btn-custom {
            transition: all 0.3s ease-in-out;
        }
        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
<i class="fa-brands fa-phoenix-framework ms-3" style="color: #d60000; font-size: 2rem;"></i>
<i class="fa-brands fa-d-and-d ms-1" style="color: #bac700; font-size: 2rem; margin-right: 1px;"></i>
    <div class="dropdown ms-3">
        <a class="navbar-brand fw-bold dropdown-toggle d-flex align-items-center gap-2" 
            href="#" id="categoryDropdown" role="button" 
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-bars"></i> Danh mục
        </a>
        <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2" aria-labelledby="categoryDropdown">
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="/Product">
                    <i class="fa-solid fa-boxes-stacked text-primary"></i> Tất cả sản phẩm
                </a>
            </li>
            <?php foreach ($categories as $category): ?>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" 
                    href="/Product?category=<?php echo $category->id; ?>">
                        <?php
                        // Gán icon theo từng danh mục (chỉnh sửa tùy theo DB của bạn)
                        $categoryIcons = [
                            'Điện thoại' => 'fa-mobile-screen-button text-info',
                            'Laptop' => 'fa-laptop text-success',
                            'Máy tính bảng' => 'fa-solid fa-tablet-alt text-info',
                            'Phụ kiện' => 'fa-plug-circle-bolt text-warning',
                            'Thiết bị âm thanh' => 'fa-volume-up text-primary',
                        ];
                        $iconClass = $categoryIcons[$category->name] ?? 'fa-tag text-secondary';
                        ?>
                        <i class="fa-solid <?php echo $iconClass; ?>"></i> 
                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white fw-bold d-flex align-items-center gap-2" 
                    href="#" id="productDropdown" 
                    role="button" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false">
                    <i class="fa-solid fa-boxes-stacked"></i> Quản lý sản phẩm
                </a>
                <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2" aria-labelledby="productDropdown">
                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="/Product">
                        <i class="fa-solid fa-list text-primary"></i> Danh sách sản phẩm</a>
                    </li>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="/Product/add">
                        <i class="fa-solid fa-plus text-success"></i> Thêm sản phẩm</a>
                    </li>
                    <?php endif; ?>

                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="/Product/Cart">
                        <i class="fa-solid fa-cart-shopping text-danger"></i> Giỏ hàng</a>
                    </li>
                </ul>
            </li>


                <?php if (SessionHelper::isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold">
                            <i class="fa-solid fa-circle-user"></i> <?= $_SESSION['username'] ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="/account/logout">
                            <i class="fa-solid fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold" href="/account/login">
                            <i class="fa-solid fa-sign-in-alt"></i> Đăng nhập
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php 
$currentPage = $_SERVER['REQUEST_URI'];
if ($currentPage !== '/Product/cart' && $currentPage !== '/Product/orderConfirmation'): 
?>
    <!-- Container chính -->

<?php endif; ?>

<!-- Bootstrap 5 Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
