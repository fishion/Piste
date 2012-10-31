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

    # test explicitly defined absolute path
    public $absolutepath_def = array(
        'path' => '/absolute/path/in/root'
    );
    public function absolutepath($pc){
    }

    # test explicitly defined relative path
    # which is really same as absolute in Root controller
    public $relativepath_def = array(
        'path' => 'relative/path/in/root'
    );
    public function relativepath($pc){
    }


    # url param testing
    public function nofixedargs($pc){
        $pc->stash('template', 'index');
    }
    public $fixedargs0_def = array(
        'args' => 0,
    );
    public function fixedargs0($pc){
        $pc->stash('template', 'index');
    }
    public $fixedargs1_def = array(
        'args' => 1,
    );
    public function fixedargs1($pc){
        $pc->stash('template', 'index');
    }

    # redirection
    public $redirect_def = array(
        'args' => 1,
    );
    public function redirect($pc){
        $pc->res()->redirect('/redirected/'.join('/', $pc->args()));
    }
    public $redirected_def = array(
        'args' => 1,
    );
    public function redirected($pc){
        $pc->stash('template', 'index');
    }

    # a chained action
    public $chained1_def = array(
        'chained'   => '',
        'args'      => 1,
    );
    public function chained1($pc){
    }
    

    # show results
    public function results($pc){
    }
}

?>
