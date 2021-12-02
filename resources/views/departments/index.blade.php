@extends('layout')

@section('title', 'Manage Departments')

@section('content')
<div class="page-class">

	<div class="mt-3">
		<table id="dTDeparts" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
				</tr>
			</thead>
			<tbody>
               @foreach($departments as $key => $d)
					<tr>
						<td>{{$key + 1}}</td>
						<td>{{$d->name}}</td>
					</tr>
			   @endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('scripts')
<script src="{{url('js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{url('js/dataTables.buttons.min.js')}}"></script>
<script src="{{url('js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('js/buttons.html5.min.js')}}"></script>
<script src="{{url('js/buttons.flash.min.js')}}"></script>
<script src="{{url('js/buttons.print.min.js')}}"></script>
<script>
    $(function() {
        $('#dTDeparts').DataTable();
    });
</script>
@endsection