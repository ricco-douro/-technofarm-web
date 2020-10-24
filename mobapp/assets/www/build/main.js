webpackJsonp([17],{

/***/ 145:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return LoadingService; });
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


var LoadingService = /** @class */ (function () {
    function LoadingService(loadingCtrl) {
        this.loadingCtrl = loadingCtrl;
    }
    LoadingService.prototype.present = function () {
        this.loading = this.loadingCtrl.create({});
        return this.loading.present();
    };
    LoadingService.prototype.presentWithMessage = function (message) {
        this.loading = this.loadingCtrl.create({
            content: message
        });
        return this.loading.present();
    };
    LoadingService.prototype.dismiss = function () {
        var _this = this;
        return new Promise(function (resolve, reject) {
            if (_this.loading) {
                return _this.loading.dismiss(resolve(true)).catch(function (error) {
                    console.log('loading error: ', error);
                });
            }
            else {
                resolve(true);
            }
        });
    };
    LoadingService = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["A" /* Injectable */])(),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["f" /* LoadingController */]])
    ], LoadingService);
    return LoadingService;
}());

//# sourceMappingURL=loading.service.js.map

/***/ }),

/***/ 156:
/***/ (function(module, exports) {

function webpackEmptyAsyncContext(req) {
	// Here Promise.resolve().then() is used instead of new Promise() to prevent
	// uncatched exception popping up in devtools
	return Promise.resolve().then(function() {
		throw new Error("Cannot find module '" + req + "'.");
	});
}
webpackEmptyAsyncContext.keys = function() { return []; };
webpackEmptyAsyncContext.resolve = webpackEmptyAsyncContext;
module.exports = webpackEmptyAsyncContext;
webpackEmptyAsyncContext.id = 156;

/***/ }),

/***/ 197:
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"../pages/_chat/room/room.module": [
		394,
		1
	],
	"../pages/browser/browser.module": [
		395,
		12
	],
	"../pages/cursos/listacursos/listacursos.module": [
		396,
		11
	],
	"../pages/cursos/pesquisacurso/pesquisacurso.module": [
		397,
		10
	],
	"../pages/cursos/singlecurso/singlecurso.module": [
		398,
		9
	],
	"../pages/fale-conosco/fale-conosco.module": [
		399,
		8
	],
	"../pages/faq/faq.module": [
		400,
		0
	],
	"../pages/login/login.module": [
		401,
		16
	],
	"../pages/menu/menu.module": [
		402,
		15
	],
	"../pages/noticia/listanoticias/listanoticias.module": [
		403,
		14
	],
	"../pages/noticia/singlenoticia/singlenoticia.module": [
		404,
		7
	],
	"../pages/polos/listapolos/listapolos.module": [
		405,
		6
	],
	"../pages/polos/singlepolo/singlepolo.module": [
		406,
		5
	],
	"../pages/signup/signup.module": [
		407,
		13
	],
	"../pages/singleup/singleup.module": [
		408,
		4
	],
	"../pages/tabs/tabs.module": [
		409,
		3
	],
	"../pages/unidprod/unidprod.module": [
		410,
		2
	]
};
function webpackAsyncContext(req) {
	var ids = map[req];
	if(!ids)
		return Promise.reject(new Error("Cannot find module '" + req + "'."));
	return __webpack_require__.e(ids[1]).then(function() {
		return __webpack_require__(ids[0]);
	});
};
webpackAsyncContext.keys = function webpackAsyncContextKeys() {
	return Object.keys(map);
};
webpackAsyncContext.id = 197;
module.exports = webpackAsyncContext;

/***/ }),

/***/ 255:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return HomePage; });
/* unused harmony export snapshotToArray */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_firebase__ = __webpack_require__(198);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_firebase___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_firebase__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__providers_util_singleton_service__ = __webpack_require__(59);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__noticia_listanoticias_listanoticias__ = __webpack_require__(257);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};


//import { RoomPage } from '../room/room';

//import { FirebaseProvider } from '../../../providers/firebase/firebase'


