<?php
namespace Piste\Dispatch;
/*=head1 Name
Piste\Dispatch\ActionSet

=head1 DESCRIPTION
A clas to collect and run actions

=cut*/
class ActionSet {

    private $set = array();

    public function __construct(Action $act = null){
        if ($act){
            $this->add($act);
        }
    }

    public function add(Action $act){
        array_push($this->set, $act);
    }

    public function size(){
        return count($this->set);
    }

    public function first(){
        return count($this->set) ? $this->set[0] : null;
    }

    public function call(\Piste\Context $pc){
        # put in order of specifity, then run.
        usort($this->set, array(__CLASS__, 'specifity_sort'));
        foreach ($this->set as $act){
            $pc->controller()->attatched() && $act->call($pc);
        }
    }

    static function specifity_sort(Action $a, Action $b){
        $a1 = $a->specifity();
        $b1 = $b->specifity();
        if ($a1 == $b1) {
            return 0;
        }
        return ($a1 < $b1) ? 1 : -1;
    }

}

?>
