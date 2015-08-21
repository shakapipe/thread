以下、初期設定となります。

[Xamppまでのパス]\xampp\htdocs\
配下に
[Xamppまでのパス]\xampp\htdocs\thread\public\index.php
となるようにファイルを配置


■apache
・変更ファイル
[Xamppまでのパス]\xampp\apache\conf\extra\httpd-vhosts.conf

・追加内容
serverNameは任意
----------------------------内容-------------------------------
NameVirtualHost *:80
<VirtualHost *:80>
    ServerAdmin localhost
    DocumentRoot "C:/xampp/htdocs/thread/public/"
    ServerName localhost
    ServerAlias localhost

</VirtualHost>
--------------------------------------------------------------

■hosts
・変更ファイル
C:\Windows\System32\drivers\etc.hosts

上記で設定したserverNameを名前解決しループバックアドレスに
アクセスできるようにhostsに追加
※localhostに設定した場合、デフォルトでlocalhostの名前解決の記述が
　ある為、追加の必要はなし

----------------------------内容-------------------------------
	127.0.0.1       [上記でapacheに設定したサーバーネーム]
--------------------------------------------------------------

■アクセスするURL

http://[apacheに設定したサーバーネーム]/index.php


■データベース作成
同フォルダないのthread.sqlをを実行し、DBの作成を実施


■使用方法
入力フォームに入力し、投稿ボタンをクリック



Cloud9設定

アパッチ　ドキュメントルート設定
 cd /etc/apache2/
 cd sites-available/
 sudo vim 001-cloud9.conf 
 
 アパッチリスタート
 service apache2 restart
 
 
 mysql設定
 
  mysql -u root -p
  パスワードはブランク