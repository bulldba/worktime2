@extends('layouts.dashboard')

@section('title', '任务列表')

@section('main')

<div class="alert alert-success">
<div class="row">

<div class="col-lg-2">
  <div class="input-group">
    <input id="gid" type="text" class="form-control" placeholder="输入编号直接打开">
    <span class="input-group-btn">
      <button onclick="window.open( '/task/show/' + $('#gid').val() );" class="btn btn-default" type="button">Go!</button>
    </span>
  </div>
</div>

<div class="col-lg-3">
  <div class="input-group">
    <input id="stitle" type="text" class="form-control" placeholder="标题模糊查询">
    <span class="input-group-btn">
      <button onclick='getlist( "title=" + $("#stitle").val() ); ' class="btn btn-default" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search</button>
    </span>
  </div>
</div>

<div class="col-lg-1">
  <a href="/task/create" target="_blank" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 添加新任务</a>
</div>

<div class="col-lg-6">
<form target="_blank"  class="form-inline" role="form" method="POST" action="/tag/show">
<div class="form-group">
<label>开始：</label>
<input name="start" class="form-control" type="text" onclick="showcalendar(event, this)">
</div>
<div class="form-group">
<label>结束：</label>
<input name="end" class="form-control" type="text" onclick="showcalendar(event, this)">
</div>
<button class="btn btn-success"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> 查看统计</button>
</form>
</div>

</div>
</div>

<table class="table table-bordered table-hover table-striped vertical-middle text-center">
  <thead id="taskfilter">
    <tr>
        <th width="20">
<input type="checkbox" onclick="checkall('tasklist', 'ids[]',  $(this).prop('checked') );"></th>
  <th width="100">
<select onchange="onFilterChangePro( this.value );taskFilter( 1 );" itag="val" name="search[pro]" class="form-control">
<option value="0">项目</option>
@include('selection-users', ['data' => $pros, 'slt' => isset($options['pro']) ? $options['pro'] : 0])
</select>
        </th>
        <th width="100">
<select onchange="taskFilter( 1 );" itag="val" name="search[tag]" class="form-control" id="filterTags">
<option value="0">版本</option>
@include('selection-users', ['data' => $tags, 'slt' => isset($options['tag']) ? $options['tag'] : 0])
</select>
        </th>
        <th width="100">
<select onchange="taskFilter( 1 );" itag="val" name="search[status]" class="form-control">
<option value="0">状态</option>
@include('selection', ['data' => $status, 'slt' => isset($options['status']) ? $options['status'] : 0])
</select></th>
        <th width="80">
<select onchange="taskFilter( 1 );" itag="val" name="search[priority]" class="form-control">
<option value="0">优</option>
@include('selection', ['data' => $prioritys, 'slt' => isset($options['priority']) ? $options['priority'] : 0])
</select>
        </th>
        <th width="100">
<select onchange="taskFilter( 1 );" itag="val" name="search[caty]" class="form-control">
<option value="0">类型</option>
@include('selection', ['data' => $catys, 'slt' => isset($options['caty']) ? $options['caty'] : 0])
</select>
        </th>
        <th>标题 </th>

        <th width="100">
<select onchange="taskFilter( 1 );" itag="val" name="search[leader]" class="form-control" id="filterLeaders">
<option value="0">负责</option>
@include('selection-users', ['data' => $users, 'slt' => isset($options['leader']) ? $options['leader'] : 0])
</select>
        </th>

        <th width="100">
<select onchange="onFilterChangeDepartment(this.value);taskFilter( 1 );" itag="val" name="search[department]" class="form-control">
<option value="0">部门</option>
@include('selection', ['data' => $departments, 'slt' => isset($options['department']) ? $options['department'] : 0])
</select>
        </th>

        <th width="155">修改时间</th>
        <th width="155">期限</th>
    </tr>
  </thead>
  <tbody id="tasklist">
@include('task-list-content')
  </tbody>
</table>

<div class="row">

  <div class="col-lg-12">
<div class="form-inline" id="changemoreform">
    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">状态</span>
<select itag="val" name="changeto[status]" class="form-control">
<option value="0">不修改</option>
@include('selection', ['data' => $status, 'slt' => 0])
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">优先级</span>
<select itag="val" name="changeto[priority]" class="form-control">
<option value="0">不修改</option>
@include('selection', ['data' => $prioritys, 'slt' => 0])
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">类型</span>
<select itag="val" name="changeto[caty]" class="form-control">
<option value="0">不修改</option>
@include('selection', ['data' => $catys, 'slt' => 0])
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">部门</span>
<select class="form-control" onchange="onChangeDepartment( this.value )">
<option value="0">不修改</option>
@include('selection', ['data' => $departments, 'slt' => 0])
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">负责人</span>
<select itag="val" name="changeto[leader]" class="form-control" id="leaders">
<option value="0">不修改</option>
@include('selection-users', ['data' => $users, 'slt' => 0])
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">项目</span>
<select class="form-control" onchange="onChangePro(this.value);">
<option value="0">不修改</option>
@include('selection-users', ['data' => $pros, 'slt' => 0])
</select>
    </div>
    </div>

    <div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">版本</span>
<select itag="val" name="changeto[tag]" class="form-control" id="tags">
<option value="0">不修改</option>
</select>
    </div>
    </div>

    <button onclick="changeMore( );" class="btn btn-danger">批量修改</button>

</div>

  <p></p>
  </div>

</div>

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
