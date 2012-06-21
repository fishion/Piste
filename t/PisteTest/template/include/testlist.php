<?php

$GLOBALS['timeout_pass'] = 50;
$GLOBALS['timeout_fail'] = 100;
$GLOBALS['testlist'] = array(

    #################################################
    # Basic COntroller and Special method behaviour #
    #################################################

    # test root controller
    array('/',
          array(
                'Root::before',
                'Root::auto',
                'Root::index',
                'Root::after',
          ),
          array(),
          'index',
          'Test empty directory dispatches to \'index\' method'),
    array('/index',
          array(
                'Root::before',
                'Root::auto',
                'Root::index',
                'Root::after',
          ),
          array(),
          'index',
          'Test index controller method in Root'),
    array('/absolute/path/in/root',
          array(
                'Root::before',
                'Root::auto',
                'Root::absolutepath',
                'Root::after',
          ),
          array(),
          'absolutepath',
          'Test setting an absolute path in root'),
    array('/relative/path/in/root',
          array(
                'Root::before',
                'Root::auto',
                'Root::relativepath',
                'Root::after',
          ),
          array(),
          'relativepath',
          'Test setting a relative path in root'),

    # test level1 controller
    array('/level1/',
          array(
                'Level1::before',
                'Root::auto',
                'Level1::auto',
                'Level1::index',
                'Level1::after',
          ),
          array(),
          'level1/index',
          'Test empty directory dispatches to \'index\' method in level1'),
    array('/level1/index',
          array(
                'Level1::before',
                'Root::auto',
                'Level1::auto',
                'Level1::index',
                'Level1::after',
          ),
          array(),
          'level1/index',
          'Test index controller method in Level1'),
    array('/absolute/path/in/level1',
          array(
                'Level1::before',
                'Root::auto',
                'Level1::auto',
                'Level1::absolutepath',
                'Level1::after',
          ),
          array(),
          'level1/absolutepath',
          'Test setting an absolute path in level1'),
    array('/level1/relative/path/in/level1',
          array(
                'Level1::before',
                'Root::auto',
                'Level1::auto',
                'Level1::relativepath',
                'Level1::after',
          ),
          array(),
          'level1/relativepath',
          'Test setting a relative path in level1'),

    # test level2 controller
    array('/level1/level2/',
          array(
                'Level1\Level2::before',
                'Root::auto',
                'Level1::auto',
                'Level1\Level2::auto',
                'Level1\Level2::index',
                'Level1\Level2::after',
          ),
          array(),
          'level1/level2/index',
          'Test empty directory dispatches to \'index\' method in level2'),
    array('/level1/level2/index',
          array(
                'Level1\Level2::before',
                'Root::auto',
                'Level1::auto',
                'Level1\Level2::auto',
                'Level1\Level2::index',
                'Level1\Level2::after',
          ),
          array(),
          'level1/level2/index',
          'Test index controller method in Level2'),
    array('/absolute/path/in/level2',
          array(
                'Level1\Level2::before',
                'Root::auto',
                'Level1::auto',
                'Level1\Level2::auto',
                'Level1\Level2::absolutepath',
                'Level1\Level2::after',
          ),
          array(),
          'level1/level2/absolutepath',
          'Test setting an absolute path in Level2'),
    array('/level1/level2/relative/path/in/level2',
          array(
                'Level1\Level2::before',
                'Root::auto',
                'Level1::auto',
                'Level1\Level2::auto',
                'Level1\Level2::relativepath',
                'Level1\Level2::after',
          ),
          array(),
          'level1/level2/relativepath',
          'Test setting a relative path in Level2'),

    # test fallback methods
    array('/doesntexist',
          array(
                'Root::before',
                'Root::auto',
                'Root::fallback',
                'Root::after',
          ),
          array('doesntexist'),
          'fallback',
          'No contoller set for this. Should use Root fallback'),
    array('/level1/doesntexist',
          array(
                'Root::before',
                'Root::auto',
                'Root::fallback',
                'Root::after',
          ),
          array('level1','doesntexist'),
          'fallback',
          'no fallback method so special methods all \'Root\' based'),
    array('/level1/nested/much/deeper/doesntexist',
          array(
                'Root::before',
                'Root::auto',
                'Root::fallback',
                'Root::after',
          ),
          array('level1','nested','much','deeper','doesntexist'),
          'fallback',
          'no fallback method so special methods all \'Root\' based'),
    array('/level1withfallback/doesntexist',
          array(
                'Root::before',
                'Root::auto',
                'Level1WithFallback::fallback',
                'Root::after',
          ),
          array('doesntexist'),
          'level1withfallback/fallback',
          'No contoller set for this. Level1WithFallback has no before/after/auto methods'),
    array('/level1withfallback/nested/much/deeper/doesntexist',
          array(
                'Root::before',
                'Root::auto',
                'Level1WithFallback::fallback',
                'Root::after',
          ),
          array('nested','much','deeper','doesntexist'),
          'level1withfallback/fallback',
          'No contoller set for this. Level1WithFallback has no before/after/auto methods'),
    array('/level1/level2/doesntexist',
          array(
                'Root::before',
                'Root::auto',
                'Root::fallback',
                'Root::after',
          ),
          array('level1','level2','doesntexist'),
          'fallback',
          'no fallback method so special methods all \'Root\' based'),
    array('/level1/level2/nested/much/deeper/doesntexist',
          array( 
                'Root::before',
                'Root::auto',
                'Root::fallback',
                'Root::after',
          ),
          array('level1','level2','nested','much','deeper','doesntexist'),
          'fallback',
          'no fallback method so special methods all \'Root\' based'),


    ############################################
    # Test Passing of agruments to controllers #
    ############################################

    array('/nofixedargs/param1/param2',
          array( 
                'Root::before',
                'Root::auto',
                'Root::nofixedargs',
                'Root::after',
          ),
          array('param1', 'param2'),
          'index',
          '2 Params passed to nofixedargs method. Resolves fine'),
    array('/fixedargs0',
          array( 
                'Root::before',
                'Root::auto',
                'Root::fixedargs0',
                'Root::after',
          ),
          null,
          'index',
          'No Params passed to Args(0) method.'),
    array('/fixedargs0/param1',
          array( 
                'Root::before',
                'Root::auto',
                'Root::fallback',
                'Root::after',
          ),
          array('fixedargs0','param1'),
          'fallback',
          '1 Params passed to Args(0) method. Doesn\'t resolve - fallback used'),
    array('/fixedargs1',
          array( 
                'Root::before',
                'Root::auto',
                'Root::fallback',
                'Root::after',
          ),
          array('fixedargs1'),
          'fallback',
          '0 Params passed to Args(1) method. Doesn\'t resolve - fallback used'),
    array('/fixedargs1/param1',
          array( 
                'Root::before',
                'Root::auto',
                'Root::fixedargs1',
                'Root::after',
          ),
          array('param1'),
          'index',
          '1 Param passed to Args(1) method. All happy'),
    array('/fixedargs1/param1/param2',
          array( 
                'Root::before',
                'Root::auto',
                'Root::fallback',
                'Root::after',
          ),
          array('fixedargs1','param1','param2'),
          'fallback',
          '2 Params passed to Args(1) method. Doesn\'t resolve - fallback used'),

    # do some level1 tests too
    array('/level1/nofixedargs/param1/param2',
          array( 
                'Level1::before',
                'Root::auto',
                'Level1::auto',
                'Level1::nofixedargs',
                'Level1::after',
          ),
          array('param1', 'param2'),
          'index',
          '2 Params passed to level1/nofixedargs method. Resolves fine'),
    array('/level1/fixedargs0',
          array( 
                'Level1::before',
                'Root::auto',
                'Level1::auto',
                'Level1::fixedargs0',
                'Level1::after',
          ),
          null,
          'index',
          'No Params passed to Args(0) method. Resolves OK'),
    array('/level1/fixedargs0/param1',
          array( 
                'Root::before',
                'Root::auto',
                'Root::fallback',
                'Root::after',
          ),
          array('level1','fixedargs0','param1'),
          'fallback',
          '1 Params passed to Args(0) method. Doesn\'t resolve - fallback used'),
    array('/level1/fixedargs1/param1',
          array( 
                'Level1::before',
                'Root::auto',
                'Level1::auto',
                'Level1::fixedargs1',
                'Level1::after',
          ),
          array('param1'),
          'index',
          '1 Param passed to Args(1) method. All happy'),
);

