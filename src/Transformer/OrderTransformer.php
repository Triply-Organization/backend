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
        OrderService $orderService
    ) {
        $this->orderService = $orderService;
        $this->params = $params;
    }

    public function toArray(Order $order): array
    {
        $result = $this->transform($order, static::PARAMS);
        $result['user'] = $order->getUser()->getId();
        $firstTicket = $this->orderService->findOneTicketOfOrder($order);
        $result['tourId'] = $firstTicket->getPriceList()->getSchedule()->getTour()->getId();
        $result['tourTitle'] = $firstTicket->getPriceList()->getSchedule()->getTour()->getTitle();
        $result['startDay'] = $firstTicket->getPriceList()->getSchedule()->getStartDate();
        $result['duration'] = $firstTicket->getPriceList()->getSchedule()->getTour()->getDuration();
        $images = $firstTicket->getPriceList()->getSchedule()->getTour()->getTourImages();
        foreach ($images as $image) {
            $result['imageTour']['path'] = $this->params->get('s3url') . $image->getImage()->getPath();
            $result['imageTour']['type'] = $image->getType();
        }
        $ticketsOfOrder = $this->orderService->findTicketsOfOrder($order);
        foreach ($ticketsOfOrder as $key => $ticket) {
            $ticketInfos[$key]['idTicket'] = $ticket->getId();
            $ticketInfos[$key]['amount'] = $ticket->getAmount();
            $ticketInfos[$key]['typeTicket'] = $ticket->getPriceList()->getType()->getName();
            $ticketInfos[$key]['priceTick'] = $ticket->getPriceList()->getPrice();
        }
        $result['tickets'] =  $this->ticketInfoReturn($ticketInfos);

        $result['discount'] = null;
        if ($order->getDiscount() !== null) {
            $result['discount']['id'] = $order->getDiscount()->getId();
            $result['discount']['code'] = $order->getDiscount()->getCode();
        }
        $result['subTotal'] = $order->getTotalPrice();
        return $result;
    }

    private function ticketInfoReturn(array $ticketInfos): array
    {
        $arrayResult = [];
        foreach ($ticketInfos as $ticketInfo) {
            if ($ticketInfo['typeTicket'] === 'children') {
                $arrayResult[1]['idTicket'] = $ticketInfo['idTicket'];
                $arrayResult[1]['amount'] = $ticketInfo['amount'];
                $arrayResult[1]['typeTicket'] = $ticketInfo['typeTicket'];
                $arrayResult[1]['priceTick'] = $ticketInfo['priceTick'];
            }
            if ($ticketInfo['typeTicket'] === 'young') {
                $arrayResult[2]['idTicket'] = $ticketInfo['idTicket'];
                $arrayResult[2]['amount'] = $ticketInfo['amount'];
                $arrayResult[2]['typeTicket'] = $ticketInfo['typeTicket'];
                $arrayResult[2]['priceTick'] = $ticketInfo['priceTick'];
            }
            if ($ticketInfo['typeTicket'] === 'adult') {
                $arrayResult[3]['idTicket'] = $ticketInfo['idTicket'];
                $arrayResult[3]['amount'] = $ticketInfo['amount'];
                $arrayResult[3]['typeTicket'] = $ticketInfo['typeTicket'];
                $arrayResult[3]['priceTick'] = $ticketInfo['priceTick'];
            }
        }

        return $arrayResult;
    }
}
