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
    public final function namespace_path(){
        return $this->namespace_path;
    }
    public final function object_class(){
        return get_class($this->object);
    }
    public final function method_name(){
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
                $ap = $this->namespace_path->extend($ap);
            }
            $this->action_path = $ap;
        }
        return $this->action_path;
    }
    protected final function pathre_base(){
        $this->pathre = preg_quote($this->action_path(), '/');
        return $this;
    }
    protected final function pathre_params(){
        if ($this->arg_def() === false){
            $this->pathre .= '(\/.+)?';
        } elseif (is_int($this->arg_def()) && $this->arg_def() > 0) {
            $this->pathre .= '((?:\/[^\/]+){'.$this->arg_def().'})';
        }
        return $this;
    }
    protected final function pathre_start(){
        $this->pathre = '^' . $this->pathre;
        return $this;
    }
    protected final function pathre_end(){
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

    protected function arg_def(){
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
            $this->specifity = (1 / count(explode('/', $this->namespace_path())));
        }
        return $this->specifity;
    }

    public final function default_template(){
        return preg_replace(
                '/^\//', '',
                $this->namespace_path()->extend($this->method_name())
               );
    }

    # methods
    public final function better_match($uripath, $that){
        if ($that && $this->specifity() > $that->specifity()){
            return $that; # even if this matches, that is better
        }
        return $this->match($uripath) ? $this : $that;
    }

    public function match($uripath){
        if ($this->match_http_method() &&
            preg_match('/'.$this->pathre().'/', $uripath, $remain)){
            \Logger::info('MATCHED ' . $this->pathre());
            # remain[1] is not always set as we don't capture extra params
            # for special methods (before, after, auto)
            # or when zero args has been specified
            $args = isset($remain[1]) ?
                preg_replace('/^\//', '', $remain[1])
                : '';
            $this->args = $args != '' ? split('/',$args) : array();
            return true;
        }
        return false;
    }

    public function match_http_method(){
        if (isset($this->def['http_method']) &&
            strtoupper($this->def['http_method']) != strtoupper($_SERVER['REQUEST_METHOD']) ){
            return false;
        }
        return true;
    }

    public function call($pc){
        if (!$pc->controller()->attatched()){
            return; # we're detatched
        }
        # track execution_stack
        array_push($pc->execution_stack, preg_replace('/^.*\\\Controller\\\/','',$this->method->class . '::' . $this->method_name));
        array_push($pc->execution_stack, $this->args);
        # make the call
        $this->object->P_call_action($this->method_name, $this->args, $pc);
    }

}

?>
