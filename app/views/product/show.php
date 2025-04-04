<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <?php if ($product): ?>
        <div class="row align-items-center">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-md-6">
                <div class="p-3 bg-white rounded shadow-lg">
                    <img src="/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                         alt="Product Image" 
                         class="img-fluid rounded-4 w-100"
                         style="max-height: 400px; object-fit: contain;">
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-6">
                <div class="p-4 bg-light rounded shadow-sm">
                    <h2 class="fw-bold text-dark"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?></p>
                    <p class="fw-bold text-danger fs-4">💰 <?php echo number_format($product->price, 0, ',', '.'); ?> VND</p>

                    <!-- Nút quay lại và thêm vào giỏ hàng -->
                    <div class="d-flex gap-2 mt-3">
                        <a href="/Product/" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Quay lại
                        </a>
                        <a href="/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-primary">
                            <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ hàng
                        </a>

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning">
                                <i class="fa-solid fa-pen-to-square"></i> Sửa
                            </a>
                            <a href="/Product/delete/<?php echo $product->id; ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                <i class="fa-solid fa-trash"></i> Xóa
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger text-center">
            ❌ Sản phẩm không tồn tại hoặc đã bị xóa.
        </div>
    <?php endif; ?>
</div>


<!-- Import Font Awesome để dùng icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<?php include 'app/views/shares/footer.php'; ?>
