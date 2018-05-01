/**
 * Created by Suncafly
 */
var krpano;


window.onload = function () {
    krpano = document.getElementById('krpanoSWFObject');
}

/**
 * 双击鼠标添加热点
 */
function addHotspot() {
    alert("aaaa");
}




function addHotspot1() {
    //跨浏览器的事件对象
    var EventUtil = {
        addHandler: function (elem, type, handler) {
            if (elem.addEventListener) {
                elem.addEventListener(type, handler, false);
            } else if (elem.attachEvent) {
                elem.attachEvent("on" + type, handler);
            } else {
                elem["on" + type] = handler;
            }
        }
    };
    //鼠标点击监听
    var div = document.getElementById("pano");
    EventUtil.addHandler(div, "click", function (event) {
        var mousex = krpano.get("mouse.x");
        var mousey = krpano.get("mouse.y");
        var sphereXY = krpano.screentosphere(mousex, mousey);
        var sphereX = sphereXY.x;
        var sphereY = sphereXY.y;

        var hotspotName = "suncafly" + mousex;
        krpano.call("addHotspot(" + hotspotName + ")");
        krpano.set("hotspot[" + hotspotName + "].url", "/demo/img/a.gif");
        krpano.set("hotspot[" + hotspotName + "].ath", sphereX);
        krpano.set("hotspot[" + hotspotName + "].atv", sphereY);
        krpano.set("hotspot[" + hotspotName + "].scale", 0.5);
        krpano.set("hotspot[" + hotspotName + "].resize", true);
    });
}