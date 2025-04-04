<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Bán Hàng - Footer</title>
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* CSS cho Footer */
        footer {
            background-color: #222; /* Nền footer màu tối */
            color: #dcdcdc; /* Màu văn bản nhạt cho dễ đọc */
            padding: 30px 0;
        }

        footer h5 {
            color: #fff; /* Tiêu đề footer màu trắng */
            font-weight: bold;
            margin-bottom: 15px;
        }

        footer p {
            font-size: 14px;
            line-height: 1.6;
        }

        footer a {
            color: #dcdcdc; /* Màu chữ cho liên kết */
            text-decoration: none;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        footer a:hover {
            color: #ff6347; /* Màu cam cho liên kết khi hover */
            transform: scale(1.1); /* Tăng kích thước liên kết khi hover */
        }

        footer .social-icon {
            font-size: 1.5rem;
            color: #dcdcdc;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        footer .social-icon:hover {
            color: #ff6347; /* Màu cam cho biểu tượng mạng xã hội khi hover */
            transform: scale(1.2); /* Tăng kích thước biểu tượng khi hover */
        }

        footer .bg-secondary {
            background-color: #444; /* Màu nền cho dòng bản quyền */
            color: #fff; /* Màu chữ dòng bản quyền */
            padding: 20px 0;
            font-size: 14px;
        }

        /* Media Query để điều chỉnh cho các màn hình nhỏ */
        @media (max-width: 767px) {
            footer .row {
                text-align: center;
            }

            footer .col-lg-6,
            footer .col-lg-3 {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Footer -->
    <footer class="footer bg-dark text-white text-center text-lg-start mt-4">
        <div class="container p-4">
            <div class="row">
                <!-- Cột thông tin liên hệ -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <h5 class="text-uppercase">Quản lý sản phẩm</h5>
                    <p>
                        Hệ thống quản lý sản phẩm giúp bạn theo dõi và cập nhật thông tin sản phẩm dễ dàng.
                    </p>
                </div>
                <!-- Cột liên kết nhanh -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-uppercase">Liên kết nhanh</h5>
                    <ul class="list-unstyled mb-0">
                        <li><a href="/Product/" class="text-light">Danh sách sản phẩm</a></li>
                        <li><a href="/Product/add" class="text-light">Thêm sản phẩm</a></li>
                    </ul>
                </div>
                <!-- Cột mạng xã hội -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-uppercase">Kết nối với chúng tôi</h5>
                    <a href="https://www.facebook.com/jetnagatsumi.nguyen" class="text-light social-icon me-4 ms-5" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-light social-icon me-4"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/the_coding_wizard/" class="text-light social-icon mr-3" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <!-- Dòng bản quyền -->
        <div class="text-center p-3 bg-secondary text-white">
            © 2025 Quản lý sản phẩm. All rights reserved.
        </div>
    </footer>

</body>

</html>
