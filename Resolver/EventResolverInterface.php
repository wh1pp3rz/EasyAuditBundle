<?php

/*
 * This file is part of the XiideaEasyAuditBundle package.
 *
 * (c) Xiidea <http://www.xiidea.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Xiidea\EasyAuditBundle\Resolver;

use Symfony\Component\EventDispatcher\Event;

interface EventResolverInterface
{
    /**
     * @param Event $event
     * @param string $eventName
     * @return mixed
     */
    public function getEventLogInfo(Event $event, $eventName);
}
