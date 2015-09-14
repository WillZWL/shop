<?php
class ProductCreation extends MY_Controller
{

    public function createProductByXML()
    {
        $data = 'xml_file';

        $this->sc['productService']->data = $data;
        $this->sc['productService']->addProductData($this->sc['productDataXML'])->createProductProcess();
    }
}
