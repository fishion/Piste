<?php

$t->heading('Testing dispatch', 2);

$t->heading('Basic Controller and Special method behaviour', 3);

$t->heading('test root controller', 4);
$t->is( $GLOBALS['pc']->execution_stack,
        array(  'Root::before', array(),
                'Root::auto',   array(),
                'Root::index',  array(),
                'Root::after',  array(),
            ),
        'Test empty directory dispatches to \'index\' method');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "'/' uses index default template");

$t->redirect('/index');
$t->is( $GLOBALS['pc']->execution_stack,
        array(  'Root::before', array(),
                'Root::auto',   array(),
                'Root::index',  array(),
                'Root::after',  array(),
            ),
        'Test index controller method in Root');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->redirect('/absolute/path/in/root');
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


$t->redirect('/relative/path/in/root');
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

$t->heading('test level1 controller', 4);
$t->redirect('/level1/');
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

$t->redirect('/level1/index');
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

$t->redirect('/absolute/path/in/level1');
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

$t->redirect('/level1/relative/path/in/level1');
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

$t->heading('test level2 controller', 4);
$t->redirect('/level1/level2/');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1\Level2::before', array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1\Level2::auto',  array(),
                'Level1\Level2::index', array(),
                'Level1\Level2::after', array(),
          ),
          'Test empty directory dispatches to \'index\' method in level2');
$t->is( $GLOBALS['pc']->template(), 'level1/level2/index',
                 "uses correct default template");

$t->redirect('/level1/level2/index');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1\Level2::before', array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1\Level2::auto',  array(),
                'Level1\Level2::index', array(),
                'Level1\Level2::after', array(),
          ),
          'Test index controller method in Level2');
$t->is( $GLOBALS['pc']->template(), 'level1/level2/index',
                 "uses correct default template");

$t->redirect('/absolute/path/in/level2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1\Level2::before',        array(),
                'Root::auto',                   array(),
                'Level1::auto',                 array(),
                'Level1\Level2::auto',          array(),
                'Level1\Level2::absolutepath',  array(),
                'Level1\Level2::after',         array(),
          ),
          'Test setting an absolute path in Level2');
$t->is( $GLOBALS['pc']->template(), 'level1/level2/absolutepath',
                 "uses correct default template");

$t->redirect('/level1/level2/relative/path/in/level2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1\Level2::before',        array(),
                'Root::auto',                   array(),
                'Level1::auto',                 array(),
                'Level1\Level2::auto',          array(),
                'Level1\Level2::relativepath',  array(),
                'Level1\Level2::after',         array(),
          ),
          'Test setting a relative path in Level2');
$t->is( $GLOBALS['pc']->template(), 'level1/level2/relativepath',
                 "uses correct default template");

$t->heading('test fallback methods', 4);
$t->redirect('/doesntexist');
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

$t->redirect('/level1/doesntexist');
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

$t->redirect('/level1/nested/much/deeper/doesntexist');
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

$t->redirect('/level1withfallback/doesntexist');
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

$t->redirect('/level1withfallback/nested/much/deeper/doesntexist');
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

$t->redirect('/level1/level2/doesntexist');
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

$t->redirect('/level1/level2/nested/much/deeper/doesntexist');
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

$t->heading('Test Passing of agruments to controllers', 3);

$t->redirect('/nofixedargs/param1/param2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::nofixedargs',array('param1', 'param2'),
                'Root::after',      array(),
          ),
          '2 Params passed to nofixedargs method. Resolves fine');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->redirect('/fixedargs0');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fixedargs0', array(),
                'Root::after',      array(),
          ),
          'No Params passed to Args(0) method.');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->redirect('/fixedargs0/param1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('fixedargs0','param1'),
                'Root::after',      array(),
          ),
          '1 Params passed to Args(0) method. Doesn\'t resolve - fallback used');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

$t->redirect('/fixedargs1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('fixedargs1'),
                'Root::after',      array(),
          ),
          '0 Params passed to Args(1) method. Doesn\'t resolve - fallback used');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

$t->redirect('/fixedargs1/param1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fixedargs1', array('param1'),
                'Root::after',      array(),
          ),
          '1 Param passed to Args(1) method. All happy');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->redirect('/fixedargs1/param1/param2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('fixedargs1','param1','param2'),
                'Root::after',      array(),
          ),
          '2 Params passed to Args(1) method. Doesn\'t resolve - fallback used');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

$t->heading('do some level1 tests too', 4);
$t->redirect('/level1/nofixedargs/param1/param2');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::nofixedargs',  array('param1', 'param2'),
                'Level1::after',        array(),
          ),
          '2 Params passed to level1/nofixedargs method. Resolves fine');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->redirect('/level1/fixedargs0');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::fixedargs0',   array(),
                'Level1::after',        array(),
          ),
          'No Params passed to Args(0) method. Resolves OK');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->redirect('/level1/fixedargs0/param1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('level1','fixedargs0','param1'),
                'Root::after',      array(),
          ),
          '1 Params passed to Args(0) method. Doesn\'t resolve - fallback used');
