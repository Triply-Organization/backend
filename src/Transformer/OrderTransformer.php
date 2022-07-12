<?php

namespace App\Transformer;

use App\Entity\Order;
use App\Service\OrderService;
use App\Service\VoucherService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OrderTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'amount'];
    private const USER_PARAMS = ['id', 'amount', 'status', 'totalPrice'];
    private OrderService $orderService;
    private VoucherService $voucherService;
    private ParameterBagInterface $params;

    public function __construct(
        ParameterBagInterface $params,
        OrderService          $orderService,
        VoucherService        $voucherService,
    )
    {
        $this->orderService = $orderService;
        $this->params = $params;
        $this->voucherService = $voucherService;
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
        $result['tickets'] = $this->ticketInfoReturn($ticketInfos);
        $result['subTotal'] = $order->getTotalPrice();
        $result['status'] = $order->getStatus();

        return $result;
    }

    public function getOrderOfUser(Order $order): ?array
    {
        $result = $this->transform($order, static::USER_PARAMS);
        if ($order->getTickets()->first() === false) {
            return null;
        }
        $result['title'] = $order->getTickets()->first()->getPriceList()->getSchedule()->getTour()->getTitle();
        $result['bookedAt'] = $order->getCreatedAt();
        $result['startDay'] = $order->getTickets()->first()->getPriceList()->getSchedule()->getStartDate();
        $images = $order->getTickets()->first()->getPriceList()->getSchedule()->getTour()->getTourImages();

        foreach ($images as $image) {
            if ($image->getType() === 'cover') {
                $result['cover'] = $this->params->get('s3url') . $image->getImage()->getPath();
            }
        }
        $review = $order->getReview();
        if ($review) {
            $result['review']['comment'] = $review->getComment();
            foreach ($review->getReviewDetails() as $key => $detail) {
                $result['review'][$key]['name'] = $detail->getType()->getName();
                $result['review'][$key]['rate'] = $detail->getRate();
            }
        }
        if ($order->getBill() !== null) {
            $result['bill']['id'] = $order->getBill()->getId();
            $result['bill']['totalPrice'] = $order->getBill()->getTotalPrice();
            $result['bill']['currency'] = $order->getBill()->getCurrency();
            $result['bill']['tax'] = $order->getBill()->getTax();
            $result['bill']['discount'] = $order->getBill()->getDiscount();
            $result['bill']['stripe'] = $order->getBill()->getStripePaymentId();
        }
        return $result;
    }

    public function orderToArray(Order $order)
    {
        $result = $this->toArray($order);
        foreach ($this->voucherService->getAllDisCount() as $key => $voucher) {
            $result['voucher'][$key]['id'] = $voucher->getId();
            $result['voucher'][$key]['code'] = $voucher->getCode();
            $result['voucher'][$key]['discount'] = $voucher->getDiscount();
            $result['voucher'][$key]['remain'] = $voucher->getRemain();
        }
        $result['tax'] = null;
        if ($order->getTax() !== null) {
            $result['tax']['id'] = $order->getTax()->getId();
            $result['tax']['currency'] = $order->getTax()->getCurrency();
            $result['tax']['percent'] = $order->getTax()->getPercent();
        }

        return $result;
    }

    public function detailToArray(Order $order)
    {
        $result = $this->toArray($order);
        if ($order->getBill() !== null) {
            $result['bill']['id'] = $order->getBill()->getId();
            $result['bill']['totalPrice'] = $order->getBill()->getTotalPrice();
            $result['bill']['currency'] = $order->getBill()->getCurrency();
            $result['bill']['tax'] = $order->getBill()->getTax();
            $result['bill']['discount'] = $order->getBill()->getDiscount();
            $result['bill']['stripe'] = $order->getBill()->getStripePaymentId();
        }
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
