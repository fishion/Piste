<?php
namespace Piste;
/*=head1 Name
Piste\Controller

=head1 DESCRIPTION
Acts as a base class for all Piste Controllers.

=head1 DEPENDENCIES

=cut*/
require_once('Logger.php');
require_once('Piste/Util/Path.php');
require_once('Piste/Util/ReflectionClass.php');
require_once('Piste/Dispatch/Controllers.php');
require_once('Piste/Dispatch/Action.php');
require_once('Piste/Dispatch/Action/ChainLink.php');
require_once('Piste/Dispatch/Action/Fallback.php');
require_once('Piste/Dispatch/Action/Special.php');

abstract Class Controller {

    public final function P_register(){
        $namespace_path = new \Piste\Util\Path(mb_strtolower(preg_replace('/^.*?\\\\Controller\\\\(Root)?/i','',get_class($this))));
        $namespace_path->is_dir(); # make sure we know it should have trailing slash usually
        $reflection     = new \Piste\Util\ReflectionClass($this);
        $methods        = $reflection->getNonInheritedMethods();
        $controllers    = \Piste\Dispatch\Controllers::singleton();
        foreach ($methods as $method){
            $def = $method->getMetaData();

            if ($method->isPublic() && isset($def['chained'])){
                $action_class = 'Piste\Dispatch\Action\Chainlink';
            }
            elseif ($method->isPublic()) {
                $action_class = 'Piste\Dispatch\Action';
            }
            elseif ($method->isProtected() && $method->name == 'fallback'){
                $action_class = 'Piste\Dispatch\Action\Fallback';
            }
            elseif ($method->isProtected() && array_search($method->name, array('before', 'after', 'auto')) !== false ){
                $action_class = 'Piste\Dispatch\Action\Special';
            }
            elseif ($method->isProtected()){
                \Logger::fatal("Protected method '$method->name' not allowed in contoller class. So there.");
                continue; # just in case fatal doesn't do what we expect
            }
            else {
                # private method. Do nothing
                continue;
            }
            $controllers->register(new $action_class(
                                            $this,    
                                            $namespace_path,
                                            $method,
                                            $def
                                        ) );
        }
    }

    public final function P_call_action($method, $args, $pc){
        $pc->request()->set_args($args);
        $this->$method($pc);
        $pc->request()->set_args();
    }

/*
begin
The begin action is called at the beginning of every request involving this namespace directly,
before other matching actions are called. It can be used to set up variables/data for this
particular part of your app. A single begin action is called, its always the one most relevant to
the current namespace.
is called once when
http://localhost:3000/bucket/(anything)?
is visited.

end
Like begin, this action is always called for the namespace it is in, after every other action has
finished. It is commonly used to forward processing to the View component. A single end action is
called, its always the one most relevant to the current namespace.
is called once after any actions when
http://localhost:3000/bucket/(anything)?
is visited.

auto
Lastly, the auto action is magic in that every auto action in the chain of paths up to and including
the ending namespace, will be called. (In contrast, only one of the begin/end/default actions will
be called, the relevant one).
will both be called when visiting
http://localhost:3000/bucket/(anything)?

fallback
The fallback action will be called, if no other matching action is found. If you don't have one of
these in your namespace, or any sub part of your namespace, you'll get an error page instead. If you
want to find out where it was the user was trying to go, you can look in the request object using
"$c->req->path".
works for all unknown URLs, in this controller namespace, or every one if put directly into
MyApp.pm.
*/

}

?>
