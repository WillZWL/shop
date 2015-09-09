<?php
namespace ESG\Panther;

use ArrayAccess;
use InvalidArgumentException;

class ProductData
{
    private $productVo;

    public function __construct(BaseVo $obj)
    {
        $this->productVo = $dataObj;
    }
}
