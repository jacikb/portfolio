<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jacik2
 * Date: 29.09.18
 * Time: 23:59
 * To change this template use File | Settings | File Templates.
 */

namespace tests\AppBundle\Twig;

use AppBundle\Twig\DateExtension;

class TestDateExtension extends \PHPUnit\Framework\TestCase
{
    public function testGetStyle()
    {
        $dateExtension = new DateExtension();

        $this->assertEquals("panel-default", $dateExtension->auctionStyle(new \DateTime("+2 days")), "powinno byc panel-default");
        $this->assertEquals("panel-danger", $dateExtension->auctionStyle(new \DateTime("+20 minutes")), "powinno byc panel-danger");
    }

}