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

Class Controllers {

    public function run($pc) {
        $uripath = $pc->req()->path();
        $actions = \Piste\Dispatch\ActionCollection::singleton();
        $action = null;

        // Find defined action (low specifity is good)
        $action = $actions->best_match($uripath);

        if ($action){
            # run 'before'-method
            $actions->run_most_specific($action->namespace_path(), 'before', $pc);
            # run 'auto' methods
            $actions->run_all_matching($action->namespace_path(), 'auto', $pc);
            # run main controllers
            $action->call($pc);
            # run 'after'-method
            $actions->run_most_specific($action->namespace_path(), 'after', $pc);
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
