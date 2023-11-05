<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Purchase;
use App\Models\PurchaseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function get(Request $request)
    {
        $purchases = DB::table('purchases as p')
        ->join('purchase_statuses as status', 'status.id', '=', 'p.status')
        ->join('categories as c', 'c.id', '=', 'p.category')
        ->join('organizations as o', 'o.id', '=', 'p.organization_id')
        ->select('p.name', 'p.registration_number', 'p.additional_contact_info',
        'p.starting_price', 'p.currency_title', 'p.purchase_start_datetime', 'p.purchase_end_datetime', 'p.proposal_validity_days', 'p.fee_amount', 'status.title as status',
        'c.name as category', 'o.name as organization', 'p.id','p.gpt_analyze',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(p.product, '$.title')) as product"))
        ->where('p.name', '!=', null);
        if($request->categoryId)
        {
            if($request->categoryId != 0){
                $purchases->where('c.id', $request->categoryId);
            }
        }
        if($request->status)
        {
            $purchases->where('status.title', $request->status);
        }
        return response($purchases->paginate(20));
    }

    public function getOne(Purchase $purchase)
    {
        $purchase->edms = $purchase->edms != null ? json_decode($purchase->edms) : null;
        $purchase->actions = $purchase->actions != null ? json_decode($purchase->actions) : null;
        $purchase->deliverables = $purchase->deliverables != null ? json_decode($purchase->deliverables) : null;
        $purchase->product = $purchase->product != null ? json_decode($purchase->product) : null;
        $purchase->proposals = $purchase->proposals != null ? json_decode($purchase->proposals) : null;
        $purchase->envelopes_opening_protocol = $purchase->envelopes_opening_protocol != null ? json_decode($purchase->envelopes_opening_protocol) : null;
        $purchase->notice_supplementary_attachments = $purchase->notice_supplementary_attachments != null ? json_decode($purchase->notice_supplementary_attachments) : null;
        $purchase->contract_draft_attachment = $purchase->contract_draft_attachment != null ? json_decode($purchase->contract_draft_attachment) : null;
        $purchase->supplier_requirements = $purchase->supplier_requirements != null ? json_decode($purchase->supplier_requirements) : null;
        $purchase->status = PurchaseStatus::where('id', $purchase->status)->first();
        $purchase->organization = Organization::where('id', $purchase->organization_id)->first();
        return response($purchase);
    }

}
