<?php
namespace PisteTest\Controller;
require_once('Piste/Controller.php');
/*=head1 Name
PisteTest\Controller\Detatch

*/
Class Detatch extends \Piste\Controller {

    # special methods
    protected function before($pc){
        $pc->template('index');
        if ($pc->req()->param('breakbefore')){
            $pc->controller()->detatch();
        }
    }
    protected function auto($pc){
        if ($pc->req()->param('breakrootauto')){
            $pc->controller()->detatch();
        }
    }
    protected function after($pc){
    }

    /**
     * {
     *  "chained" : ""
     * }
     */
    public function doone($pc){
    }

    /**
     * {
     *   "chained" : "doone",
     *   "endchain" : true
     * }
     */
    public function dotwo($pc){
        $pc->controller()->detatch();
    }

    /**
     * {
     *   "chained" : "dotwo",
     *   "endchain" : true
     * }
     */
    public function neverdothree($pc){
    }

}

?>
