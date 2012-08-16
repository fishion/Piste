<?php
namespace Piste\Dispatch\Action;
/*=head1 Name
Piste\Dispatch\Action\Main

=head1 DESCRIPTION
A regular Controller action

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Dispatch/Action.php');

Class Special extends \Piste\Dispatch\Action {

    public function action_path($object, $action, $defvar){
        return '';
    }

    public function specifity_offset(){
        return 1;
    }

}

?>
