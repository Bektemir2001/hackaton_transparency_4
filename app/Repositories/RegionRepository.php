<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class RegionRepository
{
    public function getSuccessPurchaseForMonth($region, $currentMonth)
    {
        return DB::table('organizations as o')
            ->join('purchases as p', 'p.organization_id', '=', 'o.id')
            ->join('purchase_statuses as status', 'status.id', '=', 'p.status')
            ->select(
                DB::raw('DATE_FORMAT(p.purchase_end_datetime, "%Y-%m") as month'),
                DB::raw('SUM(p.starting_price) as sum_price')
            )
            ->where('p.purchase_end_datetime', '<=', $currentMonth)
            ->where(DB::raw('LOWER(o.legal_address)'), 'like', '%' . $region . '%')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
    }
}
