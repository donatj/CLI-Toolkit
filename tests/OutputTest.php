<?php

namespace Tests;

use CLI\Cursor;
use CLI\Erase;
use CLI\Output;
use PHPUnit\Framework\TestCase;

class OutputTest extends TestCase {

	/** @var resource */
	private $stream;
	/** @var resource */
	private $originalCursorStream;
	/** @var resource */
	private $originalEraseStream;
	/** @var resource */
	private $originalOutputStream;

	protected function setUp(): void {
		$this->stream = fopen('php://temp', 'w+');
		$this->originalCursorStream = Cursor::$stream;
		$this->originalEraseStream = Erase::$stream;
		$this->originalOutputStream = Output::$stream;
		Cursor::$stream = $this->stream;
		Erase::$stream = $this->stream;
		Output::$stream = $this->stream;
	}

	protected function tearDown(): void {
		Cursor::$stream = $this->originalCursorStream;
		Erase::$stream = $this->originalEraseStream;
		Output::$stream = $this->originalOutputStream;
		fclose($this->stream);
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

}
