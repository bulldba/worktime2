<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
<meta name="description" content="">
<meta name="author" content="">
<meta name="csrf-token" content="{{ csrf_token() }}" />

<title>@yield('title') - worktime</title>

<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset("assets/stylesheets/calendar.css") }}" />
<link rel="stylesheet" href="{{ asset("assets/stylesheets/dashboard.css") }}" />

<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<!-- include summernote css/js-->
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.1/summernote.js"></script>
<script src="{{ asset("summernote/summernote-zh-CN.js") }}"></script>
<script src="{{ asset("assets/scripts/calendar.js") }}"></script>
<script src="{{ asset("assets/scripts/worktime.js") }}"></script>

</head>

<body>
  <div id="append_parent"></div>
<div class="container-fluid">
    @yield('body')
</div>

<hr>
<div class="container-fluid">
    <p></p>
    <p></p>
<p class="text-center">aoktian@foxmail.com</p>
    <p></p>
    <p></p>
</div>

<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

</script>
@yield('js')
  </body>
</html>