<?php 

include_once(BASEPATH . "plugins/TransformArray.php");
include_once(BASEPATH . "plugins/Inflector.php");

class XmlArray extends TransformArray
{

    private $XMLFile;
    /** @var  DOMDocument */
    private $doc;

    /**
     * setXMLFile
     * @param $XMLFile
     * @return bool
     */
    public function setXMLFile($XMLFile)
    {
        if (file_exists($XMLFile)) {
            $this->XMLFile = $XMLFile;
            return true;
        }
        return false;
    }

    /**
     * saveArray
     * @param string $rootName
     * @param string $encoding
     * @return string
     */
    public function createXmlFromArray($rootName = 'orders', $encoding = 'utf-8')
    {
        $this->doc = new DOMDocument('1.0', $encoding);
        if (count($this->array) > 0) {
            if ($rootName !== '') {
                $root = $this->doc->createElement($rootName);
            } else {
                $root = $this->doc->createElement('root');
                $rootName = 'root';
            }
            $arr = $this->array;
        } else {
            $key = key($this->array);

            if (!is_int($key)) {
                $root = $this->doc->createElement($key);
                $rootName = $key;
            } else {
                if ($rootName !== '') {
                    $root = $this->doc->createElement($rootName);
                } else {
                    $root = $this->doc->createElement('root');
                    $rootName = 'root';
                }
            }
            $arr = $this->array[$key];
        }

        $root = $this->doc->appendChild($root);

        $this->addArray($arr, $root, $rootName);

        return $this->doc->saveXML();
    }


    public function addArray($arr, DOMNode $n, $name = '')
    {
        foreach ($arr as $key => $val) {
            $newKey = $key;
            if (is_int($key)) {
                $newKey = 'order';
                if ((strlen($name) > 1) && ($this->singular($name) != $name)) {
                    $newKey = $this->singular($name);
                }
                $node=$n;
            }else{
                $node = $this->doc->createElement($newKey);
            }

            if (is_array($val)) {
                $this->addArray($val, $node, $key);
            } else {
                $nodeText = $this->doc->createTextNode($val);
                $node->appendChild($nodeText);
            }
            if(!is_int($key)){
               $n->appendChild($node); 
            }
        }
    }

    public function singular($value)
    {
        $singular=Inflector::singularize($value);
         return static::matchCase($singular, $value);
    }

    protected static function matchCase($value, $comparison)
    {
        $functions = ['mb_strtolower', 'mb_strtoupper', 'ucfirst', 'ucwords'];

        foreach ($functions as $function) {
            if (call_user_func($function, $comparison) === $comparison) {
                return call_user_func($function, $value);
            }
        }

        return $value;
    }
}