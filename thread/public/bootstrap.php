<?php
require APPPATH . 'classes/classes_autoLoader.php';

$autoLoader = new Classes_AutoLoader();
//thread/app/class、model、model/database直下をサーチ対象として登録
//必要があれば他のディレクトリも登録(DB関連等
$autoLoader->registerDir(APPPATH . 'classes');
$autoLoader->registerDir(APPPATH . 'model');
$autoLoader->registerDir(APPPATH . 'model/database');

//オートロード登録実行
$autoLoader->register();
