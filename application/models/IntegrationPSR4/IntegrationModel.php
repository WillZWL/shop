<?php
namespace ESG\Panther\Models\Integration;

use ESG\Panther\Service\CountryService;
use ESG\Panther\Service\CountryStateService;
use ESG\Panther\Service\PaymentOptionService;
use ESG\Panther\Service\SoFactoryService;
use ESG\Panther\Service\CartSessionService;
use ESG\Panther\Service\PaymentGatewayRedirectCybersourceService;
use ESG\Panther\Service\PaymentGatewayRedirectPaypalService;
use ESG\Panther\Service\PaymentGatewayRedirectMoneybookersService;
use ESG\Panther\Form\GeneralInputFilter;
use ESG\Panther\Service\ClientService;

class IntegrationModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sendOrderToCybsDecisionManager($debug = 0)
    {
        $cybs = new PaymentGatewayRedirectCybersourceService();
        return $cybs->sendOrderToDm($debug);
    }
}
