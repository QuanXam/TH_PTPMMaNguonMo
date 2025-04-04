<?php include 'app/views/shares/header.php'; ?>

<div class="container text-center mt-4">
    <div class="mb-4 shadow-lg rounded overflow-hidden"> 
        <a id="banner-link" href="http://webbanhang.com/Product" target="_blank">    
            <img id="banner" src="https://img.freepik.com/free-psd/black-friday-super-sale-facebook-cover-banner-template_120329-5178.jpg?t=st=1740973650~exp=1740977250~hmac=540051e1295b375f99a861f8cd715470d31c15d948fd5684121eccb11dac65e0&w=1380" 
                class="d-block w-100 rounded" 
                style="max-height: 400px; object-fit: cover;" 
                alt="Banner">
        </a>
    </div>
    <script>
        // Danh sách ảnh banner và liên kết tương ứng
        const banners = [
            {
                img: "https://img.freepik.com/free-psd/black-friday-super-sale-facebook-cover-banner-template_120329-5178.jpg?t=st=1740973650~exp=1740977250~hmac=540051e1295b375f99a861f8cd715470d31c15d948fd5684121eccb11dac65e0&w=1380",
                link: "http://webbanhang.com/Product"
            },
            {
                img: "https://img.pikbest.com/templates/20240520/bright-blue-sale-banner-decorates-electronics-shop_10575158.jpg!bwr800",
                link: "http://webbanhang.com/Product"
            },
            {
                img: "https://img.pikbest.com/origin/10/01/53/35bpIkbEsTBzN.png!w700wp",
                link: "http://webbanhang.com/Product"
            },
            {
                img: "https://tintuc.dienthoaigiakho.vn/wp-content/uploads/2022/03/1200x628_FB-Ads-2.jpg?_gl=1*1b24gqn*_gcl_au*MTQ0MzY2NDQwNS4xNzQzNDQyMTU0",
                link: "http://webbanhang.com/Product"
            },
            {
                img: "https://img.pikbest.com/templates/20240601/june-sale-banner-in-blue-tone-decorates-electronics-shop_10593429.jpg!w700wp",
                link: "http://webbanhang.com/Product"
            }
        ];

        let currentIndex = 0; // Chỉ số banner hiện tại

        function changeBanner() {
            currentIndex = (currentIndex + 1) % banners.length; // Chuyển banner
            document.getElementById("banner").src = banners[currentIndex].img;
            document.getElementById("banner-link").href = banners[currentIndex].link;
        }

        // Đổi banner mỗi 5 giây
        setInterval(changeBanner, 5000);
    </script>

    <!-- Nút lọc sản phẩm -->
    <div class="d-flex justify-content-end mb-3">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-filter me-2"></i>
            <span class="me-2">Bộ lọc</span>
            <select id="price-filter" class="form-select w-auto">
                <option value="all">Tất cả</option>
                <option value="low">Dưới 3.000.000 VNĐ</option>
                <option value="medium">3.000.000 - 20.000.000 VNĐ</option>
                <option value="high">Trên 20.000.000 VNĐ</option>
            </select>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div id="product-list" class="row justify-content-center"></div>
</div>

<!-- Import Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchProducts();

        document.getElementById("price-filter").addEventListener("change", filterProducts);
    });

    async function fetchProducts() {
        try {
            const response = await fetch('/api/product');
            const products = await response.json();

            renderProducts(products);
        } catch (error) {
            console.error("Lỗi khi lấy danh sách sản phẩm:", error);
        }
    }

    function renderProducts(products) {
        const productList = document.getElementById("product-list");
        productList.innerHTML = "";

        products.forEach(product => {
            const productCard = document.createElement("div");
            productCard.className = "col-md-6 col-lg-4 mb-4 product-card";
            productCard.setAttribute("data-price", product.price);

            productCard.innerHTML = `
                <div class="card shadow-lg border-0 rounded-4 d-flex flex-column h-100">
                    <div class="ratio ratio-4x3">
                        <img src="${product.image ? '/' + product.image : 'path/to/default-image.jpg'}" 
                             alt="Product Image" class="card-img-top rounded-top-4">
                    </div>
                    
                    <div class="card-body text-center d-flex flex-column flex-grow-1">
                        <h5 class="card-title">
                            <a href="/Product/show/${product.id}" 
                               class="text-decoration-none fw-bold text-dark">
                                ${product.name}
                            </a>
                        </h5>
                        <p class="fw-bold text-danger fs-5">💰 ${new Intl.NumberFormat('vi-VN').format(product.price)} VND</p>
                        <p class="text-muted">📌 Danh mục: ${product.category_name || 'Không có'}</p>

                        <!-- Nút thao tác (chỉ hiển thị nếu là admin) -->
                        ${isAdmin() ? `
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="/Product/edit/${product.id}" class="btn btn-warning px-3">
                                <i class="fa-solid fa-pen-to-square"></i> Sửa
                            </a>
                            <button class="btn btn-danger px-3" onclick="deleteProduct(${product.id})">
                                <i class="fa-solid fa-trash"></i> Xóa
                            </button>
                        </div>
                        ` : ''}

                        <!-- Nút thêm vào giỏ hàng -->
                        <div class="mt-2">
                            <a href="/Product/addToCart/${product.id}" class="btn btn-primary w-100">
                                <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ hàng
                            </a>
                        </div>
                    </div>
                </div>
            `;

            productList.appendChild(productCard);
        });

        filterProducts();
    }

    function filterProducts() {
        const filterValue = document.getElementById("price-filter").value;
        const products = document.querySelectorAll(".product-card");

        products.forEach(product => {
            const price = parseFloat(product.getAttribute("data-price"));

            switch (filterValue) {
                case "low":
                    product.style.display = price < 3000000 ? "block" : "none";
                    break;
                case "medium":
                    product.style.display = (price >= 3000000 && price <= 20000000) ? "block" : "none";
                    break;
                case "high":
                    product.style.display = price > 20000000 ? "block" : "none";
                    break;
                case "all":
                    product.style.display = "block";
                    break;
            }
        });
    }

    async function deleteProduct(id) {
        if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
            try {
                const response = await fetch(`/api/product/${id}`, { method: "DELETE" });
                const result = await response.json();

                if (result.message === "Product deleted successfully") {
                    fetchProducts();
                } else {
                    alert("Xóa sản phẩm thất bại");
                }
            } catch (error) {
                alert("Có lỗi khi xóa sản phẩm");
            }
        }
    }

    function isAdmin() {
        return <?php echo isset($_SESSION['role']) && $_SESSION['role'] == 'admin' ? 'true' : 'false'; ?>;
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>