function test(){
    global $testlist, $execution_stack, $template, $args;
    $state = getstate();
    $tn = $state['testno'];

    if ($tn > count($testlist)){
    testoutput($tn, &$state, $pass, $failstr);
        die("Trying to test beyond number of tests. Something's gone a bit wrong");
    }
    
    # test execution stack
    $pass1 = ($execution_stack === $testlist[$tn][1]);
    $failstr = $pass1 ? '' :
        '<br> - Expected execution stack : ' . join(', ',$testlist[$tn][1]) .
        '<br> - Actual execution stack ..: ' . join(', ',$execution_stack);
    # test args
    $pass2 = ($args == $testlist[$tn][2]);
    $failstr .= $pass2 ? '' :
        "<br> - Expected Args : " . '('.join(',',array_map(function($n){return "'$n'";},$testlist[$tn][2])) .')'.
        "<br> - Actual Args ..: " . '('.join(',',array_map(function($n){return "'$n'";},$args)).')';
    # test template
    $pass3 = ($template == $testlist[$tn][3]);
    $failstr .= $pass3 ? '' :
        "<br> - Expected Template : " . $testlist[$tn][3] .
        "<br> - Actual template ..: $template";
    $pass = ($pass1 && $pass2 && $pass3);
    if ($pass){
        $state['pass']++;
    }

 
    testoutput($tn, &$state, $pass, $failstr);
    $state['testno']++;
    storestate($state);
    redirect($tn+1, $pass);
}

