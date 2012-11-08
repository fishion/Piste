<?php
$t->heading('level1 controller', 3);

$t->get('/level1/');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',   array(),
                'Root::auto',       array(),
                'Level1::auto',     array(),
                'Level1::index',    array(),
                'Level1::after',    array(),
          ),
          'Test empty directory dispatches to \'index\' method in level1');
$t->is( $GLOBALS['pc']->template(), 'level1/index',
                 "uses correct default template");

$t->get('/level1/index');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',   array(),
                'Root::auto',       array(),
                'Level1::auto',     array(),
                'Level1::index',    array(),
                'Level1::after',    array(),
          ),
          'Test index controller method in Level1');
$t->is( $GLOBALS['pc']->template(), 'level1/index',
                 "uses correct default template");

$t->get('/absolute/path/in/level1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::absolutepath', array(),
                'Level1::after',        array(),
          ),
          'Test setting an absolute path in level1');
$t->is( $GLOBALS['pc']->template(), 'level1/absolutepath',
                 "uses correct default template");

$t->get('/level1/relative/path/in/level1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::relativepath', array(),
                'Level1::after',        array(),
          ),
          'Test setting a relative path in level1');
$t->is( $GLOBALS['pc']->template(), 'level1/relativepath',
                 "uses correct default template");

?>