var HomePage = /** @class */ (function () {
    function HomePage(singleton, navCtrl, navParams) {
        var _this = this;
        this.singleton = singleton;
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.data = { type: '', nickname: '', message: '' };
        this.chats = [];
        this.offStatus = false;
        this.message = '';
        this.admin = 1;
        this.roomkey = this.navParams.get("key");
        this.nickname = this.singleton.loginname;
        this.email = this.singleton.loginemail;
        this.type = 'message';
        this.entrarnasala(this.roomkey, this.type, this.singleton.loginname, '', this.singleton.loginemail);
        this.message = '';
        this.data.message = '';
        __WEBPACK_IMPORTED_MODULE_2_firebase__["database"]().ref('chatrooms/' + this.roomkey + '/chats').on('value', function (resp) {
            _this.chats = [];
            _this.chats = snapshotToArray(resp);
            setTimeout(function () {
                if (_this.offStatus === false) {
                    _this.content.scrollToBottom(300);
                }
            }, 100);
        });
    }
    HomePage.prototype.sendMessage = function () {
        this.enviarmensagem(this.roomkey, this.type, this.singleton.loginname, this.data.message, this.singleton.loginemail);
        this.data.message = '';
    };
    HomePage.prototype.exitChat = function () {
        this.sairdasala(this.roomkey, this.data.type, this.nickname, this.data.message, this.singleton.loginemail);
        this.offStatus = true;
        this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_4__noticia_listanoticias_listanoticias__["a" /* ListanoticiasPage */], {
            nickname: this.singleton.loginname
        });
    };
    HomePage.prototype.enviarmensagem = function (myroomkey, mytype, mynickname, mymessage, myemail) {
        __WEBPACK_IMPORTED_MODULE_2_firebase__["database"]().ref('chatrooms/' + myroomkey + '/chats').push({
            type: mytype,
            user: this.singleton.loginname,
            message: mymessage,
            email: this.singleton.loginemail,
            sendDate: Date(),
            admin: "0"
        });
    };
    HomePage.prototype.sairdasala = function (myroomkey, mytype, mynickname, mymessage, myemail) {
        __WEBPACK_IMPORTED_MODULE_2_firebase__["database"]().ref('chatrooms/' + myroomkey + '/chats').push({
            type: 'exit',
            user: this.singleton.loginname,
            message: this.singleton.loginname + ' saiu da sala.',
            email: this.singleton.loginemail,
            sendDate: Date(),
            admin: "0"
        });
    };
    HomePage.prototype.entrarnasala = function (myroomkey, mytype, mynickname, mymessage, myemail) {
        console.log('entrar na sala');
        console.log(this.singleton);
        __WEBPACK_IMPORTED_MODULE_2_firebase__["database"]().ref('chatrooms/' + myroomkey + '/chats').push({
            type: 'join',
            user: this.singleton.loginname,
            message: this.singleton.loginname + ' entrou na sala.',
            email: this.singleton.loginemail,
            sendDate: Date(),
            admin: "0"
        });
    };
    HomePage.prototype.mymessages = function (email) {
        return email == this.singleton.loginemail;
    };
    __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["_8" /* ViewChild */])(__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["a" /* Content */]),
        __metadata("design:type", __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["a" /* Content */])
    ], HomePage.prototype, "content", void 0);
    HomePage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-home',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/_chat/home/home.html"*/'<ion-header>\n\n  <ion-navbar>\n\n    <ion-title>\n\n      Bate Papo\n\n    </ion-title>\n\n    <ion-buttons end>\n\n      <button ion-button icon-only (click)="exitChat()">\n\n        <ion-icon name="exit" style="color:#ffffff;"></ion-icon>\n\n      </button>\n\n    </ion-buttons>\n\n  </ion-navbar>\n\n</ion-header>\n\n\n\n<ion-content>\n\n  <ion-list>\n\n    <ion-item *ngFor="let chat of chats" no-lines>\n\n      <div class="chat-status" text-center *ngIf="(chat.type===\'join\' && chat.email === email)\n\n      ||(chat.type===\'exit\'&& chat.email === email);else message">\n\n        <span class="chat-date">{{chat.sendDate | date:\'dd/MM/yyyy\'}}</span>\n\n        <span class="chat-content-center">{{chat.message}}</span>\n\n      </div>\n\n      <ng-template #message>\n\n        <div class="chat-message" text-right *ngIf="chat.admin === \'0\' && chat.email === email">\n\n          <div class="right-bubble">\n\n            <span class="msg-name">Eu:</span>\n\n            <span class="msg-date">{{chat.sendDate |date:\'dd/MM/yyyy H:M\'}}</span>\n\n            <p text-wrap>{{chat.message}}</p>\n\n          </div>\n\n        </div>\n\n        <div class="chat-message" text-right *ngIf="chat.admin === \'1\' && chat.email === email">\n\n          <div class="left-bubble">\n\n            <span class="msg-name">Gerente do CEP</span>\n\n            <span class="msg-date">{{chat.sendDate | date:\'dd/MM/yyyy H:M\'}}</span>\n\n            <p text-wrap>{{chat.message}}</p>\n\n          </div>\n\n        </div>\n\n      </ng-template>\n\n    </ion-item>\n\n  </ion-list>\n\n  <br/><br/><br/><br/><br/><br/><br/>\n\n</ion-content>\n\n\n\n<ion-footer>\n\n  <ion-grid>\n\n    <ion-row>\n\n      <ion-col col-10>\n\n        <ion-input type="text" placeholder="Digite uma mensagem" [(ngModel)]="data.message" name="message"></ion-input>\n\n      </ion-col>\n\n      <ion-col col-2 (click)="sendMessage()">\n\n        <ion-icon name="paper-plane"></ion-icon>\n\n      </ion-col>\n\n    </ion-row>\n\n  </ion-grid>\n\n</ion-footer>'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/_chat/home/home.html"*/
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_3__providers_util_singleton_service__["a" /* SingletonService */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */]])
    ], HomePage);
    return HomePage;
}());

var snapshotToArray = function (snapshot) {
    var returnArr = [];
    snapshot.forEach(function (childSnapshot) {
        var item = childSnapshot.val();
        item.key = childSnapshot.key;
        returnArr.push(item);
    });
    console.log(returnArr);
    return returnArr;
    //.filter(this.mymessages);
};
//# sourceMappingURL=home.js.map

/***/ }),

/***/ 257:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ListanoticiasPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_rest_rest__ = __webpack_require__(75);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_rxjs_add_operator_map__ = __webpack_require__(76);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_rxjs_add_operator_map___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_rxjs_add_operator_map__);
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





var ListanoticiasPage = /** @class */ (function () {
    function ListanoticiasPage(navCtrl, loader, navParams, restProvider) {
        var _this = this;
        this.navCtrl = navCtrl;
        this.loader = loader;
        this.navParams = navParams;
        this.restProvider = restProvider;
        this.searchTerm = '';
        this.loader.presentWithMessage("Por favor, aguarde");
        this.loader.dismiss().then(function () {
            _this.getNoticias();
        });
    }
    ListanoticiasPage.prototype.getNoticias = function () {
        var _this = this;
        this.restProvider.getRest('{"operacao":"getnoticias"}')
            .then(function (data) {
            console.log(data);
            _this.noticias = data;
        });
    };
    ListanoticiasPage.prototype.goToNoticia = function (i) {
        console.log(i);
        console.log(this.noticias[i].AD0031TituloNoticia);
        this.navCtrl.push('SinglenoticiaPage', { noticia: this.noticias[i] });
    };
    ListanoticiasPage.prototype.filterItems = function (searchTerm) {
        return this.noticias.filter(function (item) {
            return item.AD0031TituloNoticia.toLowerCase().indexOf(searchTerm.toLowerCase()) > -1;
        });
    };
    ListanoticiasPage.prototype.setFilteredItems = function () {
        this.noticias = this.filterItems(this.searchTerm);
    };
    ListanoticiasPage.prototype.goToPesquisa = function () {
        this.navCtrl.push('PesquisacursoPage');
    };
    ListanoticiasPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-listanoticias',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/noticia/listanoticias/listanoticias.html"*/'<ion-header>   \n    <ion-navbar>        \n         <ion-grid>\n            <ion-row>\n                <ion-col>\n                    <button ion-button menuToggle>\n                        <ion-icon name="menu"></ion-icon>\n                    </button>\n                </ion-col>\n                <ion-col>\n                    <img id="logo" src="../assets/imgs/senac-logo-branco.png" style="width:40px;"/>\n                </ion-col>\n                <ion-col col-2>\n                    <ion-icon  class="IconPesquisa"  name="ios-search" (click)="goToPesquisa()"></ion-icon>\n                </ion-col>\n            </ion-row>\n        </ion-grid>\n\n\n    </ion-navbar>  \n\n    <ion-searchbar [(ngModel)]="searchTerm" (ionInput)="setFilteredItems()"></ion-searchbar>\n\n</ion-header>\n<ion-content class="cards-bg">\n    <ion-card *ngFor="let noticia of noticias;let i = index" [attr.data-index]="i">\n\n        <img class="ImgNoticia" src={{noticia.AD0031ImagemNoticia}}><img>\n        <!--<img src={{polo.AD0010UOFoto}}/>-->\n        <p></p>\n        <ion-card-content>\n            <h2 class="titleNoticia" text-left>\n                {{noticia.AD0031TituloNoticia}}\n            </h2>\n            <p text-left class="textoNoticia">\n                {{noticia.AD0031DescricaoNoticia}}\n            </p>\n\n        </ion-card-content>\n\n        <ion-row no-padding>      \n\n            <ion-col text-right>\n                <button class="NoticiaLeiaMais TxtSenacLaranja" clear icon-start (click)="goToNoticia(i)">                  \n                        Leia mais...\n            </button>            \n        </ion-col>\n    </ion-row>\n\n</ion-card>\n</ion-content>\n'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/noticia/listanoticias/listanoticias.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_4__providers_util_loading_service__["a" /* LoadingService */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */], __WEBPACK_IMPORTED_MODULE_2__providers_rest_rest__["a" /* RestProvider */]])
    ], ListanoticiasPage);
    return ListanoticiasPage;
}());

