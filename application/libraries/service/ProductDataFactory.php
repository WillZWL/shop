<?php
namespace AtomV2\Service;

use ArrayAccess;
use InvalidArgumentException;

class ProductDataFactory
{
    public static $contract = '\\BaseVo';

    private $bucket;

    public function __construct(ArrayAccess $bucket)
    {
        $this->bucket = $bucket;
    }

    public function prepareData(array $args = [], $class = '\\BaseVo')
    {
        $key = $this->buildKey($class, $args);
        if (!$this->bucket->offsetExists($key) || !is_a($this->bucket[$key], self::$contract)) {
            $this->checkClass($class)->factory($class, $key, $args);
        }

        return $this->bucket[$key];
    }

    private function factory($class, $key, $args)
    {
        $this->bucket[$key] = new $class($args);
    }

    private function checkClass($class)
    {
        if (!class_exists($class) || !in_array(ltrim(self::$contract, '\\'), class_implements($class), true)) {
            throw new InvalidArgumentException("{$class} doesn't implement " . self::$contract);
        }
    }

    public function buildKey($class, $args)
    {
        if (!is_string($class)) {
            throw new InvalidArgumentException(__CLASS__ . '::prepareData() needs a valid class name');
        }

        $a = explode('\\', $class);

        return strtolower(end($a)).'.'.implode('_', array_map('strtolower', $args));
    }
}
