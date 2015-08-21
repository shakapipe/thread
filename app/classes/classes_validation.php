<?php
class Classes_Validation {

	/**
	 * @var  array  available after validation started running: contains given input values
	 */
	protected $input = array();

	/**
	 * @var  array  contains values of fields that validated successfully
	 */
	protected $validated = array();

	/**
	 * @var  array  contains Validation_Error instances of encountered errors
	 */
	protected $errors = array();

	public function __construct($fieldset){
		//fieldsetオブジェクトをセット
		$this->input = $fieldset;
	}

	public function run()
	{
		//設定されているフィールド名とバリデーション関数を取得
		$fields = $this->input->get_field();

		foreach($fields as $fields_name => $field_value)
		{
			//バリデーションルールの実行
			$this->run_rule($fields_name, $field_value);
		}

		return $this->errors;
	}

	private function run_rule($field_name = '' , $field_value = array()){
		//設定された入力フォームを順次バリデーションチェック
		foreach($field_value as $method_name => $val){
			//バリデーションの実行
			if(!call_user_func_array('self::' . $method_name, $val)){
				//エラーメソッド名を格納
				$this->errors[$field_name] = $method_name;
				return false;
			}
		}

		return true;
	}


//----------------------------------------------
//以下が実際のバリデーション
//----------------------------------------------
	/**
	 * 英数字チェック
	 *
	 * @param string $arg チェックする値
	 * @return bool 英数字のみの場合true、そうでなければfalse
	 */
	public static function checkAlphanumeric($val)
	{
		if(preg_match('/^[a-zA-Z0-9]+$/', $val)){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 必須チェック
	 *
	 * @param string $val チェックする値
	 * @return bool true/false
	 */
	public static function required($val, $chk_flg)
	{
		if($chk_flg){
			//0、'0'がemptyと判断されるため、「==」曖昧判定で0除外
			return !(is_null($val) || $val == '');
//			return isset($val);
		}
		return true;
	}

	/**
	 * 最大文字数チェック
	 *
	 * @param string $val チェックする値
	 * @param int $length 最大文字数
	 * @return bool true/false
	 */
	public static function max_length($val, $length)
	{
		return mb_strlen($val) <= $length;
	}
}
