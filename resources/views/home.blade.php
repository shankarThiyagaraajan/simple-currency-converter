@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Converter</div>

                    <div class="panel-body">
                        <div class="col-md-12">
                            <form method="post" action="" id="formCurrency">
                                <div class="row">
                                    {{--Currency From--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>From :</label>
                                            <select class="form-control action_currency_rate" name="from">
                                                @foreach($currency as $cur)
                                                    <option value="{{$cur->code}}">{{$cur->currency}} - [{{$cur->code}}
                                                        ]
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{--Currency To--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>To :</label>
                                            <select class="form-control action_currency_rate" name="to">
                                                @foreach($currency as $cur)
                                                    <option value="{{$cur->code}}">{{$cur->currency}} - [{{$cur->code}}
                                                        ]
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{--Currency Valud--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Amount :</label>
                                            <input type="text" class="form-control" name="value">
                                            <h3 class="text text-muted" id="convertion_rate"></h3>
                                            <h2 class="text text-success" id="convertion_rate_result"></h2>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="button" class="btn btn-primary" id="convertRate" value="Convert">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script>
        (function () {

            $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}});

            var active_convertion_rate = false;

            $(document).on('change', '.action_currency_rate', function () {
                var from = $('[name="from"]').val();
                var to = $('[name="to"]').val();
                // Reset the Rate.
                $('#convertion_rate').html('');
                $.ajax({
                    url: '{{url('/currency/rate/get')}}',
                    type: 'POST',
                    data: {from: from, to: to},
                    success: function (e) {
                        console.log(e);
                        // Update the the Rate.
                        if (typeof e.rate !== 'undefined') {
                            $('#convertion_rate').html('Rate : <span>' + e.rate + '</span>');
                            active_convertion_rate = e.rate;
                        } else {
                            $('#convertion_rate').html('No Rate Available !');
                            active_convertion_rate = false;
                        }
                    }
                });
            });

            $('#convertRate').on('click', function () {
                var value = $('[name="value"]').val();

                value = parseFloat(value);

                var rate = value * active_convertion_rate;

                rate = rate.format(2, 2);

                $('#convertion_rate_result').html(rate);

            });

            Number.prototype.format = function (n, x) {
                var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
                return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
            };

        }());
    </script>
@endsection