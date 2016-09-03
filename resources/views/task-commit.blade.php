@extends('layouts.plane')
@section('title', '提交任务')

@section('body')

<h1>提交新任务</h1>
<hr />

<form method="POST" action="/task/store" onsubmit="return oncommit( );">
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<input type="hidden" id="taskContent" name="row[content]">
<input type="hidden" name="id" value="{{$task->id}}" />
<div class="row">
  <div class="col-sm-5">
    <div class="form-group">
    <div class="input-group">
      <span class="input-group-addon">标题：</span>
      <input id="task-title" name="row[title]" type="text" class="form-control" value="{{'' == $task->title ? '没有标题的标题' : $task->title}}">
    </div>
    </div>
  </div>
  <div class="col-sm-2">
    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">类型：</span>
        <select name="row[caty]" class="form-control">
@include('selection', ['data' => Config::get('worktime.caty'), 'slt' => $task->caty])
        </select>
    </div>
    </div>
  </div>
  <div class="col-sm-2">
    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">优先级：</span>
        <select name="row[priority]" class="form-control">
@include('selection', ['data' => Config::get('worktime.priority'), 'slt' => $task->priority])
        </select>
    </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
<div class="form-inline">
    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">部门：</span>
        <select class="form-control" onchange="onChangeDepartment( this.value )">
@if ($task->id)
@include('selection', ['data' => Config::get('worktime.department'), 'slt' => $task->department])
@else
<option value="0">选择部门</option>
@include('selection', ['data' => Config::get('worktime.department'), 'slt' => 0])
@endif
        </select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">负责人：</span>
        <select name="row[leader]" class="form-control" id="leaders">
@if ($task->id)
@foreach ($users as $user)
@if ($user->department == $task->department)
<option value="{{$user->id}}" {{$user->id == $task->leader ? 'selected' : ''}}>{{$user->name}}</option>
@endif
@endforeach
@else
<option value="0">未选部门</option>
@endif
        </select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">项目</span>
<select class="form-control" onchange="onChangePro(this.value);">
@if ($task->id)
@include('selection-users', ['data' => $pros, 'slt' => $task->pro])
@else
<option value="0">选择项目</option>
@include('selection-users', ['data' => $pros, 'slt' => 0])
@endif
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">版本：</span>
        <select name="row[tag]" class="form-control" id="tags">
@if ($task->id)
@foreach ($tags as $tag)
@if ($tag->pro == $task->pro)
<option value="{{$tag->id}}" {{$tag->id == $task->tag ? 'selected' : ''}}>{{$tag->name}}</option>
@endif
@endforeach
@else
<option value="0">未选项目</option>
@endif
        </select>
    </div>
    </div>

  </div></div>
</div>
<p></p>
<div class="row">
  <div class="col-lg-12">
    <div class="form-group">
      <textarea id="summernote" height="500">{!!$task->content!!}</textarea>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <button type="submit" class="btn btn-danger btn-lg btn-block"> 提 交 </button>
    </div>
</div>

</form>

<p></p>

@stop


@section('js')
<script type="text/javascript">
var users = {!!$users->toJson( )!!};
var tags = {!!$tags->toJson( )!!};

$(document).ready(function( ) {
  initEditor( "summernote" );
});

function oncommit( ) {
  if ($("#leaders").val() <= 0) {
    alert('没有选择部门或者负责人');
    return false;
  }

  if ($("#tags").val() <= 0) {
    alert('没有选择项目或者版本');
    return false;
  }

  $('#taskContent').val( $('#summernote').summernote( 'code' ) );
  return true;
}

</script>
@stop