<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrganizationRepository
{
    public function getSuccessPurchaseForMonth($organization, $currentMonth)
    {
        return DB::table('organizations as o')
            ->join('purchases as p', 'p.organization_id', '=', 'o.id')
            ->join('purchase_statuses as status', 'status.id', '=', 'p.status')
            ->where('o.id', '=', $organization)
            ->select(
                DB::raw('DATE_FORMAT(p.purchase_end_datetime, "%Y-%m") as month'),
                DB::raw('SUM(p.starting_price) as sum_price')
            )
            ->where('p.purchase_end_datetime', '<=', $currentMonth)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
    }

    public function forDiagram($organization)
    {
        return DB::table('purchases as p')
            ->join('purchase_statuses as status', 'status.id', '=', 'p.status')
            ->join('organizations as o', 'o.id', 'p.organization_id')
            ->select('status.title as status', DB::raw('COUNT(*) as count'))
            ->where('o.id', '=', $organization)
            ->groupBy('status.id')
            ->get();
    }
}
