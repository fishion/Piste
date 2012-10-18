<?php
namespace Piste\Dispatch\Action;
/*=head1 Name
Piste\Dispatch\Action\Chained

=head1 DESCRIPTION
A chained Controller action

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Dispatch/Action.php');
require_once('Logger.php');

Class Chained extends \Piste\Dispatch\Action {

    private $chain = array();

    public function __construct($chain){
        $this->chain = $chain;
        # some of the properties just use the characteristics
        # of the last link in the chain
        $this->namespace_path = end($chain)->namespace_path();
        $this->method_name    = end($chain)->method_name();
        \Logger::collect("Registered path " . $this->action_path() . " to chained actions");
    }

    public function action_path(){
        if (!isset($this->action_path)){
            $ap_parts = array();
            foreach ($this->chain as $link){
                $ap_part = $link->action_path();
                $ap_part .= ($link->arg_def() === false) ? '/*x*' : '/*';
                array_push($ap_parts, $ap_part);
            };
            $this->action_path = join('', $ap_parts);
        }
        return $this->action_path;
    }

    public function pathre(){
        if (!isset($this->pathre)){
            $re_parts = array();
            foreach ($this->chain as $link){
                array_push($re_parts, $link->pathre());
            }
            $this->pathre = join('', $re_parts);
            $this->pathre_start()
                 ->pathre_end();
        }
        return $this->pathre;
    }

    # override call method to match
    # to capture params properly
    public function match($uripath){
        $match = preg_match('/'.$this->pathre().'/', $uripath, $remain);
        array_shift($remain); # don't want first index
        foreach ($this->chain as $link){
            if ($match && $link->arg_def() !== 0){
                $match = $link->args_match(array_shift($remain));
            }
        }
        if ($match){
            \Logger::info('MATCHED chained actions ' . $this->pathre());
            return true;
        }
        return false;
    }

    # override call method to call all actions in set
    public function call($pc){
        foreach ($this->chain as $link){
            $link->call($pc);
        }
    }

}

?>
