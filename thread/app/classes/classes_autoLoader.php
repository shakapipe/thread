<?php
class Classes_AutoLoader {
	//
	//オートローダーが探しにいくディレクトリ
	//
	protected $dirs;

	//
	//__autoload()の実装として、下記のautoLoad()を登録する
	//
	public function register()
	{
		spl_autoload_register(array($this, 'autoLoad'));
	}

	//
	//探索ディレクトリを登録するメソッド
	//
	public function registerDir($dir)
	{
		$this->dirs[] = $dir;
	}

	//
	//autoloadはインスタンス生成時に呼ばれるがそのとき対象となるクラス名を引数として引き受ける
	//
	public function autoLoad($className)
	{
		//検索対象ディレクトリループ
		foreach ($this->dirs as $dir) {
			$file = $dir . '/' . mb_strtolower($className) . '.php';
			//存在するファイルの場合
			if (is_readable($file)) {
				//ファイル読み込み
				require $file;

				return;
			}
		}
   }
}
