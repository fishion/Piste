<?php

$GLOBALS['timeout_pass'] = 50;
$GLOBALS['timeout_fail'] = 1000;

function test(){
    global $testlist, $execution_stack, $template, $args;
    $state = getstate();
    $tn = $state['testno'];

    if ($tn > count($testlist)){
    testoutput($tn, &$state, $pass, $failstr);
        die("Trying to test beyond number of tests. Something's gone a bit wrong");
    }

    list ($expected_controllers, $expected_args) = array_untangle($testlist[$tn][1]);
    list ($received_controllers, $received_args) = array_untangle($execution_stack);

    # test execution stack
    $pass1 = ($expected_controllers == $received_controllers);
    $failstr = $pass1 ? '' :
        '<br> - Expected execution stack : ' . join(', ',$expected_controllers) .
        '<br> - Actual execution stack ..: ' . join(', ',$received_controllers);
    # test args
    $pass2 = ($expected_args == $received_args);
    $failstr .= $pass2 ? '' :
        "<br> - Expected Args : " . '('.join(',',array_map(function($n){return '(' . join(',',$n) . ')';},$expected_args )) .')'.
        "<br> - Actual Args ..: " . '('.join(',',array_map(function($n){return '(' . join(',',$n) . ')';},$received_args )). ')';
    # test template
    $pass3 = ($template == $testlist[$tn][2]);
    $failstr .= $pass3 ? '' :
        "<br> - Expected Template : " . $testlist[$tn][2] .
        "<br> - Actual template ..: $template";
    $pass = ($pass1 && $pass2 && $pass3);
    if ($pass){
        $state['pass']++;
    }

 
    testoutput($tn, &$state, $pass, $failstr);
    $state['testno']++;
    storestate($state);
    if (isset($_GET['singletest'])){
        return;
    }
    redirect($tn+1, $pass);
}

function testoutput($tn, $state, $pass, $failstr){
    global $testlist, $pagetitle;
    $pagetitle = "Test $tn";
    if (!$pass){
        $passclass = $pass ? 'pass' : 'fail';
        $state['results'] .= "<li class=\"$passclass\">" . strtoupper($passclass) . ' : ';
        $state['results'] .= $testlist[$tn][0] . "', " . $testlist[$tn][3] . ". $failstr</li>";
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

function array_untangle($array){
    $return = array(array(), array());
    foreach ($array as $index => $val){
        if ($index & 1){
            array_push($return[1], $val);
        } else {
            array_push($return[0], $val);
        }
    }
    return $return;
}

?>
