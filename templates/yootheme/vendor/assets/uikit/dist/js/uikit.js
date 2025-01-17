/*! UIkit 3.0.0-beta.19 | http://www.getuikit.com | (c) 2014 - 2017 YOOtheme | MIT License */

(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory(require('jquery')) :
    typeof define === 'function' && define.amd ? define('uikit', ['jquery'], factory) :
    (global.UIkit = factory(global.jQuery));
}(this, (function ($$1) { 'use strict';

var $$1__default = 'default' in $$1 ? $$1['default'] : $$1;

var win = $$1__default(window);
var doc = $$1__default(document);
var docElement = $$1__default(document.documentElement);

var isRtl = $$1__default('html').attr('dir') === 'rtl';

function isReady() {
    return document.readyState === 'complete' || document.readyState !== 'loading' && !document.documentElement.doScroll;
}

function ready(fn) {

    var handle = function () {
        off(document, 'DOMContentLoaded', handle);
        off(window, 'load', handle);
        fn();
    };

    if (isReady()) {
        fn();
    } else {
        on(document, 'DOMContentLoaded', handle);
        on(window, 'load', handle);
    }

}

function on(el, type, listener, useCapture) {
    toNode(el).addEventListener(type, listener, useCapture)
}

function off(el, type, listener, useCapture) {
    toNode(el).removeEventListener(type, listener, useCapture)
}

function transition(element, props, duration, transition) {
    if ( duration === void 0 ) duration = 400;
    if ( transition === void 0 ) transition = 'linear';


    var p = promise(function (resolve, reject) {

        element = $$1__default(element);

        for (var name in props) {
            element.css(name, element.css(name));
        }

        var timer = setTimeout(function () { return element.trigger(transitionend || 'transitionend'); }, duration);

        element
            .one(transitionend || 'transitionend', function (e, cancel) {

                e.promise = p;

                clearTimeout(timer);
                element.removeClass('uk-transition').css('transition', '');
                if (!cancel) {
                    resolve();
                } else {
                    reject();
                }
            })
            .addClass('uk-transition')
            .css('transition', ("all " + duration + "ms " + transition))
            .css(props);

    }).then(null, function () {});

    return p;
}

var Transition = {

    start: transition,

    stop: function stop(element, cancel) {
        var e = $$1__default.Event(transitionend || 'transitionend');
        $$1__default(element).triggerHandler(e, [cancel]);
        return e.promise || promise.resolve();
    },

    cancel: function cancel(element) {
        return this.stop(element, true);
    },

    inProgress: function inProgress(element) {
        return $$1__default(element).hasClass('uk-transition');
    }

};

function animate(element, animation, duration, origin, out) {
    if ( duration === void 0 ) duration = 200;


    var p = promise(function (resolve) {

        var cls = out ? 'uk-animation-leave' : 'uk-animation-enter';

        element = $$1__default(element);

        if (animation.lastIndexOf('uk-animation-', 0) === 0) {

            if (origin) {
                animation += " uk-animation-" + origin;
            }

            if (out) {
                animation += ' uk-animation-reverse';
            }

        }

        reset();

        element
            .one(animationend || 'animationend', function (e) {
                e.promise = p;
                p.then(reset);
                resolve();
            })
            .css('animation-duration', (duration + "ms"))
            .addClass(animation)
            .addClass(cls);

        if (!animationend) {
            requestAnimationFrame(function () { return Animation.cancel(element); });
        }

        function reset() {
            element.css('animation-duration', '').removeClass((cls + " " + animation));
        }

    });

    return p;
}

var Animation = {

    in: function in$1(element, animation, duration, origin) {
        return animate(element, animation, duration, origin, false);
    },

    out: function out(element, animation, duration, origin) {
        return animate(element, animation, duration, origin, true);
    },

    inProgress: function inProgress(element) {
        return $$1__default(element).hasClass('uk-animation-enter') || $$1__default(element).hasClass('uk-animation-leave');
    },

    cancel: function cancel(element) {
        var e = $$1__default.Event(animationend || 'animationend');
        $$1__default(element).triggerHandler(e);
        return e.promise || promise.resolve();
    }

};

function isJQuery(obj) {
    return obj instanceof $$1__default;
}

function isWithin(element, selector) {
    element = $$1__default(element);
    return element.is(selector) || !!(isString(selector)
        ? element.parents(selector).length
        : $$1__default.contains(toNode(selector), element[0]));
}

function attrFilter(element, attr, pattern, replacement) {
    element = $$1__default(element);
    return element.attr(attr, function (i, value) { return value ? value.replace(pattern, replacement) : value; });
}

function removeClass(element, cls) {
    return attrFilter(element, 'class', new RegExp(("(^|\\s)" + cls + "(?!\\S)"), 'g'), '');
}

function createEvent(e, bubbles, cancelable, data) {
    if ( bubbles === void 0 ) bubbles = true;
    if ( cancelable === void 0 ) cancelable = false;
    if ( data === void 0 ) data = false;

    if (isString(e)) {
        var event = document.createEvent('Event');
        event.initEvent(e, bubbles, cancelable);
        e = event;
    }

    if (data) {
        $$1__default.extend(e, data);
    }

    return e;
}

function isInView(element, offsetTop, offsetLeft) {
    if ( offsetTop === void 0 ) offsetTop = 0;
    if ( offsetLeft === void 0 ) offsetLeft = 0;


    var rect = toNode(element).getBoundingClientRect();

    return rect.bottom >= -1 * offsetTop
        && rect.right >= -1 * offsetLeft
        && rect.top <= (window.innerHeight || document.documentElement.clientHeight) + offsetTop
        && rect.left <= (window.innerWidth || document.documentElement.clientWidth) + offsetLeft;
}

function getIndex(index, elements, current) {
    if ( current === void 0 ) current = 0;


    elements = $$1__default(elements);

    var length = $$1__default(elements).length;

    index = (isNumber(index)
        ? index
        : index === 'next'
            ? current + 1
            : index === 'previous'
                ? current - 1
                : isString(index)
                    ? parseInt(index, 10)
                    : elements.index(index)
    ) % length;

    return index < 0 ? index + length : index;
}

var voidElements = {
    area: true,
    base: true,
    br: true,
    col: true,
    embed: true,
    hr: true,
    img: true,
    input: true,
    keygen: true,
    link: true,
    menuitem: true,
    meta: true,
    param: true,
    source: true,
    track: true,
    wbr: true
};
function isVoidElement(element) {
    return voidElements[toNode(element).tagName.toLowerCase()];
}

var Dimensions = {

    ratio: function ratio(dimensions, prop, value) {

        var aProp = prop === 'width' ? 'height' : 'width';

        return ( obj = {}, obj[aProp] = Math.round(value * dimensions[aProp] / dimensions[prop]), obj[prop] = value, obj );
        var obj;
    },

    fit: function fit(dimensions, maxDimensions) {
        var this$1 = this;

        dimensions = $$1.extend({}, dimensions);

        $$1.each(dimensions, function (prop) { return dimensions = dimensions[prop] > maxDimensions[prop] ? this$1.ratio(dimensions, prop, maxDimensions[prop]) : dimensions; });

        return dimensions;
    },

    cover: function cover(dimensions, maxDimensions) {
        var this$1 = this;

        dimensions = this.fit(dimensions, maxDimensions);

        $$1.each(dimensions, function (prop) { return dimensions = dimensions[prop] < maxDimensions[prop] ? this$1.ratio(dimensions, prop, maxDimensions[prop]) : dimensions; });

        return dimensions;
    }

};

function query(selector, context) {
    var selectors = getContextSelectors(selector);
    return selectors ? selectors.reduce(function (context, selector) { return toJQuery(selector, context); }, context) : toJQuery(selector);
}

function bind(fn, context) {
    return function (a) {
        var l = arguments.length;
        return l ? l > 1 ? fn.apply(context, arguments) : fn.call(context, a) : fn.call(context);
    };
}

var hasOwnProperty = Object.prototype.hasOwnProperty;
function hasOwn(obj, key) {
    return hasOwnProperty.call(obj, key);
}

function promise(executor) {

    if (hasPromise) {
        return new Promise(executor);
    }

    var def = $$1__default.Deferred();

    executor(def.resolve, def.reject);

    return def;
}

promise.resolve = function (value) {
    return promise(function (resolve) {
        resolve(value);
    });
};

promise.reject = function (value) {
    return promise(function (_, reject) {
        reject(value);
    });
};

promise.all = function (iterable) {
    return hasPromise
        ? Promise.all(iterable)
        : $$1__default.when.apply($$1__default, iterable);
};

function classify(str) {
    return str.replace(/(?:^|[-_\/])(\w)/g, function (_, c) { return c ? c.toUpperCase() : ''; });
}

function hyphenate(str) {
    return str
        .replace(/([a-z\d])([A-Z])/g, '$1-$2')
        .toLowerCase()
}

var camelizeRE = /-(\w)/g;
function camelize(str) {
    return str.replace(camelizeRE, toUpper)
}

function toUpper(_, c) {
    return c ? c.toUpperCase() : ''
}

function isString(value) {
    return typeof value === 'string';
}

function isNumber(value) {
    return typeof value === 'number';
}

function isUndefined(value) {
    return value === undefined;
}

function isContextSelector(selector) {
    return isString(selector) && selector.match(/^(!|>|\+|-)/);
}

function getContextSelectors(selector) {
    return isContextSelector(selector) && selector.split(/(?=\s(?:!|>|\+|-))/g).map(function (value) { return value.trim(); });
}

var contextSelectors = {'!': 'closest', '+': 'nextAll', '-': 'prevAll'};
function toJQuery(element, context) {

    if (element === true) {
        return null;
    }

    try {

        if (context && isContextSelector(element) && element[0] !== '>') {

            var fn = contextSelectors[element[0]], selector = element.substr(1);

            context = $$1__default(context);

            if (fn === 'closest') {
                context = context.parent();
                selector = selector || '*';
            }

            element = context[fn](selector);

        } else {
            element = $$1__default(element, context);
        }

    } catch (e) {
        return null;
    }

    return element.length ? element : null;
}

function toNode(element) {
    return element && (isJQuery(element) ? element[0] : element);
}

function toBoolean(value) {
    return typeof value === 'boolean'
        ? value
        : value === 'true' || value == '1' || value === ''
            ? true
            : value === 'false' || value == '0'
                ? false
                : value;
}

function toNumber(value) {
    var number = Number(value);
    return !isNaN(number) ? number : false;
}

function toList(value) {
    return $$1.isArray(value)
        ? value
        : isString(value)
            ? value.split(',').map(function (value) { return toBoolean(value.trim()); })
            : [value];
}

var vars = {};
function toMedia(value) {

    if (isString(value)) {
        if (value[0] == '@') {
            var name = "media-" + (value.substr(1));
            value = vars[name] || (vars[name] = parseFloat(getCssVar(name)));
        } else if (value.match(/^\(min-width:/)) {
            return value;
        }
    }

    return value && !isNaN(value) ? ("(min-width: " + value + "px)") : false;
}

function coerce(type, value, context) {

    if (type === Boolean) {
        return toBoolean(value);
    } else if (type === Number) {
        return toNumber(value);
    } else if (type === 'jQuery') {
        return query(value, context);
    } else if (type === 'list') {
        return toList(value);
    } else if (type === 'media') {
        return toMedia(value);
    }

    return type ? type(value) : value;
}

function toMs(time) {
    return !time
        ? 0
        : time.substr(-2) === 'ms'
            ? parseFloat(time)
            : parseFloat(time) * 1000;
}

function swap(value, a, b) {
    return value.replace(new RegExp((a + "|" + b), 'mg'), function (match) {
        return match === a ? b : a
    });
}

var Observer = window.MutationObserver || window.WebKitMutationObserver;
var requestAnimationFrame = window.requestAnimationFrame || function (fn) { return setTimeout(fn, 1000 / 60); };
var cancelAnimationFrame = window.cancelAnimationFrame || window.clearTimeout;

var hasPromise = 'Promise' in window;
var hasTouch = 'ontouchstart' in window
    || window.DocumentTouch && document instanceof DocumentTouch
    || navigator.msPointerEnabled && navigator.msMaxTouchPoints > 0 // IE 10
    || navigator.pointerEnabled && navigator.maxTouchPoints > 0; // IE >=11

var pointerDown = !hasTouch ? 'mousedown' : window.PointerEvent ? 'pointerdown' : 'touchstart';
var pointerMove = !hasTouch ? 'mousemove' : window.PointerEvent ? 'pointermove' : 'touchmove';
var pointerUp = !hasTouch ? 'mouseup' : window.PointerEvent ? 'pointerup' : 'touchend';
var pointerEnter = hasTouch && window.PointerEvent ? 'pointerenter' : 'mouseenter';
var pointerLeave = hasTouch && window.PointerEvent ? 'pointerleave' : 'mouseleave';

var transitionstart = prefix('transition', 'transition-start');
var transitionend = prefix('transition', 'transition-end');
var animationstart = prefix('animation', 'animation-start');
var animationend = prefix('animation', 'animation-end');

function getStyle(element, property, pseudoElt) {
    return (window.getComputedStyle(element, pseudoElt) || {})[property];
}

function getCssVar(name) {

    /* usage in css:  .var-name:before { content:"xyz" } */

    var val, doc = document.documentElement,
        element = doc.appendChild(document.createElement('div'));

    element.classList.add(("var-" + name));

    try {

        val = getStyle(element, 'content', ':before').replace(/^["'](.*)["']$/, '$1');
        val = JSON.parse(val);

    } catch (e) {}

    doc.removeChild(element);

    return val || undefined;
}

function prefix(name, event) {

    var ucase = classify(name),
        lowered = classify(event).toLowerCase(),
        classified = classify(event),
        element = document.body || document.documentElement,
        names = ( obj = {}, obj[("Webkit" + ucase)] = ("webkit" + classified), obj[("Moz" + ucase)] = lowered, obj[("o" + ucase)] = ("o" + classified + " o" + lowered), obj[name] = lowered, obj );
    var obj;

    for (name in names) {
        if (element.style[name] !== undefined) {
            return names[name];
        }
    }
}

/*
    Based on:
    Copyright (c) 2016 Wilson Page wilsonpage@me.com
    https://github.com/wilsonpage/fastdom
*/

var fastdom = {

    reads: [],
    writes: [],

    measure: function(task) {
        this.reads.push(task);
        scheduleFlush(this);
        return task;
    },

    mutate: function(task) {
        this.writes.push(task);
        scheduleFlush(this);
        return task;
    },

    clear: function(task) {
        return remove(this.reads, task) || remove(this.writes, task);
    }

};

function scheduleFlush(fastdom) {
    if (!fastdom.scheduled) {
        fastdom.scheduled = true;
        requestAnimationFrame(flush.bind(null, fastdom));
    }
}

function flush(fastdom) {

    runTasks(fastdom.reads);
    runTasks(fastdom.writes.splice(0, fastdom.writes.length));

    fastdom.scheduled = false;

    if (fastdom.reads.length || fastdom.writes.length) {
        scheduleFlush(fastdom);
    }

}

function runTasks(tasks) {
    var task;
    while (task = tasks.shift()) {
        task();
    }
}

function remove(array, item) {
    var index = array.indexOf(item);
    return !!~index && !!array.splice(index, 1);
}

function MouseTracker() {}

MouseTracker.prototype = {

    positions: [],
    position: null,

    init: function init() {
        var this$1 = this;


        this.positions = [];
        this.position = null;

        var ticking = false;
        this.handler = function (e) {

            if (!ticking) {
                setTimeout(function () {

                    var time = Date.now(), length = this$1.positions.length;
                    if (length && (time - this$1.positions[length - 1].time > 100)) {
                        this$1.positions.splice(0, length);
                    }

                    this$1.positions.push({time: time, x: e.pageX, y: e.pageY});

                    if (this$1.positions.length > 5) {
                        this$1.positions.shift();
                    }

                    ticking = false;
                }, 5);
            }

            ticking = true;
        };

        doc.on('mousemove', this.handler);

    },

    cancel: function cancel() {
        if (this.handler) {
            doc.off('mousemove', this.handler);
        }
    },

    movesTo: function movesTo(target) {

        if (this.positions.length < 2) {
            return false;
        }

        var p = getDimensions(target),
            position = this.positions[this.positions.length - 1],
            prevPos = this.positions[0];

        if (p.left <= position.x && position.x <= p.right && p.top <= position.y && position.y <= p.bottom) {
            return false;
        }

        var points = [
            [{x: p.left, y: p.top}, {x: p.right, y: p.bottom}],
            [{x: p.right, y: p.top}, {x: p.left, y: p.bottom}]
        ];

        if (p.right <= position.x) {

        } else if (p.left >= position.x) {
            points[0].reverse();
            points[1].reverse();
        } else if (p.bottom <= position.y) {
            points[0].reverse();
        } else if (p.top >= position.y) {
            points[1].reverse();
        }

        return !!points.reduce(function (result, point) {
            return result + (slope(prevPos, point[0]) < slope(position, point[0]) && slope(prevPos, point[1]) > slope(position, point[1]));
        }, 0);
    }

};

function slope(a, b) {
    return (b.y - a.y) / (b.x - a.x);
}

var strats = {};

// concat strategy
strats.args =
strats.attrs =
strats.created =
strats.events =
strats.init =
strats.ready =
strats.connected =
strats.disconnected =
strats.destroy = function (parentVal, childVal) {

    parentVal = parentVal && !$$1.isArray(parentVal) ? [parentVal] : parentVal;

    return childVal
        ? parentVal
            ? parentVal.concat(childVal)
            : $$1.isArray(childVal)
                ? childVal
                : [childVal]
        : parentVal;
};

// update strategy
strats.update = function (parentVal, childVal) {
    return strats.args(parentVal, $$1.isFunction(childVal) ? {write: childVal} : childVal);
};

// property strategy
strats.props = function (parentVal, childVal) {

    if ($$1.isArray(childVal)) {
        childVal = childVal.reduce(function (value, key) {
            value[key] = String;
            return value;
        }, {});
    }

    return strats.methods(parentVal, childVal);
};

// extend strategy
strats.computed =
strats.defaults =
strats.methods = function (parentVal, childVal) {
    return childVal
        ? parentVal
            ? $$1.extend(true, {}, parentVal, childVal)
            : childVal
        : parentVal;
};

// default strategy
var defaultStrat = function (parentVal, childVal) {
    return isUndefined(childVal) ? parentVal : childVal;
};

function mergeOptions(parent, child) {

    var options = {}, key;

    if (child.mixins) {
        for (var i = 0, l = child.mixins.length; i < l; i++) {
            parent = mergeOptions(parent, child.mixins[i]);
        }
    }

    for (key in parent) {
        mergeKey(key);
    }

    for (key in child) {
        if (!hasOwn(parent, key)) {
            mergeKey(key);
        }
    }

    function mergeKey(key) {
        options[key] = (strats[key] || defaultStrat)(parent[key], child[key]);
    }

    return options;
}

var dirs = {
    x: ['width', 'left', 'right'],
    y: ['height', 'top', 'bottom']
};

function position(element, target, attach, targetAttach, offset, targetOffset, flip, boundary) {

    attach = getPos(attach);
    targetAttach = getPos(targetAttach);

    var flipped = {element: attach, target: targetAttach};

    if (!element) {
        return flipped;
    }

    var dim = getDimensions(element),
        targetDim = getDimensions(target),
        position = targetDim;

    moveTo(position, attach, dim, -1);
    moveTo(position, targetAttach, targetDim, 1);

    offset = getOffsets(offset, dim.width, dim.height);
    targetOffset = getOffsets(targetOffset, targetDim.width, targetDim.height);

    offset['x'] += targetOffset['x'];
    offset['y'] += targetOffset['y'];

    position.left += offset['x'];
    position.top += offset['y'];

    boundary = getDimensions(boundary || window);

    if (flip) {
        $$1.each(dirs, function (dir, ref) {
            var prop = ref[0];
            var align = ref[1];
            var alignFlip = ref[2];


            if (!(flip === true || ~flip.indexOf(dir))) {
                return;
            }

            var elemOffset = attach[dir] === align ? -dim[prop] : attach[dir] === alignFlip ? dim[prop] : 0,
                targetOffset = targetAttach[dir] === align ? targetDim[prop] : targetAttach[dir] === alignFlip ? -targetDim[prop] : 0;

            if (position[align] < boundary[align] || position[align] + dim[prop] > boundary[alignFlip]) {

                var newVal = position[align] + elemOffset + targetOffset - offset[dir] * 2;

                if (newVal >= boundary[align] && newVal + dim[prop] <= boundary[alignFlip]) {
                    position[align] = newVal;

                    ['element', 'target'].forEach(function (el) {
                        flipped[el][dir] = !elemOffset
                            ? flipped[el][dir]
                            : flipped[el][dir] === dirs[dir][1]
                                ? dirs[dir][2]
                                : dirs[dir][1];
                    });
                }
            }

        });
    }

    $$1__default(element).offset({left: position.left, top: position.top});

    return flipped;
}

function getDimensions(element) {

    element = toNode(element);

    var window = getWindow(element), top = window.pageYOffset, left = window.pageXOffset;

    if (!element.ownerDocument) {
        return {
            top: top,
            left: left,
            height: window.innerHeight,
            width: window.innerWidth,
            bottom: top + window.innerHeight,
            right: left + window.innerWidth,
        }
    }

    var display = false;
    if (!element.offsetHeight) {
        display = element.style.display;
        element.style.display = 'block';
    }

    var rect = element.getBoundingClientRect();

    if (display !== false) {
        element.style.display = display;
    }

    return {
        height: rect.height,
        width: rect.width,
        top: rect.top + top,
        left: rect.left + left,
        bottom: rect.bottom + top,
        right: rect.right + left,
    }
}

function offsetTop(element) {
    element = toNode(element);
    return element.getBoundingClientRect().top + getWindow(element).pageYOffset;
}

function getWindow(element) {
    return element && element.ownerDocument ? element.ownerDocument.defaultView : window;
}

function moveTo(position, attach, dim, factor) {
    $$1.each(dirs, function (dir, ref) {
        var prop = ref[0];
        var align = ref[1];
        var alignFlip = ref[2];

        if (attach[dir] === alignFlip) {
            position[align] += dim[prop] * factor;
        } else if (attach[dir] === 'center') {
            position[align] += dim[prop] * factor / 2;
        }
    });
}

function getPos(pos) {

    var x = /left|center|right/, y = /top|center|bottom/;

    pos = (pos || '').split(' ');

    if (pos.length === 1) {
        pos = x.test(pos[0])
            ? pos.concat(['center'])
            : y.test(pos[0])
                ? ['center'].concat(pos)
                : ['center', 'center'];
    }

    return {
        x: x.test(pos[0]) ? pos[0] : 'center',
        y: y.test(pos[1]) ? pos[1] : 'center'
    };
}

function getOffsets(offsets, width, height) {

    offsets = (offsets || '').split(' ');

    return {
        x: offsets[0] ? parseFloat(offsets[0]) * (offsets[0][offsets[0].length - 1] === '%' ? width / 100 : 1) : 0,
        y: offsets[1] ? parseFloat(offsets[1]) * (offsets[1][offsets[1].length - 1] === '%' ? height / 100 : 1) : 0
    };
}

function flipPosition(pos) {
    switch (pos) {
        case 'left':
            return 'right';
        case 'right':
            return 'left';
        case 'top':
            return 'bottom';
        case 'bottom':
            return 'top';
        default:
            return pos;
    }
}

/*
    Based on:
    Copyright (c) 2010-2016 Thomas Fuchs
    http://zeptojs.com/
*/

var touch = {};
var touchTimeout;
var tapTimeout;
var swipeTimeout;
var longTapTimeout;
var longTapDelay = 750;
var gesture;
var clicked;
function swipeDirection(x1, x2, y1, y2) {
    return Math.abs(x1 - x2) >= Math.abs(y1 - y2) ? (x1 - x2 > 0 ? 'Left' : 'Right') : (y1 - y2 > 0 ? 'Up' : 'Down');
}

function longTap() {
    longTapTimeout = null;
    if (touch.last) {
        if (touch.el !== undefined) { touch.el.trigger('longTap'); }
        touch = {};
    }
}

function cancelLongTap() {
    if (longTapTimeout) { clearTimeout(longTapTimeout); }
    longTapTimeout = null;
}

function cancelAll() {
    if (touchTimeout)   { clearTimeout(touchTimeout); }
    if (tapTimeout)     { clearTimeout(tapTimeout); }
    if (swipeTimeout)   { clearTimeout(swipeTimeout); }
    if (longTapTimeout) { clearTimeout(longTapTimeout); }
    touchTimeout = tapTimeout = swipeTimeout = longTapTimeout = null;
    touch = {};
}

ready(function () {
    var now, delta, deltaX = 0, deltaY = 0, firstTouch;

    if ('MSGesture' in window) {
        gesture = new MSGesture();
        gesture.target = document.body;
    }

    on(document, 'click', function () { return clicked = true; }, true);

    doc

        .on('MSGestureEnd gestureend', function (e) {

            var swipeDirectionFromVelocity = e.originalEvent.velocityX > 1 ? 'Right' : e.originalEvent.velocityX < -1 ? 'Left' : e.originalEvent.velocityY > 1 ? 'Down' : e.originalEvent.velocityY < -1 ? 'Up' : null;

            if (swipeDirectionFromVelocity && touch.el !== undefined) {
                touch.el.trigger('swipe');
                touch.el.trigger('swipe' + swipeDirectionFromVelocity);
            }
        })
        .on(pointerDown, function (e) {

            firstTouch = e.originalEvent.touches ? e.originalEvent.touches[0] : e;

            now = Date.now();
            delta = now - (touch.last || now);
            touch.el = $$1__default('tagName' in firstTouch.target ? firstTouch.target : firstTouch.target.parentNode);

            if (touchTimeout) { clearTimeout(touchTimeout); }

            touch.x1 = firstTouch.pageX;
            touch.y1 = firstTouch.pageY;

            if (delta > 0 && delta <= 250) { touch.isDoubleTap = true; }

            touch.last = now;
            longTapTimeout = setTimeout(longTap, longTapDelay);

            // adds the current touch contact for IE gesture recognition
            if (gesture && ( e.type == 'pointerdown' || e.type == 'touchstart' )) {
                gesture.addPointer(e.originalEvent.pointerId);
            }

            clicked = e.button > 0;

        })
        .on(pointerMove, function (e) {

            firstTouch = e.originalEvent.touches ? e.originalEvent.touches[0] : e;

            cancelLongTap();
            touch.x2 = firstTouch.pageX;
            touch.y2 = firstTouch.pageY;

            deltaX += Math.abs(touch.x1 - touch.x2);
            deltaY += Math.abs(touch.y1 - touch.y2);
        })
        .on(pointerUp, function () {

            cancelLongTap();

            // swipe
            if ((touch.x2 && Math.abs(touch.x1 - touch.x2) > 30) || (touch.y2 && Math.abs(touch.y1 - touch.y2) > 30)) {

                swipeTimeout = setTimeout(function () {
                    if (touch.el !== undefined) {
                        touch.el.trigger('swipe');
                        touch.el.trigger('swipe' + (swipeDirection(touch.x1, touch.x2, touch.y1, touch.y2)));
                    }
                    touch = {};
                }, 0);

                // normal tap
            } else if ('last' in touch) {

                // don't fire tap when delta position changed by more than 30 pixels,
                // for instance when moving to a point and back to origin
                if (isNaN(deltaX) || (deltaX < 30 && deltaY < 30)) {
                    // delay by one tick so we can cancel the 'tap' event if 'scroll' fires
                    // ('tap' fires before 'scroll')
                    tapTimeout = setTimeout(function () {

                        // trigger universal 'tap' with the option to cancelTouch()
                        // (cancelTouch cancels processing of single vs double taps for faster 'tap' response)
                        var event = $$1__default.Event('tap');
                        event.cancelTouch = cancelAll;

                        if (touch.el !== undefined) {
                            touch.el.trigger(event);
                        }

                        // trigger double tap immediately
                        if (touch.isDoubleTap) {
                            if (touch.el !== undefined) { touch.el.trigger('doubleTap'); }
                            touch = {};
                        }

                        // trigger single tap after 300ms of inactivity
                        else {
                            touchTimeout = setTimeout(function () {
                                touchTimeout = null;
                                if (touch.el !== undefined) {
                                    touch.el.trigger('singleTap');

                                    if (!clicked) {
                                        touch.el.trigger('click');
                                    }

                                }
                                touch = {};
                            }, 300);
                        }
                    });
                } else {
                    touch = {};
                }
                deltaX = deltaY = 0;
            }
        })
        // when the browser window loses focus,
        // for example when a modal dialog is shown,
        // cancel all ongoing events
        .on('touchcancel pointercancel', cancelAll);

    // scrolling the window indicates intention of the user
    // to scroll, not tap or swipe, so cancel all ongoing events
    win.on('scroll', cancelAll);
});

var touching = false;

on(document, 'touchstart', function () { return touching = true; }, true);
on(document, 'click', function () { return touching = false; });
on(document, 'touchcancel', function () { return touching = false; }, true);

function isTouch(e) {
    return touching || e.originalEvent && e.originalEvent.pointerType === 'touch';
}



var util = Object.freeze({
	win: win,
	doc: doc,
	docElement: docElement,
	isRtl: isRtl,
	isReady: isReady,
	ready: ready,
	on: on,
	off: off,
	transition: transition,
	Transition: Transition,
	animate: animate,
	Animation: Animation,
	isJQuery: isJQuery,
	isWithin: isWithin,
	attrFilter: attrFilter,
	removeClass: removeClass,
	createEvent: createEvent,
	isInView: isInView,
	getIndex: getIndex,
	isVoidElement: isVoidElement,
	Dimensions: Dimensions,
	query: query,
	Observer: Observer,
	requestAnimationFrame: requestAnimationFrame,
	cancelAnimationFrame: cancelAnimationFrame,
	hasPromise: hasPromise,
	hasTouch: hasTouch,
	pointerDown: pointerDown,
	pointerMove: pointerMove,
	pointerUp: pointerUp,
	pointerEnter: pointerEnter,
	pointerLeave: pointerLeave,
	transitionstart: transitionstart,
	transitionend: transitionend,
	animationstart: animationstart,
	animationend: animationend,
	getStyle: getStyle,
	getCssVar: getCssVar,
	fastdom: fastdom,
	$: $$1__default,
	bind: bind,
	hasOwn: hasOwn,
	promise: promise,
	classify: classify,
	hyphenate: hyphenate,
	camelize: camelize,
	isString: isString,
	isNumber: isNumber,
	isUndefined: isUndefined,
	isContextSelector: isContextSelector,
	getContextSelectors: getContextSelectors,
	toJQuery: toJQuery,
	toNode: toNode,
	toBoolean: toBoolean,
	toNumber: toNumber,
	toList: toList,
	toMedia: toMedia,
	coerce: coerce,
	toMs: toMs,
	swap: swap,
	ajax: $$1.ajax,
	each: $$1.each,
	extend: $$1.extend,
	map: $$1.map,
	merge: $$1.merge,
	isArray: $$1.isArray,
	isNumeric: $$1.isNumeric,
	isFunction: $$1.isFunction,
	isPlainObject: $$1.isPlainObject,
	MouseTracker: MouseTracker,
	mergeOptions: mergeOptions,
	position: position,
	getDimensions: getDimensions,
	offsetTop: offsetTop,
	flipPosition: flipPosition,
	isTouch: isTouch
});

function boot (UIkit) {

    if (Observer) {

        if (document.body) {

            init();

        } else {

            (new Observer(function () {

                if (document.body) {
                    this.disconnect();
                    init();
                }

            })).observe(document.documentElement, {childList: true, subtree: true});

        }

    } else {

        ready(function () {
            apply(document.body, UIkit.connect);
            on(document.documentElement, 'DOMNodeInserted', function (e) { return apply(e.target, UIkit.connect); });
            on(document.documentElement, 'DOMNodeRemoved', function (e) { return apply(e.target, UIkit.disconnect); });
        });

    }

    function init() {

        apply(document.body, UIkit.connect);

        (new Observer(function (mutations) { return mutations.forEach(function (mutation) {

                for (var i = 0; i < mutation.addedNodes.length; i++) {
                    apply(mutation.addedNodes[i], UIkit.connect)
                }

                for (i = 0; i < mutation.removedNodes.length; i++) {
                    apply(mutation.removedNodes[i], UIkit.disconnect)
                }

                UIkit.update('update', mutation.target, true);
            }); }
        )).observe(document.documentElement, {childList: true, subtree: true, characterData: true, attributes: true, attributeFilter: ['href']});
    }

    function apply(node, fn) {

        if (node.nodeType !== Node.ELEMENT_NODE || node.hasAttribute('uk-no-boot')) {
            return;
        }

        fn(node);
        node = node.firstChild;
        while (node) {
            var next = node.nextSibling;
            apply(node, fn);
            node = next;
        }
    }

}

function globalAPI (UIkit) {

    var DATA = UIkit.data;

    UIkit.use = function (plugin) {

        if (plugin.installed) {
            return;
        }

        plugin.call(null, this);
        plugin.installed = true;

        return this;
    };

    UIkit.mixin = function (mixin, component) {
        component = (isString(component) ? UIkit.components[component] : component) || this;
        mixin = mergeOptions({}, mixin);
        mixin.mixins = component.options.mixins;
        delete component.options.mixins;
        component.options = mergeOptions(mixin, component.options);
    };

    UIkit.extend = function (options) {

        options = options || {};

        var Super = this, name = options.name || Super.options.name;
        var Sub = createClass(name || 'UIkitComponent');

        Sub.prototype = Object.create(Super.prototype);
        Sub.prototype.constructor = Sub;
        Sub.options = mergeOptions(Super.options, options);

        Sub['super'] = Super;
        Sub.extend = Super.extend;

        return Sub;
    };

    UIkit.update = function (e, element, parents) {
        if ( parents === void 0 ) parents = false;


        e = createEvent(e || 'update');

        if (!element) {

            update(UIkit.instances, e);
            return;

        }

        element = toNode(element);

        if (parents) {

            do {

                update(element[DATA], e);
                element = element.parentNode;

            } while (element)

        } else {

            apply(element, function (element) { return update(element[DATA], e); });

        }

    };

    var container;
    Object.defineProperty(UIkit, 'container', {

        get: function get() {
            return container || document.body;
        },

        set: function set(element) {
            container = element;
        }

    });

}

function createClass(name) {
    return new Function(("return function " + (classify(name)) + " (options) { this._init(options); }"))();
}

function apply(node, fn) {

    if (node.nodeType !== Node.ELEMENT_NODE) {
        return;
    }

    fn(node);
    node = node.firstChild;
    while (node) {
        apply(node, fn);
        node = node.nextSibling;
    }
}

function update(data, e) {

    if (!data) {
        return;
    }

    for (var name in data) {
        if (data[name]._isReady) {
            data[name]._callUpdate(e);
        }
    }

}

function hooksAPI (UIkit) {

    UIkit.prototype._callHook = function (hook) {
        var this$1 = this;


        var handlers = this.$options[hook];

        if (handlers) {
            handlers.forEach(function (handler) { return handler.call(this$1); });
        }
    };

    UIkit.prototype._callReady = function () {

        if (this._isReady) {
            return;
        }

        this._isReady = true;
        this._callHook('ready');
        this._callUpdate();
    };

    UIkit.prototype._callConnected = function () {
        var this$1 = this;


        if (this._connected) {
            return;
        }

        if (!~UIkit.elements.indexOf(this.$options.el)) {
            UIkit.elements.push(this.$options.el);
        }

        UIkit.instances[this._uid] = this;

        this._initEvents();

        this._callHook('connected');
        this._connected = true;

        this._initObserver();

        if (!this._isReady) {
            ready(function () { return this$1._callReady(); });
        }

        this._callUpdate();
    };

    UIkit.prototype._callDisconnected = function () {

        if (!this._connected) {
            return;
        }

        if (this._observer) {
            this._observer.disconnect();
            this._observer = null;
        }

        var index = UIkit.elements.indexOf(this.$options.el);

        if (~index) {
            UIkit.elements.splice(index, 1);
        }

        delete UIkit.instances[this._uid];

        this._initEvents(true);
        this._callHook('disconnected');

        this._connected = false;

    };

    UIkit.prototype._callUpdate = function (e) {
        var this$1 = this;


        e = createEvent(e || 'update');

        if (e.type === 'update') {
            this._computeds = {};
        }

        var updates = this.$options.update;

        if (!updates) {
            return;
        }

        updates.forEach(function (update, i) {

            if (e.type !== 'update' && (!update.events || !~update.events.indexOf(e.type))) {
                return;
            }

            if (e.sync) {

                if (update.read) {
                    update.read.call(this$1, e);
                }

                if (update.write) {
                    update.write.call(this$1, e);
                }

                return;

            }

            if (update.read && !~fastdom.reads.indexOf(this$1._frames.reads[i])) {
                this$1._frames.reads[i] = fastdom.measure(function () { return update.read.call(this$1, e); });
            }

            if (update.write && !~fastdom.writes.indexOf(this$1._frames.writes[i])) {
                this$1._frames.writes[i] = fastdom.mutate(function () { return update.write.call(this$1, e); });
            }

        });

    };

}

function stateAPI (UIkit) {

    var uid = 0;

    UIkit.prototype.props = {};

    UIkit.prototype._init = function (options) {

        options = options || {};
        options = this.$options = mergeOptions(this.constructor.options, options, this);

        this.$el = null;
        this.$name = UIkit.prefix + hyphenate(this.$options.name);
        this.$props = {};

        this._uid = uid++;
        this._initData();
        this._initMethods();
        this._initComputeds();
        this._callHook('created');

        this._frames = {reads: {}, writes: {}};

        if (options.el) {
            this.$mount(options.el);
        }
    };

    UIkit.prototype._initData = function () {
        var this$1 = this;


        var defaults = $$1.extend(true, {}, this.$options.defaults),
            data = this.$options.data || {},
            args = this.$options.args || [],
            props = this.$options.props || {};

        if (!defaults) {
            return;
        }

        if (args.length && $$1.isArray(data)) {
            data = data.slice(0, args.length).reduce(function (data, value, index) {
                if ($$1.isPlainObject(value)) {
                    $$1.extend(data, value);
                } else {
                    data[args[index]] = value;
                }
                return data;
            }, {});
        }

        for (var key in defaults) {
            this$1.$props[key] = this$1[key] = hasOwn(data, key) ? coerce(props[key], data[key], this$1.$options.el) : defaults[key];
        }
    };

    UIkit.prototype._initMethods = function () {
        var this$1 = this;


        var methods = this.$options.methods;

        if (methods) {
            for (var key in methods) {
                this$1[key] = bind(methods[key], this$1);
            }
        }
    };

    UIkit.prototype._initComputeds = function () {
        var this$1 = this;


        var computed = this.$options.computed;

        this._computeds = {};

        if (computed) {
            for (var key in computed) {
                registerComputed(this$1, key, computed[key]);
            }
        }
    };

    UIkit.prototype._initProps = function (props) {
        var this$1 = this;


        this._computeds = {};
        $$1.extend(this.$props, props || this._getProps());

        var exclude = [this.$options.computed, this.$options.methods];
        for (var key in this$1.$props) {
            if (notIn(exclude, key)) {
                this$1[key] = this$1.$props[key];
            }
        }
    };

    UIkit.prototype._initEvents = function (unbind) {
        var this$1 = this;


        var events = this.$options.events;

        if (events) {
            events.forEach(function (event) {

                if (!hasOwn(event, 'handler')) {
                    for (var key in event) {
                        registerEvent(this$1, unbind, event[key], key);
                    }
                } else {
                    registerEvent(this$1, unbind, event);
                }

            });
        }
    };

    UIkit.prototype._initObserver = function () {
        var this$1 = this;


        if (this._observer || !this.$options.props || !this.$options.attrs || !Observer) {
            return;
        }

        this._observer = new Observer(function (mutations) {

            var data = this$1._getProps(true),
                changed = mutations
                    .map(function (mutation) { return camelize(mutation.attributeName); })
                    .some(function (key) { return !equals(data[key], this$1.$props[key]); });

            if (changed) {
                this$1.$reset(data);
            }

        });

        this._observer.observe(this.$options.el, {
            attributes: true,
            attributeFilter: Object.keys(this.$options.props).map(function (key) { return hyphenate(key); })
        });
    };

    UIkit.prototype._getProps = function (attrs) {
        if ( attrs === void 0 ) attrs = false;


        var data = {},
            el = this.$el[0],
            args = this.$options.args || [],
            props = this.$options.props || {},
            options = el.getAttribute(this.$name) || el.getAttribute(("data-" + (this.$name))),
            key, prop;

        if (!props) {
            return data;
        }

        for (key in props) {
            prop = hyphenate(key);
            if (el.hasAttribute(prop)) {

                var value = coerce(props[key], el.getAttribute(prop), el);

                if (prop === 'target' && (!value || value.lastIndexOf('_', 0) === 0)) {
                    continue;
                }

                data[key] = value;
            }
        }

        if (attrs || !options) {
            return data;
        }

        if (options[0] === '{') {
            try {
                options = JSON.parse(options);
            } catch (e) {
                console.warn("Invalid JSON.");
                options = {};
            }
        } else if (args.length && !~options.indexOf(':')) {
            options = (( obj = {}, obj[args[0]] = options, obj ));
            var obj;
        } else {
            var tmp = {};
            options.split(';').forEach(function (option) {
                var ref = option.split(/:(.+)/);
                var key = ref[0];
                var value = ref[1];
                if (key && value) {
                    tmp[key.trim()] = value.trim();
                }
            });
            options = tmp;
        }

        for (key in options || {}) {
            prop = camelize(key);
            if (props[prop] !== undefined) {
                data[prop] = coerce(props[prop], options[key], el);
            }
        }

        return data;
    };

}

function registerComputed(component, key, cb) {
    Object.defineProperty(component, key, {

        enumerable: true,

        get: function get() {

            if (!hasOwn(component._computeds, key)) {
                component._computeds[key] = cb.call(component);
            }

            return component._computeds[key];
        },

        set: function set(value) {
            component._computeds[key] = value;
        }

    });
}

function registerEvent(component, unbind, event, key) {

    if (!$$1.isPlainObject(event)) {
        event = ({name: key, handler: event});
    }

    var name = event.name;
    var el = event.el;
    var delegate = event.delegate;
    var self = event.self;
    var filter = event.filter;
    var handler = event.handler;

    el = el && el.call(component) || component.$el;

    name += "." + (component.$options.name) + "." + (component._uid);

    if (unbind) {

        el.off(name);

    } else {

        if (filter && !filter.call(component)) {
            return;
        }

        handler = isString(handler) ? component[handler] : bind(handler, component);

        if (self) {
            var fn = handler;
            handler = function (e) {

                if (!el.is(e.target)) {
                    return;
                }

                return fn.call(component, e);
            }
        }

        if (delegate) {
            el.on(name, isString(delegate) ? delegate : delegate.call(component), handler);
        } else {
            el.on(name, handler);
        }
    }

}

function notIn(options, key) {
    return options.every(function (arr) { return !arr || !hasOwn(arr, key); });
}

function equals(a, b) {
    return isUndefined(a) || a === b || isJQuery(a) && isJQuery(b) && a.is(b);
}

function instanceAPI (UIkit) {

    var DATA = UIkit.data;

    UIkit.prototype.$mount = function (el) {

        var name = this.$options.name;

        if (!el[DATA]) {
            el[DATA] = {};
        }

        if (el[DATA][name]) {
            console.warn(("Component \"" + name + "\" is already mounted on element: "), el);
            return;
        }

        el[DATA][name] = this;

        this.$el = $$1__default(el);

        this._initProps();

        this._callHook('init');

        if (document.documentElement.contains(el)) {
            this._callConnected();
        }
    };

    UIkit.prototype.$emit = function (e) {
        this._callUpdate(e);
    };

    UIkit.prototype.$emitSync = function (e) {
        this._callUpdate(createEvent(e || 'update', true, false, {sync: true}));
    };

    UIkit.prototype.$update = function (e, parents) {
        UIkit.update(e, this.$el, parents);
    };

    UIkit.prototype.$updateSync = function (e, parents) {
        this.$update(createEvent(e || 'update', true, false, {sync: true}), parents);
    };

    UIkit.prototype.$reset = function (data) {
        this._callDisconnected();
        this._initProps(data);
        this._callConnected();
    };

    UIkit.prototype.$destroy = function (remove) {
        if ( remove === void 0 ) remove = false;


        var el = this.$options.el;

        if (el) {
            this._callDisconnected();
        }

        this._callHook('destroy');

        if (!el || !el[DATA]) {
            return;
        }

        delete el[DATA][this.$options.name];

        if (!Object.keys(el[DATA]).length) {
            delete el[DATA];
        }

        if (remove) {
            this.$el.remove();
        }
    };

}

function componentAPI (UIkit) {

    var DATA = UIkit.data;

    UIkit.components = {};

    UIkit.component = function (id, options) {

        var name = camelize(id);

        if ($$1.isPlainObject(options)) {
            options.name = name;
            options = UIkit.extend(options);
        } else {
            options.options.name = name;
        }

        UIkit.components[name] = options;

        UIkit[name] = function (element, data) {
            var i = arguments.length, argsArray = Array(i);
            while ( i-- ) argsArray[i] = arguments[i];


            if ($$1.isPlainObject(element)) {
                return new UIkit.components[name]({data: element});
            }

            if (UIkit.components[name].options.functional) {
                return new UIkit.components[name]({data: [].concat( argsArray )});
            }

            return $$1__default(element).toArray().map(function (element) { return UIkit.getComponent(element, name) || new UIkit.components[name]({el: element, data: data || {}}); }
            )[0];
        };

        if (document.body && !options.options.functional) {
            UIkit[name](("[uk-" + id + "],[data-uk-" + id + "]"));
        }

        return UIkit.components[name];
    };

    UIkit.getComponents = function (element) { return element && (element = isJQuery(element) ? element[0] : element) && element[DATA] || {}; };
    UIkit.getComponent = function (element, name) { return UIkit.getComponents(element)[name]; };

    UIkit.connect = function (node) {

        var name;

        if (node[DATA]) {
            for (name in node[DATA]) {
                node[DATA][name]._callConnected();
            }
        }

        for (var i = 0; i < node.attributes.length; i++) {

            name = node.attributes[i].name;

            if (name.lastIndexOf('uk-', 0) === 0 || name.lastIndexOf('data-uk-', 0) === 0) {

                name = camelize(name.replace('data-uk-', '').replace('uk-', ''));

                if (UIkit[name]) {
                    UIkit[name](node);
                }
            }
        }

    };

    UIkit.disconnect = function (node) {
        for (var name in node[DATA]) {
            node[DATA][name]._callDisconnected();
        }
    }

}

var UIkit$1 = function (options) {
    this._init(options);
};

UIkit$1.util = util;
UIkit$1.data = '__uikit__';
UIkit$1.prefix = 'uk-';
UIkit$1.options = {};
UIkit$1.instances = {};
UIkit$1.elements = [];

globalAPI(UIkit$1);
hooksAPI(UIkit$1);
stateAPI(UIkit$1);
instanceAPI(UIkit$1);
componentAPI(UIkit$1);

var Class = {

    init: function init() {
        this.$el.addClass(this.$name);
    }

}

var Toggable = {

    props: {
        cls: Boolean,
        animation: 'list',
        duration: Number,
        origin: String,
        transition: String,
        queued: Boolean
    },

    defaults: {
        cls: false,
        animation: [false],
        duration: 200,
        origin: false,
        transition: 'linear',
        queued: false,

        initProps: {
            overflow: '',
            height: '',
            paddingTop: '',
            paddingBottom: '',
            marginTop: '',
            marginBottom: ''
        },

        hideProps: {
            overflow: 'hidden',
            height: 0,
            paddingTop: 0,
            paddingBottom: 0,
            marginTop: 0,
            marginBottom: 0
        }

    },

    computed: {

        hasAnimation: function hasAnimation() {
            return !!this.animation[0];
        },

        hasTransition: function hasTransition() {
            return this.hasAnimation && this.animation[0] === true;
        }

    },

    methods: {

        toggleElement: function toggleElement(targets, show, animate) {
            var this$1 = this;


            var queued = this.queued && this.hasAnimation, toggles, body = document.body, scroll = body.scrollTop,
                all = function (targets) { return promise.all(targets.toArray().map(function (el) { return this$1._toggleElement(el, show, animate); })).then(null, function () {}); },
                delay = function (targets) {
                    var def = all(targets);
                    queued = true;
                    body.scrollTop = scroll;
                    return def;
                };

            targets = $$1__default(targets);

            if (!queued || targets.length < 2) {
                return all(targets);
            }

            if (queued !== true) {
                return delay(targets.not(queued));
            }

            queued = targets.not(toggles = targets.filter(function (_, el) { return this$1.isToggled(el); }));

            return all(toggles).then(function () { return queued !== true && delay(queued); });
        },

        toggleNow: function toggleNow(targets, show) {
            var this$1 = this;

            return promise.all($$1__default(targets).toArray().map(function (el) { return this$1._toggleElement(el, show, false); })).then(null, function () {});
        },

        isToggled: function isToggled(el) {
            el = el && $$1__default(el) || this.$el;
            return this.cls ? el.hasClass(this.cls.split(' ')[0]) : !el.attr('hidden');
        },

        updateAria: function updateAria(el) {
            if (this.cls === false) {
                el.attr('aria-hidden', !this.isToggled(el));
            }
        },

        _toggleElement: function _toggleElement(el, show, animate) {
            var this$1 = this;


            el = $$1__default(el);

            if (Animation.inProgress(el)) {
                return Animation.cancel(el).then(function () { return this$1._toggleElement(el, show, animate); });
            }

            show = typeof show === 'boolean' ? show : !this.isToggled(el);

            var event = $$1__default.Event(("before" + (show ? 'show' : 'hide')));
            el.trigger(event, [this]);

            if (event.result === false) {
                return promise.reject();
            }

            var def = (animate === false || !this.hasAnimation
                ? this._toggleImmediate
                : this.hasTransition
                    ? this._toggleHeight
                    : this._toggleAnimation
            )(el, show);

            el.trigger(show ? 'show' : 'hide', [this]);
            return def.then(function () { return el.trigger(show ? 'shown' : 'hidden', [this$1]); });
        },

        _toggle: function _toggle(el, toggled) {

            el = $$1__default(el);

            if (this.cls) {
                el.toggleClass(this.cls, ~this.cls.indexOf(' ') ? undefined : toggled);
            } else {
                el.attr('hidden', !toggled);
            }

            el.find('[autofocus]:visible').focus();

            this.updateAria(el);
            UIkit$1.update(null, el);
        },

        _toggleImmediate: function _toggleImmediate(el, show) {
            this._toggle(el, show);
            return promise.resolve();
        },

        _toggleHeight: function _toggleHeight(el, show) {
            var this$1 = this;


            var inProgress = Transition.inProgress(el),
                inner = parseFloat(el.children().first().css('margin-top')) + parseFloat(el.children().last().css('margin-bottom')),
                height = el[0].offsetHeight ? el.height() + (inProgress ? 0 : inner) : 0,
                endHeight;

            return Transition.cancel(el).then(function () {

                if (!this$1.isToggled(el)) {
                    this$1._toggle(el, true);
                }

                el.css('height', '');
                endHeight = el.height() + (inProgress ? 0 : inner);
                el.height(height);

                return show
                    ? Transition.start(el, $$1.extend(this$1.initProps, {overflow: 'hidden', height: endHeight}), Math.round(this$1.duration * (1 - height / endHeight)), this$1.transition)
                    : Transition.start(el, this$1.hideProps, Math.round(this$1.duration * (height / endHeight)), this$1.transition).then(function () {
                            this$1._toggle(el, false);
                            el.css(this$1.initProps);
                        });

            });

        },

        _toggleAnimation: function _toggleAnimation(el, show) {
            var this$1 = this;


            if (show) {
                this._toggle(el, true);
                return Animation.in(el, this.animation[0], this.duration, this.origin);
            }

            return Animation.out(el, this.animation[1] || this.animation[0], this.duration, this.origin).then(function () { return this$1._toggle(el, false); });
        }

    }

};

var active;

var Modal = {

    mixins: [Class, Toggable],

    props: {
        clsPanel: String,
        selClose: String,
        escClose: Boolean,
        bgClose: Boolean,
        stack: Boolean,
        container: Boolean
    },

    defaults: {
        cls: 'uk-open',
        escClose: true,
        bgClose: true,
        overlay: true,
        stack: false,
        container: true
    },

    computed: {

        body: function body() {
            return $$1__default(document.body);
        },

        panel: function panel() {
            return this.$el.find(("." + (this.clsPanel)));
        },

        container: function container() {
            return this.$props.container === true && UIkit.container || this.$props.container && toJQuery(this.$props.container);
        },

        transitionElement: function transitionElement() {
            return this.panel;
        },

        transitionDuration: function transitionDuration() {
            return toMs(this.transitionElement.css('transition-duration'));
        }

    },

    events: [

        {

            name: 'click',

            delegate: function delegate() {
                return this.selClose;
            },

            handler: function handler(e) {
                e.preventDefault();
                this.hide();
            }

        },

        {

            name: 'toggle',

            handler: function handler(e) {
                e.preventDefault();
                this.toggle();
            }

        },

        {

            name: 'beforeshow',

            self: true,

            handler: function handler() {
                var this$1 = this;


                if (this.isToggled()) {
                    return false;
                }

                var prev = active && active !== this && active;

                active = this;

                if (prev) {
                    if (this.stack) {
                        this.prev = prev;
                    } else {
                        prev.hide().then(this.show);
                        return false;
                    }
                } else {
                    requestAnimationFrame(function () { return register(this$1.$options.name); });
                }

                if (!prev) {
                    this.scrollbarWidth = window.innerWidth - docElement[0].offsetWidth;
                    this.body.css('overflow-y', this.scrollbarWidth && this.overlay ? 'scroll' : '');
                }

                docElement.addClass(this.clsPage);

            }

        },

        {

            name: 'beforehide',

            self: true,

            handler: function handler() {

                if (!this.isToggled()) {
                    return false;
                }

                active = active && active !== this && active || this.prev;

                if (!active) {
                    deregister(this.$options.name);
                }

            }

        },

        {

            name: 'hidden',

            self: true,

            handler: function handler() {
                if (!active) {
                    docElement.removeClass(this.clsPage);
                    this.body.css('overflow-y', '');
                }
            }

        }

    ],

    methods: {

        toggle: function toggle() {
            return this.isToggled() ? this.hide() : this.show();
        },

        show: function show() {
            var this$1 = this;

            if (this.container && !this.$el.parent().is(this.container)) {
                this.$el.appendTo(this.container);
                return promise(function (resolve) { return requestAnimationFrame(function () { return resolve(this$1.show()); }
                    ); }
                )
            }

            return this.toggleNow(this.$el, true);
        },

        hide: function hide() {
            return this.toggleNow(this.$el, false);
        },

        getActive: function getActive() {
            return active;
        },

        _toggleImmediate: function _toggleImmediate(el, show) {
            var this$1 = this;

            this._toggle(el, show);

            return this.transitionDuration ? promise(function (resolve, reject) {

                if (this$1._transition) {
                    this$1.transitionElement.off(transitionend, this$1._transition.handler);
                    this$1._transition.reject();
                }

                this$1._transition = {
                    reject: reject,
                    handler: function () {
                        resolve();
                        this$1._transition = null;
                    }
                };

                this$1.transitionElement.one(transitionend, this$1._transition.handler);

            }) : promise.resolve();
        },
    }

}

function register(name) {
    doc.on(( obj = {}, obj[("click." + name)] = function (e) {
            if (active && active.bgClose && !e.isDefaultPrevented() && !isWithin(e.target, active.panel)) {
                active.hide();
            }
        }, obj[("keydown." + name)] = function (e) {
            if (e.keyCode === 27 && active && active.escClose) {
                e.preventDefault();
                active.hide();
            }
        }, obj ));
    var obj;
}

function deregister(name) {
    doc.off(("click." + name)).off(("keydown." + name));
}

var Position = {

    props: {
        pos: String,
        offset: null,
        flip: Boolean,
        clsPos: String
    },

    defaults: {
        pos: !isRtl ? 'bottom-left' : 'bottom-right',
        flip: true,
        offset: false,
        clsPos: ''
    },

    computed: {

        pos: function pos() {
            return (this.$props.pos + (!~this.$props.pos.indexOf('-') ? '-center' : '')).split('-');
        },

        dir: function dir() {
            return this.pos[0];
        },

        align: function align() {
            return this.pos[1];
        }

    },

    methods: {

        positionAt: function positionAt(element, target, boundary) {

            removeClass(element, ((this.clsPos) + "-(top|bottom|left|right)(-[a-z]+)?")).css({top: '', left: ''});

            var offset = toNumber(this.offset) || 0,
                axis = this.getAxis(),
                flipped = position(
                    element,
                    target,
                    axis === 'x' ? ((flipPosition(this.dir)) + " " + (this.align)) : ((this.align) + " " + (flipPosition(this.dir))),
                    axis === 'x' ? ((this.dir) + " " + (this.align)) : ((this.align) + " " + (this.dir)),
                    axis === 'x' ? ("" + (this.dir === 'left' ? -1 * offset : offset)) : (" " + (this.dir === 'top' ? -1 * offset : offset)),
                    null,
                    this.flip,
                    boundary
                );

            this.dir = axis === 'x' ? flipped.target.x : flipped.target.y;
            this.align = axis === 'x' ? flipped.target.y : flipped.target.x;

            element.toggleClass(((this.clsPos) + "-" + (this.dir) + "-" + (this.align)), this.offset === false);

        },

        getAxis: function getAxis() {
            return this.dir === 'top' || this.dir === 'bottom' ? 'y' : 'x';
        }

    }

}

function mixin (UIkit) {

    UIkit.mixin.class = Class;
    UIkit.mixin.modal = Modal;
    UIkit.mixin.position = Position;
    UIkit.mixin.toggable = Toggable;

}

function Accordion (UIkit) {

    UIkit.component('accordion', {

        mixins: [Class, Toggable],

        props: {
            targets: String,
            active: null,
            collapsible: Boolean,
            multiple: Boolean,
            toggle: String,
            content: String,
            transition: String
        },

        defaults: {
            targets: '> *',
            active: false,
            animation: [true],
            collapsible: true,
            multiple: false,
            clsOpen: 'uk-open',
            toggle: '> .uk-accordion-title',
            content: '> .uk-accordion-content',
            transition: 'ease'
        },

        computed: {

            items: function items() {
                var this$1 = this;

                var items = $$1__default(this.targets, this.$el);
                this._changed = !this._items || items.length !== this._items.length || items.toArray().some(function (el, i) { return el !== this$1._items.get(i); });
                return this._items = items;
            }

        },

        connected: function connected() {
            this.$emitSync();
        },

        events: [

            {

                name: 'click',

                delegate: function delegate() {
                    return ((this.targets) + " " + (this.$props.toggle));
                },

                handler: function handler(e) {
                    e.preventDefault();
                    this.toggle(this.items.find(this.$props.toggle).index(e.currentTarget));
                }

            }

        ],

        update: function update() {
            var this$1 = this;


            if (!this.items || !this._changed) {
                return;
            }

            this.items.each(function (i, el) {
                el = $$1__default(el);
                this$1.toggleNow(el.find(this$1.content), el.hasClass(this$1.clsOpen));
            });

            var active = this.active !== false && toJQuery(this.items.eq(Number(this.active))) || !this.collapsible && toJQuery(this.items.eq(0));
            if (active && !active.hasClass(this.clsOpen)) {
                this.toggle(active, false);
            }

        },

        methods: {

            toggle: function toggle(item, animate) {
                var this$1 = this;


                var index = getIndex(item, this.items),
                    active = this.items.filter(("." + (this.clsOpen)));

                item = this.items.eq(index);

                item.add(!this.multiple && active).each(function (i, el) {

                    el = $$1__default(el);

                    var isItem = el.is(item), state = isItem && !el.hasClass(this$1.clsOpen);

                    if (!state && isItem && !this$1.collapsible && active.length < 2) {
                        return;
                    }

                    el.toggleClass(this$1.clsOpen, state);

                    var content = el[0]._wrapper ? el[0]._wrapper.children().first() : el.find(this$1.content);

                    if (!el[0]._wrapper) {
                        el[0]._wrapper = content.wrap('<div>').parent().attr('hidden', state);
                    }

                    this$1._toggleImmediate(content, true);
                    this$1.toggleElement(el[0]._wrapper, state, animate).then(function () {
                        if (el.hasClass(this$1.clsOpen) === state) {

                            if (!state) {
                                this$1._toggleImmediate(content, false);
                            }

                            el[0]._wrapper = null;
                            content.unwrap();
                        }
                    });

                })
            }

        }

    });

}

function Alert (UIkit) {

    UIkit.component('alert', {

        mixins: [Class, Toggable],

        args: 'animation',

        props: {
            close: String
        },

        defaults: {
            animation: [true],
            close: '.uk-alert-close',
            duration: 150,
            hideProps: {opacity: 0}
        },

        events: [

            {

                name: 'click',

                delegate: function delegate() {
                    return this.close;
                },

                handler: function handler(e) {
                    e.preventDefault();
                    this.closeAlert();
                }

            }

        ],

        methods: {

            closeAlert: function closeAlert() {
                var this$1 = this;

                this.toggleElement(this.$el).then(function () { return this$1.$destroy(true); });
            }

        }

    });

}

function Cover (UIkit) {

    UIkit.component('cover', {

        mixins: [Class],

        props: {
            automute: Boolean,
            width: Number,
            height: Number
        },

        defaults: {automute: true},

        computed: {

            el: function el() {
                return this.$el[0];
            },

            parent: function parent() {
                return this.$el.parent()[0];
            }

        },

        ready: function ready() {

            if (!this.$el.is('iframe')) {
                return;
            }

            this.$el.css('pointerEvents', 'none');

            if (this.automute) {

                var src = this.$el.attr('src');

                this.$el
                    .attr('src', ("" + src + (~src.indexOf('?') ? '&' : '?') + "enablejsapi=1&api=1"))
                    .on('load', function (ref) {
                        var target = ref.target;

                        return target.contentWindow.postMessage('{"event": "command", "func": "mute", "method":"setVolume", "value":0}', '*');
                });
            }
        },

        update: {

            write: function write() {

                if (this.el.offsetHeight === 0) {
                    return;
                }

                this.$el
                    .css({width: '', height: ''})
                    .css(Dimensions.cover(
                        {width: this.width || this.el.clientWidth, height: this.height || this.el.clientHeight},
                        {width: this.parent.offsetWidth, height: this.parent.offsetHeight}
                    ));

            },

            events: ['load', 'resize']

        },

        events: {

            loadedmetadata: function loadedmetadata() {
                this.$emit();
            }

        }

    });

}

function Drop (UIkit) {

    var active;

    doc.on('click', function (e) {
        var prev;
        while (active && active !== prev && !isWithin(e.target, active.$el) && (!active.toggle || !isWithin(e.target, active.toggle.$el))) {
            prev = active;
            active.hide(false);
        }
    });

    UIkit.component('drop', {

        mixins: [Position, Toggable],

        args: 'pos',

        props: {
            mode: 'list',
            toggle: Boolean,
            boundary: 'jQuery',
            boundaryAlign: Boolean,
            delayShow: Number,
            delayHide: Number,
            clsDrop: String
        },

        defaults: {
            mode: ['click', 'hover'],
            toggle: '- :first',
            boundary: window,
            boundaryAlign: false,
            delayShow: 0,
            delayHide: 800,
            clsDrop: false,
            hoverIdle: 200,
            animation: ['uk-animation-fade'],
            cls: 'uk-open'
        },

        init: function init() {
            this.tracker = new MouseTracker();
            this.clsDrop = this.clsDrop || ("uk-" + (this.$options.name));
            this.clsPos = this.clsDrop;

            this.$el.addClass(this.clsDrop);
        },

        ready: function ready() {

            this.updateAria(this.$el);

            if (this.toggle) {
                this.toggle = UIkit.toggle(query(this.toggle, this.$el), {target: this.$el, mode: this.mode});
            }

        },

        events: [

            {

                name: 'click',

                delegate: function delegate() {
                    return ("." + (this.clsDrop) + "-close");
                },

                handler: function handler(e) {
                    e.preventDefault();
                    this.hide(false);
                }

            },

            {

                name: 'click',

                delegate: function delegate() {
                    return 'a[href^="#"]';
                },

                handler: function handler(e) {

                    if (e.isDefaultPrevented()) {
                        return;
                    }

                    var id = $(e.target).attr('href');

                    if (id.length === 1) {
                        e.preventDefault();
                    }

                    if (id.length === 1 || !isWithin(id, this.$el)) {
                        this.hide(false);
                    }
                }

            },

            {

                name: 'toggle',

                handler: function handler(e, toggle) {

                    if (toggle && !this.$el.is(toggle.target)) {
                        return;
                    }

                    e.preventDefault();

                    if (this.isToggled()) {
                        this.hide(false);
                    } else {
                        this.show(toggle, false);
                    }
                }

            },

            {

                name: pointerEnter,

                filter: function filter() {
                    return ~this.mode.indexOf('hover');
                },

                handler: function handler(e) {

                    if (isTouch(e)) {
                        return;
                    }

                    if (active
                        && active !== this
                        && active.toggle
                        && ~active.toggle.mode.indexOf('hover')
                        && !isWithin(e.target, active.$el)
                        && !isWithin(e.target, active.toggle.$el)
                    ) {
                        active.hide(false);
                    }

                    e.preventDefault();
                    this.show(this.toggle);
                }

            },

            {

                name: 'toggleshow',

                handler: function handler(e, toggle) {

                    if (toggle && !this.$el.is(toggle.target)) {
                        return;
                    }

                    e.preventDefault();
                    this.show(toggle || this.toggle);
                }

            },

            {

                name: ("togglehide " + pointerLeave),

                handler: function handler(e, toggle) {

                    if (isTouch(e) || toggle && !this.$el.is(toggle.target)) {
                        return;
                    }

                    e.preventDefault();

                    if (this.toggle && ~this.toggle.mode.indexOf('hover')) {
                        this.hide();
                    }
                }

            },

            {

                name: 'beforeshow',

                self: true,

                handler: function handler() {
                    this.clearTimers();
                }

            },

            {

                name: 'show',

                self: true,

                handler: function handler() {
                    this.tracker.init();
                    this.toggle.$el.addClass(this.cls).attr('aria-expanded', 'true');
                }

            },

            {

                name: 'beforehide',

                self: true,

                handler: function handler() {
                    this.clearTimers();
                }

            },

            {

                name: 'hide',

                handler: function handler(ref) {
                    var target = ref.target;


                    if (!this.$el.is(target)) {
                        active = active === null && isWithin(target, this.$el) && this.isToggled() ? this : active;
                        return;
                    }

                    active = this.isActive() ? null : active;
                    this.toggle.$el.removeClass(this.cls).attr('aria-expanded', 'false').blur().find('a, button').blur();
                    this.tracker.cancel();
                }

            }

        ],

        update: {

            write: function write() {

                if (!this.$el.hasClass(this.cls)) {
                    return;
                }

                removeClass(this.$el, ((this.clsDrop) + "-(stack|boundary)")).css({top: '', left: ''});

                this.$el.toggleClass(((this.clsDrop) + "-boundary"), this.boundaryAlign);

                var boundary = getDimensions(this.boundary), alignTo = this.boundaryAlign ? boundary : getDimensions(this.toggle.$el);

                if (this.align === 'justify') {
                    var prop = this.getAxis() === 'y' ? 'width' : 'height';
                    this.$el.css(prop, alignTo[prop]);
                } else if (this.$el.outerWidth() > Math.max(boundary.right - alignTo.left, alignTo.right - boundary.left)) {
                    this.$el.addClass(((this.clsDrop) + "-stack"));
                    this.$el.trigger('stack', [this]);
                }

                this.positionAt(this.$el, this.boundaryAlign ? this.boundary : this.toggle.$el, this.boundary);

            },

            events: ['resize']

        },

        methods: {

            show: function show(toggle, delay) {
                var this$1 = this;
                if ( delay === void 0 ) delay = true;


                var show = function () { return !this$1.isToggled() && this$1.toggleElement(this$1.$el, true); },
                    tryShow = function () {

                    this$1.toggle = toggle || this$1.toggle;

                    this$1.clearTimers();

                    if (this$1.isActive()) {
                        return;
                    } else if (delay && active && active !== this$1 && active.isDelaying) {
                        this$1.showTimer = setTimeout(this$1.show, 10);
                        return;
                    } else if (this$1.isParentOf(active)) {

                        if (active.hideTimer) {
                            active.hide(false);
                        } else {
                            return;
                        }

                    } else if (active && !this$1.isChildOf(active) && !this$1.isParentOf(active)) {
                        var prev;
                        while (active && active !== prev) {
                            prev = active;
                            active.hide(false);
                        }
                    }

                    if (delay && this$1.delayShow) {
                        this$1.showTimer = setTimeout(show, this$1.delayShow);
                    } else {
                        show();
                    }

                    active = this$1;
                };

                if (toggle && this.toggle && !this.toggle.$el.is(toggle.$el)) {

                    this.$el.one('hide', tryShow);
                    this.hide(false);

                } else {
                    tryShow();
                }
            },

            hide: function hide(delay) {
                var this$1 = this;
                if ( delay === void 0 ) delay = true;


                var hide = function () { return this$1.toggleNow(this$1.$el, false); };

                this.clearTimers();

                this.isDelaying = this.tracker.movesTo(this.$el);

                if (delay && this.isDelaying) {
                    this.hideTimer = setTimeout(this.hide, this.hoverIdle);
                } else if (delay && this.delayHide) {
                    this.hideTimer = setTimeout(hide, this.delayHide);
                } else {
                    hide();
                }
            },

            clearTimers: function clearTimers() {
                clearTimeout(this.showTimer);
                clearTimeout(this.hideTimer);
                this.showTimer = null;
                this.hideTimer = null;
                this.isDelaying = false;
            },

            isActive: function isActive() {
                return active === this;
            },

            isChildOf: function isChildOf(drop) {
                return drop && drop !== this && isWithin(this.$el, drop.$el);
            },

            isParentOf: function isParentOf(drop) {
                return drop && drop !== this && isWithin(drop.$el, this.$el);
            }

        }

    });

    UIkit.drop.getActive = function () { return active; };
}

function Dropdown (UIkit) {

    UIkit.component('dropdown', UIkit.components.drop.extend({name: 'dropdown'}));

}

function FormCustom (UIkit) {

    UIkit.component('form-custom', {

        mixins: [Class],

        args: 'target',

        props: {
            target: Boolean
        },

        defaults: {
            target: false
        },

        computed: {

            input: function input() {
                return this.$el.find(':input:first');
            },

            state: function state() {
                return this.input.next();
            },

            target: function target() {
                return this.$props.target && query(this.$props.target === true ? '> :input:first + :first' : this.$props.target, this.$el)
            }

        },

        connected: function connected() {
            this.input.trigger('change');
        },

        events: [

            {

                name: 'focus blur mouseenter mouseleave',

                delegate: ':input:first',

                handler: function handler(ref) {
                    var type = ref.type;

                    this.state.toggleClass(("uk-" + (~['focus', 'blur'].indexOf(type) ? 'focus' : 'hover')), ~['focus', 'mouseenter'].indexOf(type));
                }

            },

            {

                name: 'change',

                handler: function handler() {
                    this.target && this.target[this.target.is(':input') ? 'val' : 'text'](
                        this.input[0].files && this.input[0].files[0]
                            ? this.input[0].files[0].name
                            : this.input.is('select')
                                ? this.input.find('option:selected').text()
                                : this.input.val()
                    );
                }

            }

        ]

    });

}

function Gif (UIkit) {

    UIkit.component('gif', {

        update: {

            read: function read() {

                var inview = isInView(this.$el);

                if (!this.isInView && inview) {
                    this.$el[0].src = this.$el[0].src;
                }

                this.isInView = inview;
            },

            events: ['scroll', 'load', 'resize']
        }

    });

}

function Grid (UIkit) {

    UIkit.component('grid', UIkit.components.margin.extend({

        mixins: [Class],

        name: 'grid',

        defaults: {
            margin: 'uk-grid-margin',
            clsStack: 'uk-grid-stack'
        },

        update: {

            write: function write() {

                this.$el.toggleClass(this.clsStack, this.stacks);

            },

            events: ['load', 'resize']

        }

    }));

}

function HeightMatch (UIkit) {

    UIkit.component('height-match', {

        args: 'target',

        props: {
            target: String,
            row: Boolean
        },

        defaults: {
            target: '> *',
            row: true
        },

        computed: {

            elements: function elements() {
                return $$1__default(this.target, this.$el);
            }

        },

        update: {

            read: function read() {
                var this$1 = this;


                var lastOffset = false;

                this.elements.css('minHeight', '');

                this.rows = !this.row
                    ? [this.match(this.elements)]
                    : this.elements.toArray().reduce(function (rows, el) {

                        if (lastOffset !== el.offsetTop) {
                            rows.push([el]);
                        } else {
                            rows[rows.length - 1].push(el);
                        }

                        lastOffset = el.offsetTop;

                        return rows;

                    }, []).map(function (elements) { return this$1.match($$1__default(elements)); });
            },

            write: function write() {

                this.rows.forEach(function (ref) {
                        var height = ref.height;
                        var elements = ref.elements;

                        return elements && elements.each(function (_, el) { return el.style.minHeight = height + "px"; }
                    );
                }
                );

            },

            events: ['resize']

        },

        methods: {

            match: function match(elements) {

                if (elements.length < 2) {
                    return {};
                }

                var max = 0, heights = [];

                elements = elements
                    .each(function (_, el) {

                        var $el, style, hidden;

                        if (el.offsetHeight === 0) {
                            $el = $$1__default(el);
                            style = $el.attr('style') || null;
                            hidden = $el.attr('hidden') || null;

                            $el.attr({
                                style: (style + ";display:block !important;"),
                                hidden: null
                            });
                        }

                        max = Math.max(max, el.offsetHeight);
                        heights.push(el.offsetHeight);

                        if ($el) {
                            $el.attr({style: style, hidden: hidden});
                        }

                    })
                    .filter(function (i) { return heights[i] < max; });

                return {height: max, elements: elements};
            }
        }

    });

}

function HeightViewport (UIkit) {

    UIkit.component('height-viewport', {

        props: {
            expand: Boolean,
            offsetTop: Boolean,
            offsetBottom: Boolean
        },

        defaults: {
            expand: false,
            offsetTop: false,
            offsetBottom: false
        },

        connected: function connected() {
            this.$emitSync();
        },

        update: {

            write: function write() {

                this.$el.css('boxSizing', 'border-box');

                var viewport = window.innerHeight, height, offset = 0;

                if (this.expand) {

                    this.$el.css({height: '', minHeight: ''});

                    var diff = viewport - document.documentElement.offsetHeight;

                    if (diff > 0) {
                        this.$el.css('min-height', height = this.$el.outerHeight() + diff)
                    }

                } else {

                    var top = offsetTop(this.$el);

                    if (top < viewport && this.offsetTop) {
                        offset += top;
                    }

                    if (this.offsetBottom === true) {

                        offset += this.$el.next().outerHeight() || 0;

                    } else if ($$1.isNumeric(this.offsetBottom)) {

                        offset += (viewport / 100) * this.offsetBottom;

                    } else if (this.offsetBottom && this.offsetBottom.substr(-2) === 'px') {

                        offset += parseFloat(this.offsetBottom);

                    } else if (isString(this.offsetBottom)) {

                        var el = query(this.offsetBottom, this.$el);
                        offset += el && el.outerHeight() || 0;

                    }

                    this.$el.css('min-height', height = offset ? ("calc(100vh - " + offset + "px)") : '100vh');

                }

                // IE 10-11 fix (min-height on a flex container won't apply to its flex items)
                this.$el.css('height', '');
                if (height && viewport - offset >= this.$el.outerHeight()) {
                    this.$el.css('height', height);
                }

            },

            events: ['load', 'resize']

        }

    });

}

function Hover (UIkit) {

    ready(function () {

        if (!hasTouch) {
            return;
        }

        var cls = 'uk-hover';

        docElement.on('tap', function (ref) {
            var target = ref.target;

            return $$1__default(("." + cls)).filter(function (_, el) { return !isWithin(target, el); }).removeClass(cls);
        });

        Object.defineProperty(UIkit, 'hoverSelector', {

            set: function set(selector) {
                docElement.on('tap', selector, function (ref) {
                    var currentTarget = ref.currentTarget;

                    return currentTarget.classList.add(cls);
                });
            }

        });

        UIkit.hoverSelector = '.uk-animation-toggle, .uk-transition-toggle, [uk-hover]';

    });

}

var closeIcon = "<svg width=\"14\" height=\"14\" viewBox=\"0 0 14 14\" xmlns=\"http://www.w3.org/2000/svg\"><line fill=\"none\" stroke=\"#000\" stroke-width=\"1.1\" x1=\"1\" y1=\"1\" x2=\"13\" y2=\"13\"></line><line fill=\"none\" stroke=\"#000\" stroke-width=\"1.1\" x1=\"13\" y1=\"1\" x2=\"1\" y2=\"13\"></line></svg>";

var closeLarge = "<svg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><line fill=\"none\" stroke=\"#000\" stroke-width=\"1.4\" x1=\"1\" y1=\"1\" x2=\"19\" y2=\"19\"></line><line fill=\"none\" stroke=\"#000\" stroke-width=\"1.4\" x1=\"19\" y1=\"1\" x2=\"1\" y2=\"19\"></line></svg>";

var navbarToggleIcon = "<svg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><rect y=\"9\" width=\"20\" height=\"2\"></rect><rect y=\"3\" width=\"20\" height=\"2\"></rect><rect y=\"15\" width=\"20\" height=\"2\"></rect></svg>";

var overlayIcon = "<svg width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" xmlns=\"http://www.w3.org/2000/svg\"><rect x=\"19\" y=\"0\" width=\"1\" height=\"40\"></rect><rect x=\"0\" y=\"19\" width=\"40\" height=\"1\"></rect></svg>";

var paginationNext = "<svg width=\"7\" height=\"12\" viewBox=\"0 0 7 12\" xmlns=\"http://www.w3.org/2000/svg\"><polyline fill=\"none\" stroke=\"#000\" stroke-width=\"1.2\" points=\"1 1 6 6 1 11\"></polyline></svg>";

var paginationPrevious = "<svg width=\"7\" height=\"12\" viewBox=\"0 0 7 12\" xmlns=\"http://www.w3.org/2000/svg\"><polyline fill=\"none\" stroke=\"#000\" stroke-width=\"1.2\" points=\"6 1 1 6 6 11\"></polyline></svg>";

var searchIcon = "<svg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><circle fill=\"none\" stroke=\"#000\" stroke-width=\"1.1\" cx=\"9\" cy=\"9\" r=\"7\"></circle><path fill=\"none\" stroke=\"#000\" stroke-width=\"1.1\" d=\"M14,14 L18,18 L14,14 Z\"></path></svg>";

var searchLarge = "<svg width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" xmlns=\"http://www.w3.org/2000/svg\"><circle fill=\"none\" stroke=\"#000\" stroke-width=\"1.8\" cx=\"17.5\" cy=\"17.5\" r=\"16.5\"></circle><line fill=\"none\" stroke=\"#000\" stroke-width=\"1.8\" x1=\"38\" y1=\"39\" x2=\"29\" y2=\"30\"></line></svg>";

var searchNavbar = "<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\"><circle fill=\"none\" stroke=\"#000\" stroke-width=\"1.1\" cx=\"10.5\" cy=\"10.5\" r=\"9.5\"/><line fill=\"none\" stroke=\"#000\" stroke-width=\"1.1\" x1=\"23\" y1=\"23\" x2=\"17\" y2=\"17\"/></svg>";

var slidenavNext = "<svg width=\"11\" height=\"20\" viewBox=\"0 0 11 20\" xmlns=\"http://www.w3.org/2000/svg\"><polyline fill=\"none\" stroke=\"#000\" stroke-width=\"1.2\" points=\"1 1 10 10 1 19\"></polyline></svg>";

var slidenavNextLarge = "<svg width=\"18\" height=\"34\" viewBox=\"0 0 18 34\" xmlns=\"http://www.w3.org/2000/svg\"><polyline fill=\"none\" stroke=\"#000\" stroke-width=\"1.4\" points=\"1 1 17 17 1 33\"></polyline></svg>";

var slidenavPrevious = "<svg width=\"11\" height=\"20\" viewBox=\"0 0 11 20\" xmlns=\"http://www.w3.org/2000/svg\"><polyline fill=\"none\" stroke=\"#000\" stroke-width=\"1.2\" points=\"10 1 1 10 10 19\"></polyline></svg>";

var slidenavPreviousLarge = "<svg width=\"18\" height=\"34\" viewBox=\"0 0 18 34\" xmlns=\"http://www.w3.org/2000/svg\"><polyline fill=\"none\" stroke=\"#000\" stroke-width=\"1.4\" points=\"17 1 1 17 17 33\"></polyline></svg>";

var spinner = "<svg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" xmlns=\"http://www.w3.org/2000/svg\"><circle fill=\"none\" stroke=\"#000\" cx=\"15\" cy=\"15\" r=\"14\"></circle></svg>";

var totop = "<svg width=\"18\" height=\"10\" viewBox=\"0 0 18 10\" xmlns=\"http://www.w3.org/2000/svg\"><polyline fill=\"none\" stroke=\"#000\" stroke-width=\"1.2\" points=\"1 9 9 1 17 9 \"></polyline></svg>";

function Icon (UIkit) {

    var parsed = {},
        icons = {
            spinner: spinner,
            totop: totop,
            'close-icon': closeIcon,
            'close-large': closeLarge,
            'navbar-toggle-icon': navbarToggleIcon,
            'overlay-icon': overlayIcon,
            'pagination-next': paginationNext,
            'pagination-previous': paginationPrevious,
            'search-icon': searchIcon,
            'search-large': searchLarge,
            'search-navbar': searchNavbar,
            'slidenav-next': slidenavNext,
            'slidenav-next-large': slidenavNextLarge,
            'slidenav-previous': slidenavPrevious,
            'slidenav-previous-large': slidenavPreviousLarge
        };

    UIkit.component('icon', UIkit.components.svg.extend({

        mixins: [Class],

        name: 'icon',

        args: 'icon',

        props: ['icon'],

        defaults: {exclude: ['id', 'style', 'class', 'src']},

        init: function init() {
            this.$el.addClass('uk-icon');

            if (isRtl) {
                this.icon = swap(swap(this.icon, 'left', 'right'), 'previous', 'next');
            }
        },

        update: {

            read: function read() {

                if (this.delay) {
                    var icon = this.getIcon();

                    if (icon) {
                        this.delay(icon);
                    }
                }
            },

            events: ['load']

        },

        methods: {

            getSvg: function getSvg() {
                var this$1 = this;


                var icon = this.getIcon();

                if (!icon) {

                    if (document.readyState !== 'complete') {
                        return promise(function (resolve) {
                            this$1.delay = resolve;
                        });
                    }

                    return promise.reject('Icon not found.');

                }

                return promise.resolve(icon);
            },

            getIcon: function getIcon() {

                if (!icons[this.icon]) {
                    return null;
                }

                if (!parsed[this.icon]) {
                    parsed[this.icon] = this.parse(icons[this.icon]);
                }

                return parsed[this.icon];
            }

        }

    }));

    [
        'navbar-toggle-icon',
        'overlay-icon',
        'pagination-previous',
        'pagination-next',
        'totop'
    ].forEach(function (name) { return registerComponent(name); });

    [
        'slidenav-previous',
        'slidenav-next'
    ].forEach(function (name) { return registerComponent(name, {

        init: function init() {
            this.$el.addClass('uk-slidenav');

            if (this.$el.hasClass('uk-slidenav-large')) {
                this.icon += '-large';
            }
        }

    }); });

    registerComponent('search-icon', {

        init: function init() {
            if (this.$el.hasClass('uk-search-icon') && this.$el.parents('.uk-search-large').length) {
                this.icon = 'search-large';
            } else if (this.$el.parents('.uk-search-navbar').length) {
                this.icon = 'search-navbar';
            }
        }

    });

    registerComponent('close', {

        init: function init() {
            this.icon = "close-" + (this.$el.hasClass('uk-close-large') ? 'large' : 'icon');
        }

    });

    registerComponent('spinner', {

        connected: function connected() {
            var this$1 = this;


            this.height = this.width = this.$el.width();

            this.svg.then(function (svg) {

                var circle = svg.find('circle'),
                    diameter = Math.floor(this$1.width / 2);

                svg[0].setAttribute('viewBox', ("0 0 " + (this$1.width) + " " + (this$1.width)));

                circle.attr({cx: diameter, cy: diameter, r: diameter - parseFloat(circle.css('stroke-width') || 0)});
            });
        }

    });

    UIkit.icon.add = function (added) { return $$1.extend(icons, added); };

    function registerComponent(name, mixin) {

        UIkit.component(name, UIkit.components.icon.extend({

            name: name,

            mixins: mixin ? [mixin] : [],

            defaults: {
                icon: name
            }

        }));
    }

}

function Margin (UIkit) {

    UIkit.component('margin', {

        props: {
            margin: String,
            firstColumn: Boolean
        },

        defaults: {
            margin: 'uk-margin-small-top',
            firstColumn: 'uk-first-column'
        },

        update: {

            read: function read() {
                var this$1 = this;


                if (this.$el[0].offsetHeight === 0) {
                    this.hidden = true;
                    return;
                }

                this.hidden = false;
                this.stacks = true;

                var columns = this.$el.children().filter(function (_, el) { return el.offsetHeight > 0; });

                this.rows = [[columns.get(0)]];

                columns.slice(1).each(function (_, el) {

                    var top = Math.ceil(el.offsetTop), bottom = top + el.offsetHeight;

                    for (var index = this$1.rows.length - 1; index >= 0; index--) {
                        var row = this$1.rows[index], rowTop = Math.ceil(row[0].offsetTop);

                        if (top >= rowTop + row[0].offsetHeight) {
                            this$1.rows.push([el]);
                            break;
                        }

                        if (bottom > rowTop) {

                            this$1.stacks = false;

                            if (el.offsetLeft < row[0].offsetLeft) {
                                row.unshift(el);
                                break;
                            }

                            row.push(el);
                            break;
                        }

                        if (index === 0) {
                            this$1.rows.splice(index, 0, [el]);
                            break;
                        }

                    }

                });

            },

            write: function write() {
                var this$1 = this;


                if (this.hidden) {
                    return;
                }

                this.rows.forEach(function (row, i) { return row.forEach(function (el, j) { return $$1__default(el)
                            .toggleClass(this$1.margin, i !== 0)
                            .toggleClass(this$1.firstColumn, j === 0); }
                    ); }
                )

            },

            events: ['load', 'resize']

        }

    });

}

function Modal$1 (UIkit) {

    UIkit.component('modal', {

        mixins: [Modal],

        props: {
            center: Boolean
        },

        defaults: {
            center: false,
            clsPage: 'uk-modal-page',
            clsPanel: 'uk-modal-dialog',
            selClose: '.uk-modal-close, .uk-modal-close-default, .uk-modal-close-outside, .uk-modal-close-full'
        },

        update: {

            write: function write() {

                if (this.$el.css('display') === 'block' && this.center) {
                    this.$el
                        .removeClass('uk-flex uk-flex-center uk-flex-middle')
                        .css('display', 'block')
                        .toggleClass('uk-flex uk-flex-center uk-flex-middle', window.innerHeight > this.panel.outerHeight(true))
                        .css('display', this.$el.hasClass('uk-flex') ? '' : 'block');
                }

            },

            events: ['resize']

        },

        events: [

            {
                name: 'beforeshow',

                self: true,

                handler: function handler() {
                    this.$el.css('display', 'block').height();
                }
            },

            {
                name: 'hidden',

                self: true,

                handler: function handler() {
                    this.$el.css('display', '').removeClass('uk-flex uk-flex-center uk-flex-middle');
                }
            }

        ]

    });

    UIkit.component('overflow-auto', {

        mixins: [Class],

        computed: {

            panel: function panel() {
                return this.$el.closest('.uk-modal-dialog');
            }

        },

        connected: function connected() {
            this.$el.css('min-height', 150);
        },

        update: {

            write: function write() {
                var current = this.$el.css('max-height');
                this.$el.css('max-height', 150).css('max-height', Math.max(150, 150 - (this.panel.outerHeight(true) - window.innerHeight)));
                if (current !== this.$el.css('max-height')) {
                    this.$el.trigger('resize');
                }
            },

            events: ['load', 'resize']

        }

    });

    UIkit.modal.dialog = function (content, options) {

        var dialog = UIkit.modal(
            ("<div class=\"uk-modal\">\n                <div class=\"uk-modal-dialog\">" + content + "</div>\n             </div>")
        , options);

        dialog.$el.on('hidden', function () { return dialog.$destroy(true); });
        dialog.show();

        return dialog;
    };

    UIkit.modal.alert = function (message, options) {

        options = $$1.extend({bgClose: false, escClose: false, labels: UIkit.modal.labels}, options);

        return promise(
            function (resolve) { return UIkit.modal.dialog(("\n                <div class=\"uk-modal-body\">" + (isString(message) ? message : $$1__default(message).html()) + "</div>\n                <div class=\"uk-modal-footer uk-text-right\">\n                    <button class=\"uk-button uk-button-primary uk-modal-close\" autofocus>" + (options.labels.ok) + "</button>\n                </div>\n            "), options).$el.on('hide', resolve); }
        );
    };

    UIkit.modal.confirm = function (message, options) {

        options = $$1.extend({bgClose: false, escClose: false, labels: UIkit.modal.labels}, options);

        return promise(
            function (resolve, reject) { return UIkit.modal.dialog(("\n                <div class=\"uk-modal-body\">" + (isString(message) ? message : $$1__default(message).html()) + "</div>\n                <div class=\"uk-modal-footer uk-text-right\">\n                    <button class=\"uk-button uk-button-default uk-modal-close\">" + (options.labels.cancel) + "</button>\n                    <button class=\"uk-button uk-button-primary uk-modal-close\" autofocus>" + (options.labels.ok) + "</button>\n                </div>\n            "), options).$el.on('click', '.uk-modal-footer button', function (e) { return $$1__default(e.target).index() === 0 ? reject() : resolve(); }); }
        );
    };

    UIkit.modal.prompt = function (message, value, options) {

        options = $$1.extend({bgClose: false, escClose: false, labels: UIkit.modal.labels}, options);

        return promise(function (resolve) {

            var resolved = false,
                prompt = UIkit.modal.dialog(("\n                <form class=\"uk-form-stacked\">\n                    <div class=\"uk-modal-body\">\n                        <label>" + (isString(message) ? message : $$1__default(message).html()) + "</label>\n                        <input class=\"uk-input\" type=\"text\" autofocus>\n                    </div>\n                    <div class=\"uk-modal-footer uk-text-right\">\n                        <button class=\"uk-button uk-button-default uk-modal-close\" type=\"button\">" + (options.labels.cancel) + "</button>\n                        <button class=\"uk-button uk-button-primary\" type=\"submit\">" + (options.labels.ok) + "</button>\n                    </div>\n                </form>\n            "), options),
                input = prompt.$el.find('input').val(value);

            prompt.$el
                .on('submit', 'form', function (e) {
                    e.preventDefault();
                    resolve(input.val());
                    resolved = true;
                    prompt.hide()
                })
                .on('hide', function () {
                    if (!resolved) {
                        resolve(null);
                    }
                });

        });
    };

    UIkit.modal.labels = {
        ok: 'Ok',
        cancel: 'Cancel'
    }

}

function Nav (UIkit) {

    UIkit.component('nav', UIkit.components.accordion.extend({

        name: 'nav',

        defaults: {
            targets: '> .uk-parent',
            toggle: '> a',
            content: 'ul:first'
        }

    }));

}

function Navbar (UIkit) {

    UIkit.component('navbar', {

        mixins: [Class],

        props: {
            dropdown: String,
            mode: 'list',
            align: String,
            offset: Number,
            boundary: Boolean,
            boundaryAlign: Boolean,
            clsDrop: String,
            delayShow: Number,
            delayHide: Number,
            dropbar: Boolean,
            dropbarMode: String,
            dropbarAnchor: 'jQuery',
            duration: Number
        },

        defaults: {
            dropdown: '.uk-navbar-nav > li',
            align: !isRtl ? 'left' : 'right',
            clsDrop: 'uk-navbar-dropdown',
            mode: undefined,
            offset: undefined,
            delayShow: undefined,
            delayHide: undefined,
            boundaryAlign: undefined,
            flip: 'x',
            boundary: true,
            dropbar: false,
            dropbarMode: 'slide',
            dropbarAnchor: false,
            duration: 200,
        },

        computed: {

            boundary: function boundary() {
                return (this.$props.boundary === true || this.boundaryAlign) ? this.$el : this.$props.boundary
            },

            pos: function pos() {
                return ("bottom-" + (this.align));
            }

        },

        ready: function ready() {

            if (this.dropbar) {
                UIkit.navbarDropbar(
                    query(this.dropbar, this.$el) || $$1__default('<div></div>').insertAfter(this.dropbarAnchor || this.$el),
                    {clsDrop: this.clsDrop, mode: this.dropbarMode, duration: this.duration, navbar: this}
                );
            }

        },

        update: function update() {

            UIkit.drop($$1__default(((this.dropdown) + " ." + (this.clsDrop)), this.$el), $$1.extend({}, this))

        },

        events: [

            {
                name: pointerEnter,

                delegate: function delegate() {
                    return this.dropdown;
                },

                handler: function handler(ref) {
                    var currentTarget = ref.currentTarget;

                    var active = this.getActive();
                    if (active && active.toggle && !isWithin(active.toggle.$el, currentTarget) && !active.tracker.movesTo(active.$el)) {
                        active.hide(false);
                    }
                }

            }

        ],

        methods: {

            getActive: function getActive() {
                var active = UIkit.drop.getActive();
                return active && active.mode !== 'click' && isWithin(active.toggle.$el, this.$el) && active;
            }

        }

    });

    UIkit.component('navbar-dropbar', {

        mixins: [Class],

        defaults: {
            clsDrop: '',
            mode: 'slide',
            navbar: null,
            duration: 200
        },

        init: function init() {

            if (this.mode === 'slide') {
                this.$el.addClass('uk-navbar-dropbar-slide');
            }

        },

        events: [

            {
                name: 'beforeshow',

                el: function el() {
                    return this.navbar.$el;
                },

                handler: function handler(_, ref) {
                    var $el = ref.$el;
                    var dir = ref.dir;

                    if (dir === 'bottom' && !isWithin($el, this.$el)) {
                        $el.appendTo(this.$el);
                        this.$el.trigger('beforeshow', [{$el: $el}]);
                    }
                }
            },

            {
                name: 'mouseleave',

                handler: function handler() {
                    var active = this.navbar.getActive();

                    if (active && !this.$el.is(':hover')) {
                        active.hide();
                    }
                }
            },

            {
                name: 'beforeshow',

                handler: function handler(e, ref) {
                    var $el = ref.$el;

                    this.clsDrop && $el.addClass(((this.clsDrop) + "-dropbar"));
                    this.transitionTo($el.outerHeight(true));
                }
            },

            {
                name: 'beforehide',

                handler: function handler(e, ref) {
                    var $el = ref.$el;


                    var active = this.navbar.getActive();

                    if (this.$el.is(':hover') && active && active.$el.is($el)) {
                        return false;
                    }
                }
            },

            {
                name: 'hide',

                handler: function handler(e, ref) {
                    var $el = ref.$el;


                    var active = this.navbar.getActive();

                    if (!active || active && active.$el.is($el)) {
                        this.transitionTo(0);
                    }
                }
            }

        ],

        methods: {

            transitionTo: function transitionTo(height) {
                var this$1 = this;

                this.$el.height(this.$el[0].offsetHeight ? this.$el.height() : 0);
                return Transition.cancel(this.$el).then(function () { return Transition.start(this$1.$el, {height: height}, this$1.duration); });
            }

        }

    });

}

var scroll;

function Offcanvas (UIkit) {

    UIkit.component('offcanvas', {

        mixins: [Modal],

        args: 'mode',

        props: {
            content: String,
            mode: String,
            flip: Boolean,
            overlay: Boolean
        },

        defaults: {
            content: '.uk-offcanvas-content:first',
            mode: 'slide',
            flip: false,
            overlay: false,
            clsPage: 'uk-offcanvas-page',
            clsContainer: 'uk-offcanvas-container',
            clsPanel: 'uk-offcanvas-bar',
            clsFlip: 'uk-offcanvas-flip',
            clsContent: 'uk-offcanvas-content',
            clsContentAnimation: 'uk-offcanvas-content-animation',
            clsSidebarAnimation: 'uk-offcanvas-bar-animation',
            clsMode: 'uk-offcanvas',
            clsOverlay: 'uk-offcanvas-overlay',
            selClose: '.uk-offcanvas-close'
        },

        computed: {

            content: function content() {
                return $$1__default(query(this.$props.content, this.$el));
            },

            clsFlip: function clsFlip() {
                return this.flip ? this.$props.clsFlip : '';
            },

            clsOverlay: function clsOverlay() {
                return this.overlay ? this.$props.clsOverlay : '';
            },

            clsMode: function clsMode() {
                return ((this.$props.clsMode) + "-" + (this.mode));
            },

            clsSidebarAnimation: function clsSidebarAnimation() {
                return this.mode === 'none' || this.mode === 'reveal' ? '' : this.$props.clsSidebarAnimation;
            },

            clsContentAnimation: function clsContentAnimation() {
                return this.mode !== 'push' && this.mode !== 'reveal' ? '' : this.$props.clsContentAnimation
            },

            transitionElement: function transitionElement() {
                return this.mode === 'reveal' ? this.panel.parent() : this.panel;
            }

        },

        update: {

            write: function write() {

                if (this.isToggled()) {

                    if (this.overlay || this.clsContentAnimation) {
                        this.content.width(window.innerWidth - this.scrollbarWidth);
                    }

                    if (this.overlay) {
                        this.content.height(window.innerHeight);
                        scroll && this.content.scrollTop(scroll.y);
                    }


                }

            },

            events: ['resize']

        },

        events: [

            {
                name: 'beforeshow',

                self: true,

                handler: function handler() {

                    scroll = scroll || {x: window.pageXOffset, y: window.pageYOffset};

                    if (this.mode === 'reveal' && !this.panel.parent().hasClass(this.clsMode)) {
                        this.panel.wrap('<div>').parent().addClass(this.clsMode);
                    }

                    docElement.css('overflow-y', (!this.clsContentAnimation || this.flip) && this.scrollbarWidth && this.overlay ? 'scroll' : '');

                    this.body.addClass(((this.clsContainer) + " " + (this.clsFlip) + " " + (this.clsOverlay))).height();
                    this.content.addClass(this.clsContentAnimation);
                    this.panel.addClass(((this.clsSidebarAnimation) + " " + (this.mode !== 'reveal' ? this.clsMode : '')));
                    this.$el.addClass(this.clsOverlay).css('display', 'block').height();

                }
            },

            {
                name: 'beforehide',

                self: true,

                handler: function handler() {
                    this.content.removeClass(this.clsContentAnimation);

                    if (this.mode === 'none' || this.getActive() && this.getActive() !== this) {
                        this.panel.trigger(transitionend);
                    }
                }
            },

            {
                name: 'hidden',

                self: true,

                handler: function handler() {

                    if (this.mode === 'reveal') {
                        this.panel.unwrap();
                    }

                    if (!this.overlay) {
                        scroll = {x: window.pageXOffset, y: window.pageYOffset}
                    }

                    this.panel.removeClass(((this.clsSidebarAnimation) + " " + (this.clsMode)));
                    this.$el.removeClass(this.clsOverlay).css('display', '');
                    this.body.removeClass(((this.clsContainer) + " " + (this.clsFlip) + " " + (this.clsOverlay))).scrollTop(scroll.y);

                    docElement.css('overflow-y', '');
                    this.content.width('').height('');

                    window.scrollTo(scroll.x, scroll.y);

                    scroll = null;

                }
            },

            {
                name: 'swipeLeft swipeRight',

                handler: function handler(e) {

                    if (this.isToggled() && isTouch(e) && (e.type === 'swipeLeft' && !this.flip || e.type === 'swipeRight' && this.flip)) {
                        this.hide();
                    }

                }
            }

        ]

    });

}

function Responsive (UIkit) {

    UIkit.component('responsive', {

        props: ['width', 'height'],

        init: function init() {
            this.$el.addClass('uk-responsive-width');
        },

        update: {

            write: function write() {
                if (this.$el.is(':visible') && this.width && this.height) {
                    this.$el.height(Dimensions.fit(
                        {height: this.height, width: this.width},
                        {width: this.$el.parent().width(), height: this.height || this.$el.height()}
                    )['height']);
                }
            },

            events: ['load', 'resize']

        }

    });

}

function Scroll (UIkit) {

    UIkit.component('scroll', {

        props: {
            duration: Number,
            transition: String,
            offset: Number
        },

        defaults: {
            duration: 1000,
            transition: 'easeOutExpo',
            offset: 0
        },

        methods: {

            scrollToElement: function scrollToElement(el) {
                var this$1 = this;


                el = $$1__default(el);

                // get / set parameters
                var target = offsetTop(el) - this.offset,
                    docHeight = document.documentElement.offsetHeight,
                    winHeight = window.innerHeight;

                if (target + winHeight > docHeight) {
                    target = docHeight - winHeight;
                }

                // animate to target, fire callback when done
                $$1__default('html,body')
                    .stop()
                    .animate({scrollTop: parseInt(target, 10) || 1}, this.duration, this.transition)
                    .promise()
                    .then(function () { return this$1.$el.trigger('scrolled', [this$1]); });

            }

        },

        events: {

            click: function click(e) {

                if (e.isDefaultPrevented()) {
                    return;
                }

                e.preventDefault();
                this.scrollToElement($$1__default(this.$el[0].hash).length ? this.$el[0].hash : 'body');
            }

        }

    });

    if (!$$1__default.easing.easeOutExpo) {
        $$1__default.easing.easeOutExpo = function (x, t, b, c, d) {
            return (t == d) ? b + c : c * (-Math.pow(2, -10 * t / d) + 1) + b;
        };
    }

}

function Scrollspy (UIkit) {

    UIkit.component('scrollspy', {

        args: 'cls',

        props: {
            cls: 'list',
            target: String,
            hidden: Boolean,
            offsetTop: Number,
            offsetLeft: Number,
            repeat: Boolean,
            delay: Number
        },

        defaults: {
            cls: ['uk-scrollspy-inview'],
            target: false,
            hidden: true,
            offsetTop: 0,
            offsetLeft: 0,
            repeat: false,
            delay: 0,
            inViewClass: 'uk-scrollspy-inview'
        },

        init: function init() {
            this.$emitSync();
        },

        computed: {

            elements: function elements() {
                return this.target && $$1__default(this.target, this.$el) || this.$el;
            }

        },

        update: [

            {

                write: function write() {
                    if (this.hidden) {
                        this.elements.filter((":not(." + (this.inViewClass) + ")")).css('visibility', 'hidden');
                    }
                }

            },

            {

                read: function read() {
                    var this$1 = this;

                    this.elements.each(function (_, el) {

                        if (!el._scrollspy) {
                            var cls = $$1__default(el).attr('uk-scrollspy-class');
                            el._scrollspy = {toggles: cls && cls.split(',') || this$1.cls};
                        }

                        el._scrollspy.show = isInView(el, this$1.offsetTop, this$1.offsetLeft);

                    });
                },

                write: function write() {
                    var this$1 = this;


                    var index = this.elements.length === 1 ? 1 : 0;

                    this.elements.each(function (_, el) {

                        var $el = $$1__default(el);

                        var data = el._scrollspy;

                        if (data.show) {

                            if (!data.inview && !data.timer) {

                                data.timer = setTimeout(function () {

                                    $el.css('visibility', '')
                                        .addClass(this$1.inViewClass)
                                        .toggleClass(data.toggles[0])
                                        .trigger('inview');

                                    data.inview = true;
                                    delete data.timer;

                                }, this$1.delay * index++);

                            }

                        } else {

                            if (data.inview && this$1.repeat) {

                                if (data.timer) {
                                    clearTimeout(data.timer);
                                    delete data.timer;
                                }

                                $el.removeClass(this$1.inViewClass)
                                    .toggleClass(data.toggles[0])
                                    .css('visibility', this$1.hidden ? 'hidden' : '')
                                    .trigger('outview');

                                data.inview = false;
                            }

                        }

                        data.toggles.reverse();

                    });

                },

                events: ['scroll', 'load', 'resize']

            }

        ]

    });

}

function ScrollspyNav (UIkit) {

    UIkit.component('scrollspy-nav', {

        props: {
            cls: String,
            closest: String,
            scroll: Boolean,
            overflow: Boolean,
            offset: Number
        },

        defaults: {
            cls: 'uk-active',
            closest: false,
            scroll: false,
            overflow: true,
            offset: 0
        },

        computed: {

            links: function links() {
                return this.$el.find('a[href^="#"]').filter(function (i, el) { return el.hash; });
            },

            elements: function elements() {
                return this.closest ? this.links.closest(this.closest) : this.links;
            },

            targets: function targets() {
                return $$1__default(this.links.toArray().map(function (el) { return el.hash; }).join(','));
            }

        },

        update: [

            {

                read: function read() {
                    if (this.scroll) {
                        UIkit.scroll(this.links, {offset: this.offset || 0});
                    }
                }

            },

            {

                read: function read() {
                    var this$1 = this;


                    var scroll = window.pageYOffset + this.offset, max = document.documentElement.scrollHeight - window.innerHeight + this.offset;

                    this.active = false;

                    this.targets.each(function (i, el) {

                        el = $$1__default(el);

                        var top = offsetTop(el), last = i + 1 === this$1.targets.length;
                        if (!this$1.overflow && (i === 0 && top > scroll || last && top + el[0].offsetTop < scroll)) {
                            return false;
                        }

                        if (!last && offsetTop(this$1.targets.eq(i + 1)) <= scroll) {
                            return;
                        }

                        if (scroll >= max) {
                            for (var j = this$1.targets.length - 1; j > i; j--) {
                                if (isInView(this$1.targets.eq(j))) {
                                    el = this$1.targets.eq(j);
                                    break;
                                }
                            }
                        }

                        return !(this$1.active = toJQuery(this$1.links.filter(("[href=\"#" + (el.attr('id')) + "\"]"))));

                    });

                },

                write: function write() {

                    this.links.blur();
                    this.elements.removeClass(this.cls);

                    if (this.active) {
                        this.$el.trigger('active', [
                            this.active,
                            (this.closest ? this.active.closest(this.closest) : this.active).addClass(this.cls)
                        ]);
                    }

                },

                events: ['scroll', 'load', 'resize']

            }

        ]

    });

}

function Sticky (UIkit) {

    UIkit.component('sticky', {

        mixins: [Class],

        attrs: true,

        props: {
            top: null,
            bottom: Boolean,
            offset: Number,
            animation: String,
            clsActive: String,
            clsInactive: String,
            clsFixed: String,
            widthElement: 'jQuery',
            showOnUp: Boolean,
            media: 'media',
            target: Number
        },

        defaults: {
            top: 0,
            bottom: false,
            offset: 0,
            animation: '',
            clsActive: 'uk-active',
            clsInactive: '',
            clsFixed: 'uk-sticky-fixed',
            widthElement: false,
            showOnUp: false,
            media: false,
            target: false
        },

        connected: function connected() {

            this.placeholder = $$1__default('<div class="uk-sticky-placeholder"></div>');
            this.widthElement = this.$props.widthElement || this.placeholder;

            if (!this.isActive) {
                this.$el.addClass(this.clsInactive);
            }
        },

        disconnected: function disconnected() {

            if (this.isActive) {
                this.isActive = false;
                this.hide();
                this.$el.removeClass(this.clsInactive);
            }

            this.placeholder.remove();
            this.placeholder = null;
            this.widthElement = null;
        },

        ready: function ready() {
            var this$1 = this;


            if (!(this.target && location.hash && window.pageYOffset > 0)) {
                return;
            }

            var target = query(location.hash);

            if (target) {
                requestAnimationFrame(function () {

                    var top = offsetTop(target),
                        elTop = offsetTop(this$1.$el),
                        elHeight = this$1.$el[0].offsetHeight,
                        elBottom = elTop + elHeight;

                    if (elBottom >= top && elTop <= top + target[0].offsetHeight) {
                        window.scrollTo(0, top - elHeight - this$1.target - this$1.offset);
                    }

                });
            }

        },

        update: [

            {

                write: function write() {
                    var this$1 = this;


                    var outerHeight = this.$el[0].offsetHeight, el;

                    this.placeholder
                        .css('height', this.$el.css('position') !== 'absolute' ? outerHeight : '')
                        .css(this.$el.css(['marginTop', 'marginBottom', 'marginLeft', 'marginRight']));

                    if (!document.documentElement.contains(this.placeholder[0])) {
                        this.placeholder.insertAfter(this.$el).attr('hidden', true);
                    }

                    this.width = this.widthElement.attr('hidden', null)[0].offsetWidth;
                    this.widthElement.attr('hidden', !this.isActive);

                    this.topOffset = offsetTop(this.isActive ? this.placeholder : this.$el);
                    this.bottomOffset = this.topOffset + outerHeight;

                    ['top', 'bottom'].forEach(function (prop) {

                        this$1[prop] = this$1.$props[prop];

                        if (!this$1[prop]) {
                            return;
                        }

                        if ($$1.isNumeric(this$1[prop])) {

                            this$1[prop] = this$1[(prop + "Offset")] + parseFloat(this$1[prop]);

                        } else {

                            if (isString(this$1[prop]) && this$1[prop].match(/^-?\d+vh$/)) {
                                this$1[prop] = window.innerHeight * parseFloat(this$1[prop]) / 100;
                            } else {

                                el = this$1[prop] === true ? this$1.$el.parent() : query(this$1[prop], this$1.$el);

                                if (el) {
                                    this$1[prop] = offsetTop(el) + el[0].offsetHeight;
                                }

                            }

                        }

                    });

                    this.top = Math.max(parseFloat(this.top), this.topOffset) - this.offset;
                    this.bottom = this.bottom && this.bottom - outerHeight;
                    this.inactive = this.media && !window.matchMedia(this.media).matches;

                    if (this.isActive) {
                        this.update();
                    }
                },

                events: ['load', 'resize']

            },

            {

                read: function read() {
                    this.offsetTop = offsetTop(this.$el)
                },

                write: function write(ref) {
                    var this$1 = this;
                    if ( ref === void 0 ) ref = {};
                    var dir = ref.dir;


                    var scroll = window.pageYOffset;

                    if (scroll < 0 || !this.$el.is(':visible') || this.disabled || this.showOnUp && !dir) {
                        return;
                    }

                    if (this.inactive
                        || scroll < this.top
                        || this.showOnUp && (scroll <= this.top || dir ==='down' || dir === 'up' && !this.isActive && scroll <= this.bottomOffset)
                    ) {

                        if (!this.isActive) {
                            return;
                        }

                        this.isActive = false;

                        if (this.animation && this.bottomOffset < this.offsetTop) {
                            Animation.cancel(this.$el).then(function () { return Animation.out(this$1.$el, this$1.animation).then(function () { return this$1.hide(); }); });
                        } else {
                            this.hide();
                        }

                    } else if (this.isActive) {

                        this.update();

                    } else if (this.animation) {

                        Animation.cancel(this.$el).then(function () {
                            this$1.show();
                            Animation.in(this$1.$el, this$1.animation);
                        });

                    } else {
                        this.show();
                    }

                },

                events: ['scroll']

            } ],

        methods: {

            show: function show() {

                this.isActive = true;
                this.update();
                this.$el.trigger('active');
                this.placeholder.attr('hidden', null);

            },

            hide: function hide() {

                this.$el
                    .addClass(this.clsInactive)
                    .removeClass(this.clsFixed)
                    .removeClass(this.clsActive)
                    .css({position: '', top: '', width: ''})
                    .trigger('inactive');

                this.placeholder.attr('hidden', true);

            },

            update: function update() {

                var top = Math.max(0, this.offset), scroll = window.pageYOffset, active = scroll > this.top;

                if (this.bottom && scroll > this.bottom - this.offset) {
                    top = this.bottom - scroll;
                }

                this.$el
                    .css({
                        position: 'fixed',
                        top: (top + "px"),
                        width: this.width
                    })
                    .addClass(this.clsFixed)
                    .toggleClass(this.clsActive, active)
                    .toggleClass(this.clsInactive, !active);

            }

        }

    });

}

var svgs = {};
var parser = new DOMParser();
function Svg (UIkit) {

    UIkit.component('svg', {

        attrs: true,

        props: {
            id: String,
            icon: String,
            src: String,
            class: String,
            style: String,
            width: Number,
            height: Number,
            ratio: Number
        },

        defaults: {
            ratio: 1,
            id: false,
            class: '',
            exclude: ['src']
        },

        init: function init() {
            this.class += ' uk-svg';
        },

        connected: function connected() {
            var this$1 = this;


            if (!this.icon && this.src && ~this.src.indexOf('#')) {

                var parts = this.src.split('#');

                if (parts.length > 1) {
                    this.src = parts[0];
                    this.icon = parts[1];
                }
            }

            this.width = this.$props.width;
            this.height = this.$props.height;

            this.svg = this.getSvg().then(function (doc) { return promise(function (resolve, reject) { return fastdom.mutate(function () {

                var svg, el;

                if (!doc) {
                    reject('SVG not found.');
                    return;
                }

                if (!this$1.icon) {
                    el = doc.documentElement.cloneNode(true);
                } else {
                    svg = doc.getElementById(this$1.icon);

                    if (!svg) {

                        // fallback if SVG has no symbols
                        if (!doc.querySelector('symbol')) {
                            el = doc.documentElement.cloneNode(true);
                        }

                    } else {

                        var html = svg.outerHTML;

                        // IE workaround
                        if (!html) {
                            var div = document.createElement('div');
                            div.appendChild(svg.cloneNode(true));
                            html = div.innerHTML;
                        }

                        html = html
                            .replace(/<symbol/g, ("<svg" + (!~html.indexOf('xmlns') ? ' xmlns="http://www.w3.org/2000/svg"' : '')))
                            .replace(/symbol>/g, 'svg>');

                        el = parser.parseFromString(html, 'image/svg+xml').documentElement;
                    }

                }

                if (!el) {
                    reject('SVG not found.');
                    return;
                }

                var dimensions = el.getAttribute('viewBox'); // jQuery workaround, el.attr('viewBox')

                if (dimensions) {
                    dimensions = dimensions.split(' ');
                    this$1.width = this$1.width || dimensions[2];
                    this$1.height = this$1.height || dimensions[3];
                }

                el = $$1__default(el);

                this$1.width *= this$1.ratio;
                this$1.height *= this$1.ratio;

                for (var prop in this$1.$options.props) {
                    if (this$1[prop] && !~this$1.exclude.indexOf(prop)) {
                        el.attr(prop, this$1[prop]);
                    }
                }

                if (!this$1.id) {
                    el.removeAttr('id');
                }

                if (this$1.width && !this$1.height) {
                    el.removeAttr('height');
                }

                if (this$1.height && !this$1.width) {
                    el.removeAttr('width');
                }

                if (isVoidElement(this$1.$el) || this$1.$el[0].tagName === 'CANVAS') {
                    this$1.$el.attr({hidden: true, id: null});
                    el.insertAfter(this$1.$el);
                } else {
                    el.appendTo(this$1.$el);
                }

                resolve(el);

            }); }); }).then(null, function () {});

            if (!this._isReady) {
                this.$emitSync();
            }
        },

        disconnected: function disconnected() {

            if (isVoidElement(this.$el)) {
                this.$el.attr({hidden: null, id: this.id || null});
            }

            if (this.svg) {
                this.svg.then(function (svg) {
                    svg && svg.remove();
                });
                this.svg = null;
            }
        },

        methods: {

            getSvg: function getSvg() {
                var this$1 = this;


                if (!this.src) {
                    return promise.reject();
                }

                if (svgs[this.src]) {
                    return svgs[this.src];
                }

                svgs[this.src] = promise(function (resolve, reject) {

                    if (this$1.src.lastIndexOf('data:', 0) === 0) {
                        resolve(this$1.parse(decodeURIComponent(this$1.src.split(',')[1])));
                    } else {

                        $$1__default.ajax(this$1.src, {dataType: 'html'}).then(function (doc) {
                            resolve(this$1.parse(doc));
                        }, function () {
                            reject('SVG not found.');
                        });

                    }

                });

                return svgs[this.src];

            },

            parse: function parse(doc) {
                var parsed = parser.parseFromString(doc, 'image/svg+xml');
                return parsed.documentElement && parsed.documentElement.nodeName === 'svg' ? parsed : null;
            }

        }

    });

}

function Switcher (UIkit) {

    UIkit.component('switcher', {

        mixins: [Toggable],

        args: 'connect',

        props: {
            connect: String,
            toggle: String,
            active: Number,
            swiping: Boolean
        },

        defaults: {
            connect: false,
            toggle: ' > *',
            active: 0,
            swiping: true,
            cls: 'uk-active',
            clsContainer: 'uk-switcher',
            attrItem: 'uk-switcher-item',
            queued: true
        },

        connected: function connected() {
            this.$emitSync();
        },

        computed: {

            connects: function connects() {
                return query(this.connect, this.$el) || $$1__default(this.$el.next(("." + (this.clsContainer))));
            },

            toggles: function toggles() {
                return $$1__default(this.toggle, this.$el);
            }

        },

        events: [

            {

                name: 'click',

                delegate: function delegate() {
                    return ((this.toggle) + ":not(.uk-disabled)");
                },

                handler: function handler(e) {
                    e.preventDefault();
                    this.show(e.currentTarget);
                }

            },

            {
                name: 'click',

                el: function el() {
                    return this.connects;
                },

                delegate: function delegate() {
                    return ("[" + (this.attrItem) + "],[data-" + (this.attrItem) + "]");
                },

                handler: function handler(e) {
                    e.preventDefault();
                    this.show($$1__default(e.currentTarget)[e.currentTarget.hasAttribute(this.attrItem) ? 'attr' : 'data'](this.attrItem));
                }
            },

            {
                name: 'swipeRight swipeLeft',

                filter: function filter() {
                    return this.swiping;
                },

                el: function el() {
                    return this.connects;
                },

                delegate: function delegate() {
                    return ("[" + (this.attrItem) + "],[data-" + (this.attrItem) + "]");
                },

                handler: function handler(e) {
                    if (!isTouch(e)) {
                        return;
                    }

                    e.preventDefault();
                    if (!window.getSelection().toString()) {
                        this.show(e.type == 'swipeLeft' ? 'next' : 'previous');
                    }
                }
            }

        ],

        update: function update() {

            this.updateAria(this.connects.children());
            this.show(toJQuery(this.toggles.filter(("." + (this.cls) + ":first"))) || toJQuery(this.toggles.eq(this.active)) || this.toggles.first());

        },

        methods: {

            show: function show(item) {
                var this$1 = this;


                var length = this.toggles.length,
                    prev = this.connects.children(("." + (this.cls))).index(),
                    hasPrev = prev >= 0,
                    index = getIndex(item, this.toggles, prev),
                    dir = item === 'previous' ? -1 : 1,
                    toggle;

                for (var i = 0; i < length; i++, index = (index + dir + length) % length) {
                    if (!this$1.toggles.eq(index).is('.uk-disabled, [disabled]')) {
                        toggle = this$1.toggles.eq(index);
                        break;
                    }
                }

                if (!toggle || prev >= 0 && toggle.hasClass(this.cls) || prev === index) {
                    return;
                }

                this.toggles.removeClass(this.cls).attr('aria-expanded', false);
                toggle.addClass(this.cls).attr('aria-expanded', true);

                if (!hasPrev) {
                    this.toggleNow(this.connects.children((":nth-child(" + (index + 1) + ")")));
                } else {
                    this.toggleElement(this.connects.children((":nth-child(" + (prev + 1) + "),:nth-child(" + (index + 1) + ")")));
                }
            }

        }

    });

}

function Tab (UIkit) {

    UIkit.component('tab', UIkit.components.switcher.extend({

        mixins: [Class],

        name: 'tab',

        props: {
            media: 'media'
        },

        defaults: {
            media: 960,
            attrItem: 'uk-tab-item'
        },

        init: function init() {

            var cls = this.$el.hasClass('uk-tab-left') && 'uk-tab-left' || this.$el.hasClass('uk-tab-right') && 'uk-tab-right';

            if (cls) {
                UIkit.toggle(this.$el, {cls: cls, mode: 'media', media: this.media});
            }
        }

    }));

}

function Toggle (UIkit) {

    UIkit.component('toggle', {

        mixins: [UIkit.mixin.toggable],

        args: 'target',

        props: {
            href: String,
            target: null,
            mode: 'list',
            media: 'media'
        },

        defaults: {
            href: false,
            target: false,
            mode: 'click',
            queued: true,
            media: false
        },

        computed: {

            target: function target() {
                return query(this.$props.target || this.href, this.$el) || this.$el;
            }

        },

        events: [

            {

                name: (pointerEnter + " " + pointerLeave),

                filter: function filter() {
                    return ~this.mode.indexOf('hover');
                },

                handler: function handler(e) {
                    if (!isTouch(e)) {
                        this.toggle(e.type === pointerEnter ? 'toggleshow' : 'togglehide');
                    }
                }

            },

            {

                name: 'click',

                filter: function filter() {
                    return ~this.mode.indexOf('click') || hasTouch;
                },

                handler: function handler(e) {

                    if (!isTouch(e) && !~this.mode.indexOf('click')) {
                        return;
                    }

                    // TODO better isToggled handling
                    if ($$1__default(e.target).closest('a[href="#"], button').length || $$1__default(e.target).closest('a[href]') && (this.cls || !this.target.is(':visible'))) {
                        e.preventDefault();
                    }

                    this.toggle();
                }

            }
        ],

        update: {

            write: function write() {

                if (!~this.mode.indexOf('media') || !this.media) {
                    return;
                }

                var toggled = this.isToggled(this.target);
                if (window.matchMedia(this.media).matches ? !toggled : toggled) {
                    this.toggle();
                }

            },

            events: ['load', 'resize']

        },

        methods: {

            toggle: function toggle(type) {

                var event = $$1__default.Event(type || 'toggle');
                this.target.triggerHandler(event, [this]);

                if (!event.isDefaultPrevented()) {
                    this.toggleElement(this.target);
                }
            }

        }

    });

}

function Leader (UIkit) {

    UIkit.component('leader', {

        mixins: [Class],

        props: {
            fill: String,
            media: 'media'
        },

        defaults: {
            fill: '',
            media: false,
            clsWrapper: 'uk-leader-fill',
            clsHide: 'uk-leader-hide',
            attrFill: 'data-fill'
        },

        computed: {

            fill: function fill() {
                return this.$props.fill || getCssVar('leader-fill');
            }

        },

        connected: function connected() {
            this.wrapper = this.$el.wrapInner(("<span class=\"" + (this.clsWrapper) + "\">")).children().first();
        },

        disconnected: function disconnected() {
            this.wrapper.contents().unwrap();
        },

        update: [

            {

                read: function read() {
                    var prev = this._width;
                    this._width = Math.floor(this.$el[0].offsetWidth / 2);
                    this._changed = prev !== this._width;
                    this._hide = this.media && !window.matchMedia(this.media).matches;
                },

                write: function write() {

                    this.wrapper.toggleClass(this.clsHide, this._hide);

                    if (this._changed) {
                        this.wrapper.attr(this.attrFill, Array(this._width).join(this.fill));
                    }

               },

                events: ['load', 'resize']

            }
        ]
    });

}

function core (UIkit) {

    var scroll = null, dir, ticking, resizing, started = 0;

    win
        .on('load', UIkit.update)
        .on('resize', function (e) {
            if (!resizing) {
                requestAnimationFrame(function () {
                    UIkit.update(e);
                    resizing = false;
                });
                resizing = true;
            }
        })
        .on('scroll', function (e) {

            if (scroll === null) {
                scroll = 0;
            }

            if (scroll === window.pageYOffset) {
                return;
            }

            dir = scroll < window.pageYOffset;
            scroll = window.pageYOffset;
            if (!ticking) {
                requestAnimationFrame(function () {
                    e.dir = dir ? 'down' : 'up';
                    UIkit.update(e);
                    ticking = false;
                });
                ticking = true;
            }
        });

    on(document, animationstart, function (ref) {
        var target = ref.target;

        if ((getStyle(target, 'animationName') || '').match(/^uk-.*(left|right)/)) {
            started++;
            document.body.style.overflowX = 'hidden';
            setTimeout(function () {
                if (!--started) {
                    document.body.style.overflowX = '';
                }
            }, toMs(getStyle(target, 'animationDuration')) + 100);
        }
    }, true);

    // core components
    UIkit.use(Toggle);
    UIkit.use(Accordion);
    UIkit.use(Alert);
    UIkit.use(Cover);
    UIkit.use(Drop);
    UIkit.use(Dropdown);
    UIkit.use(FormCustom);
    UIkit.use(HeightMatch);
    UIkit.use(HeightViewport);
    UIkit.use(Hover);
    UIkit.use(Margin);
    UIkit.use(Gif);
    UIkit.use(Grid);
    UIkit.use(Leader);
    UIkit.use(Modal$1);
    UIkit.use(Nav);
    UIkit.use(Navbar);
    UIkit.use(Offcanvas);
    UIkit.use(Responsive);
    UIkit.use(Scroll);
    UIkit.use(Scrollspy);
    UIkit.use(ScrollspyNav);
    UIkit.use(Sticky);
    UIkit.use(Svg);
    UIkit.use(Icon);
    UIkit.use(Switcher);
    UIkit.use(Tab);

}

UIkit$1.version = '3.0.0-beta.19';

mixin(UIkit$1);
core(UIkit$1);

function plugin(UIkit) {

    if (plugin.installed) {
        return;
    }

    var ref = UIkit.util;
    var $ = ref.$;
    var doc = ref.doc;
    var extend = ref.extend;
    var Dimensions = ref.Dimensions;
    var getIndex = ref.getIndex;
    var Transition = ref.Transition;
    var active;

    doc.on({
        keydown: function (e) {
            if (active) {
                switch (e.keyCode) {
                    case 37:
                        active.show('previous');
                        break;
                    case 39:
                        active.show('next');
                        break;
                }
            }
        }
    });

    UIkit.component('lightbox', {

        name: 'lightbox',

        props: {
            toggle: String,
            duration: Number,
            inverse: Boolean
        },

        defaults: {
            toggle: 'a',
            duration: 400,
            dark: false,
            attrItem: 'uk-lightbox-item',
            items: [],
            index: 0
        },

        computed: {

            toggles: function toggles() {
                var this$1 = this;

                return $(this.toggle, this.$el).each(function (_, el) { return this$1.items.push({
                    source: el.getAttribute('href'),
                    title: el.getAttribute('title'),
                    type: el.getAttribute('type')
                }); });
            }

        },

        events: [

            {

                name: 'click',

                delegate: function delegate() {
                    return ((this.toggle) + ":not(.uk-disabled)");
                },

                handler: function handler(e) {
                    e.preventDefault();
                    this.show(this.toggles.index(e.currentTarget));
                }

            },

            {

                name: 'showitem',

                handler: function handler(e) {

                    var item = this.getItem();

                    if (item.content) {
                        this.$update();
                        e.stopImmediatePropagation();
                    }
                }

            }

        ],

        update: {

            write: function write() {
                var this$1 = this;


                var item = this.getItem();

                if (!this.modal || !item.content) {
                    return;
                }

                var panel = this.modal.panel,
                    dim = {width: panel.width(), height: panel.height()},
                    max = {
                        width: window.innerWidth - (panel.outerWidth(true) - dim.width),
                        height: window.innerHeight - (panel.outerHeight(true) - dim.height)
                    },
                    newDim = Dimensions.fit({width: item.width, height: item.height}, max);

                Transition.stop(panel);
                Transition.stop(this.modal.content);

                if (this.modal.content) {
                    this.modal.content.remove();
                }

                this.modal.content = $(item.content).css('opacity', 0).appendTo(panel);
                panel.css(dim);

                Transition.start(panel, newDim, this.duration).then(function () {
                    Transition.start(this$1.modal.content, {opacity: 1}, 400).then(function () {
                        panel.find('[uk-transition-hide]').show();
                        panel.find('[uk-transition-show]').hide();
                    });
                });

            },

            events: ['resize']

        },

        methods: {

            show: function show(index) {
                var this$1 = this;


                this.index = getIndex(index, this.items, this.index);

                if (!this.modal) {
                    this.modal = UIkit.modal.dialog("\n                    <button class=\"uk-modal-close-outside\" uk-transition-hide type=\"button\" uk-close></button>\n                    <span class=\"uk-position-center\" uk-transition-show uk-spinner></span>\n                    ", {center: true});
                    this.modal.$el.css('overflow', 'hidden').addClass('uk-modal-lightbox');
                    this.modal.panel.css({width: 200, height: 200});
                    this.modal.caption = $('<div class="uk-modal-caption" uk-transition-hide></div>').appendTo(this.modal.panel);

                    if (this.items.length > 1) {
                        $(("<div class=\"" + (this.dark ? 'uk-dark' : 'uk-light') + "\" uk-transition-hide>\n                            <a href=\"#\" class=\"uk-position-center-left\" uk-slidenav-previous uk-lightbox-item=\"previous\"></a>\n                            <a href=\"#\" class=\"uk-position-center-right\" uk-slidenav-next uk-lightbox-item=\"next\"></a>\n                        </div>\n                    ")).appendTo(this.modal.panel.addClass('uk-slidenav-position'));
                    }

                    this.modal.$el
                        .on('hide', this.hide)
                        .on('click', ("[" + (this.attrItem) + "]"), function (e) {
                            e.preventDefault();
                            this$1.show($(e.currentTarget).attr(this$1.attrItem));
                        }).on('swipeRight swipeLeft', function (e) {
                        e.preventDefault();
                        if (!window.getSelection().toString()) {
                            this$1.show(e.type == 'swipeLeft' ? 'next' : 'previous');
                        }
                    });
                }

                active = this;

                this.modal.panel.find('[uk-transition-hide]').hide();
                this.modal.panel.find('[uk-transition-show]').show();

                this.modal.content && this.modal.content.remove();
                this.modal.caption.text(this.getItem().title);

                var event = $.Event('showitem');
                this.$el.trigger(event);
                if (!event.isImmediatePropagationStopped()) {
                    this.setError(this.getItem());
                }
            },

            hide: function hide() {
                var this$1 = this;


                active = active && active !== this && active;

                this.modal.hide().then(function () {
                    this$1.modal.$destroy(true);
                    this$1.modal = null;
                });
            },

            getItem: function getItem() {
                return this.items[this.index] || {source: '', title: '', type: ''};
            },

            setItem: function setItem(item, content, width, height) {
                if ( width === void 0 ) width = 200;
                if ( height === void 0 ) height = 200;

                extend(item, {content: content, width: width, height: height});
                this.$update();
            },

            setError: function setError(item) {
                this.setItem(item, '<div class="uk-position-cover uk-flex uk-flex-middle uk-flex-center"><strong>Loading resource failed!</strong></div>', 400, 300);
            }

        }

    });

    UIkit.mixin({

        events: {

            showitem: function showitem(e) {
                var this$1 = this;


                var item = this.getItem();

                if (item.type !== 'image' && item.source && !item.source.match(/\.(jp(e)?g|png|gif|svg)$/i)) {
                    return;
                }

                var img = new Image();

                img.onerror = function () { return this$1.setError(item); };
                img.onload = function () { return this$1.setItem(item, ("<img class=\"uk-responsive-width\" width=\"" + (img.width) + "\" height=\"" + (img.height) + "\" src =\"" + (item.source) + "\">"), img.width, img.height); };

                img.src = item.source;

                e.stopImmediatePropagation();
            }

        }

    }, 'lightbox');

    UIkit.mixin({

        events: {

            showitem: function showitem(e) {
                var this$1 = this;


                var item = this.getItem();

                if (item.type !== 'video' && item.source && !item.source.match(/\.(mp4|webm|ogv)$/i)) {
                    return;
                }

                var video = $('<video class="uk-responsive-width" controls></video>')
                    .on('loadedmetadata', function () { return this$1.setItem(item, video.attr({width: video[0].videoWidth, height: video[0].videoHeight}), video[0].videoWidth, video[0].videoHeight); })
                    .attr('src', item.source);

                e.stopImmediatePropagation();
            }

        }

    }, 'lightbox');

    UIkit.mixin({

        events: {

            showitem: function showitem(e) {
                var this$1 = this;


                var item = this.getItem(), matches;

                if (!(matches = item.source.match(/\/\/.*?youtube\.[a-z]+\/watch\?v=([^&]+)&?(.*)/)) && !(item.source.match(/youtu\.be\/(.*)/))) {
                    return;
                }

                var id = matches[1],
                    img = new Image(),
                    lowres = false,
                    setIframe = function (width, height) { return this$1.setItem(item, ("<iframe src=\"//www.youtube.com/embed/" + id + "\" width=\"" + width + "\" height=\"" + height + "\" style=\"max-width:100%;box-sizing:border-box;\"></iframe>"), width, height); };

                img.onerror = function () { return setIframe(640, 320); };
                img.onload = function () {
                    //youtube default 404 thumb, fall back to lowres
                    if (img.width === 120 && img.height === 90) {
                        if (!lowres) {
                            lowres = true;
                            img.src = "//img.youtube.com/vi/" + id + "/0.jpg";
                        } else {
                            setIframe(640, 320);
                        }
                    } else {
                        setIframe(img.width, img.height);
                    }
                };

                img.src = "//img.youtube.com/vi/" + id + "/maxresdefault.jpg";

                e.stopImmediatePropagation();
            }

        }

    }, 'lightbox');

    UIkit.mixin({

        events: {

            showitem: function showitem(e) {
                var this$1 = this;


                var item = this.getItem(), matches;

                if (!(matches = item.source.match(/(\/\/.*?)vimeo\.[a-z]+\/([0-9]+).*?/))) {
                    return;
                }

                var id = matches[2],
                    setIframe = function (width, height) { return this$1.setItem(item, ("<iframe src=\"//player.vimeo.com/video/" + id + "\" width=\"" + width + "\" height=\"" + height + "\" style=\"max-width:100%;box-sizing:border-box;\"></iframe>"), width, height); };

                $.ajax({type: 'GET', url: ("http://vimeo.com/api/oembed.json?url=" + (encodeURI(item.source))), jsonp: 'callback', dataType: 'jsonp'}).then(function (res) { return setIframe(res.width, res.height); });

                e.stopImmediatePropagation();
            }

        }

    }, 'lightbox');

}

function plugin$1(UIkit) {

    if (plugin$1.installed) {
        return;
    }

    var ref = UIkit.util;
    var $ = ref.$;
    var each = ref.each;
    var pointerEnter = ref.pointerEnter;
    var pointerLeave = ref.pointerLeave;
    var Transition = ref.Transition;
    var containers = {};

    UIkit.component('notification', {

        functional: true,

        args: ['message', 'status'],

        defaults: {
            message: '',
            status: '',
            timeout: 5000,
            group: null,
            pos: 'top-center',
            onClose: null,
            clsClose: 'uk-notification-close'
        },

        created: function created() {

            if (!containers[this.pos]) {
                containers[this.pos] = $(("<div class=\"uk-notification uk-notification-" + (this.pos) + "\"></div>")).appendTo(UIkit.container);
            }

            this.$mount($(
                ("<div class=\"uk-notification-message" + (this.status ? (" uk-notification-message-" + (this.status)) : '') + "\">\n                    <a href=\"#\" class=\"" + (this.clsClose) + "\" data-uk-close></a>\n                    <div>" + (this.message) + "</div>\n                </div>")
            ).appendTo(containers[this.pos].show())[0]);

        },

        ready: function ready() {
            var this$1 = this;


            var marginBottom = parseInt(this.$el.css('margin-bottom'), 10);

            Transition.start(
                this.$el.css({opacity: 0, marginTop: -1 * this.$el.outerHeight(), marginBottom: 0}),
                {opacity: 1, marginTop: 0, marginBottom: marginBottom}
            ).then(function () {
                if (this$1.timeout) {
                    this$1.timer = setTimeout(this$1.close, this$1.timeout);
                    this$1.$el
                        .on(pointerEnter, function () { return clearTimeout(this$1.timer); })
                        .on(pointerLeave, function () { return this$1.timer = setTimeout(this$1.close, this$1.timeout); });
                }
            });

        },

        events: {

            click: function click(e) {
                if ($(e.target).closest('a[href="#"]').length) {
                    e.preventDefault();
                }
                this.close();
            }

        },

        methods: {

            close: function close(immediate) {
                var this$1 = this;


                var remove = function () {

                    this$1.onClose && this$1.onClose();
                    this$1.$el.trigger('close', [this$1]).remove();

                    if (!containers[this$1.pos].children().length) {
                        containers[this$1.pos].hide();
                    }

                };

                if (this.timer) {
                    clearTimeout(this.timer);
                }

                if (immediate) {
                    remove();
                } else {
                    Transition.start(this.$el, {opacity: 0, marginTop: -1 * this.$el.outerHeight(), marginBottom: 0}).then(remove)
                }
            }

        }

    });

    UIkit.notification.closeAll = function (group, immediate) {
        each(UIkit.instances, function (_, component) {
            if (component.$options.name === 'notification' && (!group || group === component.group)) {
                component.close(immediate);
            }
        })
    };

}

function plugin$2(UIkit) {

    if (plugin$2.installed) {
        return;
    }

    var mixin = UIkit.mixin;
    var util = UIkit.util;
    var $ = util.$;
    var doc = util.docElement;
    var extend = util.extend;
    var getDimensions = util.getDimensions;
    var isWithin = util.isWithin;
    var on = util.on;
    var off = util.off;
    var offsetTop = util.offsetTop;
    var pointerDown = util.pointerDown;
    var pointerMove = util.pointerMove;
    var pointerUp = util.pointerUp;
    var promise = util.promise;
    var win = util.win;

    UIkit.component('sortable', {

        mixins: [mixin.class],

        props: {
            group: String,
            animation: Number,
            threshold: Number,
            clsItem: String,
            clsPlaceholder: String,
            clsDrag: String,
            clsDragState: String,
            clsBase: String,
            clsNoDrag: String,
            clsEmpty: String,
            clsCustom: String,
            handle: String
        },

        defaults: {
            group: false,
            animation: 150,
            threshold: 5,
            clsItem: 'uk-sortable-item',
            clsPlaceholder: 'uk-sortable-placeholder',
            clsDrag: 'uk-sortable-drag',
            clsDragState: 'uk-drag',
            clsBase: 'uk-sortable',
            clsNoDrag: 'uk-sortable-nodrag',
            clsEmpty: 'uk-sortable-empty',
            clsCustom: '',
            handle: false
        },

        init: function init() {
            var this$1 = this;

            ['init', 'start', 'move', 'end'].forEach(function (key) {
                var fn = this$1[key];
                this$1[key] = function (e) {
                    e = e.originalEvent || e;
                    this$1.scrollY = window.scrollY;
                    var ref = e.touches && e.touches[0] || e;
                    var pageX = ref.pageX;
                    var pageY = ref.pageY;
                    this$1.pos = {x: pageX, y: pageY};

                    fn(e);
                }
            });
        },

        events: ( obj = {}, obj[pointerDown] = 'init', obj ),

        update: {

            write: function write() {
                var this$1 = this;


                if (this.clsEmpty) {
                    this.$el.toggleClass(this.clsEmpty, !this.$el.children().length);
                }

                if (!this.drag) {
                    return;
                }

                this.drag.offset({top: this.pos.y + this.origin.top, left: this.pos.x + this.origin.left});

                var top = offsetTop(this.drag), bottom = top + this.drag[0].offsetHeight;

                if (top > 0 && top < this.scrollY) {
                    setTimeout(function () { return win.scrollTop(this$1.scrollY - 5); }, 5);
                } else if (bottom < doc[0].offsetHeight && bottom > window.innerHeight + this.scrollY) {
                    setTimeout(function () { return win.scrollTop(this$1.scrollY + 5); }, 5);
                }

            }

        },

        methods: {

            init: function init(e) {

                var target = $(e.target), placeholder = this.$el.children().filter(function (i, el) { return isWithin(e.target, el); });

                if (!placeholder.length
                    || target.is(':input')
                    || this.handle && !isWithin(target, this.handle)
                    || e.button && e.button !== 0
                    || isWithin(target, ("." + (this.clsNoDrag)))
                ) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                this.touched = [this];
                this.placeholder = placeholder;
                this.origin = extend({target: target, index: this.placeholder.index()}, this.pos);

                doc.on(pointerMove, this.move);
                doc.on(pointerUp, this.end);
                win.on('scroll', this.scroll);

                if (!this.threshold) {
                    this.start(e);
                }

            },

            start: function start(e) {

                this.drag = $(this.placeholder[0].outerHTML.replace(/^<li/i, '<div').replace(/li>$/i, 'div>'))
                    .attr('uk-no-boot', '')
                    .addClass(((this.clsDrag) + " " + (this.clsCustom)))
                    .css({
                        boxSizing: 'border-box',
                        width: this.placeholder.outerWidth(),
                        height: this.placeholder.outerHeight()
                    })
                    .css(this.placeholder.css(['paddingLeft', 'paddingRight', 'paddingTop', 'paddingBottom']))
                    .appendTo(UIkit.container);

                this.drag.children().first().height(this.placeholder.children().height());

                var ref = getDimensions(this.placeholder);
                var left = ref.left;
                var top = ref.top;
                extend(this.origin, {left: left - this.pos.x, top: top - this.pos.y});

                this.placeholder.addClass(this.clsPlaceholder);
                this.$el.children().addClass(this.clsItem);
                doc.addClass(this.clsDragState);

                this.$el.trigger('start', [this, this.placeholder, this.drag]);

                this.move(e);
            },

            move: function move(e) {

                if (!this.drag) {

                    if (Math.abs(this.pos.x - this.origin.x) > this.threshold || Math.abs(this.pos.y - this.origin.y) > this.threshold) {
                        this.start(e);
                    }

                    return;
                }

                this.$emit();

                var target = e.type === 'mousemove' ? e.target : document.elementFromPoint(this.pos.x - document.body.scrollLeft, this.pos.y - document.body.scrollTop),
                    sortable = getSortable(target),
                    previous = getSortable(this.placeholder[0]),
                    move = sortable !== previous;

                if (!sortable || isWithin(target, this.placeholder) || move && (!sortable.group || sortable.group !== previous.group)) {
                    return;
                }

                target = sortable.$el.is(target.parentNode) && $(target) || sortable.$el.children().has(target);

                if (move) {
                    previous.remove(this.placeholder);
                } else if (!target.length) {
                    return;
                }

                sortable.insert(this.placeholder, target);

                if (!~this.touched.indexOf(sortable)) {
                    this.touched.push(sortable);
                }

            },

            scroll: function scroll() {
                var scroll = window.scrollY;
                if (scroll !== this.scrollY) {
                    this.pos.y += scroll - this.scrollY;
                    this.scrollY = scroll;
                    this.$emit();
                }
            },

            end: function end(e) {

                doc.off(pointerMove, this.move);
                doc.off(pointerUp, this.end);
                win.off('scroll', this.scroll);

                if (!this.drag) {

                    if (e.type !== 'mouseup' && isWithin(e.target, 'a[href]')) {
                        location.href = $(e.target).closest('a[href]').attr('href');
                    }

                    return;
                }

                preventClick();

                var sortable = getSortable(this.placeholder[0]);

                if (this === sortable) {
                    if (this.origin.index !== this.placeholder.index()) {
                        this.$el.trigger('change', [this, this.placeholder, 'moved']);
                    }
                } else {
                    sortable.$el.trigger('change', [sortable, this.placeholder, 'added']);
                    this.$el.trigger('change', [this, this.placeholder, 'removed']);
                }

                this.$el.trigger('stop', [this]);

                this.drag.remove();
                this.drag = null;

                this.touched.forEach(function (sortable) { return sortable.$el.children().removeClass(((sortable.clsPlaceholder) + " " + (sortable.clsItem))); });

                doc.removeClass(this.clsDragState);

            },

            insert: function insert(element, target) {
                var this$1 = this;


                this.$el.children().addClass(this.clsItem);

                var insert = function () {

                    if (target.length) {

                        if (!this$1.$el.has(element).length || element.prevAll().filter(target).length) {
                            element.insertBefore(target);
                        } else {
                            element.insertAfter(target);
                        }

                    } else {
                        this$1.$el.append(element);
                    }

                };

                if (this.animation) {
                    this.animate(insert);
                } else {
                    insert();
                }

            },

            remove: function remove(element) {

                if (!this.$el.has(element).length) {
                    return;
                }

                if (this.animation) {
                    this.animate(function () { return element.detach(); });
                } else {
                    element.detach();
                }

            },

            animate: function animate(action) {
                var this$1 = this;


                var props = [],
                    children = this.$el.children().toArray().map(function (el) {
                        el = $(el);
                        props.push(extend({
                            position: 'absolute',
                            pointerEvents: 'none',
                            width: el.outerWidth(),
                            height: el.outerHeight()
                        }, el.position()));
                        return el;
                    }),
                    reset = {position: '', width: '', height: '', pointerEvents: '', top: '', left: ''};

                action();

                children.forEach(function (el) { return el.stop(); });
                this.$el.children().css(reset);
                this.$updateSync('update', true);

                this.$el.css('min-height', this.$el.height());

                var positions = children.map(function (el) { return el.position(); });
                promise.all(children.map(function (el, i) { return el.css(props[i]).animate(positions[i], this$1.animation).promise(); }))
                    .then(function () {
                        this$1.$el.css('min-height', '').children().css(reset);
                        this$1.$updateSync('update', true);
                    });

            }

        }

    });
    var obj;

    function getSortable(element) {
        return UIkit.getComponent(element, 'sortable') || element.parentNode && getSortable(element.parentNode);
    }

    function preventClick() {
        var timer = setTimeout(function () { return doc.trigger('click'); }, 0),
            listener = function (e) {

                e.preventDefault();
                e.stopPropagation();

                clearTimeout(timer);
                off(doc, 'click', listener, true);
            };

        on(doc, 'click', listener, true);
    }

}

function plugin$3(UIkit) {

    if (plugin$3.installed) {
        return;
    }

    var util = UIkit.util;
    var mixin = UIkit.mixin;
    var $ = util.$;
    var doc = util.doc;
    var fastdom = util.fastdom;
    var flipPosition = util.flipPosition;
    var isTouch = util.isTouch;
    var isWithin = util.isWithin;
    var pointerDown = util.pointerDown;
    var pointerEnter = util.pointerEnter;
    var pointerLeave = util.pointerLeave;
    var toJQuery = util.toJQuery;

    var active;

    doc.on('click', function (e) {
        if (active && !isWithin(e.target, active.$el)) {
            active.hide();
        }
    });

    UIkit.component('tooltip', {

        attrs: true,

        mixins: [mixin.toggable, mixin.position],

        props: {
            delay: Number,
            container: Boolean,
            title: String
        },

        defaults: {
            pos: 'top',
            title: '',
            delay: 0,
            animation: ['uk-animation-scale-up'],
            duration: 100,
            cls: 'uk-active',
            clsPos: 'uk-tooltip',
            container: true,
        },

        init: function init() {
            this.container = this.container === true && UIkit.container || this.container && toJQuery(this.container);
        },

        connected: function connected() {
            var this$1 = this;

            fastdom.mutate(function () { return this$1.$el.removeAttr('title').attr('aria-expanded', false); });
        },

        disconnected: function disconnected() {
            this.hide();
        },

        methods: {

            show: function show() {
                var this$1 = this;


                if (active === this) {
                    return;
                }

                if (active) {
                    active.hide();
                }

                active = this;

                clearTimeout(this.showTimer);

                this.tooltip = $(("<div class=\"" + (this.clsPos) + "\" aria-hidden=\"true\"><div class=\"" + (this.clsPos) + "-inner\">" + (this.title) + "</div></div>")).appendTo(this.container);

                this.$el.attr('aria-expanded', true);

                this.positionAt(this.tooltip, this.$el);
                this.origin = this.getAxis() === 'y' ? ((flipPosition(this.dir)) + "-" + (this.align)) : ((this.align) + "-" + (flipPosition(this.dir)));

                this.showTimer = setTimeout(function () {
                    this$1.toggleElement(this$1.tooltip, true);

                    this$1.hideTimer = setInterval(function () {
                        if (!this$1.$el.is(':visible')) {
                            this$1.hide();
                        }
                    }, 150);

                }, this.delay);
            },

            hide: function hide() {

                if (this.$el.is('input') && this.$el[0] === document.activeElement) {
                    return;
                }

                active = active !== this && active || false;

                clearTimeout(this.showTimer);
                clearInterval(this.hideTimer);
                this.$el.attr('aria-expanded', false);
                this.toggleElement(this.tooltip, false);
                this.tooltip && this.tooltip.remove();
                this.tooltip = false;
            }

        },

        events: ( obj = {
            'blur': 'hide'
        }, obj[("focus " + pointerEnter + " " + pointerDown)] = function (e) {
                if (e.type !== pointerDown || !isTouch(e)) {
                    this.show();
                }
            }, obj[pointerLeave] = function (e) {
                if (!isTouch(e)) {
                    this.hide()
                }
            }, obj )

    });
    var obj;

}

function plugin$4(UIkit) {

    if (plugin$4.installed) {
        return;
    }

    var ref = UIkit.util;
    var $ = ref.$;
    var ajax = ref.ajax;
    var on = ref.on;

    UIkit.component('upload', {

        props: {
            allow: String,
            clsDragover: String,
            concurrent: Number,
            dataType: String,
            mime: String,
            msgInvalidMime: String,
            msgInvalidName: String,
            multiple: Boolean,
            name: String,
            params: Object,
            type: String,
            url: String
        },

        defaults: {
            allow: false,
            clsDragover: 'uk-dragover',
            concurrent: 1,
            dataType: undefined,
            mime: false,
            msgInvalidMime: 'Invalid File Type: %s',
            msgInvalidName: 'Invalid File Name: %s',
            multiple: false,
            name: 'files[]',
            params: {},
            type: 'POST',
            url: '',
            abort: null,
            beforeAll: null,
            beforeSend: null,
            complete: null,
            completeAll: null,
            error: null,
            fail: function fail(msg) {
                alert(msg);
            },
            load: null,
            loadEnd: null,
            loadStart: null,
            progress: null
        },

        events: {

            change: function change(e) {

                if (!$(e.target).is('input[type="file"]')) {
                    return;
                }

                e.preventDefault();

                if (e.target.files) {
                    this.upload(e.target.files);
                }

                e.target.value = '';
            },

            drop: function drop(e) {
                e.preventDefault();
                e.stopPropagation();

                var transfer = e.originalEvent.dataTransfer;

                if (!transfer || !transfer.files) {
                    return;
                }

                this.$el.removeClass(this.clsDragover);

                this.upload(transfer.files);
            },

            dragenter: function dragenter(e) {
                e.preventDefault();
                e.stopPropagation();
            },

            dragover: function dragover(e) {
                e.preventDefault();
                e.stopPropagation();
                this.$el.addClass(this.clsDragover);
            },

            dragleave: function dragleave(e) {
                e.preventDefault();
                e.stopPropagation();
                this.$el.removeClass(this.clsDragover);
            }

        },

        methods: {

            upload: function upload(files) {
                var this$1 = this;


                if (!files.length) {
                    return;
                }

                this.$el.trigger('upload', [files]);

                for (var i = 0; i < files.length; i++) {

                    if (this$1.allow) {
                        if (!match(this$1.allow, files[i].name)) {
                            this$1.fail(this$1.msgInvalidName.replace(/%s/, this$1.allow));
                            return;
                        }
                    }

                    if (this$1.mime) {
                        if (!match(this$1.mime, files[i].type)) {
                            this$1.fail(this$1.msgInvalidMime.replace(/%s/, this$1.mime));
                            return;
                        }
                    }

                }

                if (!this.multiple) {
                    files = [files[0]];
                }

                this.beforeAll && this.beforeAll(this, files);

                var chunks = chunk(files, this.concurrent),
                    upload = function (files) {

                        var data = new FormData();

                        files.forEach(function (file) { return data.append(this$1.name, file); });

                        for (var key in this$1.params) {
                            data.append(key, this$1.params[key]);
                        }

                        ajax({
                            data: data,
                            url: this$1.url,
                            type: this$1.type,
                            dataType: this$1.dataType,
                            beforeSend: this$1.beforeSend,
                            complete: [this$1.complete, function (xhr, status) {
                                if (chunks.length) {
                                    upload(chunks.shift());
                                } else {
                                    this$1.completeAll && this$1.completeAll(xhr);
                                }

                                if (status === 'abort') {
                                    this$1.abort && this$1.abort(xhr);
                                }
                            }],
                            cache: false,
                            contentType: false,
                            processData: false,
                            xhr: function () {
                                var xhr = $.ajaxSettings.xhr();
                                xhr.upload && this$1.progress && on(xhr.upload, 'progress', this$1.progress);
                                ['loadStart', 'load', 'loadEnd', 'error', 'abort'].forEach(function (type) { return this$1[type] && on(xhr, type.toLowerCase(), this$1[type]); });
                                return xhr;
                            }
                        })

                    };

                upload(chunks.shift());

            }

        }

    });

    function match(pattern, path) {
        return path.match(new RegExp(("^" + (pattern.replace(/\//g, '\\/').replace(/\*\*/g, '(\\/[^\\/]+)*').replace(/\*/g, '[^\\/]+').replace(/((?!\\))\?/g, '$1.')) + "$"), 'i'));
    }

    function chunk(files, size) {
        var chunks = [];
        for (var i = 0; i < files.length; i += size) {
            var chunk = [];
            for (var j = 0; j < size; j++) {
                chunk.push(files[i+j]);
            }
            chunks.push(chunk);
        }
        return chunks;
    }

}

UIkit$1.use(plugin);
UIkit$1.use(plugin$1);
UIkit$1.use(plugin$2);
UIkit$1.use(plugin$3);
UIkit$1.use(plugin$4);

if (true) {
    boot(UIkit$1);
}

return UIkit$1;

})));