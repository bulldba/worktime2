
function initEditor( id ) {
  var $summernote = $('#'+id);
  $summernote.summernote({
    height: $summernote.attr("height"),             // set minimum height of editor
    lang : "zh-CN",
    callbacks: {
      onImageUpload: function(files) {
        var data = new FormData();
        data.append("file", files[0]);
        $.ajax({
            data: data,
            type: "POST",
            url: "/task/upload",
            cache: false,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function( res ) {
                if (!res.path) {
                  console.log( res.err );
                  return
                }

                // var img = document.createElement("img");
                // img.src = url;
                // $summernote.summernote('insertNode', img);
                $summernote.summernote('insertImage', res.path);
            }
        });
      }
    }
  });
}

function get_form_values(id, forme ) {
    var rtn = "";

    if (!forme) {
        forme = 'val';
    }
    var els = $("#" + id + " [itag='" + forme + "']");
    var dot = "";
    for (var i = 0; i < els.length; i++) {
        var el = $(els[i]);
        if ( el.prop('type') != "checkbox" || el.prop("checked")) {
            rtn += dot + el.prop("name") + "=" + encodeURIComponent(el.val());
            dot = "&";
        }
    }
    return rtn;
}

String.prototype.format = function (args) {
    var newStr = this;
    for (var key in args) {
        newStr = newStr.replace('[[' + key + ']]', args[key]);
    }
    return newStr;
}

function getUsers( id ) {
  var options = "";
  for (var i in users) {
    if (users[i].department == id) {
      options += "<option value='" + users[i].id + "'>" + users[i].name + "</option>";
    }
  }
  return options;
}
function onChangeDepartment( id ) {
  var options = "<option value='0'>不修改</option>";
  $("#leaders").html( options + getUsers( id ) );
}
function onFilterChangeDepartment( id ) {
  var options = "<option value='0'>负责</option>";
  $("#filterLeaders").html( options + getUsers( id ) );
}

function getTags( id ) {
  var options = "";
  for (var i in tags) {
    if (tags[i].pro == id) {
      options += "<option value='" + tags[i].id + "'>" + tags[i].name + "</option>";
    }
  }
  return options;
}
function onChangePro( id ) {
  var options = "<option value='0'>不修改</option>";
  $("#tags").html( options + getTags( id ) );
}

function onFilterChangePro( id ) {
  var options = "<option value='0'>版本</option>";
  $("#filterTags").html( options + getTags( id ) );
}

function updateTaskOnchange( id ) {

  if ($("#leaders").val() <= 0 ) {
    alert("没有选择负责人。");
    return;
  }

  if ($("#tags").val() <= 0 ) {
    alert("没有选择版本。");
    return;
  }

  // console.log( dom.attr('name')+'='+dom.val()); return;
  var s = get_form_values( "taskinfo" );
  console.log(s);

  $("#title").html("#" + id + " " + $("#task-title").val());

  $.ajax({
    data: s + "&id=" + id,
    type: "POST",
    url: '/task/store',
    cache: false,
    success: function( ) {
      alert("修改成功...");
    }
  });
}

function commitFeedback( ) {
  $('#formFeedbackContent').val( $('#summernote').summernote( 'code' ) );
  return true;
}

function onpublishcheck( ) {
  if ($("#task-title").val() === "") {
    alert('没有填写标题');
    return false;
  }
  if ($("#leaders").val() <= 0) {
    alert('没有选择部门或者负责人');
    return false;
  }

  if ($("#tags").val() <= 0) {
    alert('没有选择项目或者版本');
    return false;
  }

  return true;
}

function onchagecheckstate(id, iid, slt ) {
  var td = document.getElementById("index-" + iid);
  if (slt.value == 1) {
    td.className = "bg-green";
  } else {
    td.className = "bg-red";
  }

  $.ajax({
    data: "id=" + id + "&iid=" + iid + "&passk=" + slt.value,
    type: "POST",
    url: '/task/icheck',
    cache: false,
    success: function( ) {
      alert("修改成功...");
    }
  });
}