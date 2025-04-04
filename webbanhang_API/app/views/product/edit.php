<?php include 'app/views/shares/header.php'; ?>
<h1>Sửa sản phẩm</h1>
<form id="edit-product-form">
<input type="hidden" id="id" name="id">
<div class="form-group">
<label for="name">Tên sản phẩm:</label>
<input type="text" id="name" name="name" class="form-control" required>
</div>
<div class="form-group">
<label for="description">Mô tả:</label>
<textarea id="description" name="description" class="form-control" required></textarea>
</div>
<div class="form-group">
<label for="price">Giá:</label>
<input type="number" id="price" name="price" class="form-control" step="0.01" required>
</div>
<div class="form-group">
<label for="category_id">Danh mục:</label>
<select id="category_id" name="category_id" class="form-control" required>
<!-- Các danh mục sẽ được tải từ API và hiển thị tại đây -->
</select>
</div>
<button type="submit" class="btn btn-primary">Lưu thay đổi</button>
</form>
<a href="
/Product/list" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>
<?php include 'app/views/shares/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
  // Lấy id sản phẩm từ PHP
  const productId = <?= $editId ?>;

  // Lấy thông tin sản phẩm từ API
  fetch(`
  /api/product/${productId}`)
    .then(response => response.json())
    .then(data => {
      document.getElementById('id').value = data.id;
      document.getElementById('name').value = data.name;
      document.getElementById('description').value = data.description;
      document.getElementById('price').value = data.price;
      document.getElementById('category_id').value = data.category_id;
    });

  // Lấy danh mục từ API và hiển thị
  fetch('
  /api/category')
    .then(response => response.json())
    .then(data => {
      const categorySelect = document.getElementById('category_id');
      data.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        categorySelect.appendChild(option);
      });
    });

  // Xử lý form submit
  document.getElementById('edit-product-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    const jsonData = {};
    formData.forEach((value, key) => {
      jsonData[key] = value;
    });

    // Gửi thông tin sản phẩm đã cập nhật qua API
    fetch(`/api/product/${jsonData.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
      if (data.message === 'Product updated successfully') {
        location.href = '
        /Product/list';
      } else {
        alert('Cập nhật sản phẩm thất bại');
      }
    });
  });
});
</script>
