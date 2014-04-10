<?php

/**
 * @todo rework this and document it
 */

namespace CLI;

/**
 * Class Style
 *
 * @method bold($text, $args = null)
 * @method dim($text, $args = null)
 *
 * @method normal($text, $args = null)
 * @method black($text, $args = null)
 * @method red($text, $args = null)
 * @method green($text, $args = null)
 * @method yellow($text, $args = null)
 * @method blue($text, $args = null)
 * @method magenta($text, $args = null)
 * @method cyan($text, $args = null)
 * @method lightGray($text, $args = null)
 * @method darkGray($text, $args = null)
 * @method lightRed($text, $args = null)
 * @method lightGreen($text, $args = null)
 * @method lightYellow($text, $args = null)
 * @method lightBlue($text, $args = null)
 * @method lightMagenta($text, $args = null)
 * @method lightCyan($text, $args = null)
 * @method white($text, $args = null)
 *
 * @package CLI
 */
class Style {

	const BOLD = 'bold';
	const DIM  = 'dim';

	const UNDERLINE = 'underline';
	const BLINK     = 'blink';
	const REVERSE   = 'reverse';
	const HIDDEN    = 'hidden';

	const NORMAL        = 'normal';
	const BLACK         = 'black';
	const RED           = 'red';
	const GREEN         = 'green';
	const YELLOW        = 'yellow';
	const BLUE          = 'blue';
	const MAGENTA       = 'magenta';
	const CYAN          = 'cyan';
	const LIGHT_GRAY    = 'lightGray';
	const DARK_GRAY     = 'darkGray';
	const LIGHT_RED     = 'lightRed';
	const LIGHT_GREEN   = 'lightGreen';
	const LIGHT_YELLOW  = 'lightYellow';
	const LIGHT_BLUE    = 'lightBlue';
	const LIGHT_MAGENTA = 'lightMagenta';
	const LIGHT_CYAN    = 'lightCyan';
	const WHITE         = 'white';

	protected static $foreground_colors = array(
		self::BOLD          => 1,
		self::DIM           => 2,
		self::NORMAL        => 39,
		self::BLACK         => 30,
		self::RED           => 31,
		self::GREEN         => 32,
		self::YELLOW        => 33,
		self::BLUE          => 34,
		self::MAGENTA       => 35,
		self::CYAN          => 36,
		self::LIGHT_GRAY    => 37,
		self::DARK_GRAY     => 90,
		self::LIGHT_RED     => 91,
		self::LIGHT_GREEN   => 92,
		self::LIGHT_YELLOW  => 93,
		self::LIGHT_BLUE    => 94,
		self::LIGHT_MAGENTA => 95,
		self::LIGHT_CYAN    => 96,
		self::WHITE         => 97,
	);

	protected static $background_colors = array(
		self::NORMAL        => 49,
		self::BLACK         => 40,
		self::RED           => 41,
		self::GREEN         => 42,
		self::YELLOW        => 43,
		self::BLUE          => 44,
		self::MAGENTA       => 45,
		self::CYAN          => 46,
		self::LIGHT_GRAY    => 47,
		self::DARK_GRAY     => 100,
		self::LIGHT_RED     => 101,
		self::LIGHT_GREEN   => 102,
		self::LIGHT_YELLOW  => 103,
		self::LIGHT_BLUE    => 104,
		self::LIGHT_MAGENTA => 105,
		self::LIGHT_CYAN    => 106,
		self::WHITE         => 107,
	);

	protected static $options = array(
		self::UNDERLINE => 4,
		self::BLINK     => 5,
		self::REVERSE   => 7,
		self::HIDDEN    => 8,
	);

	public static function __callStatic( $foreground_color, $args ) {

		$string         = $args[0];
		$colored_string = "";

		// Check if given foreground color found
		if( isset(self::$foreground_colors[$foreground_color]) ) {
			$colored_string .= "\033[" . self::$foreground_colors[$foreground_color] . "m";
		} else {
			die($foreground_color . ' not a valid color');
		}

		array_shift($args);
		foreach( $args as $option ) {
			// Check if given background color found
			if( isset(self::$background_colors[$option]) ) {
				$colored_string .= "\033[" . self::$background_colors[$option] . "m";
			} elseif( isset(self::$options[$option]) ) {
				$colored_string .= "\033[" . self::$options[$option] . "m";
			}
		}

		// Add string and end coloring
		$colored_string .= $string . "\033[0m";

		return $colored_string;

	}

	public function __call( $foreground_color, $args ) {
		return self::__callStatic($foreground_color, $args);
	}

}