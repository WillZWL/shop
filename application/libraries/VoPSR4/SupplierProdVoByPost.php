<?php

class SupplierProdVoByPost extends \SupplierProdVo implements ProductVoInterface
{
    public function Pick(array $data)
    {
        if ($this->check($data)) {
            $this->bindValue($data);
        }

        return $this;
    }

    public function check(array $data)
    {
        return true;
    }


    public function bindValue($data)
    {
        $classMethods = get_class_methods($this);

        foreach ($classMethods as $methodName) {
            if (substr($methodName, 0, 3) == "set") {
                $rskey = camelcase2underscore(substr($methodName, 3));
                if (isset($data[$rskey])) {
                    call_user_func(array($this, $methodName), $data[$rskey]);
                }
            }
        }
        unset($data);
    }
}
