<?php

namespace yadjet\helpers;

use InvalidArgumentException;
use itbdw\Ip\IpLocation;

/**
 * IP 检测
 * Class IpLocationHelperAbstract
 *
 * @package yadjet\helpers
 * @author hiscaler <hiscaler@gmail.com>
 */
abstract class IpLocationHelperAbstract
{

    /* @var $class IIpLocationHelper */
    public $class;

    public $ip;

}

interface IIpLocationHelper
{

    public function detect($ip);

}

class IpLocationHelper extends IpLocationHelperAbstract
{

    /* @var $_ipObject IP */
    private $_ipObject;

    /**
     * 设置解析类
     *
     * @param $class
     * @return $this
     */
    public final function setEndpoint($class)
    {
        if (!is_string($class) || !class_exists($class)) {
            throw new InvalidArgumentException('无效的 $class 参数值 ' . $class);
        }
        $this->class = $class;

        return $this;
    }

    /**
     * 设置要查询的 ip 地址
     *
     * @param $ip
     * @return $this
     */
    public final function setIp($ip)
    {
        $ip = trim($ip);
        if (empty($ip)) {
            throw new InvalidArgumentException('ip 不能为空。');
        }
        $this->ip = $ip;

        return $this;
    }

    /**
     * @param $object
     * @return array
     * @throws \ReflectionException
     */
    private function _toArray($object)
    {
        $reflectionClass = new \ReflectionClass($object);
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($object);
            if (is_object($value)) {
                $array[$property->getName()] = $this->_toArray($value);
            } else {
                $array[$property->getName()] = $value;
            }
        }

        return $array;
    }

    /**
     * @return IP
     */
    public function detect()
    {
        if (!$this->_ipObject) {
            $this->_ipObject = (new $this->class)->detect($this->ip);
        }

        return $this->_ipObject;
    }

    /**
     * 返回数组
     *
     * @return array
     * @throws \ReflectionException
     */
    public function toArray()
    {
        return $this->_toArray($this->detect());
    }

    /**
     * 返回 JSON 格式
     *
     * @param null $options
     * @return false|string
     * @throws \ReflectionException
     */
    public function toJson($options = null)
    {
        return json_encode($this->toArray(), $options);
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
    private $success = false;

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
     * @param null $default
     * @return mixed
     */
    public function getCountryId($default = null)
    {
        return $this->countryId ?: $default;
    }

    /**
     * @param mixed $countryId
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;
    }

    /**
     * @param null $default
     * @return mixed
     */
    public function getCountryName($default = null)
    {
        return $this->countryName ?: $default;
    }

    /**
     * @param mixed $countryName
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
    }

    /**
     * @param null $default
     * @return mixed
     */
    public function getAreaId($default = null)
    {
        return $this->areaId ?: $default;
    }

    /**
     * @param mixed $areaId
     */
    public function setAreaId($areaId)
    {
        $this->areaId = $areaId;
    }

    /**
     * @param null $default
     * @return mixed
     */
    public function getAreaName($default = null)
    {
        return $this->areaName ?: $default;
    }

    /**
     * @param mixed $areaName
     */
    public function setAreaName($areaName)
    {
        $this->areaName = $areaName;
    }

    /**
     * @param null $default
     * @return mixed
     */
    public function getProvinceId($default = null)
    {
        return $this->provinceId ?: $default;
    }

    /**
     * @param mixed $provinceId
     */
    public function setProvinceId($provinceId)
    {
        $this->provinceId = $provinceId;
    }

    /**
     * @param null $default
     * @return mixed
     */
    public function getProvinceName($default = null)
    {
        return $this->provinceName ?: $default;
    }

    /**
     * @param mixed $provinceName
     */
    public function setProvinceName($provinceName)
    {
        $this->provinceName = $provinceName;
    }

    /**
     * @param null $default
     * @return mixed
     */
    public function getCityId($default = null)
    {
        return $this->cityId ?: $default;
    }

    /**
     * @param mixed $cityId
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;
    }

    /**
     * @param null $default
     * @return mixed
     */
    public function getCityName($default = null)
    {
        return $this->cityName ?: $default;
    }

    /**
     * @param mixed $cityName
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
    }

    /**
     * @param null $default
     * @return mixed
     */
    public function getIspId($default = null)
    {
        return $this->ispId ?: $default;
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

    public function setSuccess($success = true)
    {
        $this->success = $success ? true : false;
    }

    public function getSuccess()
    {
        return $this->success;
    }

}

class TaobaoIpLocationHelper implements IIpLocationHelper
{

    public function detect($ipAddress)
    {
        $ip = new IP();
        $ip->setIp($ipAddress);
        $response = @file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=$ipAddress");
        if ($response !== false) {
            $response = json_decode($response, true);
            if ($response && isset($response['code']) && $response['code'] == 0) {
                $body = $response['data'];
                $ip->setSuccess(true);
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

class PcOnlineIpLocationHelper implements IIpLocationHelper
{

    public function detect($ipAddress)
    {
        $ip = new IP();
        $ip->setIp($ipAddress);
        $response = file_get_contents("http://whois.pconline.com.cn/ipJson.jsp?ip=$ipAddress");
        if ($response !== false) {
            $response = str_replace(['if(window.IPCallBack) {IPCallBack(', ');}'], '', $response);
            $response = json_decode($response, true);
            if ($response && empty($response['err'])) {
                $ip->setProvinceId(isset($response['proCode']) ? $response['proCode'] : null);
                $ip->setProvinceName(isset($response['pro']) ? $response['pro'] : null);
                $ip->setCityId(isset($response['cityCode']) ? $response['cityCode'] : null);
                $ip->setCityName(isset($response['city']) ? $response['city'] : null);
            }
        }

        return $ip;
    }

}

/**
 * Class CZ88IpHelper
 * 纯真 IP 库离线查询
 *
 * @package yadjet\helpers
 */
class CZ88IpLocationHelper implements IIpLocationHelper
{

    public function detect($ipAddress)
    {
        $ip = new IP();
        $ip->setIp($ipAddress);
        $response = IpLocation::getLocation($ipAddress);
        if (!isset($response['error'])) {
            $ip->setSuccess(true);
            $ip->setCountryName($response['country']);
            $ip->setProvinceName($response['province']);
            $ip->setCityName($response['city']);
            $ip->setAreaName($response['area']);
            $ip->setIspName($response['isp']);
        }

        return $ip;
    }

}