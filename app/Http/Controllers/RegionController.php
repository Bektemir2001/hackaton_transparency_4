<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Services\RegionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegionController
{
    protected RegionService $regionService;

    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    public function graphicSuccessForMonth($region, Request $request)
    {
        $currentMonth = Carbon::now();
        if($request->month)
        {
            $monthsToSubtract = (int)$request->month;
            $currentMonth->subMonths($monthsToSubtract);
        }
        return response(['data' => $this->regionService->getSuccessForMonth($region, $currentMonth)]);
    }
}
