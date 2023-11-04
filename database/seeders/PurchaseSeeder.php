<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Organization;
use App\Models\Purchase;
use App\Models\PurchaseStatus;
use App\Services\Purchase\PurchaseQueryService;
use App\Services\Purchase\PurchaseService;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseSeeder extends Seeder
{
    protected PurchaseQueryService $purchaseQueryService;
    protected PurchaseService $purchaseService;
    public function __construct(PurchaseQueryService $purchaseQueryService, PurchaseService $purchaseService)
    {
        $this->purchaseQueryService = $purchaseQueryService;
        $this->purchaseService = $purchaseService;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $i = 1;
        while (true)
        {
            $data = $this->purchaseQueryService->getPurchases($i);
            if(count($data) == 0) break;
            foreach ($data['data'] as $item)
            {
                $purchase = $this->purchaseQueryService->getPurchase($item['id']);
                if(isset($purchase['error']))
                {
                    $this->purchaseService->saveErrorPurchase($item);
                    continue;
                }
                $organization = Organization::where('id', $purchase['data']['customer']['id'])->first();
                $purchase_data = $purchase['data'];
                $status = $purchase_data['status'];
                try{
                    DB::beginTransaction();
                    $status = PurchaseStatus::where('id_', $status['id'])
                        ->where('stage', $status['stage'])->first();
                    if(!$status)
                    {
                        $purchase_data['status']['id_'] = $purchase_data['status']['id'];
                        unset($purchase_data['status']['id']);
                        $status = PurchaseStatus::create([
                            'id_' => $purchase_data['status']['id_'],
                            'title' => $purchase_data['status']['title'],
                            'stage' => $purchase_data['status']['stage'],
                        ]);
                    }

                    $category = Category::where('name', $purchase_data['deliverable_group_title'])->first();
                    if(!$category)
                    {
                        $category = Category::create(['name' => $purchase_data['deliverable_group_title']]);
                    }
                    $datetime = new DateTime($purchase_data['purchase_start_datetime']);
                    $purchase_data['purchase_start_datetime'] = $datetime->format('Y-m-d H:i:s');
                    $datetime = new DateTime($purchase_data['purchase_end_datetime']);
                    $purchase_data['purchase_end_datetime'] = $datetime->format('Y-m-d H:i:s');
                    $sava_data = [
                        'id' => $purchase_data['id'],
                        'edms' => $this->convertArrayToJson($purchase_data['edms']),
                        'supplier_requirements' => $this->convertArrayToJson($purchase_data['supplier_requirements']),
                        'deliverables' => $this->convertArrayToJson($purchase_data['deliverables']),
                        'product' => $this->convertArrayToJson($purchase_data['product']),
                        'contract_draft_attachment' => $this->convertArrayToJson($purchase_data['contract_draft_attachment']),
                        'notice_supplementary_attachments' => $this->convertArrayToJson($purchase_data['notice_supplementary_attachments']),
                        'actions' => $this->convertArrayToJson($purchase_data['actions']),
                        'registration_number' => $purchase_data['registration_number'],
                        'name' => $purchase_data['name'],
                        'additional_contact_info' => $purchase_data['additional_contact_info'],
                        'cancellation_info' => $purchase_data['cancellation_info'] != null ? $this->convertArrayToJson($purchase_data['cancellation_info']) : null,
                        'starting_price' => $purchase_data['starting_price'],
                        'currency_title' => $purchase_data['currency_title'],
                        'deliverable_group_title' => $purchase_data['deliverable_group_title'],
                        'with_lots' => $purchase_data['with_lots'],
                        'proposal_validity_days' => $purchase_data['proposal_validity_days'],
                        'envelopes_opening_protocol' => isset($purchase_data['envelopes_opening_protocol']) ? $this->convertArrayToJson($purchase_data['envelopes_opening_protocol']) : null,
                        'fee_amount' => $purchase_data['fee_amount'],
                        'proposals' => isset($purchase_data['proposals']) ? $this->convertArrayToJson($purchase_data['proposals']) : null,
                        'status' => $status->id,
                        'category' => $category->id,
                        'organization_id' => $organization->id,
                        'purchase_start_datetime' => $purchase_data['purchase_start_datetime'],
                        'purchase_end_datetime' => $purchase_data['purchase_end_datetime']

                    ];
                    Purchase::create($sava_data);
                    DB::commit();
                }
                catch (\Exception $e)
                {
                    DB::rollBack();
                }

            }
            $i += 1;

        }
    }

    public function convertArrayToJson($array) {
        if ($array == null) return null;
        array_walk_recursive($array, function (&$item) {
            if (is_array($item)) {
                $item = convertArrayToJson($item); // Recursively convert nested arrays
            }
        });
        return json_encode($array);
    }
}
