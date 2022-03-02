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
	echo $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
}

// 文字コード設定
$mysqli->set_charset('utf8');

$query = "INSERT INTO post(subject, name, email, tel, message) VALUES ('$subject', '$name', '$email', '$tel', '$message')";
$mysqli->query($query);

// データベースとの接続解除
$mysqli->close();

//完了後、メール送信処理へと進む
header('Location: ./mail.php');
?>