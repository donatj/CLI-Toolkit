<?php

namespace Tests;

use CLI\Cursor;
use PHPUnit\Framework\TestCase;

class CursorTest extends TestCase {

	/** @var resource */
	private $stream;
	/** @var resource */
	private $originalCursorStream;

	protected function setUp(): void {
		$this->stream = fopen('php://temp', 'w+');
		$this->originalCursorStream = Cursor::$stream;
		Cursor::$stream = $this->stream;
	}

	protected function tearDown(): void {
		Cursor::$stream = $this->originalCursorStream;
		fclose($this->stream);
	}

	/**
	 * @dataProvider cursorSequencesProvider
	 *
	 * @param string $method
	 * @param array $args
	 * @param string $expected
	 */
	public function testCursorSequences($method, array $args, $expected): void {
		call_user_func_array(array( Cursor::class, $method ), $args);
		rewind($this->stream);
		$this->assertSame($expected, stream_get_contents($this->stream));
	}

	public function cursorSequencesProvider(): array {
		return array(
			array( 'up', array( 3 ), "\033[3A" ),
			array( 'down', array( 2 ), "\033[2B" ),
			array( 'forward', array( 4 ), "\033[4C" ),
			array( 'back', array( 5 ), "\033[5D" ),
			array( 'rowcol', array( 6, 7 ), "\033[6;7f" ),
			array( 'savepos', array(), "\033[s" ),
			array( 'save', array(), "\0337" ),
			array( 'unsave', array(), "\033[u" ),
			array( 'restore', array(), "\0338" ),
			array( 'hide', array(), "\033[?25l" ),
			array( 'show', array(), "\033[?25h\033[?0c" ),
			array( 'wrap', array( true ), "\033[?7h" ),
			array( 'wrap', array( false ), "\033[?7l" ),
		);
	}

}
