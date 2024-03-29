<?php $__env->startSection('title', 'Manage Users'); ?>


<?php $__env->startSection('content'); ?>

<?php if(session()->has('success')): ?>
<div class="alert alert-success flush">
	<i class="fa fa-check"></i> <?php echo e(session()->get('success')); ?>

</div>
<script src="<?php echo e(url('js/jquery.min.js')); ?>"></script>
<script type="text/javascript">
	$('.flush').delay(5000).fadeOut();
</script>
<?php endif; ?>

<?php if(session()->has('error')): ?>
<div class="alert alert-danger flush">
	<i class="fa fa-check"></i> <?php echo e(session()->get('error')); ?>

</div>
<script src="<?php echo e(url('js/jquery.min.js')); ?>"></script>
<script type="text/javascript">
	$('.flush').delay(5000).fadeOut();
</script>
<?php endif; ?>

<div class="page-class">
	<div class="row">
		<div class="col-md-12">
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal"><i class="fa fa-plus"></i> Add New User</button>
			<hr />
			<table id="dataTable" class="table table-striped table-bordered">
				<thead style="background: #666; color: #FFF; ">
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Phone</th>
						<th>Department</th>
						<th>Email</th>
						<th>Status</th>
						<th>Manage</th>
					</tr>
				</thead>
				<tbody>
					<?php 

					$users = \App\User::where('role_id', '!=', 1)->get();
					$i = 1;

					?>
					<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td><?php echo e($i); ?></td>
						<td><?php echo e($u->name); ?></td>
						<td><?php echo e($u->phone); ?></td>
						<td><?php echo e($u->department->name); ?></td>
						<td><?php echo e($u->email); ?></td>
						<td><?php echo $u->active == 1 ? '<label class="badge badge-success">Active</label>' : '<label class="badge badge-danger">Blocked</label>'; ?></td>
						<td>
							<button class="btn btn-primary btn-sm editUser" title="Edit User" route="<?php echo e(route('users.edit', $u->id)); ?>" userid="<?php echo e($u->id); ?>"  data-toggle="modal" data-target="#userModal" ><i class="fa fa-edit"></i> </button>
							<?php if($u->active == 1): ?>
							<button title="De-activate" class="btn btn-danger btn-sm deactivate" userid="<?php echo e($u->id); ?>"><i class="fa fa-lock"></i> </button>
							<?php else: ?>
							<button title="Activate" class="btn btn-success btn-sm activate" userid="<?php echo e($u->id); ?>"><i class="fa fa-unlock"></i> </button>
							<?php endif; ?>
							<button title="Change Password" class="btn btn-warning btn-sm changepassword" userid="<?php echo e($u->id); ?>" route="<?php echo e(route('app.changepassword', $u->id)); ?>" data-toggle="modal" data-target="#changePassword"><i class="fa fa-key"></i> </button>
						</td>
					</tr>
					<?php $i++; ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

				</tbody>
			</table>
		</div>
	</div>
</div>

<article>
	<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addQuizModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered " role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-center" id="addQuizModalLabel">Add New User</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="<?php echo e(route('users.store')); ?>" method="POST" id="addUserForm" onsubmit="return processAddUser()">
					<div class="modal-body">
                        <?php echo e(csrf_field()); ?>

						<div class="form-group">
							<label for="add-full-name" class="col-form-label">Full Name:</label>
                            <input type="text" name="full_name" class="validate[required] form-control" id="add-full-name"
                            data-errormessage-value-missing="Full name is required!"
                            />
						</div>
						<div class="form-group">
							<label for="phone" class="col-form-label">Phone(Optional):</label>
                            <input type="text" name="phone" class="form-control" id="phone"
                            data-errormessage-value-missing="Phone is required!"
                            />
						</div>
						
						<div class="form-group">
							<label for="email" class="col-form-label">Email:</label>
                            <input type="text" name="email" class="validate[required] form-control" id="email"
                            data-errormessage-value-missing="Email is required!"
                            />
						</div>

						<div class="form-group">
                            <label for="register_password">Password</label>
                            <input type="password" class="validate[required] form-control" id="register_password" name="register_password"
                            data-errormessage-value-missing="Password is required!"
                             />
                        </div>
                        <div class="form-group">
                            <label for="register_password_confirm">Confirm Password</label>
                            <input type="password" class="validate[required,equals[register_password]] form-control" id="register_password_confirm" name="register_password_confirm"
                            data-errormessage-value-missing="Confirm password is required!"
                            data-errormessage="Password mismatches!" />
                        </div>

						<div class="form-group">
							<label for="department_id" class="col-form-label">Department:</label>
                            <select name="department_id" class="validate[required] form-control" id="department_id"
                            data-errormessage-value-missing="Department is required!"
                            >
							<option value="">--SELECT DEPARTMENT--</option>
							<?php $__currentLoopData = \App\Department::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
						</div>
						
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Create User</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</article>


<article>
	<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered " role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="userModalLabel"><i class="fa fa-edit"></i> Edit User Information</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div style="padding:10px">
					<center>
						<img src="<?php echo e(url('images/loader.gif')); ?>" id="loader" />
					</center>
					<div id="userEditor"></div>
				</div>
			</div>
		</div>
	</div>
