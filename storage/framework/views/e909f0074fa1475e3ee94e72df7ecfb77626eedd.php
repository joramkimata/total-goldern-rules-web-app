<?php if(count($allUsers) != 0): ?>
    <table id="dataTable_reportx" class="table table-dark table-striped table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Department</th>
        </tr>
        </thead>
        <tbody>

        <?php $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <tr>
                <td><?php echo e(($k+1)); ?></td>
                <td><?php echo e($a->name); ?></td>
                <td><?php echo e(\App\Department::find($a->department_id)->name); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-danger">No Staff found</div>
<?php endif; ?>