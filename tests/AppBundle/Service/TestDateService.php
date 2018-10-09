<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jacik2
 * Date: 29.09.18
 * Time: 23:31
 * To change this template use File | Settings | File Templates.
 */

namespace tests\AppBundle\Service;

use AppBundle\Service\DateService;

class TestDateService extends \PHPUnit\Framework\TestCase
{
    public function testGetDay()
    {
        $dateService = new DateService();

        //w piwerszym par oczekiwana
        $this->assertEquals(1, $dateService->getDay(new \DateTime("2013-02-1")), "Powinien być zwracany dzień 1");
        $this->assertEquals(7, $dateService->getDay(new \DateTime("2012-04-7")), "Powinien być zwracany dzień 7");
        $this->assertEquals(23, $dateService->getDay(new \DateTime("2013-09-23")), "Powinien być zwracany dzień 23");
    }
}