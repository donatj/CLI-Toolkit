<?php

namespace Tests;

use CLI\Erase;
use PHPUnit\Framework\TestCase;

class EraseTest extends TestCase {

	/** @var resource */
	private $stream;
	/** @var resource */
	private $originalEraseStream;

	protected function setUp(): void {
		$this->stream = fopen('php://temp', 'w+');
		$this->originalEraseStream = Erase::$stream;
		Erase::$stream = $this->stream;
	}

	protected function tearDown(): void {
		Erase::$stream = $this->originalEraseStream;
		fclose($this->stream);
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

}
