<?php
namespace Piste;
/*=head1 Name
Piste\ReflectionClass

=head1 DESCRIPTION
Subclass the standard PHP ReflectionClass Class for extra methods

=head1 DEPENDENCIES

=cut*/
require_once('Piste/ReflectionMethod.php');

class ReflectionClass extends \ReflectionClass {
    
    public function getNonInheritedMethods($filter = null){
        # TODO, use call_user_func_array and func_get_args
        $methods = $filter ? $this->getMethods($filter) :
                             $this->getMethods();
        $not_inherited = array();
        foreach ($methods as $meth){
            if ($meth->getDeclaringClass()->name == $this->name){
                # use custom subclass for ReflectionMethod
                array_push( $not_inherited, new ReflectionMethod($this->name, $meth->name) );
            };
        }
        return $not_inherited;
    }

}
