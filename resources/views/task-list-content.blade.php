<?php
$textcolor = array();
$a = array( 'text-muted', 'text-primary', 'text-danger', 'text-danger' );
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
<td><input itag="val" name="ids[]" type="checkbox" value="{{$task->id}}"></td>
<td>{{$task->id}}</td>
<td>{{$status[$task->status]}}</td>
<td>{{$prioritys[$task->priority]}}</td>
<td>{{$catys[$task->caty]}}</td>
<td><a class="{{$tcolor}}" href="/task/show/{{$task->id}}" target="_blank">{{$task->title}}</a></td>
<td>{{$users[$task->leader]->name}}</td>
<td>{{$departments[$task->department]}}</td>
<td>{{$pros[$task->pro]->name . ' - ' . $tags[$task->tag]->name}}</td>
<td>{{$users[$task->author]->name}}</td>
<td>{{$task->updated_at}}</td>
</tr>
<?php } ?>
<tr><td colspan="11">
@include('ajax-page')
</td></tr>