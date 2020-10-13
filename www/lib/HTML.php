<?php
session_start(array(
	"cookie_lifetime" => 7*24*60*60,
));

if (!class_exists('Globals'))
require_once "standard.php";

class HTML {
	private static $init_ok = FALSE;
	private static $base_url = NULL;
	private static $base_dir = NULL;

	public static function init($file) {
		if (self::$init_ok)
		return;

		self::$init_ok = TRUE;

		$protocol = isset($_SERVER['HTTPS']) ? "https" : "http";

		switch ($_SERVER["HTTP_HOST"]) {
		case 'localhost':		// developement
			self::$base_url = $protocol . "://localhost/awklang.org/";
			break;
		case 'www.opasopa.net':		// testing
			self::$base_url = $protocol . "://" . $_SERVER["HTTP_HOST"] . "/awklang.org/";
			break;
		default:			// production
			self::$base_url = $protocol . "://" . $_SERVER["HTTP_HOST"] . "/";
			break;
		}

		self::$base_dir = dirname($file);
	}

	public static function url($filename) {
		if (substr($filename, 0, 1) === "/")
		$filename = substr($filename, 1);

		return self::$base_url . $filename;
	}

	public static function page_begin($description = NULL) {
		register_shutdown_function('HTML::page_end');

		$title = Globals::param_get("TITLE");
		$skin = self::skin_get();

		if ($description === NULL)
		$description = Globals::param_get("DESCRIPTION");
		?>
		<!DOCTYPE html>
		<html>
		<head>
		<meta charset="UTF-8">
		<meta name="description" content="<?php print Globals::param_get("DESCRIPTION"); ?>">
		<meta name="keywords" content="awk,gawk">
		<meta name="author" content="<?php print Globals::param_get("AUTHOR"); ?>">
		<link rel="icon" type="image/png" href="<?php print HTML::url("image/awkicon/atma64.png"); ?>">
		<title><?php print $title; ?> &ndash; <?php print $description; ?></title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<link rel="stylesheet" href="<?php print HTML::url("lib/standard.css"); ?>" media="all">
		<link rel="stylesheet" href="<?php print HTML::url("lib/print.css"); ?>" media="print">
		<link id="skin" rel="stylesheet" href="<?php print HTML::url("lib/skin/" . $skin . ".css"); ?>">
		<script>
		Globals = {};
		Globals.baseUrl = '<?php print self::$base_url; ?>';
		Globals.skin = '<?php print $skin; ?>';
		Globals.paramList = {};
		Globals.phpRequest = {};
		<?php
		if (self::param_passed("nvgexp")) {
			?>Globals.navigatorExpand = true;<?php
		}
		foreach (Globals::$param_list as $key => $val) {
			?>Globals.paramList['<?php print $key; ?>'] = '<?php print $val; ?>';<?php
		}
		foreach ($_REQUEST as $key => $val) {
			?>Globals.phpRequest['<?php print $key; ?>'] = '<?php print $val; ?>';<?php
		}
		?>
		</script>
		<script src="<?php print HTML::url("lib/standard.js"); ?>"></script>
		<?php
		HTML::stylesheet("main.css");
		HTML::javascript("main.js");
	}

	private static function skin_get() {
		$skin = NULL;

		if (self::param_passed("debug"))
		$skin = "debug";

		if (self::param_passed("skin"))
		$skin = self::param_get("skin");

		if (!isset($skin))
		$skin = self::session_get("skin");

		if (!isset($skin))
		$skin = "default";

		$_SESSION["skin"] = $skin;
		return $skin;
	}

	public static function body_begin() {
		?>
		</head>
		<body>
		<?php
	}

	public static function page_end() {
		?>
		</body>
		</html>
		<?php
	}

	public static function stylesheet($filename) {
		if (substr($filename, -4) !== ".css")
		$filename .= ".css";

		if (!is_readable($filename))
		return;

		?>
		<link rel="stylesheet" href="<?php print $filename; ?>">
		<?php
	}

	public static function javascript($filename) {
		if (substr($filename, -3) !== ".js")
		$filename .= ".js";

		if (!is_readable($filename))
		return;

		?>
		<script src="<?php print $filename; ?>"></script>
		<?php
	}

	public static function page_setup() {
		HTML::header_setup();
		HTML::arena_setup();
		HTML::footer_setup();
	}

