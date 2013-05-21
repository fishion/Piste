<?php
namespace Piste\Context;
/*=head1 Name
Piste\Context\Response

=head1 DESCRIPTION


=head1 DEPENDENCIES
=cut*/
require_once('Piste/Context/Response/Headers.php');

/*=head1 Synopsis

*/
Class Response {
    private $headers;
    private $body = '';

    function __construct(){
        $this->headers = new Response\Headers();
    }

    public function body($body = null){
        if (isset($body)){ $this->body = $body; }
        return $this->body;
    }

    public function respond(){
        $this->headers->respond();
        echo $this->body;
    }

    # pass on header methods
    public function redirect($url){
        $this->headers->redirect($url);
    }
    public function status($status){
        $this->headers->status($status);
    }
    public function content_type($ct){
        $this->headers->content_type($ct);
    }

}

?>
