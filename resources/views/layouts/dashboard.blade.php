@extends('layouts.plane')

@section('body')
<a name="top"></a>
<h3>Work time</h3>
<hr />
<div class="row">

<div class="col-lg-1">

<div class="list-group">
  <a href="/task/index" class="list-group-item{{ Request::is('task/index') || Request::is('/') ? ' active' : '' }}">任务清单</a>
  <a href="/task/checks" class="list-group-item{{ Request::is('task/checks') ? ' active' : '' }}">CHECKS</a>
  <a href="/task/hr" class="list-group-item{{ Request::is('task/hr') ? ' active' : '' }}">HR专用</a>
</div>

<div class="list-group">
  <a target="_blank" href="http://www.chiark.greenend.org.uk/~sgtatham/bugs-cn.html" class="list-group-item">如何报告BUG</a>
  <a href="/task/ido" class="list-group-item{{ Request::is('task/ido') ? ' active' : '' }}">我的任务</a>
  <a href="/task/icommit" class="list-group-item{{ Request::is('task/icommit') ? ' active' : '' }}">我发布的</a>
  <a href="/task/itest" class="list-group-item {{ Request::is('task/itest') ? ' active' : '' }}">我的验收</a>
</div>

<div class="list-group">
  <a href="/pro/index" class="list-group-item{{ Request::is('pro/*') ? ' active' : '' }}">项目管理</a>
  <a href="/tag/index" class="list-group-item{{ Request::is('tag/*') ? ' active' : '' }}">版本管理</a>
  <a href="/title/index" class="list-group-item{{ Request::is('title/*') ? ' active' : '' }}">字段管理</a>
  <a href="/user/index" class="list-group-item{{ Request::is('user/*') ? ' active' : '' }}">成员管理</a>
</div>

<div class="list-group">
<a href="/user/edit/{{Auth::user()->id}}" class="list-group-item">修改密码</a>
<a href="{{ url ('auth/logout') }}" class="list-group-item">退出</a>
</div>

</div>

<div class="col-lg-11">
@yield('main')
</div>

</div>

@stop
