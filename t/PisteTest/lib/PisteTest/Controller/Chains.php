<?php
namespace PisteTest\Controller;
require_once('Piste/Controller.php');
/*=head1 Name
PisteTest\Controller\Chains

*/
Class Chains extends \Piste\Controller {

    # special methods
    protected function before($pc){
    }
    protected function auto($pc){
    }
    protected function after($pc){
    }

    /**
     * Start chain with no path parts
     * {
     *  "chained" : "",
     *  "path"  : "",
     *  "args" : 1
     * }
     */
    public function chainednopath1($pc){
    }
    /**
     * {
     *   "chained" : "chainednopath1",
     *   "path" : "",
     *   "args" : 2,
     *   "endchain" : true
     * }
     */
    public function chainednopath2($pc){
    }

}

?>
