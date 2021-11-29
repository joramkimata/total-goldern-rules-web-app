@if(count($allUsers) != 0)
    <table id="dataTable_reportx" class="table table-dark table-striped table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Department</th>
        </tr>
        </thead>
        <tbody>

        @foreach($allUsers as $k=>$a)

            <tr>
                <td>{{($k+1)}}</td>
                <td>{{$a->name}}</td>
                <td>{{\App\Department::find($a->department_id)->name}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
@else
    <div class="alert alert-danger">No Staff found</div>
@endif