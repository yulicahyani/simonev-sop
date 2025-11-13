<script>
var permission_id = [];
$('#list_permission').jstree({
    "checkbox" : {
        "keep_selected_style" : false
    },
    "plugins" : [ "checkbox" ],
    "core":{
        "themes": {
            "icons": false
        }
    }
});

$(document).ready(function () {
    $('#role').change(function (e) { 
        e.preventDefault();
        var url = '{{ route("user-permission.index") }}?role_id=' + $('#role').val();
        window.location.replace(url);
    });
});

$('#save_role').click(function(e) {
    e.preventDefault();
    var permission_id = [];
    $.each($("#list_permission").jstree("get_checked", true), function() {
        if (this.parent != '#') {
            permission_id.push(this.parent);
        }
        permission_id.push(this.id);
    });
    $('body').LoadingOverlay("show");
    $.ajax({
        type: "POST",
        url: "{{ route('user-permission.store') }}",
        data: {
            _token : "{{ csrf_token() }}",
            role: $('#role').val(),
            permission: permission_id,
        },
        success: function(response) {
            $('body').LoadingOverlay("hide");
            if(response == "Success"){
                swalTimer('success', 'Berhasil Tersimpan', 2000);
            } else if(response == 'Failed') {
                swalTimer('error', 'Gagal Tersimpan', 2000);
            }
            location.reload();
        }
    });
});
</script>