<?php
namespace Piste\Dispatch\Action;
/*=head1 Name
Piste\Dispatch\Action\Simple

=head1 DESCRIPTION
A regular Controller action

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Dispatch/Action.php');

Class Simple extends \Piste\Dispatch\Action {

    public function action_path($object, $action, $namespace_path, $defvar){
        # is path explicitally set?
        $ap = isset($object->$defvar) &&
              isset($object->{$defvar}['path'])
                ? $object->{$defvar}['path']
                : $action->name;
        # is that global or local?
        if (!preg_match('/^\//', $ap)){
            # local, Make it global
            $ap = $namespace_path . $ap;
        }
        return $ap;
    }
    public function arg_def($object, $defvar){
        return isset($object->$defvar) &&
               isset($object->{$defvar}['args']) &&
               is_int($object->{$defvar}['args'])
                  ? $object->{$defvar}['args']
                  : false;
    }

}

?>
