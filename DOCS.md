# Documentation

## Class: \CLI\Cursor

```php
<?php
namespace CLI;

class Cursor {
	/**
	 * Pointer to the stream where the data is sent
	 * @var resource
	 */
	public static $stream = STDERR;
}
```

### Method: Cursor::up

```php
function up([ $count = 1])
```

Move the cursor up by count

#### Parameters:

- ***int*** `$count`

---

### Method: Cursor::down

```php
function down([ $count = 1])
```

Move the cursor down by count

#### Parameters:

- ***int*** `$count`

---

### Method: Cursor::forward

```php
function forward([ $count = 1])
```

Move the cursor right by count

#### Parameters:

- ***int*** `$count`

---

### Method: Cursor::back

```php
function back([ $count = 1])
```

Move the cursor left by count

#### Parameters:

- ***int*** `$count`

---

### Method: Cursor::rowcol

```php
function rowcol([ $row = 1 [, $col = 1]])
```

Move the cursor to a specific row and column

#### Parameters:

- ***int*** `$row`
- ***int*** `$col`

---

### Method: Cursor::savepos

```php
function savepos()
```

Save the current cursor position

---

### Method: Cursor::save

```php
function save()
```

Save the current cursor position and attributes

---

### Method: Cursor::unsave

```php
function unsave()
```

Delete the currently saved cursor data

---

### Method: Cursor::restore

```php
function restore()
```

Restore the previously saved cursor data

---

### Method: Cursor::hide

```php
function hide()
```

Hides the cursor

---

### Method: Cursor::show

```php
function show()
```

Shows the cursor

---

### Method: Cursor::wrap

```php
function wrap([ $wrap = true])
```

Enable/Disable Auto-Wrap

#### Parameters:

- ***bool*** `$wrap`

## Class: \CLI\Erase

```php
<?php
namespace CLI;

class Erase {
	/**
	 * Pointer to the stream where the data is sent
	 * @var resource
	 */
	public static $stream = STDERR;
}
```

### Method: Erase::eol

```php
function eol()
```

Erase to the end of line

---

### Method: Erase::sol

```php
function sol()
```

Erase to the start of line

---

### Method: Erase::line

```php
function line([ $row = null])
```

Erase entire line

#### Parameters:

- ***int*** | ***null*** `$row` - from a specific row

---

### Method: Erase::down

```php
function down([ $row = null])
```

Erases everything below the cursor

#### Parameters:

- ***int*** | ***null*** `$row` - from a specific row

---

### Method: Erase::up

```php
function up([ $row = null])
```

Erases everything above the cursor

#### Parameters:

- ***int*** | ***null*** `$row` - from a specific row

---

### Method: Erase::screen

```php
function screen()
```

Erases the entire screen

## Class: \CLI\Graphics



### Undocumented Method: `Graphics::line($x1, $y1, $x2, $y2 [, $chars = array('#')])`

---

### Undocumented Method: `Graphics::box($x1, $y1, $x2, $y2 [, $frame = array("-", "|", "-", "|", "x", "x", "x", "x")])`

## Class: \CLI\Misc

```php
<?php
namespace CLI;

class Misc {
	/**
	 * Pointer to the stream where the data is sent
	 * @var resource
	 */
	public static $stream = STDERR;
}
```

### Method: Misc::cols

```php
function cols([ $cache = true])
```

The col size of the current terminal as returned by tput

#### Parameters:

- ***bool*** `$cache` - Whether to cache the response

#### Returns:

- ***int***

---

### Method: Misc::rows

```php
function rows([ $cache = true])
```

The row size of the current terminal as returned by tput

#### Parameters:

- ***bool*** `$cache` - Whether to cache the response

#### Returns:

- ***int***

---

### Method: Misc::bell

```php
function bell([ $count = 1])
```

Triggers a terminal bell

#### Parameters:

- ***int*** `$count`

---

### Method: Misc::savestate

```php
function savestate()
```

Save the current state of the terminal via tput

---

### Method: Misc::restorestate

```php
function restorestate()
```

Restore the current state of the terminal via tput

