<?php
class ClientVo extends \BaseVo
{
    private $id;
    private $ext_client_id;
    private $client_id_no;
    private $email;
    private $password;
    private $title;
    private $forename;
    private $surname;
    private $companyname;
    private $address_1;
    private $address_2;
    private $address_3;
    private $postcode;
    private $city;
    private $state;
    private $country_id;
    private $del_name;
    private $del_company;
    private $del_address_1;
    private $del_address_2;
    private $del_address_3;
    private $del_postcode;
    private $del_city;
    private $del_state;
    private $del_country_id;
    private $tel_1;
    private $tel_2;
    private $tel_3;
    private $mobile;
    private $del_tel_1;
    private $del_tel_2;
    private $del_tel_3;
    private $del_mobile;
    private $subscriber = 0;
    private $party_subscriber;
    private $vip = 0;
    private $vip_joined_date;
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at;
    private $modify_by;

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setExtClientId($ext_client_id)
    {
        $this->ext_client_id = $ext_client_id;
    }

    public function getExtClientId()
    {
        return $this->ext_client_id;
    }

    public function setClientIdNo($client_id_no)
    {
        $this->client_id_no = $client_id_no;
    }

    public function getClientIdNo()
    {
        return $this->client_id_no;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setForename($forename)
    {
        $this->forename = $forename;
    }

    public function getForename()
    {
        return $this->forename;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function setCompanyname($companyname)
    {
        $this->companyname = $companyname;
    }

    public function getCompanyname()
    {
        return $this->companyname;
    }

    public function setAddress1($address_1)
    {
        $this->address_1 = $address_1;
    }

    public function getAddress1()
    {
        return $this->address_1;
    }

    public function setAddress2($address_2)
    {
        $this->address_2 = $address_2;
    }

    public function getAddress2()
    {
        return $this->address_2;
    }

    public function setAddress3($address_3)
    {
        $this->address_3 = $address_3;
    }

    public function getAddress3()
    {
        return $this->address_3;
    }

    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setDelName($del_name)
    {
        $this->del_name = $del_name;
    }

    public function getDelName()
    {
        return $this->del_name;
    }

    public function setDelCompany($del_company)
    {
        $this->del_company = $del_company;
    }

    public function getDelCompany()
    {
        return $this->del_company;
    }

    public function setDelAddress1($del_address_1)
    {
        $this->del_address_1 = $del_address_1;
    }

    public function getDelAddress1()
    {
        return $this->del_address_1;
    }

    public function setDelAddress2($del_address_2)
    {
        $this->del_address_2 = $del_address_2;
    }

    public function getDelAddress2()
    {
        return $this->del_address_2;
    }

    public function setDelAddress3($del_address_3)
    {
        $this->del_address_3 = $del_address_3;
    }

    public function getDelAddress3()
    {
        return $this->del_address_3;
    }

    public function setDelPostcode($del_postcode)
    {
        $this->del_postcode = $del_postcode;
    }

    public function getDelPostcode()
    {
        return $this->del_postcode;
    }

    public function setDelCity($del_city)
    {
        $this->del_city = $del_city;
    }

    public function getDelCity()
    {
        return $this->del_city;
    }

    public function setDelState($del_state)
    {
        $this->del_state = $del_state;
    }

    public function getDelState()
    {
        return $this->del_state;
    }

    public function setDelCountryId($del_country_id)
    {
        $this->del_country_id = $del_country_id;
    }

    public function getDelCountryId()
    {
        return $this->del_country_id;
    }

    public function setTel1($tel_1)
    {
        $this->tel_1 = $tel_1;
    }

    public function getTel1()
    {
        return $this->tel_1;
    }

    public function setTel2($tel_2)
    {
        $this->tel_2 = $tel_2;
    }

    public function getTel2()
    {
        return $this->tel_2;
    }

    public function setTel3($tel_3)
    {
        $this->tel_3 = $tel_3;
    }

    public function getTel3()
    {
        return $this->tel_3;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setDelTel1($del_tel_1)
    {
        $this->del_tel_1 = $del_tel_1;
    }

    public function getDelTel1()
    {
        return $this->del_tel_1;
    }

    public function setDelTel2($del_tel_2)
    {
        $this->del_tel_2 = $del_tel_2;
    }

    public function getDelTel2()
    {
        return $this->del_tel_2;
    }

    public function setDelTel3($del_tel_3)
    {
        $this->del_tel_3 = $del_tel_3;
    }

    public function getDelTel3()
    {
        return $this->del_tel_3;
    }

    public function setDelMobile($del_mobile)
    {
        $this->del_mobile = $del_mobile;
    }

    public function getDelMobile()
    {
        return $this->del_mobile;
    }

    public function setSubscriber($subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function getSubscriber()
    {
        return $this->subscriber;
    }

    public function setPartySubscriber($party_subscriber)
    {
        $this->party_subscriber = $party_subscriber;
    }

    public function getPartySubscriber()
    {
        return $this->party_subscriber;
    }

    public function setVip($vip)
    {
        $this->vip = $vip;
    }

    public function getVip()
    {
        return $this->vip;
    }

    public function setVipJoinedDate($vip_joined_date)
    {
        $this->vip_joined_date = $vip_joined_date;
    }

    public function getVipJoinedDate()
    {
        return $this->vip_joined_date;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
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
