
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
