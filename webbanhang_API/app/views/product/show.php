<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div id="product-container" class="row align-items-center"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const productId = window.location.pathname.split("/").pop();
            fetch(`/api/product/${productId}`)
                .then(response => response.json())
                .then(product => {
                    if (!product || product.error) {
                        document.getElementById("product-container").innerHTML = 
                            `<div class='alert alert-danger text-center'>❌ Sản phẩm không tồn tại hoặc đã bị xóa.</div>`;
                        return;
                    }

                    document.getElementById("product-container").innerHTML = `
                        <div class="col-md-6">
                            <div class="p-3 bg-white rounded shadow-lg">
                                <img src="/${product.image}" alt="Product Image" class="img-fluid rounded-4 w-100" style="max-height: 400px; object-fit: contain;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded shadow-sm">
                                <h2 class="fw-bold text-dark">${product.name}</h2>
                                <p class="text-muted">${product.description}</p>
                                <p class="fw-bold text-danger fs-4">💰 ${new Intl.NumberFormat('vi-VN').format(product.price)} VND</p>
                                <div class="d-flex gap-2 mt-3">
                                    <a href="/Product/" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
                                    <a href="/Product/addToCart/${product.id}" class="btn btn-primary"><i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ hàng</a>
                                </div>
                            </div>
                        </div>
                    `;
                })
                .catch(error => console.error("Lỗi khi tải sản phẩm:", error));
        });
    </script>
</div>

<?php include 'app/views/shares/footer.php'; ?>
