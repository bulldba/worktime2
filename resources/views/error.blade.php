@extends('layouts.dashboard')
@section('title', '错误信息')

@section('main')

<div class="row">
<div class="col-lg-12">
<div class="alert alert-danger">
	<h4>出错了！</h4>
	<p>{{$error}}</p>
	<?php if (isset($backurl)) {?>
		<p>
			<a href="{{$backurl}}" class="btn btn-link">点击返回</a>
		</p>
	<?php } ?>
</div>
</div>
</div>

@stop