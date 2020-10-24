webpackJsonp([7],{

/***/ 404:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SinglenoticiaPageModule", function() { return SinglenoticiaPageModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__singlenoticia__ = __webpack_require__(419);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};



var SinglenoticiaPageModule = /** @class */ (function () {
    function SinglenoticiaPageModule() {
    }
    SinglenoticiaPageModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["I" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_2__singlenoticia__["a" /* SinglenoticiaPage */],
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["e" /* IonicPageModule */].forChild(__WEBPACK_IMPORTED_MODULE_2__singlenoticia__["a" /* SinglenoticiaPage */]),
            ],
        })
    ], SinglenoticiaPageModule);
    return SinglenoticiaPageModule;
}());

//# sourceMappingURL=singlenoticia.module.js.map

/***/ }),

/***/ 419:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return SinglenoticiaPage; });
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


var SinglenoticiaPage = /** @class */ (function () {
    function SinglenoticiaPage(navCtrl, navParams) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.noticia = this.navParams.get('noticia');
        this.txt = this.noticia.AD0031TextoNoticia;
        this.txtnoticia = this.txt.replace(/<.*?>/g, '');
    }
    SinglenoticiaPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad SinglenoticiaPage');
    };
    SinglenoticiaPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-singlenoticia',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/noticia/singlenoticia/singlenoticia.html"*/'<!--\n  Generated template for the SinglenoticiaPage page.\n\n  See http://ionicframework.com/docs/components/#navigation for more info on\n  Ionic pages and navigation.\n-->\n<ion-header>\n\n  <ion-navbar>\n    <ion-title text-center>Notícia</ion-title>\n  </ion-navbar>\n\n</ion-header>\n\n\n<ion-content class="cards-bg">\n    <ion-card>\n\n        <img class="ImgNoticia" src={{noticia.AD0031ImagemNoticia}}><img>\n        <p></p>\n        <ion-card-content>\n            <h2 class="titleNoticia" text-left>\n                {{noticia.AD0031TituloNoticia}}\n            </h2>\n            <p text-left class="textoNoticia">\n                {{noticia.AD0031DescricaoNoticia}}\n            </p>\n            <hr/>\n            <p>\n                {{txtnoticia}}\n            </p>\n\n        </ion-card-content>\n    </ion-card>\n</ion-content>\n'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/noticia/singlenoticia/singlenoticia.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */]])
    ], SinglenoticiaPage);
    return SinglenoticiaPage;
}());

//# sourceMappingURL=singlenoticia.js.map

/***/ })

});
//# sourceMappingURL=7.js.map