<?php

namespace yadjet\helpers;

use InvalidArgumentException;

/**
 * IP 检测
 * Class IpHelperAbstract
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
abstract class IpHelperAbstract
{

    /* @var $class IIpHelper */
    public $class;

    public $ip;

}

interface IIpHelper
{

    public function detect($ip);
}

class IpHelper extends IpHelperAbstract
{

    public function __construct($class, $ip)
    {
        if (!is_string($class) || !class_exists($class)) {
            throw new InvalidArgumentException('无效的 $class 参数值 ' . $class);
        }
        $this->class = $class;

        $ip = trim($ip);
        if (empty($ip)) {
            throw new InvalidArgumentException('$ip 不能为空。');
        }
        $this->ip = $ip;
    }

    /**
     * @return IP
     */
    public function detect()
    {
        return (new $this->class)->detect($this->ip);
    }

}

class IP
{

    private $ip;
    private $countryId;
    private $countryName;
    private $areaId;
    private $areaName;
    private $provinceId;
    private $provinceName;
    private $cityId;
    private $cityName;
    private $ispId;
    private $ispName;

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * @param mixed $countryId
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;
    }

    /**
     * @return mixed
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * @param mixed $countryName
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
    }

    /**
     * @return mixed
     */
    public function getAreaId()
    {
        return $this->areaId;
    }

    /**
     * @param mixed $areaId
     */
    public function setAreaId($areaId)
    {
        $this->areaId = $areaId;
    }

    /**
     * @return mixed
     */
    public function getAreaName()
    {
        return $this->areaName;
    }

    /**
     * @param mixed $areaName
     */
    public function setAreaName($areaName)
    {
        $this->areaName = $areaName;
    }

    /**
     * @return mixed
     */
    public function getProvinceId()
    {
        return $this->provinceId;
    }

    /**
     * @param mixed $provinceId
     */
    public function setProvinceId($provinceId)
    {
        $this->provinceId = $provinceId;
    }

    /**
     * @return mixed
     */
    public function getProvinceName()
    {
        return $this->provinceName;
    }

    /**
     * @param mixed $provinceName
     */
    public function setProvinceName($provinceName)
    {
        $this->provinceName = $provinceName;
    }

    /**
     * @return mixed
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * @param mixed $cityId
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;
    }

    /**
     * @return mixed
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * @param mixed $cityName
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
    }

    /**
     * @return mixed
     */
    public function getIspId()
    {
        return $this->ispId;
    }

    /**
     * @param mixed $ispId
     */
    public function setIspId($ispId)
    {
        $this->ispId = $ispId;
    }

    /**
     * @return mixed
     */
    public function getIspName()
    {
        return $this->ispName;
    }

    /**
     * @param mixed $ispName
     */
    public function setIspName($ispName)
    {
        $this->ispName = $ispName;
    }

}

class TaoBaoIpHelper implements IIpHelper
{

    public function detect($ipAddress)
    {
        $ip = new IP();
        $ip->setIp($ip);
        $response = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=$ipAddress");
        if ($response !== false) {
            $response = json_decode($response, true);
            if ($response && isset($response['code']) && $response['code'] == 0) {
                $body = $response['data'];
                $ip->setCountryId(isset($body['country_id']) ? $body['country_id'] : null);
                $ip->setCountryName(isset($body['country']) ? $body['country'] : null);
                $ip->setAreaId(isset($body['area_id']) ? $body['area_id'] : null);
                $ip->setAreaName(isset($body['area']) ? $body['area'] : null);
                $ip->setProvinceId(isset($body['region_id']) ? $body['region_id'] : null);
                $ip->setProvinceName(isset($body['region']) ? $body['region'] : null);
                $ip->setCityId(isset($body['city_id']) ? $body['city_id'] : null);
                $ip->setCityName(isset($body['city']) ? $body['city'] : null);
                $ip->setIspId(isset($body['isp_id']) ? $body['isp_id'] : null);
                $ip->setIspName(isset($body['isp']) ? $body['isp'] : null);
            }
        }

        return $ip;
    }

}