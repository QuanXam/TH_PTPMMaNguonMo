<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
class ProductController
{
private $productModel;
private $categoryModel;
private $db;
public function __construct()
{
$this->db = (new Database())->getConnection();
$this->productModel = new ProductModel($this->db);
$this->categoryModel = new CategoryModel($this->db); 
}

public function index()
{
    $category_id = isset($_GET['category']) ? intval($_GET['category']) : null;

    if ($category_id) {
        $products = $this->productModel->getProductsByCategory($category_id);
    } else {
        $products = $this->productModel->getProducts();
    }

    $categories = $this->categoryModel->getCategories();
    include 'app/views/product/list.php';
}



public function show($id)
{
$product = $this->productModel->getProductById($id);
if ($product) {
include 'app/views/product/show.php';
} else {
echo "Không thấy sản phẩm.";
}
}

public function add()
{
    // Kiểm tra nếu người dùng không phải admin
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: /Product"); // Chuyển hướng về danh sách sản phẩm
        exit();
    }

    // Lấy danh sách danh mục sản phẩm
    $categories = (new CategoryModel($this->db))->getCategories();
    include 'app/views/product/add.php';
}

public function save()
{
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$price = $_POST['price'] ?? '';
$category_id = $_POST['category_id'] ?? null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
$image = $this->uploadImage($_FILES['image']);
} else {
$image = "";
}
$result = $this->productModel->addProduct($name, $description, $price,

$category_id, $image);

if (is_array($result)) {
$errors = $result;
$categories = (new CategoryModel($this->db))->getCategories();
include 'app/views/product/add.php';
} else {
    header('Location: /Product');
}
}
}

public function edit($id)
{
    
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: /Product");
        exit();
    }

    $product = $this->productModel->getProductById($id);
    $categories = (new CategoryModel($this->db))->getCategories();
    
    if ($product) {
        include 'app/views/product/edit.php';
    } else {
        echo "Không thấy sản phẩm.";
    }
}

public function update()
{
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$id = $_POST['id'];
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$category_id = $_POST['category_id'];
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
$image = $this->uploadImage($_FILES['image']);
} else {
$image = $_POST['existing_image'];
}
$edit = $this->productModel->updateProduct($id, $name, $description,

$price, $category_id, $image);
if ($edit) {
header('Location: /Product');
} else {
echo "Đã xảy ra lỗi khi lưu sản phẩm.";
}
}
}

public function delete($id)
{
    session_start();
    
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: /Product");
        exit();
    }

    if ($this->productModel->deleteProduct($id)) {
        header("Location: /Product");
    } else {
        echo "Đã xảy ra lỗi khi xóa sản phẩm.";
    }
}

private function uploadImage($file)
{
$target_dir = "uploads/";
// Kiểm tra và tạo thư mục nếu chưa tồn tại
if (!is_dir($target_dir)) {
mkdir($target_dir, 0777, true);
}
$target_file = $target_dir . basename($file["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Kiểm tra xem file có phải là hình ảnh không
$check = getimagesize($file["tmp_name"]);
if ($check === false) {
throw new Exception("File không phải là hình ảnh.");
}
// Kiểm tra kích thước file (10 MB = 10 * 1024 * 1024 bytes)
if ($file["size"] > 10 * 1024 * 1024) {
throw new Exception("Hình ảnh có kích thước quá lớn.");
}
// Chỉ cho phép một số định dạng hình ảnh nhất định
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType !=
"jpeg" && $imageFileType != "gif") {

throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
}
// Lưu file
if (!move_uploaded_file($file["tmp_name"], $target_file)) {
throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
}
return $target_file;
}
public function addToCart($id)
{
$product = $this->productModel->getProductById($id);
if (!$product) {
echo "Không tìm thấy sản phẩm.";
return;
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity']++;
    } else {
    $_SESSION['cart'][$id] = [
    'name' => $product->name,
    'price' => $product->price,
    'quantity' => 1,
    'image' => $product->image
    ];
    }
    header('Location: /Product/cart');
    }
    public function cart()
    {
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    include 'app/views/product/cart.php';
    }

    public function checkout()
{


    if (!isset($_SESSION['username'])) {
        header('Location: account/login'); // Chuyển hướng đến trang đăng nhập
        exit;
    }

    include 'app/views/product/checkout.php';
}


    public function processCheckout()
    {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    // Kiểm tra giỏ hàng
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Giỏ hàng trống.";
    return;
    }
    // Bắt đầu giao dịch
    $this->db->beginTransaction();
    try {
    // Lưu thông tin đơn hàng vào bảng orders
    $query = "INSERT INTO orders (name, phone, address) VALUES (:name,

:phone, :address)";

$stmt = $this->db->prepare($query);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':phone', $phone);
$stmt->bindParam(':address', $address);
$stmt->execute();
$order_id = $this->db->lastInsertId();
// Lưu chi tiết đơn hàng vào bảng order_details
$cart = $_SESSION['cart'];
foreach ($cart as $product_id => $item) {
$query = "INSERT INTO order_details (order_id, product_id,

quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";

$stmt = $this->db->prepare($query);
$stmt->bindParam(':order_id', $order_id);
$stmt->bindParam(':product_id', $product_id);
$stmt->bindParam(':quantity', $item['quantity']);
$stmt->bindParam(':price', $item['price']);
$stmt->execute();
}


// Xóa giỏ hàng sau khi đặt hàng thành công
unset($_SESSION['cart']);
// Commit giao dịch
$this->db->commit();
// Chuyển hướng đến trang xác nhận đơn hàng
header('Location: /Product/orderConfirmation');
} catch (Exception $e) {
// Rollback giao dịch nếu có lỗi
$this->db->rollBack();
echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
}
}
}

//cap nhat gia tien
public function updateCart() {
    session_start(); // Bắt đầu session

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $quantity = $_POST['quantity'];

        // Kiểm tra sản phẩm có tồn tại trong giỏ hàng không
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $quantity;
            
            // Tính lại tổng tiền
            $totalPrice = $_SESSION['cart'][$id]['price'] * $quantity;

            // Trả về JSON để cập nhật UI ngay
            echo json_encode([
                'success' => true,
                'totalPrice' => number_format($totalPrice, 0, ',', '.')
            ]);
            exit;
        }
    }

    // Nếu có lỗi, trả về lỗi JSON
    echo json_encode(['error' => 'Cập nhật thất bại!']);
    exit;
}

public function removeFromCart() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // Chỉ khởi động session nếu chưa có
    }

    if (!isset($_POST['productId'])) {
        echo json_encode(['status' => 'error', 'message' => 'Không nhận được sản phẩm']);
        exit;
    }

    $productId = $_POST['productId'];

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]); // Xóa sản phẩm khỏi giỏ hàng
        echo json_encode(['status' => 'success', 'message' => 'Đã xóa sản phẩm']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không tồn tại']);
    }

    exit;
}





public function orderConfirmation()
{
include 'app/views/product/orderConfirmation.php';
}
}
?>