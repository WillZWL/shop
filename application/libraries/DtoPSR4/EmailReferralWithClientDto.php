<?php
class EmailReferralWithClientDto
{
    private $id;
    private $client_id;
    private $email;
    private $forename;
    private $surname;
    private $address;
    private $address_1;
    private $address_2;
    private $address_3;
    private $postcode;
    private $city;
    private $state;
    private $country_id;
    private $tel_1;
    private $tel_2;
    private $tel_3;
    private $create_on;
    private $create_at;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
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

    public function getAddress()
    {
        $temp[] = $this->address_1;
        $temp[] = $this->address_2;
        $temp[] = $this->address_3;
        $temp = array_filter($temp);
        $addr_str = '';
        foreach ($temp as $addr) {
            $addr_str .= $addr . ',';
        }

        return rtrim($addr_str, ',');
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

}
