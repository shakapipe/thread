<?php

class Model_Database_Dbmanager{

	public $db_con;
	public $table_name;

	public function __construct($table_name){
		$this->db_init();
		$this->table_name = $table_name;
	}

	/**
	 * @Author fujimura
	 * DB接続
	 */
	protected function db_init(){
		//DB接続情報
		require APPPATH . 'config/db.php';
		//DB接続
		$this->db_con = new PDO($db_con['dsn'], $db_con['user'], $db_con['password']);
		//PDOエラーで例外を投げるように設定
		$this->db_con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}


	/**
	 * insert
	 * @param  array $save_data　セーブデータ配列　array(カラム名,保存データ)
	 * @return bool  SQLの実行結果　true/false
	 * @Author fujimura
	 * SQLインサート
	 */
	public function insert($save_data = array()){

		if(empty($save_data)) return false;

		//カラム名と、バインド用の文字列を生成
		foreach($save_data as $column_name => $column_val){
			$fields[]     = $column_name;
			$bind_value[] = ':' . $column_name;
		}

		//インサートクエリ作成
		$query = 'INSERT INTO ' . PHP_EOL
				. $this->table_name . PHP_EOL
				. ' (' . implode(',', $fields) . ')' . PHP_EOL
				. ' VALUES (' . implode(',', $bind_value) . ')' . PHP_EOL;

		//クエリをオブジェクトに変換
		$stmt = $this->db_con->prepare($query);

		//値をパラメータにバインドする
		foreach ($save_data as $column_name => $column_val) {
			$stmt->bindValue(':' . $column_name, $column_val);
		}

		//SQLを実行して結果を返却 true/false
		return $stmt->execute();
	}

	/**
	 * update
	 * @param  array $update_data　アップデートデータ配列　array(カラム名,保存データ)
	 * @return bool  SQLの実行結果　true/false
	 * @Author fujimura
	 * SQLアップデート
	 */
	public  function update($update_data, $where_id = ''){
	//UPDATE db_name.tbl_name SET col_name1=expr1 [, col_name2=expr2 ...] [WHERE where_condition];

		if(empty($update_data) || empty($where_id)) return false;

		//カラム名と、バインド用の文字列を生成
		foreach($update_data as $column_name => $column_val){
			$fields[]     = $column_name;
			$bind_value[] = ':' . $column_name;
		}

		//インサートクエリ作成
		$query = 'UPDATE ' . PHP_EOL
				. $this->table_name . PHP_EOL
				. ' SET ' . PHP_EOL;

		//保存フィールドの設定
		foreach($fields as $field_key => $field){
			//2個目のフィールドからカンマを付ける
			if(reset($fields) !== $field){
				$query .= ',';
			}
			$query .= $field . '=' . $bind_value[$field_key] . PHP_EOL;

		}
		$query .= ' WHERE id=:id' . PHP_EOL;

		//クエリをオブジェクトに変換
		$stmt = $this->db_con->prepare($query);

		//値をパラメータにバインドする
		foreach ($update_data as $column_name => $column_val) {
			$stmt->bindValue(':' . $column_name, $column_val);
		}
		$stmt->bindValue(':id', $where_id);

		//SQLを実行して結果を返却 true/false
		return $stmt->execute();
	}


	/**
	 * delete
	 * @param  string $id　削除ID（複数は未対応）
	 * @return bool  SQLの実行結果　true/false
	 * @Author fujimura
	 * SQLデリート
	 */
	public  function delete($id = ''){

		if(empty($id)) return false;

		//DELETE FROM db_name.tbl_name [WHERE where_condition];
		$query = 'DELETE FROM ' . $this->table_name . ' WHERE id=:id';

		//クエリをオブジェクトに変換
		$stmt = $this->db_con->prepare($query);

		//値をパラメータにバインドする
		$stmt->bindValue(':id', $id);

		//SQLを実行して結果を返却 true/false
		return $stmt->execute();
	}


	/**
	 * select
	 * @param  string $id　取得ID
	 * @return bool  SQLの実行結果　true/false
	 * @Author fujimura
	 * SQLセレクト　1レコード、1次元連想配列
	 */
	public  function select($id){

		if(empty($id)) return null;

		$query = 'SELECT * FROM ' . $this->table_name . ' WHERE id = :id';

		//クエリをオブジェクトに変換
		$stmt = $this->db_con->prepare($query);

		//値をパラメータにバインドする
		$stmt->bindValue(':id', $id);

		//SQLを実行して結果を返却 true/false
		$stmt->execute();

		//結果を取得 連想/false
		$select = $stmt->fetch(PDO::FETCH_ASSOC);
		return $select ? $select : null;
	}


	/**
	 * select_all
	 * @return bool  SQLの実行結果　true/false
	 * @Author fujimura
	 * SQLセレクト　テーブルの全てのレコード2次元配列
	 */
	public  function select_all(){

		$query = 'SELECT * FROM ' . $this->table_name;

		//クエリをオブジェクトに変換
		$stmt = $this->db_con->query($query);

		//SQLを実行して結果を返却 true/false
		return $stmt->execute();

		//SQLを実行して結果を返却 連想配列/false
		$select_all = $stmt->fetchALL(PDO::FETCH_ASSOC);
		return $select_all ? $select_all : array();
	}

	/**
	 * trancate
	 * @return bool  SQLの実行結果　true/false
	 * @Author fujimura
	 * SQLトランケート（データをリセットするときに使用）
	 */
	public  function trancate(){

		$query = 'TRANCATE TABLE ' . $this->table_name;
		//クエリをオブジェクトに変換
		$stmt = $this->db_con->query($query);

		//SQLを実行して結果を返却 true/false
		return $stmt->execute();
	}

	/**
	 * トランザクションスタート
	 *
	 * @return bool
	 */
	public function start_transaction()
	{
		return $this->db_con->beginTransaction();
	}


	/**
	 * トランザクションコミット
	 *
	 * @return bool
	 */
	public function commit_transaction()
	{
		return $this->db_con->commit();
	}


	/**
	 * トランザクションロールバック
	 * @return bool
	 */
	public function rollback_transaction()
	{
		return $this->db_con->rollBack();
	}


	/**
	 * DBの接続解放
	 */
	public function __destruct() {
		unset($this->db_con);
	}
}

