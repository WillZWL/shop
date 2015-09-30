<?php
namespace ESG\Panther\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use ESG\Panther\Service\ProductDataFactory;

class ProductDataFactoryServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['bucket'] = new ArrayObject();

        $pimple['factory'] = function ($pimple) {
            return new ProductDataFactory($pimple['bucket']);
        };
    }
}
