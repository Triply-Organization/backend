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
        $result['scheduleId'] = $firstTicket->getPriceList()->getSchedule()->getId();
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
            $result['discount']['value'] = $order->getDiscount()->getDiscount();
        }
        $result['tax'] = null;
        if ($order->getTax() !== null) {
            $result['tax']['id'] = $order->getTax()->getId();
            $result['tax']['currency'] = $order->getTax()->getCurrency();
            $result['tax']['percent'] = $order->getTax()->getPercent();
        }
        $result['subTotal'] = $order->getTotalPrice();
        $result['status'] = $order->getStatus();
        return $result;
    }

    private function ticketInfoReturn(array $ticketInfos): array
    {
        $arrayResult = [];
        foreach ($ticketInfos as $ticketInfo) {
            if ($ticketInfo['typeTicket'] === 'children') {
                $arrayResult['children']['idTicket'] = $ticketInfo['idTicket'];
                $arrayResult['children']['amount'] = $ticketInfo['amount'];
                $arrayResult['children']['priceTick'] = $ticketInfo['priceTick'];
            }
            if ($ticketInfo['typeTicket'] === 'youth') {
                $arrayResult['youth']['idTicket'] = $ticketInfo['idTicket'];
                $arrayResult['youth']['amount'] = $ticketInfo['amount'];
                $arrayResult['youth']['priceTick'] = $ticketInfo['priceTick'];
            }
            if ($ticketInfo['typeTicket'] === 'adult') {
                $arrayResult['adult']['idTicket'] = $ticketInfo['idTicket'];
                $arrayResult['adult']['amount'] = $ticketInfo['amount'];
                $arrayResult['adult']['priceTick'] = $ticketInfo['priceTick'];
            }
        }

        return $arrayResult;
    }
}
