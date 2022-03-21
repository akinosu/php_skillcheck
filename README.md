# 開発環境

* VMWare Workstation 16
* CentOS
* PHP 8.1.3
* MySQL 8.0.28
* Apache 2.4.6

# 実装に費やした時間

約12時間

# 実装中に遭遇した問題点

### 1. mysqlのインストールを試みた際、依存関係のエラーが出力される

  **原因**
  
    mysql80-community-release-el8-3.noarch.rpmと他パッケージとの相性がよくなかった。
  
  **対処**

    $ rpm -qa | grep -i mysqlコマンドでインストールされたパッケージを確認。
    
    mysql80-community-release-el8-3.noarch.rpmを削除した後、
    mysql80-community-release-el7-5.noarch.rpmをダウンロードする。
    
    再度yum –y install mysql-community-serverでインストール。
    無事、エラーなくインストールできた。

### 2. phpをインストールし、バージョン確認もできている状態だったが、テストとしてphpinfoを表示させようとしたが、phpが機能せず、文字列がそのまま表示される

**原因**
  
    AddHandlerディレクティブで適切にハンドラを定義していなかったため、.phpファイルがPHPスクリプトとApache側で解釈されず、正常処理されなかった。
  
**対処**
  
    httpd.confに以下記述を追加
   
    AddHandler php7-script .php

参考：<https://qiita.com/bzy/items/576e85a1e44c6d54a25c>

### 3. 外部クライアントからmysqlへ接続できない

**原因**
  
    centOSのfirewallがブロックしていた。
  
**対処**

    centOSのfirewallの設定変更。

  参考：<https://qiita.com/kenjjiijjii/items/1057af2dddc34022b09e>

### 4. 電話番号カラムのデータ型がintだとエラーが出力される

**原因**
  
    int型は2147483647までしか入らないため、電話番号の桁数だと足りない。
  
**対処**

    varchar型に変更。BigIntでも可。

### 6. NICに割り当てられたipアドレスが変わってしまう

**原因**
  
    IPを固定する設定をしていなかったため、DHCPによって自動で割り当てられていた。
  
**対処**

    /etc/sysconfig/network-scripts/ifcfg-ethにIPADDR=192.168.xxx.xxxを追記。
    
    # systemctl restart networkでリスタートし反映。

参考：<https://qiita.com/routerman/items/4d19b3084fa58723830c>

### 7. フォーム入力内容が「,'" ./\=?!:;」のような記号文字列の際、エラーが出力される

**原因**
  
    [php:error] [pid 23315] [client 192.168.149.1:65131] PHP Fatal error:  Uncaught mysqli_sql_exception: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax
    
    上記のエラーの通り、シンタックスエラー。特殊記号がエスケープされていない。

### 8. メール設定するも、Call to undefined function mb_send_mail()というエラーが出力される

**原因**

    mb_send_mail()などの、マルチバイト文字列関連の関数を使うときに必要であるphp-mbstringをインストールしていなかった。

**対処**

    yum install php-mbstringでインストール。

# 工夫した点

* 入力されたデータをsessionを使用し保存。各ファイル間での受け渡し処理をなくし、コード数を減らした。

* 各入力データにおいて、文字数制限、型制限に引っかかった場合、入力ページに戻しエラー内容を表示させるようにした。

# 課題・改善点

1. 例外、エラー処理が完全ではない可能性が高い。

2. SQLインジェクション対策が完全ではない可能性が高い。

3. 入力された電話番号、メールアドレスが使用可能であるかのチェックができていない。

# 実施した動作テスト

### 1. 記号入力テスト

    対象：名前、本文欄

    方法：,'" ./\=?!:;という記号文字列を入力

    結果：エラーが出たため修正(上述)

### 2. 半角カタカナ入力テスト

    対象：名前、本文欄

    方法：半角カタカナを入力

    結果：エラー出力なし

### 3. 環境依存文字入力テスト

    対象：名前、本文欄

    方法：㌶Ⅲ⑳㏾☎㈱髙﨑という特殊文字列を入力

    結果：エラー出力なし

### 4. 閾値テスト

    対象：名前、メールアドレス、本文欄

    方法：制限値を超えて入力
    
    結果：例外処理の通り動作することを確認

# 参考サイト

* 開発環境構築

  <https://www.rem-system.com/centos-httpd-inst/#httpd-2>

* MySQL・DB操作

  <https://www.php.net/manual/ja/class.mysqli.php>
  
* session機能

  <https://www.php.net/manual/ja/session.examples.basic.php>
  
* メール機能

  <https://www.php.net/manual/ja/function.mb-send-mail.php>
  
  <https://qiita.com/tiida26/items/2ff9abfc2f8f3c521af7>
  
  <https://techtech-note.com/1388/#toc11>

* テスト、エラー処理

  <https://www.php.net/manual/ja/mysqli.real-escape-string.php>
  
  <https://www.php.net/manual/ja/mysqli.error.php#61044>
  
  <https://www.bricoleur.co.jp/blog/archives/4239>

# 修正点

### セキュリティ

* Readmeの課題・改善点にあるとおり、セキュリティ的な脆弱性がある。

