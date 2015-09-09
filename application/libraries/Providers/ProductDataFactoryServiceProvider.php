<?php
namespace AtomV2\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use AtomV2\Service\ProductDataFactory;

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
