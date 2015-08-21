<?php
//@todo コントローラー・ルーティングクラス未実装（いつか時間が有れば・・・）
//@todo 保存テーブルは二つに分ける
//@todo クラスファイル名がかなりめんどい事になっているので、それも時間があれば対応。
//【課題】オブジェクト指向とは言い難いのでクラスの使用方法とオブジェクト指向の理解を深める
//			⇒アクセス修飾子が開放的すぎる、抽象クラスの適切な使用、取り入れれる所にはデザインパターンを使ってみるなど
//			HTMLの組み方が全然駄目


//pathの定数設定
define('DOCROOT', __DIR__ . '/');
define('APPPATH', realpath(__DIR__.'/../app') . '/');

//bootstrapでclass直下、model直下のクラスをオートロード
require DOCROOT . 'bootstrap.php';

//POST値取得、初期表示時は空
$post = Classes_Input::postAll();
//バリデーションエラー時のフォームを開いた状態	保持に使用：フォームliタグのクラス名
$post_form = Classes_Input::post('post_form');
if(isset($post['post_form'])) unset($post['post_form']);
$errors = array();

//入力フォームのフィールド名と、バリデーション関数名をセットする
$fieldset = Model_Index::fieldset($post);

//postされた時
if(!empty($post) && Classes_Input::isPost())
{
	//submitボタンのPOST値を元に処理を振り分ける
	list($db_status, $errors) = Model_index::routingSubmitBtn($post, $fieldset);
}


//掲示板のリストデータ取得
$thread_list = Model_Index::getCommentList();

//エスケープ処理
$thread_list = Classes_Input::escape($thread_list);

//テンプレートファイルの読み込み
require APPPATH . 'view/index/template.php';


