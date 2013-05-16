<?php
$t->heading('passing query params to controllers', 3);

function req() { return $GLOBALS['pc']->req(); } 

$t->post('/index?foo=vfoo&bar=vbar',
         array('bar' => 'pbar', 'baz' => 'pbaz'));

$get_params = req()->get_params();
$foo        = isset($get_params['foo']) ? $get_params['foo'] : '';
$bar        = isset($get_params['bar']) ? $get_params['bar'] : '';
$t->is(req()->get_params('foo'), 'vfoo', 'foo correct from get_params("foo")');
$t->is(req()->get_params('bar'), 'vbar', 'baz correct from get_params("bar")');
$t->is($foo, 'vfoo', 'foo set correctly from get_params()');
$t->is($bar, 'vbar', 'baz set correctly from get_params()');

$post_params= req()->post_params();
$bar        = isset($post_params['bar']) ? $post_params['bar'] : '';
$baz        = isset($post_params['baz']) ? $post_params['baz'] : '';
$t->is(req()->post_params('bar'), 'pbar', 'bar correct, post_params("foo")');
$t->is(req()->post_params('baz'), 'pbaz', 'baz correct, post_params("bar")');
$t->is($bar, 'pbar', 'bar set correctly from post_params()');
$t->is($baz, 'pbaz', 'baz set correctly from post_params()');

$params = req()->params();
$foo        = isset($params['foo']) ? $params['foo'] : '';
$bar        = isset($params['bar']) ? $params['bar'] : '';
$baz        = isset($params['baz']) ? $params['baz'] : '';
$t->is(req()->params('foo'), 'vfoo', 'foo set correctly from params("foo")');
$t->is(req()->params('bar'), 'pbar', 'bar set correctly from params("bar")');
$t->is(req()->params('baz'), 'pbaz', 'baz set correctly from params("baz")');
$t->is($foo, 'vfoo', 'foo set correctly from params');
$t->is($bar, 'pbar', 'bar set correctly from params');
$t->is($baz, 'pbaz', 'baz set correctly from params');

?>
