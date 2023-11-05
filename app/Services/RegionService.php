<?php

namespace App\Services;

use App\Repositories\RegionRepository;

class RegionService
{
    protected static $SITIES = ['бишкек', 'ош', 'жалал-абад', 'нарын'];
    protected RegionRepository $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    public function getSuccessForMonth($region, $currentMonth)
    {
        $report = $this->regionRepository->getSuccessPurchaseForMonth($region, $currentMonth);
        $months = [];
        $reportData = [];

        for ($i = 0; $i < 8; $i++) {
            $month = $currentMonth->format('Y-m');
            $months[] = $month;
            $reportData[$month] = 0;
            $currentMonth->subMonth();
        }
        foreach ($report as $item) {
            $month = $item->month;
            $reportData[$month] = $item->sum_price;
        }
        foreach ($months as $month) {
            $finalReport[] = ['month' => $month, 'sum_price' => $reportData[$month]];
        }

        return array_reverse($finalReport);
    }
}
