<?php 

$q = \App\Question::find($id); 

$qz = \App\Quiz::find($quizid);

?>
<h5 class="d-flex"><span class="question-number"><?php echo e($q->qn_no < 10 ? '0'.$q->qn_no : $q->qn_no); ?>.</span> <span>
	<?php echo e($q->question); ?>

</span></h5>
<div class="answers-option ml-5 ">
	<?php
		$answers = \App\Answer::where('question_id', $q->id)->get();
	?>
	<?php $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

	<?php $checked = $a->correct == 0 ? '' : 'checked="true"'; ?>

	<?php if($q->category == "single"): ?>
	<div class="custom-control custom-radio">
		<input type="radio" id="question<?php echo e($q->id); ?>_answer_<?php echo e($a->id); ?>_single" <?php echo $checked; ?> name="customRadio_<?php echo e($a->id); ?>" class="custom-control-input">
		<label class="custom-control-label <?php echo e($a->correct == 0 ? '' : 'text-success'); ?>" for="question<?php echo e($q->id); ?>_answer<?php echo e($a->id); ?>"><?php echo e($a->answer); ?></label>
	</div>
	<?php endif; ?>
	<?php if($q->category == "multiple"): ?>
	<div class="custom-control custom-checkbox">
		<input type="checkbox" class="custom-control-input" <?php echo $checked; ?> id="question<?php echo e($q->id); ?>_answer<?php echo e($a->id); ?>">
		<label class="custom-control-label <?php echo e($a->correct == 0 ? '' : 'text-success'); ?>" for="question<?php echo e($q->id); ?>_answer<?php echo e($a->id); ?>"><?php echo e($a->answer); ?></label>
	</div>
	<?php endif; ?>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	
	<?php if($qz->status == 0): ?>
	<hr/>
	<p> <span style="cursor: pointer;" qn="<?php echo e($q->id); ?>" quizid="<?php echo e($quizid); ?>" class="editQn" route="<?php echo e(route('question.edit',$q->id)); ?>"><i class="fa fa-edit text-success"></i> Edit </span> <!--  <span style="cursor: pointer;" class="deleteQn" route="<?php echo e(route('quiz.destroy',$q->id)); ?>"><i class="fa fa-trash text-danger"></i> Delete </span> --></p>
	<?php else: ?>
	
	<?php endif; ?>

	
</div>