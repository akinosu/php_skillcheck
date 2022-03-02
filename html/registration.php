<?php
//セッションから情報を取得
session_start();
$subject = $_SESSION['subject'];
$name = $_SESSION['name'];
$email = $_SESSION['email'];
$tel = $_SESSION['tel'];
$message = $_SESSION['message'];

// データベース接続
$mysqli = new Mysqli("192.168.149.129", "user", "Cnkcj@62j", "phpform");

if( $mysqli->connect_errno ) {
	printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

// 文字コード設定
$mysqli->set_charset('utf8');

//特殊文字をエスケープ
$name = $mysqli->real_escape_string($name);
$message = $mysqli->real_escape_string($message);

//SQL文実行
$query = "INSERT INTO post(subject, name, email, tel, message) VALUES ('$subject', '$name', '$email', '$tel', '$message')";
$mysqli->query($query);

//エラー処理
if ($mysqli->error) {
    try {   
        throw new Exception("MySQL error $mysqli->error <br> Query:<br> $query", $msqli->errno);   
    } catch(Exception $e ) {
        echo "Error No: ".$e->getCode(). " - ". $e->getMessage() . "<br >";
        echo nl2br($e->getTraceAsString());
    }
}

// データベースとの接続解除
$mysqli->close();

//完了後、メール送信処理へと進む
header('Location: ./mail.php');
?>