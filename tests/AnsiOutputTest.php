<?php

namespace Tests;

use CLI\Cursor;
use CLI\Erase;
use CLI\Output;
use CLI\Style;
use PHPUnit\Framework\TestCase;

class AnsiOutputTest extends TestCase {

	/** @var resource */
	private $stream;
	/** @var resource */
	private $originalCursorStream;
	/** @var resource */
	private $originalEraseStream;
	/** @var resource */
	private $originalOutputStream;

	protected function setUp(): void {
		$this->stream               = fopen('php://temp', 'w+');
		$this->originalCursorStream = Cursor::$stream;
		$this->originalEraseStream  = Erase::$stream;
		$this->originalOutputStream = Output::$stream;

		Cursor::$stream = $this->stream;
		Erase::$stream  = $this->stream;
		Output::$stream = $this->stream;
	}

	protected function tearDown(): void {
		Cursor::$stream = $this->originalCursorStream;
		Erase::$stream  = $this->originalEraseStream;
		Output::$stream = $this->originalOutputStream;
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

	/**
	 * @dataProvider eraseSequencesProvider
	 *
	 * @param string $method
	 * @param string $expected
	 */
	public function testEraseSequences($method, $expected): void {
		Erase::$method();
		rewind($this->stream);
		$this->assertSame($expected, stream_get_contents($this->stream));
	}

	public function eraseSequencesProvider(): array {
		return array(
			array( 'eol', "\033[K" ),
			array( 'sol', "\033[1K" ),
			array( 'line', "\033[2K" ),
			array( 'down', "\033[J" ),
			array( 'up', "\033[1J" ),
			array( 'screen', "\033[2J" ),
		);
	}

	/**
	 * @dataProvider outputSequencesProvider
	 *
	 * @param string $method
	 * @param array $args
	 * @param string $expected
	 */
	public function testOutputSequences($method, array $args, $expected): void {
		call_user_func_array(array( Output::class, $method ), $args);
		rewind($this->stream);
		$this->assertSame($expected, stream_get_contents($this->stream));
	}

	public function outputSequencesProvider(): array {
		return array(
			array( 'string', array( 'hello' ), 'hello' ),
			array( 'string', array( 'hello', 3, 2 ), "\033[3;2fhello" ),
			array( 'line', array( 'world', null, false ), 'world' ),
			array( 'line', array( 'world', 5, true ), "\033[5;1f\033[2Kworld" ),
		);
	}

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
