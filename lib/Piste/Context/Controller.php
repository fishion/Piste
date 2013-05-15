<?php
namespace Piste\Context;
/*=head1 Name
Piste\Context\Controller

=head1 DESCRIPTION

=head1 DEPENDENCIES
=cut*/

require_once('Logger.php');

Class Controller {
    private $attached = true;

    public function detatch(){
        \Logger::info('setting controller to detatch from actions');
        $this->attached = false;
    }
    public function attatched(){
        return $this->attached;
    }

}

?>