//# sourceMappingURL=listanoticias.js.map

/***/ }),

/***/ 258:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_platform_browser_dynamic__ = __webpack_require__(259);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__app_module__ = __webpack_require__(279);


Object(__WEBPACK_IMPORTED_MODULE_0__angular_platform_browser_dynamic__["a" /* platformBrowserDynamic */])().bootstrapModule(__WEBPACK_IMPORTED_MODULE_1__app_module__["a" /* AppModule */]);
//# sourceMappingURL=main.js.map

/***/ }),

/***/ 279:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export firebaseConfig */
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return AppModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_platform_browser__ = __webpack_require__(32);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__ionic_native_splash_screen__ = __webpack_require__(251);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__ionic_native_status_bar__ = __webpack_require__(252);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__angular_common_http__ = __webpack_require__(209);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__providers_util_singleton_service__ = __webpack_require__(59);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__ionic_native_in_app_browser__ = __webpack_require__(385);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__ionic_native_geolocation__ = __webpack_require__(254);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__ionic_native_call_number__ = __webpack_require__(253);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_10__ionic_storage__ = __webpack_require__(122);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_11_firebase__ = __webpack_require__(198);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_11_firebase___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_11_firebase__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_12__app_component__ = __webpack_require__(386);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_13__pages_menu_menu__ = __webpack_require__(387);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_14__pages_welcome_welcome__ = __webpack_require__(388);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_15__pages_chat_home_home__ = __webpack_require__(255);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_16__pages_signup_signup__ = __webpack_require__(389);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_17__pages_login_login__ = __webpack_require__(77);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_18__providers_rest_rest__ = __webpack_require__(75);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_19__providers_util_loading_service__ = __webpack_require__(145);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_20__providers_database_database__ = __webpack_require__(391);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};





//import { HttpModule, JsonpModule } from '@angular/http';





//import { Storage } from '@ionic/storage'

//import { AngularFireModule } from 'angularfire2';
//import { AngularFireDatabaseModule, AngularFireDatabase } from 'angularfire2/database';
//import { AngularFireAuthModule } from 'angularfire2/auth';



//import { RoomPage } from '../pages/_chat/room/room';




//import { BrowserPage } from '../pages/browser/browser'
//import { LoginPage } from '../pages/login/login'
//import { ListanoticiasPage } from '../pages/noticia/listanoticias/listanoticias'

//import { FirebaseProvider } from '../providers/firebase/firebase';


