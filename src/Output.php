<?php

namespace CLI;

class Output {

	/**
	 * Pointer to the stream where the data is sent
	 *
	 * @var resource
	 */
	static $stream = STDOUT;

	/**
	 * Output a string
	 *
	 * @param  string    $str String to output
	 * @param  false|int $row The optional row to output to
	 * @param  false|int $col The optional column to output to
	 */
	public static function string( $str, $row = null, $col = null ) {
		if( $col !== null || $row !== null ) {
			Cursor::rowcol($row, $col);
		}
		fwrite(self::$stream, $str);
	}

	/**
	 * Output a line, erasing the line first
	 *
	 * @param  string    $str String to output
	 * @param  null|int $col The column to draw the current line
	 * @param  boolean   $erase Clear the line before drawing the passed string
	 */
	public static function line( $str, $col = null, $erase = true ) {
		if( $col !== null ) {
			Cursor::rowcol($col, 1);
		}
		if( $erase ) {
			Erase::line();
		}
		fwrite(self::$stream, $str);
	}

}