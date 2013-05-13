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
	 * @param  string  $str String to output
	 * @param  false|int $row The optional row to output to
	 * @param  false|int $col The optional column to output to
	 */
	static function string($str, $row = false, $col = false){
		if($col !== false || $row !== false) {
			$row = max(1, $row);
			$col = max(1, $col);
		 	Cursor::rowcol( $row, $col );
		}
		fwrite(self::$stream, $str);
	}

	/**
	 * Output a line, erasing the line first
	 * @param  string  $str String to output
	 * @param  false|int $col The column to draw the current line
	 * @param  boolean $erase Clear the line before drawing the passed string
	 */
	static function line($str, $col = false, $erase = true){
		if($col !== false) {
		 	Cursor::rowcol( $col, 1 );
		}
		if( $erase ) { Erase::line(); }
		fwrite(self::$stream, $str);
	}

}