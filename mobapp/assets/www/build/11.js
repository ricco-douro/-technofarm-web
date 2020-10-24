webpackJsonp([11],{

/***/ 396:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ListacursosPageModule", function() { return ListacursosPageModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__listacursos__ = __webpack_require__(414);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};



var ListacursosPageModule = /** @class */ (function () {
    function ListacursosPageModule() {
    }
    ListacursosPageModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["I" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_2__listacursos__["a" /* ListacursosPage */],
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["e" /* IonicPageModule */].forChild(__WEBPACK_IMPORTED_MODULE_2__listacursos__["a" /* ListacursosPage */]),
            ],
        })
    ], ListacursosPageModule);
    return ListacursosPageModule;
}());

//# sourceMappingURL=listacursos.module.js.map

/***/ }),

/***/ 414:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ListacursosPage; });
/* unused harmony export snapshotToArray */
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




var ListacursosPage = /** @class */ (function () {
    function ListacursosPage(navCtrl, platform, restProvider) {
        this.navCtrl = navCtrl;
        this.restProvider = restProvider;
        this.pet = "puppies";
        this.isAndroid = false;
        this.searchTerm = '';
        this.isAndroid = platform.is('android');
        this.getCursos();
    }
    ListacursosPage.prototype.goToSingleCurso = function (i) {
        console.log(this.cursos[i].FK0011ProgramacaoCurso);
        console.log(this.cursos[i]);
        this.navCtrl.push('SinglecursoPage', { singleCursos: this.cursos[i] });
    };
    ListacursosPage.prototype.getCursos = function () {
        var _this = this;
        this.restProvider.getRest('{"operacao":"getcursos"}')
            .then(function (data) {
            console.log(data);
            _this.cursos = data;
        });
    };
    ListacursosPage.prototype.goToPesquisa = function () {
        this.navCtrl.push('PesquisacursoPage');
    };
    ListacursosPage.prototype.filterItems = function (searchTerm) {
        return this.cursos.filter(function (item) {
            return item.AD0013TurmaNome.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1;
        });
    };
    ListacursosPage.prototype.setFilteredItems = function () {
        this.items = this.filterItems(this.searchTerm);
    };
    ListacursosPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-listacursos',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/cursos/listacursos/listacursos.html"*/'\n<ion-header>\n    <ion-navbar>       \n        <ion-grid>\n            <ion-row>\n                <ion-col>\n                    <button ion-button menuToggle>\n                        <ion-icon name="menu"></ion-icon>\n                    </button>\n                </ion-col>\n                <ion-col>\n                    <strong class="TxtTitlePage">Cursos</strong>\n                </ion-col>\n                <ion-col col-2>\n                    <ion-icon  class="IconPesquisa"   name="ios-search" (click)="goToPesquisa()"></ion-icon>\n                </ion-col>\n            </ion-row>\n        </ion-grid>\n    </ion-navbar>  \n    <hr/>\n    <ion-toolbar no-border-top>\n        <ion-segment [(ngModel)]="pet">\n            <ion-segment-button class="TxtWhite" value="puppies">\n                CURSOS\n            </ion-segment-button>\n            <ion-segment-button class="TxtWhite" value="kittens">\n                CURSOS TÉCNICOS\n            </ion-segment-button>      \n        </ion-segment>\n    </ion-toolbar>\n</ion-header>\n\n<ion-content>\n    <div [ngSwitch]="pet">\n        <ion-list *ngSwitchCase="\'puppies\'">\n            <button ion-item *ngFor="let curso of cursos; let i = index" [attr.data-index]="i" (click)="goToSingleCurso(i)">\n                    <p>\n                    <strong class="TxtCurso">{{curso.AD0013TurmaNome}}</strong><br/>\n                    <strong class="TxtCurso">{{curso.AD0010UnidOrganicaNome}}</strong><br/>\n                    <strong class="TxtCursoSituacao">{{curso.AD0018TurmaSituacaoDescricao}} <ion-icon name="arrow-round-forward"></ion-icon> {{curso.AD0013DataInicio | date:\'dd/MM/yyyy\'}} à {{curso.AD0013DataFim | date:\'dd/MM/yyyy\'}}</strong>\n\n                </p>\n            </button>\n        </ion-list>        \n        <ion-list *ngSwitchCase="\'kittens\'">\n            <button ion-item *ngFor="let curso of cursos; let i = index" [attr.data-index]="i" (click)="goToSingleCurso(i)" >\n                    <p>\n                    <strong class="TxtCurso">{{curso.AD0013TurmaNome}}</strong><br/>\n                    <strong class="TxtCurso">{{curso.AD0010UnidOrganicaNome}}</strong><br/>\n                    <strong class="TxtCursoSituacao">{{curso.AD0018TurmaSituacaoDescricao}} <ion-icon name="arrow-round-forward"></ion-icon> {{curso.AD0013DataInicio | date:\'dd/MM/yyyy\'}} à {{curso.AD0013DataFim | date:\'dd/MM/yyyy\'}}</strong>\n\n                </p>\n            </button>\n        </ion-list>\n    </div>\n</ion-content>\n\n\n\n\n'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/cursos/listacursos/listacursos.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["j" /* Platform */],
            __WEBPACK_IMPORTED_MODULE_2__providers_rest_rest__["a" /* RestProvider */]])
    ], ListacursosPage);
    return ListacursosPage;
}());

var snapshotToArray = function (snapshot) {
    var returnArr = [];
    snapshot.forEach(function (childSnapshot) {
        var item = childSnapshot.val();
        item.key = childSnapshot.key;
        returnArr.push(item);
    });
    return returnArr;
};
//# sourceMappingURL=listacursos.js.map

/***/ })

});
//# sourceMappingURL=11.js.map