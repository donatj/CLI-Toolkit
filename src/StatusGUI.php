<?php

namespace CLI;

class StatusGUI {

	/**
	 * Pointer to the stream where the data is sent
	 *
	 * @var resource
	 */
	static $stream = STDERR;
	static $altstream = STDOUT;

	/**
	 * Render a statusbar stack
	 *
	 * @staticvar bool $lines A stack of previously added lines
	 * @param string   $str The status to add
	 * @param null|int $height The height of the status menu to render
	 * @param bool     $last_line_to_opposing_stream Send the last line of the status to the oposite stream (STDERR/STDOUT)
	 */
	public static function statusbar( $str, $height = null, $last_line_to_opposing_stream = true ) {

		if( $height < 0 ) {
			$height = Misc::rows() + $height + 1;
		}

		if( !$height ) {
			if( defined('STATUSBAR_HEIGHT') ) {
				$height = STATUSBAR_HEIGHT; //@todo: backwards compatibility with some of my older code, remove
			} else {
				$height = max(5, Misc::rows() - 5);
			}
		}

		static $lines = false;
		$i = 0;
		if( !$lines ) {
			$lines = array_fill(0, $height - 1, array( 'str' => '' ));
		}
		foreach( explode(PHP_EOL, $str) as $line ) {
			$lines[] = array( 'str' => strtr(trim($line), "\r\n", "  ") );
			array_shift($lines);
		}

		fwrite(self::$stream, "\0337\033[1;1f\033[2K");
		foreach( $lines as $line ) {
			$i++;
			$text = substr(str_pad($line['str'], Misc::cols()), 0, Misc::cols() - 1);
			Cursor::rowcol($i, 1);
			if( $i == $height - 1 && $last_line_to_opposing_stream ) {
				fwrite(self::$altstream, $text);
			} else {
				fwrite(self::$stream, $text);
			}
		}

		fwrite(self::$stream, "\033[" . $height . ";1f");
		fwrite(self::$stream, str_repeat('─', Misc::cols()));
		fwrite(self::$stream, "\0338");
	}

	/**
	 * Draw a Progress Bar
	 *
	 * @staticvar array $timer
	 * @param string   $title
	 * @param int      $numerator
	 * @param int      $denominator
	 * @param int      $line Which row of the terminal to render
	 * @param int|null $time_id
	 * @param string   $color
	 */
	public static function progressbar( $title, $numerator, $denominator, $line, $time_id = null, $color = 'cyan' ) {
		static $timer = array();
		if( $time_id ) {
			if( !isset($timer[$time_id]) || $timer[$time_id]['last_numerator'] > $numerator ) {
				$timer[$time_id] = array( 'start' => microtime(true) );
			}
			$timer[$time_id]['last_numerator'] = $numerator;
		}

		$text      = $title . ' - ' . self::num_display($numerator) . '/' . self::num_display($denominator);
		$time_text = '';
		if( $time_id ) {
			$diff      = microtime(true) - $timer[$time_id]['start'];
			$time_left = self::formtime(($numerator ? $diff / $numerator : 0) * ($denominator - $numerator));
			$elapsed   = self::formtime($diff);
			if( $diff > 2 ) {
				$time_text = $time_left . " ($elapsed)";
			}
		}
		$width   = max(min(Misc::cols() - strlen($text) - (strlen($time_text) ? : -1) - 6, $denominator), 4);
		$main_xs = floor(($numerator / $denominator) * $width);

		$str = $text . ' [' . Style::$color(str_repeat('#', $main_xs)) . str_repeat('.', $width - $main_xs) . "] " . $time_text;

		Output::line($str, $line);
	}

	/**
	 * Draw a Histogram
	 *
	 * @param  string $title
	 * @param  int    $numerator
	 * @param  int    $denominator
	 * @param  int    $line
	 * @param  int    $hist_id
	 * @param  string $color
	 * @param  string $full_color
	 */
	public static function histogram( $title, $numerator, $denominator, $line, $hist_id, $color = 'normal', $full_color = 'red' ) {
		$levels = array( ' ', '▁', '▂', '▃', '▄', '▅', '▆', '▇', '█' );
		static $hist = false;

		if( !$hist || !$hist[$hist_id] ) {
			$hist[$hist_id] = array_fill(0, Misc::cols(), $levels[0]);
		}

		$text = $title . ' - ' . self::num_display($numerator) . '/' . self::num_display($denominator) . ' ';

		#$lev = round(($numerator / $denominator) * 8);


		$lev              = intval(($numerator / $denominator) * 9);
		$lev              = min($lev, 8);
		$hist[$hist_id][] = $lev == 8 ? Style::$full_color($levels[$lev]) : Style::$color($levels[$lev]);
		array_shift($hist[$hist_id]);

		$sub_array = array_slice($hist[$hist_id], 0 - (Misc::cols() - strlen($text)) + 4);

		$str = $text . '[' . implode($sub_array, '') . ']';
		Output::line($str, $line);
	}

	private static function formtime( $seconds ) {
		return '0' . preg_replace('/^[0:]+(?=:)/', '', ((int)($seconds / 3600)) . ':' . str_pad((int)(($seconds % 3600) / 60), 2, "0", STR_PAD_LEFT) . ':' . str_pad((int)(($seconds % 60)), 2, "0", STR_PAD_LEFT));
	}

	/**
	 * @param integer $number
	 */
	private static function num_display( $number ) {
		return rtrim(rtrim(number_format($number, 2), '0'), '.'); // in two rtrim steps to avoid over trimming 10.0
	}

}