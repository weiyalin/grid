$(document).on('click','a.import-btn',function(e){
    e.preventDefault();
    $('.modal-darkorange .import-submit').click();
})
$(document).on('change','.import-submit',function(e){
    $(this).siblings('.file-bar').text(this.files[0].name);
})
$('#import_excel').on('click',function(e){
    e.preventDefault();
    bootbox.dialog({
        message: $("#import-modal").html(),
        title: "导入文件",
        className: "modal-darkorange",
        buttons: {
            success: {
                label: "导入",
                className: "btn-blue",
                callback: function () {
                    var formdata = new FormData ();
                    var url = $('#import-url').val();//上传地址
                    formdata.append('import_file',$('.modal-darkorange .import-submit')[0].files[0]);
                    $.ajax({
                        url :url,
                        type: 'post',
                        data:formdata,
                        cache: false,
                        processData:false,
                        contentType:false,
                        success:function(data){
                            if (data.code==0) {
                                Notify(data.msg, 'top-right', '3000', 'success', 'fa-check', true);
                                setTimeout(function(){
                                    location.reload();
                                },1000)
                            } else {
                                Notify(data.msg, 'top-right', '3000', 'danger', 'fa-edit', true);
                            }
                            return false;
                        }
                    })
                }
            },
            "取消": {
                className: "btn",
                callback: function () {}
            }
        }
    });
    $('.modal-darkorange').find('#download-template').attr('href',$('#download-template-url').val());
})