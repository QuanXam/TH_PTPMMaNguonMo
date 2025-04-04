<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5">
    <h1 class="text-center mb-4">🛒 Giỏ hàng của bạn</h1>

    <?php if (!empty($cart)): ?>
        <div class="card shadow p-4">
            <table class="table table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $totalAmount = 0; // Biến lưu tổng tiền
                        foreach ($cart as $id => $item): 
                            $subtotal = $item['price'] * $item['quantity']; // Tính tổng tiền từng sản phẩm
                            $totalAmount += $subtotal; // Cộng vào tổng tiền đơn hàng
                    ?>
                        <tr id="product-row-<?php echo $id; ?>">
                            <td>
                                <?php if (!empty($item['image'])): ?>
                                    <img src="/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="Product Image" class="rounded" style="max-width: 80px;">
                                <?php else: ?>
                                    <p>Không có ảnh</p>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="fw-bold price"><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
                            <td>
                                <input type="number" class="quantity-input form-control text-center" 
                                       data-id="<?php echo $id; ?>" 
                                       value="<?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>" 
                                       style="width: 80px;" min="1">
                            </td>
                            <td class="total-price" data-id="<?php echo $id; ?>">
                                <?php echo number_format($subtotal, 0, ',', '.'); ?> VND
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-product" data-id="<?php echo $id; ?>">
                                    🗑 Xóa
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Tổng tiền đơn hàng -->
            <div class="text-end mt-3">
                <h4 class="fw-bold">Tổng tiền: <span id="total-amount"><?php echo number_format($totalAmount, 0, ',', '.'); ?></span></h4>
            </div>

        </div>
    <?php else: ?>
        <p class="text-center text-muted fs-4">Giỏ hàng của bạn đang trống.</p>
    <?php endif; ?>

    <div class="d-flex justify-content-between mt-4">
        <a href="/Product" class="btn btn-secondary btn-lg">🔄 Tiếp tục mua sắm</a>
        <a href="/Product/checkout" class="btn btn-primary btn-lg btn-checkout">💳 Thanh Toán</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
    $(".quantity-input").on("input", function () {
        var productId = $(this).data("id"); // Lấy ID sản phẩm
        var newQuantity = parseInt($(this).val()); // Lấy số lượng mới
        var priceElement = $(this).closest("tr").find(".price"); // Ô chứa giá tiền sản phẩm
        var totalPriceElement = $(this).closest("tr").find(".total-price"); // Ô thành tiền cần cập nhật

        var price = parseInt(priceElement.text().replace(/\D/g, '')); // Lấy giá sản phẩm (loại bỏ ký tự không phải số)

        // Kiểm tra giá trị nhập hợp lệ
        if (newQuantity < 1 || isNaN(newQuantity)) {
            newQuantity = 1;
            $(this).val(1);
        }

        // Tính lại tổng giá trị của sản phẩm này
        var newTotalPrice = newQuantity * price;

        // Cập nhật giá trị mới vào cột Thành tiền (định dạng số tiền đẹp)
        totalPriceElement.text(newTotalPrice.toLocaleString("vi-VN") + " VND");

        // **GỌI CẬP NHẬT TỔNG TIỀN**
        updateTotalAmount();

        // Gửi AJAX để cập nhật giỏ hàng trên server
        $.ajax({
            url: "/Product/updateOrderDetails",
            type: "POST",
            data: { productId: productId, quantity: newQuantity },
            success: function (response) {
                var result = JSON.parse(response);
                if (result.status !== "success") {
                    alert(result.message);
                }
            }
        });
    });

    $(".delete-product").on("click", function () {
        var productId = $(this).data("id");
        var row = $("#product-row-" + productId);

        if (confirm("Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?")) {
            $.ajax({
                url: "/Product/removeFromCart",
                type: "POST",
                data: { productId: productId },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        row.fadeOut(300, function () {
                            $(this).remove();
                            updateTotalAmount();
                        });
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert("Lỗi kết nối đến server!");
                }
            });
        }
    });

    function checkCartEmpty() {
        if ($(".total-price").length === 0) {
            $(".btn-checkout").hide(); // Ẩn nút Thanh Toán
        } else {
            $(".btn-checkout").show(); // Hiển thị nếu có sản phẩm
        }
    }


    
    
    $(".btn-checkout").on("click", function (e) {
        var isLoggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;

        if (!isLoggedIn) {
            e.preventDefault();
            alert("Bạn cần đăng nhập để tiếp tục thanh toán!");
            window.location.href = "/account/login"; // Chuyển hướng đến trang đăng nhập
        } else {
            var totalPrice = parseFloat($(".total-price").text().replace(/\D/g, "")) || 0; // Lấy số từ chuỗi
            if (totalPrice === 0) {
                e.preventDefault();
                alert("Giỏ hàng của bạn đang trống, không thể thanh toán!");
            } else {
                window.location.href = "/orderConfirmation.php"; // Chuyển hướng đến trang xác nhận đơn hàng
            }
        }
    });


    checkCartEmpty();

    // **Hàm cập nhật tổng tiền**
    function updateTotalAmount() {
        var totalAmount = 0;
        $(".total-price").each(function () {
            var price = parseInt($(this).text().replace(/\D/g, ''));
            totalAmount += price;
        });

        // Cập nhật tổng tiền hiển thị
        $("#total-amount").text(totalAmount.toLocaleString("vi-VN") + " VND");
    }

    updateTotalAmount(); // Chạy khi load trang
});


</script>

<?php include 'app/views/shares/footer.php'; ?>
