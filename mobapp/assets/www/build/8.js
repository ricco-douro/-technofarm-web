webpackJsonp([8],{

/***/ 399:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "FaleConoscoPageModule", function() { return FaleConoscoPageModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__fale_conosco__ = __webpack_require__(417);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};



var FaleConoscoPageModule = /** @class */ (function () {
    function FaleConoscoPageModule() {
    }
    FaleConoscoPageModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["I" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_2__fale_conosco__["a" /* FaleConoscoPage */],
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["e" /* IonicPageModule */].forChild(__WEBPACK_IMPORTED_MODULE_2__fale_conosco__["a" /* FaleConoscoPage */]),
            ],
        })
    ], FaleConoscoPageModule);
    return FaleConoscoPageModule;
}());

//# sourceMappingURL=fale-conosco.module.js.map

/***/ }),

/***/ 417:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return FaleConoscoPage; });
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


/**
 * Generated class for the FaleConoscoPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */
var FaleConoscoPage = /** @class */ (function () {
    function FaleConoscoPage(navCtrl, navParams) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
    }
    FaleConoscoPage.prototype.goToPesquisa = function () {
        this.navCtrl.push('PesquisacursoPage');
    };
    FaleConoscoPage.prototype.goToPolos = function () {
        this.navCtrl.push('ListaPolosPage');
    };
    FaleConoscoPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad FaleConoscoPage');
    };
    FaleConoscoPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-fale-conosco',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/fale-conosco/fale-conosco.html"*/'<ion-header>\n    <ion-navbar>      \n        <ion-grid>\n            <ion-row>\n                <ion-col>\n                    <button ion-button menuToggle>\n                        <ion-icon name="menu"></ion-icon>\n                    </button>\n                </ion-col>\n                <ion-col>\n                    <strong class="TxtTitlePage" style="font-family=\'Sanchez\'">Fale Conosco</strong>\n                </ion-col>\n                <ion-col col-2>\n                    <ion-icon  class="IconPesquisa"   name="ios-search" (click)="goToPesquisa()"></ion-icon>\n                </ion-col>\n            </ion-row>\n        </ion-grid>\n    </ion-navbar>\n</ion-header>\n\n\n<ion-content padding>\n    <ion-grid>\n        <ion-row>\n            <ion-col>\n                <p><ion-icon class="TxtSenacAzul" name="call"></ion-icon><strong> Telefone: (65)3614-2481</strong></p>\n                <hr>\n                <p><ion-icon class="TxtSenacAzul" name="mail"></ion-icon><strong> E-mail: atendimento@mt.senac.br</strong></p>\n                 <hr>\n                 <p (click)="goToPolos()"><ion-icon class="TxtSenacAzul" name="pin"></ion-icon><strong> Endereço: Veja nossas unidades</strong></p>\n                 <hr>\n                 <p><ion-icon name="logo-facebook"></ion-icon><strong> Facebook: /senacmt</strong></p>\n                 <hr>\n                 <p><ion-icon name="logo-instagram"></ion-icon><strong> Instagram: @senacmt</strong></p>\n                 <hr>\n                 <p><ion-icon name="logo-twitter"></ion-icon><strong> Twitter: #senacmt</strong></p>\n            \n                 <hr>\n                 <p text-center><strong>Horário de Atendimento</strong></p>\n                 <p text-center>\n                     <span>Segunda à Sexta das 07h00 às 22h00</span>\n                     <span>Sábado das 08h00 às 12h00</span>\n                 </p>\n            </ion-col>            \n        </ion-row>\n    </ion-grid>\n</ion-content>\n'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/fale-conosco/fale-conosco.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */]])
    ], FaleConoscoPage);
    return FaleConoscoPage;
}());

//# sourceMappingURL=fale-conosco.js.map

/***/ })

});
//# sourceMappingURL=8.js.map