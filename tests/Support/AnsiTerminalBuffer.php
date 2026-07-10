<?php

namespace Tests\Support;

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
	/** @var int */
	private $savedRow = 1;
	/** @var int */
	private $savedCol = 1;

	public function __construct($rows = 10, $cols = 40) {
		$this->rows = (int)$rows;
		$this->cols = (int)$cols;
		for( $row = 1; $row <= $this->rows; $row++ ) {
			$this->lines[$row] = str_repeat(' ', $this->cols);
		}
	}

	public function apply($output): void {
		$length = strlen($output);
		$index = 0;
		while( $index < $length ) {
			$char = $output[$index];
			if( $char !== "\033" ) {
				$this->writeChar($char);
				$index++;
				continue;
			}

			$index++;
			if( $index >= $length ) {
				break;
			}

			$escapeChar = $output[$index];
			if( $escapeChar === '[' ) {
				$index++;
				$params = '';
				while( $index < $length ) {
					$candidate = $output[$index];
					$ord = ord($candidate);
					if( $ord >= 0x40 && $ord <= 0x7E ) {
						$this->applyCsi($params, $candidate);
						$index++;
						break;
					}
					$params .= $candidate;
					$index++;
				}
				continue;
			}

			if( $escapeChar === '7' ) {
				$this->savedRow = $this->cursorRow;
				$this->savedCol = $this->cursorCol;
			} elseif( $escapeChar === '8' ) {
				$this->cursorRow = $this->savedRow;
				$this->cursorCol = $this->savedCol;
			}

			$index++;
		}
	}

	public function getLine($row): string {
		if( $row < 1 || $row > $this->rows ) {
			return str_repeat(' ', $this->cols);
		}

		return $this->lines[$row];
	}

	public function getCursorRow(): int {
		return $this->cursorRow;
	}

	public function getCursorCol(): int {
		return $this->cursorCol;
	}

	private function writeChar($char): void {
		if( $char === "\n" ) {
			$this->cursorRow = min($this->rows, $this->cursorRow + 1);
			$this->cursorCol = 1;
			return;
		}

		if( $char === "\r" ) {
			$this->cursorCol = 1;
			return;
		}

		$this->setChar($this->cursorRow, $this->cursorCol, $char);
		$this->cursorCol++;
		if( $this->cursorCol > $this->cols ) {
			$this->cursorCol = 1;
			$this->cursorRow = min($this->rows, $this->cursorRow + 1);
		}
	}

	private function applyCsi($params, $final): void {
		if( isset($params[0]) && $params[0] === '?' ) {
			return;
		}

		$parts = $params === '' ? array() : explode(';', $params);

		switch( $final ) {
			case 'f':
			case 'H':
				$row = isset($parts[0]) && $parts[0] !== '' ? (int)$parts[0] : 1;
				$col = isset($parts[1]) && $parts[1] !== '' ? (int)$parts[1] : 1;
				$this->cursorRow = $this->clamp($row, 1, $this->rows);
				$this->cursorCol = $this->clamp($col, 1, $this->cols);
				break;
			case 'A':
				$amount = isset($parts[0]) && $parts[0] !== '' ? (int)$parts[0] : 1;
				$this->cursorRow = $this->clamp($this->cursorRow - $amount, 1, $this->rows);
				break;
			case 'B':
				$amount = isset($parts[0]) && $parts[0] !== '' ? (int)$parts[0] : 1;
				$this->cursorRow = $this->clamp($this->cursorRow + $amount, 1, $this->rows);
				break;
			case 'C':
				$amount = isset($parts[0]) && $parts[0] !== '' ? (int)$parts[0] : 1;
				$this->cursorCol = $this->clamp($this->cursorCol + $amount, 1, $this->cols);
				break;
			case 'D':
				$amount = isset($parts[0]) && $parts[0] !== '' ? (int)$parts[0] : 1;
				$this->cursorCol = $this->clamp($this->cursorCol - $amount, 1, $this->cols);
				break;
			case 'K':
				$mode = isset($parts[0]) && $parts[0] !== '' ? (int)$parts[0] : 0;
				$this->eraseLine($mode);
				break;
			case 'J':
				$mode = isset($parts[0]) && $parts[0] !== '' ? (int)$parts[0] : 0;
				$this->eraseDisplay($mode);
				break;
		}
	}

	private function eraseLine($mode): void {
		if( $mode === 1 ) {
			$this->clearRange($this->cursorRow, 1, $this->cursorCol);
			return;
		}

		if( $mode === 2 ) {
			$this->clearRange($this->cursorRow, 1, $this->cols);
			return;
		}

		$this->clearRange($this->cursorRow, $this->cursorCol, $this->cols);
	}

	private function eraseDisplay($mode): void {
		if( $mode === 1 ) {
			for( $row = 1; $row <= $this->cursorRow; $row++ ) {
				$end = $row === $this->cursorRow ? $this->cursorCol : $this->cols;
				$this->clearRange($row, 1, $end);
			}
			return;
		}

		if( $mode === 2 ) {
			for( $row = 1; $row <= $this->rows; $row++ ) {
				$this->clearRange($row, 1, $this->cols);
			}
			return;
		}

		for( $row = $this->cursorRow; $row <= $this->rows; $row++ ) {
			$start = $row === $this->cursorRow ? $this->cursorCol : 1;
			$this->clearRange($row, $start, $this->cols);
		}
	}

	private function clearRange($row, $startCol, $endCol): void {
		$start = $this->clamp((int)$startCol, 1, $this->cols);
		$end = $this->clamp((int)$endCol, 1, $this->cols);
		if( $start > $end || !isset($this->lines[$row]) ) {
			return;
		}

		$line = $this->lines[$row];
		$line = substr_replace($line, str_repeat(' ', $end - $start + 1), $start - 1, $end - $start + 1);
		$this->lines[$row] = $line;
	}

	private function setChar($row, $col, $char): void {
		if( !isset($this->lines[$row]) || $col < 1 || $col > $this->cols ) {
			return;
		}

		$line = $this->lines[$row];
		$line = substr_replace($line, $char, $col - 1, 1);
		$this->lines[$row] = $line;
	}

	private function clamp($value, $min, $max): int {
		return max($min, min($max, (int)$value));
	}

}
