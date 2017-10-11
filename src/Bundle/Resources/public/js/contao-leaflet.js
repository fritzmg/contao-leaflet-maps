L.Contao=L.Evented.extend({statics:{ATTRIBUTION:' | <a href="https://netzmacht.de/contao-leaflet" title="Powered by Leaflet extension for Contao CMS developed by netzmacht David Molineus">netzmacht</a>'},maps:{},icons:{},initialize:function(){L.Icon.Default.imagePath="assets/leaflet/libs/leaflet/images/",this.setGeoJsonListeners(L.GeoJSON)},addMap:function(t,o){return this.maps[t]=o,this.fire("map:added",{id:t,map:o}),this},getMap:function(t){return void 0===this.maps[t]?null:this.maps[t]},addIcon:function(t,o){return this.icons[t]=o,this.fire("icon:added",{id:t,icon:o}),this},loadIcons:function(t){for(var o=0;o<t.length;o++){var e;e="extraMarkers.icon"===t[o].type?L.ExtraMarkers.icon(t[o].options):L[t[o].type](t[o].options),this.addIcon(t[o].id,e)}},getIcon:function(t){return void 0===this.icons[t]?null:this.icons[t]},load:function(t,o,e,n,i){var s=this.createRequestUrl(t,i),r=omnivore[o](s,e,n);return i&&(L.stamp(r),i.options.dynamicLoad&&"fit"==r.options.boundsMode&&(r.options.requestHash=t,i.on("moveend",r.refreshData,r),i.on("layerremove",function(t){t.layer===r&&i.off("moveend",r.updateBounds,r)})),i.fire("dataloading",{layer:r}),r.on("ready",function(){i.calculateFeatureBounds(r),i.fire("dataload",{layer:r})}),r.on("error",function(){i.fire("dataload",{layer:r})})),r},pointToLayer:function(t,o){var e="marker",n=null;if(t.properties&&(t.properties.bounds=!0,t.properties.type&&(e=t.properties.type),t.properties.arguments&&(n=L[e].apply(L[e],t.properties.arguments),L.Util.setOptions(n,t.properties.options))),null===n&&(n=L[e](o,t.properties.options)),t.properties){if(t.properties.radius&&n.setRadius(t.properties.radius),t.properties.icon){var i=this.getIcon(t.properties.icon);i&&n.setIcon(i)}this.bindPopupFromFeature(n,t)}return this.fire("point:added",{marker:n,feature:t,latlng:o,type:e}),n},onEachFeature:function(t,o){t.properties&&(L.Util.setOptions(o,t.properties.options),this.bindPopupFromFeature(o,t),this.fire("feature:added",{feature:t,layer:o}))},bindPopupFromFeature:function(t,o){o.properties&&(o.properties.popup?t.bindPopup(o.properties.popup,o.properties.popupOptions):o.properties.popupContent&&t.bindPopup(o.properties.popupContent,o.properties.popupOptions))},setGeoJsonListeners:function(t){t&&t.prototype&&(t.prototype.options={pointToLayer:this.pointToLayer.bind(this),onEachFeature:this.onEachFeature.bind(this)})},createRequestUrl:function(t,o){var e,n="leaflet",i=document.location.search.substr(1).split("&");if(t=encodeURIComponent(t),""==i)t=document.location.pathname+"?"+[n,t].join("=");else{for(var s,r=i.length;r--;)if((s=i[r].split("="))[0]==n){s[1]=t,i[r]=s.join("=");break}r<0&&(i[i.length]=[n,t].join("=")),t=document.location.pathname+"?"+i.join("&")}return o&&o.options.dynamicLoad&&(t+="&f=bbox&v=",t+=(e=o.getBounds()).getSouth()+","+e.getWest(),t+=","+e.getNorth()+","+e.getEast()),t}}),L.contao=new L.Contao,L.Control.Attribution.addInitHook(function(){this.options.prefix+=L.Contao.ATTRIBUTION}),L.Control.Attribution.include({setPrefix:function(t){return-1===t.indexOf(L.Contao.ATTRIBUTION)&&(t+=L.Contao.ATTRIBUTION),this.options.prefix=t,this._update(),this}}),L.GeoJSON.include({refreshData:function(t){var o=L.geoJson(),e=this;o.on("ready",function(){var t,o=e.getLayers();for(t=0;t<o.length;t++)e.removeLayer(o[t]);for(o=this.getLayers(),t=0;t<o.length;t++)this.removeLayer(o[t]),e.addLayer(o[t])}),omnivore.geojson(L.contao.createRequestUrl(this.options.requestHash,t.target),null,o)}}),L.Map.include({_dynamicBounds:null,calculateFeatureBounds:function(t,o){if(t){if(!this.options.adjustBounds&&!o)return;this._scanForBounds(t)}else this.eachLayer(this._scanForBounds,this);this._dynamicBounds&&this.fitBounds(this._dynamicBounds,this.getBoundsOptions())},getBoundsOptions:function(){return options={},this.options.boundsPadding?options.padding=this.options.boundsPadding:(this.options.boundsPaddingTopLeft&&(options.paddingTopLeft=this.options.boundsPaddingTopLeft),this.options.boundsPaddingBottomRight&&(options.paddingBottomRight=this.options.boundsPaddingBottomRight)),options},_scanForBounds:function(t){var o;!t.feature||t.feature.properties&&t.feature.properties.ignoreForBounds?L.MarkerClusterGroup&&t instanceof L.MarkerClusterGroup&&t.options.boundsMode&&"extend"==t.options.boundsMode?(o=t.getBounds()).isValid()&&(this._dynamicBounds?this._dynamicBounds.extend(o):this._dynamicBounds=L.latLngBounds(o.getSouthWest(),o.getNorthEast())):(!t.options||t.options&&t.options.boundsMode&&!t.options.ignoreForBounds)&&t.eachLayer&&t.eachLayer(this._scanForBounds,this):t.getBounds?(o=t.getBounds()).isValid()&&(this._dynamicBounds?this._dynamicBounds.extend(o):this._dynamicBounds=L.latLngBounds(o.getSouthWest(),o.getNorthEast())):t.getLatLng&&(o=t.getLatLng(),this._dynamicBounds?this._dynamicBounds.extend(o):this._dynamicBounds=L.latLngBounds(o,o))}}),L.LatLngBounds.prototype.toOverpassBBoxString=function(){var t=this._southWest,o=this._northEast;return[t.lat,t.lng,o.lat,o.lng].join(",")},L.OverPassLayer=L.FeatureGroup.extend({options:{minZoom:0,endpoint:"//overpass-api.de/api/",query:"(node(BBOX)[organic];node(BBOX)[second_hand];);out qt;",amenityIcons:{}},initialize:function(t){t.pointToLayer||(t.pointToLayer=this.pointToLayer),t.onEachFeature||(t.onEachFeature=this.onEachFeature),L.Util.setOptions(this,t),this.options.dynamicLoad=!!this.options.query.match(/BBOX/g),this._layer=L.geoJson(),this._layers={},this.addLayer(this._layer)},refreshData:function(){if(!(this._map.getZoom()<this.options.minZoom)){var t=this._map.getBounds().toOverpassBBoxString(),o=this.options.query.replace(/(BBOX)/g,t),e=this.options.endpoint+"interpreter?data=[out:json];"+o;this._map.fire("dataloading",{layer:this}),this.request(e,function(t,o){var e=JSON.parse(o.response),n=osmtogeojson(e),i=L.geoJson(n,{pointToLayer:this.options.pointToLayer.bind(this),onEachFeature:this.options.onEachFeature.bind(this)});if(this.addLayer(i),this.removeLayer(this._layer),this._layer=i,"extend"===this.options.boundsMode&&i.getBounds().isValid()){var s=this._map.getBounds();s=s.extend(i.getBounds()),this._map.fitBounds(s,this._map.getBoundsOptions())}this._map.fire("dataload",{layer:this})}.bind(this))}},onAdd:function(t){"fit"===this.options.boundsMode&&this.options.dynamicLoad&&t.on("moveend",this.refreshData,this),this.refreshData()},pointToLayer:function(t,o){var e=null,n=L.marker(o,t.properties.options);return t.properties&&(t.properties.radius&&n.setRadius(t.properties.radius),t.properties.icon?e=this._map.getIcon(t.properties.icon):t.properties.tags&&t.properties.tags.amenity&&this.options.amenityIcons[t.properties.tags.amenity]&&(e=L.contao.getIcon(this.options.amenityIcons[t.properties.tags.amenity])),e&&n.setIcon(e)),this.options.overpassPopup&&n.bindPopup(this.options.overpassPopup(t,n)),this._map.fire("point:added",{marker:n,feature:t,latlng:o,type:"marker"}),n},onEachFeature:function(t,o){t.properties&&(L.Util.setOptions(o,t.properties.options),this.options.overpassPopup&&o.bindPopup(this.options.overpassPopup(t,o)),this._map.fire("feature:added",{feature:t,layer:o}))},request:function(t,o,e){function n(t){return t>=200&&t<300||304===t}function i(){void 0===a.status||n(a.status)?o.call(a,null,a):o.call(a,a,null)}var s=!1;if(void 0===window.XMLHttpRequest)return o(Error("Browser not supported"));if(void 0===e){var r=t.match(/^\s*https?:\/\/[^\/]*/);e=r&&r[0]!==location.protocol+"//"+location.hostname+(location.port?":"+location.port:"")}var a=new window.XMLHttpRequest;if(e&&!("withCredentials"in a)){a=new window.XDomainRequest;var p=o;o=function(){if(s)p.apply(this,arguments);else{var t=this,o=arguments;setTimeout(function(){p.apply(t,o)},0)}}}return"onload"in a?a.onload=i:a.onreadystatechange=function(){4===a.readyState&&i()},a.onerror=function(t){o.call(this,t||!0,null),o=function(){}},a.onprogress=function(){},a.ontimeout=function(t){o.call(this,t,null),o=function(){}},a.onabort=function(t){o.call(this,t,null),o=function(){}},a.open("GET",t,!0),a.send(null),s=!0,a}});