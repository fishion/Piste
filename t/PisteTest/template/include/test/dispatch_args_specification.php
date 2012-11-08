<?php
$t->heading('passing agruments to controllers', 3);

$t->get('/nofixedargs/arg1/arg2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::nofixedargs',array('arg1', 'arg2'),
                'Root::after',      array(),
          ),
          '2 Args passed to nofixedargs method. Resolves fine');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->get('/fixedargs0');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fixedargs0', array(),
                'Root::after',      array(),
          ),
          'No Args passed to Args(0) method.');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->get('/fixedargs0/arg1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('fixedargs0','arg1'),
                'Root::after',      array(),
          ),
          '1 Args passed to Args(0) method. Doesn\'t resolve - fallback used');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

$t->get('/fixedargs1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('fixedargs1'),
                'Root::after',      array(),
          ),
          '0 Args passed to Args(1) method. Doesn\'t resolve - fallback used');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

$t->get('/fixedargs1/arg1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fixedargs1', array('arg1'),
                'Root::after',      array(),
          ),
          '1 Arg passed to Args(1) method. All happy');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->get('/fixedargs1/arg1/arg2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('fixedargs1','arg1','arg2'),
                'Root::after',      array(),
          ),
          '2 Args passed to Args(1) method. Doesn\'t resolve - fallback used');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

// do some level1 tests too
$t->get('/level1/nofixedargs/arg1/arg2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::nofixedargs',  array('arg1', 'arg2'),
                'Level1::after',        array(),
          ),
          '2 Args passed to level1/nofixedargs method. Resolves fine');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->get('/level1/fixedargs0');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::fixedargs0',   array(),
                'Level1::after',        array(),
          ),
          'No Args passed to Args(0) method. Resolves OK');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->get('/level1/fixedargs0/arg1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('level1','fixedargs0','arg1'),
                'Root::after',      array(),
          ),
          '1 Args passed to Args(0) method. Doesn\'t resolve - fallback used');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

$t->get('/level1/fixedargs1/arg1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::fixedargs1',   array('arg1'),
                'Level1::after',        array(),
          ),
          '1 Arg passed to Args(1) method. All happy');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");
?>
