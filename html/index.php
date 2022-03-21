<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>PHPスキルテスト</title>
</head>
<body>
  <h1>お問い合わせ</h1>

  <?php if (!empty($errmessage)) : ?>
    <?php foreach ($errmessage as $value): ?>
      <div><p style='color: red;'><?= $value?></p></div>
    <?php endforeach;?>
  <?php endif; ?>
  
  <form action="post.php" method="post">
    <p>
      <label class="label" for="subject" style="font-weight: bold;">件名</label><small style="color: gray;"> 選択必須</small><br>
      <select name="subject" id="subject" required>
          <option value="">件名を選択してください</option>
          <!-- セッションに値が保存されていたら、その値のoptionをselectedにする -->
          <option value="ご意見" <?php if ($_SESSION['subject'] == "ご意見") {echo 'selected';} ?>>ご意見</option>
          <option value="ご感想" <?php if ($_SESSION['subject'] == "ご感想") {echo 'selected';} ?>>ご感想</option>
          <option value="その他" <?php if ($_SESSION['subject'] == "その他") {echo 'selected';} ?>>その他</option>
      </select>
    </p>
    <p>
      <label class="label" for="name" style="font-weight: bold;">名前</label> (50字以内)<small style="color: gray;"> 入力必須</small><br>
      <!-- セッションに値が保存されていたら、その値をvalueに設定 -->
      <input type="text" name="name" id="name" value="<?php if (!empty($_SESSION['name'])) {echo $_SESSION['name'];} ?>" required>
    </p>
    <p>
      <label class="label" for="email" style="font-weight: bold;">メールアドレス</label> (50字以内)<small style="color: gray;"> 入力必須</small><br>
      <input type="email" name="email" id="email" value="<?php if (!empty($_SESSION['email'])) {echo $_SESSION['email'];} ?>" required>
    </p>
    <p>
      <label class="label" for="tel" style="font-weight: bold;">電話番号</label> (ハイフンなし)<small style="color: gray;"> 入力必須</small><br>
      <input type="tel" name="tel" id="tel" value="<?php if (!empty($_SESSION['tel'])) {echo $_SESSION['tel'];} ?>" required>
    </p>
    <p>
      <label class="label" for="message" style="font-weight: bold;">お問い合わせ内容</label> (500字以内)<small style="color: gray;"> 入力必須</small><br>
      <textarea name="message" id="message" cols="50" rows="5" required><?php if (!empty($_SESSION['message'])) :?><?= $_SESSION['message']; ?> <?php endif; ?></textarea>
    </p>

    <!-- CSRF対策のためのトークンを生成 -->
    <?php
    $token = bin2hex(random_bytes(32));
    $_SESSION["token"] = $token;
    ?>
    <!-- トークンを一緒に送信 -->
    <input type="hidden" name="token" value="<?= $_SESSION["token"]; ?>">

    <p><input type="submit" name="submitBtn" value="入力内容確認に進む"></p>
  </form>
</body>
</html>