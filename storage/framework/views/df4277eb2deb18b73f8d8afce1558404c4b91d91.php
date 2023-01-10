<?php $__env->startSection('title', 'Manage Departments'); ?>

<?php $__env->startSection('content'); ?>
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
               <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td><?php echo e($key + 1); ?></td>
						<td><?php echo e($d->name); ?></td>
					</tr>
			   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(url('js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(url('js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(url('js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(url('js/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(url('js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(url('js/buttons.flash.min.js')); ?>"></script>
<script src="<?php echo e(url('js/buttons.print.min.js')); ?>"></script>
<script>
    $(function() {
        $('#dTDeparts').DataTable();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>