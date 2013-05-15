<?php
namespace Piste\Util;
/*=head1 Name
Piste\Util\ReflectionMethod

=head1 DESCRIPTION
Subclass the standard PHP ReflectionMethod Class for extra methods

=head1 DEPENDENCIES

=cut*/

class ReflectionMethod extends \ReflectionMethod {
    
    public function getMetaData(){
        $comment = $this->getDocComment();
        $def = $comment
                ? json_decode(
                    preg_replace('/.*({.*}).*/', '$1',
                        preg_replace('/\n\s*\*?\s*/', " ",
                            $comment
                        )
                    )
                    , true
                  )
                : null;
        return $def ? $def : array();
    }
}
