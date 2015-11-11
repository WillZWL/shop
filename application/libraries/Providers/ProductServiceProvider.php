<?php
namespace ESG\Panther\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use ESG\Panther\Service\ProductService;

class ProductServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['productService'] = function ($pimple) {
            return new ProductService($pimple['factory']);
        };
    }
}
