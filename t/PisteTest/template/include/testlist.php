<?php
require_once('testmethods.php');

$GLOBALS['testlist'] = array(

    #################################################
    # Basic COntroller and Special method behaviour #
    #################################################

    # test root controller
    array('/',
          array(
                'Root::before', array(),
                'Root::auto',   array(),
                'Root::index',  array(),
                'Root::after',  array(),
          ),
          'index',
          'Test empty directory dispatches to \'index\' method'),
    array('/index',
          array(
                'Root::before', array(),
                'Root::auto',   array(),
                'Root::index',  array(),
                'Root::after',  array(),
          ),
          'index',
          'Test index controller method in Root'),
    array('/absolute/path/in/root',
          array(
                'Root::before',         array(),
                'Root::auto',           array(),
                'Root::absolutepath',   array(),
                'Root::after',          array(),
          ),
          'absolutepath',
          'Test setting an absolute path in root'),
    array('/relative/path/in/root',
          array(
                'Root::before',         array(),
                'Root::auto',           array(),
                'Root::relativepath',   array(),
                'Root::after',          array(),
          ),
          'relativepath',
          'Test setting a relative path in root'),

    # test level1 controller
    array('/level1/',
          array(
                'Level1::before',   array(),
                'Root::auto',       array(),
                'Level1::auto',     array(),
                'Level1::index',    array(),
                'Level1::after',    array(),
          ),
          'level1/index',
          'Test empty directory dispatches to \'index\' method in level1'),
    array('/level1/index',
          array(
                'Level1::before',   array(),
                'Root::auto',       array(),
                'Level1::auto',     array(),
                'Level1::index',    array(),
                'Level1::after',    array(),
          ),
          'level1/index',
          'Test index controller method in Level1'),
    array('/absolute/path/in/level1',
          array(
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::absolutepath', array(),
                'Level1::after',        array(),
          ),
          'level1/absolutepath',
          'Test setting an absolute path in level1'),
    array('/level1/relative/path/in/level1',
          array(
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::relativepath', array(),
                'Level1::after',        array(),
          ),
          'level1/relativepath',
          'Test setting a relative path in level1'),

    # test level2 controller
    array('/level1/level2/',
          array(
                'Level1\Level2::before', array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1\Level2::auto',  array(),
                'Level1\Level2::index', array(),
                'Level1\Level2::after', array(),
          ),
          'level1/level2/index',
          'Test empty directory dispatches to \'index\' method in level2'),
    array('/level1/level2/index',
          array(
                'Level1\Level2::before', array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1\Level2::auto',  array(),
                'Level1\Level2::index', array(),
                'Level1\Level2::after', array(),
          ),
          'level1/level2/index',
          'Test index controller method in Level2'),
    array('/absolute/path/in/level2',
          array(
                'Level1\Level2::before',        array(),
                'Root::auto',                   array(),
                'Level1::auto',                 array(),
                'Level1\Level2::auto',          array(),
                'Level1\Level2::absolutepath',  array(),
                'Level1\Level2::after',         array(),
          ),
          'level1/level2/absolutepath',
          'Test setting an absolute path in Level2'),
    array('/level1/level2/relative/path/in/level2',
          array(
                'Level1\Level2::before',        array(),
                'Root::auto',                   array(),
                'Level1::auto',                 array(),
                'Level1\Level2::auto',          array(),
                'Level1\Level2::relativepath',  array(),
                'Level1\Level2::after',         array(),
          ),
          'level1/level2/relativepath',
          'Test setting a relative path in Level2'),

    # test fallback methods
    array('/doesntexist',
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('doesntexist'),
                'Root::after',      array(),
          ),
          'fallback',
          'No contoller set for this. Should use Root fallback'),
    array('/level1/doesntexist',
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('level1','doesntexist'),
                'Root::after',      array(),
          ),
          'fallback',
          'no fallback method so special methods all \'Root\' based'),
    array('/level1/nested/much/deeper/doesntexist',
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('level1','nested','much','deeper','doesntexist'),
                'Root::after',      array(),
          ),
          'fallback',
          'no fallback method so special methods all \'Root\' based'),
    array('/level1withfallback/doesntexist',
          array(
                'Root::before',                 array(),
                'Root::auto',                   array(),
                'Level1WithFallback::fallback', array('doesntexist'),
                'Root::after',                  array(),
          ),
          'level1withfallback/fallback',
          'No contoller set for this. Level1WithFallback has no before/after/auto methods'),
    array('/level1withfallback/nested/much/deeper/doesntexist',
          array(
                'Root::before',                 array(),
                'Root::auto',                   array(),
                'Level1WithFallback::fallback', array('nested','much','deeper','doesntexist'),
                'Root::after',                  array(),
          ),
          'level1withfallback/fallback',
          'No contoller set for this. Level1WithFallback has no before/after/auto methods'),
    array('/level1/level2/doesntexist',
          array(
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('level1','level2','doesntexist'),
                'Root::after',      array(), 
          ),
          'fallback',
          'no fallback method so special methods all \'Root\' based'),
    array('/level1/level2/nested/much/deeper/doesntexist',
          array( 
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('level1','level2','nested','much','deeper','doesntexist'),
                'Root::after',      array(),
          ),
          'fallback',
          'no fallback method so special methods all \'Root\' based'),


    ############################################
    # Test Passing of agruments to controllers #
    ############################################

    array('/nofixedargs/param1/param2',
          array( 
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::nofixedargs',array('param1', 'param2'),
                'Root::after',      array(),
          ),
          'index',
          '2 Params passed to nofixedargs method. Resolves fine'),
    array('/fixedargs0',
          array( 
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fixedargs0', array(),
                'Root::after',      array(),
          ),
          'index',
          'No Params passed to Args(0) method.'),
    array('/fixedargs0/param1',
          array( 
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('fixedargs0','param1'),
                'Root::after',      array(),
          ),
          'fallback',
          '1 Params passed to Args(0) method. Doesn\'t resolve - fallback used'),
    array('/fixedargs1',
          array( 
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('fixedargs1'),
                'Root::after',      array(),
          ),
          'fallback',
          '0 Params passed to Args(1) method. Doesn\'t resolve - fallback used'),
    array('/fixedargs1/param1',
          array( 
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fixedargs1', array('param1'),
                'Root::after',      array(),
          ),
          'index',
          '1 Param passed to Args(1) method. All happy'),
    array('/fixedargs1/param1/param2',
          array( 
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('fixedargs1','param1','param2'),
                'Root::after',      array(),
          ),
          'fallback',
          '2 Params passed to Args(1) method. Doesn\'t resolve - fallback used'),

    # do some level1 tests too
    array('/level1/nofixedargs/param1/param2',
          array( 
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::nofixedargs',  array('param1', 'param2'),
                'Level1::after',        array(),
          ),
          'index',
          '2 Params passed to level1/nofixedargs method. Resolves fine'),
    array('/level1/fixedargs0',
          array( 
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::fixedargs0',   array(),
                'Level1::after',        array(),
          ),
          'index',
          'No Params passed to Args(0) method. Resolves OK'),
    array('/level1/fixedargs0/param1',
          array( 
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::fallback',   array('level1','fixedargs0','param1'),
                'Root::after',      array(),
          ),
          'fallback',
          '1 Params passed to Args(0) method. Doesn\'t resolve - fallback used'),
    array('/level1/fixedargs1/param1',
          array( 
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::fixedargs1',   array('param1'),
                'Level1::after',        array(),
          ),
          'index',
          '1 Param passed to Args(1) method. All happy'),

    ################################################
    # Test specifity is respected
    ################################################

    array('/level1/specifity/morespecifity/param1',
          array( 
                'Level1::before',       array(),
                'Root::auto',           array(),
                'Level1::auto',         array(),
                'Level1::morespecifity',array('param1'),
                'Level1::after',        array(),
          ),
          'index',
          'Check that most specific controller is used'),


    ################################################
    # Chaining controllers
    ################################################

    array('/level1/chained1/param1/chained2/param2/param3/chained3/param4/param5',
          array( 
                'Level1::before',   array(),
                'Root::auto',       array(),
                'Level1::auto',     array(),
                'Level1::chained1', array('param1'),
                'Level1::chained2', array('param2', 'param3'),
                'Level1::chained3', array('param4', 'param5'),
                'Level1::after',    array(),
          ),
          'level1/chained3',
          'chained 3 controllers together with args'),


    ################################################
    # Redirecting
    ################################################

    array('/redirect/param1',
          array( 
                'Root::before',     array(),
                'Root::auto',       array(),
                'Root::redirected', array('param1'),
                'Root::after',      array(),
          ),
          'index',
          "Redirect from 'redirect' to 'redirected'. Retain parameter"),
);


?>
