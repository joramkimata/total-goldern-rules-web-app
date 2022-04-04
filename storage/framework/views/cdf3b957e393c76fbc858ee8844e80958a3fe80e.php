<?php $__env->startSection('title', 'Manage Reminders'); ?>

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

    <div class="modal fade" id="noneAttendes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i
                                class="fa fa-users"></i> None Attempted Staff List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="alert alert-info">
                            Total Staff: <span id="totl"></span>
                        </div>
                        <table id="dataTablex" class="table table-dark">
                            <thead>
                            <th>#</th>
                            <th>Email</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12" style="background-color: white; padding: 12px">

            <form id="reminderForm">
                <h5><i class="fa fa-bell"></i> Create New Reminder</h5>
                <hr/>
                <div class="form-group">
                    <label for="quiz" class="col-form-label">Select Quiz:</label>
                    <select name="quizId" id="quiz" class="form-control">
                        <option></option>
                        <?php $__currentLoopData = \App\Quiz::where('is_published',1)->where('quiz_status', 'EXECUTION_STARTED')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($q->id); ?>"><?php echo e($q->quiz_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reminder_subject" class="col-form-label">Reminder Subject:</label>
                    <textarea name="reminder_subject" class="validate[required] form-control" id="reminder_subject"
                              data-errormessage-value-missing="Subject is required!"
                    ></textarea>
                </div>
                <div class="form-group">
                    <label for="reminder_body" class="col-form-label">Reminder Body:</label>
                    <textarea rows="4" name="reminder_body" class="validate[required] form-control" id="reminder_body"
                              data-errormessage-value-missing="Body is required!"
                    ></textarea>
                </div>
                <hr/>
                <br/>
                <div class="form-group">
                    <button disabled="true" type="button" id="createReminder"
                            redirectUrl="<?php echo e(route("app.reminders.refresh")); ?>"
                            route=<?php echo e(route("app.reminders")); ?> class="hasReminders btn btn-primary
                    "><i class="fa fa-bell"></i> Remind Now</button>
                    <button type="button" data-toggle="modal" data-target="#noneAttendes" disabled="true"
                            class="hasReminders btn btn-success"><i
                                class="fa fa-users"></i> None Attempted Staff List
                    </button>
                </div>
            </form>
        </div>

    </div>

    <hr/>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

    <script src="<?php echo e(url('js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/dataTables.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/buttons.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/buttons.html5.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/buttons.flash.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/buttons.print.min.js')); ?>"></script>

    <script type="text/javascript">

        $(function () {


            $('body').on('change', '#quiz', function () {
                var quiz = $(this).val();
                var data = {
                    quizId: quiz,
                    _token: '<?php echo e(csrf_token()); ?>'
                }

                $('.hasReminders').attr('disabled', true);
                $('#totl').html(0);

                Biggo.talkToServer('<?php echo e(route('quiz.checkreminders')); ?>', data).then(function (res) {
                    if (res.length > 0) {
                        $('.hasReminders').attr('disabled', false);
                        $("#dataTablex").DataTable().clear();

                        var i = 1;
                        res.forEach(function (e) {
                            var email = (e.email);
                            $('#dataTablex').dataTable().fnAddData([
                                i,
                                email,
                            ]);
                            i++;
                        });

                        $('#totl').html(res.length);
                    }
                });
            });

            var emails = []

            $('body').on('click', '#createReminder', function () {

                var route = $(this).attr('route');
                var redirectUrl = $(this).attr('redirectUrl');


                var data = $('#reminderForm').serializeArray();

                var valid = $("#reminderForm").validationEngine('validate');
                if (valid) {

                    $("#reminderForm").css('opacity', 0.2);
                    $(this).prop('disabled', true);
                    $(this).css('cursor', 'wait');


                    data.push({
                        "name": "_token",
                        "value": '<?php echo e(csrf_token()); ?>'
                    });


                    Biggo.talkToServer(route, data).then(function (res) {


                        $('#createReminder').prop('disabled', false);
                        $('#createReminder').css('cursor', '');
                        $("#reminderForm").css('opacity', 1);


                        window.location = redirectUrl;
                        // if(res.error) {
                        // 	Biggo.showFeedBack(userEditForm, res.msg, res.error);
                        // }


                    });
                }
            });
        });


        var table = $('#dataTable').DataTable({
            "bInfo": false,
            "pageLength": 15,
            "lengthMenu": [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
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
    </script>

<?php $__env->stopSection(); ?>	
<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>