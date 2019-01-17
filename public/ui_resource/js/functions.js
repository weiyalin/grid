/*
 * 确认提示方法
 * message:提示信息
 * fun:回调函数
 **/
function bootConfirm(message, fun) {
    bootbox.confirm({
        message: message,
        buttons: {
            confirm: {
                label: "确认",
                className: "btn-success"
            },
            cancel: {
                label: "取消"
            }
        },
        callback: function (result) {
            if (result) {
                fun();
            }
        }
    });
}