<?php

$GLOBALS['testlist'] = array(

    # test root controller methods
    array('/',
          array(
                'Root::before',
                'Root::auto',
                'Root::index',
                'Root::after',
          ),
          'index',
          'Test empty directory dispatches to \'index\' method'),
    array('/index',
          array(
                'Root::before',
                'Root::auto',
                'Root::index',
                'Root::after',
          ),
          'index',
          'Test index controller method in Root'),
    array('/absolute/path/in/root',
          array(
                'Root::before',
                'Root::auto',
                'Root::absolutepath',
                'Root::after',
          ),
          'absolutepath',
          'Test setting an absolute path in root'),
    array('/relative/path/in/root',
          array(
                'Root::before',
                'Root::auto',
                'Root::relativepath',
                'Root::after',
          ),
          'relativepath',
          'Test setting a relative path in root'),
    array('/doesntexist',
          array(
                'Root::before',
                'Root::auto',
                'Root::fallback',
                'Root::after',
          ),
          'fallback',
          'No contoller set for this. Should use Root fallback'),

    # test level1 controller methods
    array('/level1/',
          array(
                'Level1::before',
                'Root::auto',
                'Level1::auto',
                'Level1::index',
                'Level1::after',
          ),
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
          'level1/relativepath',
          'Test setting a relative path in level1'),
    array('/level1/doesntexist', # no fallback method so special methods all 'Root' based
          array(
                'Root::before',
                'Root::auto',
                'Root::fallback',
                'Root::after',
          ),
          'fallback',
          'No contoller set for this. Should use Root fallback'),

    # test fallbacks
    array('/level1withfallback/doesntexist',
          array(
                'Root::before',
                'Root::auto',
                'Level1WithFallback::fallback',
                'Root::after',
          ),
          'level1withfallback/fallback',
          'No contoller set for this. Should use Level1WithFallback fallback'),
    array('/level1withfallback/nested/much/further/doesntexist',
          array(
                'Root::before',
                'Root::auto',
                'Level1WithFallback::fallback',
                'Root::after',
          ),
          'level1withfallback/fallback',
          'No contoller set for this. Should use Level1WithFallback fallback'),
    array('/level1/nested/much/further/doesntexist',
          array(
                'Root::before',
                'Root::auto',
                'Root::fallback',
                'Root::after',
          ),
          'fallback',
          'No contoller set for this. Should use Root fallback'),
);

function test(){
    global $testlist, $execution_stack, $template;
    $state = getstate();
    $tn = $state['testno'];

    if ($tn > count($testlist)){
        die("Trying to test beyond number of tests. Something's gone a bit wrong");
    }
    
    # test that we have the output we're expecting
    $pass = ($execution_stack === $testlist[$tn][1]);
    $failstr = $pass ? '' : 'EXECUTION STACK ' . join(', ',$execution_stack) . ' != ' . join(', ',$testlist[$tn][1]);
    $pass = ($pass && $template == $testlist[$tn][2]);
    $failstr .= ($failstr == '' ? '' : ', ') . ($pass ? '' : "TEMPLATE '$template' != '" . $testlist[$tn][2] . "'");
    if ($pass){
        $state['pass']++;
    }
    
    testoutput($tn, &$state, $pass, $failstr);
    $state['testno']++;
    storestate($state);
    redirect($tn+1, $state);
}

function testoutput($tn, $state, $pass, $failstr){
    global $testlist, $pagetitle;
    $pagetitle = "Test $tn";
    $passclass = $pass ? 'pass' : 'fail';
    $state['results'] .= "<li class=\"$passclass\">" . strtoupper($passclass) . ' : ';
    $state['results'] .= $testlist[$tn][0] . "', " . $testlist[$tn][3] . ". $failstr</li>";
    echo '<ol>' . $state['results'] . '</ol>';
}

function redirect($tn){
    global $testlist;
    $redirect = (isset($testlist[$tn])) ? $testlist[$tn][0] : '/results';
    echo "<script>setTimeout(function(){location.href = '$redirect'}, 250)</script>";
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
