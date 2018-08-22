var krpano = document.getElementById("krpanoSWFObject");
var layers = [];
function onready(value) {
    krpano.get("layer").getArray().forEach(function (layer) {
        if(layer.name == "map"){
            layer.url = "/demo/map/skin/b1map.png";
        }
        if(layer.parent == "radarmask" && layer.name.startsWith("spot")){
            layers.push(layer);
        }
    })
    krpano.get("layer").getArray().forEach(function (layer) {
        if(layer.parent == "radarmask" && layer.name.startsWith("spot")){
            console.log(layer.name)
        }
    })

    var radarmask = krpano.get("layer[radarmask]");
    console.log(radarmask)
}