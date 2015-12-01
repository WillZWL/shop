<?php
class SoRiskVo extends \BaseVo
{
    private $id;
    private $so_no = '';
    private $risk_requested = '0';
    private $risk_var_1 = '';
    private $risk_var_2 = '';
    private $risk_var_3 = '';
    private $risk_var_4 = '';
    private $risk_var_5 = '';
    private $risk_var_6 = '';
    private $risk_var_7 = '';
    private $risk_var_8 = '';
    private $risk_var_9 = '';
    private $risk_var_10 = '';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

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

    public function setRiskRequested($risk_requested)
    {
        if ($risk_requested !== null) {
            $this->risk_requested = $risk_requested;
        }
    }

    public function getRiskRequested()
    {
        return $this->risk_requested;
    }

    public function setRiskVar1($risk_var_1)
    {
        if ($risk_var_1 !== null) {
            $this->risk_var_1 = $risk_var_1;
        }
    }

    public function getRiskVar1()
    {
        return $this->risk_var_1;
    }

    public function setRiskVar2($risk_var_2)
    {
        if ($risk_var_2 !== null) {
            $this->risk_var_2 = $risk_var_2;
        }
    }

    public function getRiskVar2()
    {
        return $this->risk_var_2;
    }

    public function setRiskVar3($risk_var_3)
    {
        if ($risk_var_3 !== null) {
            $this->risk_var_3 = $risk_var_3;
        }
    }

    public function getRiskVar3()
    {
        return $this->risk_var_3;
    }

    public function setRiskVar4($risk_var_4)
    {
        if ($risk_var_4 !== null) {
            $this->risk_var_4 = $risk_var_4;
        }
    }

    public function getRiskVar4()
    {
        return $this->risk_var_4;
    }

    public function setRiskVar5($risk_var_5)
    {
        if ($risk_var_5 !== null) {
            $this->risk_var_5 = $risk_var_5;
        }
    }

    public function getRiskVar5()
    {
        return $this->risk_var_5;
    }

    public function setRiskVar6($risk_var_6)
    {
        if ($risk_var_6 !== null) {
            $this->risk_var_6 = $risk_var_6;
        }
    }

    public function getRiskVar6()
    {
        return $this->risk_var_6;
    }

    public function setRiskVar7($risk_var_7)
    {
        if ($risk_var_7 !== null) {
            $this->risk_var_7 = $risk_var_7;
        }
    }

    public function getRiskVar7()
    {
        return $this->risk_var_7;
    }

    public function setRiskVar8($risk_var_8)
    {
        if ($risk_var_8 !== null) {
            $this->risk_var_8 = $risk_var_8;
        }
    }

    public function getRiskVar8()
    {
        return $this->risk_var_8;
    }

    public function setRiskVar9($risk_var_9)
    {
        if ($risk_var_9 !== null) {
            $this->risk_var_9 = $risk_var_9;
        }
    }

    public function getRiskVar9()
    {
        return $this->risk_var_9;
    }

    public function setRiskVar10($risk_var_10)
    {
        if ($risk_var_10 !== null) {
            $this->risk_var_10 = $risk_var_10;
        }
    }

    public function getRiskVar10()
    {
        return $this->risk_var_10;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on !== null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at !== null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by !== null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on !== null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at !== null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by !== null) {
            $this->modify_by = $modify_by;
        }
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
