<?php
$t->heading('chained dispatch by HTTP method', 3);

$t->get('/chained1/arg1/getpost/arg2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::chained1',   array('arg1'),
                'Root::chainedget', array('arg2'),
                'Root::after',      array(),
          ),
          'chained GET action');
$t->is( $GLOBALS['pc']->template(), 'chainedget',
                 "uses correct default template");
$t->post('/chained1/arg1/getpost/arg2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::chained1',   array('arg1'),
                'Root::chainedpost',array('arg2'),
                'Root::after',      array(),
          ),
          'chained POST action');
$t->is( $GLOBALS['pc']->template(), 'chainedpost',
                 "uses correct default template");


$t->get('/getpost/arg1/chainedgetpost/arg2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::rootchainedget', array('arg1'),
                'Root::rootchainedget2', array('arg2'),
                'Root::after',      array(),
          ),
          'chained GET action');
$t->is( $GLOBALS['pc']->template(), 'rootchainedget2',
                 "uses correct default template");
$t->post('/getpost/arg1/chainedgetpost/arg2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::rootchainedpost', array('arg1'),
                'Root::rootchainedpost2', array('arg2'),
                'Root::after',      array(),
          ),
          'chained POST action');
$t->is( $GLOBALS['pc']->template(), 'rootchainedpost2',
                 "uses correct default template");

?>
