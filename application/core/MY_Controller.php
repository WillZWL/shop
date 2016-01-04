<?php
use Pimple\Container;
use ESG\Panther\Models\CustomerService\FaqadminModel;
use ESG\Panther\Models\Mastercfg\BrandModel;
use ESG\Panther\Models\Mastercfg\ColourModel;
use ESG\Panther\Models\Mastercfg\CountryModel;
use ESG\Panther\Models\Mastercfg\CurrencyModel;
use ESG\Panther\Models\Mastercfg\CustomClassModel;
use ESG\Panther\Models\Mastercfg\DeliverytimeModel;
use ESG\Panther\Models\Mastercfg\DeliveryModel;
use ESG\Panther\Models\Mastercfg\ExchangeRateModel;
use ESG\Panther\Models\Mastercfg\FreightModel;
use ESG\Panther\Models\Mastercfg\LanguageModel;
use ESG\Panther\Models\Mastercfg\UserModel;
use ESG\Panther\Models\Mastercfg\ProfitVarModel;
use ESG\Panther\Models\Marketing\CategoryModel;
use ESG\Panther\Models\Marketing\RaProdCatModel;
use ESG\Panther\Models\Marketing\PricingRulesModel;
use ESG\Panther\Models\Marketing\DataFeedModel;
use ESG\Panther\Models\Marketing\BundleConfigModel;
use ESG\Panther\Models\Order\SoModel;
use ESG\Panther\Models\Order\CreditCheckModel;
use ESG\Panther\Models\Website\CartSessionModel;
use ESG\Panther\Service as S;
use ESG\Panther\Dao as D;

abstract class MY_Controller extends CI_Controller
{
    private $langId = "en";
    protected $container;
    private static $serviceContainer;

    abstract public function getAppId();

    public function __construct($checkAccessRights = TRUE)
    {

        parent::__construct();
        $this->load->library('pagination');

        if (!self::$serviceContainer) {
            $sc = new \Pimple\Container;
            $daoArr = (array) require APPPATH . 'libraries/ServicePSR4/providers.php';
            array_walk($daoArr, function($class, $i, $sc) {
                class_exists($class) AND $sc->register(new $class);
            }, $sc);

            self::$serviceContainer = true;
            $this->sc = $sc;

            // Important, must call one time to make initilize BaseService
            $this->sc['Base'];
        }

        $this->loadModelDependcy();
        // $this->loadVoDependcy();

        $_SESSION["CURRPAGE"] = $_SERVER['REQUEST_URI'];
        $currsign = array("GBP" => "£", "EUR" => "€");
        if ($this->config->item('uri_protocol') != "CLI") {
            $this->checkAuthed();
            if ($checkAccessRights) {
                $this->sc['Authorization']->checkAccessRights($this->getAppId(), "");
                $feature_list = $this->sc['Authorization']->setApplicationFeatureRight($this->getAppId(), "");
            }
        }
    }

    public function loadModelDependcy()
    {
        $this->sc['brandModel'] = function ($c) {
            return new BrandModel;
        };

        $this->sc['colourModel'] = function ($c) {
            return new ColourModel;
        };

        $this->sc['countryModel'] = function ($c) {
            return new CountryModel;
        };

        $this->sc['currencyModel'] = function ($c) {
            return new CurrencyModel;
        };

        $this->sc['customClassModel'] = function ($c) {
            return new CustomClassModel;
        };

        $this->sc['deliverytimeModel'] = function ($c) {
            return new DeliverytimeModel;
        };

        $this->sc['deliveryModel'] = function ($c) {
            return new DeliveryModel;
        };

        $this->sc['exchangeRateModel'] = function ($c) {
            return new ExchangeRateModel;
        };

        $this->sc['faqadminModel'] = function ($c) {
            return new FaqadminModel;
        };

        $this->sc['freightModel'] = function ($c) {
            return new FreightModel;
        };

        $this->sc['languageModel'] = function ($c) {
            return new LanguageModel;
        };

        $this->sc['userModel'] = function ($c) {
            return new UserModel;
        };

        $this->sc['profitVarModel'] = function ($c) {
            return new ProfitVarModel;
        };

        $this->sc['pricingRulesModel'] = function ($c) {
            return new PricingRulesModel;
        };

        $this->sc['raProdCatModel'] = function ($c) {
            return new RaProdCatModel;
        };

		$this->sc['categoryModel'] = function ($c) {
            return new CategoryModel;
        };

        $this->sc['soModel'] = function ($c) {
            return new SoModel;
        };

        $this->sc['dataFeedModel'] = function ($c) {
            return new DataFeedModel;
        };

        $this->sc['creditCheckModel'] = function ($c) {
            return new CreditCheckModel;
        };

        $this->sc['bundleConfigModel'] = function ($c) {
            return new BundleConfigModel;
        };


        $this->sc['CartSessionModel'] = function ($c) {
            return new CartSessionModel;
        };
    }

    // public function loadVoDependcy()
    // {
    //     $this->sc['productVoByPost'] = $this->sc->factory(function ($c) {
    //         return new ProductVoByPost();
    //     });

    //     $this->sc['supplierProdVoByPost'] = $this->sc->factory(function ($c) {
    //         return new SupplierProdVoByPost();
    //     });
    // }


    public function getLangId()
    {
        return $this->langId;
    }

    private function checkAuthed()
    {
        if (!$this->sc['Authentication']->checkAuthed()) {
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
