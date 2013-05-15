<?php
namespace Piste\Context\Response;
/*=head1 Name
Piste\Context\Response\Headers

=head1 DESCRIPTION

*/
Class Headers {
    private $content_type;
    private $status;

    public function redirect($url){
        # This also returns a REDIRECT 302 to browser
        header("Location: $url");
        exit;
    }

    public function content_type($ct = null){
        if (isset($ct)){
            $this->content_type = $ct;
        }
        return $this->content_type;
    }

    public function status($status){
        if (isset($status)){
            switch ($status){
                # TODO for regular apache 404
                # header("HTTP/1.0 404 Not Found");
                # Wheras for fcgi 404 you need to do 
                # header("Status: 404 Not Found");
                case 404:
                    $this->status = "HTTP/1.0 404 Not Found";
                    break;
            }
        }
        return $this->status;
    }

    public function respond(){
        # dump out all the headers
        if ($this->status){
            header($this->status);
        }
        if ($this->content_type){
            header($this->content_type);
        }
    }

}

?>
