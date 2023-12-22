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

// get the rental records from the borrow table
$sql = "SELECT borrow_no, borrow_time, borrower_id , returned, name FROM borrow JOIN customer WHERE borrower_id = '$customer_no' AND borrower_id=customer.customer_no";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
s_msg("No rental records found.");
exit();
}
// display the rental records in a table
echo "<table border='1'>";
echo "<tr><th>대여자 ID</th><th>대여자 성함</th><th>대여 번호</th><th>대여 시각</th><th>반납 여부</th></tr>";
while ($row = $result->fetch_assoc()) {
echo "<tr><td>" . $row["borrower_id"] . "</td><td>" . $row["name"] . "</td><td>" . $row["borrow_no"] . "</td><td>" . $row["borrow_time"] . "</td><td>" . $row["returned"] ."</td></tr>";
}
echo "</table>";

?>