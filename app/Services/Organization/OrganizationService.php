<?php

namespace App\Services\Organization;

use App\Models\Organization;
use App\Repositories\OrganizationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class OrganizationService
{
    protected OrganizationRepository $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function search(Request $request): Collection
    {
        $organization = DB::table('organizations');
        if($request->name)
        {
            $organization = $organization->where('name', $request->name);
        }
        if($request->inn)
        {
            $organization = $organization->where('inn', $request->inn);
        }
        if($request->okpo)
        {
            $organization = $organization->where('okpo', $request->okpo);
        }
        if($request->registration_number)
        {
            $organization = $organization->where('okpo', $request->registration_number);
        }

        return $organization->paginate(20);
    }

    public function getSuccessForMonth($organization, $currentMonth)
    {
        $report = $this->organizationRepository->getSuccessPurchaseForMonth($organization, $currentMonth);
        $months = [];
        $reportData = [];

        for ($i = 0; $i < 5; $i++) {
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

    public function diagram($organization)
    {
        return $this->organizationRepository->forDiagram($organization);

    }
}
