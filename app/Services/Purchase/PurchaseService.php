<?php

namespace App\Services\Purchase;

use App\Models\Category;
use App\Models\Organization;
use App\Models\Purchase;
use App\Models\PurchaseStatus;
use DateTime;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function saveErrorPurchase(array $data): void
    {
        $organization = Organization::where('id', $data['customer']['id'])->first();
        $status = $data['status'];
        try{
            DB::beginTransaction();
            $status = PurchaseStatus::where('id_', $status['id'])
                ->where('stage', $status['stage'])->first();
            if(!$status)
            {
                $purchase_data['status']['id_'] = $data['status']['id'];
                unset($purchase_data['status']['id']);
                $status = PurchaseStatus::create([
                    'id_' => $data['status']['id_'],
                    'title' => $data['status']['title'],
                    'stage' => $data['status']['stage'],
                ]);
            }

            $category = Category::where('name', $data['deliverable_group_title'])->first();
            if(!$category)
            {
                $category = Category::create(['name' => $data['deliverable_group_title']]);
            }
            $datetime = new DateTime($purchase_data['purchase_end_datetime']);
            $data['purchase_end_datetime'] = $datetime->format('Y-m-d H:i:s');
            $sava_data = [
                'id' => $data['id'],
                'product' => $this->convertArrayToJson($data['product']),
                'actions' => $this->convertArrayToJson($data['actions']),
                'registration_number' => $data['registration_number'],
                'name' => $data['name'],
                'starting_price' => $data['starting_price'],
                'currency_title' => $data['currency_title'],
                'deliverable_group_title' => $data['deliverable_group_title'],
                'with_lots' => $data['with_lots'],
                'fee_amount' => $data['fee_amount'],
                'status' => $status->id,
                'category' => $category->id,
                'organization_id' => $organization->id,
                'purchase_end_datetime' => $data['purchase_end_datetime']

            ];
            Purchase::create($sava_data);
            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
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
