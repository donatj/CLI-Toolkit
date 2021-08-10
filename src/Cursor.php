<?php

namespace CLI;

class Cursor {

	/**
	 * Pointer to the stream where the data is sent
	 *
	 * @var resource
	 */
	public static $stream = STDERR;

	/**
	 * Move the cursor up by count
	 *
	 * @param int $count
	 */
	public static function up( $count = 1 ) {
		fwrite(self::$stream, "\033[{$count}A");
	}

	/**
	 * Move the cursor down by count
	 *
	 * @param int $count
	 */
	public static function down( $count = 1 ) {
		fwrite(self::$stream, "\033[{$count}B");
	}

	/**
	 * Move the cursor right by count
	 *
	 * @param int $count
	 */
	public static function forward( $count = 1 ) {
		fwrite(self::$stream, "\033[{$count}C");
	}

	/**
	 * Move the cursor left by count
	 *
	 * @param int $count
	 */
	public static function back( $count = 1 ) {
		fwrite(self::$stream, "\033[{$count}D");
	}

	/**
	 * Move the cursor to a specific row and column
	 *
	 * @param int $row
	 * @param int $col
	 */
	public static function rowcol( $row = 1, $col = 1 ) {
		$row = intval($row);
		$col = intval($col);
		if( $row < 0 ) {
			$row = Misc::rows() + $row + 1;
		}
		if( $col < 0 ) {
			$col = Misc::cols() + $col + 1;
		}
		fwrite(self::$stream, "\033[{$row};{$col}f");
	}

	/**
	 * Save the current cursor position
	 */
	public static function savepos() {
		fwrite(self::$stream, "\033[s");
	}

	/**
	 * Save the current cursor position and attributes
	 */
	public static function save() {
		fwrite(self::$stream, "\0337");
	}

	/**
	 * Delete the currently saved cursor data
	 */
	public static function unsave() {
		fwrite(self::$stream, "\033[u");
	}

	/**
	 * Restore the previously saved cursor data
	 */
	public static function restore() {
		fwrite(self::$stream, "\0338");
	}

	/**
	 * Hides the cursor
	 */
	public static function hide() {
		fwrite(self::$stream, "\033[?25l");
	}

	/**
	 * Shows the cursor
	 */
	public static function show() {
		fwrite(self::$stream, "\033[?25h\033[?0c");
	}

	/**
	 * Enable/Disable Auto-Wrap
	 *
	 * @param bool $wrap
	 */
	public static function wrap( $wrap = true ) {
		if( $wrap ) {
			fwrite(self::$stream, "\033[?7h");
		} else {
			fwrite(self::$stream, "\033[?7l");
		}
	}

}