var firebaseConfig = {
    apiKey: "AIzaSyBrMXA8YnwRcFMnmbhQrJNb6EbWx0aqYoE",
    authDomain: "chat-app-senac.firebaseapp.com",
    databaseURL: "https://chat-app-senac.firebaseio.com",
    projectId: "chat-app-senac",
    storageBucket: "chat-app-senac.appspot.com"
    /*,
    messagingSenderId: "639213700470"
    */
};
__WEBPACK_IMPORTED_MODULE_11_firebase__["initializeApp"](firebaseConfig);
var AppModule = /** @class */ (function () {
    function AppModule() {
    }
    AppModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_1__angular_core__["I" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_12__app_component__["a" /* MyApp */],
                __WEBPACK_IMPORTED_MODULE_13__pages_menu_menu__["a" /* MenuPage */],
                __WEBPACK_IMPORTED_MODULE_15__pages_chat_home_home__["a" /* HomePage */],
                __WEBPACK_IMPORTED_MODULE_16__pages_signup_signup__["a" /* SignupPage */],
                __WEBPACK_IMPORTED_MODULE_14__pages_welcome_welcome__["a" /* WelcomePage */],
                //BrowserPage,
                __WEBPACK_IMPORTED_MODULE_17__pages_login_login__["a" /* LoginPage */]
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_0__angular_platform_browser__["a" /* BrowserModule */],
                __WEBPACK_IMPORTED_MODULE_5__angular_common_http__["b" /* HttpClientModule */],
                __WEBPACK_IMPORTED_MODULE_2_ionic_angular__["d" /* IonicModule */].forRoot(__WEBPACK_IMPORTED_MODULE_12__app_component__["a" /* MyApp */], {}, {
                    links: [
                        { loadChildren: '../pages/_chat/room/room.module#RoomPageModule', name: 'RoomPage', segment: 'room', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/browser/browser.module#BrowserPageModule', name: 'BrowserPage', segment: 'browser', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/cursos/listacursos/listacursos.module#ListacursosPageModule', name: 'ListacursosPage', segment: 'listacursos', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/cursos/pesquisacurso/pesquisacurso.module#PesquisacursoPageModule', name: 'PesquisacursoPage', segment: 'pesquisacurso', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/cursos/singlecurso/singlecurso.module#SinglecursoPageModule', name: 'SinglecursoPage', segment: 'singlecurso', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/fale-conosco/fale-conosco.module#FaleConoscoPageModule', name: 'FaleConoscoPage', segment: 'fale-conosco', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/faq/faq.module#FaqPageModule', name: 'FaqPage', segment: 'faq', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/login/login.module#LoginPageModule', name: 'LoginPage', segment: 'login', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/menu/menu.module#MenuPageModule', name: 'MenuPage', segment: 'menu', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/noticia/listanoticias/listanoticias.module#ListanoticiasPageModule', name: 'ListanoticiasPage', segment: 'listanoticias', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/noticia/singlenoticia/singlenoticia.module#SinglenoticiaPageModule', name: 'SinglenoticiaPage', segment: 'singlenoticia', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/polos/listapolos/listapolos.module#ListaPolosPageModule', name: 'ListaPolosPage', segment: 'listapolos', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/polos/singlepolo/singlepolo.module#SinglepoloPageModule', name: 'SinglepoloPage', segment: 'singlepolo', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/signup/signup.module#SignupPageModule', name: 'SignupPage', segment: 'signup', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/singleup/singleup.module#SingleupPageModule', name: 'SingleupPage', segment: 'singleup', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/tabs/tabs.module#TabsPageModule', name: 'TabsPage', segment: 'tabs', priority: 'low', defaultHistory: [] },
                        { loadChildren: '../pages/unidprod/unidprod.module#UnidprodPageModule', name: 'UnidprodPage', segment: 'unidprod', priority: 'low', defaultHistory: [] }
                    ]
                }),
                __WEBPACK_IMPORTED_MODULE_10__ionic_storage__["a" /* IonicStorageModule */].forRoot()
            ],
            bootstrap: [__WEBPACK_IMPORTED_MODULE_2_ionic_angular__["b" /* IonicApp */]],
            entryComponents: [
                __WEBPACK_IMPORTED_MODULE_12__app_component__["a" /* MyApp */],
                __WEBPACK_IMPORTED_MODULE_13__pages_menu_menu__["a" /* MenuPage */],
                __WEBPACK_IMPORTED_MODULE_15__pages_chat_home_home__["a" /* HomePage */],
                //    RoomPage,
                __WEBPACK_IMPORTED_MODULE_16__pages_signup_signup__["a" /* SignupPage */],
                __WEBPACK_IMPORTED_MODULE_14__pages_welcome_welcome__["a" /* WelcomePage */],
                //BrowserPage,
                __WEBPACK_IMPORTED_MODULE_17__pages_login_login__["a" /* LoginPage */]
            ],
            providers: [
                __WEBPACK_IMPORTED_MODULE_4__ionic_native_status_bar__["a" /* StatusBar */],
                __WEBPACK_IMPORTED_MODULE_3__ionic_native_splash_screen__["a" /* SplashScreen */],
                { provide: __WEBPACK_IMPORTED_MODULE_1__angular_core__["u" /* ErrorHandler */], useClass: __WEBPACK_IMPORTED_MODULE_2_ionic_angular__["c" /* IonicErrorHandler */] },
                __WEBPACK_IMPORTED_MODULE_18__providers_rest_rest__["a" /* RestProvider */],
                __WEBPACK_IMPORTED_MODULE_6__providers_util_singleton_service__["a" /* SingletonService */],
                //FirebaseProvider,
                __WEBPACK_IMPORTED_MODULE_5__angular_common_http__["b" /* HttpClientModule */],
                __WEBPACK_IMPORTED_MODULE_7__ionic_native_in_app_browser__["a" /* InAppBrowser */],
                __WEBPACK_IMPORTED_MODULE_8__ionic_native_geolocation__["a" /* Geolocation */],
                __WEBPACK_IMPORTED_MODULE_9__ionic_native_call_number__["a" /* CallNumber */],
                __WEBPACK_IMPORTED_MODULE_19__providers_util_loading_service__["a" /* LoadingService */],
                __WEBPACK_IMPORTED_MODULE_20__providers_database_database__["a" /* DatabaseProvider */],
            ]
        })
    ], AppModule);
    return AppModule;
}());

//# sourceMappingURL=app.module.js.map

/***/ }),

/***/ 386:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return MyApp; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__ionic_native_status_bar__ = __webpack_require__(252);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__ionic_native_splash_screen__ = __webpack_require__(251);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__pages_login_login__ = __webpack_require__(77);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




//import { MenuPage } from '../pages/menu/menu';

//import { NgModule } from '@angular/core';
//import { IonicApp, IonicModule } from 'ionic-angular';
var MyApp = /** @class */ (function () {
    function MyApp(platform, statusBar, splashScreen) {
        var _this = this;
        this.rootPage = __WEBPACK_IMPORTED_MODULE_4__pages_login_login__["a" /* LoginPage */];
        platform.ready().then(function () {
            // used for an example of ngFor and navigation
            _this.pages = [
                { title: 'Login', component: __WEBPACK_IMPORTED_MODULE_4__pages_login_login__["a" /* LoginPage */] }
            ];
            statusBar.styleDefault();
            splashScreen.hide();
        });
    }
    __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["_8" /* ViewChild */])(__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["g" /* Nav */]),
        __metadata("design:type", __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["g" /* Nav */])
    ], MyApp.prototype, "nav", void 0);
    MyApp = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/app/app.html"*/'<ion-menu [content]="content">\n        <ion-header>\n          <ion-toolbar>\n            <img src="assets/svgs/technofarm-symbol.svg" style="width:80px;">\n            \n          </ion-toolbar>\n        </ion-header>\n      \n        <ion-content>\n          <ion-list>\n            <button menuClose ion-item *ngFor="let p of pages" (click)="openPage(p)">\n              {{p.title}}\n            </button>\n          </ion-list>\n        </ion-content>\n      \n      </ion-menu>\n      \n      <!-- Disable swipe-to-go-back because it\'s poor UX to combine STGB with side menus -->\n      <ion-nav [root]="rootPage" #content swipeBackEnabled="false"></ion-nav>'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/app/app.html"*/
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["j" /* Platform */], __WEBPACK_IMPORTED_MODULE_2__ionic_native_status_bar__["a" /* StatusBar */], __WEBPACK_IMPORTED_MODULE_3__ionic_native_splash_screen__["a" /* SplashScreen */]])
    ], MyApp);
    return MyApp;
}());

//# sourceMappingURL=app.component.js.map

/***/ }),

/***/ 387:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return MenuPage; });
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


