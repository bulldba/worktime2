@foreach ($feedbacks as $feedback)
<div class="panel panel-default">

    <div class="panel-heading">
      <div class="row">
<div class="col-sm-2">
  <a name="feedback.{{$feedback->id}}">#{{$feedback->id}}</a>
</div>
<div class="col-sm-2">
作者：{{$users[$feedback->author]->name}}
</div>
<div class="col-sm-2">
提交：{{$feedback->created_at}}
</div>
<div class="col-sm-2">
修改：{{$feedback->updated_at}}
</div>
<div class="col-sm-2">
<a href="/feedback/edit/{{$feedback->id}}">重新编辑</a>
</div>
      </div>
    </div>
    <div class="panel-body">
{!!$feedback->message!!}
    </div>
    <!-- /.panel-body -->
</div>
@endforeach


<blockquote><p class="text-primary">提交完成情况、测试反馈、其他意见......</p></blockquote>
<form method="POST" action="/feedback/store" onsubmit="return commitFeedback( );">
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<input type="hidden" id="formFeedbackContent" name="row[message]">
<input type="hidden" name="row[pid]" value="{{$task->id}}">
<div class="row line">
<div class="col-lg-12">
      <textarea id="summernote" height="300"></textarea>
</div>
</div>
<div class="row line">
    <div class="col-sm-4">
        <button type="submit" class="btn btn-danger btn-lg btn-block"> 提交反馈 </button>
    </div>
</div>

</form>

<script type="text/javascript">
var users = {!!$users->toJson( )!!};
var tags = {!!$tags->toJson( )!!};

$(document).ready(function( ) {
  initEditor( "summernote" );
});
</script>
