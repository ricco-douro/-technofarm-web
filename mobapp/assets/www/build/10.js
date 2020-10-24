webpackJsonp([10],{

/***/ 397:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "PesquisacursoPageModule", function() { return PesquisacursoPageModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__pesquisacurso__ = __webpack_require__(415);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};



var PesquisacursoPageModule = /** @class */ (function () {
    function PesquisacursoPageModule() {
    }
    PesquisacursoPageModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["I" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_2__pesquisacurso__["a" /* PesquisacursoPage */],
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["e" /* IonicPageModule */].forChild(__WEBPACK_IMPORTED_MODULE_2__pesquisacurso__["a" /* PesquisacursoPage */]),
            ],
        })
    ], PesquisacursoPageModule);
    return PesquisacursoPageModule;
}());

//# sourceMappingURL=pesquisacurso.module.js.map

/***/ }),

/***/ 415:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return PesquisacursoPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_rest_rest__ = __webpack_require__(75);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_rxjs_add_operator_map__ = __webpack_require__(76);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_rxjs_add_operator_map___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_rxjs_add_operator_map__);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var PesquisacursoPage = /** @class */ (function () {
    function PesquisacursoPage(navCtrl, navParams, restProvider) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.restProvider = restProvider;
        this.searchTerm = '';
        this.getCursos();
    }
    PesquisacursoPage.prototype.getCursos = function () {
        var _this = this;
        this.restProvider.getRest('{"operacao":"getcursos"}')
            .then(function (data) {
            console.log(data);
            _this.cursos = data;
        });
    };
    PesquisacursoPage.prototype.filterItems = function (searchTerm) {
        return this.cursos.filter(function (item) {
            return item.AD0013TurmaNome.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1;
        });
    };
    PesquisacursoPage.prototype.setFilteredItems = function () {
        this.items = this.filterItems(this.searchTerm);
    };
    PesquisacursoPage.prototype.goToSingleCurso = function (i) {
        console.log(this.cursos[i].FK0011ProgramacaoCurso);
        console.log(this.cursos[i]);
        this.navCtrl.push('SinglecursoPage', { singleCursos: this.cursos[i] });
    };
    PesquisacursoPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-pesquisacurso',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/cursos/pesquisacurso/pesquisacurso.html"*/'<!--\n  Generated template for the CursoPage page.\n\n  See http://ionicframework.com/docs/components/#navigation for more info on\n  Ionic pages and navigation.\n-->\n<ion-header>\n    <ion-searchbar [(ngModel)]="searchTerm" (ionInput)="setFilteredItems()"></ion-searchbar>\n</ion-header>\n\n\n<ion-content padding>\n\n    <ion-list>\n        <button ion-item *ngFor="let curso of items; let i = index" [attr.data-index]="i" (click)="goToSingleCurso(i)">\n            <p>\n                <strong class="TxtCurso">{{curso.AD0013TurmaNome}}</strong><br/>\n                <strong class="TxtCurso">{{curso.AD0010UnidOrganicaNome}}</strong><br/>\n                <strong class="TxtCursoSituacao">{{curso.AD0018TurmaSituacaoDescricao}} <ion-icon name="arrow-round-forward"></ion-icon> {{curso.AD0013DataInicio | date:\'dd/MM/yyyy\'}} à {{curso.AD0013DataFim | date:\'dd/MM/yyyy\'}}</strong>\n\n            </p>\n        </button>\n    </ion-list>\n</ion-content>\n'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/cursos/pesquisacurso/pesquisacurso.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */], __WEBPACK_IMPORTED_MODULE_2__providers_rest_rest__["a" /* RestProvider */]])
    ], PesquisacursoPage);
    return PesquisacursoPage;
}());

//# sourceMappingURL=pesquisacurso.js.map

/***/ })

});
//# sourceMappingURL=10.js.map