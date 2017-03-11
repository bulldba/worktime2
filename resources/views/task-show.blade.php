@extends('layouts.plane')
@section('title', $task->title)

@section('body')

<p></p>

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

<div class="form-inline line">

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
        <span class="input-group-addon">部门</span>
<select class="form-control" onchange="onChangeDepartment( this.value )">
@include('selection-users', ['data' => $departments, 'slt' => $task->department])
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">实施</span>
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
        <span class="input-group-addon">验收</span>
<select itag="val" name="row[tester]" class="form-control" id="leaders">
<option value="0" >无</option>
@include('selection-users', ['data' => $users, 'slt' => $task->tester])
</select>
    </div>
    </div>


  </div>

<div class="form-inline">
    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">类型</span>
<select id="caty" itag="val" name="row[caty]" class="form-control">
@include('selection-users', ['data' => $catys, 'slt' => $task->caty])
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
        <span class="input-group-addon" title="是否可以被量化验证">是否量化</span>
<select itag="val" name="row[science]" class="form-control">
@include('selection', ['data' => ['未', '否', '是'], 'slt' => $task->science])
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


      {!!$task->content!!}

    </div><!-- /.panel-body -->
</div>

@include('task-feedback')

@stop


