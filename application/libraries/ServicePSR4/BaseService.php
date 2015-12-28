<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao as D;

class BaseService
{
    const ALERT_HAZARD_LEVEL = "HAZARD";
    const ALERT_GENERAL_LEVEL = "GENERAL";
    private $dao;

    private static $daoContainer;
    private static $serviceContainer;

    public function __construct($c = null)
    {
        if ($c instanceof \Pimple\Container) {
            self::$serviceContainer = $c;
        }

        if (!self::$daoContainer) {
            $dc = new \Pimple\Container;
            $daoArr = (array) require APPPATH . 'libraries/DaoPSR4/providers.php';
            array_walk($daoArr, function($class, $i, $dc) {
                class_exists($class) AND $dc->register(new $class);
            }, $dc);

            self::$daoContainer = $dc;
        }

    }

    public function getService($service)
    {

        if (is_null(self::$serviceContainer[$service])) {
            throw new \InvalidArgumentException("{$service}Service doesn't in ServiceProvider.php");
        }

        if ($service.'Service' === end(explode('\\', get_class($this)))) {
            throw new \Exception("use getService in endless loop", 1);
        }

        return self::$serviceContainer[$service];
    }

    public function getDao($dao = null)
    {

        if (is_null($dao)) {
            $serviceName = get_class($this);
            $serviceBaseName = end(explode('\\', $serviceName));
            $dao = substr($serviceBaseName, 0, -7);
        }

        if (is_null(self::$daoContainer[$dao])) {
            throw new \InvalidArgumentException("{$dao}Dao doesn't in DaoProvider.php");
        }

        return self::$daoContainer[$dao];
    }

    public function setDao($dao)
    {
        $this->dao = $dao;
    }

    public function get($where = [], $className = '')
    {
        return $this->getDao()->get($where, $className);
    }

    public function getNumRows($where = [])
    {
        return $this->getDao()->getNumRows($where);
    }

    public function getList($where = [], $option = [], $className = "")
    {
        return $this->getDao()->getList($where, $option, $className);
    }

    public function sendAlert($subject, $message, $email = null, $type = self::ALERT_GENERAL_LEVEL)
    {
        //if ($type == self::ALERT_GENERAL_LEVEL)
        if ($email != null)
            mail($email, $subject, $message, "From: website@" . strtolower(SITE_DOMAIN) . "\r\n");
        //print $message;
    }
}
