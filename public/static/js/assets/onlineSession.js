function onlineSession()
{
    if ("WebSocket" in window)
    {

        // 打开一个 web socket
        var ws = new WebSocket("{$webSocketUrl}");

        ws.onopen = function()
        {
            // Web Socket 已连接上，使用 send() 方法发送数据
            ws.send("online");
        };

        ws.onmessage = function (evt)
        {
            // console.log(evt.data);
            var response = evt.data;
            if (null != response) {
                response = JSON.parse(response);
                console.log(response.message);
                // list
                // $('#list').append('<li><span class="text-info small"><i>' + response.message + '</i></span></li>');

                // table
                console.log(response.result);
                var tableData = response.result;

                $('#content').empty();
                if (0 != tableData.length){
                    for (var i = 0; i < tableData.length; i++) {
                        $('#content').append('<tr></tr>');
                        // $('#content tr:last').append('<td>' + tableData[i].id + '</td>');
                        $('#content tr:last').append('<td>' + tableData[i].machine + '</td>');
                        $('#content tr:last').append('<td>' + tableData[i].machineId + '</td>');
                        $('#content tr:last').append('<td>' + tableData[i].LANIP + '</td>');
                        $('#content tr:last').append('<td>' + tableData[i].ShelfId_SwitchId + '</td>');
                        $('#content tr:last').append('<td><i class="fa fa-circle text-red"></i> inUse</td>');
                    }
                }
                else {
                    $('#content').append('<tr></tr>');
                    $('#content tr:last').append('<td colspan="5"><span><i style="color: green"> The Shelf is not inUse... </i></span></td>');
                }

            }

        };

        ws.onclose = function()
        {
            // 关闭 websocket
            // setTimeout("toastr.success(\"ws connect close...\");", 80);
        };
    }

    else
    {
        // 浏览器不支持 WebSocket
        toastr.error("your browse not support WebSocket!");
    }
}