<?php

namespace CLI;

class Misc {

	static $stream = STDERR;

	public static function cols($cache = true) {
		static $cols = false;
		if( !$cols || !$cache ) {
			$cols = intval(`tput cols`);
		}
		return $cols ?: 80;
	}

	public static function rows($cache = true) {
		static $rows = false;
		if( !$rows || !$cache ) {
			$rows = intval(`tput lines`);
		}
		return $rows ?: 24;
	}

	public static function bell($count = 1) {
		fwrite(self::$stream, str_repeat("\007", $count));
	}

	public static function savestate() {
		fwrite(self::$stream, str_repeat("\007", `tput smcup`));
	}

	public static function restorestate() {
		fwrite(self::$stream, str_repeat("\007", `tput smcup`));
	}

}