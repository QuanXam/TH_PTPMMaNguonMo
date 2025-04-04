<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3><i class="fas fa-credit-card"></i> Thanh toán</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="/Product/processCheckout">
                        <div class="form-group">
                            <label for="name"><i class="fas fa-user"></i> Họ tên:</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Nhập họ tên" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="phone"><i class="fas fa-phone"></i> Số điện thoại:</label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Nhập số điện thoại" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="address"><i class="fas fa-map-marker-alt"></i> Địa chỉ:</label>
                            <textarea id="address" name="address" class="form-control" rows="3" placeholder="Nhập địa chỉ giao hàng" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100 mt-4">
                            <i class="fas fa-check-circle"></i> Thanh toán
                        </button>
                    </form>
                    <a href="/Product/cart" class="btn btn-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left"></i> Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'app/views/shares/footer.php'; ?>
