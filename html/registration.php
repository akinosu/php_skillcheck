<?php
// セッションから情報を取得
session_start();
$subject = $_SESSION['subject'];
$name = $_SESSION['name'];
$email = $_SESSION['email'];
$tel = $_SESSION['tel'];
$message = $_SESSION['message'];

// データベース接続
$mysqli = new Mysqli("192.168.149.129", "user", "Cnkcj@62j", "phpform");

// データベース接続エラー時
if ($mysqli->connect_errno) {
    echo "データベース接続に失敗しました";
    exit();
}

// 文字コード設定
$mysqli->set_charset('utf8');

// 特殊文字をエスケープ
$subject = $mysqli->real_escape_string($subject);
$name = $mysqli->real_escape_string($name);
$email = $mysqli->real_escape_string($email);
$tel = $mysqli->real_escape_string($tel);
$message = $mysqli->real_escape_string($message);

// トランザクション開始
$mysqli->begin_transaction();

try {
    // insert 文を準備
    $stmt = $mysqli->prepare("INSERT INTO post(subject, name, email, tel, message) VALUES (?,?,?,?,?)");
    // 変数をパラメータにバインド
    $stmt->bind_param("sssss", $subject, $name, $email, $tel, $message);
    // SQL文を実行
    $stmt->execute();
    // 正常に完了したらコミット
    $mysqli->commit();
} catch (Exception $e) {
    // エラー発生時、ロールバック
    $mysqli->rollback();
    echo "エラーが発生しました";
}

// データベースとの接続解除
$mysqli->close();

// 完了後、メール送信処理へと進む
include "mail.php";
