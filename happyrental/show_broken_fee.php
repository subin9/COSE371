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

$sql = "SELECT return_no, returner_id, return_time, broken, payment, name FROM return_info JOIN customer WHERE returner_id = '$customer_no' AND returner_id=customer.customer_no";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
s_msg("No return records found.");
exit();
}

$row = $result->fetch_assoc();
$broken = $row["broken"];


// Get the difference in hours
$hours = $interval->h + $interval->days * 24;
if ($broken > 3) {
  $payment = 34650;
} else if ($broken > 2) {
  $payment = 33000;
}
  else if ($broken>1) {
  $payment = 1650;
}
  else{
  $payment=0;	
}

// display the rental records in a table
echo "<table border='1'>";
echo "<tr><th>반납자 ID</th><th>반납자 성함</th><th>반납 번호</th><th>반납 시각</th><th>지불 예정 금액 조회</th></tr>";
echo "<tr><td>" . $row["returner_id"] . "</td><td>". $row["name"] . "</td><td>"  . $row["return_no"] . "</td><td>" . $row["return_time"] . "</td><td>" . $payment . "<d></tr>";
echo "</table>";

?>