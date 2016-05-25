/**
 * Meican LMap 1.0
 *
 * A DCN topology viewer based on Leaflet Javascript library.
 *
 * @copyright Copyright (c) 2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

function LMap(canvasDivId) {
    this._canvasDivId = canvasDivId;
    this._map;                          // Leaflet Map
    this._nodes = [];                   // markers/nodes container
    this._links = [];                   // polylines/links/edges container
    this._nodeType;                     // current node type visible
    this._domainsList;                  // domains list reference;
    this._lastShowedMarker; 
    this._cluster;
};

LMap.prototype.show = function(nodeType) {
    if($("#map-l").length == 1) {
        $("#map-l").show();
    } else {
        $("#"+this._canvasDivId).append('<div id="map-l" style="width:100%; height:100%;"></div>');
        this.build('map-l');
    }

    var currentMap = this._map;

    setTimeout(function() {
        currentMap.invalidateSize(true);
    }, 200);

    this.setNodeType(nodeType);
}

LMap.prototype.hide = function() {
    if($("#map-l").length == 1) {
        $("#map-l").hide();
        /*this._map.remove();
        $("#map-l").remove();*/
    }
}

LMap.prototype.addPort = function(id, name, dir, srcNodeId, dstNodeId, type) {
    var node = this.getNode(srcNodeId);
    node.options.ports[id] = {
        name: name,
        dir: dir,
        link: dstNodeId != null ? this.addLink([srcNodeId, dstNodeId], type, true) : null
    };
}

LMap.prototype.addLink = function(path, type, partial) {
    if(path.length < 2) return null;

    var latLngList = [];

    var src = this.getNode(path[0]);
    if(src != null)
        latLngList.push(src.getLatLng());

    var dst = this.getNode(path[1]);
    if(dst != null)
        latLngList.push(dst.getLatLng());

    if(partial) {
        latLngList[1] = L.latLngBounds(latLngList[0], latLngList[1]).getCenter();
    }

    //strokeColor = "#0000FF"; 
    //strokeOpacity = 0.1;

    if (latLngList.length > 1) {
        var link = L.polyline(
            latLngList, 
            {
                from: path[0],
                to: path[1],
                color: '#cccccc',
                type: type,
            }).addTo(this._map).bindPopup("#");

        this._links.push(link);
    } else return null;

    var currentMap = this;

    link.on('click', function(e) {
        $("#"+currentMap._canvasDivId).trigger("lmap.linkClick", link);
    });

    return link;
    
    /*google.maps.event.addListener(link, 'click', function(event) {
        var srcDomain = meicanMap.getDomainName(source.domainId);
        var dstDomain = meicanMap.getDomainName(destin.domainId);
        meicanMap.closeWindows();
        var infoWin = new google.maps.InfoWindow({
            content: "Link between <b>" + 
                ((source.name == srcDomain) ? srcDomain : srcDomain + " (" + source.name + ")") + '</b> and <b>' + 
                ((destin.name == dstDomain) ? dstDomain : dstDomain + " (" + destin.name + ")")  + "</b>",
            position: event.latLng,
        });
        infoWin.open(meicanMap.getMap());
        meicanMap.addWindow(infoWin);
    });*/
}

LMap.prototype.removeLink = function(link) {
    this._map.removeLayer(link);
    ///to do
}

LMap.prototype.hideLink = function(link) {
    this._map.removeLayer(link);
}

LMap.prototype.showLink = function(link) {
    this._map.addLayer(link);
}

LMap.prototype.getLinks = function() {
    return this._links;
}

LMap.prototype.removeLinks = function() {
    if (this._links.length > 0) {
        for (var i = 0; i < this._links.length; i++) {
            this.removeLink(this._links[i]);
        };
    }
}

LMap.prototype.addNode = function(id, name, type, domainId, lat, lng, color) {
    if (!color) color = this.getDomain(domainId).color;
    if (lat != null && lng != null) {
        var pos = [lat,lng];
    } else {
        var pos = [0, 0];
    }

    var icon = L.divIcon({
        iconSize: [22,22],
        iconAnchor: [11, 22],
        popupAnchor: [0,-24],
        html: '<svg width="22" height="22" xmlns="http://www.w3.org/2000/svg">' + 
        '<g>' +
        '<path stroke="black" fill="' + color + '" d="m1,0.5l20,0l-10,20l-10,-20z"/>' + 
        '</g>' + 
        '</svg>',
        className: 'marker-icon-svg',
    });

    var node = L.marker(
        pos, 
        {
            id: id, 
            icon: icon,
            type: type,
            name: name,
            domainId: domainId,
            ports: {}
        }
    ).bindPopup("#");

    this._nodes.push(node);
    this._cluster.addLayer(node);

    var currentMap = this;

    node.on('click', function(e) {
        $("#"+currentMap._canvasDivId).trigger("lmap.nodeClick", node);
    });
}

LMap.prototype.getDomain = function(id) {
    for (var i = 0; i < this._domainsList.length; i++) {
        if (this._domainsList[i].id == id) return this._domainsList[i];
    }
}

LMap.prototype.getDomainByName = function(name) {
    for (var i = 0; i < this._domainsList.length; i++) {
        if (this._domainsList[i].name == name) return this._domainsList[i];
    }
}

LMap.prototype.setDomains = function(list) {
    this._domainsList = list;
}

