<?php
namespace PisteTest;
require_once('Piste/Controller.php');
Class Controller extends \Piste\Controller {

    protected function track_execution_stack($pc, $method){
        $es = $pc->stash('execution_stack')? $pc->stash('execution_stack') : array();
        array_push($es, preg_replace('/^.*\\\Controller\\\/','',$method));
        $pc->stash('execution_stack', $es);
    }
 
}

?>
