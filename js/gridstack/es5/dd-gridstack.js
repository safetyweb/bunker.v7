"use strict";
/**
 * dd-gridstack.ts 11.3.0
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
Object.defineProperty(exports, "__esModule", { value: true });
exports.DDGridStack = void 0;
var utils_1 = require("./utils");
var dd_manager_1 = require("./dd-manager");
var dd_element_1 = require("./dd-element");
// let count = 0; // TEST
/**
 * HTML Native Mouse and Touch Events Drag and Drop functionality.
 */
var DDGridStack = /** @class */ (function () {
    function DDGridStack() {
    }
    DDGridStack.prototype.resizable = function (el, opts, key, value) {
        this._getDDElements(el).forEach(function (dEl) {
            var _a;
            if (opts === 'disable' || opts === 'enable') {
                dEl.ddResizable && dEl.ddResizable[opts](); // can't create DD as it requires options for setupResizable()
            }
            else if (opts === 'destroy') {
                dEl.ddResizable && dEl.cleanResizable();
            }
            else if (opts === 'option') {
                dEl.setupResizable((_a = {}, _a[key] = value, _a));
            }
            else {
                var n = dEl.el.gridstackNode;
                var grid = n.grid;
                var handles = dEl.el.getAttribute('gs-resize-handles') || grid.opts.resizable.handles || 'e,s,se';
                if (handles === 'all')
                    handles = 'n,e,s,w,se,sw,ne,nw';
                // NOTE: keep the resize handles as e,w don't have enough space (10px) to show resize corners anyway. limit during drag instead
                // restrict vertical resize if height is done to match content anyway... odd to have it spring back
                // if (Utils.shouldSizeToContent(n, true)) {
                //   const doE = handles.indexOf('e') !== -1;
                //   const doW = handles.indexOf('w') !== -1;
                //   handles = doE ? (doW ? 'e,w' : 'e') : (doW ? 'w' : '');
                // }
                var autoHide = !grid.opts.alwaysShowResizeHandle;
                dEl.setupResizable(__assign(__assign(__assign({}, grid.opts.resizable), { handles: handles, autoHide: autoHide }), {
                    start: opts.start,
                    stop: opts.stop,
                    resize: opts.resize
                }));
            }
        });
        return this;
    };
    DDGridStack.prototype.draggable = function (el, opts, key, value) {
        this._getDDElements(el).forEach(function (dEl) {
            var _a;
            if (opts === 'disable' || opts === 'enable') {
                dEl.ddDraggable && dEl.ddDraggable[opts](); // can't create DD as it requires options for setupDraggable()
            }
            else if (opts === 'destroy') {
                dEl.ddDraggable && dEl.cleanDraggable();
            }
            else if (opts === 'option') {
                dEl.setupDraggable((_a = {}, _a[key] = value, _a));
            }
            else {
                var grid = dEl.el.gridstackNode.grid;
                dEl.setupDraggable(__assign(__assign({}, grid.opts.draggable), {
                    // containment: (grid.parentGridNode && grid.opts.dragOut === false) ? grid.el.parentElement : (grid.opts.draggable.containment || null),
                    start: opts.start,
                    stop: opts.stop,
                    drag: opts.drag
                }));
            }
        });
        return this;
    };
    DDGridStack.prototype.dragIn = function (el, opts) {
        this._getDDElements(el).forEach(function (dEl) { return dEl.setupDraggable(opts); });
        return this;
    };
    DDGridStack.prototype.droppable = function (el, opts, key, value) {
        if (typeof opts.accept === 'function' && !opts._accept) {
            opts._accept = opts.accept;
            opts.accept = function (el) { return opts._accept(el); };
        }
        this._getDDElements(el).forEach(function (dEl) {
            var _a;
            if (opts === 'disable' || opts === 'enable') {
                dEl.ddDroppable && dEl.ddDroppable[opts]();
            }
            else if (opts === 'destroy') {
                if (dEl.ddDroppable) { // error to call destroy if not there
                    dEl.cleanDroppable();
                }
            }
            else if (opts === 'option') {
                dEl.setupDroppable((_a = {}, _a[key] = value, _a));
            }
            else {
                dEl.setupDroppable(opts);
            }
        });
        return this;
    };
    /** true if element is droppable */
    DDGridStack.prototype.isDroppable = function (el) {
        var _a;
        return !!(((_a = el === null || el === void 0 ? void 0 : el.ddElement) === null || _a === void 0 ? void 0 : _a.ddDroppable) && !el.ddElement.ddDroppable.disabled);
    };
    /** true if element is draggable */
    DDGridStack.prototype.isDraggable = function (el) {
        var _a;
        return !!(((_a = el === null || el === void 0 ? void 0 : el.ddElement) === null || _a === void 0 ? void 0 : _a.ddDraggable) && !el.ddElement.ddDraggable.disabled);
    };
    /** true if element is draggable */
    DDGridStack.prototype.isResizable = function (el) {
        var _a;
        return !!(((_a = el === null || el === void 0 ? void 0 : el.ddElement) === null || _a === void 0 ? void 0 : _a.ddResizable) && !el.ddElement.ddResizable.disabled);
    };
    DDGridStack.prototype.on = function (el, name, callback) {
        this._getDDElements(el).forEach(function (dEl) {
            return dEl.on(name, function (event) {
                callback(event, dd_manager_1.DDManager.dragElement ? dd_manager_1.DDManager.dragElement.el : event.target, dd_manager_1.DDManager.dragElement ? dd_manager_1.DDManager.dragElement.helper : null);
            });
        });
        return this;
    };
    DDGridStack.prototype.off = function (el, name) {
        this._getDDElements(el).forEach(function (dEl) { return dEl.off(name); });
        return this;
    };
    /** @internal returns a list of DD elements, creating them on the fly by default */
    DDGridStack.prototype._getDDElements = function (els, create) {
        if (create === void 0) { create = true; }
        var hosts = utils_1.Utils.getElements(els);
        if (!hosts.length)
            return [];
        var list = hosts.map(function (e) { return e.ddElement || (create ? dd_element_1.DDElement.init(e) : null); });
        if (!create) {
            list.filter(function (d) { return d; });
        } // remove nulls
        return list;
    };
    return DDGridStack;
}());
exports.DDGridStack = DDGridStack;
//# sourceMappingURL=dd-gridstack.js.map