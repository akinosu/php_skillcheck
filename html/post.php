<?php
// 確認画面から送信されたトークンとセッションに保存されたトークンが一致しているか確認
// 異なっていた場合、エラーメッセージと共に入力ページを表示
session_start();
if (!$_POST['token'] || !$_SESSION['token']) {
    $errmessage[] = '不正な処理が行われました';
    // セッションの初期化
    $_SESSION     = array();
    include "index.php";
} else {
    // エスケープ処理の関数を用意
    function sec($txt)
    {
        return htmlentities($txt, ENT_QUOTES, 'UTF-8');
    }

    $subject = sec($_POST["subject"]);
    $name = sec($_POST["name"]);
    $email = sec($_POST["email"]);
    $tel = sec($_POST["tel"]);
    $message = sec($_POST["message"]);

    //セッションに保存
    $_SESSION['subject'] = $subject;
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['tel'] = $tel;
    $_SESSION['message'] = $message;

    // エラーメッセージを格納する配列を用意
    $errmessage = [];

    if (50 < mb_strlen($name, 'UTF-8')) {
        // 名前の文字数を確認
        $errmessage[] = '名前は50文字以内で入力してください。';
    }
    if (50 < mb_strlen($email, 'UTF-8')) {
        // メールアドレスの文字数を確認
        $errmessage[] = 'メールアドレスは50文字以内で入力してください。';
    }
    if (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/', $email) == false) {
        // メールアドレスの文字数を確認
        $errmessage[] = 'メールアドレスの形式で入力してください。';
    }
    if (preg_match('/^[0-9]{10,11}+$/', $tel) == false) {
        // 電話番号が10桁か11桁の半角数字であるか確認
        $errmessage[] = "電話番号は10桁または11桁の半角数字で入力してください。";
    }
    if (100 < mb_strlen($subject, 'UTF-8')) {
        // 件名の文字数を確認
        $errmessage[] = '件名は100文字以内で入力してください。';
    }
    if (500 < mb_strlen($message, 'UTF-8')) {
        // 本文の文字数を確認
        $errmessage[] = 'お問い合わせ内容は500文字以内で入力してください。';
    }

    // バリデーションエラーの有無で条件分岐
    if (empty($errmessage)) {
        include "conf.html";
    } else {
        include "index.php";
    }
}