var MenuPage = /** @class */ (function () {
    function MenuPage(navCtrl, navParams) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.rootPage = 'TabsPage';
        this.pages = [
            { title: 'Notícias', pageName: 'ListanoticiasPage', tabComponent: 'ListanoticiasPage', icon: 'ios-book-outline' },
            { title: 'Cursos', pageName: 'ListacursosPage', tabComponent: 'ListacursosPage', icon: 'ios-bookmarks-outline' },
            { title: 'Unidades', pageName: 'ListaPolosPage', tabComponent: 'PolosPage', icon: 'ios-home-outline' },
            { title: 'Faq', pageName: 'FaqPage', tabComponent: 'FaqPage', icon: 'ios-bulb-outline' },
            { title: 'Login', pageName: 'LoginPage', icon: 'ios-log-in-outline' },
            { title: 'Chat', pageName: 'RoomPage', icon: 'ios-mail-outline' },
            { title: 'Fale Conosco', pageName: 'FaleConoscoPage', tabComponent: 'FaleConoscoPage', icon: 'ios-call-outline' },
        ];
    }
    MenuPage.prototype.openPage = function (page) {
        var params = {};
        if (page.index) {
            params = { tabIndex: page.index };
        }
        if (this.nav.getActiveChildNav() && page.index != undefined) {
            this.nav.getActiveChildNav().select(page.index);
        }
        else {
            this.nav.setRoot(page.pageName, params);
        }
    };
    MenuPage.prototype.isActive = function (page) {
        var childNav = this.nav.getActiveChildNav();
        if (childNav) {
            if (childNav.getSelected() && childNav.getSelected().root === page.tabComponent) {
                return 'primary';
            }
            return;
        }
        if (this.nav.getActive() && this.nav.getActive().name === page.pageName) {
            return 'primary';
        }
    };
    __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["_8" /* ViewChild */])(__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["g" /* Nav */]),
        __metadata("design:type", __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["g" /* Nav */])
    ], MenuPage.prototype, "nav", void 0);
    MenuPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-menu',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/menu/menu.html"*/'<ion-menu [content]="content">\n\n    <ion-header>\n        <ion-navbar>\n            <ion-grid>\n                <ion-row>\n                    <ion-col>\n                        <img class="right" id="logoToolbar" src="../assets/svgs/senaclogo.svg" \n                        style="width:60px; height:60px"/>\n                    </ion-col>\n                    <ion-col>                        \n                        <h2 id="titleMenu">SENAC MT</h2>\n                    </ion-col>\n                </ion-row>\n            </ion-grid>\n        </ion-navbar>\n\n    </ion-header>\n\n    <ion-content>\n        <ion-list>\n            <button ion-item menuClose *ngFor="let p of pages" (click)="openPage(p)">\n                    <ion-icon item-start [name]="p.icon" [color]="isActive(p)"></ion-icon>\n                {{ p.title }}\n            </button>\n        </ion-list>\n    </ion-content>\n\n</ion-menu>\n\n<ion-nav [root]="rootPage" #content swipeBackEnabled="false"></ion-nav>'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/menu/menu.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */]])
    ], MenuPage);
    return MenuPage;
}());

//# sourceMappingURL=menu.js.map

/***/ }),

/***/ 388:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return WelcomePage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__providers_util_singleton_service__ = __webpack_require__(59);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};



var WelcomePage = /** @class */ (function () {
    function WelcomePage(singleton, navCtrl, navParams) {
        this.singleton = singleton;
        this.navCtrl = navCtrl;
        this.navParams = navParams;
    }
    WelcomePage.prototype.ionViewDidLoad = function () {
        console.log('ionViewDidLoad FaleConoscoPage');
    };
    WelcomePage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-welcome',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/welcome/welcome.html"*/'<ion-header>\n\n        <ion-navbar>\n\n        <ion-title text-center>Welcome</ion-title>\n        <ion-buttons start>\n            <button ion-button menuToggle>\n                <ion-icon name="menu"></ion-icon>\n            </button>\n        </ion-buttons>\n    </ion-navbar>\n\n</ion-header>\n\n\n<ion-content padding>\n\n</ion-content>'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/welcome/welcome.html"*/
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_2__providers_util_singleton_service__["a" /* SingletonService */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */]])
    ], WelcomePage);
    return WelcomePage;
}());

//# sourceMappingURL=welcome.js.map

/***/ }),

/***/ 389:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return SignupPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__ionic_storage__ = __webpack_require__(122);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__login_login__ = __webpack_require__(77);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__angular_forms__ = __webpack_require__(13);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5_md5_typescript__ = __webpack_require__(390);
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
 * Generated class for the SignupPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */
