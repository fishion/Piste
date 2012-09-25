<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\Dispatch\Action

=head1 DESCRIPTION
Acts as a base class for all Dispatch Controller Actions
=head1 DEPENDENCIES

=cut*/
require_once('Logger.php');

class Action {

    protected $object;
    protected $namespace_path;
    protected $method;
    protected $method_name;
    protected $def;
    protected $action_path;
    protected $arg_def;
    protected $specifity;
    protected $pathre;
    protected $args;

    public function __construct($object, $namespace_path, $method, $def){
        $this->object         = $object;
        $this->namespace_path = $namespace_path;
        $this->method         = $method;
        $this->method_name    = $method->name;
        $this->def            = $def;
    }

    # accessors
    public function namespace_path(){
        return $this->namespace_path;
    }
    public function object_class(){
        return get_class($this->object);
    }
    public function method_name(){
        return $this->method_name;
    }
    public function action_path(){
        if (!isset($this->action_path)){
            # is path explicitally set?
            $ap = isset($this->def['path'])
                    ? $this->def['path']
                    : $this->method_name;
            # is that global or local?
            if (!preg_match('/^\//', $ap)){
                # local, Make it global
                $ap = $this->namespace_path . $ap;
            }
            $this->action_path = $ap;
        }
        return $this->action_path;
    }
    public final function pathre_base(){
        $this->pathre = preg_quote($this->action_path(), '/');
        return $this;
    }
    public final function pathre_params(){
        $this->pathre .= '\/?(.+)?';
        return $this;
    }
    public final function pathre_start(){
        $this->pathre = '^' . $this->pathre;
        return $this;
    }
    public final function pathre_end(){
        $this->pathre .= '$';
        return $this;
    }
    
    public function pathre(){
        if (!isset($this->pathre)){
            $this->pathre_base()
                 ->pathre_params()
                 ->pathre_start()
                 ->pathre_end();
        }
        return $this->pathre;
    }

    public function arg_def(){
        if (!isset($this->arg_def)){
            $this->arg_def = isset($this->def['args']) &&
                             is_int($this->def['args'])
                                ? $this->def['args']
                                : false;
        }
        return $this->arg_def;
    }

    public function specifity(){
        if (!isset($this->specifity)){
            $this->specifity = (1 / count(explode('/', $this->action_path())));
        }
        return $this->specifity;
    }

    public function default_template(){
        return preg_replace(
                '/^\//', '',
                $this->namespace_path() . $this->method_name()
               );
    }

    # methods
    public function better_match($uripath, $that){
        if ($that && $this->specifity() > $that->specifity()){
            return $that; # even if this matches, that is better
        }
        return $this->match($uripath) ? $this : $that;
    }

    public function match($uripath){
        $match = preg_match('/'.$this->pathre().'/', $uripath, $remain);
        # remain[1] is not always set as we don't capture extra params
        # for special methods (before, after, auto)
        $this->args = isset($remain[1]) ? split('/',$remain[1]) : array();
        if ($match &&
            ( $this->arg_def() === false
              || $this->arg_def() == count($this->args) )
           ){
            \Logger::debug('MATCHED!!!');
            return true;
        }
        return false;
    }

    public function call($pc){
        $this->object->P_call_action($this->method_name, $this->args, $pc);
    }

}

?>
