@extends('layouts.app')
@section('title','Detail Question')
@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <i>{{$detail->username}} at {{$detail->question_date}}</i>
        </div>
        <div class="panel-body">
          {{csrf_field()}}
          <h3 id="content">{{$detail->content}} </h3>
          @if($detail->user_id == Auth::user()->id)
          <button type="button" class="btn btn-default btn-xs " id="editbtn" onclick="EditQuestion({{$detail->question_id}})">Edit</button> </h3>
          @endif
          <hr>
          <div class="list-group" id="answers_data">
            <?php
            if ($count != 0) {
                foreach ($answers as $ans) {
                    $id_span = "id_".$ans->id_answer;
                    $comment_id_span = "comment_".$ans->id_answer; ?>
                <div  id="<?=$id_span?>">
                  <span class="list-group-item" id="<?= $comment_id_span ?>">
                    "<?=$ans->content?>" . <small><i>by <?=$ans->username ?> at <?=$ans->update_answer?></i></small>
                    @if($ans->user_id == Auth::user()->id || $detail->user_id == Auth::user()->id )
                    <div class="pull-right btn-group btn-group-xs" role="group" aria-label="...">
                      <button type="button" class="btn btn-default" onclick="DelComment(<?=$ans->id_answer?>,<?=$detail->question_id?>)">Delete</button>
                      <button type="button" class="btn btn-default" onclick="EditComment(<?=$ans->id_answer?>,<?=$detail->question_id?>)">Edit</button>
                    </div>
                    @endif
                  </span>
                </div>
                <?php

                } ?>
              <?php

            } ?>

          </div>
        </div>

        @if($detail->user_id != Auth::user()->id)
        <div class="modal-footer" id="comment_form">
          <div class="row">
            <div class="col-md-10 col-sm-10 col-xs-12" >
              {{csrf_field()}}
              <input type="text" class="form-control" id="new_comment">
              <input type="hidden" id="question_id"  value="{{$detail->question_id}}">
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
              <button class="btn btn-default pull-right" onclick="AddComment()">Comment</button>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>

  <script>

  function EditQuestion(question_id){
    var new_html =
    '<textarea class="form-control" id="new_question" name="question"></textarea>'+
    '<input type="hidden" id="question_id_update" name="question_id" >';
    $("#content").html(new_html);
    $("#new_question").val('{{$detail->content}}');
    $("#question_id_update").val('{{$detail->question_id }}');
    $("#editbtn").attr('onclick','updateQuestion()');
  }

  function updateQuestion(){
    var new_question = $('#new_question').val();
    var question_id = $('#question_id_update').val();
    var csrf_token = $("[name='_token']").val();
    var data = {"new_question" : new_question, "question_id" : question_id};
    $.ajax({
      type: 'POST',
      url: "http://localhost:8000/edit_submit_question/",
      headers: {
        'X-CSRF-TOKEN': csrf_token
      },
      data : data ,
      success: function(res) {
        var response = JSON.parse(res);
        if(response == "OK"){
          $("#content").html(new_question);
        } else {
          alert();
        }

      }
    })
  }

  function AddComment(){
    var new_comment = $('#new_comment').val();
    var question_id = $('#question_id').val();
    var csrf_token = $("[name='_token']").val();
    var data = {"new_comment" : new_comment, "question_id" : question_id};
    $.ajax({
      type: 'POST',
      url: "http://localhost:8000/comment_submit_question/",
      headers: {
        'X-CSRF-TOKEN': csrf_token
      },
      data : data ,
      success: function(res) {
        var response = JSON.parse(res);
        if(response == "OK"){
          location.reload();
        } else {
          alert();
        }

      }
    })
  }

  function EditComment(answer_id,question_id){
    $("#comment_"+answer_id).text('display','none');
    var new_html = '<div class="row"><div class="col-md-10"><input type="text" class="form-control input-xs" id="new_edit_comment"></div><div class="col-md-2"><div class="pull-right btn-group btn-group-xs" role="group" aria-label="..."><button type="button" class="btn btn-default" onclick="DelComment('+answer_id+','+question_id+')">Delete</button><button type="button" class="btn btn-default" onclick="SaveNewEditComment('+answer_id+','+question_id+')">Save</button></div></div>';
    $("#comment_"+answer_id).html(new_html);
  }

  function SaveNewEditComment(answer_id,question_id){
    var new_comment = $('#new_edit_comment').val();
    var answer_id = answer_id;
    var csrf_token = $("[name='_token']").val();
    var data = {"new_comment" : new_comment , "answer_id" : answer_id};
    $.ajax({
      type: 'POST',
      url: "http://localhost:8000/comment_update_submit_question/",
      data : data ,
      headers: {
        'X-CSRF-TOKEN': csrf_token
      },
      success: function(res) {
        var response = JSON.parse(res);
        if(response == "OK"){
          location.reload();
        }
      }
    })
  }

  function DelComment(answer_id,question_id){
    var answer_id = answer_id;
    $.ajax({
      type: 'GET',
      url: "http://localhost:8000/comment_delete/"+answer_id,
      success: function(res) {
        var response = JSON.parse(res);
        if(response == "OK"){
          $("#id_"+answer_id).css('display','none');
        }
      }
    })
  }
  </script>
  @endsection
