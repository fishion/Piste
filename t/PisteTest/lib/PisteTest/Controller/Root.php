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
     * url param testing
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
    
}

?>
