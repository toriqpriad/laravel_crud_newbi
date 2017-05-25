@extends('layouts.app')
@section('title','My Questions')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <b>My Questions ({{$countall}}) </b>
          <button class="pull-right btn btn-xs btn-primary" onclick="Add()">Add a question</button>
        </div>
      </div>
      @if (session('add_success'))
      <div class="alert alert-success">
        {{ session('add_success') }}
      </div>
      @endif
      <div class="row">

        @unless($array_questions)
        <div class="col-md-12">
          No questions
        </div>
        @endunless

        @foreach($array_questions as $question)
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-body">
              {{ $question['content']}}
            </div>
            <div class="panel-footer">
              <span><i class="fa fa-comment"></i>&nbsp;{{$question['answer_count']}}</span>&nbsp;&nbsp;
              <span><i class="fa fa-calendar"></i>&nbsp;{{$question['created_at']}}</span>
              <a class="btn btn-default btn-xs pull-right" href="{{ route('detail_questions',['id' => $question['id'] ]) }}">Detail</a>
              <button class="btn btn-default btn-xs pull-right" style='margin-right:2px;' onclick="Delete({{$question['id']}})">Delete</button>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
<script>
function Add(){
  $('#addModal').modal('show');
}

function Edit(id){
  $.ajax({
    type: 'GET',
    url: "http://localhost:8000/get_question/"+id,
    success: function(res) {
      var response = JSON.parse(res);
      $('#EditModal').modal('show');
      $('#question').text(response.detail.content);
      $('#question_id').val(response.detail.id);
      $.each(response.answers, function(key, each) {
        $('#answers_data').html('<div class="well">'+each.content+'</div>');
      });

    }
  })

}



function Delete(id){
  $('#question_delete_id').val(id);
  $('#DeleteModal').modal('show');
}
</script>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Question Modal</h4>
      </div>
      <form method="post" action="{{ route('add_submit_question') }}">
        <div class="modal-body">
          {{csrf_field()}}
          <div class="form-group">
            <label for="exampleInputEmail1">New Question</label>
            <textarea class="form-control" name="question"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit" >Add</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
        <div id="answers_data"></div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div><!-- /.modal -->

<div class="modal fade" id="EditModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Question Modal</h4>
      </div>
      <form method="post" action="{{ route('edit_submit_question') }}">
        <div class="modal-body">
          {{csrf_field()}}
          <div class="form-group">
            <label for="exampleInputEmail1"> Question</label>
            <textarea class="form-control" id="question" name="question"></textarea>
            <input type="hidden" id="question_id" name="question_id" value=''>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit" >Edit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Preview</h4>
      </div>
      <form method="post" action="{{ route('delete_submit_question') }}">
        {{csrf_field()}}
        <div class="modal-body">
          Delete this question will also delete answers, are you sure to delete this question ?
        </div><!-- /.modal-content -->
        <input type="hidden" id="question_delete_id" name="question_delete_id" >
        <div class="modal-footer">
          <button class="btn btn-danger" type="submit" >Delete</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div><!-- /.modal -->
@endsection
