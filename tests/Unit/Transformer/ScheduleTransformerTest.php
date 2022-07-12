<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Schedule;
use App\Service\PriceListService;
use App\Transformer\ScheduleTransformer;
use PHPUnit\Framework\TestCase;

class ScheduleTransformerTest extends TestCase
{
    public function testToArray(): void
    {
        $schedule = $this->getMockBuilder(Schedule::class)->disableOriginalConstructor()->getMock();
        $schedule->expects($this->once())->method('getTicketRemain')->willReturn(50);
        $schedule->expects($this->once())->method('getStartDate')->willReturn(new \DateTime());
        $priceListServiceMock = $this->getMockBuilder(PriceListService::class)->disableOriginalConstructor()->getMock();
        $priceListServiceMock->expects($this->once())->method('getTicketType')->willReturn([]);
        $scheduleTransformer = new ScheduleTransformer($priceListServiceMock);
        $result = $scheduleTransformer->toArray($schedule);
        $this->assertEquals([
            'id' => null,
            'ticket' => [],
            'ticketRemain' => 50,
            'startDate' => '2022-07-12'
        ], $result);
    }

}
