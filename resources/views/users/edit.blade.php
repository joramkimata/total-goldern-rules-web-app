<?php

$user = \App\User::find($id);

?>

<form id="userEditForm">
    <div class="modal-body" id="formEditUser">

        {{csrf_field()}}

        <div class="form-group">
            <label for="user-name" class="col-form-label">Full Name:</label>
            <input type="text" value="{{$user->name}}" name="fullname" class="form-control validate[required]" id="user-name">
        </div>
        <div class="form-group">
            <label for="user-email" class="col-form-label">Email:</label>
            <input type="email" value="{{$user->email}}" name="email" class="form-control validate[required,custom[email]]" id="user-email">
        </div>

        <div class="form-group">
            <label for="phone" class="col-form-label">Phone:</label>
            <input type="text" name="phone" value="{{$user->phone}}" class="validate[required] form-control" id="phone"
            data-errormessage-value-missing="Phone is required!"
            />
        </div>

        <div class="form-group">
            <label for="department_id" class="col-form-label">Department:</label>
            <select name="department_id" class="validate[required] form-control" id="department_id"
            data-errormessage-value-missing="Department is required!"
            >
            @if($user->department_id)
                <option value="{{$user->department_id}}">{{$user->department->name}}</option>
                @foreach(\App\Department::where('id', '!=', $user->department_id)->get() as $d)
                    <option value="{{$d->id}}">{{$d->name}}</option>
                @endforeach
            @else
                <option value="">--SELECT DEPARTMENT--</option>
                @foreach(\App\Department::all() as $d)
                    <option value="{{$d->id}}">{{$d->name}}</option>
                @endforeach
            @endif
        </select>
        </div>




    </div>
    <div class="modal-footer">
        
        <button type="button" refreshURL="{{route('users.refresh')}}" id="updateUser" route="{{route('users.update', $id)}}" class="btn btn-primary">Update Changes</button>
    </div>
</form>
