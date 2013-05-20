<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\Dispatch\Models

=head1 DESCRIPTION
Singleton Object Coordinates Models

=head1 DEPENDENCIES
=cut*/

require_once('Piste/Util/ReflectionClass.php');

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

    public function register(\Piste\Model $model){
        # Allow people to use fully qualified $class or strip
        # off <Appname>\Mosel
        $class = get_class($model);
        $path = preg_replace('/^.*?\\\\Model\\\\/','',$class);
        $this->models[$path] = $this->models[$class] = $model;
    }


    public function get_model($class) {
        if (!isset($this->models[$class])){
            throw new \Exception("Model '$class' not installed");
        }
        return $this->models[$class];
    }

}

?>
