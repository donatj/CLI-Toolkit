<?php

namespace CLI;

class Erase {

	/**
	 * Pointer to the stream where the data is sent
	 *
	 * @var resource
	 */
	static $stream = STDERR;

	/**
	 * Erase to the end of line
	 */
	public static function eol() {
		fwrite(self::$stream, "\033[K");
	}

	/**
	 * Erase to the start of line
	 */
	public static function sol() {
		fwrite(self::$stream, "\033[1K");
	}

	/**
	 * Erase entire line
	 */
	public static function line() {
		fwrite(self::$stream, "\033[2K");
	}

	/**
	 * Erases everything below the cursor
	 */
	public static function down() {
		fwrite(self::$stream, "\033[J");
	}

	/**
	 * Erases everything above the cursor
	 */
	public static function up() {
		fwrite(self::$stream, "\033[1J");
	}

	/**
	 * Erases the entire screen
	 */
	public static function screen() {
		fwrite(self::$stream, "\033[2J");
	}

}