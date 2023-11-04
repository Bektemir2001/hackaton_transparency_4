<?php

namespace App\Http\Controllers;
use App\Models\Organization;
use App\Services\Organization\OrganizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    protected OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    public function get()
    {
        $organizations = Organization::paginate(16);
        return response($organizations);
    }

    public function getOrganization(Request $request)
    {
        $organization = Organization::where('id', $request->organization)->first();
        return response($organization);
    }

    public function search(Request $request)
    {
        $organization = DB::table('organizations');
        if($request->name)
        {
            $organization = $organization->where('name', 'like', '%' . $request->name . '%');
        }
        if($request->inn)
        {
            $organization = $organization->where('inn', 'like', '%' . $request->inn . '%');
        }
        if($request->okpo)
        {
            $organization = $organization->where('okpo', 'like', '%' . $request->okpo . '%');
        }
        if($request->registration_number)
        {
            $organization = $organization->where('registration_number', 'like', '%' . $request->registration_number . '%');
        }
        return response($organization->paginate(20));
    }
}
