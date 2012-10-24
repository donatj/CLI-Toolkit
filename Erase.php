<?php

namespace CLI;

class Erase {

	static $stream = STDERR;

	public static function eol() {
		fwrite(self::$stream, "\033[K");
	}

	public static function sol() {
		fwrite(self::$stream, "\033[1K");
	}

	public static function line() {
		fwrite(self::$stream, "\033[2K");
	}

	public static function down() {
		fwrite(self::$stream, "\033[J");
	}

	public static function up() {
		fwrite(self::$stream, "\033[1J");
	}

	public static function screen() {
		fwrite(self::$stream, "\033[2J");
	}

}