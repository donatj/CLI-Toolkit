<?php

namespace CLI;

class StatusGUI {

	/**
	 * Pointer to the stream where the data is sent
	 *
	 * @var resource
	 */
	static $stream = STDERR;

	/**
	 * Render a statusbar stack
	 *
	 * @staticvar boolean $lines A stack of previously added lines
	 * @param string $str The status to add
	 * @param book|int $height The height of the status menu to render
	 * @param bool $last_line_to_opposing_stream Send the last line of the status to the oposite stream (STDERR/STDOUT)
	 */
	static function statusbar($str, $height = false, $last_line_to_opposing_stream = true) {

		if( !$height ) {
			if( defined('STATUSBAR_HEIGHT') ) {
				$height = STATUSBAR_HEIGHT; //@todo: backwards compatibility with some of my older code, remove
			}else{
				$height = max(5, Misc::rows() - 5 );
			}
		}

		static $lines = false; $i = 0;
		if( !$lines ) { $lines = array_fill(0, $height - 1, array('str' => '')); }
		$lines[] = array( 'str' => trim($str) );
		array_shift($lines);

		fwrite(self::$stream, "\0337\033[1;1f\033[2K");
		foreach($lines as $line) { $i++;
			$text = substr(str_pad($line['str'], Misc::cols() - 10 ), 0, Misc::cols()).PHP_EOL;
			if( $i == $height - 1 && $last_line_to_opposing_stream ) {
				fwrite(self::$stream == STDERR ? STDOUT : STDERR, $text);
			}else{
				fwrite(self::$stream, $text); 
			}
		}
		
		fwrite(self::$stream, "\033[".$height.";1f");
		fwrite(self::$stream, str_repeat('─', Misc::cols()) ); 
		fwrite(self::$stream, "\0338");
	}

	/**
	 * Draw a Progress Bar
	 *
	 * @staticvar array $timer
	 * @param string $title
	 * @param int $numerator
	 * @param int $denominator
	 * @param int $line Which row of the terminal to render
	 * @param int $time_id
	 * @param string $color
	 */
	static function progressbar($title, $numerator, $denominator, $line, $time_id = false, $color = 'cyan') {
		static $timer = array();
		if($time_id) {
			if( !isset($timer[$time_id]) || $timer[$time_id]['last_numerator'] > $numerator  ) {
				$timer[$time_id] = array('start' => microtime(true));
			}
			$timer[$time_id]['last_numerator'] = $numerator;
		}
		fwrite(self::$stream, "\0337\033[" . intval($line) . ";1f\033[2K");
		
		$text      = $title . $main_xs . ' - ' . $numerator . '/' . $denominator;
		$time_text = '';
		if( $time_id ) {
			$diff = microtime(true) - $timer[$time_id]['start'];
			$time_left = self::formtime(($diff / $numerator) * ($denominator - $numerator));
			$elapsed   = self::formtime($diff);
			if( $diff > 2) { $time_text = $time_left . " ($elapsed)"; }
		}
		$width   = max(min( Misc::cols() - strlen($text) - (strlen($time_text) ?: -1) - 6, $denominator), 4);
		$main_xs = floor(($numerator / $denominator) * $width);

		fwrite(self::$stream, $text . ' [' . Style::$color( str_repeat('#', $main_xs) ) . str_repeat('.', $width - $main_xs) ."] " . $time_text);
		fwrite(self::$stream, "\0338");
	}

	private static function formtime($seconds) {
		return '0' . preg_replace('/^[0:]+(?=:)/', '', ((int)($seconds / 3600)) . ':' . str_pad( (int)(($seconds % 3600) / 60), 2, "0", STR_PAD_LEFT). ':' . str_pad( (int)(($seconds % 60)), 2, "0", STR_PAD_LEFT));
	}

}