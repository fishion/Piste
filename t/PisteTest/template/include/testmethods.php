<?php

class TestSimple {
    private $exit_on_fail = true;

    private $testlist = array();
    private $redirects = 0;
    private $passed = array();
    private $failed = array();
    private $tn = false;

    public final function is($subject, $control, $desc = null){
        array_push($this->testlist,
            array(
                'type' => 'is',
                'func' => function($args){
                    return ($args[0] === $args[1]) ? true : false;
                },
                'args' => array($subject, $control),
                'desc' => $desc ? $desc : "$subject == $control",
            )
        );
    }
    public final function redirect($url){
        $this->redirects++;
        array_push($this->testlist,
            array('redirect' => $url,)
        );
    }


    public final function run(){
        echo '<ol>';

        # right. where are we
        # fastforward to where we left off
        $this->restorestate();
        while ( $this->tn > 0 &&
                list ($tn, $test) = each($this->testlist) ){
            if (!isset($test['redirect'])){
                # dump out results so far
                $this->testoutput($test, in_array($tn, $this->passed));
            }
            if ($tn >= $this->tn){
                break;
            }
        }

        while (list ($tn, $test) = each($this->testlist) ){
            $this->tn = $tn;
            if (isset($test['redirect'])){
                #echo "<script>location.href='$url';</script>";
                echo sprintf("<script>setTimeout(function(){location.href = '%s'}, 10)</script>", $test['redirect']);
                $this->storestate();
                break;
            }
            try {
                $passed = $test['func']($test['args']);
                if ($passed){
                    array_push($this->passed, $tn);
                } else {
                    array_push($this->failed, $tn);
                }
                $this->testoutput($test, $passed);
            } catch(\Exception $err){
                echo sprintf("Failed to run test $tn (%s) : %s", $test['desc'], $err);
            }       
        }
        echo '</ol>';

        # get to the end, show results
        $this->results();
    }

    private final function testcount(){
        return count($this->testlist) - $this->redirects;
    }
    private final function testoutput($test, $pass){
        $passclass = $pass ? 'pass' : 'fail';
        echo "<li class=\"$passclass\">" . strtoupper($passclass) . ' : ';
        echo $test['desc']. '</li>';
    }
    private function results(){
        $allpassed = (count($this->passed) == $this->testcount()) ? 'pass' : 'fail';
        echo sprintf("<p class=\"$allpassed\">%s of %s tests passed, %s failed</p>", count($this->passed), $this->testcount(), count($this->failed));
    }


    private function storestate(){
        setcookie("Pistetest[tn]", $this->tn, time()+60, '/');
        setcookie("Pistetest[passed]", join(',',$this->passed), time()+60, '/');
        setcookie("Pistetest[failed]", join(',',$this->failed), time()+60, '/');
    }
    private function restorestate(){
        if (!isset($_SERVER['HTTP_REFERER'])){
            # if no referrer, don't restore anything and delete cookie
            $_COOKIE["Pistetest"] = null;
        } elseif (isset($_COOKIE["Pistetest"])){
            $this->tn = $_COOKIE["Pistetest"]['tn'];
            $this->passed = isset($_COOKIE["Pistetest"]['passed']) ?
                            split(',',$_COOKIE["Pistetest"]['passed'])
                            : array();
            $this->failed = isset($_COOKIE["Pistetest"]['failed']) ?
                            split(',',$_COOKIE["Pistetest"]['failed'])
                            : array();
        }
    }
}   



?>
