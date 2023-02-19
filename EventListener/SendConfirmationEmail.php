<?php

/*************************************************************************************/
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace SendStatus\EventListener;

use SendStatus\SendStatus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Log\Tlog;
use Thelia\Mailer\MailerFactory;
use Thelia\Model\OrderQuery;
use Thelia\Model\OrderStatusQuery;
use Thelia\Model\ConfigQuery;

use Thelia\Model\MessageQuery;

/**
 * Class SendConfirmationEmail
 *
 * @package SendStatus\EventListeners
 * @author  Franck Allimant <franck@cqfdev.fr>
 */
class SendConfirmationEmail implements EventSubscriberInterface
{
    /** @var MailerFactory */
    protected $mailer;

    public function __construct(MailerFactory $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Checks if order payment module is paypal and if order new status is paid, send an email to the customer.
     *
     * @param OrderEvent $event
     * @param $eventName
     * @param EventDispatcherInterface $dispatcher
     * @throws \Propel\Runtime\Exception\PropelException
     */
     
     
    public function updateOrderStatus(OrderEvent $event)
    {
        $order = $event->getOrder();
        $orderStatus = $order->getOrderStatus()->getId();
        $customer = $order->getCustomer();
        $package = $order->getDeliveryRef();

        $message = MessageQuery::create()   
        ->filterByName('PARTIAL_CONFIRMATION_MESSAGE_NAME')
                    ->findOne();

        if ($orderStatus === 7) {

            $email = $this->mailer->createEmailMessage(
                SendStatus::PARTIAL_CONFIRMATION_MESSAGE_NAME,
                [ConfigQuery::getStoreEmail() => ConfigQuery::getStoreName()],
                [$customer->getEmail() => $customer->getFirstname() . " " . $customer->getLastname()],
                [
                    'order_id' => $event->getOrder()->getId(),
                    'order_ref' => $event->getOrder()->getRef(),
                    'package' => $order->getDeliveryRef()
                    
                ]
            );

            $this->mailer->send($email);

        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            TheliaEvents::ORDER_UPDATE_STATUS           => ['updateOrderStatus', 128],
            //TheliaEvents::ORDER_SEND_CONFIRMATION_EMAIL => ['checkSendEmail', 130],
        );
    }
}
