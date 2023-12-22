<?
include "header.php";
include "config.php";  
include "utils.php";     

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  s_msg("not connected");
}

$customer_no = $_POST['id'];
$sql = "SELECT * FROM customer WHERE customer_no = '$customer_no'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
s_msg("Customer not found.");
exit();

}

$sql = "SELECT borrow_no, borrower_id, borrow_time , battery_id, returned, name FROM borrow JOIN customer WHERE borrower_id = '$customer_no' AND returned = 0 AND borrower_id=customer.customer_no";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
s_msg("No borrow record found.");
echo "<script>location.replace('index.php');</script>";
exit();
}

$row = $result->fetch_assoc();
$borrow_no = $row["borrow_no"];
$borrow_time = $row["borrow_time"];

$return_time = date("Y-m-d H:i");

$borrowDateTime = new DateTime($borrow_time);
$returnDateTime = new DateTime($return_time);

$interval = $borrowDateTime->diff($returnDateTime);

// Get the difference in hours
$hours = $interval->h + $interval->days * 24;
if ($hours <= 3) {
  $payment = 0;
} else if ($hours > 3 && $hours <= 12) {
  $payment = 1000;
} else if ($hours > 12 && $hours <= 24) {
  $payment = 3000;
} else if ($hours > 24 && $hours <= 48) {
  $payment = 5000;
} else if ($hours > 48 && $hours <= 168) {
  $payment = 5000 + ($hours - 48) * 2000;
} else {
  $payment = 34650;
}

// display the rental records in a table
echo "<table border='1'>";
echo "<tr><th>대여자 ID</th><th>대여자 성함</th><th>대여 번호</th><th>대여 시각</th><th>지불 예정 금액 조회</th></tr>";
echo "<tr><td>" . $row["borrower_id"] . "</td><td>" . $row["name"] . "</td><td>" . $row["borrow_no"] . "</td><td>" . $row["borrow_time"] . "</td><td>" . $payment . "<d></tr>";
echo "</table>";

?>