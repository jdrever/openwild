!function(t){if("function"==typeof define&&define.amd)define(["leaflet"],t);else if("undefined"!=typeof module)module.exports=t(require("leaflet"));else{if(void 0===this.L)throw"Leaflet must be loaded first!";this.L.WMS=this.L.wms=t(this.L)}}((function(t){var e={};"keys"in Object||(Object.keys=function(t){var e=[];for(var i in t)t.hasOwnProperty(i)&&e.push(i);return e}),e.Source=t.Layer.extend({options:{untiled:!0,identify:!0},initialize:function(e,i){t.setOptions(this,i),this.options.tiled&&(this.options.untiled=!1),this._url=e,this._subLayers={},this._overlay=this.createOverlay(this.options.untiled)},createOverlay:function(t){var i={};for(var r in this.options)"untiled"!=r&&"identify"!=r&&(i[r]=this.options[r]);return t?e.overlay(this._url,i):e.tileLayer(this._url,i)},onAdd:function(){this.refreshOverlay()},getEvents:function(){return this.options.identify?{click:this.identify}:{}},setOpacity:function(t){this.options.opacity=t,this._overlay&&this._overlay.setOpacity(t)},bringToBack:function(){this.options.isBack=!0,this._overlay&&this._overlay.bringToBack()},bringToFront:function(){this.options.isBack=!1,this._overlay&&this._overlay.bringToFront()},getLayer:function(t){return e.layer(this,t)},addSubLayer:function(t){this._subLayers[t]=!0,this.refreshOverlay()},removeSubLayer:function(t){delete this._subLayers[t],this.refreshOverlay()},refreshOverlay:function(){var t=Object.keys(this._subLayers).join(",");this._map&&(t?(this._overlay.setParams({layers:t}),this._overlay.addTo(this._map)):this._overlay.remove())},identify:function(t){var e=this.getIdentifyLayers();e.length&&this.getFeatureInfo(t.containerPoint,t.latlng,e,this.showFeatureInfo)},getFeatureInfo:function(e,i,r,s){var n=this.getFeatureInfoParams(e,r),o=this._url+t.Util.getParamString(n,this._url);this.showWaiting(),this.ajax(o,(function(t){this.hideWaiting();var e=this.parseFeatureInfo(t,o);s.call(this,i,e)}))},ajax:function(t,e){r.call(this,t,e)},getIdentifyLayers:function(){return this.options.identifyLayers?this.options.identifyLayers:Object.keys(this._subLayers)},getFeatureInfoParams:function(e,i){var r,s;this.options.untiled?r=this._overlay.wmsParams:((s=this.createOverlay(!0)).updateWmsParams(this._map),(r=s.wmsParams).layers=i.join(","));var n={request:"GetFeatureInfo",query_layers:i.join(","),X:Math.round(e.x),Y:Math.round(e.y)};return t.extend({},r,n)},parseFeatureInfo:function(t,e){return"error"==t&&(t="<iframe src='"+e+"' style='border:none'>"),t},showFeatureInfo:function(t,e){this._map&&this._map.openPopup(e,t)},showWaiting:function(){this._map&&(this._map._container.style.cursor="progress")},hideWaiting:function(){this._map&&(this._map._container.style.cursor="default")}}),e.source=function(t,i){return new e.Source(t,i)},e.Layer=t.Layer.extend({initialize:function(i,r,s){t.setOptions(this,s),i.addSubLayer||(i=e.getSourceForUrl(i,s)),this._source=i,this._name=r},onAdd:function(){this._source._map||this._source.addTo(this._map),this._source.addSubLayer(this._name)},onRemove:function(){this._source.removeSubLayer(this._name)},setOpacity:function(t){this._source.setOpacity(t)},bringToBack:function(){this._source.bringToBack()},bringToFront:function(){this._source.bringToFront()}}),e.layer=function(t,i){return new e.Layer(t,i)};var i={};function r(t,e){var i=this,r=new XMLHttpRequest;r.onreadystatechange=function(){4===r.readyState&&(200===r.status?e.call(i,r.responseText):e.call(i,"error"))},r.open("GET",t),r.send()}return e.getSourceForUrl=function(t,r){return i[t]||(i[t]=e.source(t,r)),i[t]},e.TileLayer=t.TileLayer.WMS,e.tileLayer=t.tileLayer.wms,e.Overlay=t.Layer.extend({defaultWmsParams:{service:"WMS",request:"GetMap",version:"1.1.1",layers:"",styles:"",format:"image/jpeg",transparent:!1},options:{crs:null,uppercase:!1,attribution:"",opacity:1,isBack:!1,minZoom:0,maxZoom:18},initialize:function(e,i){this._url=e;var r={},s={};for(var n in i)n in this.options?s[n]=i[n]:r[n]=i[n];t.setOptions(this,s),this.wmsParams=t.extend({},this.defaultWmsParams,r)},setParams:function(e){t.extend(this.wmsParams,e),this.update()},getAttribution:function(){return this.options.attribution},onAdd:function(){this.update()},onRemove:function(t){this._currentOverlay&&(t.removeLayer(this._currentOverlay),delete this._currentOverlay),this._currentUrl&&delete this._currentUrl},getEvents:function(){return{moveend:this.update}},update:function(){if(this._map){this.updateWmsParams();var e=this.getImageUrl();if(this._currentUrl!=e){this._currentUrl=e;var i=this._map.getBounds(),r=t.imageOverlay(e,i,{opacity:0});r.addTo(this._map),r.once("load",(function(){if(!this._map)return;if(r._url!=this._currentUrl)return void this._map.removeLayer(r);this._currentOverlay&&this._map.removeLayer(this._currentOverlay);this._currentOverlay=r,r.setOpacity(this.options.opacity?this.options.opacity:1),!0===this.options.isBack&&r.bringToBack();!1===this.options.isBack&&r.bringToFront()}),this),(this._map.getZoom()<this.options.minZoom||this._map.getZoom()>this.options.maxZoom)&&this._map.removeLayer(r)}}},setOpacity:function(t){this.options.opacity=t,this._currentOverlay&&this._currentOverlay.setOpacity(t)},bringToBack:function(){this.options.isBack=!0,this._currentOverlay&&this._currentOverlay.bringToBack()},bringToFront:function(){this.options.isBack=!1,this._currentOverlay&&this._currentOverlay.bringToFront()},updateWmsParams:function(e){e||(e=this._map);var i=e.getBounds(),r=e.getSize(),s=parseFloat(this.wmsParams.version),n=this.options.crs||e.options.crs,o=s>=1.3?"crs":"srs",a=n.project(i.getNorthWest()),u=n.project(i.getSouthEast()),h={width:r.x,height:r.y};h[o]=n.code,h.bbox=(s>=1.3&&n===t.CRS.EPSG4326?[u.y,a.x,a.y,u.x]:[a.x,u.y,u.x,a.y]).join(","),t.extend(this.wmsParams,h)},getImageUrl:function(){var e=this.options.uppercase||!1,i=t.Util.getParamString(this.wmsParams,this._url,e);return this._url+i}}),e.overlay=function(t,i){return new e.Overlay(t,i)},e}));
