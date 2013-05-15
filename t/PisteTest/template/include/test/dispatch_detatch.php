<?php
$t->heading('chained dispatch detatch', 3);

$t->get('/detatch/doone/dotwo');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Detatch::before',  array(),
                'Root::auto',       array(),
                'Detatch::auto',    array(),
                'Detatch::doone',   array(),
                'Detatch::dotwo',   array(),
          ),
          'detatch from chain. Dont run any "after" action');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->get('/detatch/doone/dotwo/neverdothree');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Detatch::before',  array(),
                'Root::auto',       array(),
                'Detatch::auto',    array(),
                'Detatch::doone',   array(),
                'Detatch::dotwo',   array(),
          ),
          'detatch from chain. Dont run any more chain actions or "after" action');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->get('/detatch/doone/dotwo?breakbefore=1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Detatch::before',  array(),
          ),
          'detatch in "before" method. Dont run any more actions');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->get('/detatch/doone/dotwo?breakdetatchauto=1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Detatch::before',  array(),
                'Root::auto',       array(),
                'Detatch::auto',    array(),
          ),
          'detatch in "root auto" method. Dont run any more actions');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->get('/detatch/doone/dotwo?breakrootauto=1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Detatch::before',  array(),
                'Root::auto',       array(),
          ),
          'detatch in "root auto" method. Dont run any more actions');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");
?>
