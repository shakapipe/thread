<?php
/**
 * Description of fieldset
 *
 * @author fujimura
 */
class Classes_Fieldset {
	protected $name = null;
	//フィールド名
	protected $fields = array();

	public function get_field(){
		//フィールド取得
		return $this->fields;
	}
	public function add_field($name = null){
		//フィールド名を設定
		$this->name = $name;
	}

	public function add_rule($rule = null, $val = ''){
		//Fieldにルール名を設定
		$this->fields[$this->name][$rule] = $val;
	}
}
