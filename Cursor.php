<?php

namespace CLI;

class Cursor {

	static $stream = STDERR;

	static function up($count = 1) {
		fwrite(self::$stream, "\033[{$count}A");
	}

	static function down($count = 1) {
		fwrite(self::$stream, "\033[{$count}B");
	}

	static function forward($count = 1) {
		fwrite(self::$stream, "\033[{$count}C");
	}

	static function back($count = 1) {
		fwrite(self::$stream, "\033[{$count}D");
	}

	static function rowcol($row = 1, $col = 1) {
		fwrite(self::$stream, "\033[{$row};{$col}f");
	}

	static function savepos() {
		fwrite(self::$stream, "\033[s");
	}

	static function save() {
		fwrite(self::$stream, "\0337");
	}

	static function unsave() {
		fwrite(self::$stream, "\033[u");
	}

	static function restore() {
		fwrite(self::$stream, "\0338");
	}

}