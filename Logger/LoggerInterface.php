<?php

/*
 * This file is part of the XiideaEasyAuditBundle package.
 *
 * (c) Xiidea <http://www.xiidea.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Xiidea\EasyAuditBundle\Logger;

use Xiidea\EasyAuditBundle\Document\BaseAuditLog;

interface LoggerInterface
{
    /**
     * @param BaseAuditLog $event
     * @return void
     */
    public function log(BaseAuditLog $event);
}
