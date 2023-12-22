<!DOCTYPE html>
<html lang='ko'>
<head>
    <title>해피렌탈</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
  rel="stylesheet"
  type="text/css"
  href="https://cdn.jsdelivr.net/gh/eunchurn/NanumSquareNeo@0.0.6/nanumsquareneo.css"
/>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<form>
    <div class='navbar fixed'>
        <div class='container'>
            <a class='pull-left title' href="index.php">해피렌탈</a>
            <ul class='pull-right'>
                <li>
                    <input type="text" name="id" placeholder="아이디">
                </li>
                <li>
                	<select name="station">
                	<option value="0">고려대역</option>
                	<option value="1">안암역</option>
                	<option value="2">신촌역</option>
                	<option value="3">서강대역</option>
                    </select>
                </li>

                <button type="submit" formmethod="post" formaction="rental.php">대여</button>
                <button type="submit" formmethod="post" formaction="show_rental.php">대여 내역</button>
                <button type="submit" formmethod="post" formaction="return.php"> 반납</button>
                <button type="submit" formmethod="post" formaction="show_return.php">반납 내역</button>
                <button type="submit" formmethod="post" formaction="show_fee.php">미납금 조회</button>
                <button type="submit" formmethod="post" formaction="show_broken_fee.php">손망실금 조회</button>
                <button type="submit" formmethod="post" formaction="register.php">회원 가입</button>
                
            </ul>
        </div>
    </div>
</form>