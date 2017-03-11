@extends('layouts.dashboard')

@section('title', '任务列表')

@section('main')

<?php
$prioritys = Config::get('worktime.priority');
$status = Config::get('worktime.status');
?>
<div class="alert alert-success">
<div class="row">

<div class="col-lg-12">
<form class="form-inline" role="form" method="GET" action="/task/hr">
<input name="page" class="form-control" type="hidden" value="{{$page or 1}}" >

<div class="form-group"> <div class="input-group">
      <span class="input-group-addon">部门</span>
<select onchange="onFilterChangeDepartment(this.value);" class="form-control">
  <option value="0">选择</option>
@include('selection-users', ['data' => $departments, 'slt' => 0])
</select>
</div></div>

<div class="form-group"> <div class="input-group">
      <span class="input-group-addon">负责人</span>
<select name="leader" class="form-control" id="filterLeaders">
  <option value="0">选择</option>
@include('selection-users', ['data' => $users, 'slt' => isset($options['leader']) ? $options['leader'] : 0])
</select>
</div></div>

<div class="form-group"> <div class="input-group">
      <span class="input-group-addon">开始</span>
<input name="t_start" value="{{$options['t_start'] or ''}}" class="form-control" type="text" onclick="showcalendar(event, this)">
</div></div>

<div class="form-group"> <div class="input-group">
      <span class="input-group-addon">结束</span>
<input name="t_end" value="{{$options['t_end'] or ''}}" class="form-control" type="text" onclick="showcalendar(event, this)">
</div></div>

<button class="btn btn-success"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> 查询</button>
</form>
</div>

</div>
</div>

<table class="table table-bordered table-hover table-striped vertical-middle text-center">
  <thead>
<tr>
  <th width="100" class="text-center"> 项目 </th>
  <th width="100" class="text-center">版本 </th>
  <th width="80" class="text-center">状态 </th>
  <th width="80" class="text-center">优先级 </th>
  <th width="80" class="text-center">类型 </th>
  <th>标题 </th>
  <th width="155" class="text-center">创建</th>
  <th width="155" class="text-center">修改</th>
  <th width="155" class="text-center">耗时</th>
</tr>
  </thead>
  <tbody>

<?php
$textcolor = array();
$a = array( 'text-normal', 'text-primary', 'text-danger', 'text-danger' );
$i = 0;
foreach ($prioritys as $key => $value) {
    $textcolor[$key] = $a[$i];
    $i++;
}
foreach ($tasks as $task) {
    if (98 == $task->status) {
        $tcolor = 'text-success';
    } elseif (99 == $task->status) {
        $tcolor = 'text-muted';
    } else {
        $tcolor = $textcolor[$task->priority];
    }
?>
<tr class="{{$tcolor}}">
<td>{{isset($pros[$task->pro]) ? $pros[$task->pro]->name : ''}}</td>
<td>{{isset($tags[$task->tag]) ? $tags[$task->tag]->name : ''}}</td>
<td>{{$status[$task->status] or ''}}</td>
<td>{{isset($prioritys[$task->priority]) ? $prioritys[$task->priority] : ''}}</td>
<td>{{$catys[$task->caty]->name}}</td>
<td class="text-left"><a class="{{$tcolor}}" href="/task/show/{{$task->id}}" target="_blank">#{{$task->id}} {{$task->title}}</a></td>
<td>{{$task->created_at}}</td>
<td>{{$task->updated_at}}</td>
<td>{{timediff(strtotime($task->updated_at), strtotime($task->created_at))}}</td>
</tr>
<?php } ?>
<tr><td colspan="9" class="text-left">
{!! $tasks->appends($options)->render() !!}
</td></tr>

  </tbody>
</table>

@stop

@section('js')

<script type="text/javascript">
var users = {!!$users->toJson( )!!};
var tags = {!!$tags->toJson( )!!};

var page = 1;
function taskFilter( _page ) {
  if (_page) {
    page = _page;
  }
  var s = "page=" + page + "&" + get_form_values( "taskfilter" );
  s += "&title=" + $("#stitle").val();
  console.log(s);
  getlist( s );
}

function getlist( s ) {
  $.ajax({
    data: s,
    type: "GET",
    url: '/task/index',
    cache: false,
    success: function( res ) {
      $("#tasklist").html( res );
    }
  });
}

setInterval( "taskFilter( );", 1000 * 60 * 5 );

function checkall( id, name, b ) {
    var els = $("#" + id + " :checkbox");
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        if (name == el.prop("name")) {
            if ("undefined" == typeof(b) ) {
                if (el.prop("checked")) {
                    el.prop("checked", false);
                } else {
                    el.prop("checked", true);
                }
            } else{
                el.prop("checked", b);
            }
        }
    }
}

function changeMore( ) {
  var s = get_form_values( "tasklist" );
  if ("" == s) {
    alert( "没有选择任务" );
    return;
  }

  var changed = false;
  var els = $("#changemoreform [itag='val']");
  for (var i = 0; i < els.length; i++) {
      var el = $(els[i]);
      var va = el.val();
      if (va > 0) {
        s += "&" + el.prop("name") + "=" + va;
        el.val( 0 );
        changed = true;
      }
  }
  if (!changed) {
    alert( "没有变化" );
    return;
  }

  s += "title=" + $("#stitle").val();
  s += "&page=" + page + "&" + get_form_values( "taskfilter" );
  console.log( s );

  $.ajax({
    data: s,
    url:'/task/index'
  }).done(function(data){
      $("#tasklist").html( data );
  });
}

</script>
@stop