var SignupPage = /** @class */ (function () {
    function SignupPage(form, navCtrl, navParams, loadingCtrl, toastCtrl, storage) {
        this.form = form;
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.loadingCtrl = loadingCtrl;
        this.toastCtrl = toastCtrl;
        this.storage = storage;
        this.errornome = false;
        this.erroremail = false;
        this.errorsenha = false;
        this.errorcpf = false;
        this.msgemail = "";
        this.msgsenha = "";
        this.curruser = [{
                unome: "",
                uemail: "",
                ucpf: "",
                umd5pwd: ""
            }];
        this.signupform = form.group({
            nome: ['', __WEBPACK_IMPORTED_MODULE_4__angular_forms__["f" /* Validators */].required],
            email: ['', __WEBPACK_IMPORTED_MODULE_4__angular_forms__["f" /* Validators */].compose([
                    __WEBPACK_IMPORTED_MODULE_4__angular_forms__["f" /* Validators */].required,
                    __WEBPACK_IMPORTED_MODULE_4__angular_forms__["f" /* Validators */].pattern('^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$')
                ])],
            cpf: ['', __WEBPACK_IMPORTED_MODULE_4__angular_forms__["f" /* Validators */].compose([
                    __WEBPACK_IMPORTED_MODULE_4__angular_forms__["f" /* Validators */].required,
                    __WEBPACK_IMPORTED_MODULE_4__angular_forms__["f" /* Validators */].pattern('[0-9]{11}')
                    /*Validators.pattern('[0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2}')*/
                ])],
            senha: ['', __WEBPACK_IMPORTED_MODULE_4__angular_forms__["f" /* Validators */].compose([
                    __WEBPACK_IMPORTED_MODULE_4__angular_forms__["f" /* Validators */].minLength(6),
                    __WEBPACK_IMPORTED_MODULE_4__angular_forms__["f" /* Validators */].maxLength(8),
                    __WEBPACK_IMPORTED_MODULE_4__angular_forms__["f" /* Validators */].required
                ])]
        });
    }
    SignupPage.prototype.signup = function () {
        var _a = this.signupform.controls, nome = _a.nome, email = _a.email, cpf = _a.cpf, senha = _a.senha;
        if (!this.signupform.valid) {
            if (!email.valid) {
                this.erroremail = true;
            }
            else {
                this.erroremail = false;
            }
            if (!senha.valid) {
                this.errorsenha = true;
            }
            else {
                this.errorsenha = false;
            }
            if (!nome.valid) {
                this.errornome = true;
            }
            else {
                this.errornome = false;
            }
            if (!cpf.valid) {
                this.errorcpf = true;
            }
            else {
                this.errorcpf = false;
            }
        }
        else {
            this.curruser.umd5pwd = __WEBPACK_IMPORTED_MODULE_5_md5_typescript__["a" /* Md5 */].init(this.tmpsenha);
            console.log(this.curruser.umd5);
            this.presentMd5Toast(this.curruser.umd5);
            this.curruser.umd5pwd = __WEBPACK_IMPORTED_MODULE_5_md5_typescript__["a" /* Md5 */].init(this.curruser.uemail);
            this.storage.set("senacstore", this.curruser);
            this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_3__login_login__["a" /* LoginPage */]);
        }
    };
    SignupPage.prototype.doRefresh = function (refresher) {
        console.log('Begin async operation', refresher);
        setTimeout(function () {
            console.log('Async operation has ended');
            refresher.complete();
        }, 2000);
    };
    SignupPage.prototype.presentMd5Toast = function (md5) {
        var toast = this.toastCtrl.create({
            message: md5,
            position: 'bottom',
            showCloseButton: true,
            cssClass: "justify",
            closeButtonText: 'fechar'
        });
        toast.onDidDismiss(function () {
            console.log('Dismissed toast');
        });
        toast.present();
    };
    SignupPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_1__angular_core__["m" /* Component */])({
            selector: 'page-signup',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/signup/signup.html"*/'<!--\n  Generated template for the SignupPage page.\n\n  See http://ionicframework.com/docs/components/#navigation for more info on\n  Ionic pages and navigation.\n-->\n<ion-header>\n\n  <ion-navbar>\n    <ion-title>Signup</ion-title>\n  </ion-navbar>\n\n</ion-header>\n\n\n<ion-content>\n\n  <ion-refresher (ionRefresh)="doRefresh($event)">\n      <ion-refresher-content></ion-refresher-content>\n    </ion-refresher>\n                \n  \n                 <div style="text-align:center">\n    \n                   <h1  class="lato" style="font-size:30px; color:#000000;font-weight:900;">\n                    Crie Sua Conta</h1>\n                   <div style="display: inline-block;">\n\n                     <form [formGroup]="signupform">\n\n                       <ion-item>\n                         \n                         <ion-input class="lato lcase inputfield" \n                                    formControlName="email"\n                                    typeof="text"\n                                    placeholder="Email" [(ngModel)]="curruser.uemail">\n                         </ion-input>\n\n                       </ion-item>\n                       <p *ngIf="erroremail; else noerror1" class="error lato">O seu email contém erros</p>\n                       <ng-template #noerror1>\n                          <p> &nbsp;</p>\n                        </ng-template>\n                  \n\n                       <ion-item>\n                         \n                         <ion-input formControlName="nome"\n                                    typeof="text"\n       \n                                    placeholder="Nome"\n                                    [(ngModel)]="curruser.unome"\n                                    class="lato inputfield">\n                         </ion-input>\n                       </ion-item>\n                       <p *ngIf="errornome; else noerror2" class="error lato">Nome é um campo obrigatório</p>\n                       <ng-template #noerror2>\n                          <p> &nbsp;</p>\n                        </ng-template>\n\n                       <ion-item>\n                         <ion-input formControlName="cpf"\n                                    typeof="text"\n                                    placeholder="CPF (só numeros)"\n                                    [(ngModel)]="curruser.ucpf"\n                                    class="lato inputfield">\n                         </ion-input>\n                       </ion-item>\n                       <p *ngIf="errorcpf; else noerror3" class="error lato">Seu CPF está no formato incorreto<p>\n                          <ng-template #noerror3>\n                              <p> &nbsp;</p>\n                            </ng-template>\n                \n                      \n                       <ion-item>\n                         <ion-input formControlName="senha"\n                                    typeof="password"\n                                    clearInput\n                                    clearOnEdit="false"\n                                    placeholder="Senha"\n                                    [(ngModel)]="this.tmpsenha"\n                                    class="lato lcase inputfield">\n                         </ion-input>\n                       </ion-item>\n                       <p *ngIf="errorsenha; else noerror5" class="error lato">Sua senha tem que ter entre 6 e 8 digitos e/ou letras</p>\n                       <ng-template #noerror5>\n                          <p> &nbsp;</p>\n                        </ng-template>\n\n                     </form>\n                   </div>\n                 </div>\n\n                 <div class="row">\n                    <div class="col-10 col" style="text-align: center;">\n                \n                      <button (click)="signup()" ion-button icon-start \n                      class="lato BkcSenacAzul"\n                      style=" border-radius: 7px; background-color: #216778; font-size:15px;padding:10px;width:250px;height:60px;display: inline-block">\n                            Salvar\n                        </button>\n                    </div>\n                  </div>\n               \n\n</ion-content>'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/signup/signup.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_4__angular_forms__["a" /* FormBuilder */], __WEBPACK_IMPORTED_MODULE_0_ionic_angular__["h" /* NavController */],
            __WEBPACK_IMPORTED_MODULE_0_ionic_angular__["i" /* NavParams */],
            __WEBPACK_IMPORTED_MODULE_0_ionic_angular__["f" /* LoadingController */],
            __WEBPACK_IMPORTED_MODULE_0_ionic_angular__["k" /* ToastController */],
            __WEBPACK_IMPORTED_MODULE_2__ionic_storage__["b" /* Storage */]])
    ], SignupPage);
    return SignupPage;
}());

//# sourceMappingURL=signup.js.map

/***/ }),

/***/ 391:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return DatabaseProvider; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__ionic_native_sqlite__ = __webpack_require__(392);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_rxjs_add_operator_map__ = __webpack_require__(76);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_rxjs_add_operator_map___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_rxjs_add_operator_map__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__angular_http__ = __webpack_require__(393);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
//import { HttpClient } from '@angular/common/http';




