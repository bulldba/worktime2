@extends('layouts.plane')
@section('title', $task->title)

@section('body')

<p></p>
<div class="row">
  <div class="col-lg-12" id="task-content">
<div class="panel panel-default">
    <div class="panel-heading">
  <h1 id="title">#{{$task->id}} {{$task->title}}</h1>
    </div>
    <div class="panel-body">
<p>
报告人：{{$users[$task->author]->name}}
提交：{{$task->created_at}}
修改：{{$task->updated_at}}
</p>

<hr />

<div id="taskinfo">

<div>
  <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">标题</span>
<input itag="val" id="task-title" name="row[title]" type="text" class="form-control" value="{{$task->title}}">
    </div>
    </div>
</div>

<div class="form-inline">
    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">部门</span>
<select class="form-control" onchange="onChangeDepartment( this.value )">
@include('selection', ['data' => Config::get('worktime.department'), 'slt' => $task->department])
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">负责人</span>
<select itag="val" name="row[leader]" class="form-control" id="leaders">
@foreach ($users as $user)
@if ($user->department == $task->department)
<option value="{{$user->id}}" {{$user->id == $task->leader ? 'selected' : ''}}>{{$user->name}}</option>
@endif
@endforeach
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">类型</span>
<select itag="val" name="row[caty]" class="form-control">
@include('selection', ['data' => Config::get('worktime.caty'), 'slt' => $task->caty])
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">优先级</span>
<select itag="val" name="row[priority]" class="form-control">
@include('selection', ['data' => Config::get('worktime.priority'), 'slt' => $task->priority])
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">项目</span>
<select class="form-control" onchange="onChangePro(this.value);">
@include('selection-users', ['data' => $pros, 'slt' => $task->pro])
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">版本</span>
<select itag="val" name="row[tag]" class="form-control" id="tags">
@foreach ($tags as $tag)
@if ($tag->pro == $task->pro)
<option value="{{$tag->id}}" {{$tag->id == $task->tag ? 'selected' : ''}}>{{$tag->name}}</option>
@endif
@endforeach
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">状态</span>
<select itag="val" name="row[status]" class="form-control">
@include('selection', ['data' => Config::get('worktime.status'), 'slt' => $task->status])
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">限期</span>
<input onclick="showcalendar(event, this, true)" itag="val" name="row[deadline]" type="text" class="form-control" value="{{date('Y-m-d H:i:s', $task->deadline) }}">
    </div>
    </div>
    <button onclick="updateTaskOnchange({{$task->id}});" class="btn btn-danger margin-right">修改属性</button>
    <a href="/task/edit/{{$task->id}}" class="btn btn-primary margin-right">重新编辑</a>
  </div>
</div>

      <hr />

      {!!$task->content!!} </div>
    <!-- /.panel-body -->
</div>


@foreach ($feedbacks as $feedback)
<div class="panel panel-{{$feedback->id%2 ? 'success' : 'info'}}">
  <a name="feedback.{{$feedback->id}}"></a>
    <div class="panel-heading">
      <div class="row">
<div class="col-sm-2">
作者：{{$users[$feedback->author]->name}}
</div>
<div class="col-sm-2">
提交：{{$feedback->created_at}}
</div>
<div class="col-sm-2">
修改：{{$feedback->created_at}}
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

  </div>
</div>


<blockquote><p class="text-primary">提交完成情况、测试反馈、其他意见......</p></blockquote>

<div class="row">
<div class="col-lg-12">
      <textarea id="summernote" height="300"></textarea>
</div>
</div>
<div class="row">
    <div class="col-sm-4">
        <button type="button" onclick="commitFeedback( {{$task->id}} );" class="btn btn-danger btn-lg btn-block"> 提交反馈 </button>
    </div>
    <div class="col-sm-2">
    </div>
</div>

<p></p>

<div id="tpl-feedback" style="display:none">
<div class="panel panel-default">
  <a name="feedback.[[id]]"></a>
    <div class="panel-heading">
      <div class="row">
<div class="col-sm-2">
作者：[[author]]
</div>
<div class="col-sm-2">
提交：[[created_at]]
</div>
<div class="col-sm-2">
修改：[[updated_at]]
</div>
<div class="col-sm-2">
<a href="/feedback/edit/[[id]]">重新编辑</a>
</div>
      </div>
    </div>
    <div class="panel-body">
[[message]]
    </div>
    <!-- /.panel-body -->
</div>
</div>

@stop

@section('js')
<script type="text/javascript">
var users = {!!$users->toJson( )!!};
var tags = {!!$tags->toJson( )!!};

$(document).ready(function( ) {
  initEditor( "summernote" );
});

var submiting = false;
function commitFeedback( taskid, feedid ) {
  if (submiting) {
    return;
  }
  submiting = true;
  var data = "row[message]="+encodeURIComponent($('#summernote').summernote( "code" ));
  data += "&row[pid]=" + taskid;
  if (feedid) {
    data += "&id=" + feedid;
  }

  console.log( data );

  $.ajax({
    data: data,
    type: "POST",
    url: '/feedback/store',
    cache: false,
    dataType: "json",
    success: function( res ) {
      submiting = false;
      console.log( res );
      $("#task-content").append( $("#tpl-feedback").html( ).format( res ) );
      $('#summernote').summernote( "code", "");
    }
  });
}

function updateTaskOnchange( id ) {
  if ($("#leaders").val() <= 0 ) {
    alert("没有选择负责人。");
    return;
  }

  if ($("#tags").val() <= 0 ) {
    alert("没有选择版本。");
    return;
  }

  // console.log( dom.attr('name')+'='+dom.val()); return;
  var s = get_form_values( "taskinfo" );
  console.log(s);

  $("#title").html("#" + id + " " + $("#task-title").val());

  $.ajax({
    data: s + "&id=" + id,
    type: "POST",
    url: '/task/store',
    cache: false,
    success: function( ) {
      alert("修改成功...");
    }
  });
}

</script>
@stop
