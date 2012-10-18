<?php
namespace Piste\Dispatch\Action;
/*=head1 Name
Piste\Dispatch\Action\Fallback

=head1 DESCRIPTION
A fallback Controller action

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Dispatch/Action/Special.php');

Class Fallback extends Special {

    public function pathre(){
        if (!isset($this->pathre)){
            $this->pathre_base()
                 ->pathre_fallback_params()
                 ->pathre_start();
        }
        return $this->pathre;
    }

    private function pathre_fallback_params(){
        $this->pathre .= '(.*)';
        return $this;
    }

}

?>
