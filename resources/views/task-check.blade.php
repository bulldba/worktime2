@extends('layouts.plane')
@section('title', $task->title)

@section('body')
<div class="line"></div>
<div class="panel panel-default">
  <div class="panel-heading">
  <h1 id="title">#{{$task->id}} {{$task->title}}</h1>
    </div>
  <div class="panel-body">

<div class="row" id="taskinfo">
<div class="col-lg-12">
  <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">标题</span>
<input itag="val" id="task-title" name="row[title]" type="text" class="form-control" value="{{$task->title}}">
    </div>
  </div>

  <div class="line"></div>
<div class="form-inline">
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
@include('selection-users', ['data' => $tags, 'slt' => $task->tag])
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
    <button onclick="updateTaskOnchange({{$task->id}});" class="btn btn-danger margin-right">修改配置</button>
    <a href="/task/resetcheck/{{$task->id}}" class="btn btn-primary margin-right">重置重置</a>
  </div>

</div>

</div>

  </div>
</div>

<table class="table table-striped vertical-middle text-center">
  <tbody>
@foreach (json_decode($task->content) as $i => $desc)
<tr>
  <td id="index-{{$i}}" class="{{$desc[1] > 0 ? 'bg-green' : 'bg-red'}}"> {{$i+1}} </td>
<td class="text-left"><pre class="ipre">
{{$desc[0]}}
</pre> </td>
  <td>
<div class="form-group">
  <div class="input-group">
      <select onchange="onchagecheckstate({{$task->id}}, {{$i}}, this)" class="form-control">
@include('selection', ['data' => ['未通过', '已通过'], 'slt' => $desc[1]])
      </select>
  </div>
</div>
  </td>
</tr>
@endforeach
  </tbody>
</table>


@include('task-feedback')

@stop

