<?php

/*
 * This file is part of the XiideaEasyAuditBundle package.
 *
 * (c) Xiidea <http://www.xiidea.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Xiidea\EasyAuditBundle\Resolver\UserEventCommand;

use Symfony\Component\EventDispatcher\Event;

abstract class ResolverCommand
{
    /**
     * @param string $username
     * @return array
     */
    protected function getEventDetailsArray($username)
    {
        return [
            'type'        => $this->getType(),
            'description' => sprintf($this->getTemplate(), $username)
        ];
    }

    /**
     * @param Event $event
     * @return mixed
     */
    abstract public function resolve($event);

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return string
     */
    abstract public function getTemplate();
}
