<?php

namespace App\Transformer;

use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OrderTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'amount'];

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function toArray(Ticket $order): array
    {
        $result = $this->transform($order, static::PARAMS);
        $result['user'] = $order->getUser()->getId();
        $result['tour'] = $order->getTicket()->getSchedule()->getTour()->getTitle();
        $result['startDay'] = $order->getTicket()->getSchedule()->getStartDate();
        $result['price'] = $order->getTicket()->getPrice() * $result['amount'];
        $result['duration'] = $order->getTicket()->getSchedule()->getTour()->getDuration();
        $images = $order->getTicket()->getSchedule()->getTour()->getTourImages();
        foreach ($images as $image) {
            $result['image'] = $this->params->get('s3url') . $image->getImage()->getPath();
        }

        return $result;
    }

    public function result($orderTransformer, $orderService)
    {
        if (key_exists('children', $orderService)) {
            $result['children'] = $orderTransformer->toArray($orderService['children']);
        }
        if (key_exists('youth', $orderService)) {
            $result['youth'] = $orderTransformer->toArray($orderService['youth']);
        }
        if (key_exists('adult', $orderService)) {
            $result['adult'] = $orderTransformer->toArray($orderService['adult']);
        }
        $result['totalPrice'] = $result['children']['price'] ?? 0 +
            $result['youth']['price'] ?? 0 +
            $result['adult']['price'] ?? 0;
        $result['VAT'] = 0.3;
        $result['subTotal'] = $result['totalPrice'] + $result['VAT'] * $result['totalPrice'];

        return $result;
    }
}
