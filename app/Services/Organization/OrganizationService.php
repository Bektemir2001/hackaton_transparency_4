<?php

namespace App\Services\Organization;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class OrganizationService
{
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
}
