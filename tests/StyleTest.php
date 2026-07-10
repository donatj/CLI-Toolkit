<?php

namespace Tests;

use CLI\Style;
use PHPUnit\Framework\TestCase;

class StyleTest extends TestCase {

	/**
	 * @dataProvider styleSequencesProvider
	 *
	 * @param string $method
	 * @param array $args
	 * @param string $expected
	 */
	public function testStyleSequences($method, array $args, $expected): void {
		$actual = call_user_func_array(array( Style::class, $method ), $args);
		$this->assertSame($expected, $actual);
	}

	public function styleSequencesProvider(): array {
		return array(
			array( 'red', array( 'danger' ), "\033[0;31mdanger\033[0m" ),
			array( 'bold', array( 'important' ), "\033[1mimportant\033[0m" ),
			array( 'green', array( 'ok', 'black', 'underline' ), "\033[0;32m\033[40m\033[4mok\033[0m" ),
			array( 'blue', array( 'note', 'missing_option' ), "\033[0;34mnote\033[0m" ),
		);
	}

}
