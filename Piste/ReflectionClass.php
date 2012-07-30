<?php
namespace Piste;

class ReflectionClass extends \ReflectionClass {
    
    public function getNonInheritedMethods($filter = null){
        # TODO really? I can't pass null to this method?
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
