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

    # test explicitly defined absolute path
    public $absolutepath_def = array(
        'path' => '/absolute/path/in/level1'
    );
    public function absolutepath($pc){
    }

    # test explicitly defined relative path
    public $relativepath_def = array(
        'path' => 'relative/path/in/level1'
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

    # Test specifity
    public function specifity($pc){
        $pc->stash('template', 'index');
    }
    public $morespecifity_def = array(
        'path' => 'specifity/morespecifity',
        'args' => 1,
    );
    public function morespecifity($pc){
        $pc->stash('template', 'index');
    }

    ######
    # chained methods
    ######

    # simple three link chain
    public $chained1_def = array(
        'chained'   => '/',
        'args'      => 1,
    );
    public function chained1($pc){
    }
    public $chained2_def = array(
        'chained'   => 'chained1',
    );
    public function chained2($pc){
    }
    public $chained3_def = array(
        'chained'   => 'chained2',
        'args'      => 2,
        'endchain'  => true,
    );
    public function chained3($pc){
    }

    # chained off globally referenced level2 namespace action
    public $chained4_def = array(
        'chained'   => '/level1/level2/chained2',
        'endchain'  => true,
    );
    public function chained4($pc){
    }

    # chained off relatively referenced level2 namespace action
    public $chained5_def = array(
        'chained'   => 'level2/chained2',
        'endchain'  => true,
    );
    public function chained5($pc){
    }


    # chain of Root namespaced action (deliberately confusingly
    # named the same as a chained action in this namespace)
    public $chained6_def = array(
        'chained'   => '/chained1',
        'endchain'  => true,
    );
    public function chained6($pc){
    }

}

?>
