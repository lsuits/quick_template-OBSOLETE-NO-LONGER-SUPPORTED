<?php

abstract class quick_template {

    public static function render($template, $data= array(), $module='moodle', $custom_funs = array(), $output = true) {
        global $CFG;

        require_once($CFG->libdir . '/smarty3/Smarty.class.php');

        $path = "$CFG->dataroot/templates_c";
        if(!is_dir($path)) {
            mkdir($path);
        }

        $smarty = new Smarty();
        $smarty->compile_dir = $path;
        foreach($data as $key => $value) {
            $smarty->assign($key, $value);
        }

        // Lang string modifier
        $lang = function ($var) use($module) {
                if(strpos($var, ":")) {
                    list($name, $use_module) = explode(":", $var);
                } else {
                    $name = $var;
                    $use_module = $module;
                }
                return get_string($name, $use_module);
            };

        // Set the moodle string function 
        if(isset($custom_funs['modifier'])) {
            $custom_funs['modifier']['s'] = $lang;
        } else {
            $custom_funs['modifier'] = array('s' => $lang);
        }

        foreach($custom_funs as $key => $funs) {
            $smarty_partial = self::map($key, $smarty);
            foreach($funs as $ident => $fun) {
                // Check only exists for closure objects
                if(method_exists($fun, '__invoke')) {
                    $smarty_partial($ident, array($fun, '__invoke'));
                } else {
                    $smarty_partial($ident, $fun);
                }
            }
        }

        if ($output) {
            $smarty->display($template);
        } else {
            return $smarty->fetch($template);
        }
    }

    private static function map($key, &$smarty) {
        $plugin = array('function', 'block', 'compiler', 'modifier');
        $filter = array('pre', 'post', 'output', 'variable');
        if(in_array($key, $plugin)) {
            return function($ident, $fun) use($key, $smarty) {
                $smarty->registerPlugin($key, $ident, $fun);
            };
        }

        if(in_array($key, $filter)) {
            return function($ident, $fun) use($key, $smarty) {
                $smarty->registerFilter($key, $ident, $fun);
            };
        }

        if($key == "object") {
            return function($ident, $fun) use($key, $smarty) {
                $smarty->registerObject($ident, $fun);
            };
        }

        throw new Exception("$key is not a registerable in Smarty");
    }
}
