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

        <div class="row row-gap-3 mb-5">
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
        </div>
    </div>
</section>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>

        // Leads por dia
            var spark1 = {
                chart: {
                    id: 'sparkline1',
                    group: 'sparklines',
                    type: 'area',
                    height: 160,
                    sparkline: {
                    enabled: true
                    },
                },
                stroke: {
                    curve: 'straight'
                },
                fill: {
                    opacity: 1,
                },
                series: [{
                    name: 'Leads por hora',
                    data: [
                        @if($leadsDay->first())
                            @foreach($leadsDay as $lead)
                                {{ $lead->count }},   
                            @endforeach
                        @else
                            0
                        @endif
                    ]
                }],
                labels: [
                    @if($leadsDay->first())
                        @foreach($leadsDay as $lead)
                            `{{ $lead->created_at_hours }}`,   
                        @endforeach
                    @else
                        0
                    @endif
                ],
                yaxis: {
                    min: 0
                },
                colors: ['#00D8B6'],
                title: {
                    text: '{{ $leadsDayCount }}',
                    offsetX: 30,
                    style: {
                        fontSize: '24px',
                        cssClass: 'apexcharts-yaxis-title'
                    }
                },
                subtitle: {
                    text: 'Leads do dia',
                    offsetX: 30,
                    style: {
                        fontSize: '14px',
                        cssClass: 'apexcharts-yaxis-title'
                    }
                }
            }
            var sparkG1 = new ApexCharts(document.querySelector("#spark1"), spark1);
            sparkG1.render();
        
        // Leads média por dia
            var spark2 = {
                chart: {
                    id: 'sparkline1',
                    group: 'sparklines',
                    type: 'area',
                    height: 160,
                    sparkline: {
                    enabled: true
                    },
                },
                stroke: {
                    curve: 'straight'
                },
                fill: {
                    opacity: 1,
                },
                series: [{
                    name: 'Média por dia de leads',
                    data: [
                        @foreach($leadsAVG as $i => $lead)
                            {{ round( ($lead->count / $leads) * $leadsTotal[$id]->count, 1)  }},   
                        @endforeach
                    ]
                }],
                labels: [
                    @foreach($leadsAVG as $lead)
                        `{{ $lead->created_at_1 }}`,   
                    @endforeach
                ],
                yaxis: {
                    min: 0
                },
                xaxis: {
                    type: 'datetime',
                },
                colors: ['#008FFB'],
                title: {
                    text: '{{ round($leadsAVG->avg("count"), 0) }}',
                    offsetX: 30,
                    style: {
                        fontSize: '24px',
                        cssClass: 'apexcharts-yaxis-title'
                    }
                },
                subtitle: {
                    text: 'Média de leads',
                    offsetX: 30,
                    style: {
                        fontSize: '14px',
                        cssClass: 'apexcharts-yaxis-title'
                    }
                }
            }
            var sparkG2 = new ApexCharts(document.querySelector("#spark2"), spark2);
            sparkG2.render();

        // Total de leads
            var spark3 = {
                chart: {
                    id: 'sparkline1',
                    group: 'sparklines',
                    type: 'area',
                    height: 160,
                    sparkline: {
                    enabled: true
                    },
                },
                stroke: {
                    curve: 'straight'
                },
                fill: {
                    opacity: 1,
                },
                series: [{
                    name: 'Total por dia',
                    data: [
                        @foreach($leadsTotal as $lead)
                            {{ $lead->count }},   
                        @endforeach
                    ]
                }],
                labels: [
                    @foreach($leadsTotal as $lead)
                        `{{ $lead->created_at_1 }}`,   
                    @endforeach
                ],
                yaxis: {
                    min: 0
                },
                xaxis: {
                    type: 'datetime',
                },
                colors: ['#FEB019'],
                title: {
                    text: '{{ $leads }}',
                    offsetX: 30,
                    style: {
                    fontSize: '24px',
                    cssClass: 'apexcharts-yaxis-title'
                    }
                },
                subtitle: {
                    text: 'Total de leads',
                    offsetX: 30,
                    style: {
                    fontSize: '14px',
                    cssClass: 'apexcharts-yaxis-title'
                    }
                }
            }
            var sparkG3 = new ApexCharts(document.querySelector("#spark3"), spark3);
            sparkG3.render();
        
        // Leads por empreendimento
            var options1 = {
                title: {
                    text: 'Leads por empreendimento',
                },
                chart: {
                    type: 'bar'
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                    }
                },
                colors:[
                    '#0d6efd'
                ],
                series: [{
                    name: 'Qtd. de leads',
                    data: [
                        @foreach($leadsForBuildings as $lead)
                            {{ $lead->count }},   
                        @endforeach
                    ]
                }],
                xaxis: {
                    labels: {
                        show: false,
                    },
                    categories: [
                        @foreach($leadsForBuildings as $lead)
                            '{{ $lead->RelationBuildings->name }}',   
                        @endforeach
                    ]
                }
            }
            var chart1 = new ApexCharts(document.querySelector("#leadsForBuildings"), options1);
            chart1.render();

        // Leads por origin
            /*var options2 = {
                title: {
                    text: 'Leads por origin',
                },
                chart: {
                    type: 'donut',
                },
                series: [
                    @foreach($leadsForOrigins as $lead)
                        {{ $lead->count }},   
                    @endforeach
                ],
                labels: [
                    @foreach($leadsForOrigins as $lead)
                        '{{ $lead->RelationOrigins->name }}',   
                    @endforeach
                ]
            };*/
            var options2 = {
                chart: {
                    type: 'bar'
                },
                plotOptions: {
                bar: {
                        borderRadius: 0,
                        horizontal: true,
                        barHeight: '80%',
                        isFunnel: true,
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opt) {
                        return opt.w.globals.labels[opt.dataPointIndex] + ':  ' + val
                    },
                    dropShadow: {
                        enabled: true,
                    },
                },
                series: [{
                    name: 'Qtd. de leads',
                    data: [
                        @foreach($leadsForOrigins as $lead)
                            {{ $lead->count }},   
                        @endforeach
                    ]
                }],
                xaxis: {
                    categories: [
                        @foreach($leadsForOrigins as $lead)
                            '{{ $lead->RelationOrigins->name }}',   
                        @endforeach
                    ]
                }
            }
            var chart2 = new ApexCharts(document.querySelector("#leadsForOrigins"), options2);
            chart2.render();

        // Leads por utm_source
            var options3 = {
                title: {
                    text: 'Leads por utm_source',
                },
                chart: {
                    type: 'area',
                },
                series: [{
                    name: 'Qtd. de leads',
                    data: [
                        @foreach($leadsForUtmSource as $lead)
                            {{ $lead->count }},   
                        @endforeach
                    ]
                }],
                xaxis: {
                    categories: [
                        @foreach($leadsForUtmSource as $lead)
                            '{{ $lead->value }}',   
                        @endforeach
                    ]
                }
            }
            var chart3 = new ApexCharts(document.querySelector("#leadsForUtmSource"), options3);
            chart3.render();

        // Leads por utm_campign
            var options4 = {
                title: {
                    text: 'Leads por utm_campign',
                },
                chart: {
                    type: 'polarArea',
                },
                fill: {
                    opacity: 1
                },
                yaxis: {
                    show: false
                },
                legend: {
                    position: 'bottom'
                },
                series: [
                    @foreach($leadsForUtmCampaign as $lead)
                        {{ $lead->count }},   
                    @endforeach
                ],
                labels: [
                    @foreach($leadsForUtmCampaign as $lead)
                        '{{ $lead->value }}',   
                    @endforeach
                ]
            };
            var chart4 = new ApexCharts(document.querySelector("#leadsForUtmCampaign"), options4);
            chart4.render();

        // Leads por empresa
            var options5 = {
                title: {
                    text: 'Leads por empresa',
                },
                chart: {
                    type: 'radialBar',
                },
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            name: {
                                fontSize: '22px',
                            },
                            value: {
                                fontSize: '16px',
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function (w) {
                                    // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                    return {{ $leads }}
                                }
                            }
                        }
                    }
                },
                series: [
                    @foreach($leadsForCompanies as $lead)
                        {{ round($lead->count / $leads * 100) }},   
                    @endforeach
                ],
                labels: [
                    @foreach($leadsForCompanies as $lead)
                        '{{ $lead->name }}',   
                    @endforeach
                ],
            }
            var chart5 = new ApexCharts(document.querySelector("#leadsForCompanies"), options5);
            chart5.render();
    </script>
@endsection