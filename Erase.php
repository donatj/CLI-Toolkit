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
	 *
	 * @param int|bool $row from a specific row
	 */
	public static function line($row = false) {
		if ($row) {
			Cursor::savepos();
			Cursor::rowcol($row);
		}
		fwrite(self::$stream, "\033[2K");
		if ($row) {
			Cursor::restore();
		}
	}

	/**
	 * Erases everything below the cursor
	 *
	 * @param int|bool $row from a specific row
	 */
	public static function down($row = false) {
		if ($row) {
			Cursor::savepos();
			Cursor::rowcol($row);
		}
		fwrite(self::$stream, "\033[J");
		if ($row) {
			Cursor::restore();
		}
	}

	/**
	 * Erases everything above the cursor
	 *
	 * @param int|bool $row from a specific row
	 */
	public static function up($row = false) {
		if ($row) {
			Cursor::savepos();
			Cursor::rowcol($row, Misc::cols());
		}
		fwrite(self::$stream, "\033[1J");
		if ($row) {
			Cursor::restore();
		}
	}

	/**
	 * Erases the entire screen
	 */
	public static function screen() {
		fwrite(self::$stream, "\033[2J");
	}

}