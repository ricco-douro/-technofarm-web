webpackJsonp([9],{

/***/ 398:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SinglecursoPageModule", function() { return SinglecursoPageModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__singlecurso__ = __webpack_require__(416);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};



var SinglecursoPageModule = /** @class */ (function () {
    function SinglecursoPageModule() {
    }
    SinglecursoPageModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["I" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_2__singlecurso__["a" /* SinglecursoPage */],
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["e" /* IonicPageModule */].forChild(__WEBPACK_IMPORTED_MODULE_2__singlecurso__["a" /* SinglecursoPage */]),
            ],
        })
    ], SinglecursoPageModule);
    return SinglecursoPageModule;
}());

//# sourceMappingURL=singlecurso.module.js.map

/***/ }),

/***/ 416:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return SinglecursoPage; });
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
 * Generated class for the SinglecursoPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */
var SinglecursoPage = /** @class */ (function () {
    function SinglecursoPage(navCtrl, navParams) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.curso = this.navParams.get('singleCursos');
    }
    SinglecursoPage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad SinglecursoPage');
    };
    SinglecursoPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-singlecurso',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/cursos/singlecurso/singlecurso.html"*/'<ion-header>\n\n    <ion-navbar>\n        <ion-title text-center>Curso</ion-title>\n    </ion-navbar>\n\n</ion-header>\n\n\n<ion-content padding>\n    <ion-grid>\n        <ion-row>\n            <ion-col>\n                <h2 text-center><strong>{{curso.AD0013TurmaNome}}</strong></h2>\n                <ion-icon name="ios-home-outline"></ion-icon> <span>Unidade: {{curso.AD0010UnidOrganicaNome}}</span>\n                <br/>\n                <ion-icon name="ios-calendar-outline"></ion-icon> <span>Data: {{curso.AD0013DataInicio | date:\'dd/MM/yyyy\'}} à {{curso.AD0013DataFim | date:\'dd/MM/yyyy\'}}</span>\n                <br/>\n                <ion-icon name="md-calendar"></ion-icon> <span>Dias da Semana: {{curso.AD0017FrequenciaDescricao}}</span>\n                <br/>\n                <ion-icon name="md-timer"></ion-icon> <span>Horário:</span>\n                <br/>\n                <ion-icon name="ios-cash-outline"></ion-icon> <span>Investimento:</span>\n\n            </ion-col>\n\n        </ion-row>\n        <hr/>\n        <ion-row>\n            <ion-col>\n                <strong class="TxtSenacLaranja">Objetivo:</strong>\n                <p>Texto objetivos</p>\n                <strong class="TxtSenacLaranja">Programa:</strong>\n                <p>Texto programas</p>\n                <strong class="TxtSenacLaranja">Público:</strong>\n                <p>Texto público</p>\n                <strong class="TxtSenacLaranja">Requisitos de Acesso:</strong>\n                <p>Texto requisitos de acesso</p>\n                <strong class="TxtSenacLaranja">Material Incluso:</strong>\n                <p>Texto material incluso</p>\n                <strong class="TxtSenacLaranja">Informações Importantes:</strong>\n                <p>Texto informações importantes</p>\n                <strong class="TxtSenacLaranja">Carga horária:</strong>\n                <p>Texto carga horária</p>\n\n            </ion-col>                   \n        </ion-row>\n    </ion-grid>\n</ion-content>\n'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/cursos/singlecurso/singlecurso.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */]])
    ], SinglecursoPage);
    return SinglecursoPage;
}());

//# sourceMappingURL=singlecurso.js.map

/***/ })

});
//# sourceMappingURL=9.js.map