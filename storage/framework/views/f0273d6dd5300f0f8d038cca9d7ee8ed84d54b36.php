<?php

$user = \App\User::find($id);

?>

<form id="userEditForm">
    <div class="modal-body" id="formEditUser">

        <?php echo e(csrf_field()); ?>


        <div class="form-group">
            <label for="user-name" class="col-form-label">Full Name:</label>
            <input type="text" value="<?php echo e($user->name); ?>" name="fullname" class="form-control validate[required]" id="user-name">
        </div>
        <div class="form-group">
            <label for="user-email" class="col-form-label">Email:</label>
            <input type="email" value="<?php echo e($user->email); ?>" name="email" class="form-control validate[required,custom[email]]" id="user-email">
        </div>

        <div class="form-group">
            <label for="phone" class="col-form-label">Phone:</label>
            <input type="text" name="phone" value="<?php echo e($user->phone); ?>" class="validate[required] form-control" id="phone"
            data-errormessage-value-missing="Phone is required!"
            />
        </div>

        <div class="form-group">
            <label for="department_id" class="col-form-label">Department:</label>
            <select name="department_id" class="validate[required] form-control" id="department_id"
            data-errormessage-value-missing="Department is required!"
            >
            <?php if($user->department_id): ?>
                <option value="<?php echo e($user->department_id); ?>"><?php echo e($user->department->name); ?></option>
                <?php $__currentLoopData = \App\Department::where('id', '!=', $user->department_id)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <option value="">--SELECT DEPARTMENT--</option>
                <?php $__currentLoopData = \App\Department::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </select>
        </div>




    </div>
    <div class="modal-footer">
        
        <button type="button" refreshURL="<?php echo e(route('users.refresh')); ?>" id="updateUser" route="<?php echo e(route('users.update', $id)); ?>" class="btn btn-primary">Update Changes</button>
    </div>
</form>
