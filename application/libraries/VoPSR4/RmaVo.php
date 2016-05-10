<?php
class RmaVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $client_id;
    private $forename;
    private $surname;
    private $address_1;
    private $address_2;
    private $postcode;
    private $city;
    private $state;
    private $country_id;
    private $product_returned;
    private $category;
    private $serial_no;
    private $components;
    private $reason;
    private $action_request = '0';
    private $details;
    private $shipfrom;
    private $status = '0';


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

    public function setClientId($client_id)
    {
        if ($client_id !== null) {
            $this->client_id = $client_id;
        }
    }

    public function getClientId()
    {
        return $this->client_id;
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

    public function setProductReturned($product_returned)
    {
        if ($product_returned !== null) {
            $this->product_returned = $product_returned;
        }
    }

    public function getProductReturned()
    {
        return $this->product_returned;
    }

    public function setCategory($category)
    {
        if ($category !== null) {
            $this->category = $category;
        }
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setSerialNo($serial_no)
    {
        if ($serial_no !== null) {
            $this->serial_no = $serial_no;
        }
    }

    public function getSerialNo()
    {
        return $this->serial_no;
    }

    public function setComponents($components)
    {
        if ($components !== null) {
            $this->components = $components;
        }
    }

    public function getComponents()
    {
        return $this->components;
    }

    public function setReason($reason)
    {
        if ($reason !== null) {
            $this->reason = $reason;
        }
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setActionRequest($action_request)
    {
        if ($action_request !== null) {
            $this->action_request = $action_request;
        }
    }

    public function getActionRequest()
    {
        return $this->action_request;
    }

    public function setDetails($details)
    {
        if ($details !== null) {
            $this->details = $details;
        }
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function setShipfrom($shipfrom)
    {
        if ($shipfrom !== null) {
            $this->shipfrom = $shipfrom;
        }
    }

    public function getShipfrom()
    {
        return $this->shipfrom;
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
