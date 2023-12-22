<?
include "header.php";
include "config.php";  
include "utils.php";     

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  s_msg("연결에 실패했습니다. 나중에 시도해주세요.");
}

$customer_no = $_POST['id'];
$sql = "SELECT * FROM customer WHERE customer_no = '$customer_no'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
s_msg("존재하지 않는 ID입니다.");
exit();

}


$sql = "SELECT return_no, returner_id, return_time, broken, payment, name FROM return_info JOIN customer WHERE returner_id = '$customer_no' and returner_id=customer.customer_no";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
s_msg("반납 내역이 존재하지 않습니다.");
exit();
}
// display the rental records in a table
echo "<table border='1'>";
echo "<tr><th>반납자 ID</th><th>반납자 성함</th><th>반납 번호</th><th>반납 시각</th><th>손망실 여부</th><th>청구 금액</th></tr>";
while ($row = $result->fetch_assoc()) {
echo "<tr><td>" . $row["returner_id"] . "</td><td>" . $row["name"] . "</td><td>"  . $row["return_no"] . "</td><td>" . $row["return_time"] . "</td><td>"  . $row["broken"] . "</td><td>". $row["payment"] . "</td></tr>";
}
echo "</table>";

?>