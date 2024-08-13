@extends('base.index')

@section('title')
Dashboard
@endsection

@section('css')
    <link href="{{ asset('css/dashboards.css') }}" rel="stylesheet">
@endsection


@section('content-page')
<section>
    <div class="container-fluid">

         <!--<div class="row row-gap-3 mb-5">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card rounded-1 pt-3">
                    <div id="spark1"></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card rounded-1 pt-3">
                    <div id="spark2"></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card rounded-1 pt-3">
                    <div id="spark3"></div>
                </div>
            </div>
        </div>

        <div class="row row row-gap-4">
            <div class="col-lg-6 col-12 text-center">
                <div class="bg-white p-4 shadow">
                    <div id="leadsForBuildings"></div>
                </div>
            </div>
            <div class="col-lg-6 col-12 text-center">
                <div class="bg-white p-4 shadow">
                    <div id="leadsForOrigins"></div>
                </div>
            </div>
            <div class="col-lg-6 col-12 text-center">
                <div class="bg-white p-4 shadow">
                    <div id="leadsForUtmSource"></div>
                </div>
            </div>
            <div class="col-lg-6 col-12 text-center">
                <div class="bg-white p-4 shadow">
                    <div id="leadsForUtmCampaign"></div>
                </div>
            </div>
            <div class="col-lg-6 col-12 text-center">
                <div class="bg-white p-4 shadow">
                    <div id="leadsForCompanies"></div>
                </div>
            </div>
        </div>-->

        <div class="row">
            <div class="col-12">
                <h4>Em breve...</h4>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection