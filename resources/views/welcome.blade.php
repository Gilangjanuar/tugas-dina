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
    <div class="row" id="list-car"></div>
@endsection()

@push('scripts')
    <script type="text/javascript">
        let type = 'TERLARIS';
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
            $('#termahal').on('click',function (){
                type = "TERMAHAL";
                loadList();
            })

            $('#terlaris').on('click',function (){
                type = "TERLARIS";
                loadList();
            })

            $('#termurah').on('click',function (){
                type = "TERMURAH";
                loadList();
            })
        })
    </script>
@endpush
