<?php
$t->heading('fallback methods', 3);

$t->get('/doesntexist');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('doesntexist'),
                'Root::after',      array(),
          ),
          'No contoller set for this. Should use Root fallback');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

$t->get('/level1/doesntexist');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('level1','doesntexist'),
                'Root::after',      array(),
          ),
          'no fallback method so special methods all \'Root\' based');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

$t->get('/level1/nested/much/deeper/doesntexist');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('level1','nested','much','deeper','doesntexist'),
                'Root::after',      array(),
          ),
          'no fallback method so special methods all \'Root\' based');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

$t->get('/level1withfallback/doesntexist');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',                 array(),
                'Root::auto',                   array(),
                'Level1WithFallback::fallback', array('doesntexist'),
                'Root::after',                  array(),
          ),
          'No contoller set for this. Level1WithFallback has no before/after/auto methods');
$t->is( $GLOBALS['pc']->template(), 'level1withfallback/fallback',
                 "uses correct default template");

$t->get('/level1withfallback/nested/much/deeper/doesntexist');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',                 array(),
                'Root::auto',                   array(),
                'Level1WithFallback::fallback', array('nested','much','deeper','doesntexist'),
                'Root::after',                  array(),
          ),
          'No contoller set for this. Level1WithFallback has no before/after/auto methods');
$t->is( $GLOBALS['pc']->template(), 'level1withfallback/fallback',
                 "uses correct default template");

$t->get('/level1/level2/doesntexist');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('level1','level2','doesntexist'),
                'Root::after',      array(), 
          ),
          'no fallback method so special methods all \'Root\' based');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

$t->get('/level1/level2/nested/much/deeper/doesntexist');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('level1','level2','nested','much','deeper','doesntexist'),
                'Root::after',      array(),
          ),
          'no fallback method so special methods all \'Root\' based');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

?>
