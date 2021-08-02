@extends('layout.main')

@section('title')
    LIST
@endsection()

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">List Car</li>
@endsection()

@section('contents')
    <div class="text-right" id="filter" style="margin-bottom: 10px">
        <button class="btn btn-outline-primary btn-sm" id="terlaris"> Terlaris</button>
        <button class="btn btn-outline-primary btn-sm" id="termahal"> Termahal</button>
        <button class="btn btn-outline-primary btn-sm" id="termurah"> Termurah</button>
    </div>
    <hr>
    <!-- DONUT CHART -->
    <div class="card card-danger" id="cart-terlaris">
        <div class="card-header">
            <h3 class="card-title">Cart Terlaris</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <canvas id="donutChart"
                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- Bar chart -->
    <div class="card card-primary card-outline" id="cart-termahal">
        <div class="card-header">
            <h3 class="card-title">
                <i class="far fa-chart-bar"></i>
                Bar Chart
            </h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="bar-chart" style="height: 300px;"></div>
        </div>
        <!-- /.card-body-->
    </div>
    <!-- /.card -->
    <div class="row" id="list-car"></div>
@endsection()

@push('scripts')
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('plugins/flot/jquery.flot.js')}}"></script>
    <script type="text/javascript">
        let type = 'TERLARIS';
        let dataCart;
        loadList();

        function loadList() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{route('get-data')}}',
                type: 'post',
                data: {
                    type: type
                },
                beforeSend: function () {
                    if ($('#list-car').val() == '') {
                        $('.content').ploading({
                            action: 'show'
                        })
                    } else {
                        $('#list-car').ploading({
                            action: 'show'
                        })
                    }
                }, success: function (result, status) {
                    data = result;
                    dataCart = data;
                    if (data) {
                        $('#list-car').ploading({
                            action: 'hide'
                        })
                        $('#list-car').show();
                        cardList(data);
                    } else {
                        $('#list-car').hide();
                    }

                    $('#list-car').ploading({
                        action: 'hide'
                    })

                    $('.content').ploading({
                        action: 'hide'
                    })
                },
            })
        }

        function cardList(data) {
            console.log(data);
            $("#list-car").empty()
            if (data) {
                $.each(data, function (index, value) {
                    let card = '<div class="col-md-6">\
                                    <div class="card">\
                                    <div class="card-header">\
                                    <h4 class="card-title"> ' + value.car_name + ' </h4>\
                                <div class="float-sm-right">\
                                    ' + new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(value.car_price) + '\
                                </div>\
                            </div>\
                            <div class="card-body">\
                                    <p> Warna : ' + value.car_color + ' </p>\
                                    <p> Type : ' + value.car_type + ' </p>\
                                    <p> Terjual : ' + value.jml + ' Buah</p>\
                            </div>\
                                </div>\
                            </div>';
                    $("#list-car").append(card);
                });
            }
        }

        $(document).ready(function () {
            $('#cart-terlaris').hide();
            $('#cart-termahal').hide();
            $('#termahal').on('click', function () {
                $('#cart-terlaris').hide();
                type = "TERMAHAL";
                loadList();
                termahal(dataCart)
            })

            $('#terlaris').on('click', function () {
                $('#cart-termahal').hide();
                type = "TERLARIS";
                loadList();
                terlaris(dataCart)
            })

            $('#termurah').on('click', function () {
                $('#cart-termahal').hide();
                $('#cart-terlaris').hide();
                type = "TERMURAH";
                loadList();
            })

        })

        function terlaris(data) {
            $('#cart-terlaris').show();
            let carName = [];
            let carHex = [];
            let carValue = [];
            $.each(data, function (index, value) {
                carName.push(value.car_name+'-'+value.car_color)
                carHex.push(value.hex_color)
                carValue.push(value.jml)
            })
            var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
            var donutData = {
                labels: carName,
                datasets: [
                    {
                        data: carValue,
                        backgroundColor: carHex,
                    }
                ]
            }
            var donutOptions = {
                maintainAspectRatio: false,
                responsive: true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            var donutChart = new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions
            })
        }

        function termahal(data) {
            $('#cart-termahal').show();
            let carPrice = []
            let carName = []
            let dataPrice
            let dataName
            $.each(data,function(index,value){
                dataPrice = [index+1,value.car_price]
                carPrice.push(dataPrice)

                dataName = [index+1,value.car_name+'-'+value.car_color]
                carName.push(dataName);
            })

            var bar_data = {
                data: carPrice,
                bars: {show: true}
            }
            $.plot('#bar-chart', [bar_data], {
                grid: {
                    borderWidth: 1,
                    borderColor: '#f3f3f3',
                    tickColor: '#f3f3f3'
                },
                series: {
                    bars: {
                        show: true, barWidth: 0.5, align: 'center',
                    },
                },
                colors: ['#3c8dbc'],
                xaxis: {
                    ticks: carName
                }
            })
            /* END BAR CHART */
        }
    </script>
@endpush
