<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Leads;

class DashboardsCtrl extends Controller
{   
    public function __construct(){
		$this->middleware('auth');
	}
    
    public function index()
    {   
        $leads = Leads::count();
        $leadsDay = Leads::whereDate('created_at', date('Y-m-d'))->select( DB::raw('DATE_FORMAT(created_at, "%H") as created_at_hours, count(created_at) as count') )->groupBy('created_at_hours')->orderBy('created_at_hours', 'asc')->get();
        $leadsDayCount = Leads::whereDate('created_at', date('Y-m-d'))->count();
        $leadsAVG = Leads::select( DB::raw('DATE_FORMAT(created_at, "%d-%b-%Y") as created_at_1, count(created_at) as count') )->groupBy('created_at_1')->get();
        $leadsTotal = Leads::select( DB::raw('DATE_FORMAT(created_at, "%d-%b-%Y") as created_at_1, count(created_at) as count') )->groupBy('created_at_1')->get();

        $leadsForBuildings = Leads::select('building_id', DB::raw('count(building_id) as count'))->groupBy('building_id')->get();
        $leadsForOrigins = Leads::select('leads_origin_id', DB::raw('count(leads_origin_id) as count'))->groupBy('leads_origin_id')->get();
        $leadsForCompanies = Leads::join('buildings', 'leads.building_id', 'buildings.id')->join('companies', 'buildings.companie_id', 'companies.id')->select(DB::raw('count(companies.name) as count, companies.name'))->groupBy('name')->get();
        $leadsForUtmSource = Leads::join('leads_fields', 'leads_fields.leads_id', 'leads.id')->where('leads_fields.name', 'utm_source')->select(DB::raw('count(leads_fields.value) as count, leads_fields.value'))->groupBy('leads_fields.value')->get();
        $leadsForUtmCampaign = Leads::join('leads_fields', 'leads_fields.leads_id', 'leads.id')->where('leads_fields.name', 'utm_campaign')->select(DB::raw('count(leads_fields.value) as count, leads_fields.value'))->groupBy('leads_fields.value')->get();

        return view('dashboards.index')
            ->with('leads', $leads)
            ->with('leadsDay', $leadsDay)
            ->with('leadsDayCount', $leadsDayCount)
            ->with('leadsAVG', $leadsAVG)
            ->with('leadsTotal', $leadsTotal)
            ->with('leadsForBuildings', $leadsForBuildings)
            ->with('leadsForOrigins', $leadsForOrigins)
            ->with('leadsForCompanies', $leadsForCompanies)
            ->with('leadsForUtmSource', $leadsForUtmSource)
            ->with('leadsForUtmCampaign', $leadsForUtmCampaign);
    }
}
