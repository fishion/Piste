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

    public function action_path($object, $namespace_path, $method, $def){
        # is path explicitally set?
        $ap = isset($def['path'])
                ? $def['path']
                : $method->name;
        # is that global or local?
        if (!preg_match('/^\//', $ap)){
            # local, Make it global
            $ap = $namespace_path . $ap;
        }
        return $ap;
    }
    public function arg_def($object, $def){
        return isset($def['args']) &&
               is_int($def['args'])
                  ? $def['args']
                  : false;
    }

}

?>
