<?php
namespace Piste;

class ReflectionClass extends \ReflectionClass {
    
    public function getNonInheritedMethods($filter = null){
        $methods = $this->getMethods($filter);
        $not_inherited = array();
        foreach ($methods as $meth){
            if ($meth->getDeclaringClass()->name == $this->name){
                array_push( $not_inherited, $meth );
            };
        }
        return $not_inherited;
    }

}
