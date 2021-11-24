<?php $q = \App\Question::find($id); ?>
<form id="editQuestionFormX">
    <div style="padding: 10px" id="qnx<?php echo e($id); ?>">
        <h5 class="d-flex"><span class="question-number"><?php echo e($q->qn_no < 10 ? '0'.$q->qn_no : $q->qn_no); ?>.</span> <span>
			
		</span></h5>


        <textarea class="form-control" name="question" id="editXQn" style="width: 100%" rows="4"><?php echo e($q->question); ?></textarea><br/>


        <div class="answers-option ml-5 ">

            <?php if($q->qn_photo_location != ""): ?>
                <br/>
                <img id="imgEditX" src="<?php echo e($q->qn_photo_location); ?>" style="width: 100%; height: 350px; display: block"/>
                <br/>
                <div class="form-group">
                    <div class="input-group">
                        <button id="attachPhotoEditXj" type="button" class="btn btn-success"><i class="fa fa-paperclip"></i>
                            Change Photo
                        </button>
                        <input id="attachPhotoEditXjx" type="file" name="attachedPhotoEditXjx" hidden/>
                    </div>
                </div>
                <script type="text/javascript">
                    var fileInput = document.getElementById('attachPhotoEditXjx');
                    fileInput.addEventListener('change', function(e) {
                        var file = fileInput.files[0];
                        var imageType = /image.*/;
                        if (file.type.match(imageType)) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $('#imgEditX').prop('src', reader.result);
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                </script>
            <?php else: ?>
                <div class="form-group">
                    <div class="input-group">
                        <button id="attachPhotoEdit" type="button" class="btn btn-success"><i class="fa fa-paperclip"></i>
                            Attach Photo
                        </button>
                        <input id="attachedPhotoEdit" type="file" name="attachedPhotoEdit" hidden/>

                    </div>
                </div>
                <div id="photoAttachmentEdit"></div>
                <script src="<?php echo e(url('js/biggo.js')); ?>"></script>
                <script type="text/javascript">
                    $(function() {
                        Biggo.imageUploadDisplay('attachedPhotoEdit', 'photoAttachmentEdit', 340, 240)
                    });
                </script>
            <?php endif; ?>

            <hr/>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="answer-type">Answer Type</label>
                </div>
                <select class="custom-select answer-type" name="category" id="answerTypeEdit">
                    <?php if($q->category == "single"): ?>
                        <option value="single">Single Answer</option>
                        <option value="multiple">Multiple Answer</option>
                    <?php else: ?>
                        <option value="multiple">Multiple Answer</option>
                        <option value="single">Single Answer</option>
                    <?php endif; ?>
                </select>
            </div>

            <?php
            $answers = \App\Answer::where('question_id', $q->id)->get();
            $ccc = count($answers);
            $aidd = 0;
            ?>
            <?php $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ix => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php
                $chk = $ix + 1;
                ?>

                <?php if($ccc == $chk): ?>
                    <?php $aidd = $a->id; ?>
                <?php endif; ?>

                <?php $checked = $a->correct == 0 ? '' : 'checked="true"'; ?>

                <?php if($q->category == "single"): ?>
                    <div class="" style="display: flex; justify-content: space-between;" id="answerDel<?php echo e($a->id); ?>">
                        <input type="checkbox" style="display: none" class=""/>
                        <input type="radio" <?php echo $checked; ?> name="customRadio_<?php echo e($q->id); ?>" class="editRadioAnswer">
                        <label style="width: 100%" class="<?php echo e($a->correct == 0 ? '' : 'text-success'); ?>"
                               for="question<?php echo e($q->id); ?>_answer<?php echo e($a->id); ?>">
                            <textarea class="form-control" rows="2"><?php echo e($a->answer); ?></textarea><br/>
                        </label>
                        <span class="deleteEditAnwr" routeEd="<?php echo e(route('question.edit', $id)); ?>"
                              qn=<?php echo e($id); ?> aid="<?php echo e($a->id); ?>"
                              quizid="<?php echo e($quizid); ?>" route="<?php echo e(route('question.answer.delete',$a->id)); ?>"> <i
                                    style="margin-left: 12px;cursor: pointer;"
                                    class="fa fa-trash text-danger"></i></span>
                    </div>
                <?php endif; ?>
                <?php if($q->category == "multiple"): ?>
                    <div class="" style="display: flex; justify-content: space-between;" id="answerDel<?php echo e($a->id); ?>">
                        <input class="single" style="display: none" type="radio"/>
                        <input type="checkbox" id="multiple<?php echo e($a->id); ?>" class="multiple"
                               <?php echo $checked; ?> id="question<?php echo e($q->id); ?>_answer<?php echo e($a->id); ?>">
                        <label style="width: 100%" class="<?php echo e($a->correct == 0 ? '' : 'text-success'); ?>"
                               for="question<?php echo e($q->id); ?>_answer<?php echo e($a->id); ?>">
                            <textarea aid="<?php echo e($a->id); ?>" class="form-control multipleX" rows="2"><?php echo e($a->answer); ?></textarea><br/>
                        </label>
                        <span class="deleteEditAnwr" routeEd="<?php echo e(route('question.edit', $id)); ?>"
                              qn=<?php echo e($id); ?> aid="<?php echo e($a->id); ?>"
                              quizid="<?php echo e($quizid); ?>" route="<?php echo e(route('question.answer.delete',$a->id)); ?>"> <i
                                    style="margin-left: 12px;cursor: pointer;"
                                    class="fa fa-trash text-danger"></i></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div id="answersAreaX" i="<?php echo e($aidd); ?>" style="width: 100%"></div>

            <div class="form-group">
                <div class="input-group mb-3">
                    <input type="text" id="answerBody" class="form-control add-answer-input">
                    <div class="input-group-append">
                        <button class="btn btn-success add-answer-button" type="button" id="addAnswerX"><i
                                    class="fa fa-plus"></i> Add Answer Option
                        </button>
                    </div>
                </div>
            </div>

            <hr/>
            <p class="well"><span style="cursor: pointer;" qn="<?php echo e($q->id); ?>" quizid="<?php echo e($quizid); ?>" class="updateQn"
                                  route="<?php echo e(route('question.update',$q->id)); ?>"><i
                            class="fa fa-save text-info"></i> Update </span> | <span style="cursor: pointer;"
                                                                                     quizid="<?php echo e($quizid); ?>"
                                                                                     qn="<?php echo e($q->id); ?>"
                                                                                     class="cancelQn"
                                                                                     route="<?php echo e(route('quiz.cancel',$q->id)); ?>"><i
                            class="fa fa-undo text-danger"></i> Cancel </span></p>

        </div>
    </div>
</form>



