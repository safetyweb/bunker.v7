"use strict";
/**
 * dd-resizable-handle.ts 11.3.0
 * Copyright (c) 2021-2024  Alain Dumesny - see GridStack root license
 */
Object.defineProperty(exports, "__esModule", { value: true });
exports.DDResizableHandle = void 0;
var dd_touch_1 = require("./dd-touch");
var DDResizableHandle = exports.DDResizableHandle = /** @class */ (function () {
    function DDResizableHandle(host, dir, option) {
        this.host = host;
        this.dir = dir;
        this.option = option;
        /** @internal true after we've moved enough pixels to start a resize */
        this.moving = false;
        // create var event binding so we can easily remove and still look like TS methods (unlike anonymous functions)
        this._mouseDown = this._mouseDown.bind(this);
        this._mouseMove = this._mouseMove.bind(this);
        this._mouseUp = this._mouseUp.bind(this);
        this._keyEvent = this._keyEvent.bind(this);
        this._init();
    }
    /** @internal */
    DDResizableHandle.prototype._init = function () {
        var el = this.el = document.createElement('div');
        el.classList.add('ui-resizable-handle');
        el.classList.add("".concat(DDResizableHandle.prefix).concat(this.dir));
        el.style.zIndex = '100';
        el.style.userSelect = 'none';
        this.host.appendChild(this.el);
        this.el.addEventListener('mousedown', this._mouseDown);
        if (dd_touch_1.isTouch) {
            this.el.addEventListener('touchstart', dd_touch_1.touchstart);
            this.el.addEventListener('pointerdown', dd_touch_1.pointerdown);
            // this.el.style.touchAction = 'none'; // not needed unlike pointerdown doc comment
        }
        return this;
    };
    /** call this when resize handle needs to be removed and cleaned up */
    DDResizableHandle.prototype.destroy = function () {
        if (this.moving)
            this._mouseUp(this.mouseDownEvent);
        this.el.removeEventListener('mousedown', this._mouseDown);
        if (dd_touch_1.isTouch) {
            this.el.removeEventListener('touchstart', dd_touch_1.touchstart);
            this.el.removeEventListener('pointerdown', dd_touch_1.pointerdown);
        }
        this.host.removeChild(this.el);
        delete this.el;
        delete this.host;
        return this;
    };
    /** @internal called on mouse down on us: capture move on the entire document (mouse might not stay on us) until we release the mouse */
    DDResizableHandle.prototype._mouseDown = function (e) {
        this.mouseDownEvent = e;
        document.addEventListener('mousemove', this._mouseMove, { capture: true, passive: true }); // capture, not bubble
        document.addEventListener('mouseup', this._mouseUp, true);
        if (dd_touch_1.isTouch) {
            this.el.addEventListener('touchmove', dd_touch_1.touchmove);
            this.el.addEventListener('touchend', dd_touch_1.touchend);
        }
        e.stopPropagation();
        e.preventDefault();
    };
    /** @internal */
    DDResizableHandle.prototype._mouseMove = function (e) {
        var s = this.mouseDownEvent;
        if (this.moving) {
            this._triggerEvent('move', e);
        }
        else if (Math.abs(e.x - s.x) + Math.abs(e.y - s.y) > 2) {
            // don't start unless we've moved at least 3 pixels
            this.moving = true;
            this._triggerEvent('start', this.mouseDownEvent);
            this._triggerEvent('move', e);
            // now track keyboard events to cancel
            document.addEventListener('keydown', this._keyEvent);
        }
        e.stopPropagation();
        // e.preventDefault(); passive = true
    };
    /** @internal */
    DDResizableHandle.prototype._mouseUp = function (e) {
        if (this.moving) {
            this._triggerEvent('stop', e);
            document.removeEventListener('keydown', this._keyEvent);
        }
        document.removeEventListener('mousemove', this._mouseMove, true);
        document.removeEventListener('mouseup', this._mouseUp, true);
        if (dd_touch_1.isTouch) {
            this.el.removeEventListener('touchmove', dd_touch_1.touchmove);
            this.el.removeEventListener('touchend', dd_touch_1.touchend);
        }
        delete this.moving;
        delete this.mouseDownEvent;
        e.stopPropagation();
        e.preventDefault();
    };
    /** @internal call when keys are being pressed - use Esc to cancel */
    DDResizableHandle.prototype._keyEvent = function (e) {
        var _a, _b;
        if (e.key === 'Escape') {
            (_b = (_a = this.host.gridstackNode) === null || _a === void 0 ? void 0 : _a.grid) === null || _b === void 0 ? void 0 : _b.engine.restoreInitial();
            this._mouseUp(this.mouseDownEvent);
        }
    };
    /** @internal */
    DDResizableHandle.prototype._triggerEvent = function (name, event) {
        if (this.option[name])
            this.option[name](event);
        return this;
    };
    /** @internal */
    DDResizableHandle.prefix = 'ui-resizable-';
    return DDResizableHandle;
}());
//# sourceMappingURL=dd-resizable-handle.js.map