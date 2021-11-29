@if(count($allAttempts) != 0)
<table id="dataTable_reportx" class="table table-dark table-striped table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Department</th>
    </tr>
    </thead>
    <tbody>

    @foreach($allAttempts as $k=>$a)
            <tr>
                <td>{{($k+1)}}</td>
                <td>{{$a->full_name}}</td>
                <td>{{$a->department_name}}</td>
            </tr>
    @endforeach

    </tbody>
</table>
@else
<div class="alert alert-danger">No Staff found</div>
@endif
