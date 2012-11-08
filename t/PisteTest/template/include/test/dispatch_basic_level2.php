<?php
$t->heading('level2 controller', 3);

$t->get('/level1/level2/');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1\Level2::before', array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1\Level2::auto',  array(),
                'Level1\Level2::index', array(),
                'Level1\Level2::after', array(),
          ),
          'Test empty directory dispatches to \'index\' method in level2');
$t->is( $GLOBALS['pc']->template(), 'level1/level2/index',
                 "uses correct default template");

$t->get('/level1/level2/index');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1\Level2::before', array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1\Level2::auto',  array(),
                'Level1\Level2::index', array(),
                'Level1\Level2::after', array(),
          ),
          'Test index controller method in Level2');
$t->is( $GLOBALS['pc']->template(), 'level1/level2/index',
                 "uses correct default template");

$t->get('/absolute/path/in/level2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1\Level2::before',        array(),
                'Root::auto',                   array(),
                'Level1::auto',                 array(),
                'Level1\Level2::auto',          array(),
                'Level1\Level2::absolutepath',  array(),
                'Level1\Level2::after',         array(),
          ),
          'Test setting an absolute path in Level2');
$t->is( $GLOBALS['pc']->template(), 'level1/level2/absolutepath',
                 "uses correct default template");

$t->get('/level1/level2/relative/path/in/level2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1\Level2::before',        array(),
                'Root::auto',                   array(),
                'Level1::auto',                 array(),
                'Level1\Level2::auto',          array(),
                'Level1\Level2::relativepath',  array(),
                'Level1\Level2::after',         array(),
          ),
          'Test setting a relative path in Level2');
$t->is( $GLOBALS['pc']->template(), 'level1/level2/relativepath',
                 "uses correct default template");

?>
