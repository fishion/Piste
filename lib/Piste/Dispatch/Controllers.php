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


    private $actions         = array();
    private $special_actions = array();

    private $chain_links     = array();

    public function register(Action $act){
        if (get_class($act) == 'Piste\Dispatch\Action' ||
            get_class($act) == 'Piste\Dispatch\Action\Fallback') {
            array_push( $this->actions, $act );
            \Logger::collect("Registered action path " . $act->action_path() . " to ". $act->object_class() ."\\" . $act->method_name() . "\()");
        }
        elseif (get_class($act) == 'Piste\Dispatch\Action\Special'){
            array_push( $this->special_actions, $act );
            \Logger::collect("Registered special path " . $act->action_path() . " to ". $act->object_class() ."\\" . $act->method_name() . "\()");
        }
        elseif (get_class($act) == 'Piste\Dispatch\Action\ChainLink'){
            array_push( $this->chain_links, $act );
            \Logger::collect("Found chain link " . $act->action_path() . " in ". $act->object_class() ."\\" . $act->method_name() . "\()");
        }
        else {
            \Logger::fatal("unrecognised action subclass " . get_class($act) . ". Failed to register it");
        }
    }

    public function link_chained(){
        \Logger::debug('linking '.count($this->chain_links).' chained method(s)');
        foreach ($this->chain_links as $act){
            if ($act->is_end_of_chain()){
                $act->attach($this->chain_links, $this->actions);
            }
        }
        # TODO alert over unused pieces
    }

    public function run(\Piste\Context $pc) {
        $uripath = $pc->req()->path();
        $action = null;

        // Find defined action (low specifity is good)
        $action = $this->best_match($this->actions, $uripath)->first();

        if ($action){
            # set which action was run in the context object.
            # set it early so it can be overwritten in a controller if you really want to lie to the view.
            $pc->action($action);

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

        } else {
            # well, no action found so do nothing View should figure it out.
        }
    }


    private function best_match(array $actions, $uripath, $name = null){
        $action = null;
        foreach ($actions as $act){
            \Logger::info(($name ? '(' . $name . ') ' : '') . 'Matching ' . $uripath . ' against ' . $act->pathre());
            if (!$name || $act->method_name() == $name){
                $action = $act->better_match($uripath, $action);
            }
        }
        return new ActionSet($action);
    }

    private function all_matching(array $actions, $uripath, $name = null){
        $set = new ActionSet();
        foreach ($actions as $act){
            if ((!$name || $act->method_name() == $name) &&
                 $act->match($uripath)){
                $set->add($act);
            }
        }
        return $set;
    }

}

?>
