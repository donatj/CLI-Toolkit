<?php

namespace CLI;

class Screen {

	/**
	 * Pointer to the stream where the data is sent
	 *
	 * @var resource
	 */
	public static $stream = STDERR;

	/**
	 * Switch to the alternate screen buffer, optionally clearing it
	 *
	 * @var $erase bool
	 */
	public static function alternate( $erase = true ) {
		fwrite(self::$stream, "\033[?1049h");
		if( $erase ) {
			fwrite(self::$stream, "\033[2J");
		}
	}

	/**
	 * Switch to the main screen buffer
	 */
	public static function main() {
		fwrite(self::$stream, "\033[?1049l");
	}

}
