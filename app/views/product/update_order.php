<?php
// Kết nối MySQL
$conn = new mysqli("localhost", "root", "", "my_store");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra dữ liệu gửi từ AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST["productId"];
    $newQuantity = $_POST["quantity"];

    // Cập nhật số lượng và giá thành trong order_details
    $sql = "UPDATE order_details od 
            JOIN product p ON od.product_id = p.id 
            SET od.quantity = ?, od.price = ? * p.price 
            WHERE od.product_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $newQuantity, $newQuantity, $productId);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Cập nhật thành công"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi khi cập nhật"]);
    }

    $stmt->close();
}
$conn->close();
?>
