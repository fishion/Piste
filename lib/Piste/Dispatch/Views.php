<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\Dispatch\Views

=head1 DESCRIPTION
Singleton Object Coordinates Views

=head1 DEPENDENCIES
=cut*/

require_once('Piste/Util/ReflectionClass.php');

Class Views {
    # This is a singleton object
    private static $singleton;
    private function __construct(){
        # private constructor on singleton
    }
    # use this method to get singleton object instance
    public static function singleton(){
        if (!isset(self::$singleton)){
            $c = __CLASS__;
            self::$singleton = new $c;
        }
        return self::$singleton;
    }


    private $views = array();

    public function register(\Piste\View $view){
        # Allow people to use fully qualified $class or strip
        # off <Appname>\View
        $class = get_class($view);
        $path = preg_replace('/^.*?\\\\View\\\\/','',$class);
        $this->views[$path] = $this->views[$class] = $view;
    }


    public function run(\Piste\Context $pc) {
        $view = $pc->view()->classname();
        if ($view){
            if (!isset($this->views[$view])){
                throw new \Exception("View '$view' not installed");
            }
            $view = new $this->views[$view]($pc);
            switch ($pc->view()->get_status()){
                case 404:
                    $view->render_404($pc);
                    break;
                default:
                    $view->render($pc);
            }
        } else {
            throw new \Exception("No view defined. Consider setting a default in your application.");
        }
        $pc->response()->respond();
    }

}

?>
