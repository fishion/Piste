<?php
namespace Piste;
/*=head1 Name
Piste\Response

=head1 DESCRIPTION

*/
Class Response {
    private $body = 'No content set';

    public function body($body = null){
        if ($body){ $this->body = $body; }
        return $this->body;
    }

    public function respond(){
        echo $this->body;
    }

}

?>
