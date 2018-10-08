<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jacik2
 * Date: 29.09.18
 * Time: 20:25
 * To change this template use File | Settings | File Templates.
 */

namespace AppBundle\Service;


class DateService {

    public function getDay(\DateTime $date)
    {
        return $date->format("d");
    }
}