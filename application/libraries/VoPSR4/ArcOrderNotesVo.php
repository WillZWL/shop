<?php

class ArcOrderNotesVo extends \BaseVo
{
    private $so_no;
    private $type = 'O';
    private $note;

    protected $primary_key = ['so_no', 'type', 'create_on'];
    protected $increment_field = '';

    public function setSoNo($so_no)
    {
        if ($so_no !== null) {
            $this->so_no = $so_no;
        }
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setType($type)
    {
        if ($type !== null) {
            $this->type = $type;
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function setNote($note)
    {
        if ($note !== null) {
            $this->note = $note;
        }
    }

    public function getNote()
    {
        return $this->note;
    }

}
