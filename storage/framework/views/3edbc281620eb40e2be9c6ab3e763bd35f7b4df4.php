<?php $q = \App\Question::find($id); ?>

<div style="padding: 10px" id="qnx<?php echo e($id); ?>">
<h5 class="d-flex"><span class="question-number"><?php echo e($q->qn_no < 10 ? '0'.$q->qn_no : $q->qn_no); ?>.</span> <span>
			
		</span></h5>
		<textarea class="form-control" style="width: 100%" rows="4"><?php echo e($q->question); ?></textarea><br/>
		<div class="answers-option ml-5 ">
			<?php
				$answers = \App\Answer::where('question_id', $q->id)->get();
			?>
			<?php $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

			<?php $checked = $a->correct == 0 ? '' : 'checked="true"'; ?>

			<?php if($q->category == "single"): ?>
			<div class="" style="display: flex; justify-content: space-between;" id="answerDel<?php echo e($a->id); ?>">
				<input type="radio"  <?php echo $checked; ?> name="customRadio_<?php echo e($q->id); ?>" class="editRadioAnswer"> 
				<label style="width: 100%" class="<?php echo e($a->correct == 0 ? '' : 'text-success'); ?>" for="question<?php echo e($q->id); ?>_answer<?php echo e($a->id); ?>">
					<textarea class="form-control"  rows="2"><?php echo e($a->answer); ?></textarea><br/>
				</label>
				<span class="deleteEditAnwr" routeEd="<?php echo e(route('question.edit', $id)); ?>" qn=<?php echo e($id); ?> aid="<?php echo e($a->id); ?>" quizid="<?php echo e($quizid); ?>" route="<?php echo e(route('question.answer.delete',$a->id)); ?>"> <i  style="margin-left: 12px;cursor: pointer;" class="fa fa-trash text-danger"></i></span>
			</div>
			<?php endif; ?>
			<?php if($q->category == "multiple"): ?>
			<div class="" style="display: flex; justify-content: space-between;">
				<input type="checkbox" class="" <?php echo $checked; ?> id="question<?php echo e($q->id); ?>_answer<?php echo e($a->id); ?>">
				<label style="width: 100%" class="<?php echo e($a->correct == 0 ? '' : 'text-success'); ?>" for="question<?php echo e($q->id); ?>_answer<?php echo e($a->id); ?>">
					<textarea class="form-control"  rows="2"><?php echo e($a->answer); ?></textarea><br/>
				</label>
				<span class="deleteEditAnwr" routeEd="<?php echo e(route('question.edit', $id)); ?>" qn=<?php echo e($id); ?> aid="<?php echo e($a->id); ?>" quizid="<?php echo e($quizid); ?>" route="<?php echo e(route('question.answer.delete',$a->id)); ?>"> <i  style="margin-left: 12px;cursor: pointer;" class="fa fa-trash text-danger"></i></span> 	
			</div>
			<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

			<div id="answersAreaX"></div>
			
			<div class="form-group">
				<div class="input-group mb-3">
					<input type="text" id="answerBody" class="form-control add-answer-input">
					<div class="input-group-append">
						<button class="btn btn-success add-answer-button" type="button" id="addAnswerX"><i class="fa fa-plus"></i> Add Answer Option</button>
					</div>
				</div>
			</div>
			
			<hr/>
			<p class="well"> <span style="cursor: pointer;" qn="<?php echo e($q->id); ?>" quizid="<?php echo e($quizid); ?>" class="updateQn" route="<?php echo e(route('question.update',$q->id)); ?>"><i class="fa fa-save text-info"></i> Update </span> |  <span style="cursor: pointer;" quizid="<?php echo e($quizid); ?>" qn="<?php echo e($q->id); ?>" class="cancelQn" route="<?php echo e(route('quiz.cancel',$q->id)); ?>"><i class="fa fa-undo text-danger"></i> Cancel </span></p>
			
		</div>
</div>

