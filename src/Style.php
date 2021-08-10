<?php

/**
 * @todo rework this and document it
 */

namespace CLI;

/**
 * Class Style
 *
 * @method static bold($text, $args = null)
 * @method static black($text, $args = null)
 * @method static blue($text, $args = null)
 * @method static green($text, $args = null)
 * @method static cyan($text, $args = null)
 * @method static red($text, $args = null)
 * @method static purple($text, $args = null)
 * @method static brown($text, $args = null)
 * @method static light_gray($text, $args = null)
 * @method static normal($text, $args = null)
 * @method static dim($text, $args = null)
 * @method static dark_gray($text, $args = null)
 * @method static light_blue($text, $args = null)
 * @method static light_green($text, $args = null)
 * @method static light_cyan($text, $args = null)
 * @method static light_red($text, $args = null)
 * @method static light_purple($text, $args = null)
 * @method static yellow($text, $args = null)
 * @method static white($text, $args = null)
 *
 * @package CLI
 */
class Style {

	public static $foreground_colors = array(
		'bold'       => '1',    'dim'          => '2',
		'black'      => '0;30', 'dark_gray'    => '1;30',
		'blue'       => '0;34', 'light_blue'   => '1;34',
		'green'      => '0;32', 'light_green'  => '1;32',
		'cyan'       => '0;36', 'light_cyan'   => '1;36',
		'red'        => '0;31', 'light_red'    => '1;31',
		'purple'     => '0;35', 'light_purple' => '1;35',
		'brown'      => '0;33', 'yellow'       => '1;33',
		'light_gray' => '0;37', 'white'        => '1;37',
		'normal'     => '0;39',
	);

	public static $background_colors = array(
		'black' => '40', 'red' => '41',
		'green' => '42', 'yellow' => '43',
		'blue'  => '44', 'magenta' => '45',
		'cyan'  => '46', 'light_gray' => '47',
	);

	public static $options = array(
		'underline' => '4', 'blink' => '5',
		'reverse'   => '7', 'hidden' => '8',
	);

	public static function __callStatic( $foreground_color, $args ) {

		$string         = $args[0];
		$colored_string = '';

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

}
