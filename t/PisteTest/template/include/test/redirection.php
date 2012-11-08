<?php
$t->heading('redirection', 3);

$t->get('/redirect/arg1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::redirected', array('arg1'),
                'Root::after',      array(),
          ),
          "Redirect from 'redirect' to 'redirected'. Retain parameter");
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

?>
