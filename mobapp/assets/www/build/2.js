webpackJsonp([2],{

/***/ 410:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "UnidprodPageModule", function() { return UnidprodPageModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__unidprod__ = __webpack_require__(424);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};



var UnidprodPageModule = /** @class */ (function () {
    function UnidprodPageModule() {
    }
    UnidprodPageModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["I" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_2__unidprod__["a" /* UnidprodPage */],
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["e" /* IonicPageModule */].forChild(__WEBPACK_IMPORTED_MODULE_2__unidprod__["a" /* UnidprodPage */]),
            ],
        })
    ], UnidprodPageModule);
    return UnidprodPageModule;
}());

//# sourceMappingURL=unidprod.module.js.map

/***/ }),

/***/ 424:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return UnidprodPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_rest_rest__ = __webpack_require__(75);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__providers_util_singleton_service__ = __webpack_require__(59);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_rxjs_add_operator_map__ = __webpack_require__(76);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_rxjs_add_operator_map___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_4_rxjs_add_operator_map__);
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
 * Generated class for the UnidprodPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */
var UnidprodPage = /** @class */ (function () {
    function UnidprodPage(navCtrl, navParams, restProvider, singleton) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.restProvider = restProvider;
        this.singleton = singleton;
    }
    UnidprodPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad UnidprodPage');
        this.getUnidadesProdutivas();
    };
    UnidprodPage.prototype.getUnidadesProdutivas = function () {
        var _this = this;
        console.log("singleton_uid2:" + this.singleton.loginuid);
        this.restProvider.getRest('{"operacao":"getups","uid":"' + this.singleton.loginuid + '"}')
            .then(function (data) {
            console.log(data);
            _this.ups = data;
        });
    };
    UnidprodPage.prototype.goUnidadeProdutiva = function (i) {
        console.log(this.ups[i]);
        this.navCtrl.push('SingleupPage', { up: this.ups[i] });
    };
    UnidprodPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-unidprod',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/unidprod/unidprod.html"*/'<!--\n  Generated template for the UnidprodPage page.\n\n  See http://ionicframework.com/docs/components/#navigation for more info on\n  Ionic pages and navigation.\n-->\n<ion-header>\n\n    <ion-navbar>  \n        <ion-grid>\n            <ion-row>\n                <ion-col>\n                    <button ion-button menuToggle>\n                        <ion-icon name="menu"></ion-icon>\n                    </button>\n                </ion-col>\n                <ion-col id="title">\n                    <strong style="font-family: \'Lato\'; font-size: 22px;color:#ffffff; margin-left: -60px;">Unidades Produtivas</strong>\n                </ion-col>\n                \n            </ion-row>\n        </ion-grid>\n    </ion-navbar>\n\n</ion-header>\n\n\n<ion-content padding style="background-color: #f9ecc7;" > \n    <ion-card *ngFor="let up of ups; let i = index" [attr.data-index]="i">\n        <ion-card-content>  \n          <div class="row3">\n            <div class="column column-4">\n              <img src="/assets/svgs/farm1.svg" style="max-width:40px; margin-top:15px;margin-left:10px;">\n            </div>\n           \n            <div class="column column-20">\n                <ion-card-title text-left style="font-family: \'Lato\'; font-size: 22px;">\n                    {{up.strnomecomum}}\n                </ion-card-title>\n            </div>\n          </div>\n\n       \n        \n            \n            <p text-center>\n                {{up.strendereco}},<br/>{{up.strcep}},{{up.strmunicipio}}<br/>\n            </p>\n            <br/>\n            \n\n        </ion-card-content>\n\n        <ion-row no-padding>      \n           \n            <ion-col text-right>\n                \n                <button ion-button clear  icon-start (click)="goUnidadeProdutiva(i)">\n                    <img src="/assets/svgs/vermais.svg" style="max-width:40px; margin-left:10px;" >\n                </button>            \n            </ion-col>\n        </ion-row>\n\n    </ion-card>\n\n</ion-content>\n'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/unidprod/unidprod.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */],
            __WEBPACK_IMPORTED_MODULE_2__providers_rest_rest__["a" /* RestProvider */],
            __WEBPACK_IMPORTED_MODULE_3__providers_util_singleton_service__["a" /* SingletonService */]])
    ], UnidprodPage);
    return UnidprodPage;
}());

//# sourceMappingURL=unidprod.js.map

/***/ })

});
//# sourceMappingURL=2.js.map