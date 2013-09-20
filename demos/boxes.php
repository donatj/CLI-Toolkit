<?php

require('__autoload.php');

$func = function ( $i, $gap ) {
	$j    = $i / 10;
	$size = CLI\Misc::cols() - $gap;

	return round( (sin($j) * $size / 2) + ($size / 2) );
};

$cofunc = function ( $i, $gap ) {
	$j    = $i / 10;
	$size = CLI\Misc::rows() - $gap;

	return round( (cos($j) * $size / 2) + ($size / 2) );
};

for( $i = 0; $i <= 100; $i++ ) {

	CLI\Erase::screen();

//	$a = $func( $i, 2 );
//	$b = $func( $i, 4 );
//	$c = $func( $i, 8 );
//	$d = $func( $i, 16 );
//
//	CLI\Graphics::box($a, 1, $a + 3, 3);
//	CLI\Graphics::box($b, 3, $b + 7, 7);
//	CLI\Graphics::box($c, 7, $c + 15, 15);
//	CLI\Graphics::box($d, 15, $d + 31, 31);

	for( $j = 0; $j <= 15; $j+= 3 ) {

		$k = ($j + 1) * 2;

		$d = $func( $i, $k );
		$e = $cofunc( $i, $k );
		CLI\Graphics::box($d, $e, $d + $k, $e + $k);

	}

	usleep(100000);

}