## Class: \CLI\Output

```php
<?php
namespace CLI;

class Output {
	/**
	 * Pointer to the stream where the data is sent
	 * @var resource
	 */
	public static $stream = STDOUT;
}
```

### Method: Output::string

```php
function string($str [, $row = null [, $col = null]])
```

Output a string

#### Parameters:

- ***string*** `$str` - String to output
- ***false*** | ***int*** `$row` - The optional row to output to
- ***false*** | ***int*** `$col` - The optional column to output to

---

### Method: Output::line

```php
function line($str [, $col = null [, $erase = true]])
```

Output a line, erasing the line first

#### Parameters:

- ***string*** `$str` - String to output
- ***null*** | ***int*** `$col` - The column to draw the current line
- ***bool*** `$erase` - Clear the line before drawing the passed string

## Class: \CLI\StatusGUI

```php
<?php
namespace CLI;

class StatusGUI {
	/**
	 * Pointer to the stream where the data is sent
	 * @var resource
	 */
	public static $stream = STDERR;
	public static $altstream = STDOUT;
}
```

### Method: StatusGUI::statusbar

```php
function statusbar($str [, $height = null [, $last_line_to_opposing_stream = true]])
```

Render a statusbar stack

#### Parameters:

- ***string*** `$str` - The status to add
- ***null*** | ***int*** `$height` - The height of the status menu to render
- ***bool*** `$last_line_to_opposing_stream` - Send the last line of the status to the oposite stream (STDERR/STDOUT)

---

### Method: StatusGUI::progressbar

```php
function progressbar($title, $numerator, $denominator, $line [, $time_id = null [, $color = 'cyan']])
```

Draw a Progress Bar

#### Parameters:

- ***string*** `$title`
- ***int*** `$numerator`
- ***int*** `$denominator`
- ***int*** `$line` - Which row of the terminal to render
- ***int*** | ***null*** `$time_id`
- ***string*** `$color`

---

### Method: StatusGUI::histogram

```php
function histogram($title, $numerator, $denominator, $line, $hist_id [, $color = 'normal' [, $full_color = 'red']])
```

Draw a Histogram

#### Parameters:

- ***string*** `$title`
- ***int*** `$numerator`
- ***int*** `$denominator`
- ***int*** `$line`
- ***int*** `$hist_id`
- ***string*** `$color`
- ***string*** `$full_color`

## Class: \CLI\Style

Class Style

### Magic Method: Style::bold

```php
function bold($text, $args) : void
```

---

### Magic Method: Style::black

```php
function black($text, $args) : void
```

---

### Magic Method: Style::blue

```php
function blue($text, $args) : void
```

---

### Magic Method: Style::green

```php
function green($text, $args) : void
```

---

### Magic Method: Style::cyan

```php
function cyan($text, $args) : void
```

---

### Magic Method: Style::red

```php
function red($text, $args) : void
```

---

### Magic Method: Style::purple

```php
function purple($text, $args) : void
```

---

### Magic Method: Style::brown

```php
function brown($text, $args) : void
```

---

### Magic Method: Style::light_gray

```php
function light_gray($text, $args) : void
```

---

### Magic Method: Style::normal

```php
function normal($text, $args) : void
```

---

### Magic Method: Style::dim

```php
function dim($text, $args) : void
```

---

### Magic Method: Style::dark_gray

```php
function dark_gray($text, $args) : void
```

---

### Magic Method: Style::light_blue

```php
function light_blue($text, $args) : void
```

---

### Magic Method: Style::light_green

```php
function light_green($text, $args) : void
```

---

### Magic Method: Style::light_cyan

```php
function light_cyan($text, $args) : void
```

---

### Magic Method: Style::light_red

```php
function light_red($text, $args) : void
```

---

### Magic Method: Style::light_purple

```php
function light_purple($text, $args) : void
```

---

### Magic Method: Style::yellow

```php
function yellow($text, $args) : void
```

---

### Magic Method: Style::white

```php
function white($text, $args) : void
```



### Undocumented Method: `Style::__callStatic($foreground_color, $args)`