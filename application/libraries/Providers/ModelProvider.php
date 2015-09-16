<?php
namespace ESG\Panther\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use ESG\Panther\Models\Mastercfg as Mastercfg;

class ModelProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['ColourModel'] = function ($pimple) {
            return new Mastercfg\ColourModel();
        };

        $pimple['UserModel'] = function ($pimple) {
            return new Mastercfg\UserModel();
        };

        $pimple['ExchangeRateModel'] = function ($pimple) {
            return new Mastercfg\ExchangeRateModel();
        };

        $pimple['FreightModel'] = function ($pimple) {
            return new Mastercfg\FreightModel();
        };
    }
}
