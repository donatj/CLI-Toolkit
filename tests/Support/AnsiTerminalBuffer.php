<?php

namespace Tests\Support;

use RuntimeException;

class AnsiTerminalBuffer {

	/** @var string[] */
	private $lines = array();
	/** @var int */
	private $rows;
	/** @var int */
	private $cols;
	/** @var int */
	private $cursorRow = 1;
	/** @var int */
	private $cursorCol = 1;

	public function __construct($rows = 10, $cols = 40) {
		$this->rows = (int)$rows;
		$this->cols = (int)$cols;
		$this->lines = array_fill(0, $this->rows, str_repeat(' ', $this->cols));
	}

	public function apply($output): void {
		$script = __DIR__ . '/render_terminal.py';
		$command = 'python3 ' . escapeshellarg($script) . ' ' . $this->rows . ' ' . $this->cols;

		$descriptors = array(
			0 => array( 'pipe', 'r' ),
			1 => array( 'pipe', 'w' ),
			2 => array( 'pipe', 'w' ),
		);

		$process = proc_open($command, $descriptors, $pipes);
		if( !is_resource($process) ) {
			throw new RuntimeException('Failed to start python3 for terminal rendering');
		}

		fwrite($pipes[0], (string)$output);
		fclose($pipes[0]);

		$stdout = stream_get_contents($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);
		fclose($pipes[1]);
		fclose($pipes[2]);

		$exitCode = proc_close($process);
		if( $exitCode !== 0 ) {
			throw new RuntimeException('pyte renderer failed: ' . trim((string)$stderr));
		}

		$decoded = json_decode((string)$stdout, true);
		if( !is_array($decoded) || !isset($decoded['lines'], $decoded['cursor_row'], $decoded['cursor_col']) ) {
			throw new RuntimeException('Invalid pyte renderer output');
		}

		$this->lines = $decoded['lines'];
		$this->cursorRow = (int)$decoded['cursor_row'];
		$this->cursorCol = (int)$decoded['cursor_col'];
	}

	public function getLine($row): string {
		$index = (int)$row - 1;
		if( $index < 0 || !isset($this->lines[$index]) ) {
			return '';
		}

		return (string)$this->lines[$index];
	}

	public function getCursorRow(): int {
		return $this->cursorRow;
	}

	public function getCursorCol(): int {
		return $this->cursorCol;
	}

}
