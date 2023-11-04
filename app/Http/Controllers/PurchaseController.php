<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function get()
    {
        $purchases = DB::table('purchases as p')
        ->join('purchase_statuses as status', 'status.id', '=', 'p.status')
        ->join('categories as c', 'c.id', '=', 'p.category')
        ->join('organizations as o', 'o.id', '=', 'p.organization_id')
        ->select('p.name', 'p.registration_number', 'p.additional_contact_info',
        'p.starting_price', 'p.currency_title', 'p.purchase_start_datetime', 'p.purchase_end_datetime',
        'p.deliverable_group_title', 'p.proposal_validity_days', 'p.fee_amount', 'status.title as status',
        'c.name as category', 'o.name as organization', 'p.id')
        ->paginate(20);
        return response($purchases);
    }



}
