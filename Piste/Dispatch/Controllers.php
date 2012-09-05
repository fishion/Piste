<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\Dispatch\Controllers

=head1 DESCRIPTION
Singleton object coordinating Contoller Actions

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Dispatch/ActionSet.php');

Class Controllers {

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


    private $actions = array();
    private $special_actions = array();

    public function register($act){
        if (get_class($act) == 'Piste\Dispatch\Action\Simple' ||
            get_class($act) == 'Piste\Dispatch\Action\Fallback') {
            array_push( $this->actions, $act );
        }
        elseif (get_class($act) == 'Piste\Dispatch\Action\Special'){
            array_push( $this->special_actions, $act );
        }
        else {
            \Logger::fatal("unrecognised action subclass " . get_class($act) . ". Failed to register it");
        }
    }


    public function run($pc) {
        $uripath = $pc->req()->path();
        $action = null;

        // Find defined action (low specifity is good)
        $action = $this->best_match($this->actions, $uripath)->first();

        if ($action){
            # run 'before'-method
            $this->best_match($this->special_actions, $action->namespace_path(), 'before')
                 ->call($pc);
            # run 'auto' methods
            $this->all_matching($this->special_actions, $action->namespace_path(), 'auto')
                 ->call($pc);
            # run main controllers
            $action->call($pc);
            # run 'after'-method
            $this->best_match($this->special_actions, $action->namespace_path(), 'after')
                 ->call($pc);
            # set default template TODO - Controller probably isn't the right place to do this
            if (!$pc->stash('template')){
                $template = $action->namespace_path() . $action->method();
                $pc->stash('template', preg_replace('/^\//', '', $template));
            }
        } else {
            $pc->res()->return_404(true);
        }
    }


    private function best_match($actions, $uripath, $name = null){
        $action = null;
        foreach ($actions as $act){
            if (!$name || $act->method() == $name){
                $action = $act->better_match($uripath, $action);
            }
        }
        return new ActionSet($action);
    }

    private function all_matching($actions, $uripath, $name = null){
        $set = new ActionSet();
        foreach ($actions as $act){
            if ((!$name || $act->method() == $name) &&
                 $act->match($uripath)){
                $set->add($act);
            }
        }
        return $set;
    }



}

?>
