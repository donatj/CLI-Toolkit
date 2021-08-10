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
	 * @param int|null $row from a specific row
	 */
	public static function line( $row = null ) {
		self::saveWriteRestore(self::$stream, "\033[2K", $row);
	}

	/**
	 * Erases everything below the cursor
	 *
	 * @param int|null $row from a specific row
	 */
	public static function down( $row = null ) {
		self::saveWriteRestore(self::$stream, "\033[J", $row);
	}

	/**
	 * Erases everything above the cursor
	 *
	 * @param int|null $row from a specific row
	 */
	public static function up( $row = null ) {
		self::saveWriteRestore(self::$stream, "\033[1J", $row);
	}

	/**
	 * Erases the entire screen
	 */
	public static function screen() {
		fwrite(self::$stream, "\033[2J");
	}

	/**
	 * @param resource $stream
	 * @param string $str
	 * @param integer|null $row
	 */
	protected static function saveWriteRestore($stream, $str, $row) {
		if( $row ) {
			Cursor::savepos();
			Cursor::rowcol($row, Misc::cols());
		}
		fwrite($stream, $str);
		if( $row ) {
			Cursor::restore();
		}
	}

}