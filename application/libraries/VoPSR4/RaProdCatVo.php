<?php
class RaProdCatVo extends \BaseVo
{
    private $id;
    private $ss_cat_id;
    private $rcm_ss_cat_id_1 = '0';
    private $rcm_ss_cat_id_2 = '0';
    private $rcm_ss_cat_id_3 = '0';
    private $rcm_ss_cat_id_4 = '0';
    private $rcm_ss_cat_id_5 = '0';
    private $rcm_ss_cat_id_6 = '0';
    private $rcm_ss_cat_id_7 = '0';
    private $rcm_ss_cat_id_8 = '0';
    private $warranty_cat = '0';
    private $status = '1';


    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSsCatId($ss_cat_id)
    {
        if ($ss_cat_id !== null) {
            $this->ss_cat_id = $ss_cat_id;
        }
    }

    public function getSsCatId()
    {
        return $this->ss_cat_id;
    }

    public function setRcmSsCatId1($rcm_ss_cat_id_1)
    {
        if ($rcm_ss_cat_id_1 !== null) {
            $this->rcm_ss_cat_id_1 = $rcm_ss_cat_id_1;
        }
    }

    public function getRcmSsCatId1()
    {
        return $this->rcm_ss_cat_id_1;
    }

    public function setRcmSsCatId2($rcm_ss_cat_id_2)
    {
        if ($rcm_ss_cat_id_2 !== null) {
            $this->rcm_ss_cat_id_2 = $rcm_ss_cat_id_2;
        }
    }

    public function getRcmSsCatId2()
    {
        return $this->rcm_ss_cat_id_2;
    }

    public function setRcmSsCatId3($rcm_ss_cat_id_3)
    {
        if ($rcm_ss_cat_id_3 !== null) {
            $this->rcm_ss_cat_id_3 = $rcm_ss_cat_id_3;
        }
    }

    public function getRcmSsCatId3()
    {
        return $this->rcm_ss_cat_id_3;
    }

    public function setRcmSsCatId4($rcm_ss_cat_id_4)
    {
        if ($rcm_ss_cat_id_4 !== null) {
            $this->rcm_ss_cat_id_4 = $rcm_ss_cat_id_4;
        }
    }

    public function getRcmSsCatId4()
    {
        return $this->rcm_ss_cat_id_4;
    }

    public function setRcmSsCatId5($rcm_ss_cat_id_5)
    {
        if ($rcm_ss_cat_id_5 !== null) {
            $this->rcm_ss_cat_id_5 = $rcm_ss_cat_id_5;
        }
    }

    public function getRcmSsCatId5()
    {
        return $this->rcm_ss_cat_id_5;
    }

    public function setRcmSsCatId6($rcm_ss_cat_id_6)
    {
        if ($rcm_ss_cat_id_6 !== null) {
            $this->rcm_ss_cat_id_6 = $rcm_ss_cat_id_6;
        }
    }

    public function getRcmSsCatId6()
    {
        return $this->rcm_ss_cat_id_6;
    }

    public function setRcmSsCatId7($rcm_ss_cat_id_7)
    {
        if ($rcm_ss_cat_id_7 !== null) {
            $this->rcm_ss_cat_id_7 = $rcm_ss_cat_id_7;
        }
    }

    public function getRcmSsCatId7()
    {
        return $this->rcm_ss_cat_id_7;
    }

    public function setRcmSsCatId8($rcm_ss_cat_id_8)
    {
        if ($rcm_ss_cat_id_8 !== null) {
            $this->rcm_ss_cat_id_8 = $rcm_ss_cat_id_8;
        }
    }

    public function getRcmSsCatId8()
    {
        return $this->rcm_ss_cat_id_8;
    }

    public function setWarrantyCat($warranty_cat)
    {
        if ($warranty_cat !== null) {
            $this->warranty_cat = $warranty_cat;
        }
    }

    public function getWarrantyCat()
    {
        return $this->warranty_cat;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
