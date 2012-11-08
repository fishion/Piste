<?php
$t->heading('dispatch by HTTP method', 3);

$t->get('/getpost/arg1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::testaget',   array('arg1'),
                'Root::after',      array(),
          ),
          'GET a method');
$t->post('/getpost/arg1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::testapost',  array('arg1'),
                'Root::after',      array(),
          ),
          'POST a method');

?>
