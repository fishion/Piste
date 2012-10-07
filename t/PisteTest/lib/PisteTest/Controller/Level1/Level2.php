<?php
namespace PisteTest\Controller\Level1;
require_once('PisteTest/Controller.php');
/*=head1 Name
PisteTest\Controller\Level1\Level1

*/
Class Level2 extends \PisteTest\Controller {

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
        'path' => '/absolute/path/in/level2'
    );
    public function absolutepath($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }

    # test explicitly defined relative path
    public $relativepath_def = array(
        'path' => 'relative/path/in/level2'
    );
    public function relativepath($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
    
    #####
    # chained methods
    #####

    # This one deliberately named the same as one in Level1 to ensure
    # hierarchy is respected.
    public $chained2_def = array(
        'chained'   => 'chained1',
    );
    public function chained2($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }

    public $chained2_3_def = array(
        'chained'   => 'chained2',
        'endchain'  => true,
    );
    public function chained2_3($pc){
        $this->track_execution_stack($pc, __METHOD__);
    }
}

?>
