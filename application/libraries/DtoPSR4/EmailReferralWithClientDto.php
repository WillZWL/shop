<?php
class EmailReferralWithClientDto
{
    private $id;
    private $client_id;
    private $email;
    private $forename;
    private $surname;
    private $address;
    private $address__1;
    private $address__2;
    private $address__3;
    private $postcode;
    private $city;
    private $state;
    private $country_id;
    private $tel__1;
    private $tel__2;
    private $tel__3;
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

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress1($address__1)
    {
        $this->address__1 = $address__1;
    }

    public function getAddress1()
    {
        return $this->address__1;
    }

    public function setAddress2($address__2)
    {
        $this->address__2 = $address__2;
    }

    public function getAddress2()
    {
        return $this->address__2;
    }

    public function setAddress3($address__3)
    {
        $this->address__3 = $address__3;
    }

    public function getAddress3()
    {
        return $this->address__3;
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

    public function setTel1($tel__1)
    {
        $this->tel__1 = $tel__1;
    }

    public function getTel1()
    {
        return $this->tel__1;
    }

    public function setTel2($tel__2)
    {
        $this->tel__2 = $tel__2;
    }

    public function getTel2()
    {
        return $this->tel__2;
    }

    public function setTel3($tel__3)
    {
        $this->tel__3 = $tel__3;
    }

    public function getTel3()
    {
        return $this->tel__3;
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
