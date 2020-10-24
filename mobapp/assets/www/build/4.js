webpackJsonp([4],{

/***/ 408:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SingleupPageModule", function() { return SingleupPageModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__singleup__ = __webpack_require__(422);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};



var SingleupPageModule = /** @class */ (function () {
    function SingleupPageModule() {
    }
    SingleupPageModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["I" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_2__singleup__["a" /* SingleupPage */],
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["e" /* IonicPageModule */].forChild(__WEBPACK_IMPORTED_MODULE_2__singleup__["a" /* SingleupPage */]),
            ],
        })
    ], SingleupPageModule);
    return SingleupPageModule;
}());

//# sourceMappingURL=singleup.module.js.map

/***/ }),

/***/ 422:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return SingleupPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__ionic_native_geolocation__ = __webpack_require__(254);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};



/**
 * Generated class for the SingleupPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */
var SingleupPage = /** @class */ (function () {
    function SingleupPage(navCtrl, navParams, platform, geolocation) {
        var _this = this;
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.platform = platform;
        this.geolocation = geolocation;
        this.UnidProd = this.navParams.get('up');
        // POSIÇÂO ATUAL DO UTILIZADOR
        this.platform.ready().then(function () {
            _this.geolocation.getCurrentPosition()
                .then(function (result) {
                _this.latOri = result.coords.latitude;
                _this.lngOri = result.coords.longitude;
                _this.latDest = _this.UnidProd.strlatcent;
                _this.lngDest = _this.UnidProd.strlngcent;
                _this.calcRota(_this.latOri, _this.lngOri, _this.latDest, _this.lngDest);
                console.log(_this.latOri);
                console.log(_this.lngOri);
            })
                .catch(function (err) { return console.log('geopositioning:', err); });
        });
        //if (this.mymap == null){
        //  this.loadmap();
        //}
    }
    SingleupPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad SingleupPage');
    };
    SingleupPage.prototype.calcRota = function (latOri, lngOri, latDest, lngDest) {
        // VARIÁVEL DO SERVIÇO DE DIREÇÃO (API) DO GOOGLE.
        var directionService = new google.maps.DirectionsService;
        // VÁRIAVEL PARA MOSTRAR (RENDERIZAR) A ROTA NO MAPA
        var directionDisplay = new google.maps.DirectionsRenderer;
        //VARIÁVEIS DE ORIGEM E DESTINO CONVERTIDAS E PREPARADAS EM ARRAY
        var origem = { lat: parseFloat(latOri), lng: parseFloat(lngOri) };
        var destino = { lat: parseFloat(latDest), lng: parseFloat(lngDest) };
        //VARIÁVEIS COM OS ICONS DA LOCALIZAÇÃO
        //var origemIcon = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=O|FF0000|000000';
        //var destinoIcon = 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=D|FFFF00|000000';
        //VARRIÁVEL DE LIMITES DA ROTA (UTILIZADOS NA APRESENTAÇÃO DAS ROTAS)
        var bounds = new google.maps.LatLngBounds;
        //VARIÁVEL QUE RENDERIZA O MAPA NO HTML
        var map = new google.maps.Map(document.getElementById('mymap'), {
            center: { lat: latOri, lng: lngOri },
            zoom: 17
        });
        //SETAR O MAPA NO HTML
        directionDisplay.setMap(map);
        //VARIÁVEL DE GEOCODER DO GOOGLE
        var geoCoder = new google.maps.Geocoder;
        //VARIÁVEL QUE REALIZARÁ O CÁLCULO DA DISTÂNCIA ENTRE DOIS PONTOS
        var service = new google.maps.DistanceMatrixService;
        service.getDistanceMatrix({
            origins: [origem],
            destinations: [destino],
            travelMode: 'DRIVING',
            unitSystem: google.maps.UnitSystem.METRIC
        }, function (response, status) {
            if (status !== 'OK') {
                alert('Houve algum problema' + status);
            }
            else {
                //ARMAZENA O ENDEREÇO DE ORIGEM
                var originList = response.originAddresses;
                //ARMAZENA O ENDEREÇO DE DESTINO
                var destinationList = response.destinationAddresses;
                //ELEMENTO RESUTADO (DIV NO HTML) PARA DEMONSTRAR O RESULTADO
                var resultado = document.getElementById('resultado');
                resultado.innerHTML = '';
                var showEnderecoOnMap = function (asDestination) {
                    //var icon = asDestination ? destinoIcon : origemIcon;
                    return function (results, status) {
                        if (status === 'OK') {
                            map.fitBounds(bounds.extend(results[0].geometry.location));
                        }
                        else {
                            alert('Geocode Erro');
                        }
                    };
                };
                directionService.route({
                    origin: origem,
                    destination: destino,
                    travelMode: 'DRIVING'
                }, function (response, status) {
                    directionDisplay.setDirections(response);
                });
                for (var i = 0; i < originList.length; i++) {
                    var results = response.rows[i].elements;
                    geoCoder.geocode({ 'address': originList[i] }, showEnderecoOnMap(false));
                    for (var j = 0; j < results.length; j++) {
                        geoCoder.geocode({ 'address': destinationList[j] }, showEnderecoOnMap(true));
                        resultado.innerHTML += 'DE: ' + originList[i] + "<br/>PARA: " + destinationList[j] +
                            '<br/>DISTÂNCIA: ' + results[j].distance.text + ' em ' +
                            results[j].duration.text;
                    }
                }
            }
        });
    };
    __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["_8" /* ViewChild */])('mymap'),
        __metadata("design:type", __WEBPACK_IMPORTED_MODULE_0__angular_core__["t" /* ElementRef */])
    ], SingleupPage.prototype, "mapContainer", void 0);
    SingleupPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-singleup',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/singleup/singleup.html"*/'    <script src="http://www.technofarm.com.br/libraries/leaflet/js/bundle.js"></script>\n    <script src="https://cdn.rawgit.com/calvinmetcalf/shapefile-js/gh-pages/dist/shp.js"></script>\n    <script src="https://cdn.rawgit.com/calvinmetcalf/leaflet.shapefile/gh-pages/leaflet.shpfile.js"></script>\n\n\n<ion-header>\n\n  <ion-navbar>\n    <ion-title>{{UnidProd.strnomecomum}}</ion-title>\n  </ion-navbar>\n\n</ion-header>\n\n\n<ion-content class="cards-bg">\n    <ion-card>    \n        <ion-card-content>\n            <img src="/assets/svgs/farm1.svg" style="max-width:40px; margin-top:15px;margin-left:10px;">\n           \n            <ion-card-title text-center>\n                {{UnidProd.strnomecomum}}\n            </ion-card-title>\n            <p style="font-family: \'Lato\'; font-size: 12px;"><ion-icon name=\'mail\'></ion-icon> Endereço: {{UnidProd.strendereco}},<br/>{{UnidProd.strcep}},{{UnidProd.strmunicipio}}</p>   \n            <p style="font-family: \'Lato\'; font-size: 12px;"><ion-icon name="pin"></ion-icon> Como Chegar:</p>            \n            <div class="row" id="resultado" style="font-family: \'Lato\'; font-size: 12px;"></div>\n            <div class="row" style="min-height:300px;" id="mymap"></div>\n            <br/>\n            <div class="row">\n                <div class="column column-6">\n                  Biomas:\n                </div>\n                <div class="column column-6">\n                    Cerrado:<img src="/assets/svgs/{{UnidProd.strcerrado}}.svg" style="width:30px;margin-top:-10px;">\n                </div>\n                <div class="column column-6">\n                    Pantanal:<img src="/assets/svgs/{{UnidProd.strpantanal}}.svg" style="width:30px;margin-top:-10px;">\n                </div>\n                <div class="column column-6">\n                    Amazônia:<img src="/assets/svgs/{{UnidProd.stramazonia}}.svg" style="width:30px;margin-top:-10px;">\n                </div>\n            </div>\n            <div class="row">\n              <div id=\'map\' style=\'width: 100%; height: 300px; position: relative;\' class=\'leaflet-container leaflet-touch leaflet-retina leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom\' tabindex=\'0\'></div>\n            </div>\n          </ion-card-content>\n    </ion-card>  \n</ion-content>\n'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/singleup/singleup.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["j" /* Platform */],
            __WEBPACK_IMPORTED_MODULE_2__ionic_native_geolocation__["a" /* Geolocation */]])
    ], SingleupPage);
    return SingleupPage;
}());

//# sourceMappingURL=singleup.js.map

/***/ })

});
//# sourceMappingURL=4.js.map