function testoutput($tn, $state, $pass, $failstr){
    global $testlist, $pagetitle;
    $pagetitle = "Test $tn";
    if (!$pass){
        $passclass = $pass ? 'pass' : 'fail';
        $state['results'] .= "<li class=\"$passclass\">" . strtoupper($passclass) . ' : ';
        $state['results'] .= $testlist[$tn][0] . "', " . $testlist[$tn][4] . ". $failstr</li>";
    }
    echo '<ol>' . $state['results'] . '</ol>';
}

function redirect($tn,$pass){
    global $testlist, $timeout_pass, $timeout_fail;
    $redirect = (isset($testlist[$tn])) ? $testlist[$tn][0] : '/results';
    $timeout = $pass ? $timeout_pass : $timeout_fail;
    echo "<script>setTimeout(function(){location.href = '$redirect'}, $timeout)</script>";
}

function results(){
    global $testlist, $pagetitle;
    $state = getstate();
    $allrun = ($state['testno'] == count($testlist)) ? 'pass' : 'fail';
    $allpassed = ($state['pass'] == count($testlist)) ? 'pass' : 'fail';

    $pagetitle = 'Test Results';
    echo "<p class=\"$allrun\">" . $state['testno'] .' of '. count($testlist) .' tests run</p>';
    echo "<p class=\"$allpassed\">" . $state['pass'] .' of '. count($testlist) .' tests passed</p>';
    echo '<ol>' . $state['results'] . '</ol>';
}


function storestate($state){
    setcookie("Pistetest[testno]", $state['testno'], time()+60, '/');
    setcookie("Pistetest[results]", $state['results'], time()+60, '/');
    setcookie("Pistetest[pass]", $state['pass'], time()+60, '/');
}
function getstate(){
    $cookies = $_COOKIE["Pistetest"];
    # if no referrer, delete cookie
    if (!$_SERVER['HTTP_REFERER']){
        $cookies = null;
    }
    return isset($cookies) ? $cookies : 
    array(
        'testno' => 0,
        'pass' => 0,
        'results' => '',
    );
}

?>
