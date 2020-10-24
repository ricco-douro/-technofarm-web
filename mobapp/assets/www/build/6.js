webpackJsonp([6],{

/***/ 405:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ListaPolosPageModule", function() { return ListaPolosPageModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__listapolos__ = __webpack_require__(420);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};



var ListaPolosPageModule = /** @class */ (function () {
    function ListaPolosPageModule() {
    }
    ListaPolosPageModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["I" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_2__listapolos__["a" /* ListaPolosPage */],
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["e" /* IonicPageModule */].forChild(__WEBPACK_IMPORTED_MODULE_2__listapolos__["a" /* ListaPolosPage */]),
            ],
        })
    ], ListaPolosPageModule);
    return ListaPolosPageModule;
}());

//# sourceMappingURL=listapolos.module.js.map

/***/ }),

/***/ 420:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ListaPolosPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_rest_rest__ = __webpack_require__(75);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__ionic_native_call_number__ = __webpack_require__(253);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__providers_util_loading_service__ = __webpack_require__(145);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};





var ListaPolosPage = /** @class */ (function () {
    function ListaPolosPage(loader, callNumber, navCtrl, navParams, restProvider) {
        var _this = this;
        this.loader = loader;
        this.callNumber = callNumber;
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.restProvider = restProvider;
        this.loader.presentWithMessage("Por favor, aguarde");
        this.loader.dismiss().then(function () {
            _this.getPolos();
        });
    }
    ListaPolosPage.prototype.getPolos = function () {
        var _this = this;
        console.log("GETPOLOS");
        this.restProvider.getRest('{"operacao":"getpolos"}')
            .then(function (data) {
            console.log(data);
            _this.polos = data;
        });
    };
    ListaPolosPage.prototype.goToPolo = function (i) {
        console.log(i);
        console.log(this.polos[i].AD0010UnidOrganicaNome);
        console.log(this.polos[i]);
        this.navCtrl.push('SinglepoloPage', { polo: this.polos[i] });
    };
    ListaPolosPage.prototype.goToPesquisa = function () {
        this.navCtrl.push('PesquisacursoPage');
    };
    ListaPolosPage.prototype.callpolo = function (i) {
        var _this = this;
        this.callNumber.callNumber(this.polos[i].AD0010UOTelefone, true)
            .then(function (res) { return console.log('Launched dialer! ' + _this.polos[i].AD0010UOTelefone, res); })
            .catch(function (err) { return console.log('Error launching dialer ' + _this.polos[i].AD0010UOTelefone, err); });
    };
    ListaPolosPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad ListapolosPage');
    };
    ListaPolosPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-listapolos',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/polos/listapolos/listapolos.html"*/'<ion-header>\n\n    <ion-navbar>\n        <ion-grid>\n            <ion-row>\n                <ion-col>\n                    <button ion-button menuToggle>\n                        <ion-icon name="menu"></ion-icon>\n                    </button>\n                </ion-col>\n                <ion-col>\n                    <strong class="TxtTitlePage">Nossas Unidades</strong>\n                </ion-col>\n                <ion-col col-2>\n                    <ion-icon  class="IconPesquisa"   name="ios-search" (click)="goToPesquisa()"></ion-icon>\n                </ion-col>\n            </ion-row>\n        </ion-grid>\n    </ion-navbar>\n    \n\n</ion-header>\n<ion-content class="cards-bg">\n\n    <ion-card *ngFor="let polo of polos; let i = index" [attr.data-index]="i">\n\n        <img src={{polo.AD0010UOFoto}}/>\n\n        <ion-card-content>\n            <ion-card-title text-center>\n                {{polo.AD0010UnidOrganicaNome}}\n            </ion-card-title>\n            <p text-center>\n                {{polo.AD0010UOEndereco}}, {{polo.AD0010UOCEP}}<br/>\n            </p>\n            <br/>\n            <p text-center>\n                <span><ion-icon name=\'call\'></ion-icon> {{polo.AD0010UOTelefone}}</span>\n                /\n                <span><ion-icon name=\'clock\'></ion-icon> {{polo.AD0010UOHorario}}</span>\n            </p>\n\n        </ion-card-content>\n\n        <ion-row no-padding>      \n            <ion-col text-left> \n                <a ng-href="">               \n                    <button ion-button clear class="TxtSenacLaranja" small  icon-start (click)="callpolo(i)">\n                        <ion-icon name=\'call\'></ion-icon>\n                        Ligar\n                    </button>\n                </a>\n            </ion-col>\n            <ion-col text-right>\n                <button ion-button clear small class="TxtSenacLaranja" icon-start (click)="goToPolo(i)">\n                    <ion-icon name=\'add-circle\'></ion-icon>\n                    Info\n                </button>            \n            </ion-col>\n        </ion-row>\n\n    </ion-card>\n</ion-content>\n'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/polos/listapolos/listapolos.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_4__providers_util_loading_service__["a" /* LoadingService */], __WEBPACK_IMPORTED_MODULE_3__ionic_native_call_number__["a" /* CallNumber */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */], __WEBPACK_IMPORTED_MODULE_2__providers_rest_rest__["a" /* RestProvider */]])
    ], ListaPolosPage);
    return ListaPolosPage;
}());

//# sourceMappingURL=listapolos.js.map

/***/ })

});
//# sourceMappingURL=6.js.map