<?php
namespace AtomV2;

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
