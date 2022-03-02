<?php
    session_start();
    $subject = $_SESSION['subject'];
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $tel = $_SESSION['tel'];
    $message = $_SESSION['message'];

    //言語と文字コードの使用宣言
    mb_language("ja");
    mb_internal_encoding("UTF-8");

    // 送信情報を登録
    $from = "g031l116@gmail.com";
    $to = $from . " , " . $email;
    $mailSub = "お問い合わせ内容確認";
    $mailMessage =
        "<html>
        <body>
        <h1>以下の内容で受付ました</h1>
        <p>件名：" . $subject . "</p>
        <p>お名前：" . $name . "</p>
        <p>メールアドレス：" . $email . "</p>
        <p>電話番号：" . $tel . "</p>
        <p>お問い合わせ内容：" . $message . "</p>
        </body>
        </html>";
    $headers = "From: " . $from;
    $headers .= "\r\n";
    $headers .= "Content-type: text/html; charset=ISO-2022-JP";

    //メール送信
    mb_send_mail($to, $mailSub, $mailMessage, $headers);

    include "complete.html";
?>