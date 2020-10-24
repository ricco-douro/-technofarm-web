webpackJsonp([5],{

/***/ 406:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SinglepoloPageModule", function() { return SinglepoloPageModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__singlepolo__ = __webpack_require__(421);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};



var SinglepoloPageModule = /** @class */ (function () {
    function SinglepoloPageModule() {
    }
    SinglepoloPageModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["I" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_2__singlepolo__["a" /* SinglepoloPage */],
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["e" /* IonicPageModule */].forChild(__WEBPACK_IMPORTED_MODULE_2__singlepolo__["a" /* SinglepoloPage */]),
            ],
        })
    ], SinglepoloPageModule);
    return SinglepoloPageModule;
}());

//# sourceMappingURL=singlepolo.module.js.map

/***/ }),

/***/ 421:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return SinglepoloPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__ionic_native_geolocation__ = __webpack_require__(254);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__ionic_native_call_number__ = __webpack_require__(253);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var SinglepoloPage = /** @class */ (function () {
    function SinglepoloPage(callNumber, platform, geolocation, navCtrl, navParams) {
        //CARGO BASEADO EM UO
        var _this = this;
        this.callNumber = callNumber;
        this.platform = platform;
        this.geolocation = geolocation;
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.polo = this.navParams.get('polo');
        if (this.polo.PK0010UnidOrganica === 'AR') {
            this.cargo = "Diretor";
        }
        else {
            this.cargo = "Gerente";
        }
        // POSIÇÂO ATUAL DO UTILIZADOR
        this.platform.ready().then(function () {
            _this.geolocation.getCurrentPosition()
                .then(function (result) {
                _this.latOri = result.coords.latitude;
                _this.lngOri = result.coords.longitude;
                _this.latDest = _this.polo.AD0010UOLat;
                _this.lngDest = _this.polo.AD0010UOLong;
                _this.calcRota(_this.latOri, _this.lngOri, _this.latDest, _this.lngDest);
                console.log(_this.latOri);
                console.log(_this.lngOri);
            })
                .catch(function (err) { return console.log('geopositioning:', err); });
        });
    }
    SinglepoloPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad SinglepoloPage');
    };
    SinglepoloPage.prototype.callpolo = function () {
        var _this = this;
        this.callNumber.callNumber(this.polo.AD0010UOTelefone, true)
            .then(function (res) { return console.log('Launched dialer! ' + _this.polo.AD0010UOTelefone, res); })
            .catch(function (err) { return console.log('Error launching dialer ' + _this.polo.AD0010UOTelefone, err); });
    };
    SinglepoloPage.prototype.calcRota = function (latOri, lngOri, latDest, lngDest) {
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
        //var destinoIcon = 'http://intranet.mt.senac.br/images/app/senacsymbol.svg';
        //VARRIÁVEL DE LIMITES DA ROTA (UTILIZADOS NA APRESENTAÇÃO DAS ROTAS)
        var bounds = new google.maps.LatLngBounds;
        //VARIÁVEL QUE RENDERIZA O MAPA NO HTML
        var map = new google.maps.Map(document.getElementById('map'), {
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
    SinglepoloPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-singlepolo',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/polos/singlepolo/singlepolo.html"*/'<ion-header>\n\n    <ion-navbar>  \n        <ion-grid>\n            <ion-row>\n                <ion-col>\n                    <button ion-button menuToggle>\n                        <ion-icon name="menu"></ion-icon>\n                    </button>\n                </ion-col>\n                <ion-col>\n                    <strong  class="TxtTitlePage">Unidade</strong>\n                </ion-col>\n                <ion-col col-2>\n                    <ion-icon  class="IconPesquisa"   name="ios-search" (click)="goToPesquisa()"></ion-icon>\n                </ion-col>\n            </ion-row>\n        </ion-grid>\n    </ion-navbar>\n\n</ion-header>\n<ion-content class="cards-bg">\n    <ion-card>    \n        <ion-card-content>\n            <img src={{polo.AD0010UOFoto}}/>\n            <ion-card-title text-center>\n                {{polo.AD0010UnidOrganicaNome}}\n            </ion-card-title>\n            <p class="regfont">{{this.cargo}}: {{polo.AD0010UOGerente}}</p>\n            <p class="regfont"><ion-icon name="business"></ion-icon> Endereço: {{polo.AD0010UOEndereco}}, {{polo.AD0010UOCEP}}</p>\n            <p class="regfont"><ion-icon name=\'call\'></ion-icon> Tel: {{polo.AD0010UOTelefone}} <span style="border: 1px solid #F7941D;\n                background-color: #F7941D;\n                font-weight: 600;\n                color:#ffffff;\n                border-radius: 7px; \n                font-size:15px;\n                padding: 3px;\n                margin-left: 15px;" class="regfont" (click)="callpolo()">Ligar</span></p>\n            <p class="regfont"><ion-icon name=\'mail\'></ion-icon> Email: {{polo.AD0010UOEmail}} </p>\n            <p class="regfont"><ion-icon name=\'clock\'></ion-icon> Horário de Funcionamento: {{polo.AD0010UOHorario}}</p>            \n            <p class="regfont"><ion-icon name="pin"></ion-icon> Como Chegar:</p>            \n            \n            <div class="row regfont" id="resultado"></div>\n\n            <div class="row" style="min-height:300px;" id="map"></div>\n        </ion-card-content>\n    </ion-card>  \n</ion-content>\n'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/polos/singlepolo/singlepolo.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_3__ionic_native_call_number__["a" /* CallNumber */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["j" /* Platform */],
            __WEBPACK_IMPORTED_MODULE_2__ionic_native_geolocation__["a" /* Geolocation */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */]])
    ], SinglepoloPage);
    return SinglepoloPage;
}());

//# sourceMappingURL=singlepolo.js.map

/***/ })

});
//# sourceMappingURL=5.js.map