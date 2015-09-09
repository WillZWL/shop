<?php
class ProductCreation extends MY_Controller
{

    public function createProductByXML()
    {
        $data = 'xml_file';

        $this->container['productService']->data = $data;
        $this->container['productService']->addProductData($this->container['productDataXML'])->createProductProcess();
    }
}
