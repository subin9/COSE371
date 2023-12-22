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
$station = $_POST['station'];

// check if the customer exists
$sql = "SELECT * FROM customer WHERE customer_no = '$customer_no'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
s_msg ("존재하지 않는 회원입니다. 회원가입 해 주세요.");
echo "<script>location.replace('register.php');</script>";
}
else {
	$row = $result->fetch_assoc();
	$late=$row['late'];
	if ($late==1){
		s_msg ("이미 대여중이거나 반납하지 않은 배터리가 있습니다.");
        echo "<script>location.replace('index.php');</script>";
        exit();
	}
}
// check if the battery exists and is available
$sql = "SELECT * FROM battery WHERE occupant = 0 AND location = '$station'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
	s_msg("대여 가능한 배터리가 없습니다.");
	echo "<script>location.replace('index.php');</script>";
    mysqli_close($conn);
    exit();
}
else{
	$row = $result->fetch_assoc();
	$battery_no = $row['battery_no'];
}

// generate a borrow number and time
$borrow_no = uniqid();
$borrow_time = date("Y-m-d H:i");
// insert a record into the borrow table
$sql = "INSERT INTO borrow (borrow_no, borrow_time, borrower_id, battery_id, returned) VALUES ('$borrow_no', '$borrow_time', '$customer_no', '$battery_no', 0)";
if ($conn->query($sql) === TRUE) {

} else {
    mysqli_query($conn, "rollback");
    mysqli_close($conn);
	s_msg ("Error: " . $sql . "<br>" . $conn->error);
	echo "<script>location.replace('index.php');</script>";

}
$sql = "UPDATE customer SET late = 1 WHERE customer_no = '$customer_no'";
if ($conn->query($sql) === TRUE) {
} else {
	mysqli_query($conn, "rollback");
    mysqli_close($conn);
	s_msg ("Error: " . $sql . "<br>" . $conn->error);
	echo "<script>location.replace('index.php');</script>";
 }
// update the battery table to set the occupant
$sql = "UPDATE battery SET occupant = 1, location='$station' WHERE battery_no = '$battery_no'";
if ($conn->query($sql) === TRUE) {
	mysqli_query($conn, "commit");
    mysqli_close($conn);
	s_msg ("대여가 완료되었습니다.");
	echo "<script>location.replace('index.php');</script>";
} else {
	mysqli_query($conn, "rollback");
    mysqli_close($conn);
	s_msg ("Error: " . $sql . "<br>" . $conn->error);
	echo "<script>location.replace('index.php');</script>";
 }
?>