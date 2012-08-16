<?php
namespace Piste\Dispatch\Action;
/*=head1 Name
Piste\Dispatch\Action\Main

=head1 DESCRIPTION
A regular Controller action

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Dispatch/Action.php');

Class Simple extends \Piste\Dispatch\Action {

    public function action_path($object, $action, $defvar){
        # is path explicitally set?
        return isset($object->$defvar) &&
               isset($object->{$defvar}['path'])
                ? $object->{$defvar}['path']
                : $action->name;
    }

    public function specifity_offset() {
        return 0;
    }
}

?>
