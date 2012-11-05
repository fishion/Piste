<?php
namespace PisteTest\Controller;
require_once('Piste/Controller.php');
/*=head1 Name
PisteTest\Controller\Level1

*/
Class Level1 extends \Piste\Controller {

    # special methods
    protected function before($pc){
    }
    protected function auto($pc){
    }
    protected function after($pc){
    }

    # test that 'level1/' redirects as expected to index
    public function index($pc) {
    }

    /**
     * test explicitly defined absolute path
     * { "path" : "/absolute/path/in/level1" }
     */
    public function absolutepath($pc){
    }

    /**
     * test explicitly defined relative path
     * { "path" : "relative/path/in/level1" }
     */
    public function relativepath($pc){
    }
    
    # url param testing
    public function nofixedargs($pc){
        $pc->template('index');
    }
    /**
     * { "args" : 0 }
     */
    public function fixedargs0($pc){
        $pc->template('index');
    }
    /**
     * { "args" : 1 }
     */
    public function fixedargs1($pc){
        $pc->template('index');
    }

    # Test specifity
    public function specifity($pc){
        $pc->template('index');
    }
    /**
     * {
     *  "path" : "specifity/morespecifity",
     *  "args" : 1
     * }
     */
    public function morespecifity($pc){
        $pc->template('index');
    }

    ######
    # chained methods
    ######

    /**
     * simple three link chain
     * {
     *  "chained" : "/",
     *  "args" : 1
     * }
     */
    public function chained1($pc){
    }
    /**
     * { "chained" : "chained1" }
     */
    public function chained2($pc){
    }
    /**
     * {
     *  "chained" : "chained2",
     *  "args" : 2,
     *  "endchain" : true
     * }
     */
    public function chained3($pc){
    }

    /**
     * chained off globally referenced level2 namespace action
     * {
     *  "chained" : "/level1/level2/chained2",
     *  "endchain" : true
     * }
     */
    public function chained4($pc){
    }

    /**
     * chained off relatively referenced level2 namespace action
     * {
     *  "chained" : "level2/chained2",
     *  "endchain" : true
     * }
     */
    public function chained5($pc){
    }


    /**
     * chain of Root namespaced action (deliberately confusingly
     * named the same as a chained action in this namespace)
     * {
     *  "chained" : "/chained1",
     *  "endchain" : true
     * }
     */
    public function chained6($pc){
    }

}

?>
