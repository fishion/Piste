<?php
$t->heading('specifity is respected', 3);

$t->get('/level1/specifity/morespecifity/arg1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::morespecifity',array('arg1'),
                'Level1::after',        array(),
          ),
          'Check that most specific controller is used');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");


?>