</article>

<article>
	<div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered " role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="userModalLabel"><i class="fa fa-key"></i> Change Password</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div style="padding:10px">
					<center>
						<img src="<?php echo e(url('images/loader.gif')); ?>" id="loaderKey" />
					</center>
					<div id="changePassEditor"></div>
				</div>
			</div>
		</div>
	</div>
</article>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>

<script>
function processAddUser() {
    return $("#addUserForm").validationEngine('validate');
}
</script>

<script src="<?php echo e(url('js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(url('js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(url('js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(url('js/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(url('js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(url('js/buttons.flash.min.js')); ?>"></script>
<script src="<?php echo e(url('js/buttons.print.min.js')); ?>"></script>
<script>
	$(document).ready(function () {

		$('body').on('click', '.activate', function() {
			var userid = $(this).attr('userid');
			swal({
				  title: "Activate User Account",
				  text: "",
				  type: "info",
				  showCancelButton: true,
				  closeOnConfirm: false,
				  showLoaderOnConfirm: true
				}, function () {
					var data = {
						userid: userid,
						_token: '<?php echo e(csrf_token()); ?>'
					}
				  	Biggo.talkToServer('<?php echo e(route("users.activate")); ?>', data).then(function(res){
						  window.location = '<?php echo e(route("users.refresh")); ?>';
					});
				});
		});

		$('body').on('click', '.changepassword', function() {
			var userid = $(this).attr('userid');
			var route  = $(this).attr('route');

			$('#changePassEditor').html('');
			$('#loaderKey').show();
			$.get(route, function(res) {
				$('#changePassEditor').html(res);
				$('#loaderKey').hide();
			});
		});

		$('body').on('click', '#changePasswordX', function() {
		
			var valid = $("#formEditUserPasswordForm").validationEngine('validate');
			var route = $(this).attr('route');
			var refreshURL = $(this).attr('refreshURL')
			if(valid) {
				$("#formEditUserPassword").css('opacity', 0.2);
				$(this).prop('disabled', true);
				$(this).css('cursor', 'wait');
				var data = $("#formEditUserPasswordForm").serializeArray();

				Biggo.talkToServer(route, data).then(function(res){

					$('#changePasswordX').prop('disabled', false);
					$('#changePasswordX').css('cursor', '');
					$("#formEditUserPassword").css('opacity', 1);
					if(res.error) {
						Biggo.showFeedBack(userEditForm, res.msg, res.error);
					}

					window.location = refreshURL;
					
					
				});
			}
		});

		$('body').on('click', '.deactivate', function() {
			var userid = $(this).attr('userid');
			swal({
				  title: "De-activare User Account",
				  text: "",
				  type: "info",
				  showCancelButton: true,
				  closeOnConfirm: false,
				  showLoaderOnConfirm: true
				}, function () {
					var data = {
						userid: userid,
						_token: '<?php echo e(csrf_token()); ?>'
					}
				  	Biggo.talkToServer('<?php echo e(route("users.deactivate")); ?>', data).then(function(res){
						  window.location = '<?php echo e(route("users.refresh")); ?>';
					});
				});
		});

		$('body').on('click', '#updateUser', function() {
			var valid = $("#userEditForm").validationEngine('validate');
			var route = $(this).attr('route');
			var refreshURL = $(this).attr('refreshURL')
			if(valid) {
				
				$("#formEditUser").css('opacity', 0.2);
				$(this).prop('disabled', true);
				$(this).css('cursor', 'wait');
				var data = $("#userEditForm").serializeArray();

				Biggo.talkToServer(route, data).then(function(res){

					$('#updateUser').prop('disabled', false);
					$('#updateUser').css('cursor', '');
					$("#formEditUser").css('opacity', 1);
					if(res.error) {
						Biggo.showFeedBack(userEditForm, res.msg, res.error);
					}

					window.location = refreshURL;
					
					
				});
				
			}
		})

		$('body').on('click', '.editUser', function() {
			var userid = $(this).attr('userid');
			var route = $(this).attr('route');
			$('#loader').show();
			$('#userEditor').html('');
			$.get(route, function(res) {
				$('#loader').hide();
				$('#userEditor').html(res);
			})
		});

		var table = $('#dataTable').DataTable({
			"bInfo": false,
			"pageLength": 15,
			"lengthMenu": [[10, 15 , 25, 50, -1], [10, 15, 25, 50, "All"]],
				dom: "Blfrtip",
				buttons: [
					{
						extend: "excel",
						className: "btn-sm btn-success px-3 py-2",
						title: 'Users'
					},
					{
						extend: "pdf",
						className: "btn-sm btn-danger px-3 py-2",
						title: 'Users'
					},
					{
						extend: "print",
						className: "btn-sm px-3 py-2",
						title: 'Users'
					}
				]
		});


		$('#userModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget) // Button that triggered the modal
			var user_name = button.data('user_name') // Extract info from data-* attributes
			var user_email = button.data('user_email') // Extract info from data-* attributes
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			var modal = $(this)
			modal.find('.modal-title').text(user_name)
			modal.find('.modal-body input#user-name').val(user_name)
			modal.find('.modal-body input#user-email').val(user_email)
		});
	});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>