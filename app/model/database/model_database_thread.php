<?php

class Model_Database_Thread extends Model_Database_Dbmanager{

	const TABLE_NAME = 'thread';

	public function __construct() {
		//親のコンストラクタを実行
		parent::__construct(self::TABLE_NAME);
	}

	/**
	 * get_main_list
	 * @return bool  SQLの実行結果
	 * @Author fujimura
	 * SQL メインコメントリスト取得
	 */
	public function get_main_list() {
		//メインコンテンツリスト
		$query = 'SELECT id,title,body,updated_at ' . PHP_EOL
				. 'FROM ' . self::TABLE_NAME . PHP_EOL
				. ' WHERE' . PHP_EOL
				. ' reply_id IS NULL ' . PHP_EOL
				. ' ORDER BY id DESC ' . PHP_EOL;

		//クエリをオブジェクトに変換
		$stmt = $this->db_con->query($query);

		//SQLを実行して結果を返却 true/false
		$main_list = $stmt->fetchALL(PDO::FETCH_ASSOC);
		return $main_list ? $main_list : array();

	}

	/**
	 * get_reply_list
	 * @param  string $parent_id　返信コメントの親ID（ルートコメントのID）
	 * @return bool  SQLの実行結果
	 * @Author fujimura
	 * SQL 返信コメントリスト取得
	 */
	public function get_reply_list($parent_id) {

		//返信コメントリスト
		$query = 'SELECT id,parent_id,reply_id,reply_title,reply_body,updated_at ' . PHP_EOL
				. 'FROM ' . self::TABLE_NAME . PHP_EOL
				. ' WHERE' . PHP_EOL
				. ' parent_id=:parent_id AND reply_id IS NOT NULL ' . PHP_EOL;

		//クエリをオブジェクトに変換
		$stmt = $this->db_con->prepare($query);
		$stmt->bindValue(':parent_id', $parent_id);
		$stmt->execute();

		//SQLを実行して結果を返却 配列/false
		$reply_list = $stmt->fetchALL(PDO::FETCH_ASSOC);
		return $reply_list ? $reply_list : array();
	}

}

