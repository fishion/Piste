<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\ControllerManager

=head1 DESCRIPTION
Coordinates Contoller Actions

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Path.php');
require_once('Piste/Controller.php');
require_once('Piste/Dispatch/ActionCollection.php');
require_once('Piste/ReflectionClass.php');

Class Controllers {
    private $actions;

    public function __construct(){
        $this->actions = new ActionCollection();
    }

    public function register($class){
        $object = new $class();

        # ensure that it's a Piste Controller
        if (!($object instanceof \Piste\Controller)){
            error_log("$class is not a Piste Controller. Ignoring.");
            return;
        }

        # Know our namespace
        $namespace_path = new \Piste\Path(mb_strtolower(preg_replace('/^.*?\\\\Controller\\\\(Root)?/i','',$class)));

        # get all the methods
        $reflection     = new \Piste\ReflectionClass($class);
        $public         = $reflection->getNonInheritedMethods(\ReflectionMethod::IS_PUBLIC);
        $protected      = $reflection->getNonInheritedMethods(\ReflectionMethod::IS_PROTECTED);

        # register main actions
        foreach ($public as $action){
            $this->actions->register($object, $namespace_path, $action);
        }

        # register special actions
        foreach ($protected as $action){
            $this->actions->register_special($object, $namespace_path, $action);
        }
    }


    public function run($pc) {
        $uripath = $pc->req()->path();
        $action = null;

        // Find defined action (low specifity is good)
        $action = $this->actions->best_match($uripath);

        if ($action){
            # run 'before'-method
            $this->actions->run_most_specific($action->namespace_path(), 'before', $pc);
            # run 'auto' methods
            $this->actions->run_all_matching($action->namespace_path(), 'auto', $pc);
            # run main controllers
            $action->call($pc);
            # run 'after'-method
            $this->actions->run_most_specific($action->namespace_path(), 'after', $pc);
            # set default template TODO - Controller probably isn't the right place to do this
            if (!$pc->stash('template')){
                $template = $action->namespace_path() . DIRECTORY_SEPARATOR . $action->method();
                $pc->stash('template', preg_replace('/^\//', '', $template));
            }
        } else {
            $pc->res()->return_404(true);
        }
    }

}

?>
