<?php

Globals::init();

class Globals {
	private static $init_ok = FALSE;
	private static $base_dir = NULL;

	public static function init() {
		if (self::$init_ok)
		return self;

		self:$init_ok = TRUE;
		self::$base_dir = preg_replace("/lib\/standard\.php$/", "", __FILE__);
	}

	public static function pathname($filename) {
		if (substr($filename, 0, 1) === "/")
		$filename = substr($filename, 1);

		return self::$base_dir . $filename;
	}

	public static $param_list = array(
		"TITLE" => "awklang.org",
		"DESCRIPTION" => "Τhe site for things related to the awk language",
		"AUTHOR" => "Panos Papadopoulos",
		"COPYRIGHT" => "2017",
		"CONTACT" => "awklang",
	);

	public static function param_get($param, $pcust = array()) {
		return array_key_exists($param, $pcust) ? $pcust[$param] : self::$param_list[$param];
	}
}

?>
