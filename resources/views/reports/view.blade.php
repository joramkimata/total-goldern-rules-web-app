<div class="modal fade" id="viewStaffs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <center>
                    <img src="{{url('images/loader.gif')}}" style="display: none" id="loaderx"/>
                </center>

                <div id="staffBox"></div>
            </div>
        </div>
    </div>
</div>


<div class="alert alert-info">
    <h5><i class="fa fa-list"></i> Quiz Reports - {{$m}} of {{$year}},
        {{$staff == 'all' ? 'All Staffs': ''}}
        {{$staff == 'attempted' ? 'Staffs who did the QUIZ': ''}}
        {{$staff == 'none_attempted' ? 'Staffs who missed the  QUIZ': ''}},

        {{$dept == 'all' ? 'All Departments' : '(' . \App\Department::find($dept)->name . ') Department'}}
    </h5>
    <hr/>
</div>

@if($staff == 'all')
    <div class="row">
        <div class="col-md-12">
            <table class="table  table-dark">
                <thead>

                <th>No. of Staffs Attempted</th>
                <th>No. of Staffs None Attempted</th>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div style="display: flex; justify-content: space-between">
                            <h1>{{$allAttempts}}</h1>
                            <button s="a" d="{{$dept}}" m="{{$month}}" y="{{$year}}" data-toggle="modal" data-target="#viewStaffs" class="btn viewStaff btn-sm btn-primary"><i
                                        class="fa fa-list"></i> View Staffs
                            </button>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; justify-content: space-between">
                            <h1>{{$allNoneAttempts}}</h1>
                            <button s="na" d="{{$dept}}" m="{{$month}}" y="{{$year}}" data-toggle="modal" data-target="#viewStaffs" class="btn viewStaff btn-sm btn-primary"><i
                                        class="fa fa-list"></i> View Staffs
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif


@if($staff == 'attempted')
    <div class="row">
        <div class="col-md-12">
            <table class="table  table-dark">
                <thead>

                <th>No. of Staffs Attempted</th>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div style="display: flex; justify-content: space-between">
                            <h1>{{$allAttempts}}</h1>
                            <button s="a" d="{{$dept}}" m="{{$month}}" y="{{$year}}" data-toggle="modal" data-target="#viewStaffs" class="btn viewStaff btn-sm btn-primary"><i
                                        class="fa fa-list"></i> View Staffs
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif


@if($staff == 'none_attempted')
    <div class="row">
        <div class="col-md-12">
            <table class="table  table-dark">
                <thead>

                <th>No. of Staffs None Attempted</th>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div style="display: flex; justify-content: space-between">
                            <h1>{{$allNoneAttempts}}</h1>
                            <button s="na" d="{{$dept}}" m="{{$month}}" y="{{$year}}" data-toggle="modal" data-target="#viewStaffs" class="btn viewStaff btn-sm btn-primary"><i
                                        class="fa fa-list"></i> View Staffs
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif