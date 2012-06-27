<?php
namespace PisteTest;
require_once('Piste/Controller.php');
Class Controller extends \Piste\Controller {

    protected function track_execution_stack($pc, $method, $template=null){
        # populate execution stack list
        $es = $pc->stash('execution_stack') ? $pc->stash('execution_stack') : array();
        array_push($es, preg_replace('/^.*\\\Controller\\\/','',$method));
        array_push($es, $pc->args());
        $pc->stash('execution_stack', $es);

        # set template if required
        if ($template){
            $pc->stash('template', $template);
        }
    }
 
}

?>
