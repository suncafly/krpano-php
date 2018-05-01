$.ajax({
    url: "../php/ImageUpload.php",
    type: "POST",
    dataType: "json",
    data: {"name":"sun"},
    error: function (error) {
        alert("出错：" + JSON.stringify(error));
    },
    success: function (data) {
        if (data.status == 'success') {
            //alert('保存成功！');
        } else {
            alert('保存失败！');
        }
    }
});