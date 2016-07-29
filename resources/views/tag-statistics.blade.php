@extends('layouts.dashboard')
@section('title', $tag->name)

@section('main')

<h1>{{$tag->name}} [{{date('Y-m-d', strtotime($tag->t_start))}}] - [{{date('Y-m-d', strtotime($tag->t_end))}}]</h1>
<hr />

<?php
$departments = Config::get('worktime.department');
?>

<div class="row">
	<div class="col-lg-12">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
                    <th width="100">#id</th>
                    <th width="80">总数量</th>
                    <th width="80">处理中</th>
                    <th width="80">已解决</th>
                    <th width="80">可测试</th>
                    <th width="80">已通过</th>
                    <th width="80">完成</th>
                    <th>进度</th>
				</tr>
			</thead>
			<tbody>
<tr>
    <td>全部</td>
@include('tag-statistics-td', ['one' => $s_all])
</tr>
<?php
foreach ($s_department as $id => $one) {
?>
<tr>
	<td><?php echo $departments[$id]; ?></td>
@include('tag-statistics-td', ['one' => $one])
</tr>
<?php }
foreach ($s_leader as $id => $one) {
?>
<tr>
    <td><?php echo $users[$id]->name; ?></td>
@include('tag-statistics-td', ['one' => $one])
</tr>
<?php } ?>
			</tbody>
		</table>
	</div>
</div>

@stop