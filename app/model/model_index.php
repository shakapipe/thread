<?php
class Model_Index {

	/**
	 * insert
	 * @param  array POST値
	 * @return object $fieldset 入力フォーム名、バリデーション関数名、POST値
	 * @Author fujimura
	 * indexページの入力フォーム名とバリデーション関数を設定
	 */
	//indexページの入力フォームを設定
	public static function fieldset($post){

		//対象フォームをチェックするかどうかのフラグ
		$commit_chk_flg       = isset($post['commit']);
		$edit_commit_chk_flg  = isset($post['edit_commit']);
		$reply_commit_chk_flg = isset($post['reply_commit']);

		$fieldset = new Classes_Fieldset();

		//タイトル
		$fieldset->add_field('title');
		$fieldset->add_rule('required',array(@$post['title'], $commit_chk_flg));
		$fieldset->add_rule('max_length',array(@$post['title'], 90));

		//投稿本文
		$fieldset->add_field('body');
		$fieldset->add_rule('required',array(@$post['body'], $commit_chk_flg));
		$fieldset->add_rule('max_length',array(@$post['body'], 500));

		//編集タイトル
		$fieldset->add_field('edit_title');
		$fieldset->add_rule('required',array(@$post['edit_title'], $edit_commit_chk_flg));
		$fieldset->add_rule('max_length',array(@$post['edit_title'], 90));

		//編集本文
		$fieldset->add_field('edit_body');
		$fieldset->add_rule('required',array(@$post['edit_body'], $edit_commit_chk_flg));
		$fieldset->add_rule('max_length',array(@$post['edit_body'], 500));

		//返信タイトル
		$fieldset->add_field('reply_title');
		$fieldset->add_rule('required',array(@$post['reply_title'], $reply_commit_chk_flg));
		$fieldset->add_rule('max_length',array(@$post['reply_title'], 90));

		//返信本文
		$fieldset->add_field('reply_body');
		$fieldset->add_rule('required',array(@$post['reply_body'], $reply_commit_chk_flg));
		$fieldset->add_rule('max_length',array(@$post['reply_body'], 500));

		return $fieldset;
	}


	/**
	 * routengSubmitBtn
	 * @param  array POST値
	 * @return array array(DB操作ステータス,エラー配列)
	 * @Author fujimura
	 * submitボタンのPOST値を元に処理を振り分ける
	 */
	public static function routingSubmitBtn($post, $fieldset){
		$db_status = true;
		$errors    = array();

		//掲示板コメント削除時
		if(array_key_exists ('delete', $post))
		{
			//データ削除
			$db_status = self::delete($post);
		}
		//掲示板コメント編集時
		else if(array_key_exists ('edit_commit', $post))
		{
			list($db_status, $errors) = self::runDb(
											$post
											,'self::save_update'
											, $fieldset
										);
		}
		//掲示板コメント投稿時 || 返信時
		else if(array_key_exists ('commit', $post)
			|| array_key_exists ('reply_commit', $post))
		{

			list($db_status, $errors) = self::runDb(
											$post
											,'self::save_insert'
											, $fieldset
										);
		}
		//DB保存ステータス、エラー配列(保存があれば)
		return array($db_status, $errors);
	}


	/**
	 * runDb
	 * @param  array POST値
	 * @return array array(DB操作ステータス,エラー配列)
	 * @Author fujimura
	 * submitボタンのPOST値を元に処理を振り分ける
	 */
	public static function runDb($post, $savetype, $fieldset){
		//バリデーションの実行(失敗時$errorにエラーメッセージが入る)
		$errors = Model_Common::validation_run($fieldset);
		$db_status = true;
		//バリデーション成功時
		if(empty($errors))
		{
			//データセーブ
			$db_status = call_user_func_array($savetype, array($post));
		}
		//失敗
		else
		{
			//エラーメッセージ取得
			$errors = Model_Common::get_error_message($errors);
		}
		return array($db_status, $errors);
	}

