<?php
namespace Piste\Util;
/*=head1 Name
Piste\Util\ReflectionClass

=head1 DESCRIPTION
Subclass the standard PHP ReflectionClass Class for extra methods

=head1 DEPENDENCIES

=cut*/
require_once('Piste/Util/ReflectionMethod.php');

class ReflectionClass extends \ReflectionClass {
    
    public function getNonInheritedMethods(){
        $methods = call_user_func_array(
                    array($this,'getMethods'),
                    func_get_args()
                   );
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
