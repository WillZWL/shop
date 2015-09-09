<?php
use Pimple\Container;
use AtomV2\Models\CustomerService\FaqadminModel;
use AtomV2\Models\Mastercfg\BrandModel;
use AtomV2\Models\Mastercfg\ColourModel;
use AtomV2\Models\Mastercfg\CountryModel;
use AtomV2\Models\Mastercfg\CurrencyModel;
use AtomV2\Models\Mastercfg\CustomClassModel;
use AtomV2\Models\Mastercfg\DeliverytimeModel;
use AtomV2\Models\Mastercfg\DeliveryModel;
use AtomV2\Models\Mastercfg\ExchangeRateModel;
use AtomV2\Models\Mastercfg\FreightModel;
use AtomV2\Models\Mastercfg\LanguageModel;
use AtomV2\Models\Mastercfg\UserModel;
use AtomV2\Models\Mastercfg\ProfitVarModel;
use AtomV2\Service as S;
use AtomV2\Dao as D;

abstract class MY_Controller extends CI_Controller
{
    private $langId = "en";
    protected $container;

    abstract public function getAppId();

    public function __construct($checkAccessRights = TRUE)
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->container = new Container();
        $this->loadModelDependcy();
        $this->loadServiceDependcy();
        $this->loadVoDependcy();

        $this->authenticationService = new S\AuthenticationService;
        $_SESSION["CURRPAGE"] = $_SERVER['REQUEST_URI'];
        $currsign = array("GBP" => "£", "EUR" => "€");
        if ($this->config->item('uri_protocol') != "CLI") {
            $this->checkAuthed();
            $this->authorizationService = new S\AuthorizationService;
            if ($checkAccessRights) {
                $this->container['authorizationService']->checkAccessRights($this->getAppId(), "");
                $feature_list = $this->container['authorizationService']->setApplicationFeatureRight($this->getAppId(), "");
            }
        }
    }

    public function loadModelDependcy()
    {
        $this->container['brandModel'] = function ($c) {
            return new BrandModel;
        };

        $this->container['colourModel'] = function ($c) {
            return new ColourModel;
        };

        $this->container['countryModel'] = function ($c) {
            return new CountryModel;
        };

        $this->container['currencyModel'] = function ($c) {
            return new CurrencyModel;
        };

        $this->container['customClassModel'] = function ($c) {
            return new CustomClassModel;
        };

        $this->container['deliverytimeModel'] = function ($c) {
            return new DeliverytimeModel;
        };

        $this->container['deliveryModel'] = function ($c) {
            return new DeliveryModel;
        };

        $this->container['exchangeRateModel'] = function ($c) {
            return new ExchangeRateModel;
        };

        $this->container['faqadminModel'] = function ($c) {
            return new FaqadminModel;
        };

        $this->container['freightModel'] = function ($c) {
            return new FreightModel;
        };

        $this->container['languageModel'] = function ($c) {
            return new LanguageModel;
        };

        $this->container['userModel'] = function ($c) {
            return new UserModel;
        };

        $this->container['profitVarModel'] = function ($c) {
            return new ProfitVarModel;
        };
    }

    public function loadVoDependcy()
    {
        $this->container['productVoByPost'] = $this->container->factory(function ($c) {
            return new ProductVoByPost();
        });

        $this->container['supplierProdVoByPost'] = $this->container->factory(function ($c) {
            return new SupplierProdVoByPost();
        });
    }

    public function loadDaoDependcy()
    {
        $this->container['supplierProdDao'] = $this->container->factory(function ($c) {
            return new D\SupplierProdDao();
        });
    }

    private function loadServiceDependcy()
    {
        $this->container['authorizationService'] = function ($c) {
            return new S\AuthorizationService;
        };

        $this->container['authenticationService'] = function ($c) {
            return new S\AuthenticationService;
        };

        $this->container['contextConfigService'] = function ($c) {
            return new S\ContextConfigService;
        };

        $this->container['languageService'] = $this->container->factory(function ($c) {
            return new S\LanguageService();
        });

        $this->container['priceMarginService'] = function ($c) {
            return new S\PriceMarginService();
        };

        $this->container['productService'] = function ($c) {
            return new S\ProductService();
        };

        $this->container['logService'] = function ($c) {
            return new S\LogService;
        };
    }

    public function getLangId()
    {
        return $this->langId;
    }

    private function checkAuthed()
    {
        if (!$this->container['authenticationService']->checkAuthed()) {
            $data["fail_msg"] = $this->getFailMsg();
            redirect($this->getLoginPage());
        }
    }

    public function getFailMsg()
    {
        return "Please login to the system first!";
    }

    public function getLoginPage()
    {
        return "?back=" . urlencode($_SESSION["CURRPAGE"]);
    }

    public function getRu()
    {
        $ru = $_SESSION["CURRPAGE"];
        if ($pru = $this->input->post("ru")) {
            $ru = $this->encrypt->decode($pru);
        }

        return $ru;
    }

    public function setFormRu()
    {
        return "<input type='hidden' name='ru' value='".$this->encrypt->encode($_SESSION["CURRPAGE"])."'>";
    }
}
