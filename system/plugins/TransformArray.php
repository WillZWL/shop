<?php 

abstract class TransformArray
{
    protected $array;

    public function __construct(array $array)
    {
        if (count($array) === 0) {
            $array = ['empty array'];
        }
        $this->array = $array;
    }

}