	private static function header_setup() {
		?>
		<div id="header">
		<table id="headerTable">

		<td><img id="headerIcon" src="<?php print HTML::url("image/awkicon/atmaWhite.png"); ?>"></td>

		<td id="headerTitle"><?php print Globals::param_get("DESCRIPTION"); ?></td>

		<td id="headerTabs"><?php self::header_tabs(); ?></td>

		</table>
		</div>
		<?php
	}

	private static function header_tabs() {
		if (self::param_passed("child"))
		self::htab("", "Home", "base");

		else
		self::htab("/news?child&t=" . date("U"), "News");
		?>
		<div class="tab htab"><a href="#" onclick="Globals.skinToggle();">Skin</a></div>
		<?php

		if (self::param_passed("child")) {
			?>
			<div class="tab htab"><a href="#" onclick="window.close();">Close</a></div>
			<?php
		}
		else {
			self::htab("/help?child", "Help");
			self::htab("/faq?child", "FAQ");
			?>
			<div class="tab htab"><a href="mailto:<?php
				print Globals::param_get("CONTACT") . "@" .
				Globals::param_get("TITLE"); ?>" target="contact">Contact</a></div>
			<?php
			self::htab("/about?child", "About");
		}
	}

	private static function htab($href, $title, $target = NULL) {
		if ($target === NULL)
		$target = $title;

		?>
		<div class="tab htab"><a target="<?php print $target; ?>" href="<?php
			print self::url($href); ?>"><?php print $title; ?></a></div>
		<?php
	}

	private static function ntab($href, $title, $target = NULL) {
		if ($target === NULL)
		$target = $title;

		?><div class="ntabContainer"><div class="tab ntab"><a target="<?php print $target; ?>" href="<?php
			print self::url($href); ?>"><?php print $title; ?></a></div></div><?php
	}

	private static function arena_setup() {
		?>
		<div id="arena">
		<table id="arenaTable">

		<td id="navigatorColumn"><div id="navigator"></div></td>
		<td id="sliderColumn"></td>
		<td id="mainColumn"><div id="main"><div id="mainContent"><?php self::main_setup(); ?></div></div></td>

		</table>
		</div>
		<?php
	}

	private static function main_setup() {
		$main = self::main_src();

		if ($main !== NULL)
		require $main;
	}

	private static function main_src() {
		$src = "main.php";

		if (is_readable($src))
		return $src;

		$src = "main.html";

		if (is_readable($src))
		return $src;

		return NULL;
	}

	private static function footer_setup() {
		$update1 = filemtime(self::$base_dir . "/main.php");
		$update2 = filemtime(self::$base_dir . "/main.js");

		if ($update1 === FALSE)
		$update = $update2;

		elseif ($update2 === FALSE)
		$update = $update1;

		elseif ($update2 > $update1)
		$update = $update2;

		else
		$update = $update1;

		?>
		<div id="footer">
		<table id="footerTable">
			<td id="footerLeft">
				<div id="licenseIconContainer"><img id="licenseIcon" src="<?php
					print HTML::url("image/misc/GPL3.png"); ?>"></div>
			</td>
			<td id="footerCenter"<?php
				if ($update !== FALSE) {
					$update = date("M d, Y", $update);
					?> title="Last modified at <?php
					print $update; ?>">&#9672; <?php
					print $update; ?> &#9672;<?php
				}
				else {
					?>&lt;<?php
				}
				?>
			</td>
			<td id="footerRight">
				<span id="copyright">&copy; Copyright <?php
					print Globals::param_get("COPYRIGHT"); ?>. All Rights Reserved.</span>
			</td>
		</table>
		</div>
		<?php
	}

	public static function param_passed($key) {
		if (!isset($_REQUEST))
		return FALSE;

		if (!is_array($_REQUEST))
		return FALSE;

		return array_key_exists($key, $_REQUEST);
	}

	public static function param_get($key, $noval = NULL) {
		return self::param_passed($key) ? $_REQUEST[$key] : $noval;
	}

	public static function session_get($key) {
		if (!isset($_SESSION))
		return FALSE;

		if (!is_array($_SESSION))
		return FALSE;

		if (array_key_exists($key, $_SESSION))
		return $_SESSION[$key];

		return NULL;
	}

	public static function code_quote($file, $download = TRUE) {
		?>
		<p>
		<pre><?php require $file; ?></pre>
		</p>

		<p>
		<a href="<?php print $file; ?>" download="<?php print $file; ?>">Download</a>
		</p>
		<?php
	}
}

?>
