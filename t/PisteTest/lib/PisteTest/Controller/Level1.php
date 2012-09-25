<?php
namespace PisteTest\Controller;
require_once('PisteTest/Controller.php');
/*=head1 Name
PisteTest\Controller\Level1

*/
Class Level1 extends \PisteTest\Controller {

    # special methods
    protected function before($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    protected function auto($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    protected function after($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }

    # test that 'level1/' redirects as expected to index
    public function index($pc) {
        $this->track_execution_stack($pc, __METHOD__);
    }

    # test explicitly defined absolute path
    public $absolutepath_def = array(
        'path' => '/absolute/path/in/level1'
    );
    public function absolutepath($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }

    # test explicitly defined relative path
    public $relativepath_def = array(
        'path' => 'relative/path/in/level1'
    );
    public function relativepath($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    
    # url param testing
    public function nofixedargs($pc){
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }
    public $fixedargs0_def = array(
        'args' => 0,
    );
    public function fixedargs0($pc){
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }
    public $fixedargs1_def = array(
        'args' => 1,
    );
    public function fixedargs1($pc){
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }

    # Test specifity
    public function specifity($pc){
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }
    public $morespecifity_def = array(
        'path' => 'specifity/morespecifity',
        'args' => 1,
    );
    public function morespecifity($pc){
        $this->track_execution_stack($pc, __METHOD__, 'index');
    }

    # chained methods
    public $chained1_def = array(
        'chained'   => '/',
        'args'      => 1,
    );
    public function chained1($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    public $chained2_def = array(
        'chained'   => 'chained1',
    );
    public function chained2($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    public $chained3_def = array(
        'chained'   => 'chained2',
        'args'      => 2,
        'endchain'  => true,
    );
    public function chained3($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }

}

?>
