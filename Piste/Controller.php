<?php
namespace Piste;
/*=head1 Name
Piste\Controller

=head1 DESCRIPTION
Acts as a base class for all Piste Controllers.

=head1 DEPENDENCIES

=cut*/
require_once('Piste/Path.php');
require_once('Piste/ReflectionClass.php');
require_once('Piste/Dispatch/Controllers.php');

abstract Class Controller {

    public final function P_register(){
        $namespace_path = new \Piste\Path(mb_strtolower(preg_replace('/^.*?\\\\Controller\\\\(Root)?/i','',get_class($this) . '/')));
        $reflection     = new \Piste\ReflectionClass($this);
        $methods        = $reflection->getNonInheritedMethods();
        $controllers    = \Piste\Dispatch\Controllers::singleton();
        foreach ($methods as $method){
            $controllers->register($this, $namespace_path, $method);
        }
    }

    public final function P_call_action($method, $args, $pc){
        try {
            $pc->set_args($args);
            $this->$method($pc);
            $pc->set_args();
        } catch(\Exception $e){
            die("Couldn't find action method. That's weird as it must have been regsitered: <br>\n$e");
        };
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
