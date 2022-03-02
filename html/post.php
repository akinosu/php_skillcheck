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

if( preg_match( '/^[0-9]+$/', $tel ) == false ) {
    //入力された電話番号が数値であるか確認
    // $telが半角数字でない場合
    $error_message = "入力された電話番号をご確認ください。半角数字以外が使用されています。";
    include "error.html";
}elseif( 50 < mb_strlen($name, 'UTF-8') ) {
    // 名前の文字数を確認
    $error_message = '名前は50文字以内で入力してください。';
    include "error.html";
}elseif( 50 < mb_strlen($email, 'UTF-8') ) {
    // メールアドレスの文字数を確認
    $error_message = 'メールアドレスは50文字以内で入力してください。';
    include "error.html";
}elseif( 100 < mb_strlen($subject, 'UTF-8') ) {
    // 件名の文字数を確認
    $error_message = '件名は100文字以内で入力してください。';
    include "error.html";
}elseif( 500 < mb_strlen($message, 'UTF-8') ) {
    // 本文の文字数を確認
    $error_message = 'お問い合わせ内容は500文字以内で入力してください。';
    include "error.html";
}else{
    include "conf.html";
}
?>