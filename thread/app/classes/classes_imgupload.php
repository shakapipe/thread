<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classes_imgupload
 *
 * @author fujimura
 */
class Classes_ImgUpload {

	private $files  = array();
	private $errors = array();

	private function __construct() {
		$this->files = $_FILES;
	}

	public function run() {
		return $this->excute();
	}

	private function excute(){
		$this->errors = $this->validation();
		return $this->errors;
	}

	private function validation(){
		
		return $this->etErrors();
	}
	
	private function getErrors(){
		
	}
}
