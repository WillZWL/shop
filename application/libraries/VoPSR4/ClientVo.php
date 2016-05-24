<?php

class ClientVo extends \BaseVo
{
    private $id;
    private $ext_client_id = '';
    private $client_id_no = '';
    private $email;
    private $password;
    private $verify_code;
    private $title = '';
    private $forename;
    private $surname;
    private $companyname;
    private $address_1;
    private $address_2;
    private $address_3;
    private $postcode;
    private $city;
    private $state = '';
    private $country_id;
    private $del_name;
    private $del_company;
    private $del_address_1;
    private $del_address_2 = '';
    private $del_address_3 = '';
    private $del_postcode;
    private $del_city;
    private $del_state = '';
    private $del_country_id;
    private $tel_1;
    private $tel_2;
    private $tel_3;
    private $mobile = '';
    private $del_tel_1;
    private $del_tel_2;
    private $del_tel_3;
    private $del_mobile = '';
    private $subscriber = '0';
    private $party_subscriber = '0';
    private $vip = '0';
    private $vip_joined_date = '';
    private $status = '1';

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

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

    public function setExtClientId($ext_client_id)
    {
        if ($ext_client_id !== null) {
            $this->ext_client_id = $ext_client_id;
        }
    }

    public function getExtClientId()
    {
        return $this->ext_client_id;
    }

    public function setClientIdNo($client_id_no)
    {
        if ($client_id_no !== null) {
            $this->client_id_no = $client_id_no;
        }
    }

    public function getClientIdNo()
    {
        return $this->client_id_no;
    }

