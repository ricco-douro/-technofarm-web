webpackJsonp([0],{

/***/ 400:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "FaqPageModule", function() { return FaqPageModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__faq__ = __webpack_require__(418);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};



var FaqPageModule = /** @class */ (function () {
    function FaqPageModule() {
    }
    FaqPageModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["I" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_2__faq__["a" /* FaqPage */],
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["e" /* IonicPageModule */].forChild(__WEBPACK_IMPORTED_MODULE_2__faq__["a" /* FaqPage */]),
            ],
        })
    ], FaqPageModule);
    return FaqPageModule;
}());

//# sourceMappingURL=faq.module.js.map

/***/ }),

/***/ 411:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return BrowserPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};


//import { InAppBrowser } from '@ionic-native/in-app-browser';
/**
 * Generated class for the BrowserPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */
var BrowserPage = /** @class */ (function () {
    function BrowserPage(/*private iab: InAppBrowser,*/ navCtrl, navParams) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.myurl = navParams.get('url');
        console.log(this.myurl);
    }
    BrowserPage.prototype.ionViewDidLoad = function () {
        //const browser = this.iab.create('https://ionicframework.com/');
        //var ref = cordova.InAppBrowser.open(url, target, options);
        console.log('ionViewDidLoad BrowserPage');
    };
    BrowserPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-browser',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/browser/browser.html"*/'<!--\n  Generated template for the BrowserPage page.\n\n  See http://ionicframework.com/docs/components/#navigation for more info on\n  Ionic pages and navigation.\n-->\n<ion-header>\n\n  <ion-navbar>\n    <ion-title>browser</ion-title>\n  </ion-navbar>\n\n</ion-header>\n\n\n<ion-content padding>\n\n</ion-content>\n'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/browser/browser.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */]])
    ], BrowserPage);
    return BrowserPage;
}());

//# sourceMappingURL=browser.js.map

/***/ }),

/***/ 418:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return FaqPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_rest_rest__ = __webpack_require__(75);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__browser_browser__ = __webpack_require__(411);
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





var FaqPage = /** @class */ (function () {
    function FaqPage(loader, restProvider, navCtrl, navParams) {
        var _this = this;
        this.loader = loader;
        this.restProvider = restProvider;
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.loader.presentWithMessage("Por favor, aguarde");
        this.loader.dismiss().then(function () {
            _this.getFAQs();
        });
    }
    FaqPage.prototype.getFAQs = function () {
        var _this = this;
        this.restProvider.getRest('{"operacao":"getfaqs"}')
            .then(function (data) {
            _this.faqs = data;
        });
    };
    FaqPage.prototype.goToPesquisa = function () {
        this.navCtrl.push('PesquisacursoPage');
    };
    FaqPage.prototype.NavigateTo = function (url) {
        console.log('url: ' + url);
        this.navCtrl.push(__WEBPACK_IMPORTED_MODULE_3__browser_browser__["a" /* BrowserPage */], url);
    };
    FaqPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-faq',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/faq/faq.html"*/'<ion-header>\n\n    <ion-navbar>  \n        <ion-grid>\n            <ion-row>\n                <ion-col>\n                    <button ion-button menuToggle>\n                        <ion-icon name="menu"></ion-icon>\n                    </button>\n                </ion-col>\n                <ion-col>\n                    <strong  class="TxtTitlePage">Perguntas Frequentes</strong>\n                </ion-col>\n                <ion-col col-2>\n                    <ion-icon  class="IconPesquisa"   name="ios-search" (click)="goToPesquisa()"></ion-icon>\n                </ion-col>\n            </ion-row>\n        </ion-grid>\n    </ion-navbar>\n\n</ion-header>\n\n\n<ion-content>\n    <ion-list inset>\n\n        <span *ngFor=\'let faq of faqs\'>\n            <p><span style=\'font-family:Roboto; font-size:18px\'>\n                    <img class="ImgFaq" src={{faq.AF0030Photo}}>\n                    &nbsp;{{faq.AF0030Pergunta}}</span></p>\n            <p><span style=\'font-family:Roboto; font-size:12px; text-align:justify\'>\n                {{faq.AF0030Resposta}}. {{faq.AF0030Chamada}} \n                <a href=\'{{faq.AF0030Url}}\' target="_new">LINK</a></span></p>\n        </span>\n        <hr/>\n    </ion-list>\n</ion-content>'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/faq/faq.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_4__providers_util_loading_service__["a" /* LoadingService */], __WEBPACK_IMPORTED_MODULE_2__providers_rest_rest__["a" /* RestProvider */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */]])
    ], FaqPage);
    return FaqPage;
}());

//# sourceMappingURL=faq.js.map

/***/ })

});
//# sourceMappingURL=0.js.map