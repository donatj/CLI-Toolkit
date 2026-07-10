<?php

namespace Tests;

use CLI\Cursor;
use CLI\Erase;
use CLI\Output;
use PHPUnit\Framework\TestCase;
use Tests\Support\AnsiTerminalBuffer;

class TerminalRenderTest extends TestCase {

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

	public function testPositionedOutputRendersIntoExpectedCell(): void {
		Output::string('hello', 2, 3);

		$buffer = $this->getRenderedBuffer();
		$this->assertSame('  hello', substr($buffer->getLine(2), 0, 7));
		$this->assertSame(2, $buffer->getCursorRow());
		$this->assertSame(8, $buffer->getCursorCol());
	}

	public function testLineEraseClearsPreviousCharactersBeforeWriting(): void {
		Output::string('XXXXXXXX', 3, 1);
		Output::line('hi', 3, true);

		$buffer = $this->getRenderedBuffer();
		$this->assertSame('hi      ', substr($buffer->getLine(3), 0, 8));
	}

	public function testEraseDownClearsRowsFromCursorToScreenEnd(): void {
		Output::string('AAAA', 1, 1);
		Output::string('BBBB', 2, 1);
		Output::string('CCCC', 3, 1);
		Cursor::rowcol(2, 3);
		Erase::down();

		$buffer = $this->getRenderedBuffer();
		$this->assertSame('AAAA', substr($buffer->getLine(1), 0, 4));
		$this->assertSame('BB  ', substr($buffer->getLine(2), 0, 4));
		$this->assertSame('    ', substr($buffer->getLine(3), 0, 4));
	}

	private function getRenderedBuffer(): AnsiTerminalBuffer {
		rewind($this->stream);
		$buffer = new AnsiTerminalBuffer(10, 40);
		$buffer->apply((string)stream_get_contents($this->stream));

		return $buffer;
	}

}