LMap.prototype.getValidMarkerPosition = function(type, position) {
    size = this._nodes.length;
    lat = position.lat().toString().substring(0,6);
    lng = position.lng().toString().substring(0,6);

    for(var i = 0; i < size; i++){
        anotherLat = this._nodes[i].position.lat().toString().substring(0,6);
        anotherLng = this._nodes[i].position.lng().toString().substring(0,6);

        if (this._nodes[i].type == type &&
                anotherLat == lat && 
                anotherLng == lng) {
            return this.getValidMarkerPosition(type, new google.maps.LatLng(position.lat(), position.lng() + 0.01));
        }
    }
    
    return position;
}

LMap.prototype.closePopups = function() {
    this._map.closePopup();
}

LMap.prototype.addPopup = function(infoWindow) {
}

LMap.prototype.openPopup = function(marker, extra) {
}

LMap.prototype.getNode = function(id) {
    var size = this._nodes.length;
    for(var i = 0; i < size; i++){
        if ((this._nodes[i].options.id.toString()) == (id.toString())) {
            return this._nodes[i];
        }
    }
    
    return null;
}

LMap.prototype.getNodeType = function() {
    return this._nodeType;
}

LMap.prototype.searchMarkerByNameOrDomain = function (type, name) {
    results = [];
    name = name.toLowerCase();
    var domainId;

    if (!domainsList) domainsList = JSON.parse($("#domains-list").text());
    for (var i = 0; i < domainsList.length; i++) {
        if(domainsList[i].name.toLowerCase().indexOf(name) > -1){
            domainId = domainsList[i].id;
            break;
        }
    };

    var size = this._nodes.length;
    for(var i = 0; i < size; i++){
        if (this._nodes[i].type == type) {
            if ((this._nodes[i].name.toLowerCase()).indexOf(name) > -1) {
                results.push(this._nodes[i]);
            } else if (domainId == this._nodes[i].domainId) {
                results.push(this._nodes[i]);
            }
        }
    }
    
    return results;
}

LMap.prototype.getMarkerByDomain = function(type, domainId) {
    var size = this._nodes.length;
    for(var i = 0; i < size; i++){
        if (this._nodes[i].type == type && this._nodes[i].domainId == domainId) {
            return this._nodes[i];
        }
    }
    
    return null;
}

LMap.prototype.setNodeType = function(type) {
    if(this._nodeType == type)
        return;

    this._nodeType = type;

    var size = this._nodes.length;
    
    for(var i = 0; i < size; i++){ 
        console.log(type, this._nodes[i].options.type);
        if (this._nodes[i].options.type == type) {
            this.showNode(this._nodes[i]);
        } else {
            this.hideNode(this._nodes[i]);
        }
    }  

    for (var i = 0; i < this._links.length; i++) {
        if (this._links[i].options.type == type) {
            this.showLink(this._links[i]);
        } else {
            this.hideLink(this._links[i]);
        }
    }
}

LMap.prototype.build = function(mapDiv) {
    this._map = L.map(mapDiv, {
        center: [-13.8771429,-52.0998244],
        zoomControl: false,
        zoom: 4,
        maxZoom: 15,
        minZoom: 2
    });

    new L.Control.Zoom({ position: 'topright' }).addTo(this._map);

    //$(".leaflet-top").css("margin-top","15%");

    this._cluster = L.markerClusterGroup({
        showCoverageOnHover: false,
        maxClusterRadius: 20,
    });
    this._map.addLayer(this._cluster);

    this.setType('rnp');

    $('#' + mapDiv).show();   
}

LMap.prototype.getNodes = function() {
    return this._nodes;
}

LMap.prototype.hideNode = function(node) {
    this._cluster.removeLayer(node);
}

LMap.prototype.showNode = function(node) {
    this._cluster.addLayer(node);
}

LMap.prototype.removeNodes = function() {
    var size = this._nodes.length;
    for (var i = 0; i < size; i++) {
        this._nodes[i].setMap(null);
    }
    this._nodes = [];
}

LMap.prototype.setType = function(mapType) {
    switch(mapType) {
        case "osm" : 
            L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                subdomains: ['a','b','c']
            }).addTo( this._map );
            break;
        case "mq" : 
            L.tileLayer('http://otile{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.jpg', {
                attribution: '&copy; MapQuest',
                subdomains: ['1','2','3','4']
                }).addTo(this._map);
            break;
        case 'rnp': 
            L.tileLayer('http://viaipe.rnp.br/mapa/{z}/{x}/{y}.png',{
                attribution: 'MEICAN Project | UFRGS | Map data &copy; 2016 <a href="http://www.rnp.br">RNP</a>',
                maxZoom: 15,
                minZoom: 2
            }).addTo(this._map);
    }
}

LMap.prototype.focusNode = function(id) {
    var marker = this.getMarker(id);
    if(marker) {
        var bounds = new google.maps.LatLngBounds();

        bounds.extend(marker.getPosition()); 

        this._map.fitBounds(bounds);
        this._map.setZoom(11);

        this.closeWindows();
        this.openWindow(marker);
        this._lastShowedMarker = id;
    }
}

LMap.prototype.focusNodes = function() {
    var latLngs = [];
    for (var i = 0; i < this._nodes.length; i++) {
        latLngs.push(this._nodes[i].getLatLng());
    };
    this._map.fitBounds(L.latLngBounds(latLngs));
}