	/**
	 * insert
	 * @param  array POST値
	 * @return bool  true/false
	 * @Author fujimura
	 * 返信・初投稿のデータをインサート
	 */
	public static function save_insert($post){

		//保存しない情報の削除(サブミットボタン情報)
		unset($post['commit']);
		unset($post['reply_commit']);

		//レコード作成日、更新日のUNIXTIME
		$post['created_at'] = time();
		$post['updated_at'] = time();

		$db = new Model_Database_Thread();

		try{
			$db->start_transaction();

			$db->insert($post);

			$db->commit_transaction();

			//POST値をクリアするためにリダイレクト
			//@todo　ちゃんとした多重POSTの対策を実装する(CSRFなど）
			header('location: http://localhost/index.php');
			exit();
		}catch (PDOException $e){
			$db->rollback_transaction();
			$e->getMessage();
			return false;
		}
	}

	/**
	 * save_update
	 * @param  array POST値
	 * @return bool true/false
	 * @Author fujimura
	 * 編集内容をアップデート
	 */
	public static function save_update($post){

		//UPDATEレコードのID
		$where_id = $post['id'];

		//保存しない情報の削除(サブミットボタン情報)
		unset($post['edit_commit']);
		unset($post['id']);

		//メイン本文の編集の時
		if(empty($post['parent_id']))
		{
			$post['title'] = $post['edit_title'];
			$post['body'] = $post['edit_body'];
		}
		//返信本文の編集の時
		else
		{
			$post['reply_title'] = $post['edit_title'];
			$post['reply_body'] = $post['edit_body'];
		}

		//不要変数アンセット
		unset($post['edit_title']);
		unset($post['edit_body']);

		//レコード作成日、更新日のUNIXTIME
		$post['updated_at'] = time();

		$db = new Model_Database_Thread();

		try{
			$db->start_transaction();

			$db->update($post, $where_id);

			$db->commit_transaction();

			//POST値をクリアするためにリダイレクト
			//@todo　ちゃんとした多重POSTの対策を実装する(CSRFなど）
			header('location: http://localhost/index.php');
			exit();
		}catch (PDOException $e){
			var_dump($e);exit;
			$db->rollback_transaction();
			$e->getMessage();
			return false;
		}
	}

	/**
	 * delete
	 * @param  array POST値
	 * @return bool true/false
	 * @Author fujimura
	 * 掲示板コメント削除
	 */
	public static function delete($post){

		//保存しない情報の削除(サブミットボタン情報)
		unset($post['delte']);

		$db = new Model_Database_Thread();

		//紐づく子コメントを取得（なし・子コメントIDの場合、array())
		$reply_list = $db->get_reply_list($post['id']);

		try{
			$db->start_transaction();

			//メインコメント削除
			$db->delete($post['id']);

			//メインに紐つく返信コメント削除
			foreach($reply_list as $reply_val){
				$db->delete($reply_val['id']);
			}

			$db->commit_transaction();

			//POST値をクリアするためにリダイレクト
			//@todo　ちゃんとした多重POSTの対策を実装する(CSRFなど）
			header('location: http://localhost/index.php');
			exit();
		}catch (PDOException $e){
			var_dump($e);exit;
			$db->rollback_transaction();
			$e->getMessage();
			return false;
		}
	}

	/**
	 * getCommentList
	 * @param  array POST値
	 * @return object $thread_list コメントリスト
	 * @Author fujimura
	 * コメントリストの取得
	 */
	//indexページの入力フォームを設定
	public static function getCommentList(){

		$db = new Model_Database_Thread();

		try{
			//メインのコメントリスト取得
			$thread_list = $db->get_main_list();

			foreach($thread_list as $list_key => $comment){
				$thread_list[$list_key]['reply'] = $db->get_reply_list($comment['id']);
			}

		}catch (PDOException $e){
			var_dump($e);exit;
			$e->getMessage();
			return false;
		}
		return $thread_list;
	}
}