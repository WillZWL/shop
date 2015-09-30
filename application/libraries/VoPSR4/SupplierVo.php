<?php
class SupplierVo extends \BaseVo
{

    //class variable
    private $id;
    private $name;
    private $origin_country;
    private $currency_id;
    private $creditor = '0';
    private $address1;
    private $address2;
    private $address3;
    private $phone1;
    private $phone2;
    private $phone3;
    private $fax1;
    private $fax2;
    private $fax3;
    private $email;
    private $supplier_reg;
    private $sourcing_reg;
    private $note;
    private $fc_id;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    //primary key
    private $primary_key = array("id");

    //auo increment
    private $increment_field = "id";

    //instance method
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getOriginCountry()
    {
        return $this->origin_country;
    }

    public function setOriginCountry($origin_country)
    {
        $this->origin_country = $origin_country;
    }

    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    public function setCurrencyId($currency_id)
    {
        $this->currencyId = $currency_id;
    }

    public function getCreditor()
    {
        return $this->creditor;
    }

    public function setCreditor($creditor)
    {
        $this->creditor = $creditor;
    }

    public function getAddress1()
    {
        return $this->address1;
    }

    public function setAddress1($address1)
    {
        $this->address1 = $address1;
    }

    public function getAddress2()
    {
        return $this->address2;
    }

    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

    public function getaddress3()
    {
        return $this->address3;
    }

    public function setaddress3($address3)
    {
        $this->address3 = $address3;
    }

    public function getPhone1()
    {
        return $this->phone1;
    }

    public function setPhone1($phone1)
    {
        $this->phone1 = $phone1;
    }

    public function getPhone2()
    {
        return $this->phone2;
    }

    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;
    }

    public function getPhone3()
    {
        return $this->phone3;
    }

    public function setPhone3($phone3)
    {
        $this->phone3 = $phone3;
    }

    public function getFax1()
    {
        return $this->fax1;
    }

    public function setFax1($fax1)
    {
        $this->fax1 = $fax1;
    }

    public function getFax2()
    {
        return $this->fax2;
    }

    public function setFax2($fax2)
    {
        $this->fax2 = $fax2;
    }

    public function getFax3()
    {
        return $this->fax3;
    }

    public function setFax3($fax3)
    {
        $this->fax3 = $fax3;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSupplierReg()
    {
        return $this->supplier_reg;
    }

    public function setSupplierReg($supplier_reg)
    {
        $this->supplier_reg = $supplier_reg;
    }

    public function getSourcingReg()
    {
        return $this->sourcing_reg;
    }

    public function setSourcingReg($sourcing_reg)
    {
        $this->sourcing_reg = $sourcing_reg;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function setNote($note)
    {
        $this->note = $note;
    }

    public function getFcId()
    {
        return $this->fcId;
    }

    public function setFcId($fc_id)
    {
        $this->fcId = $fc_id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
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