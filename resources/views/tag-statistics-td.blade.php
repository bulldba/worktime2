<td>
<?php
$total = array_sum($one);
$p = 0;
if ($total > 0) {
    $p = intval($one[98] / $total * 10000) / 100;
}
?>
@include('progress', ['p' => $p])
</td>
<td>{{$total}}</td>
<td>{{$one[10] + $one[11] + $one[12]}}</td>
<td>{{$one[19]}}</td>
<td>{{$one[50]}}</td>
<td>{{$one[98]}}</td>