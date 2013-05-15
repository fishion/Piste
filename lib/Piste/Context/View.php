<?php
namespace Piste\Context;
/*=head1 Name
Piste\Context\View

=head1 DESCRIPTION

=head1 DEPENDENCIES
=cut*/

require_once('Logger.php');

Class View {
    private $status = 200;

    public function show_404(){
        \Logger::info('setting view to show 404 page');
        $this->status = 404;
    }

    public function get_status(){
        return $this->status;
    }
}

?>
