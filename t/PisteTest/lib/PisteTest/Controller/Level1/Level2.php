<?php
namespace PisteTest\Controller\Level1;
require_once('Piste/Controller.php');
/*=head1 Name
PisteTest\Controller\Level1\Level1

*/
Class Level2 extends \Piste\Controller {

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
     *
     * { "path": "/absolute/path/in/level2" }
     */
    public function absolutepath($pc){
    }

    /**
     * test explicitly defined relative path
     *
     * { "path": "relative/path/in/level2" }
     */
    public function relativepath($pc){
    }
    
    #####
    # chained methods
    #####

    /**
     * This one deliberately named the same as one in Level1 to ensure
     * hierarchy is respected.
     * { "chained" : "chained1" }
     */
    public function chained2($pc){
    }
    /**
     * {
     *  "chained" : "chained2",
     *  "endchain" : true
     * }
     */
    public function chained2_3($pc){
    }

    /**
     * This one sets the 'path' attribute in order to not use the
     * method name directly in the matching regex.
     * TODO what happens if the 'path' is relative vs global?
     * {
     *  "chained" : "/chained1",
     *  "path"  : "bitofapath",
     *  "args" : 1
     * }
     */
    public function chained_path1($pc){
    }
    /**
     * this one gets args as no 'args' key defined
     * {
     *  "chained" : "chained_path1",
     *  "path"  : ""
     * }
     */
    public function chained_path2($pc){
    }
    /**
     * this one gets no args, because it's chained off
     * another method which will greedily take them all
     * {
     *  "chained" : "chained_path2",
     *  "path"  : ""
     * }
     */
    public function chained_path3($pc){
    }
    /**
     * {
     *  "chained" : "chained_path3",
     *  "path"  : "bitofapath/anotherbitofapath",
     *  "endchain" : true
     * }
     */
    public function chained_path4($pc){
    }
    
}

?>
