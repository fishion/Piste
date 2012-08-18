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

    protected $specifity_offset = 1;
    protected $capture_args = false;

    public function action_path($object, $action, $namespace_path, $defvar){
        return $namespace_path;
    }

    public function arg_def($object, $defvar){
        return false;
    }

}

?>
