<?php $q = \App\Question::find($id); ?>
<form id="editQuestionFormX">
    <div style="padding: 10px" id="qnx{{$id}}">
        <h5 class="d-flex"><span class="question-number">{{$q->qn_no < 10 ? '0'.$q->qn_no : $q->qn_no}}.</span> <span>
			
		</span></h5>


        <textarea class="form-control" id="editXQn" style="width: 100%" rows="4">{{$q->question}}</textarea><br/>


        <div class="answers-option ml-5 ">

            @if($q->qn_photo_location != "")
                <br/>
                <img  src="{{$q->qn_photo_location}}" style="width: 100%; height: 350px; display: block"/>
                <br/>
                <div class="form-group">
                    <div class="input-group">
                        <button id="attachPhoto" type="button" class="btn btn-success"><i class="fa fa-paperclip"></i>
                            Change Photo
                        </button>
                        <input id="attachedPhoto" type="file" name="attachedPhoto" hidden/>
                    </div>
                </div>
            @else
                <div class="form-group">
                    <div class="input-group">
                        <button id="attachPhoto" type="button" class="btn btn-success"><i class="fa fa-paperclip"></i>
                            Attach Photo
                        </button>
                        <input id="attachedPhoto" type="file" name="attachedPhoto" hidden/>

                    </div>
                </div>
                <div id="photoAttachment"></div>
            @endif

            <hr/>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="answer-type">Answer Type</label>
                </div>
                <select class="custom-select answer-type" name="category" id="answerTypeEdit">
                    @if($q->category == "single")
                        <option value="single">Single Answer</option>
                        <option value="multiple">Multiple Answer</option>
                    @else
                        <option value="multiple">Multiple Answer</option>
                        <option value="single">Single Answer</option>
                    @endif
                </select>
            </div>

            <?php
            $answers = \App\Answer::where('question_id', $q->id)->get();
            $ccc = count($answers);
            $aidd = 0;
            ?>
            @foreach($answers as $ix => $a)

                <?php
                $chk = $ix + 1;
                ?>

                @if($ccc == $chk)
                    <?php $aidd = $a->id; ?>
                @endif

                <?php $checked = $a->correct == 0 ? '' : 'checked="true"'; ?>

                @if($q->category == "single")
                    <div class="" style="display: flex; justify-content: space-between;" id="answerDel{{$a->id}}">
                        <input type="checkbox" style="display: none" class=""/>
                        <input type="radio" {!! $checked !!} name="customRadio_{{$q->id}}" class="editRadioAnswer">
                        <label style="width: 100%" class="{{ $a->correct == 0 ? '' : 'text-success' }}"
                               for="question{{$q->id}}_answer{{$a->id}}">
                            <textarea class="form-control" rows="2">{{$a->answer}}</textarea><br/>
                        </label>
                        <span class="deleteEditAnwr" routeEd="{{route('question.edit', $id)}}"
                              qn={{$id}} aid="{{$a->id}}"
                              quizid="{{$quizid}}" route="{{route('question.answer.delete',$a->id)}}"> <i
                                    style="margin-left: 12px;cursor: pointer;"
                                    class="fa fa-trash text-danger"></i></span>
                    </div>
                @endif
                @if($q->category == "multiple")
                    <div class="" style="display: flex; justify-content: space-between;" id="answerDel{{$a->id}}">
                        <input class="single" style="display: none" type="radio"/>
                        <input type="checkbox" id="multiple{{$a->id}}" class="multiple"
                               {!! $checked !!} id="question{{$q->id}}_answer{{$a->id}}">
                        <label style="width: 100%" class="{{ $a->correct == 0 ? '' : 'text-success' }}"
                               for="question{{$q->id}}_answer{{$a->id}}">
                            <textarea aid="{{$a->id}}" class="form-control multipleX" rows="2">{{$a->answer}}</textarea><br/>
                        </label>
                        <span class="deleteEditAnwr" routeEd="{{route('question.edit', $id)}}"
                              qn={{$id}} aid="{{$a->id}}"
                              quizid="{{$quizid}}" route="{{route('question.answer.delete',$a->id)}}"> <i
                                    style="margin-left: 12px;cursor: pointer;"
                                    class="fa fa-trash text-danger"></i></span>
                    </div>
                @endif
            @endforeach

            <div id="answersAreaX" i="{{$aidd}}" style="width: 100%"></div>

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
            <p class="well"><span style="cursor: pointer;" qn="{{$q->id}}" quizid="{{$quizid}}" class="updateQn"
                                  route="{{route('question.update',$q->id)}}"><i
                            class="fa fa-save text-info"></i> Update </span> | <span style="cursor: pointer;"
                                                                                     quizid="{{$quizid}}"
                                                                                     qn="{{$q->id}}"
                                                                                     class="cancelQn"
                                                                                     route="{{route('quiz.cancel',$q->id)}}"><i
                            class="fa fa-undo text-danger"></i> Cancel </span></p>

        </div>
    </div>
</form>

