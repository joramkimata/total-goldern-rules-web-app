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
                    <img src="<?php echo e(url('images/loader.gif')); ?>" style="display: none" id="loaderx"/>
                </center>

                <div id="staffBox"></div>
            </div>
        </div>
    </div>
</div>


<div class="alert alert-info">
    <h5><i class="fa fa-list"></i> Quiz Reports - <?php echo e($m); ?> of <?php echo e($year); ?>,
        <?php echo e($staff == 'all' ? 'All Staffs': ''); ?>

        <?php echo e($staff == 'attempted' ? 'Staffs who did the QUIZ': ''); ?>

        <?php echo e($staff == 'none_attempted' ? 'Staffs who missed the  QUIZ': ''); ?>,

        <?php echo e($dept == 'all' ? 'All Departments' : '(' . \App\Department::find($dept)->name . ') Department'); ?>

    </h5>
    <hr/>
</div>

<?php if($staff == 'all'): ?>
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
                            <h1><?php echo e($allAttempts); ?></h1>
                            <button s="a" d="<?php echo e($dept); ?>" m="<?php echo e($month); ?>" y="<?php echo e($year); ?>" data-toggle="modal" data-target="#viewStaffs" class="btn viewStaff btn-sm btn-primary"><i
                                        class="fa fa-list"></i> View Staffs
                            </button>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; justify-content: space-between">
                            <h1><?php echo e($allNoneAttempts); ?></h1>
                            <button s="na" d="<?php echo e($dept); ?>" m="<?php echo e($month); ?>" y="<?php echo e($year); ?>" data-toggle="modal" data-target="#viewStaffs" class="btn viewStaff btn-sm btn-primary"><i
                                        class="fa fa-list"></i> View Staffs
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>


<?php if($staff == 'attempted'): ?>
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
                            <h1><?php echo e($allAttempts); ?></h1>
                            <button s="a" d="<?php echo e($dept); ?>" m="<?php echo e($month); ?>" y="<?php echo e($year); ?>" data-toggle="modal" data-target="#viewStaffs" class="btn viewStaff btn-sm btn-primary"><i
                                        class="fa fa-list"></i> View Staffs
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>


<?php if($staff == 'none_attempted'): ?>
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
                            <h1><?php echo e($allNoneAttempts); ?></h1>
                            <button s="na" d="<?php echo e($dept); ?>" m="<?php echo e($month); ?>" y="<?php echo e($year); ?>" data-toggle="modal" data-target="#viewStaffs" class="btn viewStaff btn-sm btn-primary"><i
                                        class="fa fa-list"></i> View Staffs
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>