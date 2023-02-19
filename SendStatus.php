<?php

/*************************************************************************************/
/*      Copyright (c) Vincent Bourbon                                                */
/*      email : dev@vincentbourbon.fr                                                */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace SendStatus;

use Thelia\Module\BaseModule;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Model\Message;
use Thelia\Model\MessageQuery;

class SendStatus extends BaseModule
{
    const PARTIAL_CONFIRMATION_MESSAGE_NAME = 'send_status';

    public function postActivation(ConnectionInterface $con = null): void
    {
        if (null === MessageQuery::create()->findOneByName(self::PARTIAL_CONFIRMATION_MESSAGE_NAME)) {
            $message = new Message();

            $message
                ->setName(self::PARTIAL_CONFIRMATION_MESSAGE_NAME)
                ->setHtmlLayoutFileName('status_changed.html')
                ->setTextLayoutFileName('status_changed.txt')
                ->setLocale('en_US')
                ->setTitle('Partial order send confirmation')
                ->setSubject('Partial order send confirmation')

                ->setLocale('fr_FR')
                ->setTitle('Confirmation d\'envoi partiel')
                ->setSubject('Confirmation d\'envoi partiel')

                ->save();
        }
    }
}
