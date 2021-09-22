<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cek Ongkir</title>
    {{--  <meta name="csrf-token" content="{{ csrf_token() }}">  --}}
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    {{--  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">  --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.3.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

</head>
<body>
    <div class="container-fluid d-flex justify-content-center">
        <div class="card col-md-8 mt-5">
            <div class="card-header">
              Cek Ongkir
            </div>
            <div class="card-body d-flex justify-content-center">
                <form action="/" role="form" method="POST" class="col-md-6">
                    @csrf
                    {{--  provinsi pengirim  --}}
                    <select class="form-control mb-3" name="province_origin">
                        <option value="{{$province}}" selected>Provinsi pengirim </option>
                        @foreach ($province as $pro => $value)
                            <option value="{{$pro}}">{{$value}}</option>
                        @endforeach
                    </select>


                    {{--  kota pengirim  --}}
                    <div class=" mb-3">
                        <select name="city_origin" class="form-control" id="">
                            <option value="">Kota Pengirim</option>
                        </select>
                    </div>

                    {{--  provinsi tujuan  --}}
                    <div class="mb-3">
                        <select name="province_destination" id="" class="form-control">
                            <option value="" selected>Provinsi Tujuan</option>
                            @foreach ($province as $pro => $value)
                                <option value="{{$pro}}"> {{$value}}</option>                                
                            @endforeach
                        </select>
                    </div>

                    {{--  kota tujuan  --}}
                    <div class="mb-3">
                        <select name="city_destination" id="" class="form-control">
                            <option value="">Kota Tujuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <select name="courier" id="" class="form-control">
                            @foreach ($couriers as $courier => $value)
                                <option value="{{$courier}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <span>Berat (gr)</span>
                        <input type="number" name="weight" class="form-control" placeholder="berat (gr)" value="1000">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-md btn-primary btn-block btn-check">Submit</button>
                    </div>

                    
                    {{--  ongkir  --}}
                    <div class="row mt-3">
                      <div class="col-md-12 d-flex justify-content-end">
                          <div class="card ongkir col-md-12">
                              <div class="card-body">
                                  <li class="list-group-item" id="ongkir"></li>
                              </div>
                          </div>
                      </div>
                    </div>
                      
                </form>
                
            </div>
          </div>
    </div>
     {{--  <p>{{ $data }}</p>  --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" ></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
        
            $('select[name="province_origin"]').on('change', function() {
              let provinceId = $(this).val();
              if (provinceId) {
                jQuery.ajax({
                  url: 'province/'+provinceId+'/cities',
                  type: "GET",
                  dataType:"json",
                  success:function(data) {
                    $('select[name="city_origin"]').empty();
                    $.each(data, function(key, value) {
                      $('select[name="city_origin"]').append('<option value="'+key+'">' + value + '</option>');
                    });
                  },
                });
              } else {
                $('select[name="city_origin"]').empty();
              }
            });
            
            $('select[name="province_destination"]').on('change', function() {
              let provinceId = $(this).val();
              if (provinceId) {
                jQuery.ajax({
                    url: 'province/'+provinceId+'/cities',
                  type:"GET",
                  dataType:"json",
                  success:function(data) {
                    $('select[name="city_destination"]').empty();
                    $.each(data, function (key, value) {
                      $('select[name="city_destination"]').append('<option value="'+key+'">'+ value +'</option>');
                    });
                  },
                });
              } else {
                $('select[name="city_destination"]').empty();
              }
            });
        
            {{--  cek ongkir  --}}
            let isProcessing = false;
            $('.btn-check').click(function (e) {
            e.preventDefault();

            {{--  let token = $("meta[name='csrf-token']").attr("content");  --}}
            let token = $('csrf-token');
            let city_origin = $('select[name=city_origin]').val();
            let city_destination = $('select[name=city_destination]').val();
            let courier = $('select[name=courier]').val();
            let weight = $('#weight').val();

            if(isProcessing){
                return;
            }

            isProcessing = true;
            jQuery.ajax({
                url: "/",
                data: {
                    _token: token,
                    city_origin: city_origin,
                    city_destination: city_destination,
                    courier: courier,
                    weight: weight,
                },
                dataType: "JSON",
                type: "POST",
                success: function (response) {
                    isProcessing = false;
                    if (response) {
                        $('#ongkir').empty();
                        $('#ongkir').addClass('d-block');
                        $.each(response[0]['costs'], function (key, value) {
                            $('#ongkir').append('<li class="list-group-item">'+response[0].code.toUpperCase()+' : <strong>'+value.service+'</strong> - Rp. '+value.cost[0].value+' ('+value.cost[0].etd+' hari)</li>')
                        });

                    }
                }
            });

        });
            
          });
        </script>
</body>
</html>