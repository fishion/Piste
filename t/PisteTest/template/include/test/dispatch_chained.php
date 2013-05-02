<?php
$t->heading('chained dispatch', 3);

$t->get('/level1/chained1/arg1/chained2/arg2/arg3/chained3/arg4/arg5');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',   array(),
                'Root::auto',       array(),
                'Level1::auto',     array(),
                'Level1::chained1', array('arg1'),
                'Level1::chained2', array('arg2', 'arg3'),
                'Level1::chained3', array('arg4', 'arg5'),
                'Level1::after',    array(),
          ),
          'chain 3 controllers in same namespace together with args');
$t->is( $GLOBALS['pc']->template(), 'level1/chained3',
                 "uses correct default template");

$t->get('/chains/arg1/arg2/arg3');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Chains::before',   array(),
                'Root::auto',       array(),
                'Chains::auto',     array(),
                'Chains::chainednopath1', array('arg1'),
                'Chains::chainednopath2', array('arg2','arg3'),
                'Chains::after',   array(),
          ),
          'chain 2 with no paths');
$t->is( $GLOBALS['pc']->template(), 'chains/chainednopath2',
                 "uses correct default template");

$t->get('/level1/chained1/arg1/chained2/arg2/chained2_3/arg3');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1\Level2::before',    array(),
                'Root::auto',               array(),
                'Level1::auto',             array(),
                'Level1\Level2::auto',      array(),
                'Level1::chained1',         array('arg1'),
                'Level1\Level2::chained2',  array('arg2'),
                'Level1\Level2::chained2_3',array('arg3'),
                'Level1\Level2::after',     array(),
          ),
          'chain off method in parent namespace');
$t->is( $GLOBALS['pc']->template(), 'level1/level2/chained2_3',
                 "uses correct default template");

$t->get('/level1/chained1/arg1/chained2/chained4');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',           array(),
                'Root::auto',               array(),
                'Level1::auto',             array(),
                'Level1::chained1',         array('arg1'),
                'Level1\Level2::chained2',  array(),
                'Level1::chained4',         array(),
                'Level1::after',            array(),
          ),
          'chain accross arbitrary global namespaces');
$t->is( $GLOBALS['pc']->template(), 'level1/chained4',
                 "uses correct default template");

$t->get('/level1/chained1/arg1/chained2/chained5');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',           array(),
                'Root::auto',               array(),
                'Level1::auto',             array(),
                'Level1::chained1',         array('arg1'),
                'Level1\Level2::chained2',  array(),
                'Level1::chained5',         array(),
                'Level1::after',            array(),
          ),
          'chain accross arbitrary relative namespaces');
$t->is( $GLOBALS['pc']->template(), 'level1/chained5',
                 "uses correct default template");

$t->get('/chained1/arg1/chained6');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',           array(),
                'Root::auto',               array(),
                'Level1::auto',             array(),
                'Root::chained1',           array('arg1'),
                'Level1::chained6',         array(),
                'Level1::after',            array(),
          ),
          'chain to namespaced root action');
$t->is( $GLOBALS['pc']->template(), 'level1/chained6',
                 "uses correct default template");

$t->get('/chained1/arg1/bitofapath/arg2/arg3/arg4/bitofapath/anotherbitofapath');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1\Level2::before',        array(),
                'Root::auto',                   array(),
                'Level1::auto',                 array(),
                'Level1\Level2::auto',          array(),
                'Root::chained1',               array('arg1'),
                'Level1\Level2::chained_path1', array('arg2'),
                'Level1\Level2::chained_path2', array('arg3', 'arg4'),
                'Level1\Level2::chained_path3', array(),
                'Level1\Level2::chained_path4', array(),
                'Level1\Level2::after',         array(),
          ),
          'test chained methods with \'path\' set');
$t->is( $GLOBALS['pc']->template(), 'level1/level2/chained_path4',
                 "uses correct default template");

?>
