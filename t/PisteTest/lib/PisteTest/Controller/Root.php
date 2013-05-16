<?php
namespace PisteTest\Controller;
require_once('Piste/Controller.php');
/*=head1 Name
PisteTest\Controller\Root

*/
Class Root extends \Piste\Controller {

    # special methods.
    protected function fallback($pc){
    }
    protected function before($pc){
    }
    protected function auto($pc){
        if ($pc->req()->params('breakrootauto')){
            $pc->controller()->detatch();
        }
    }
    protected function after($pc){
    }

    # test index
    public function index($pc) {
    }

    /**
     * test explicitly defined absolute path
     *
     * { "path": "/absolute/path/in/root" }
     */
    public function absolutepath($pc){
    }

    /**
     * test explicitly defined relative path
     * which is really same as absolute in Root controller
     *
     * { "path": "relative/path/in/root" }
     */
    public function relativepath($pc){
    }

    /**
     * Test specifying controller by get/post/etc action
     * {
     *  "path"        : "/getpost",
     *  "http_method" : "GET"
     * }
     */
    public function testaget($pc){
        $pc->template('index');
    }
    /**
     * Test specifying controller by get/post/etc action
     * {
     *  "path"        : "/getpost",
     *  "http_method" : "post"
     * }
     */
    public function testapost($pc){
        $pc->template('index');
    }


    /**
     * url arg testing
     */
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

    /**
     * redirection
     * { "args" : 1 }
     */
    public function redirect($pc){
        $pc->res()->redirect('/redirected/'.join('/', $pc->args()));
    }
    /**
     * { "args" : 1 }
     */
    public function redirected($pc){
        $pc->template('index');
    }

    /**
     * a chained action root
     * {
     *  "chained" : "",
     *  "args" : 1
     * }
     */
    public function chained1($pc){
    }

    /**
     * {
     *  "chained" : "chained1",
     *  "path" : "getpost",
     *  "http_method" : "GET",
     *  "endchain" : true
     * }
     */
    public function chainedget($pc){
    }

    /**
     * {
     *  "chained" : "chained1",
     *  "path" : "getpost",
     *  "http_method" : "POST",
     *  "endchain" : true
     * }
     */
    public function chainedpost($pc){
    }


    /**
     * Set HTTP method on chain root this time
     * {
     *  "chained" : "",
     *  "path" : "getpost",
     *  "args" : 1,
     *  "http_method" : "GET"
     * }
     */
    public function rootchainedget($pc){
    }

    /**
     * Set HTTP method on chain root this time
     * {
     *  "chained" : "",
     *  "path" : "getpost",
     *  "args" : 1,
     *  "http_method" : "post"
     * }
     */
    public function rootchainedpost($pc){
    }

    /**
     * {
     *  "chained" : "rootchainedget",
     *  "path" : "chainedgetpost",
     *  "endchain" : true
     * }
     */
    public function rootchainedget2($pc){
    }
    
    /**
     * {
     *  "chained" : "rootchainedpost",
     *  "path" : "chainedgetpost",
     *  "endchain" : true
     * }
     */
    public function rootchainedpost2($pc){
    }
}

?>
