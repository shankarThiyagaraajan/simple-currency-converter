@extends('layouts.app')
<link rel="stylesheet" href="{{asset('plugin/select2/dist/css/select2.min.css')}}">
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 align="center">Create Currency</h2>
                    </div>
                    <form method="post" action="{{url('/currency/add')}}" id="form_currency" novalidate>
                        {{csrf_field()}}
                        <div class="panel-body">
                            <div class="row">
                                {{--Currency Name--}}
                                <div class="col-md-6">
                                    <label>Currency Name :</label>
                                    <input type="text" pattern="[a-zA-Z]" name="name" class="form-control" required>
                                </div>
                                {{--Currency Code--}}
                                <div class="col-md-6">
                                    <label>Currency Code : </label>
                                    <input type="text" pattern="[A-Z]" name="code" id="currency_code"
                                           class="form-control" required>
                                    <span style="color: red;" id="currency_code_ms"></span>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-12" align="right">
                                    <input type="reset" class="btn btn-warning" value="Reset">
                                    <input type="submit" class="btn btn-primary" value="Create">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 align="center">Currencies</h2>
                    </div>
                    <div class="panel-body">
                        @if($currency)
                            <table class="table table-bordered table-hover table-responsive">
                                <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Code</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($currency as $cur)
                                    <tr>
                                        <td>{{$cur->currency}}</td>
                                        <td>{{$cur->code}}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        @else
                            <h3>Create your first Currency !</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h2 align="center">Create Currency Rate</h2>
                    </div>
                    <form method="post" action="{{url('/currency/rate/add')}}" id="form_currency_rate" novalidate>
                        {{csrf_field()}}
                        <div class="panel-body">
                            <div class="row">
                                {{--Currency From--}}
                                <div class="col-md-6">
                                    <label>From : </label>
                                    <select class="select-list" name="currencyFrom" required>
                                        @foreach($currency as $cur)
                                            <option value="{{$cur->code}}">{{$cur->currency}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{--Currency To--}}
                            <div class="row">
                                <div class="col-md-6">
                                    <label>To : </label>
                                    <select class="select-list" name="currencyTo" required>
                                        @foreach($currency as $cur)
                                            <option value="{{$cur->code}}">{{$cur->currency}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <hr>
                                {{--Currency Rate--}}
                                <div class="col-md-3">
                                    <label>Rate :</label>
                                    <input type="text" pattern="[0-9.]" name="rate" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-12" align="right">
                                    <input type="reset" class="btn btn-warning" value="Reset">
                                    <input type="submit" class="btn btn-primary" value="Create">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 align="center">Currency Rates</h2>
                    </div>
                    <div class="panel-body">
                        @if($rates)
                            <table class="table table-bordered table-hover table-responsive">
                                <thead>
                                <tr>
                                    <td>From</td>
                                    <td>To</td>
                                    <td>Rate</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rates as $rate)
                                    <tr>
                                        <td>{{$rate->from->currency}} - [{{$rate->from->code}}]</td>
                                        <td>{{$rate->to->currency}} - [{{$rate->to->code}}]</td>
                                        <td>{{$rate->rate}}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        @else
                            <h3>Create your first Rate !</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('plugin/select2/dist/js/select2.min.js')}}"></script>
    <script type="text/javascript">

        var currency_add_status = true;

        $(".select-list").select2();

        var currency_form = new jsValidator().init({
            form: 'form_currency',
            message: {
                required: '<br><span style="color:red">Field is Required</span>',
                min: 'Field is Too low'
            }
        });

        var currency_rate_form = new jsValidator().init({
            form: 'form_currency_rate',
            message: {
                required: '<br><span style="color:red">Field is Required</span>'
            }
        });


        $('#currency_code').on('keyup', function () {
            $('#currency_code_ms').html('');
            var value = $(this).val();
            $.ajax({
                url: '{{url('/currency/check')}}/' + value,
                type: 'get',
                success: function (e) {
                    if (true == e) {
                        $('#currency_code_ms').html('This Currency already exits !');
                        currency_add_status = false;
                        $('#currency_code').val('');
                        setTimeout(function () {
                            $('#currency_code_ms').html('');
                        }, 4000);
                    }
                }
            });
        });

    </script>
@endsection