@extends('layouts.dashboard')
@section('title', '成员管理')

@section('main')


<div class="row">
	<div class="col-lg-6">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
                    <th width="50">#id</th>
                    <th width="80"> Email </th>
                    <th width="80"> 姓名 </th>
                    <th width="80"> 部门 </th>
                    <th>
操作
                    </th>
				</tr>
			</thead>
			<tbody>
<?php
$departments = Config::get('worktime.department');
foreach ($users as $user) { ?>
<tr>
	<td>{{$user->id}}</td>
    <td>{{$user->email}}</td>
    <td><a href="/user/edit/{{$user->id}}">{{$user->name}}</a></td>
	<td>{{$departments[$user->department]}}</td>
	<td>
<a href="#">删除</a>
	</td>
</tr>
<?php } ?>
			</tbody>
		</table>

	</div>
</div>

@stop