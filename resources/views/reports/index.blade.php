@extends('layout')

@section('title', 'Reports')

@section('content')

    <div class="card" style="padding: 15px">

        <h5><i class="fa fa-chart-bar"></i> Quiz Report</h5>

        <hr/>

        <div class="row">


            <div class="col-md-3">
                <label for="Month">Department</label>
                <select class="form-control" id="deprtId">
                    <option value="all">-- ALL DEPARTMENTS --</option>
                    @foreach(\App\Department::all() as $d)
                        <option value="{{$d->id}}">{{ strtoupper($d->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="Month">Staff</label>
                <select class="form-control" id="staff">
                    <option value="all">ALL</option>
                    <option value="attempted">ATTEMPTED</option>
                    <option value="none_attempted">NONE ATTEMPTED</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="Month">Month</label>
                <select class="form-control" id="month">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="Month">Year</label>
                <input id="year" class="form-control" value="{{date('Y')}}"/>
            </div>

        </div>

        <br/>

        <div class="row">
            <div class="col-md-12" style="display: flex; flex-direction: row; justify-content: center">
                <label for="Month"></label>
                <button id="fetchReport" type="button" class="btn btn-danger"><i class="fa fa-list"></i> Fetch Report
                </button>
            </div>
        </div>

    </div>

    <hr/>

    <div class="card" style="padding: 15px;">

        <center>
            <img src="{{url('images/loader.gif')}}" style="display: none" id="loader"/>
        </center>

        <div id="reportBox"></div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function () {

            $('body').on('click', '.viewStaff', function () {
               $('#loaderx').show();
               $('#staffBox').html('');

               var s = $(this).attr('s');
               var d = $(this).attr('d');
               var m = $(this).attr('m');
               var y = $(this).attr('y');

               var data = {
                   staff: s,
                   depart: d,
                   month: m,
                   year: y,
                   _token: '{{csrf_token()}}'
               };

               Biggo.talkToServer('{{route("app.reports.viewstaffs")}}', data).then(function (res) {
                   $('#loaderx').hide();
                   $('#staffBox').html(res);
               });

            });

            $('body').on('click', '#fetchReport', function () {

                var deprtId = $('#deprtId').val();
                var staff = $('#staff').val();
                var month = $('#month').val();
                var year = $('#year').val();

                if (!year) {
                    swal({
                        title: 'Year is required'
                    });
                    return;
                } else {

                    var text = /^[0-9]+$/;
                    if (year != 0) {
                        if ((year != "") && (!text.test(year))) {

                            swal("Please Enter Numeric Values Only");
                            return;
                        }

                        if (year.length != 4) {
                            swal("Year is not proper. Please check");
                            return;
                        }
                        var current_year = new Date().getFullYear();
                        if ((year < 1920) || (year > current_year)) {
                            alert("Year should be in range 1920 to current year");
                            return;
                        }
                    }

                }

                var data = {
                    deprtId: deprtId,
                    staff: staff,
                    month: month,
                    year: year,
                    _token: '{{csrf_token()}}'
                };

                $('#loader').show();
                $('#reportBox').html('')

                Biggo.talkToServer('{{route("app.reports.fetch")}}', data).then(function (res) {
                    $('#loader').hide();
                    $('#reportBox').html(res)
                })
            });
        });
    </script>
@endsection