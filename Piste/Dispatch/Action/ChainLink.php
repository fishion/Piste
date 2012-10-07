<?php
namespace Piste\Dispatch\Action;
/*=head1 Name
Piste\Dispatch\Action\ChainLink

=head1 DESCRIPTION
A chained Controller action

=head1 DEPENDENCIES
=cut*/
require_once('Piste/Dispatch/Action.php');
require_once('Piste/Dispatch/Action/Chained.php');
require_once('Logger.php');

Class ChainLink extends \Piste\Dispatch\Action {
    private $chain = array(); # list of all links back to the start

    public function action_path(){
        if (!isset($this->action_path)){
            $ap = isset($this->def['path'])
                    ? $this->def['path']
                    : $this->method_name;
            # Only start of chain actions can be local/global
            if ($this->is_start_of_chain() &&
                !preg_match('/^\//', $ap)){
                # local, Make it global
                $ap = $this->namespace_path . $ap;
            }
            elseif (!$this->is_start_of_chain()) {
                # don't want a leading /
                $ap = preg_replace('/^\//','',$ap);
            }
            $this->action_path = $ap;
        }
        return $this->action_path;
    }

    public function pathre(){
        if (!isset($this->pathre)){
            $this->pathre_base()
                 ->pathre_params();
        }
        return $this->pathre;
    }

    public function args_match($args){
        $this->args = split('/',$args);
        return ($this->arg_def() === false ||
                $this->arg_def() == count($this->args) );
    }

    # We need specifity of chainline to determine the best
    # next link to chain off when joining chains
    public function specifity(){
        if (!isset($this->specifity)){
            # the specifity of the chained action is determined
            # only by the length of the namespace
            # TODO - is this true? even if very specific path attribute? not sure.
            $this->specifity = (1 / count(explode('/', $this->namespace_path)));
        }
        return $this->specifity;
    }

    # gets the 'chained' attribute as set in the method definition
    public function chainedto(){
        return $this->def['chained'];
    }
    public function chainscope(){
        $bits = split('/', $this->chainedto());
        array_pop($bits);
        if (!count($bits)){ return false; }
        return '/' . join('/', $bits) . '/';
    }
    public function chainmethod(){
        return array_pop(split('/', $this->chainedto()));
    }

    public function is_start_of_chain(){
        return ($this->chainedto() == '' || $this->chainedto() == '/');
    }

    public function is_end_of_chain(){
        return (isset($this->def['endchain']) && $this->def['endchain']);
    }



/* Recursive function to try to find a route to 
   the beginning of a chain */
    public function attach(&$links, &$actions = null){
        # have we already checked this?
        if ($this->chain === false){
            return $this->chain;
        } elseif (count($this->chain)){
            return $this->chain;
        }
        # start of chains are easy
        if ($this->is_start_of_chain()){
            array_push($this->chain, $this);
            return $this->chain;
        }
        # find next link
        $next_link = null;
        foreach ($links as $link) {
            $next_link = $this->better_parent($link, $next_link);
        }

        # Did we find one? Yay. Follow it back to the start
        if (isset($next_link)
            && $this->chain = $next_link->attach($links)){
            # success - linked all the way to a chain start
            array_push($this->chain, $this);
            if ($this->is_end_of_chain()){
                \Logger::debug("Found start of chain!");
                # Add a chained action to actions ref
                array_push($actions, new Chained($this->chain));
            }
        } else {
            # failed - no next_link or chain broke somewhere
            \Logger::warn("Failed to follow chain to start");
            $this->chain = false;
        }
        return $this->chain;
    }

    private function better_parent($new, $old = null){
        if ($this->chainscope() &&
            # is it a namespaced chain? If so, must match exactly
            $this->chainscope() == $new->namespace_path() &&
            $this->chainmethod() == $new->method_name()){
            return $new;
        } elseif (
            # is this one better than what we already have?
            !$this->chainscope() &&
            (!$old || $new->specifity() < $old->specifity()) &&
            # does namespace fit instide this one?
            preg_match('/'.preg_quote($new->namespace_path(), '/').'/',
                       $this->namespace_path()) &&
            $this->chainmethod() == $new->method_name()){
            return $new;
        }
        return $old;
    }
}

?>
