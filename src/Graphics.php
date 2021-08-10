<?php

namespace CLI;

class Graphics {

	public static function line( $x1, $y1, $x2, $y2, $chars = array( '#' ) ) {

		if( $x2 != $x1 ) {

			if( $x2 < $x1 ) {
				list($x1, $x2) = array( $x2, $x1 );
				list($y2, $y1) = array( $y1, $y2 );
			}

			$slope   = ($y2 - $y1) / ($x2 - $x1);
			$radians = atan($slope);
		} else {
			$radians = pi() / 2;
		}

		$radians += 2 * pi();

		$dist = sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2));


		$last_x = false;
		$last_y = false;

		$j            = 0;
		$chars_length = count($chars);
		for( $i = 0; $i <= abs(ceil($dist)); $i += 1 ) {
			$x = round(($i * cos($radians)) + $x1);
			$y = round(($i * sin($radians)) + $y1);

			if( $x != $last_x || $last_y != $y ) {
				Output::string($chars[$j++ % $chars_length], $y, $x);
			}

			$last_x = $x;
			$last_y = $y;
		}
	}

	public static function box( $x1, $y1, $x2, $y2, $frame = array( "-", "|", "-", "|", "x", "x", "x", "x" ) ) {
		self::line($x1, $y1, $x1, $y2, (array)$frame[1]);
		self::line($x2, $y1, $x2, $y2, (array)$frame[3]);
		self::line($x1, $y1, $x2, $y1, (array)$frame[0]);
		self::line($x1, $y2, $x2, $y2, (array)$frame[2]);

		if( count($frame) == 8 ) {
			Output::string($frame[4], $y1, $x1);
			Output::string($frame[5], $y1, $x2);
			Output::string($frame[6], $y2, $x2);
			Output::string($frame[7], $y2, $x1);
		}

	}

}