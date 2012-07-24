<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\Dispatch\Views

=head1 DESCRIPTION
Coordinates Views

=head1 DEPENDENCIES
=cut*/

Class Views {
    private $views = array();


    public function register($class){
        $path = preg_replace('/^.*?\\\\View\\\\/','',$class);
        #TODO: ensure view class ISA Piste\View
        # Allow people to use fully qualified $class or strip
        # off <Appname>\View
        $this->views[$path] = $this->views[$class] = $class;
    }


    public function run($pc) {
        $view = $pc->response()->view();
        if ($view && !isset($this->views[$view])){
            throw new \Exception("View '$view' not installed");
        } elseif ($view){
            $view = new $this->views[$view]($pc);
            if ($pc->res()->return_404()){
                $view->render_404($pc);
            } else {
                $view->render($pc);
            }
        }
        $pc->response()->respond();
    }

}

?>
