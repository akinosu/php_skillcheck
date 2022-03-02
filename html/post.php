<?php
$subject = $_POST["subject"];
$name = $_POST["name"];
$email = $_POST["email"];
$tel = $_POST["tel"];
$message = $_POST["message"];

//セッションに保存
session_start();
$_SESSION['subject'] = $subject;
$_SESSION['name'] = $name;
$_SESSION['email'] = $email;
$_SESSION['tel'] = $tel;
$_SESSION['message'] = $message;

//入力された電話番号が数値(ハイフン含む)であるかバリデーション
if( preg_match( '/^[0-9]+$/', $tel ) ) {
	// $telが電話番号である場合
    include "conf.html";
}else{
	// $telが電話番号でない場合
    include "error.html";
}
?>