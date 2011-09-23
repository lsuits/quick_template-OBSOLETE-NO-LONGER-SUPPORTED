# Quick Template

Quick Template is a small Moodle specific wrapper around the [Smarty][smarty]
template engine.

## Installation

```
$ cd ~
$ git clone git@github.com:lsuits/quick_template.git
$ mv quick_template/smarty3 {MOODLE_ROOT}/lib/smarty3
$ mv quick_template/quick_template.php {MOODLE_ROOT}/lib/quick_template.php
```

## Usage

Basic usage for rendering:

__index.tpl__

```
<div class="greetings">
    {$starter}
</div>

```

__index.php__

```scala
require_once $CFG->libdir . "/quick_template.php";

$template_data = array(
    "starter" => "Hello World!"
);

quick_template::render("index.tpl", $template_data);
```
