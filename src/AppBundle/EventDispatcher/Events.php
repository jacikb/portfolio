<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jacik2
 * Date: 30.09.18
 * Time: 12:06
 * To change this template use File | Settings | File Templates.
 */

namespace AppBundle\EventDispatcher;


class Events {
    const AUCTION_ADD = "auction_add";
    const AUCTION_EDIT = "auction_edit";
    const AUCTION_DELETE = "auction_delete";
    const AUCTION_FINISH = "auction_finish";
    const AUCTION_EXPIRE = "auction_expire";

}