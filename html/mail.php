<?php
    // 言語と文字コードの使用宣言
    mb_language("ja");
    mb_internal_encoding("UTF-8");

    // 送信情報を登録
    $from = "g031l116@gmail.com";
    $to = $from . " , " . $email;
    $mailSub = "お問い合わせ内容確認";
    // バッファスタート
    ob_start();
    include("mailmessage.html");
    // バッファ終了＆変数取得
    $mailMessage = ob_get_clean();
    $headers = "From: " . $from;
    $headers .= "\r\n";
    $headers .= "Content-type: text/html; charset=ISO-2022-JP";

    // メール送信
    mb_send_mail($to, $mailSub, $mailMessage, $headers);

    // セッションの初期化
    $_SESSION     = array();
    
    include "complete.html";
