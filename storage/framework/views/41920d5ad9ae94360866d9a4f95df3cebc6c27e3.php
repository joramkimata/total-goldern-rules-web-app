<?php $__env->startSection('title', 'Quiz Staff'); ?>

<?php $__env->startSection('content'); ?>


    <article>
        <div class="modal fade" id="userModalQuizResults" data-backdrop="static" data-keyboard="false" tabindex="-1"
             role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div style="padding:10px">
                        <center>
                            <img src="<?php echo e(url('images/loader.gif')); ?>" id="loaderRex"/>
                        </center>
                        <div id="quizResxEditor"></div>
                    </div>
                </div>
            </div>
        </div>
    </article>


    <!-- CONTENT BLOCK HERE -->


    <div class="page-class">
        <div class="row">
            <div class="col-md-7 mx-auto">
                <table id="dataTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th style="display: none">#</th>
                        <th>Quiz Name</th>
                        <th>Score</th>
                        <th>Ranked</th>
                        <th>View Qestions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //$quizes = \App\Quiz::where('is_published', 1)->orderBy('id', 'asc')->get();

                    $quizes = \DB::table('quiz')->select('*')->where('is_published', 1)->orderBy('id', 'desc')->get();


                    $i = 0;

                    function myscore($quid)
                    {
                        return \DB::table('answers')->join('attempts', 'attempts.aid', '=', 'answers.id')
                            ->select('*')
                            ->where('attempts.quiz_id', $quid)->where('answers.correct', 1)
                            ->where('attempts.user_id', auth()->user()->id)
                            ->count();
                    }


                    function ansx($id)
                    {
                        return \DB::table('answers')->join('questions', 'questions.id', '=', 'answers.question_id')->select('*')->where('questions.quiz_id', $id)->where('answers.correct', 1)->count();
                    }



                    ?>
                    <?php if(count($quizes)): ?>

                        <?php $axxxx = []; ?>

                        <?php $__currentLoopData = $quizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php

                            // $c = \App\Quizfeedback::where('user_id', auth()->user()->id)->where('quiz_id', $q->id)->where('seen', 1)->get();



                            $c = \App\Attempt::where('user_id', auth()->user()->id)->where('quiz_id', $q->id)->orderBy('id', 'asc')->get();

                            ?>

                            <?php
                            $r = 1;

                            foreach (\App\User::where('role_id', 2)->get() as $u) {
                                $rank = \DB::table('answers')->join('attempts', 'attempts.aid', '=', 'answers.id')
                                    ->select('*')
                                    ->where('attempts.quiz_id', $q->id)->where('answers.correct', 1)
                                    ->where('attempts.user_id', $u->id)
                                    ->count();
                                $ranks[$u->id] = $r;
                                $r++;
                            }

                            arsort($ranks);



                            ?>
                            <?php if(count($c) != 0): ?>


                                <?php if($q->quiz_status != 'RESULTS_OUT'): ?>

                                    <?php

                                        $axxxx[] = $q;

                                    ?>


                                    <tr>
                                        <td style="display: none"><?php echo e($q->id); ?></td>
                                        <td><?php echo e($q->quiz_name); ?></td>
                                        <td>---</td>
                                        <td>---</td>
                                        <td>
                                            <button class="btn btn-success btn-sm viewResultx" data-toggle="modal"
                                                    route="<?php echo e(route('quiz.results.seen', ['id' => $q->id, 'attempt' => true])); ?>"

                                                    data-target="#userModalQuizResults">View My Attempts
                                            </button>
                                        </td>
                                    </tr>
                                <?php else: ?>

                                    <?php

                                    $axxxx[] = $q;

                                    ?>

                                    <tr>
                                        <td style="display: none"><?php echo e($q->id); ?></td>
                                        <td><?php echo e($q->quiz_name); ?></td>
                                        <td><?php echo e(myscore($q->id)); ?> out of <?php echo e(ansx($q->id)); ?></td>
                                        <td><?php echo e($ranks[auth()->user()->id]); ?>

                                            /<?php echo e(\App\User::where('role_id', 2)->count()); ?></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm viewResultx" data-toggle="modal"
                                                    route="<?php echo e(route('quiz.results.seen', ['id' => $q->id, 'attempt' => false])); ?>"

                                                    data-target="#userModalQuizResults">View Results
                                            </button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                </table>

                <?php
                   // dd($axxxx);
                ?>
            </div>
        </div>
    </div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(url('js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/matchHeight.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/nprogress.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/custom.min.js')); ?>"></script>


    <script src="<?php echo e(url('js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/dataTables.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/buttons.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/buttons.html5.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/buttons.flash.min.js')); ?>"></script>
    <script src="<?php echo e(url('js/buttons.print.min.js')); ?>"></script>
    <script>
        $(document).ready(function () {

            $('body').on('click', '.viewResultx', function () {
                var route = $(this).attr('route');
                $('#loaderRex').show();
                $('#quizResxEditor').html('');
                $.get(route, function (res) {
                    $('#loaderRex').hide();
                    $('#quizResxEditor').html(res);
                });
            });


            var table = $('#dataTable').DataTable({
                "order": [[ 0, "desc" ]]
            });


            $('#userModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var user_name = button.data('user_name') // Extract info from data-* attributes
                var user_email = button.data('user_email') // Extract info from data-* attributes
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                var modal = $(this)
                modal.find('.quiz-name-title').text(user_name)
                modal.find('.modal-body input#user-name').val(user_name)
                modal.find('.modal-body input#user-email').val(user_email)
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>