    public function setEmail($email)
    {
        if ($email !== null) {
            $this->email = $email;
        }
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        if ($password !== null) {
            $this->password = $password;
        }
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setVerifyCode($verify_code)
    {
        if ($verify_code !== null) {
            $this->verify_code = $verify_code;
        }
    }

    public function getVerifyCode()
    {
        return $this->verify_code;
    }

    public function setTitle($title)
    {
        if ($title !== null) {
            $this->title = $title;
        }
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setForename($forename)
    {
        if ($forename !== null) {
            $this->forename = $forename;
        }
    }

    public function getForename()
    {
        return $this->forename;
    }

    public function setSurname($surname)
    {
        if ($surname !== null) {
            $this->surname = $surname;
        }
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function setCompanyname($companyname)
    {
        if ($companyname !== null) {
            $this->companyname = $companyname;
        }
    }

    public function getCompanyname()
    {
        return $this->companyname;
    }

    public function setAddress1($address_1)
    {
        if ($address_1 !== null) {
            $this->address_1 = $address_1;
        }
    }

    public function getAddress1()
    {
        return $this->address_1;
    }

    public function setAddress2($address_2)
    {
        if ($address_2 !== null) {
            $this->address_2 = $address_2;
        }
    }

    public function getAddress2()
    {
        return $this->address_2;
    }

    public function setAddress3($address_3)
    {
        if ($address_3 !== null) {
            $this->address_3 = $address_3;
        }
    }

    public function getAddress3()
    {
        return $this->address_3;
    }

    public function setPostcode($postcode)
    {
        if ($postcode !== null) {
            $this->postcode = $postcode;
        }
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function setCity($city)
    {
        if ($city !== null) {
            $this->city = $city;
        }
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setState($state)
    {
        if ($state !== null) {
            $this->state = $state;
        }
    }

    public function getState()
    {
        return $this->state;
    }

    public function setCountryId($country_id)
    {
        if ($country_id !== null) {
            $this->country_id = $country_id;
        }
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setDelName($del_name)
    {
        if ($del_name !== null) {
            $this->del_name = $del_name;
        }
    }

    public function getDelName()
    {
        return $this->del_name;
    }

    public function setDelCompany($del_company)
    {
        if ($del_company !== null) {
            $this->del_company = $del_company;
        }
    }

    public function getDelCompany()
    {
        return $this->del_company;
    }

    public function setDelAddress1($del_address_1)
    {
        if ($del_address_1 !== null) {
            $this->del_address_1 = $del_address_1;
        }
    }

    public function getDelAddress1()
    {
        return $this->del_address_1;
    }

    public function setDelAddress2($del_address_2)
    {
        if ($del_address_2 !== null) {
            $this->del_address_2 = $del_address_2;
        }
    }

    public function getDelAddress2()
    {
        return $this->del_address_2;
    }

    public function setDelAddress3($del_address_3)
    {
        if ($del_address_3 !== null) {
            $this->del_address_3 = $del_address_3;
        }
    }

    public function getDelAddress3()
    {
        return $this->del_address_3;
    }

    public function setDelPostcode($del_postcode)
    {
        if ($del_postcode !== null) {
            $this->del_postcode = $del_postcode;
        }
    }

    public function getDelPostcode()
    {
        return $this->del_postcode;
    }

    public function setDelCity($del_city)
    {
        if ($del_city !== null) {
            $this->del_city = $del_city;
        }
    }

    public function getDelCity()
    {
        return $this->del_city;
    }

    public function setDelState($del_state)
    {
        if ($del_state !== null) {
            $this->del_state = $del_state;
        }
    }

    public function getDelState()
    {
        return $this->del_state;
    }

    public function setDelCountryId($del_country_id)
    {
        if ($del_country_id !== null) {
            $this->del_country_id = $del_country_id;
        }
    }

    public function getDelCountryId()
    {
        return $this->del_country_id;
    }

    public function setTel1($tel_1)
    {
        if ($tel_1 !== null) {
            $this->tel_1 = $tel_1;
        }
    }

    public function getTel1()
    {
        return $this->tel_1;
    }

    public function setTel2($tel_2)
    {
        if ($tel_2 !== null) {
            $this->tel_2 = $tel_2;
        }
    }

    public function getTel2()
    {
        return $this->tel_2;
    }

    public function setTel3($tel_3)
    {
        if ($tel_3 !== null) {
            $this->tel_3 = $tel_3;
        }
    }

    public function getTel3()
    {
        return $this->tel_3;
    }

    public function setMobile($mobile)
    {
        if ($mobile !== null) {
            $this->mobile = $mobile;
        }
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setDelTel1($del_tel_1)
    {
        if ($del_tel_1 !== null) {
            $this->del_tel_1 = $del_tel_1;
        }
    }

    public function getDelTel1()
    {
        return $this->del_tel_1;
    }

    public function setDelTel2($del_tel_2)
    {
        if ($del_tel_2 !== null) {
            $this->del_tel_2 = $del_tel_2;
        }
    }

    public function getDelTel2()
    {
        return $this->del_tel_2;
    }

    public function setDelTel3($del_tel_3)
    {
        if ($del_tel_3 !== null) {
            $this->del_tel_3 = $del_tel_3;
        }
    }

    public function getDelTel3()
    {
        return $this->del_tel_3;
    }

    public function setDelMobile($del_mobile)
    {
        if ($del_mobile !== null) {
            $this->del_mobile = $del_mobile;
        }
    }

    public function getDelMobile()
    {
        return $this->del_mobile;
    }

    public function setSubscriber($subscriber)
    {
        if ($subscriber !== null) {
            $this->subscriber = $subscriber;
        }
    }

    public function getSubscriber()
    {
        return $this->subscriber;
    }

    public function setPartySubscriber($party_subscriber)
    {
        if ($party_subscriber !== null) {
            $this->party_subscriber = $party_subscriber;
        }
    }

    public function getPartySubscriber()
    {
        return $this->party_subscriber;
    }

    public function setVip($vip)
    {
        if ($vip !== null) {
            $this->vip = $vip;
        }
    }

    public function getVip()
    {
        return $this->vip;
    }

    public function setVipJoinedDate($vip_joined_date)
    {
        if ($vip_joined_date !== null) {
            $this->vip_joined_date = $vip_joined_date;
        }
    }

    public function getVipJoinedDate()
    {
        return $this->vip_joined_date;
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
