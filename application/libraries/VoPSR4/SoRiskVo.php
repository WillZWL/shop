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

}
