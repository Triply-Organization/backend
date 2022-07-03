<?php

namespace App\Transformer;

use App\Entity\Order;
use App\Service\OrderService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OrderTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'amount'];
    private OrderService $orderService;

    public function __construct(
        ParameterBagInterface $params,
        OrderService          $orderService
    )
    {
        $this->orderService = $orderService;
        $this->params = $params;
    }

    public function toArray(Order $order): array
    {
        $result = $this->transform($order, static::PARAMS);
        $result['user'] = $order->getUser()->getId();
        $firstTicket = $this->orderService->findOneTicketOfOrder($order);
        $result['tourTitle'] = $firstTicket->getPriceList()->getSchedule()->getTour()->getTitle();
        $result['startDay'] = $firstTicket->getPriceList()->getSchedule()->getStartDate();
        $result['duration'] = $firstTicket->getPriceList()->getSchedule()->getTour()->getDuration();
        $images = $firstTicket->getPriceList()->getSchedule()->getTour()->getTourImages();
        foreach ($images as $image) {
            $result['imageTour']['path'] = $this->params->get('s3url') . $image->getImage()->getPath();
            $result['imageTour']['type'] = $image->getType();
        }
        $ticketsOfOrder = $this->orderService->findTicketsOfOrder($order);
        $subTotal = 0;
        foreach ($ticketsOfOrder as $key => $ticket) {
            $result['tickets'][$key]['idTicket'] = $ticket->getId();
            $result['tickets'][$key]['amount'] = $ticket->getAmount();
            $result['tickets'][$key]['typeTicket'] = $ticket->getPriceList()->getType()->getName();
            $result['tickets'][$key]['priceTick'] = $ticket->getPriceList()->getPrice();
            $subTotal = $subTotal + $ticket->getPriceList()->getPrice();
        }
        $result['discount'] =null;
        if($order->getDiscount() !== null){
            $result['discount']['id'] = $order->getDiscount()->getId();
            $result['discount']['code'] = $order->getDiscount()->getCode();
        }
        $result['subTotal'] = $subTotal;
        return $result;
    }
}
