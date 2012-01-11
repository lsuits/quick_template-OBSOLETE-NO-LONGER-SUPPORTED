# Quick Template

Quick Template is a simple [Smarty][smarty] wrapper for Moodle.

## Installation

After cloning or downloading and unpacking:

```
$ mv quick_template {MOODLE_ROOT}/lib
```

You can choose to have Quick Template install as a submodule to a local git
repository, if you so desire.

```
$ git submodule add git://github.com/lsuits/quick_template.git lib/quick_template
$ cd lib/quick_template
$ git checkout v{DESIRED_TAG_VERSION}
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
require_once $CFG->libdir . "/quick_template/lib.php";

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
require_once $CFG->libdir . "/quick_template/lib.php";

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

## Advanced Usage:

Smarty allows dynamic manipulation by registering plugins, functions, objects,
filters, etc.

Quick Template allows for such registering as well.

For example, it's possible to pass in a closure that utilizes the `$OUTPUT`
renderer for very specific html rendering (like user pictures).

__Inputs:__

participants.tpl

```
<div class="box">
    <table>
        <tr>
            <th>{"userpic:moodle"|s}</th>
            <th>{"fullname:moodle"|s}</th>
        </tr>
        {foreach $users as $user}
            <tr>
                <td>{picture user=$user}</td>
                <td>{fullname user=$user}</td>
            </tr>
        {/foreach}
    </table>
</div>
```

participants.php

```scala
require_once $CFG->libdir . "/quick_template/lib.php";

$data = array("users" => $users);

$registers = array(
    "function" => array(
        "picture" => function($params, &$smarty) use ($OUTPUT, $COURSE) {
            return $OUTPUT->user_picture($params["user"]);
        },
        "fullname" => function($params, &$smarty) {
            return fullname($params["user"]);
        }
    ),
);

quick_template::render("participants.tpl", $data, "block_quickmail", $registers);
```

## License

Quick Template adopts the same license as Moodle.

[smarty]: http://www.smarty.net/
