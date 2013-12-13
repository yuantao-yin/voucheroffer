var MeOnTheMap = function(options){
    
    this.options = {
        address    : "",
        container  : "",
        defaultUI  : true,
        noDragging : false,
        html       : "",
        zoomLevel  : 16,
        view       : 0
    };
    
    this.preloads = [];

    this.initialize = function(options) {

        for (var opt in options){
            this.options[opt] = options[opt];
        }

        this.preparePreloading();

        this.container = document.getElementById(this.options.container);
        if (!this.container) {
            alert("Could not locate \"" + this.options.container + "\"");
            return;
        }

        this.findLocation();
    };
    
    this.preparePreloading = function(){
        var regxp = new RegExp('(src)=("[^"]*")','g');
        var sources = this.options.html.match(regxp);

        if (!sources)
            return;

        function getHandler(obj) {
            return function(){
                var el = document.getElementById(obj.id);
                if (el){
                    el.parentNode.replaceChild(this, el);
                    obj.marker.tooltip.redraw(true);
                }
            };
        };
    
        for (var i = 0; i < sources.length; i++){
            this.options.html = this.options.html.replace(sources[i],"style=\"visibility:visible\" id=\"preloadimg" + i + "\" src=\"\"");

            var src = sources[0].split("=\"")[1];
            src = src.substring(0,src.length - 1);

            var img = new Image();

            this.preloads.push({
                element: img,
                src: src,
                id: "preloadimg" + i
            });

            img.onload = getHandler(this.preloads[this.preloads.length - 1]);
        }
    };
    
    this.startPreloading = function(marker, map){
        for (var i = 0; i < this.preloads.length; i++) {
            this.preloads[i].marker = marker;
            this.preloads[i].map    = map;
            this.preloads[i].element.src = this.preloads[i].src;
        }
    };
    
    this.findLocation = function() {
        var _this = this;
        this.geoLocator = new GClientGeocoder(); 
        this.geoLocator.getLatLng(this.options.address, function(response){
            _this.handleGetLocatorResponse(response);
        }); 
    };
    
    this.getIcon = function() {
        var icon        = new GIcon(G_DEFAULT_ICON);
        icon.image      = "wp-content/themes/couponpress/PPT/js/map/icon.png";
        icon.shadow     = "wp-content/themes/couponpress/PPT/js/map/shadow.png";
        icon.iconSize   = new GSize(35,35);
        icon.shadowSize = new GSize(52, 35);
        icon.iconAnchor = new GPoint(17, 35);
        return icon;
    };
    
    this.handleGetLocatorResponse = function(response){
        this.geoLocator = null;
        
        if(!response) {
            alert("Could not resolve this addess \"" + this.options.address + "\"");
            return;
        }

        var type = "";
        switch (this.options.view) {
            case 1  : type = G_SATELLITE_MAP; break;
            case 2  : type = G_HYBRID_MAP; break;
            default : type = G_NORMAL_MAP; break;
        }
    
        this.map = new GMap2(this.container);
        this.map.setMapType(type);
        this.map.setCenter(response, this.options.zoomLevel);
        this.map.disableScrollWheelZoom();
        
        if (this.options.noDragging)
            this.map.disableDragging();

        if (this.options.defaultUI)
            this.map.setUIToDefault();

        this.marker = new GMarker(response,{icon: this.getIcon()});
        this.map.addOverlay(this.marker);

        if (!this.options.html || this.options.html == "")
            return;

        this.marker.tooltip = new Tooltip(this.marker, this.options.html);
        this.map.addOverlay(this.marker.tooltip);
        this.marker.tooltip.show();

        if (this.newCenter) {
            var markerPos = this.map.fromLatLngToDivPixel(this.marker.getPoint());
            var pos = this.map.fromContainerPixelToLatLng({
                x: markerPos.x + this.newCenter.x,
                y: markerPos.y + this.newCenter.y
            });
            this.map.setCenter(pos);
        }
        
        this.startPreloading(this.marker, this.map);
    };

    this.adjustMapCenter = function(position){
        if (!this.geoLocator && this.map){
            var markerPos = this.map.fromLatLngToDivPixel(this.marker.getPoint());
            var pos = this.map.fromContainerPixelToLatLng({
                x: markerPos.x + position.x,
                y: markerPos.y + position.y
            });
            this.map.setCenter(pos);
        } else {
            this.newCenter = position;
        }
    };
    
    this.initialize(options);
};

function Tooltip(marker, text){

    this.isIE6 = function(){
        if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
            var ieversion = new Number(RegExp.$1); 
            return (ieversion == 6);
        }
        return false;
    };

    this.initialize = function(map){
        this.map   = map;
        this.div   = document.createElement("div");
        var top    = document.createElement("div");    top   .className = "top"    + ((this.isIE6()) ? " IE6"       : "");
        var middle = document.createElement("div");    middle.className = "middle" + ((this.isIE6()) ? " MIDDLEIE6" : "");
        var bottom = document.createElement("div");    bottom.className = "bottom" + ((this.isIE6()) ? " BOTTOMIE6" : "");

        middle.innerHTML = text;
        
        this.div.appendChild(top   );
        this.div.appendChild(middle);
        this.div.appendChild(bottom);
        
        this.div.className        = 'tooltip';
        this.div.style.position   = 'absolute';
        this.div.style.visibility = 'hidden';

        map.getPane(G_MAP_FLOAT_PANE).appendChild(this.div);
    };
    
    this.remove = function(){
        this.div.parentNode.removeChild(this.div);
    };
    
    this.copy = function(){
        return new Tooltip(this.marker, this.text, this.padding);
    };
    
    this.redraw = function(force){
        if (!force)
            return;
    
        var markerPos  = this.map.fromLatLngToDivPixel(this.marker.getPoint());
        var iconAnchor = this.marker.getIcon().iconAnchor;
        var xPos       = (markerPos.x - (this.div.offsetWidth / 2));
        var yPos       = markerPos.y - iconAnchor.y - this.div.offsetHeight;
        this.div.style.top  = yPos + 'px';
        this.div.style.left = xPos + 'px';
    };

    this.show = function(){
        this.div.style.visibility = 'visible';
    };
    
    this.hide = function(){
        this.div.style.visibility = 'hidden';
    };
    
	this.marker    = marker;
	this.text      = text;
	this.prototype = new GOverlay();
};