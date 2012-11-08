<?php
$t->heading('root controller', 3);

$t->get('/');
$t->is( $GLOBALS['pc']->execution_stack,
        array(  'Root::before', array(),
                'Root::auto',   array(),
                'Root::index',  array(),
                'Root::after',  array(),
            ),
        'Test empty directory dispatches to \'index\' method');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "'/' uses index default template");

$t->get('/index');
$t->is( $GLOBALS['pc']->execution_stack,
        array(  'Root::before', array(),
                'Root::auto',   array(),
                'Root::index',  array(),
                'Root::after',  array(),
            ),
        'Test index controller method in Root');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->get('/absolute/path/in/root');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',         array(),
                'Root::auto',           array(),
                'Root::absolutepath',   array(),
                'Root::after',          array(),
          ),
          'Test setting an absolute path in root');
$t->is( $GLOBALS['pc']->template(), 'absolutepath',
                 "uses correct default template");

$t->get('/relative/path/in/root');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',         array(),
                'Root::auto',           array(),
                'Root::relativepath',   array(),
                'Root::after',          array(),
          ),
          'Test setting a relative path in root');
$t->is( $GLOBALS['pc']->template(), 'relativepath',
                 "uses correct default template");


?>
