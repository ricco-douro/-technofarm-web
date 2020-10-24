webpackJsonp([1],{

/***/ 394:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "RoomPageModule", function() { return RoomPageModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__room__ = __webpack_require__(412);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};



var RoomPageModule = /** @class */ (function () {
    function RoomPageModule() {
    }
    RoomPageModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["I" /* NgModule */])({
            declarations: [
                __WEBPACK_IMPORTED_MODULE_2__room__["a" /* RoomPage */],
            ],
            imports: [
                __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["e" /* IonicPageModule */].forChild(__WEBPACK_IMPORTED_MODULE_2__room__["a" /* RoomPage */]),
            ],
        })
    ], RoomPageModule);
    return RoomPageModule;
}());

//# sourceMappingURL=room.module.js.map

/***/ }),

/***/ 412:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return RoomPage; });
/* unused harmony export snapshotToArray */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_ionic_angular__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__home_home__ = __webpack_require__(255);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__login_login__ = __webpack_require__(77);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__providers_util_singleton_service__ = __webpack_require__(59);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5_firebase_app__ = __webpack_require__(413);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5_firebase_app___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_5_firebase_app__);
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};



//import { ListanoticiasPage } from '../../noticia/listanoticias/listanoticias'



var RoomPage = /** @class */ (function () {
    function RoomPage(navCtrl, navParams, singleton) {
        this.navCtrl = navCtrl;
        this.navParams = navParams;
        this.singleton = singleton;
        this.rooms = [];
        this.ref = __WEBPACK_IMPORTED_MODULE_5_firebase_app__["database"]().ref('chatrooms/');
        if (this.singleton.loginemail.length == 0) {
            this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_3__login_login__["a" /* LoginPage */]);
        }
    }
    RoomPage.prototype.joinRoom = function (key) {
        this.navCtrl.setRoot(__WEBPACK_IMPORTED_MODULE_2__home_home__["a" /* HomePage */], {
            key: key,
            nickname: this.singleton.loginname
        });
    };
    RoomPage.prototype.exitRooms = function () {
        this.navCtrl.setRoot('ListanoticiasPage');
    };
    RoomPage.prototype.ionViewDidLoad = function () {
        var _this = this;
        console.log('ionViewDidLoad RoomPage');
        this.ref.on('value', function (resp) {
            _this.rooms = [];
            console.log(snapshotToArray(resp));
            _this.rooms = snapshotToArray(resp);
        });
    };
    RoomPage.prototype.showmychatsonly = function (array) {
        var _this = this;
        return array.filter(function (obj) {
            return obj.email === _this.singleton.loginemail;
        });
    };
    RoomPage = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["m" /* Component */])({
            selector: 'page-room',template:/*ion-inline-start:"/home/ayedasan/mobapps/technofarm/src/pages/_chat/room/room.html"*/'\n\n<ion-header>\n\n  <ion-navbar>\n\n    <ion-title>Salas de Bate Papo</ion-title>\n\n    <ion-buttons end>\n\n        <button class="TxtWhite" ion-button icon-only (click)="exitRooms()">\n\n        <ion-icon name="exit"></ion-icon>\n\n      </button>\n\n    </ion-buttons>\n\n\n\n  </ion-navbar>\n\n</ion-header>\n\n\n\n\n\n<ion-content padding>\n\n\n\n\n\n  <ion-list>\n\n    <ion-item *ngFor="let room of rooms">\n\n      {{room.roomname}}\n\n      <ion-icon name="chatboxes" item-end (click)="joinRoom(room.key)"></ion-icon>\n\n    </ion-item>\n\n  </ion-list>\n\n</ion-content>'/*ion-inline-end:"/home/ayedasan/mobapps/technofarm/src/pages/_chat/room/room.html"*/,
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_1_ionic_angular__["h" /* NavController */], __WEBPACK_IMPORTED_MODULE_1_ionic_angular__["i" /* NavParams */], __WEBPACK_IMPORTED_MODULE_4__providers_util_singleton_service__["a" /* SingletonService */]])
    ], RoomPage);
    return RoomPage;
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
//# sourceMappingURL=room.js.map

/***/ }),

/***/ 413:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _interopDefault (ex) { return (ex && (typeof ex === 'object') && 'default' in ex) ? ex['default'] : ex; }

__webpack_require__(256);
var firebase = _interopDefault(__webpack_require__(31));

/**
 * Copyright 2018 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

module.exports = firebase;


/***/ })

});
//# sourceMappingURL=1.js.map