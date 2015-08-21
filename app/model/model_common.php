<?php
class Model_Common {

	/**
	 * @param  object $fieldset
	 * @return object $errors エラーフォーム名とエラーバリデーション関数名を返却 or array()
	 * @Author fujimura
	 * バリデーションの実行
	 */
	public static function validation_run($fieldset = array()){
		//バリデーションクラス
		$validation = new Classes_Validation($fieldset);
		//バリデーションの実行
		$errors = $validation->run();

		return $errors;
	}

	/**
	 * @param  object $errors
	 * @return array $message エラーフォーム名とエラーバリデーション関数名を返却 or array()
	 * @Author fujimura
	 * バリデーションメッセージを取得
	 */
	public static function get_error_message($errors){
		$message = array();

		//エラーメッセージ取得
		foreach($errors as $form_name => $rule_name){
			//バリデーションエラーメッセージ配列を取得($error_message)
			require APPPATH . 'config/validation.php';

			//メッセージ配列を作成し、そこからrule名(メソッド名）のメッセージ取得
			$message[$form_name]['message']    = empty($error_message[$rule_name])
												//デフォルトメッセージ
												? '※入力内容に誤りがあります'
												: $error_message[$rule_name];
			//errorクラス(u-error_****固定)
			$message[$form_name]['form_class'] = 'u-error_form';
			$message[$form_name]['mes_class']  = 'u-error_mes';
		}

		return $message;
	}
}