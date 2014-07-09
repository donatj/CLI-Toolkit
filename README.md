# PHP CLI-Toolkit

[![Latest Stable Version](https://poser.pugx.org/donatj/cli-toolkit/v/stable.png)](https://packagist.org/packages/donatj/cli-toolkit)
[![Total Downloads](https://poser.pugx.org/donatj/cli-toolkit/downloads.png)](https://packagist.org/packages/donatj/cli-toolkit) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/donatj/CLI-Toolkit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/donatj/CLI-Toolkit/?branch=master)

A Simple PHP CLI Toolkit

## Installing

CLI-Toolkit is available through Packagist via Composer.

```json
"require": {
  "donatj/cli-toolkit": "dev-master",
}
```

## Documentation

### Class: Cursor \[ `\CLI` \]

#### Method: `Cursor`::`up([ $count = 1])`

Move the cursor up by count  
  


##### Parameters:

- ***int*** `$count`



---

#### Method: `Cursor`::`down([ $count = 1])`

Move the cursor down by count  
  


##### Parameters:

- ***int*** `$count`



---

#### Method: `Cursor`::`forward([ $count = 1])`

Move the cursor right by count  
  


##### Parameters:

- ***int*** `$count`



---

#### Method: `Cursor`::`back([ $count = 1])`

Move the cursor left by count  
  


##### Parameters:

- ***int*** `$count`



---

#### Method: `Cursor`::`rowcol([ $row = 1 [, $col = 1]])`

Move the cursor to a specific row and column  
  


##### Parameters:

- ***int*** `$row`
- ***int*** `$col`



---

#### Method: `Cursor`::`savepos()`

Save the current cursor position  
  



---

#### Method: `Cursor`::`save()`

Save the current cursor position and attributes  
  



---

#### Method: `Cursor`::`unsave()`

Delete the currently saved cursor data  
  



---

#### Method: `Cursor`::`restore()`

Restore the previously saved cursor data  
  



---

#### Method: `Cursor`::`hide()`

Hides the cursor  
  



---

#### Method: `Cursor`::`show()`

Shows the cursor  
  

### Class: Erase \[ `\CLI` \]

#### Method: `Erase`::`eol()`

Erase to the end of line  
  



---

#### Method: `Erase`::`sol()`

Erase to the start of line  
  



---

#### Method: `Erase`::`line([ $row = null])`

Erase entire line  
  


##### Parameters:

- ***int*** | ***null*** `$row` - from a specific row



---

#### Method: `Erase`::`down([ $row = null])`

Erases everything below the cursor  
  


##### Parameters:

- ***int*** | ***null*** `$row` - from a specific row



---

#### Method: `Erase`::`up([ $row = null])`

Erases everything above the cursor  
  


##### Parameters:

- ***int*** | ***null*** `$row` - from a specific row



---

#### Method: `Erase`::`screen()`

Erases the entire screen  
  

### Class: Graphics \[ `\CLI` \]

#### Undocumented Method: `Graphics`::`line($x1, $y1, $x2, $y2 [, $chars = array('#')])`
#### Undocumented Method: `Graphics`::`box($x1, $y1, $x2, $y2 [, $frame = array("-", "|", "-", "|", "x", "x", "x", "x")])`

### Class: Misc \[ `\CLI` \]

#### Method: `Misc`::`cols([ $cache = true])`

The col size of the current terminal as returned by tput  
  


##### Parameters:

- ***bool*** `$cache` - Whether to cache the response


##### Returns:

- ***int***


---

#### Method: `Misc`::`rows([ $cache = true])`

The row size of the current terminal as returned by tput  
  


##### Parameters:

- ***bool*** `$cache` - Whether to cache the response


##### Returns:

- ***int***


---

#### Method: `Misc`::`bell([ $count = 1])`

Triggers a terminal bell  
  


##### Parameters:

- ***int*** `$count`



---

#### Method: `Misc`::`savestate()`

Save the current state of the terminal via tput  
  



---

#### Method: `Misc`::`restorestate()`

Restore the current state of the terminal via tput  
  

### Class: Output \[ `\CLI` \]

#### Method: `Output`::`string($str [, $row = null [, $col = null]])`

Output a string  
  


##### Parameters:

- ***string*** `$str` - String to output
- ***false*** | ***int*** `$row` - The optional row to output to
- ***false*** | ***int*** `$col` - The optional column to output to



---

#### Method: `Output`::`line($str [, $col = null [, $erase = true]])`

Output a line, erasing the line first  
  


##### Parameters:

- ***string*** `$str` - String to output
- ***null*** | ***int*** `$col` - The column to draw the current line
- ***boolean*** `$erase` - Clear the line before drawing the passed string

### Class: StatusGUI \[ `\CLI` \]

#### Method: `StatusGUI`::`statusbar($str [, $height = null [, $last_line_to_opposing_stream = true]])`

Render a statusbar stack  
  


##### Parameters:

- ***string*** `$str` - The status to add
- ***null*** | ***int*** `$height` - The height of the status menu to render
- ***bool*** `$last_line_to_opposing_stream` - Send the last line of the status to the oposite stream (STDERR/STDOUT)



---

#### Method: `StatusGUI`::`progressbar($title, $numerator, $denominator, $line [, $time_id = null [, $color = 'cyan']])`

Draw a Progress Bar  
  


##### Parameters:

- ***string*** `$title`
- ***int*** `$numerator`
- ***int*** `$denominator`
- ***int*** `$line` - Which row of the terminal to render
- ***int*** | ***null*** `$time_id`
- ***string*** `$color`



---

#### Method: `StatusGUI`::`histogram($title, $numerator, $denominator, $line, $hist_id [, $color = 'normal' [, $full_color = 'red']])`

Draw a Histogram  
  


##### Parameters:

- ***string*** `$title`
- ***int*** `$numerator`
- ***int*** `$denominator`
- ***int*** `$line`
- ***int*** `$hist_id`
- ***string*** `$color`
- ***string*** `$full_color`

### Class: Style \[ `\CLI` \]

Class Style

#### Undocumented Method: `Style`::`__callStatic($foreground_color, $args)`

Different Level

