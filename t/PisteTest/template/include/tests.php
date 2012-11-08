<?php

$t->stop_on_fail = true;

$t->heading('Unit tests', 2);

$t->heading('Test Reflection subclasses', 3);

$rc = new Piste\ReflectionClass('\PisteTest\Controller\Reflection');
$t->is(count($rc->getNonInheritedMethods()), 6, "Right number of non-inherited methods in total");
$t->is(count($rc->getNonInheritedMethods(ReflectionMethod::IS_PROTECTED)), 1, "Right number of non-inherited private methods in total");
$t->is(count($rc->getNonInheritedMethods(ReflectionMethod::IS_PUBLIC)), 2, "Right number of non-inherited public methods in total");
$t->is(count($rc->getNonInheritedMethods(ReflectionMethod::IS_PRIVATE)), 3, "Right number of non-inherited protected methods in total");
$t->is(count($rc->getNonInheritedMethods(ReflectionMethod::IS_PUBLIC + ReflectionMethod::IS_PRIVATE)), 5, "Right number of non-inherited public + protected methods in total");


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

$t->heading('test level1 controller', 4);
$t->get('/level1/');
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

$t->get('/level1/index');
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

$t->get('/absolute/path/in/level1');
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

$t->get('/level1/relative/path/in/level1');
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
$t->get('/level1/level2/');
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

$t->get('/level1/level2/index');
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

$t->get('/absolute/path/in/level2');
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

$t->get('/level1/level2/relative/path/in/level2');
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

$t->heading('Test dispatch by HTTP method', 3);

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


$t->heading('Test Passing of agruments to controllers', 3);

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

$t->heading('do some level1 tests too', 4);
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


$t->heading('Test Passing of query params to controllers', 3);

function req() { return $GLOBALS['pc']->req(); } 

$t->post('/indexi?foo=vfoo&bar=vbar',
         array('bar' => 'pbar', 'baz' => 'pbaz'));
$get_params = req()->get_params();
$foo        = isset($get_params['foo']) ? $get_params['foo'] : '';
$bar        = isset($get_params['bar']) ? $get_params['bar'] : '';
$t->is(req()->get_param('foo'), 'vfoo', 'foo correct from get_param');
$t->is(req()->get_param('bar'), 'vbar', 'baz correct from get_param');
$t->is($foo, 'vfoo', 'foo set correctly from get_params');
$t->is($bar, 'vbar', 'baz set correctly from get_params');
$post_params= req()->post_params();
$bar        = isset($post_params['bar']) ? $post_params['bar'] : '';
$baz        = isset($post_params['baz']) ? $post_params['baz'] : '';
$t->is(req()->post_param('bar'), 'pbar', 'bar correct, post_param');
$t->is(req()->post_param('baz'), 'pbaz', 'baz correct, post_param');
$t->is($bar, 'pbar', 'bar set correctly from post_params');
$t->is($baz, 'pbaz', 'baz set correctly from post_params');
$params = req()->params();
$foo        = isset($params['foo']) ? $params['foo'] : '';
$bar        = isset($params['bar']) ? $params['bar'] : '';
$baz        = isset($params['baz']) ? $params['baz'] : '';
$t->is(req()->param('foo'), 'vfoo', 'foo set correctly from param');
$t->is(req()->param('bar'), 'pbar', 'bar set correctly from param');
$t->is(req()->param('baz'), 'pbaz', 'baz set correctly from param');
$t->is($foo, 'vfoo', 'foo set correctly from params');
$t->is($bar, 'pbar', 'bar set correctly from params');
$t->is($baz, 'pbaz', 'baz set correctly from params');


$t->heading('Test specifity is respected', 3);

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

$t->heading('Chaining controllers', 3);

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


# all in same namespace (with same name methods
# existing in same namespace
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

$t->heading('Redirecting', 2);
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


$t->heading('Test Model Access', 2);
$t->get('/model/');
$t->is(isset($GLOBALS['testdata']) ? $GLOBALS['testdata'] : '', 'TestData', 'Managed to get test data out of model');


$t->heading('Test Cookies', 2);
$t->get('/cookie/set/' . time());
$time = isset($GLOBALS['timetomatch']) ? $GLOBALS['timetomatch'] : 'notimefound';
$t->get('/cookie/get/' . $time ); # pass time param
$t->is(isset($GLOBALS['mytime']) ? $GLOBALS['mytime'] : '', (string) $time, "got right value from cookie");
$t->get('/cookie/delete');
$t->get('/cookie/get');
$t->is(isset($GLOBALS['mytime']) ? $GLOBALS['mytime'] : '', '', "time value no longer in cookie");


$t->heading('Test Switching Views', 2);

 
?>
