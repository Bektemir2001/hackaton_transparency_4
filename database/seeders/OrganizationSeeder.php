<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Services\Organization\OrganizationQueryService;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    private OrganizationQueryService $organizationQueryService;
    public function __construct(OrganizationQueryService $organizationQueryService)
    {
        $this->organizationQueryService = $organizationQueryService;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $i = 1;
        while (true)
        {
            $data = $this->organizationQueryService->getOrganizations($i);
            if(count($data) == 0)
            {
                break;
            }
            foreach ($data['data'] as $item)
            {
                $organization = $this->organizationQueryService->getOrganization($item['id']);

                $datetime = new DateTime($organization['data']['registration_date']);
                $organization['data']['registration_date'] = $datetime->format('Y-m-d H:i:s');
                Organization::insert($organization['data']);
            }
            $i += 1;
            if($i % 9 == 0){
                sleep(1);
            }
        }
    }
}
