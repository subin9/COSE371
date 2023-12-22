<?
// connect to the database
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
  exit();
}

$customer_no = $_POST['id'];
$station = $_POST['station'];
$not_returned=0;
$returned=1;
// check if the customer exists
$sql = "SELECT * FROM customer WHERE customer_no = '$customer_no'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
s_msg("Customer not found.");
echo "<script>location.replace('index.php');</script>";
exit();
}

// get the borrow number and time from the borrow table
$sql = "SELECT borrow_no, borrow_time , battery_id, returned FROM borrow WHERE borrower_id = '$customer_no' AND returned = '$not_returned'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
s_msg("No borrow record found.");
echo "<script>location.replace('index.php');</script>";
exit();
}

$row = $result->fetch_assoc();
$borrow_no = $row["borrow_no"];
$borrow_time = $row["borrow_time"];
$battery_no=$row["battery_id"];
// generate a return number and time

$return_no = uniqid();
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
} else {
  $payment = min(5000 + ($hours - 48) * 2000, 34650);
} 
// insert a record into the return table

if ($payment>0){
	s_msg("납부해야 할 금액은 $payment 원입니다.");
}

$sql = "INSERT INTO return_info (return_no, return_time, returner_id, battery_id, broken, payment) VALUES ('$return_no', '$return_time', '$customer_no', '$battery_no', '$broken', '$payment')";
if ($conn->query($sql) === TRUE) {

} else {
	mysqli_query($conn, "rollback");
    mysqli_close($conn);
	s_msg("Error: " . $sql . "<br>" . $conn->error);
	exit();
}

$sql = "UPDATE borrow SET returned = 1 WHERE borrow_no = '$borrow_no'";
if ($conn->query($sql) === TRUE) {
	
} else {
	mysqli_query($conn, "rollback");
    mysqli_close($conn);
    s_msg("Error: " . $sql . "<br>" . $conn->error);
    exit();
}

$sql = "UPDATE customer SET late = 0 WHERE customer_no = '$customer_no'";
if ($conn->query($sql) === TRUE) {
} else {
	mysqli_query($conn, "rollback");
    mysqli_close($conn);
	s_msg ("Error: " . $sql . "<br>" . $conn->error);
	echo "<script>location.replace('index.php');</script>";
 }

// update the battery table to reset the occupant and broken status
$sql = "UPDATE battery SET occupant = 0, broken = '$broken', location='$station' WHERE battery_no = '$battery_no'";
if ($conn->query($sql) === TRUE) {
	s_msg("반납이 완료되었습니다.");
	mysqli_query($conn, "commit");
    mysqli_close($conn);
} else {
	mysqli_query($conn, "rollback");
    mysqli_close($conn);
    s_msg("Error: " . $sql . "<br>" . $conn->error);
    exit();
}

?>