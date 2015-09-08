<?php
namespace AtomV2\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use AtomV2\Service\ProductService;

class ProductServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['productService'] = function ($pimple) {
            return new ProductService($pimple['factory']);
        };
    }
}