/*
  Generated class for the DatabaseProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
var DatabaseProvider = /** @class */ (function () {
    function DatabaseProvider(http, storage) {
        var _this = this;
        this.http = http;
        this.storage = storage;
        if (!this.isOpen) {
            this.storage = new __WEBPACK_IMPORTED_MODULE_1__ionic_native_sqlite__["a" /* SQLite */]();
            this.storage.create({ name: "data.db", location: "default" }).then(function (db) {
                _this.db = db;
                //db.executeSql("CREATE TABLE IF NOT EXISTS  tbl0001unidadesprodutivas (lcl0001UnidadeProdutiva INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL DEFAULT (0),pk0001UnidadeProdutiva INTEGER,user_id INTEGER NOT NULL.created DATETIME NOT NULL,modified datetime DEFAULT NULL,strmatriz varchar(255) NOT NULL,strnomecomum varchar(255) NOT NULL,strareatotal varchar(255) NOT NULL,strendereco varchar(255) NOT NULL,strcep varchar(255) NOT NULL,strmunicipio varchar(255) NOT NULL,strlatcent varchar(255) NOT NULL,strlngcent varchar(255) NOT NULL,strlatsede varchar(255) NOT NULL,strlngsede varchar(255) NOT NULL,strcerrado INTEGER NOT NULL DEFAULT (0),strpantanal INTEGER NOT NULL DEFAULT (0),stramazonia INTEGER NOT NULL DEFAULT (0),strorigemdominial varchar(255) NOT NULL,strtituloorigem varchar(255) NOT NULL)",[]);
                //db.executeSql("CREATE TABLE IF NOT EXISTS  tbl0002shpuploads (lcl0002shpuploads INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL DEFAULT (0),pk0002shpuploads INTEGER,user_id INTEGER NOT NULL,created DATETIME NOT NULL,fk0001Unidadeprodutiva INTEGER NOT NULL,strarquivoshp varchar(255) NOT NULL)",[]);
                //db.executeSql("CREATE TABLE IF NOT EXISTS  tbl0003upareas (lcl0003upareas  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL DEFAULT (0),pk0003upareas  INTEGER,user_id INTEGER NOT NULL,created DATETIME NOT NULL,fk0001unidadeprodutiva INTEGER NOT NULL,strareatotalha varchar(255) NOT NULL,strpreservacaoha varchar(255) NOT NULL,strreservalegalha varchar(255) NOT NULL,strflorestanativaha varchar(255) NOT NULL,strproducaovegetalha varchar(255) NOT NULL,strdescansoha varchar(255) NOT NULL,strreflorestamentoha varchar(255) NOT NULL,strpastagemha varchar(255) NOT NULL,strextrativaha varchar(255) NOT NULL,strmineralha varchar(255) NOT NULL,straquicolaha varchar(255) NOT NULL,strfrutracaoha varchar(255) NOT NULL,stratividaderuralha varchar(255) NOT NULL)",[]);
                db.executeSql("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, identification INTEGER, name TEXT, lastname TEXT)", []);
                _this.isOpen = true;
            }).catch(function (error) {
                console.log(error);
            });
        }
    }
    DatabaseProvider.prototype.CreateUser = function (identification, name, lastname) {
        var _this = this;
        return new Promise(function (resolve, reject) {
            var sql = "INSERT INTO users (identification, name, lastname) VALUES (?,?,?)";
            _this.db.executeSql(sql, [identification, name, lastname]).then(function (data) {
                resolve(data);
            }, function (error) {
                reject(error);
            });
        });
    };
    DatabaseProvider.prototype.DeleteUser = function (idUser) {
        var _this = this;
        return new Promise(function (resolve, reject) {
            var sql = "DELETE FROM users WHERE id = ?";
            _this.db.executeSql(sql, [idUser]).then(function (data) {
                resolve(data);
            }, function (error) {
                reject(error);
            });
        });
    };
    DatabaseProvider.prototype.GetAllUsers = function () {
        var _this = this;
        return new Promise(function (resolve, reject) {
            _this.db.executeSql("SELECT * FROM users", []).then(function (data) {
                var arrayUsers = [];
                if (data.rows.length > 0) {
                    for (var i = 0; i < data.rows.length; i++) {
                        arrayUsers.push({
                            id: data.rows.item(i).id,
                            identification: data.rows.item(i).identification,
                            name: data.rows.item(i).name,
                            lastname: data.rows.item(i).lastname
                        });
                    }
                }
                resolve(arrayUsers);
            }, function (error) {
                reject(error);
            });
        });
    };
    DatabaseProvider = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["A" /* Injectable */])(),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_3__angular_http__["a" /* Http */],
            __WEBPACK_IMPORTED_MODULE_1__ionic_native_sqlite__["a" /* SQLite */]])
    ], DatabaseProvider);
    return DatabaseProvider;
}());

//# sourceMappingURL=database.js.map

/***/ }),

/***/ 59:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return SingletonService; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};

var SingletonService = /** @class */ (function () {
    function SingletonService() {
        this.loginstate = false;
        this.loginemail = "";
        this.loginname = "";
        this.loginmd5pwd = "";
        this.loginuid = "";
    }
    SingletonService = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["A" /* Injectable */])()
    ], SingletonService);
    return SingletonService;
}());

//# sourceMappingURL=singleton.service.js.map

/***/ }),

/***/ 75:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return RestProvider; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_common_http__ = __webpack_require__(209);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_rxjs_add_operator_map__ = __webpack_require__(76);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_rxjs_add_operator_map___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_rxjs_add_operator_map__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_sweetalert__ = __webpack_require__(359);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_sweetalert___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_sweetalert__);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




var RestProvider = /** @class */ (function () {
    function RestProvider(http) {
        this.http = http;
        this.apiUrl = 'http://www.technofarm.com.br/webservices/tfwebservice.php';
        this.capiUrl = 'http://www.technofarm.com.br/api/v1/';
        console.log('constructor Rest Provider');
    }
    RestProvider.prototype.getRest = function (myJson) {
        var _this = this;
        this.reqOpts = {
            headers: {
                'AuthType': 'Basic',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Basic YWRtaW46cGFzc3dvcmQ=',
                'Access-Control-Allow-Origin': '*',
                'Access-Control-Allow-Methods': 'GET, POST, DELETE, PUT, OPTIONS',
                'Access-Control-Allow-Headers': 'origin, content-type, accept, authorization, x-request-with',
                'Access-Control-Allow-Credentials': 'true'
            },
            params: new __WEBPACK_IMPORTED_MODULE_0__angular_common_http__["c" /* HttpParams */]()
        };
        console.log('url:' + this.apiUrl);
        console.log(myJson);
        return new Promise(function (resolve) {
            _this.http.post(_this.apiUrl, myJson, _this.reqOpts)
                .subscribe(function (data) {
                resolve(data);
            }, function (err) {
                console.log(err);
            });
        });
    };
    RestProvider.prototype.execGET = function (operation) {
        var _this = this;
        this.reqOpts = {
            headers: {
                'Content-Type': 'application/json',
                'token': '4xcUZzijQWi314zTdOUMN4QYMZ0Y73Y2zNNDTmV',
                'Accept': 'application/json'
            },
            params: new __WEBPACK_IMPORTED_MODULE_0__angular_common_http__["c" /* HttpParams */]()
        };
        var completecapi = this.capiUrl + operation;
        console.log('url:' + completecapi);
        return new Promise(function (resolve) {
            _this.http.get(completecapi, _this.reqOpts)
                .subscribe(function (data) {
                resolve(data);
            }, function (err) {
                console.log(err);
                __WEBPACK_IMPORTED_MODULE_3_sweetalert___default()("", "Para fazer o seu login, deve usar as mesmas credenciais (usuario e senha) que cadastrou no nosso site http://www.technofarm.com.br\nSe não se recorda do usuário e/ou senha, no site tem a possibilidade de solucionar esse tema.\nObrigado.", "error");
            });
        });
    };
    RestProvider = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_1__angular_core__["A" /* Injectable */])(),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_0__angular_common_http__["a" /* HttpClient */]])
    ], RestProvider);
    return RestProvider;
}());

