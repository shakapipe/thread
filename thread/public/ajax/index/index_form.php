<?php
define('DOCROOT', __DIR__ . '/../../');
define('APPPATH', realpath(__DIR__.'/../../../app') . '/');
require DOCROOT . 'bootstrap.php';

if(!Classes_Input::isPost())
{
	//POST以外ははじく
	header("HTTP/1.0 404 Not Found");
	return;
}

header("Content-Type: text/html; charset=UTF-8");

//コメントID
$id               = Classes_Input::post('id');
//バリデーションエラー時に使用　通常:ブランク　エラー時：1次元配列のシリアライズデータ
$selialize_post   = Classes_Input::post('post');
$selialize_errors = Classes_Input::post('errors');

//シリアライズデータをデコード　通常:ブランク　エラー時：1次元配列
$post             = empty($selialize_post)
						? array()
						: unserialize(base64_decode($selialize_post));
$errors           = empty($selialize_errors)
						? array()
						: unserialize(base64_decode($selialize_errors));

$db = new Model_Database_Thread();

$form_data = $db->select($id);

//テンプレートファイルをバッファリング
ob_flush();
ob_implicit_flush(0);

//メインコメントの場合
if(is_null($form_data['parent_id']))
{
	require APPPATH . 'view/index/main_form.php';
}
//返信コメントの場合
else
{
	require APPPATH . 'view/index/reply_form.php';
}

//文字列で読み込み
$form = ob_get_clean();

//Ajaxへ出力
echo $form;