<? include ("header.php"); ?>
    <style>
        .navbar {
            z-index:999;
        }
        h1, p {
            padding: 0px 30px 0px 30px;
            text-align:center;
        }
        h1 {
            font-weight:800;
        }
        .container {
            position: relative;
            background: rgba(255, 255, 255, .9);
        }
        .ref {
            font-weight:200;
            font-size:0.5em;
        }
    </style>
    <div class='container'>
        <h1>회원 가입</h1>
        <form>
        <p>성함 : <input type="text" name="name" placeholder="성함"> </p><br/>
        <p>ID : <input type="text" name="id" placeholder="아이디"> </p><br/>
        <p>전화번호 : <input type="text" name="phone_number" placeholder="전화번호"> </p><br/>
        <p>주소 : <input type="text" name="address" placeholder="주소"> </p><br/>
        <div style="text-align: center;">
        <button type="submit" formmethod="post" formaction="register_action.php">제출</button>
        </div>
        </form>
    </div>
        
<? include ("footer.php"); ?>