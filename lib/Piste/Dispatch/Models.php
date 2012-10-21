<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\Dispatch\Models

=head1 DESCRIPTION
Singleton Object Coordinates Models

=head1 DEPENDENCIES
=cut*/

require_once('Piste/ReflectionClass.php');

Class Models {
    # This is a singleton object
    private static $singleton;
    private function __construct(){
        # private onstructor on singleton
    }
    # use this method to get singleton object instance
    public static function singleton(){
        if (!isset(self::$singleton)){
            $c = __CLASS__;
            self::$singleton = new $c;
        }
        return self::$singleton;
    }

    private $models = array();

    public function register($class){
        #ensure Model class ISA Piste\Model
        $reflection = new \Piste\ReflectionClass($class);
        if (!$reflection->isSubclassOf('\Piste\Model')){
            throw new \Exception("$class is not a \Piste\Model subclass");
        }
        # Allow people to use fully qualified $class or strip
        # off <Appname>\Mosel
        $path = preg_replace('/^.*?\\\\Model\\\\/','',$class);
        $this->models[$path] = $this->models[$class] = $class;
    }


    public function get_model($model) {
        if (!isset($this->models[$model])){
            throw new \Exception("Model '$model' not installed");
        }
        return new $this->models[$model]();
    }

}

?>
