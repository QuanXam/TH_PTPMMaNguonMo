<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Thêm sản phẩm mới</h3>
                </div>
                <div class="card-body p-4">

                    <!-- Hiển thị lỗi -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Form thêm sản phẩm -->
                    <form method="POST" action="/Product/save" enctype="multipart/form-data" onsubmit="return validateForm();">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm:</label>
                            <input type="text" id="name" name="name" class="form-control" required placeholder="Nhập tên sản phẩm">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả:</label>
                            <textarea id="description" name="description" class="form-control" required placeholder="Nhập mô tả sản phẩm"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Giá:</label>
                            <input type="number" id="price" name="price" class="form-control" step="0.01" required placeholder="Nhập giá sản phẩm">
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Danh mục:</label>
                            <select id="category_id" name="category_id" class="form-select" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>">
                                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Hình ảnh:</label>
                            <input type="file" id="image" name="image" class="form-control">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Thêm sản phẩm
                            </button>
                            <a href="/Product" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Font Awesome để dùng icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<?php include 'app/views/shares/footer.php'; ?>
