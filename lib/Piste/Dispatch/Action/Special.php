<?php
namespace Piste\Dispatch\Action;
/*=head1 Name
Piste\Dispatch\Action\Special

=head1 DESCRIPTION
A regular Controller action

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Dispatch/Action.php');

Class Special extends \Piste\Dispatch\Action {

    public function action_path(){
        if (!isset($this->action_path)){
            $this->action_path = $this->namespace_path;
        }
        return $this->action_path;
    }

    public function pathre(){
        if (!isset($this->pathre)){
            $this->pathre_base()
                 ->pathre_start();
        }
        return $this->pathre;
    }

    protected function arg_def(){
        return false;
    }

    public function specifity(){
        if (!isset($this->specifity)){
            # generic special classes always less specific than bespoke simple ones
            $this->specifity = (1 + parent::specifity());
        }
        return $this->specifity;
    }

}

?>
