<?php

use CLI\Style;

require __DIR__ . '/../vendor/autoload.php';

echo Style::red("foobar", "underline");
echo Style::blue("foobar", "green", "underline");
