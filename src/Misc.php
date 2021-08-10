<?php

namespace CLI;

class Misc {

	/**
	 * Pointer to the stream where the data is sent
	 *
	 * @var resource
	 */
	public static $stream = STDERR;

	/**
	 * The col size of the current terminal as returned by tput
	 *
	 * @staticvar bool|int $cols the cached value for the number of columns
	 * @param bool $cache Whether to cache the response
	 * @return int
	 */
	public static function cols( $cache = true ) {
		static $cols = false;
		if( !$cols || !$cache ) {
			$cols = (int)shell_exec('tput cols');
		}

		return $cols ? : 80;
	}

	/**
	 * The row size of the current terminal as returned by tput
	 *
	 * @staticvar bool|int $rows the cached value for the number of columns
	 * @param bool $cache Whether to cache the response
	 * @return int
	 */
	public static function rows( $cache = true ) {
		static $rows = false;
		if( !$rows || !$cache ) {
			$rows = (int)shell_exec('tput lines');
		}

		return $rows ? : 24;
	}

	/**
	 * Triggers a terminal bell
	 *
	 * @param int $count
	 */
	public static function bell( $count = 1 ) {
		fwrite(self::$stream, str_repeat("\007", $count));
	}

	/**
	 * Save the current state of the terminal via tput
	 */
	public static function savestate() {
		fwrite(self::$stream, shell_exec('tput smcup'));
	}

	/**
	 * Restore the current state of the terminal via tput
	 */
	public static function restorestate() {
		fwrite(self::$stream, shell_exec('tput rmcup'));
	}

}