* XSS・CSRF・SQLインジェクションなど代表的な攻撃手法について調査し、それらの対策をプログラムに施す

  * XSS

    * 概要

      ユーザーからの入力内容やHTTPヘッダの情報を処理し、出力するwebページにおいて、出力処理に問題がある場合、そのウェブページに悪意のあるスクリプト等を埋め込まれてしまう。これをXSS(Cross-Site Scripting / クロスサイト・スクリプティング)と呼ぶ。

    * 対策

      入力フォームにスクリプトを仕込まれるのを防ぐため、PHP標準関数であるhtmlentitiesを使用し、ユーザーから受け取った文字列に対しエスケープ処理を行った。

  * CSRF

    * 概要

        悪意のあるサイトにスクリプトを仕込んでおくことで、アクセスしてきたユーザーに意図しない操作を実行させることが出来てしまう。これをCSRF(Cross-Site Request Forgeries / クロスサイト・リクエスト・フォージェリ)と呼ぶ。

        ログイン機能の有無に限らずCSRFの対象となり得るため、ログイン不要のサイトであっても対策が必要。例えば、犯罪予告などの強制入力などがあり得る。

    * 対策

      入力画面から送信されたトークンとセッションに保存されたトークンが一致しているかチェックし、異なっていた場合、エラーメッセージと共に入力ページを表示する、という対策をとった。

      今回のお問い合わせフォームであれば、下記のような処理の流れであるので、post処理をしているのは入力画面からバリデーション処理の間のみである。

          入力画面(index.php) --[post]--> バリデーション(post.php) ---> 確認画面(conf.html) ---> 登録(registration.php) ---> メール送信(mail.php)

      なので、index.phpでトークンを生成、送信しpost.phpでチェックを行うという形になった。

  * SQLインジェクション

    * 概要

      アプリケーションのセキュリティ上の不備を意図的に利用し、アプリケーションが想定しないSQL文を実行させることにより、データベースシステムを不正に操作する攻撃方法のことを、SQLインジェクションと呼ぶ。

    * 対策

      SQL文を実行するユーザーから入力された文字列に対し、`$mysqli->real_escape_string()`を使用し、特殊文字をエスケープした。

      また、`$mysqli->prepare()`を用いプリペアドステートメントを利用することで、入力データは、数値定数や文字列定数として組み込まれ、特殊記号が含まれていた場合でも、ただの文字として扱われることになる。SQLインジェクション対策となるため、こちらも実施した。

### 全体的なコードの可読性

* 記述を統一する（「if(」と「if (」が混在している 等）

* 余計な空白を除く

* インデントを整える

    `記述方法を統一し、かつ修正漏れがないようにするため、VSCodeの拡張機能でPHPフォーマッターである「php cs fixer」を導入し整形をした。`

### index.html

* 入力必須および文字数制限があることが入力時にわかるよう明示

    `各入力欄名の後ろに上限文字数を追記した。`

### post.php

* バリデーション

  * 「入力された電話番号をご確認ください。半角数字以外が使用されています。」が少しわかりにくい。バリデーションメッセージはユーザーにどう直してほしいかを依頼する文にすべき。

    `バリデーションメッセージを「電話番号は10桁または11桁の半角数字で入力してください。」に変更した。`

  * 電話番号は桁数のチェックも行なう

    `固定電話は10桁、携帯電話は11桁なので、正規表現を^[0-9]+$から^[0-9]{10,11}+$に変更した。`

  * メールアドレスはメールアドレス形式であることのチェックも行なう
  
    以下のバリデーションを追加した。

    ```php
      elseif (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/', $email) == false) {

      $errmessage = 'メールアドレスの形式で入力してください。';

      include "index.html";

      }
    ```

  * バリデーションは一度ですべてチェックし、入力不備がある項目すべてに対してメッセージを出せたほうがよい

    `エラーメッセージを格納する配列を用意し、その配列が空でなければ全て出力するように変更した。`
  
  * バリデーションエラーでフォームに戻すとき、ユーザーの入力を破棄しない

    `セッションに値を保持している時、その値を表示するよう変更した。`

### registration.php

* システム的なエラーの内容は画面に表示せず、失敗した旨のメッセージを表示する

  * DB接続時のエラー処理を、

    ```php
    printf("Connect failed: %s\n", $mysqli->connect_error);
    ```

    上記から下記に変更した。`

    ```php
    echo "データベース接続に失敗しました";  
    ```

  * SQL実行部の例外処理のcatchには"エラーが発生しました"のみ表示させるように変更した。

* DB登録はトランザクションを使用する

  トランザクションを使用するよう以下のように変更した。

   ```php
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
  ```

* try catch の用途を再確認（DB登録時等、Exception発生時にcatchして何か処理したい場合に使う）

### mail.php

* html部分は、htmlファイルとして別に作成する

  mailmessage.htmlを作成し、html部をそちらに分離、mail.phpでバッファを使用し読み込んだhtmlファイルを変数に保存する、という方法に変更した。

   ```php
    ob_start();
    include("mailmessage.html");
    $mailMessage = ob_get_clean();
    ```
