# Quick Template

Quick Template is a simple [Smarty][smarty] wrapper for Moodle.

## Installation

After cloning or downloading and unpacking:

```
$ mv quick_template/smarty3 {MOODLE_ROOT}/lib/smarty3
$ mv quick_template/quick_template.php {MOODLE_ROOT}/lib/quick_template.php
```

## Core Usage

The `|s` filter gives you access to Moodle core language string identifiers.

__Inputs:__

index.tpl

```
<div class="greetings">
    {$greeting} Your {'firstname'|s} is {$name}.
</div>
```

index.php

```scala
require_once $CFG->libdir . "/quick_template.php";

$template_data = array(
    "greeting" => "Hello World!",
    "name" => $USER->firstname
);

quick_template::render("index.tpl", $template_data);
```

__Output:__

```html
<div class="greetings">
    Hello World! Your First name is Philip
</div>
```

## Plugin Usage:

By adding a third argument to `render`, the `|s` filter gives you quick access to a plugin's language strings.

__Inputs:__

index.tpl

```
<div class="greetings">
    {$greeting} {"pluginname"|s} is a pretty cool block.
</div>
```

index.php

```scala
require_once $CFG->libdir . "/quick_template.php";

$template_data = array(
    "greeting" => "Hello World!"
);

quick_template::render("index.tpl", $template_data, "block_quickmail");
```

__Output:__

```html
<div class="greetings">
    Hello World! Quickmail is a pretty cool block.
</div>
```

[smarty]: http://www.smarty.net/