$t->is( $GLOBALS['pc']->template(), 'fallback',
                 "uses correct default template");

$t->redirect('/level1/fixedargs1/param1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::fixedargs1',   array('param1'),
                'Level1::after',        array(),
          ),
          '1 Param passed to Args(1) method. All happy');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->heading('Test specifity is respected', 3);

$t->redirect('/level1/specifity/morespecifity/param1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::morespecifity',array('param1'),
                'Level1::after',        array(),
          ),
          'Check that most specific controller is used');
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");

$t->heading('Chaining controllers', 3);

# all in same namespace (with same name methods
# existing in same namespace
$t->redirect('/level1/chained1/param1/chained2/param2/param3/chained3/param4/param5');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',   array(),
                'Root::auto',       array(),
                'Level1::auto',     array(),
                'Level1::chained1', array('param1'),
                'Level1::chained2', array('param2', 'param3'),
                'Level1::chained3', array('param4', 'param5'),
                'Level1::after',    array(),
          ),
          'chain 3 controllers in same namespace together with args');
$t->is( $GLOBALS['pc']->template(), 'level1/chained3',
                 "uses correct default template");

$t->redirect('/level1/chained1/param1/chained2/param2/chained2_3/param3');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1\Level2::before',    array(),
                'Root::auto',               array(),
                'Level1::auto',             array(),
                'Level1\Level2::auto',      array(),
                'Level1::chained1',         array('param1'),
                'Level1\Level2::chained2',  array('param2'),
                'Level1\Level2::chained2_3',array('param3'),
                'Level1\Level2::after',     array(),
          ),
          'chain off method in parent namespace');
$t->is( $GLOBALS['pc']->template(), 'level1/level2/chained2_3',
                 "uses correct default template");

$t->redirect('/level1/chained1/param1/chained2/chained4');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',           array(),
                'Root::auto',               array(),
                'Level1::auto',             array(),
                'Level1::chained1',         array('param1'),
                'Level1\Level2::chained2',  array(),
                'Level1::chained4',         array(),
                'Level1::after',            array(),
          ),
          'chain accross arbitrary global namespaces');
$t->is( $GLOBALS['pc']->template(), 'level1/chained4',
                 "uses correct default template");

$t->redirect('/level1/chained1/param1/chained2/chained5');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',           array(),
                'Root::auto',               array(),
                'Level1::auto',             array(),
                'Level1::chained1',         array('param1'),
                'Level1\Level2::chained2',  array(),
                'Level1::chained5',         array(),
                'Level1::after',            array(),
          ),
          'chain accross arbitrary relative namespaces');
$t->is( $GLOBALS['pc']->template(), 'level1/chained5',
                 "uses correct default template");

$t->redirect('/chained1/param1/chained6');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1::before',           array(),
                'Root::auto',               array(),
                'Level1::auto',             array(),
                'Root::chained1',           array('param1'),
                'Level1::chained6',         array(),
                'Level1::after',            array(),
          ),
          'chain to namespaced root action');
$t->is( $GLOBALS['pc']->template(), 'level1/chained6',
                 "uses correct default template");

$t->redirect('/chained1/param1/bitofapath/param2/param3/param4/bitofapath/anotherbitofapath');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Level1\Level2::before',        array(),
                'Root::auto',                   array(),
                'Level1::auto',                 array(),
                'Level1\Level2::auto',          array(),
                'Root::chained1',               array('param1'),
                'Level1\Level2::chained_path1', array('param2'),
                'Level1\Level2::chained_path2', array('param3', 'param4'),
                'Level1\Level2::chained_path3', array(),
                'Level1\Level2::chained_path4', array(),
                'Level1\Level2::after',         array(),
          ),
          'test chained methods with \'path\' set');
$t->is( $GLOBALS['pc']->template(), 'level1/level2/chained_path4',
                 "uses correct default template");

$t->heading('Redirecting', 2);
$t->redirect('/redirect/param1');
$t->is( $GLOBALS['pc']->execution_stack,
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::redirected', array('param1'),
                'Root::after',      array(),
          ),
          "Redirect from 'redirect' to 'redirected'. Retain parameter");
$t->is( $GLOBALS['pc']->template(), 'index',
                 "uses correct default template");


$t->heading('Test Model Access', 2);
$t->redirect('/model/');
$t->is(isset($GLOBALS['testdata']) ? $GLOBALS['testdata'] : '', 'TestData', 'Managed to get test data out of model');


$t->heading('Test Cookies', 2);
$t->redirect('/cookie/set/' . time());
$time = isset($GLOBALS['timetomatch']) ? $GLOBALS['timetomatch'] : 'notimefound';
$t->redirect('/cookie/get/' . $time ); # pass time param
$t->is(isset($GLOBALS['mytime']) ? $GLOBALS['mytime'] : '', (string) $time, "got right value from cookie");
$t->redirect('/cookie/delete');
$t->redirect('/cookie/get');
$t->is(isset($GLOBALS['mytime']) ? $GLOBALS['mytime'] : '', '', "time value no longer in cookie");


$t->heading('Test Switching Views', 2);

 
?>
