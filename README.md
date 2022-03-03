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

2.  SQLインジェクション対策が完全ではない可能性が高い。

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
