<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\Dispatch\Controller

=head1 DESCRIPTION
Acts as a base class for all Dispatch Controllers.

=cut*/
interface Controller {

    public function specifity();
    public function namespace_path();
    public function method();
    public function better_match($uripath, $that);
    public function match($uripath);
    public function call($pc);

}

?>
