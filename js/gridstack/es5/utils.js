"use strict";
/**
 * utils.ts 11.3.0
 * Copyright (c) 2021-2024 Alain Dumesny - see GridStack root license
 */
var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __spreadArray = (this && this.__spreadArray) || function (to, from, pack) {
    if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {
        if (ar || !(i in from)) {
            if (!ar) ar = Array.prototype.slice.call(from, 0, i);
            ar[i] = from[i];
        }
    }
    return to.concat(ar || Array.prototype.slice.call(from));
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.Utils = exports.obsoleteAttr = exports.obsoleteOptsDel = exports.obsoleteOpts = exports.obsolete = void 0;
var gridstack_1 = require("./gridstack");
/** checks for obsolete method names */
// eslint-disable-next-line
function obsolete(self, f, oldName, newName, rev) {
    var wrapper = function () {
        var args = [];
        for (var _i = 0; _i < arguments.length; _i++) {
            args[_i] = arguments[_i];
        }
        console.warn('gridstack.js: Function `' + oldName + '` is deprecated in ' + rev + ' and has been replaced ' +
            'with `' + newName + '`. It will be **removed** in a future release');
        return f.apply(self, args);
    };
    wrapper.prototype = f.prototype;
    return wrapper;
}
exports.obsolete = obsolete;
/** checks for obsolete grid options (can be used for any fields, but msg is about options) */
function obsoleteOpts(opts, oldName, newName, rev) {
    if (opts[oldName] !== undefined) {
        opts[newName] = opts[oldName];
        console.warn('gridstack.js: Option `' + oldName + '` is deprecated in ' + rev + ' and has been replaced with `' +
            newName + '`. It will be **removed** in a future release');
    }
}
exports.obsoleteOpts = obsoleteOpts;
/** checks for obsolete grid options which are gone */
function obsoleteOptsDel(opts, oldName, rev, info) {
    if (opts[oldName] !== undefined) {
        console.warn('gridstack.js: Option `' + oldName + '` is deprecated in ' + rev + info);
    }
}
exports.obsoleteOptsDel = obsoleteOptsDel;
/** checks for obsolete Jquery element attributes */
function obsoleteAttr(el, oldName, newName, rev) {
    var oldAttr = el.getAttribute(oldName);
    if (oldAttr !== null) {
        el.setAttribute(newName, oldAttr);
        console.warn('gridstack.js: attribute `' + oldName + '`=' + oldAttr + ' is deprecated on this object in ' + rev + ' and has been replaced with `' +
            newName + '`. It will be **removed** in a future release');
    }
}
exports.obsoleteAttr = obsoleteAttr;
/**
 * Utility methods
 */
var Utils = /** @class */ (function () {
    function Utils() {
    }
    /** convert a potential selector into actual list of html elements. optional root which defaults to document (for shadow dom) */
    Utils.getElements = function (els, root) {
        if (root === void 0) { root = document; }
        if (typeof els === 'string') {
            var doc = ('getElementById' in root) ? root : undefined;
            // Note: very common for people use to id='1,2,3' which is only legal as HTML5 id, but not CSS selectors
            // so if we start with a number, assume it's an id and just return that one item...
            // see https://github.com/gridstack/gridstack.js/issues/2234#issuecomment-1523796562
            if (doc && !isNaN(+els[0])) { // start with digit
                var el = doc.getElementById(els);
                return el ? [el] : [];
            }
            var list = root.querySelectorAll(els);
            if (!list.length && els[0] !== '.' && els[0] !== '#') {
                list = root.querySelectorAll('.' + els);
                if (!list.length) {
                    list = root.querySelectorAll('#' + els);
                }
            }
            return Array.from(list);
        }
        return [els];
    };
    /** convert a potential selector into actual single element. optional root which defaults to document (for shadow dom) */
    Utils.getElement = function (els, root) {
        if (root === void 0) { root = document; }
        if (typeof els === 'string') {
            var doc = ('getElementById' in root) ? root : undefined;
            if (!els.length)
                return null;
            if (doc && els[0] === '#') {
                return doc.getElementById(els.substring(1));
            }
            if (els[0] === '#' || els[0] === '.' || els[0] === '[') {
                return root.querySelector(els);
            }
            // if we start with a digit, assume it's an id (error calling querySelector('#1')) as class are not valid CSS
            if (doc && !isNaN(+els[0])) { // start with digit
                return doc.getElementById(els);
            }
            // finally try string, then id, then class
            var el = root.querySelector(els);
            if (doc && !el) {
                el = doc.getElementById(els);
            }
            if (!el) {
                el = root.querySelector('.' + els);
            }
            return el;
        }
        return els;
    };
    /** create the default grid item divs, and content possibly lazy loaded calling GridStack.renderCB */
    Utils.createWidgetDivs = function (itemClass, n) {
        var _a, _b;
        var el = Utils.createDiv(['grid-stack-item', itemClass]);
        var cont = Utils.createDiv(['grid-stack-item-content'], el);
        var lazyLoad = n.lazyLoad || ((_b = (_a = n.grid) === null || _a === void 0 ? void 0 : _a.opts) === null || _b === void 0 ? void 0 : _b.lazyLoad) && n.lazyLoad !== false;
        if (lazyLoad) {
            if (!n.visibleObservable) {
                n.visibleObservable = new IntersectionObserver(function (_a) {
                    var _b;
                    var entry = _a[0];
                    if (entry.isIntersecting) {
                        (_b = n.visibleObservable) === null || _b === void 0 ? void 0 : _b.disconnect();
                        delete n.visibleObservable;
                        gridstack_1.GridStack.renderCB(cont, n);
                    }
                });
                window.setTimeout(function () { var _a; return (_a = n.visibleObservable) === null || _a === void 0 ? void 0 : _a.observe(el); }); // wait until callee sets position attributes
            }
        }
        else
            gridstack_1.GridStack.renderCB(cont, n);
        return el;
    };
    /** create a div with the given classes */
    Utils.createDiv = function (classes, parent) {
        var el = document.createElement('div');
        classes.forEach(function (c) { if (c)
            el.classList.add(c); });
        parent === null || parent === void 0 ? void 0 : parent.appendChild(el);
        return el;
    };
    /** true if we should resize to content. strict=true when only 'sizeToContent:true' and not a number which lets user adjust */
    Utils.shouldSizeToContent = function (n, strict) {
        if (strict === void 0) { strict = false; }
        return (n === null || n === void 0 ? void 0 : n.grid) && (strict ?
            (n.sizeToContent === true || (n.grid.opts.sizeToContent === true && n.sizeToContent === undefined)) :
            (!!n.sizeToContent || (n.grid.opts.sizeToContent && n.sizeToContent !== false)));
    };
    /** returns true if a and b overlap */
    Utils.isIntercepted = function (a, b) {
        return !(a.y >= b.y + b.h || a.y + a.h <= b.y || a.x + a.w <= b.x || a.x >= b.x + b.w);
    };
    /** returns true if a and b touch edges or corners */
    Utils.isTouching = function (a, b) {
        return Utils.isIntercepted(a, { x: b.x - 0.5, y: b.y - 0.5, w: b.w + 1, h: b.h + 1 });
    };
    /** returns the area a and b overlap */
    Utils.areaIntercept = function (a, b) {
        var x0 = (a.x > b.x) ? a.x : b.x;
        var x1 = (a.x + a.w < b.x + b.w) ? a.x + a.w : b.x + b.w;
        if (x1 <= x0)
            return 0; // no overlap
        var y0 = (a.y > b.y) ? a.y : b.y;
        var y1 = (a.y + a.h < b.y + b.h) ? a.y + a.h : b.y + b.h;
        if (y1 <= y0)
            return 0; // no overlap
        return (x1 - x0) * (y1 - y0);
    };
    /** returns the area */
    Utils.area = function (a) {
        return a.w * a.h;
    };
    /**
     * Sorts array of nodes
     * @param nodes array to sort
     * @param dir 1 for ascending, -1 for descending (optional)
     **/
    Utils.sort = function (nodes, dir) {
        if (dir === void 0) { dir = 1; }
        var und = 10000;
        return nodes.sort(function (a, b) {
            var _a, _b, _c, _d;
            var diffY = dir * (((_a = a.y) !== null && _a !== void 0 ? _a : und) - ((_b = b.y) !== null && _b !== void 0 ? _b : und));
            if (diffY === 0)
                return dir * (((_c = a.x) !== null && _c !== void 0 ? _c : und) - ((_d = b.x) !== null && _d !== void 0 ? _d : und));
            return diffY;
        });
    };
    /** find an item by id */
    Utils.find = function (nodes, id) {
        return id ? nodes.find(function (n) { return n.id === id; }) : undefined;
    };
    /**
     * creates a style sheet with style id under given parent
     * @param id will set the 'gs-style-id' attribute to that id
     * @param parent to insert the stylesheet as first child,
     * if none supplied it will be appended to the document head instead.
     */
    Utils.createStylesheet = function (id, parent, options) {
        var style = document.createElement('style');
        var nonce = options === null || options === void 0 ? void 0 : options.nonce;
        if (nonce)
            style.nonce = nonce;
        style.setAttribute('type', 'text/css');
        style.setAttribute('gs-style-id', id);
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        if (style.styleSheet) { // TODO: only CSSImportRule have that and different beast ??
            // eslint-disable-next-line @typescript-eslint/no-explicit-any
            style.styleSheet.cssText = '';
        }
        else {
            style.appendChild(document.createTextNode('')); // WebKit hack
        }
        if (!parent) {
            // default to head
            parent = document.getElementsByTagName('head')[0];
            parent.appendChild(style);
        }
        else {
            parent.insertBefore(style, parent.firstChild);
        }
        return style;
    };
    /** removed the given stylesheet id */
    Utils.removeStylesheet = function (id, parent) {
        var target = parent || document;
        var el = target.querySelector('STYLE[gs-style-id=' + id + ']');
        if (el && el.parentNode)
            el.remove();
    };
    /** inserts a CSS rule */
    Utils.addCSSRule = function (sheet, selector, rules) {
        // Rather than using sheet.insertRule, use text since it supports
        // gridstack node reparenting around in the DOM
        sheet.textContent += "".concat(selector, " { ").concat(rules, " } ");
    };
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    Utils.toBool = function (v) {
        if (typeof v === 'boolean') {
            return v;
        }
        if (typeof v === 'string') {
            v = v.toLowerCase();
            return !(v === '' || v === 'no' || v === 'false' || v === '0');
        }
        return Boolean(v);
    };
    Utils.toNumber = function (value) {
        return (value === null || value.length === 0) ? undefined : Number(value);
    };
    Utils.parseHeight = function (val) {
        var h;
        var unit = 'px';
        if (typeof val === 'string') {
            if (val === 'auto' || val === '')
                h = 0;
            else {
                var match = val.match(/^(-[0-9]+\.[0-9]+|[0-9]*\.[0-9]+|-[0-9]+|[0-9]+)(px|em|rem|vh|vw|%|cm|mm)?$/);
                if (!match) {
                    throw new Error("Invalid height val = ".concat(val));
                }
                unit = match[2] || 'px';
                h = parseFloat(match[1]);
            }
        }
        else {
            h = val;
        }
        return { h: h, unit: unit };
    };
    /** copies unset fields in target to use the given default sources values */
    // eslint-disable-next-line
    Utils.defaults = function (target) {
        var _this = this;
        var sources = [];
        for (var _i = 1; _i < arguments.length; _i++) {
            sources[_i - 1] = arguments[_i];
        }
        sources.forEach(function (source) {
            for (var key in source) {
                if (!source.hasOwnProperty(key))
                    return;
                if (target[key] === null || target[key] === undefined) {
                    target[key] = source[key];
                }
                else if (typeof source[key] === 'object' && typeof target[key] === 'object') {
                    // property is an object, recursively add it's field over... #1373
                    _this.defaults(target[key], source[key]);
                }
            }
        });
        return target;
    };
    /** given 2 objects return true if they have the same values. Checks for Object {} having same fields and values (just 1 level down) */
    Utils.same = function (a, b) {
        if (typeof a !== 'object')
            return a == b;
        if (typeof a !== typeof b)
            return false;
        // else we have object, check just 1 level deep for being same things...
        if (Object.keys(a).length !== Object.keys(b).length)
            return false;
        for (var key in a) {
            if (a[key] !== b[key])
                return false;
        }
        return true;
    };
    /** copies over b size & position (GridStackPosition), and optionally min/max as well */
    Utils.copyPos = function (a, b, doMinMax) {
        if (doMinMax === void 0) { doMinMax = false; }
        if (b.x !== undefined)
            a.x = b.x;
        if (b.y !== undefined)
            a.y = b.y;
        if (b.w !== undefined)
            a.w = b.w;
        if (b.h !== undefined)
            a.h = b.h;
        if (doMinMax) {
            if (b.minW)
                a.minW = b.minW;
            if (b.minH)
                a.minH = b.minH;
            if (b.maxW)
                a.maxW = b.maxW;
            if (b.maxH)
                a.maxH = b.maxH;
        }
        return a;
    };
    /** true if a and b has same size & position */
    Utils.samePos = function (a, b) {
        return a && b && a.x === b.x && a.y === b.y && (a.w || 1) === (b.w || 1) && (a.h || 1) === (b.h || 1);
    };
    /** given a node, makes sure it's min/max are valid */
    Utils.sanitizeMinMax = function (node) {
        // remove 0, undefine, null
        if (!node.minW) {
            delete node.minW;
        }
        if (!node.minH) {
            delete node.minH;
        }
        if (!node.maxW) {
            delete node.maxW;
        }
        if (!node.maxH) {
            delete node.maxH;
        }
    };
    /** removes field from the first object if same as the second objects (like diffing) and internal '_' for saving */
    Utils.removeInternalAndSame = function (a, b) {
        if (typeof a !== 'object' || typeof b !== 'object')
            return;
        for (var key in a) {
            var aVal = a[key];
            var bVal = b[key];
            if (key[0] === '_' || aVal === bVal) {
                delete a[key];
            }
            else if (aVal && typeof aVal === 'object' && bVal !== undefined) {
                Utils.removeInternalAndSame(aVal, bVal);
                if (!Object.keys(aVal).length) {
                    delete a[key];
                }
            }
        }
    };
    /** removes internal fields '_' and default values for saving */
    Utils.removeInternalForSave = function (n, removeEl) {
        if (removeEl === void 0) { removeEl = true; }
        for (var key in n) {
            if (key[0] === '_' || n[key] === null || n[key] === undefined)
                delete n[key];
        }
        delete n.grid;
        if (removeEl)
            delete n.el;
        // delete default values (will be re-created on read)
        if (!n.autoPosition)
            delete n.autoPosition;
        if (!n.noResize)
            delete n.noResize;
        if (!n.noMove)
            delete n.noMove;
        if (!n.locked)
            delete n.locked;
        if (n.w === 1 || n.w === n.minW)
            delete n.w;
        if (n.h === 1 || n.h === n.minH)
            delete n.h;
    };
    /** return the closest parent (or itself) matching the given class */
    // static closestUpByClass(el: HTMLElement, name: string): HTMLElement {
    //   while (el) {
    //     if (el.classList.contains(name)) return el;
    //     el = el.parentElement
    //   }
    //   return null;
    // }
    /** delay calling the given function for given delay, preventing new calls from happening while waiting */
    Utils.throttle = function (func, delay) {
        var isWaiting = false;
        return function () {
            var args = [];
            for (var _i = 0; _i < arguments.length; _i++) {
                args[_i] = arguments[_i];
            }
            if (!isWaiting) {
                isWaiting = true;
                setTimeout(function () { func.apply(void 0, args); isWaiting = false; }, delay);
            }
        };
    };
    Utils.removePositioningStyles = function (el) {
        var style = el.style;
        if (style.position) {
            style.removeProperty('position');
        }
        if (style.left) {
            style.removeProperty('left');
        }
        if (style.top) {
            style.removeProperty('top');
        }
        if (style.width) {
            style.removeProperty('width');
        }
        if (style.height) {
            style.removeProperty('height');
        }
    };
    /** @internal returns the passed element if scrollable, else the closest parent that will, up to the entire document scrolling element */
    Utils.getScrollElement = function (el) {
        if (!el)
            return document.scrollingElement || document.documentElement; // IE support
        var style = getComputedStyle(el);
        var overflowRegex = /(auto|scroll)/;
        if (overflowRegex.test(style.overflow + style.overflowY)) {
            return el;
        }
        else {
            return this.getScrollElement(el.parentElement);
        }
    };
    /** @internal */
    Utils.updateScrollPosition = function (el, position, distance) {
        // is widget in view?
        var rect = el.getBoundingClientRect();
        var innerHeightOrClientHeight = (window.innerHeight || document.documentElement.clientHeight);
        if (rect.top < 0 ||
            rect.bottom > innerHeightOrClientHeight) {
            // set scrollTop of first parent that scrolls
            // if parent is larger than el, set as low as possible
            // to get entire widget on screen
            var offsetDiffDown = rect.bottom - innerHeightOrClientHeight;
            var offsetDiffUp = rect.top;
            var scrollEl = this.getScrollElement(el);
            if (scrollEl !== null) {
                var prevScroll = scrollEl.scrollTop;
                if (rect.top < 0 && distance < 0) {
                    // moving up
                    if (el.offsetHeight > innerHeightOrClientHeight) {
                        scrollEl.scrollTop += distance;
                    }
                    else {
                        scrollEl.scrollTop += Math.abs(offsetDiffUp) > Math.abs(distance) ? distance : offsetDiffUp;
                    }
                }
                else if (distance > 0) {
                    // moving down
                    if (el.offsetHeight > innerHeightOrClientHeight) {
                        scrollEl.scrollTop += distance;
                    }
                    else {
                        scrollEl.scrollTop += offsetDiffDown > distance ? distance : offsetDiffDown;
                    }
                }
                // move widget y by amount scrolled
                position.top += scrollEl.scrollTop - prevScroll;
            }
        }
    };
    /**
     * @internal Function used to scroll the page.
     *
     * @param event `MouseEvent` that triggers the resize
     * @param el `HTMLElement` that's being resized
     * @param distance Distance from the V edges to start scrolling
     */
    Utils.updateScrollResize = function (event, el, distance) {
        var scrollEl = this.getScrollElement(el);
        var height = scrollEl.clientHeight;
        // #1727 event.clientY is relative to viewport, so must compare this against position of scrollEl getBoundingClientRect().top
        // #1745 Special situation if scrollEl is document 'html': here browser spec states that
        // clientHeight is height of viewport, but getBoundingClientRect() is rectangle of html element;
        // this discrepancy arises because in reality scrollbar is attached to viewport, not html element itself.
        var offsetTop = (scrollEl === this.getScrollElement()) ? 0 : scrollEl.getBoundingClientRect().top;
        var pointerPosY = event.clientY - offsetTop;
        var top = pointerPosY < distance;
        var bottom = pointerPosY > height - distance;
        if (top) {
            // This also can be done with a timeout to keep scrolling while the mouse is
            // in the scrolling zone. (will have smoother behavior)
            scrollEl.scrollBy({ behavior: 'smooth', top: pointerPosY - distance });
        }
        else if (bottom) {
            scrollEl.scrollBy({ behavior: 'smooth', top: distance - (height - pointerPosY) });
        }
    };
    /** single level clone, returning a new object with same top fields. This will share sub objects and arrays */
    Utils.clone = function (obj) {
        if (obj === null || obj === undefined || typeof (obj) !== 'object') {
            return obj;
        }
        // return Object.assign({}, obj);
        if (obj instanceof Array) {
            // eslint-disable-next-line @typescript-eslint/no-explicit-any
            return __spreadArray([], obj, true);
        }
        return __assign({}, obj);
    };
    /**
     * Recursive clone version that returns a full copy, checking for nested objects and arrays ONLY.
     * Note: this will use as-is any key starting with double __ (and not copy inside) some lib have circular dependencies.
     */
    Utils.cloneDeep = function (obj) {
        // list of fields we will skip during cloneDeep (nested objects, other internal)
        var skipFields = ['parentGrid', 'el', 'grid', 'subGrid', 'engine'];
        // return JSON.parse(JSON.stringify(obj)); // doesn't work with date format ?
        var ret = Utils.clone(obj);
        var _loop_1 = function (key) {
            // NOTE: we don't support function/circular dependencies so skip those properties for now...
            if (ret.hasOwnProperty(key) && typeof (ret[key]) === 'object' && key.substring(0, 2) !== '__' && !skipFields.find(function (k) { return k === key; })) {
                ret[key] = Utils.cloneDeep(obj[key]);
            }
        };
        for (var key in ret) {
            _loop_1(key);
        }
        return ret;
    };
    /** deep clone the given HTML node, removing teh unique id field */
    Utils.cloneNode = function (el) {
        var node = el.cloneNode(true);
        node.removeAttribute('id');
        return node;
    };
    Utils.appendTo = function (el, parent) {
        var parentNode;
        if (typeof parent === 'string') {
            parentNode = Utils.getElement(parent);
        }
        else {
            parentNode = parent;
        }
        if (parentNode) {
            parentNode.appendChild(el);
        }
    };
    // public static setPositionRelative(el: HTMLElement): void {
    //   if (!(/^(?:r|a|f)/).test(getComputedStyle(el).position)) {
    //     el.style.position = "relative";
    //   }
    // }
    Utils.addElStyles = function (el, styles) {
        if (styles instanceof Object) {
            var _loop_2 = function (s) {
                if (styles.hasOwnProperty(s)) {
                    if (Array.isArray(styles[s])) {
                        // support fallback value
                        styles[s].forEach(function (val) {
                            el.style[s] = val;
                        });
                    }
                    else {
                        el.style[s] = styles[s];
                    }
                }
            };
            for (var s in styles) {
                _loop_2(s);
            }
        }
    };
    Utils.initEvent = function (e, info) {
        var evt = { type: info.type };
        var obj = {
            button: 0,
            which: 0,
            buttons: 1,
            bubbles: true,
            cancelable: true,
            target: info.target ? info.target : e.target
        };
        ['altKey', 'ctrlKey', 'metaKey', 'shiftKey'].forEach(function (p) { return evt[p] = e[p]; }); // keys
        ['pageX', 'pageY', 'clientX', 'clientY', 'screenX', 'screenY'].forEach(function (p) { return evt[p] = e[p]; }); // point info
        return __assign(__assign({}, evt), obj);
    };
    /** copies the MouseEvent properties and sends it as another event to the given target */
    Utils.simulateMouseEvent = function (e, simulatedType, target) {
        var simulatedEvent = document.createEvent('MouseEvents');
        simulatedEvent.initMouseEvent(simulatedType, // type
        true, // bubbles
        true, // cancelable
        window, // view
        1, // detail
        e.screenX, // screenX
        e.screenY, // screenY
        e.clientX, // clientX
        e.clientY, // clientY
        e.ctrlKey, // ctrlKey
        e.altKey, // altKey
        e.shiftKey, // shiftKey
        e.metaKey, // metaKey
        0, // button
        e.target // relatedTarget
        );
        (target || e.target).dispatchEvent(simulatedEvent);
    };
    /**
     * defines an element that is used to get the offset and scale from grid transforms
     * returns the scale and offsets from said element
    */
    Utils.getValuesFromTransformedElement = function (parent) {
        var transformReference = document.createElement('div');
        Utils.addElStyles(transformReference, {
            opacity: '0',
            position: 'fixed',
            top: 0 + 'px',
            left: 0 + 'px',
            width: '1px',
            height: '1px',
            zIndex: '-999999',
        });
        parent.appendChild(transformReference);
        var transformValues = transformReference.getBoundingClientRect();
        parent.removeChild(transformReference);
        transformReference.remove();
        return {
            xScale: 1 / transformValues.width,
            yScale: 1 / transformValues.height,
            xOffset: transformValues.left,
            yOffset: transformValues.top,
        };
    };
    /** swap the given object 2 field values */
    Utils.swap = function (o, a, b) {
        if (!o)
            return;
        var tmp = o[a];
        o[a] = o[b];
        o[b] = tmp;
    };
    /** returns true if event is inside the given element rectangle */
    // Note: Safari Mac has null event.relatedTarget which causes #1684 so check if DragEvent is inside the coordinates instead
    //    this.el.contains(event.relatedTarget as HTMLElement)
    // public static inside(e: MouseEvent, el: HTMLElement): boolean {
    //   // srcElement, toElement, target: all set to placeholder when leaving simple grid, so we can't use that (Chrome)
    //   const target: HTMLElement = e.relatedTarget || (e as any).fromElement;
    //   if (!target) {
    //     const { bottom, left, right, top } = el.getBoundingClientRect();
    //     return (e.x < right && e.x > left && e.y < bottom && e.y > top);
    //   }
    //   return el.contains(target);
    // }
    /** true if the item can be rotated (checking for prop, not space available) */
    Utils.canBeRotated = function (n) {
        var _a;
        return !(!n || n.w === n.h || n.locked || n.noResize || ((_a = n.grid) === null || _a === void 0 ? void 0 : _a.opts.disableResize) || (n.minW && n.minW === n.maxW) || (n.minH && n.minH === n.maxH));
    };
    return Utils;
}());
exports.Utils = Utils;
//# sourceMappingURL=utils.js.map