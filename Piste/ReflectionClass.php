<?php
namespace Piste;

class ReflectionClass extends \ReflectionClass {
    
    public function getNonInheritedMethods($filter = null){
        # Anoyingly, can't pass null variable to this method to mean "no filter"?
        $methods = $filter ? $this->getMethods($filter) :
                             $this->getMethods();
        $not_inherited = array();
        foreach ($methods as $meth){
            if ($meth->getDeclaringClass()->name == $this->name){
                array_push( $not_inherited, $meth );
            };
        }
        return $not_inherited;
    }

}
