<?php
// namespace AtomV2\Dto;

class SimpleProductDto
{
	public $sku;

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }
}
