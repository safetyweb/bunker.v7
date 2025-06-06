"use strict";
/**
 * dd-draggable.ts 11.3.0
 * Copyright (c) 2021-2024  Alain Dumesny - see GridStack root license
 */
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
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
exports.DDDraggable = void 0;
var dd_manager_1 = require("./dd-manager");
var utils_1 = require("./utils");
var dd_base_impl_1 = require("./dd-base-impl");
var dd_touch_1 = require("./dd-touch");
// make sure we are not clicking on known object that handles mouseDown
var skipMouseDown = 'input,textarea,button,select,option,[contenteditable="true"],.ui-resizable-handle';
// let count = 0; // TEST
var DDDraggable = exports.DDDraggable = /** @class */ (function (_super) {
    __extends(DDDraggable, _super);
    function DDDraggable(el, option) {
        if (option === void 0) { option = {}; }
        var _this = this;
        var _a;
        _this = _super.call(this) || this;
        _this.el = el;
        _this.option = option;
        /** @internal */
        _this.dragTransform = {
            xScale: 1,
            yScale: 1,
            xOffset: 0,
            yOffset: 0
        };
        // get the element that is actually supposed to be dragged by
        var handleName = (_a = option === null || option === void 0 ? void 0 : option.handle) === null || _a === void 0 ? void 0 : _a.substring(1);
        var n = el.gridstackNode;
        _this.dragEls = !handleName || el.classList.contains(handleName) ? [el] : ((n === null || n === void 0 ? void 0 : n.subGrid) ? [el.querySelector(option.handle) || el] : Array.from(el.querySelectorAll(option.handle)));
        if (_this.dragEls.length === 0) {
            _this.dragEls = [el];
        }
        // create var event binding so we can easily remove and still look like TS methods (unlike anonymous functions)
        _this._mouseDown = _this._mouseDown.bind(_this);
        _this._mouseMove = _this._mouseMove.bind(_this);
        _this._mouseUp = _this._mouseUp.bind(_this);
        _this._keyEvent = _this._keyEvent.bind(_this);
        _this.enable();
        return _this;
    }
    DDDraggable.prototype.on = function (event, callback) {
        _super.prototype.on.call(this, event, callback);
    };
    DDDraggable.prototype.off = function (event) {
        _super.prototype.off.call(this, event);
    };
    DDDraggable.prototype.enable = function () {
        var _this = this;
        if (this.disabled === false)
            return;
        _super.prototype.enable.call(this);
        this.dragEls.forEach(function (dragEl) {
            dragEl.addEventListener('mousedown', _this._mouseDown);
            if (dd_touch_1.isTouch) {
                dragEl.addEventListener('touchstart', dd_touch_1.touchstart);
                dragEl.addEventListener('pointerdown', dd_touch_1.pointerdown);
                // dragEl.style.touchAction = 'none'; // not needed unlike pointerdown doc comment
            }
        });
        this.el.classList.remove('ui-draggable-disabled');
    };
    DDDraggable.prototype.disable = function (forDestroy) {
        var _this = this;
        if (forDestroy === void 0) { forDestroy = false; }
        if (this.disabled === true)
            return;
        _super.prototype.disable.call(this);
        this.dragEls.forEach(function (dragEl) {
            dragEl.removeEventListener('mousedown', _this._mouseDown);
            if (dd_touch_1.isTouch) {
                dragEl.removeEventListener('touchstart', dd_touch_1.touchstart);
                dragEl.removeEventListener('pointerdown', dd_touch_1.pointerdown);
            }
        });
        if (!forDestroy)
            this.el.classList.add('ui-draggable-disabled');
    };
    DDDraggable.prototype.destroy = function () {
        if (this.dragTimeout)
            window.clearTimeout(this.dragTimeout);
        delete this.dragTimeout;
        if (this.mouseDownEvent)
            this._mouseUp(this.mouseDownEvent);
        this.disable(true);
        delete this.el;
        delete this.helper;
        delete this.option;
        _super.prototype.destroy.call(this);
    };
    DDDraggable.prototype.updateOption = function (opts) {
        var _this = this;
        Object.keys(opts).forEach(function (key) { return _this.option[key] = opts[key]; });
        return this;
    };
    /** @internal call when mouse goes down before a dragstart happens */
    DDDraggable.prototype._mouseDown = function (e) {
        // don't let more than one widget handle mouseStart
        if (dd_manager_1.DDManager.mouseHandled)
            return;
        if (e.button !== 0)
            return true; // only left click
        // make sure we are not clicking on known object that handles mouseDown, or ones supplied by the user
        if (!this.dragEls.find(function (el) { return el === e.target; }) && e.target.closest(skipMouseDown))
            return true;
        if (this.option.cancel) {
            if (e.target.closest(this.option.cancel))
                return true;
        }
        this.mouseDownEvent = e;
        delete this.dragging;
        delete dd_manager_1.DDManager.dragElement;
        delete dd_manager_1.DDManager.dropElement;
        // document handler so we can continue receiving moves as the item is 'fixed' position, and capture=true so WE get a first crack
        document.addEventListener('mousemove', this._mouseMove, { capture: true, passive: true }); // true=capture, not bubble
        document.addEventListener('mouseup', this._mouseUp, true);
        if (dd_touch_1.isTouch) {
            e.currentTarget.addEventListener('touchmove', dd_touch_1.touchmove);
            e.currentTarget.addEventListener('touchend', dd_touch_1.touchend);
        }
        e.preventDefault();
        // preventDefault() prevents blur event which occurs just after mousedown event.
        // if an editable content has focus, then blur must be call
        if (document.activeElement)
            document.activeElement.blur();
        dd_manager_1.DDManager.mouseHandled = true;
        return true;
    };
    /** @internal method to call actual drag event */
    DDDraggable.prototype._callDrag = function (e) {
        if (!this.dragging)
            return;
        var ev = utils_1.Utils.initEvent(e, { target: this.el, type: 'drag' });
        if (this.option.drag) {
            this.option.drag(ev, this.ui());
        }
        this.triggerEvent('drag', ev);
    };
    /** @internal called when the main page (after successful mousedown) receives a move event to drag the item around the screen */
    DDDraggable.prototype._mouseMove = function (e) {
        var _this = this;
        var _a;
        // console.log(`${count++} move ${e.x},${e.y}`)
        var s = this.mouseDownEvent;
        this.lastDrag = e;
        if (this.dragging) {
            this._dragFollow(e);
            // delay actual grid handling drag until we pause for a while if set
            if (dd_manager_1.DDManager.pauseDrag) {
                var pause = Number.isInteger(dd_manager_1.DDManager.pauseDrag) ? dd_manager_1.DDManager.pauseDrag : 100;
                if (this.dragTimeout)
                    window.clearTimeout(this.dragTimeout);
                this.dragTimeout = window.setTimeout(function () { return _this._callDrag(e); }, pause);
            }
            else {
                this._callDrag(e);
            }
        }
        else if (Math.abs(e.x - s.x) + Math.abs(e.y - s.y) > 3) {
            /**
             * don't start unless we've moved at least 3 pixels
             */
            this.dragging = true;
            dd_manager_1.DDManager.dragElement = this;
            // if we're dragging an actual grid item, set the current drop as the grid (to detect enter/leave)
            var grid = (_a = this.el.gridstackNode) === null || _a === void 0 ? void 0 : _a.grid;
            if (grid) {
                dd_manager_1.DDManager.dropElement = grid.el.ddElement.ddDroppable;
            }
            else {
                delete dd_manager_1.DDManager.dropElement;
            }
            this.helper = this._createHelper();
            this._setupHelperContainmentStyle();
            this.dragTransform = utils_1.Utils.getValuesFromTransformedElement(this.helperContainment);
            this.dragOffset = this._getDragOffset(e, this.el, this.helperContainment);
            this._setupHelperStyle(e);
            var ev = utils_1.Utils.initEvent(e, { target: this.el, type: 'dragstart' });
            if (this.option.start) {
                this.option.start(ev, this.ui());
            }
            this.triggerEvent('dragstart', ev);
            // now track keyboard events to cancel or rotate
            document.addEventListener('keydown', this._keyEvent);
        }
        // e.preventDefault(); // passive = true. OLD: was needed otherwise we get text sweep text selection as we drag around
        return true;
    };
    /** @internal call when the mouse gets released to drop the item at current location */
    DDDraggable.prototype._mouseUp = function (e) {
        var _a, _b;
        document.removeEventListener('mousemove', this._mouseMove, true);
        document.removeEventListener('mouseup', this._mouseUp, true);
        if (dd_touch_1.isTouch && e.currentTarget) { // destroy() during nested grid call us again wit fake _mouseUp
            e.currentTarget.removeEventListener('touchmove', dd_touch_1.touchmove, true);
            e.currentTarget.removeEventListener('touchend', dd_touch_1.touchend, true);
        }
        if (this.dragging) {
            delete this.dragging;
            (_a = this.el.gridstackNode) === null || _a === void 0 ? true : delete _a._origRotate;
            document.removeEventListener('keydown', this._keyEvent);
            // reset the drop target if dragging over ourself (already parented, just moving during stop callback below)
            if (((_b = dd_manager_1.DDManager.dropElement) === null || _b === void 0 ? void 0 : _b.el) === this.el.parentElement) {
                delete dd_manager_1.DDManager.dropElement;
            }
            this.helperContainment.style.position = this.parentOriginStylePosition || null;
            if (this.helper !== this.el)
                this.helper.remove(); // hide now
            this._removeHelperStyle();
            var ev = utils_1.Utils.initEvent(e, { target: this.el, type: 'dragstop' });
            if (this.option.stop) {
                this.option.stop(ev); // NOTE: destroy() will be called when removing item, so expect NULL ptr after!
            }
            this.triggerEvent('dragstop', ev);
            // call the droppable method to receive the item
            if (dd_manager_1.DDManager.dropElement) {
                dd_manager_1.DDManager.dropElement.drop(e);
            }
        }
        delete this.helper;
        delete this.mouseDownEvent;
        delete dd_manager_1.DDManager.dragElement;
        delete dd_manager_1.DDManager.dropElement;
        delete dd_manager_1.DDManager.mouseHandled;
        e.preventDefault();
    };
    /** @internal call when keys are being pressed - use Esc to cancel, R to rotate */
    DDDraggable.prototype._keyEvent = function (e) {
        var _a, _b;
        var n = this.el.gridstackNode;
        var grid = (n === null || n === void 0 ? void 0 : n.grid) || ((_b = (_a = dd_manager_1.DDManager.dropElement) === null || _a === void 0 ? void 0 : _a.el) === null || _b === void 0 ? void 0 : _b.gridstack);
        if (e.key === 'Escape') {
            if (n && n._origRotate) {
                n._orig = n._origRotate;
                delete n._origRotate;
            }
            grid === null || grid === void 0 ? void 0 : grid.cancelDrag();
            this._mouseUp(this.mouseDownEvent);
        }
        else if (n && grid && (e.key === 'r' || e.key === 'R')) {
            if (!utils_1.Utils.canBeRotated(n))
                return;
            n._origRotate = n._origRotate || __assign({}, n._orig); // store the real orig size in case we Esc after doing rotation
            delete n._moving; // force rotate to happen (move waits for >50% coverage otherwise)
            grid.setAnimation(false) // immediate rotate so _getDragOffset() gets the right dom size below
                .rotate(n.el, { top: -this.dragOffset.offsetTop, left: -this.dragOffset.offsetLeft })
                .setAnimation();
            n._moving = true;
            this.dragOffset = this._getDragOffset(this.lastDrag, n.el, this.helperContainment);
            this.helper.style.width = this.dragOffset.width + 'px';
            this.helper.style.height = this.dragOffset.height + 'px';
            utils_1.Utils.swap(n._orig, 'w', 'h');
            delete n._rect;
            this._mouseMove(this.lastDrag);
        }
    };
    /** @internal create a clone copy (or user defined method) of the original drag item if set */
    DDDraggable.prototype._createHelper = function () {
        var _this = this;
        var helper = this.el;
        if (typeof this.option.helper === 'function') {
            helper = this.option.helper(this.el);
        }
        else if (this.option.helper === 'clone') {
            helper = utils_1.Utils.cloneNode(this.el);
        }
        if (!document.body.contains(helper)) {
            utils_1.Utils.appendTo(helper, this.option.appendTo === 'parent' ? this.el.parentElement : this.option.appendTo);
        }
        this.dragElementOriginStyle = DDDraggable.originStyleProp.map(function (prop) { return _this.el.style[prop]; });
        return helper;
    };
    /** @internal set the fix position of the dragged item */
    DDDraggable.prototype._setupHelperStyle = function (e) {
        var _this = this;
        this.helper.classList.add('ui-draggable-dragging');
        // TODO: set all at once with style.cssText += ... ? https://stackoverflow.com/questions/3968593
        var style = this.helper.style;
        style.pointerEvents = 'none'; // needed for over items to get enter/leave
        // style.cursor = 'move'; //  TODO: can't set with pointerEvents=none ! (done in CSS as well)
        style.width = this.dragOffset.width + 'px';
        style.height = this.dragOffset.height + 'px';
        style.willChange = 'left, top';
        style.position = 'fixed'; // let us drag between grids by not clipping as parent .grid-stack is position: 'relative'
        this._dragFollow(e); // now position it
        style.transition = 'none'; // show up instantly
        setTimeout(function () {
            if (_this.helper) {
                style.transition = null; // recover animation
            }
        }, 0);
        return this;
    };
    /** @internal restore back the original style before dragging */
    DDDraggable.prototype._removeHelperStyle = function () {
        var _this = this;
        var _a;
        this.helper.classList.remove('ui-draggable-dragging');
        var node = (_a = this.helper) === null || _a === void 0 ? void 0 : _a.gridstackNode;
        // don't bother restoring styles if we're gonna remove anyway...
        if (!(node === null || node === void 0 ? void 0 : node._isAboutToRemove) && this.dragElementOriginStyle) {
            var helper_1 = this.helper;
            // don't animate, otherwise we animate offseted when switching back to 'absolute' from 'fixed'.
            // TODO: this also removes resizing animation which doesn't have this issue, but others.
            // Ideally both would animate ('move' would immediately restore 'absolute' and adjust coordinate to match,
            // then trigger a delay (repaint) to restore to final dest with animate) but then we need to make sure 'resizestop'
            // is called AFTER 'transitionend' event is received (see https://github.com/gridstack/gridstack.js/issues/2033)
            var transition_1 = this.dragElementOriginStyle['transition'] || null;
            helper_1.style.transition = this.dragElementOriginStyle['transition'] = 'none'; // can't be NULL #1973
            DDDraggable.originStyleProp.forEach(function (prop) { return helper_1.style[prop] = _this.dragElementOriginStyle[prop] || null; });
            setTimeout(function () { return helper_1.style.transition = transition_1; }, 50); // recover animation from saved vars after a pause (0 isn't enough #1973)
        }
        delete this.dragElementOriginStyle;
        return this;
    };
    /** @internal updates the top/left position to follow the mouse */
    DDDraggable.prototype._dragFollow = function (e) {
        var containmentRect = { left: 0, top: 0 };
        // if (this.helper.style.position === 'absolute') { // we use 'fixed'
        //   const { left, top } = this.helperContainment.getBoundingClientRect();
        //   containmentRect = { left, top };
        // }
        var style = this.helper.style;
        var offset = this.dragOffset;
        style.left = (e.clientX + offset.offsetLeft - containmentRect.left) * this.dragTransform.xScale + 'px';
        style.top = (e.clientY + offset.offsetTop - containmentRect.top) * this.dragTransform.yScale + 'px';
    };
    /** @internal */
    DDDraggable.prototype._setupHelperContainmentStyle = function () {
        this.helperContainment = this.helper.parentElement;
        if (this.helper.style.position !== 'fixed') {
            this.parentOriginStylePosition = this.helperContainment.style.position;
            if (getComputedStyle(this.helperContainment).position.match(/static/)) {
                this.helperContainment.style.position = 'relative';
            }
        }
        return this;
    };
    /** @internal */
    DDDraggable.prototype._getDragOffset = function (event, el, parent) {
        // in case ancestor has transform/perspective css properties that change the viewpoint
        var xformOffsetX = 0;
        var xformOffsetY = 0;
        if (parent) {
            xformOffsetX = this.dragTransform.xOffset;
            xformOffsetY = this.dragTransform.yOffset;
        }
        var targetOffset = el.getBoundingClientRect();
        return {
            left: targetOffset.left,
            top: targetOffset.top,
            offsetLeft: -event.clientX + targetOffset.left - xformOffsetX,
            offsetTop: -event.clientY + targetOffset.top - xformOffsetY,
            width: targetOffset.width * this.dragTransform.xScale,
            height: targetOffset.height * this.dragTransform.yScale
        };
    };
    /** @internal TODO: set to public as called by DDDroppable! */
    DDDraggable.prototype.ui = function () {
        var containmentEl = this.el.parentElement;
        var containmentRect = containmentEl.getBoundingClientRect();
        var offset = this.helper.getBoundingClientRect();
        return {
            position: {
                top: (offset.top - containmentRect.top) * this.dragTransform.yScale,
                left: (offset.left - containmentRect.left) * this.dragTransform.xScale
            }
            /* not used by GridStack for now...
            helper: [this.helper], //The object arr representing the helper that's being dragged.
            offset: { top: offset.top, left: offset.left } // Current offset position of the helper as { top, left } object.
            */
        };
    };
    /** @internal properties we change during dragging, and restore back */
    DDDraggable.originStyleProp = ['width', 'height', 'transform', 'transform-origin', 'transition', 'pointerEvents', 'position', 'left', 'top', 'minWidth', 'willChange'];
    return DDDraggable;
}(dd_base_impl_1.DDBaseImplement));
//# sourceMappingURL=dd-draggable.js.map