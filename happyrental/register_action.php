<?
include "header.php";
include "config.php";  
include "utils.php";     

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

mysqli_query($conn, "set autocommit = 0");
mysqli_query($conn, "set session transaction isolation level serializable");
mysqli_query($conn, "begin");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  s_msg("not connected");
  echo "<script>location.replace('index.php');</script>";
}

$customer_no = $_POST['id'];
$name = $_POST['name'];
$phone_number = $_POST['phone_number'];
$address = $_POST['address'];
$not_late=0;

$sql = "SELECT * FROM customer WHERE customer_no = '$customer_no'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
s_msg("동일한 아이디가 있습니다! 다른 아이디를 이용해주세요.");
echo "<script>location.replace('register.php');</script>";
mysqli_close($conn);
exit();
}

$sql = "INSERT INTO customer (customer_no, name, address, phone_number, late) VALUES (?, ?, ?, ?, 0)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $customer_no, $name, $address, $phone_number);

if ($stmt->execute() === TRUE) {
    mysqli_query($conn, "commit");
    mysqli_close($conn);
    s_msg("회원가입이 완료되었습니다.");
    echo "<script>location.replace('index.php');</script>";
} else {
    mysqli_query($conn, "rollback");
    mysqli_close($conn);
    s_msg("Error: " . $stmt->error);
    echo "<script>location.replace('register.php');</script>";
}
?>