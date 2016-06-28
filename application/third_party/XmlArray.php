<?php 
namespace Esgwe\Src;

use DOMDocument;
use DOMNode;

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
    public function createXmlFromArray($rootName = 'items', $encoding = 'utf-8')
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
                $newKey = 'item';
                if ((strlen($name) > 1) && (str_singular($name) !== $name)) {
                    $newKey = str_singular($name);
                }
            }
            $node = $this->doc->createElement($newKey);
            if (is_array($val)) {
                $this->addArray($arr[$key], $node, $key);
            } else {
                $nodeText = $this->doc->createTextNode($val);
                $node->appendChild($nodeText);
            }
            $n->appendChild($node);
        }
    }
}