//# sourceMappingURL=rest.js.map

/***/ }),

/***/ 77:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return LoginPage; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__ionic_storage__ = __webpack_require__(122);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__angular_forms__ = __webpack_require__(13);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__providers_rest_rest__ = __webpack_require__(75);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__providers_util_singleton_service__ = __webpack_require__(59);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};




//import { Md5 } from "md5-typescript";


var LoginPage = /** @class */ (function () {
    function LoginPage(form, navCtrl, loadingCtrl, toastCtrl, storage, singleton, restProvider) {
        this.form = form;
        this.navCtrl = navCtrl;
        this.loadingCtrl = loadingCtrl;
        this.toastCtrl = toastCtrl;
        this.storage = storage;
        this.singleton = singleton;
        this.restProvider = restProvider;
        this.erroremail = false;
        this.errorsenha = false;
        this.errorcpf = false;
        this.errornome = false;
        this.errorano = false;
        this.errorsexo = false;
        this.msgemail = "";
        this.msgsenha = "";
        this.curruser = [{
                uemail: "",
                unome: "",
                ucpf: "",
                umd5pwd: ""
            }];
        this.loginform = form.group({
            uid: "",
            pwd: ""
        });
        this.dopageoperations();
    }
    LoginPage.prototype.dopageoperations = function () {
    };
    LoginPage.prototype.presenttoast = function () {
        var toast = this.toastCtrl.create({
            message: this.strmd5notice,
            position: 'bottom',
            showCloseButton: true,
            closeButtonText: 'Fechar'
        });
        toast.onDidDismiss(function () {
        });
        toast.present();
    };
    LoginPage.prototype.dorefresh = function (refresher) {
        console.log('Begin async operation', refresher);
        setTimeout(function () {
            console.log('Async operation has ended');
            refresher.complete();
        }, 2000);
    };
    LoginPage.prototype.deletelocalaccount = function () {
        this.storage.remove('senacstore');
        this.navCtrl.setRoot(this.navCtrl.getActive().component);
    };
    LoginPage.prototype.alerttoast = function (msg) {
        console.log('Toast INIT');
        var toast = this.toastCtrl.create({
            message: msg,
            position: 'middle',
            duration: 3000
        });
        toast.onDidDismiss(function () {
            console.log('Dismissed toast');
        });
    };
    LoginPage.prototype.validatelogin = function () {
        var _this = this;
        var operation1 = "user/login/" + this.struid + "/" + this.strpwd;
        console.log(operation1);
        this.restProvider.execGET(operation1)
            .then(function (data1) {
            _this.data_1 = data1;
            if (_this.data_1.msg == "Authenticated") {
                var operation2 = "user/detail/username/" + _this.struid;
                _this.restProvider.execGET(operation2)
                    .then(function (data2) {
                    _this.data_2 = data2;
                    _this.singleton.loginuid = _this.data_2.id;
                    _this.singleton.loginname = _this.data_2.username;
                    _this.singleton.loginstate = true;
                    console.log("singleton_uid1:" + _this.singleton.loginuid);
                    _this.alerttoast("Aguarde, por favor... Sincronizando seus dados.");
                    _this.navCtrl.setRoot('UnidprodPage', { single: _this.singleton });
                });
            }
        });
    };
    LoginPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-login',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/login/login.html"*/'<ion-header class="bkctfdarko">\n\n  \n\n</ion-header>\n\n<ion-content style="background-color: #f9ecc7;">\n   \n                 <div style="text-align:center">\n                    <br/><br/>\n                  <img src="assets/svgs/technofarm-symbol.svg" style="width:100px;">\n            \n                    <h1  class="" style="font-family:\'Lato\';font-size:30px; color:#000000;font-weight:900;">\n                    Olá, seja bem vindo</h1>\n                    <br/>\n                   <div style="display: inline-block;">\n\n                     <form [formGroup]="loginform">   \n\n                       <ion-item >\n                        \n                         <ion-input formControlName="uid"\n                                    placeholder="Usuário"\n                                    [(ngModel)]="struid"\n                                    class="loveya lcase inputfield"\n                                    style="font-family:\'Lato\';">\n                         </ion-input>\n                       </ion-item>\n                      <br/><br/>\n                       <ion-item>\n                        <ion-input formControlName="pwd"\n                                   placeholder="Senha"\n                                   [(ngModel)]="strpwd"\n                                   class="loveya lcase inputfield"\n                                   style="font-family:\'Lato\';">\n                        </ion-input>\n                      </ion-item>\n                     </form>\n\n                   </div>\n                 </div>\n                  <br/><br/>\n                 <div class="row">\n                    <div class="col-10 col" style="font-family:\'Lato\';text-align: center;">\n                      <button (click)="validatelogin()" ion-button icon-start \n                      class="lato bkctfdarko"\n                      style="font-weight:600 ;border-radius: 7px; font-size:15px;padding:10px;width:250px;height:60px;display: inline-block">\n                      Faça seu login\n                        </button>\n                    </div>\n                  </div>\n\n\n                \n               \n\n</ion-content>'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/login/login.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_3__angular_forms__["a" /* FormBuilder */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["f" /* LoadingController */],
            __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["k" /* ToastController */],
            __WEBPACK_IMPORTED_MODULE_2__ionic_storage__["b" /* Storage */],
            __WEBPACK_IMPORTED_MODULE_5__providers_util_singleton_service__["a" /* SingletonService */],
            __WEBPACK_IMPORTED_MODULE_4__providers_rest_rest__["a" /* RestProvider */]])
    ], LoginPage);
    return LoginPage;
}());

//# sourceMappingURL=login.js.map

/***/ })

},[258]);
//# sourceMappingURL=main.js.map