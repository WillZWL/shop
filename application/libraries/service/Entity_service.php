<?php

include_once "Base_service.php";

class Entity_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Entity_dao.php");
        $this->set_dao(new Entity_dao());
    }

    public function get_entity_id($total_amount = 0, $currency_id = null, $country_id = null)
    {
// from entity table
// 1 = HK
// 2 = NZ

        return 1;
/*
        if ($currency_id != null)
        {
            switch($currency_id)
            {
                case "NZD":
                    if ($total_amount >= 400)
                        return 2;
                    else
                        return 1;
                default:
                    return 1;
            }
        }
        else
        {
//country id may be used later if the combination of the entity changes.
            return 1;
        }
        */
    }
}

/* End of file entity_service.php */
/* Location: ./app/libraries/service/Entity_service.php */