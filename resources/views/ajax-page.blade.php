<?php
if ($totalnum > $perpage) {
    $page = 10;
    $offset = 2;

    $pages = @ceil($totalnum / $perpage);

    if($page > $pages) {
        $from = 1;
        $to = $pages;
    } else {
        $from = $curpage - $offset;
        $to = $from + $page - 1;
        if($from < 1) {
            $to = $curpage + 1 - $from;
            $from = 1;
            if($to - $from < $page) {
                $to = $page;
            }
        } elseif($to > $pages) {
            $from = $pages - $page + 1;
            $to = $pages;
        }
    }
?>
<nav>
  <ul class="pagination" style="margin:0;">
@if ($curpage - $offset > 1 && $pages > $page)
<li><a href="javascript:taskFilter(1);">首页</a></li>
@endif
@if ($curpage > 1)
<li><a href="javascript:taskFilter({{$curpage - 1}});">&laquo;</a></li>
@endif
@for ($i = $from; $i <= $to; $i++)
    @if ($i == $curpage)
<li class="active"><a href="javascript:;">{{$i}} <span class="sr-only">(current)</span></a></li>
    @else
<li><a href="javascript:taskFilter({{$i}});">{{$i}}</a></li>
    @endif
@endfor

@if ($to < $pages)
<li><a href="javascript:taskFilter({{$pages}});">&raquo;</a></li>
@endif
@if ($pages > 1)
<li class="disabled"><span aria-hidden="true"><em>{{$totalnum}}</em></span></li>
@endif

</ul></nav>
<?php
}
?>

