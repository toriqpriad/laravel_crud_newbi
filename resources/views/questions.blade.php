@extends('layouts.app')
@section('title','My Questions')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
            <div class="col-md-10">
              <b>All Questions ({{$countall}})</b>
            </div>
            <div class="col-md-2">
              <input type='text' class='pull-right form-control input-xs' placeholder='search'>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row  ">
    @unless($array_questions)
    <div class="col-lg-12">
      No questions
    </div>
    @endunless
    @foreach($array_questions as $question)
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-body">
          {{ mb_substr($question['content'], 0, 50)}}
          <!-- {{ $question['content']}} -->
        </div>
        <div class="panel-footer">
          <span><i class="fa fa-user"></i>&nbsp;{{$question['username']}}</span> &nbsp;
          <span><i class="fa fa-comment"></i>&nbsp;{{$question['answer_count']}}</span> &nbsp;
          <span><i class="fa fa-calendar"></i>&nbsp;{{$question['question_update']}}</span>&nbsp;&nbsp;
          <a class="btn btn-default btn-xs pull-right" href="{{ route('detail_questions',['id' => $question['question_id'] ]) }}">Detail</a>
        </div>
      </div>
    </div>
    @endforeach
    
  </div>
</div>

<script>

function Preview(id){
  $.ajax({
    type: 'GET',
    url: "http://localhost:8000/get_question/"+id,
    success: function(res) {
      var response = JSON.parse(res);
      $('#PreviewModal').modal('show');
      if(response.detail.user_id == '<?php echo Auth::user()->id ?>' ){
        $("#comment_form").remove();
      }
      $('#content_data').text(response.detail.content);
      $('#question_id').val(response.detail.id);
      var answer_name = "answers_data_"+id;
      $('.modal-body').append('<div class="list-group" id="'+answer_name+'"></div>')
      $.each(response.answers, function(key, each) {
        $('#answers_data_'+id).append('<span class="list-group-item">'+each.content+'</span>');
      });

    }
  })
}


</script>

<div class="modal fade" id="PreviewModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Preview</h4>
      </div>
      <div class="modal-body">
        <h3  id="content_data"></h3>
        <hr>

      </div><!-- /.modal-content -->
      <div class="modal-footer" id="comment_form">
        <div class="row">
          <div class="col-md-10 col-sm-10 col-xs-12" >
              {{csrf_field()}}
              <input type="text" class="form-control" id="new_comment">
              <input type="hidden" id="question_id" name="question_id" value=''>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-12">
              <button class="btn btn-default pull-right" onclick="AddComment()">Comment</button>
            </div>
          </div>
      </div>
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div><!-- /.modal -->

@endsection
