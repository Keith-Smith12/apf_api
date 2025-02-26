@extends('layouts._includes.admin.body')
@section('titulo', ' Dashboard')

@section('conteudo')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <!-- Usuários -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow bg-primary text-white border-0">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary-light">
                                            <i class="fe fe-16 fe-users text-white mb-0"></i>
                                        </span>
                                    </div>
                                    <div class="col pr-0">
                                        <p class="small text-white-50 mb-0">Usuários</p>
                                        <span class="h3 mb-0 text-white">{{ $users }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Categorias -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-warning">
                                            <i class="fe fe-16 fe-layers text-white mb-0"></i>
                                        </span>
                                    </div>
                                    <div class="col pr-0">
                                        <p class="small text-muted mb-0">Categorias</p>
                                        <span class="h3 mb-0">{{ $categorias }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metas -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-success">
                                            <i class="fe fe-16 fe-flag text-white mb-0"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Metas</p>
                                        <span class="h3 mb-0">{{ $metas }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Entradas -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-danger">
                                            <i class="fe fe-16 fe-log-in text-white mb-0"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Entradas</p>
                                        <span class="h3 mb-0">{{ $entradas }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end section -->


                <!-- charts-->
                <!-- Gráfico de Usuários -->
                <div class="row my-4">
                    <div class="col-md-12">
                        <div class="chart-box card shadow p-3">
                            <h5 class="text-center mb-3">📊 Usuários Cadastrados</h5>
                            <div id="colunChart"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="chart-widget">
                                    <div id="gradientRadial"></div>
                                </div>
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <p class="text-muted mb-0">Yesterday</p>
                                        <h4 class="mb-1">126</h4>
                                        <p class="text-muted mb-2">+5.5%</p>
                                    </div>
                                    <div class="col-6 text-center">
                                        <p class="text-muted mb-0">Today</p>
                                        <h4 class="mb-1">86</h4>
                                        <p class="text-muted mb-2">-5.5%</p>
                                    </div>
                                </div>
                            </div> <!-- .card-body -->
                        </div> <!-- .card -->
                    </div> <!-- .col -->
                    <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="chart-widget mb-2">
                                    <div id="radialbar"></div>
                                </div>
                                <div class="row items-align-center">
                                    <div class="col-4 text-center">
                                        <p class="text-muted mb-1">Cost</p>
                                        <h6 class="mb-1">$1,823</h6>
                                        <p class="text-muted mb-0">+12%</p>
                                    </div>
                                    <div class="col-4 text-center">
                                        <p class="text-muted mb-1">Revenue</p>
                                        <h6 class="mb-1">$6,830</h6>
                                        <p class="text-muted mb-0">+8%</p>
                                    </div>
                                    <div class="col-4 text-center">
                                        <p class="text-muted mb-1">Earning</p>
                                        <h6 class="mb-1">$4,830</h6>
                                        <p class="text-muted mb-0">+8%</p>
                                    </div>
                                </div>
                            </div> <!-- .card-body -->
                        </div> <!-- .card -->
                    </div> <!-- .col -->
                    <div class="col-md-4">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <p class="mb-0"><strong class="mb-0 text-uppercase text-muted">Today</strong></p>
                                <h3 class="mb-0">$2,562.30</h3>
                                <p class="text-muted">+18.9% Last week</p>
                                <div class="chart-box mt-n5">
                                    <div id="lineChartWidget"></div>
                                </div>
                                <div class="row">
                                    <div class="col-4 text-center mt-3">
                                        <p class="mb-1 text-muted">Completions</p>
                                        <h6 class="mb-0">26</h6>
                                        <span class="small text-muted">+20%</span>
                                        <span class="fe fe-arrow-up text-success fe-12"></span>
                                    </div>
                                    <div class="col-4 text-center mt-3">
                                        <p class="mb-1 text-muted">Goal Value</p>
                                        <h6 class="mb-0">$260</h6>
                                        <span class="small text-muted">+6%</span>
                                        <span class="fe fe-arrow-up text-success fe-12"></span>
                                    </div>
                                    <div class="col-4 text-center mt-3">
                                        <p class="mb-1 text-muted">Conversion</p>
                                        <h6 class="mb-0">6%</h6>
                                        <span class="small text-muted">-2%</span>
                                        <span class="fe fe-arrow-down text-danger fe-12"></span>
                                    </div>
                                </div>
                            </div> <!-- .card-body -->
                        </div> <!-- .card -->
                    </div> <!-- .col-md -->
                    <!-- .card -->
                </div> <!-- .col-md -->
                <!-- .col -->

            </div>
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var userData = {!! json_encode($usersByMonth) !!};

            var months = userData.map(item => item.month); // Eixo X (tempo)
            var counts = userData.map(item => item.count); // Eixo Y (quantidade de usuários)

            var options = {
                chart: {
                    type: 'area',
                    height: 350
                },
                series: [{
                    name: 'Usuários Cadastrados',
                    data: counts
                }],
                xaxis: {
                    categories: months,
                    type: 'category' // Mês como rótulo no eixo X
                },
                colors: ['#007bff'],
                stroke: {
                    curve: 'smooth'
                }
            };

            var chart = new ApexCharts(document.querySelector("#colunChart"), options);
            chart.render();
        });
        </script>
@endsection
