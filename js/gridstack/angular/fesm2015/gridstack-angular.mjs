import * as i0 from '@angular/core';
import { ViewContainerRef, Component, ViewChild, Input, EventEmitter, reflectComponentType, ContentChildren, Output, Injectable, NgModule } from '@angular/core';
import { NgIf } from '@angular/common';
import { GridStack } from 'gridstack';

/**
 * gridstack-item.component.ts 11.3.0
 * Copyright (c) 2022-2024 Alain Dumesny - see GridStack root license
 */
/**
 * HTML Component Wrapper for gridstack items, in combination with GridstackComponent for parent grid
 */
class GridstackItemComponent {
    constructor(elementRef) {
        this.elementRef = elementRef;
        this.el._gridItemComp = this;
    }
    /** list of options for creating/updating this item */
    set options(val) {
        var _a;
        const grid = (_a = this.el.gridstackNode) === null || _a === void 0 ? void 0 : _a.grid;
        if (grid) {
            // already built, do an update...
            grid.update(this.el, val);
        }
        else {
            // store our custom element in options so we can update it and not re-create a generic div!
            this._options = Object.assign(Object.assign({}, val), { el: this.el });
        }
    }
    /** return the latest grid options (from GS once built, otherwise initial values) */
    get options() {
        return this.el.gridstackNode || this._options || { el: this.el };
    }
    /** return the native element that contains grid specific fields as well */
    get el() { return this.elementRef.nativeElement; }
    /** clears the initial options now that we've built */
    clearOptions() {
        delete this._options;
    }
    ngOnDestroy() {
        this.clearOptions();
        delete this.childWidget;
        delete this.el._gridItemComp;
        delete this.container;
        delete this.ref;
    }
}
GridstackItemComponent.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: GridstackItemComponent, deps: [{ token: i0.ElementRef }], target: i0.ɵɵFactoryTarget.Component });
GridstackItemComponent.ɵcmp = i0.ɵɵngDeclareComponent({ minVersion: "14.0.0", version: "14.3.0", type: GridstackItemComponent, isStandalone: true, selector: "gridstack-item", inputs: { options: "options" }, viewQueries: [{ propertyName: "container", first: true, predicate: ["container"], descendants: true, read: ViewContainerRef, static: true }], ngImport: i0, template: `
    <div class="grid-stack-item-content">
      <!-- where dynamic items go based on component types, or sub-grids, etc...) -->
      <ng-template #container></ng-template>
      <!-- any static (defined in dom) content goes here -->
      <ng-content></ng-content>
      <!-- fallback HTML content from GridStackWidget content field if used instead -->
      {{options.content}}
    </div>`, isInline: true, styles: [":host{display:block}\n"] });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: GridstackItemComponent, decorators: [{
            type: Component,
            args: [{ selector: 'gridstack-item', template: `
    <div class="grid-stack-item-content">
      <!-- where dynamic items go based on component types, or sub-grids, etc...) -->
      <ng-template #container></ng-template>
      <!-- any static (defined in dom) content goes here -->
      <ng-content></ng-content>
      <!-- fallback HTML content from GridStackWidget content field if used instead -->
      {{options.content}}
    </div>`, standalone: true, styles: [":host{display:block}\n"] }]
        }], ctorParameters: function () { return [{ type: i0.ElementRef }]; }, propDecorators: { container: [{
                type: ViewChild,
                args: ['container', { read: ViewContainerRef, static: true }]
            }], options: [{
                type: Input
            }] } });

/**
 * gridstack.component.ts 11.3.0
 * Copyright (c) 2022-2024 Alain Dumesny - see GridStack root license
 */
/**
 * HTML Component Wrapper for gridstack, in combination with GridstackItemComponent for the items
 */
class GridstackComponent {
    constructor(
    // protected readonly zone: NgZone,
    // protected readonly cd: ChangeDetectorRef,
    elementRef) {
        this.elementRef = elementRef;
        /** individual list of GridStackEvent callbacks handlers as output
         * otherwise use this.grid.on('name1 name2 name3', callback) to handle multiple at once
         * see https://github.com/gridstack/gridstack.js/blob/master/demo/events.js#L4
         *
         * Note: camel casing and 'CB' added at the end to prevent @angular-eslint/no-output-native
         * eg: 'change' would trigger the raw CustomEvent so use different name.
         */
        this.addedCB = new EventEmitter();
        this.changeCB = new EventEmitter();
        this.disableCB = new EventEmitter();
        this.dragCB = new EventEmitter();
        this.dragStartCB = new EventEmitter();
        this.dragStopCB = new EventEmitter();
        this.droppedCB = new EventEmitter();
        this.enableCB = new EventEmitter();
        this.removedCB = new EventEmitter();
        this.resizeCB = new EventEmitter();
        this.resizeStartCB = new EventEmitter();
        this.resizeStopCB = new EventEmitter();
        // set globally our method to create the right widget type
        if (!GridStack.addRemoveCB) {
            GridStack.addRemoveCB = gsCreateNgComponents;
        }
        if (!GridStack.saveCB) {
            GridStack.saveCB = gsSaveAdditionalNgInfo;
        }
        this.el._gridComp = this;
    }
    /** initial options for creation of the grid */
    set options(val) { this._options = val; }
    /** return the current running options */
    get options() { var _a; return ((_a = this._grid) === null || _a === void 0 ? void 0 : _a.opts) || this._options || {}; }
    /** return the native element that contains grid specific fields as well */
    get el() { return this.elementRef.nativeElement; }
    /** return the GridStack class */
    get grid() { return this._grid; }
    /** add a list of ng Component to be mapped to selector */
    static addComponentToSelectorType(typeList) {
        typeList.forEach(type => GridstackComponent.selectorToType[GridstackComponent.getSelector(type)] = type);
    }
    /** return the ng Component selector */
    static getSelector(type) {
        return reflectComponentType(type).selector;
    }
    ngOnInit() {
        var _a, _b;
        // init ourself before any template children are created since we track them below anyway - no need to double create+update widgets
        this.loaded = !!((_b = (_a = this.options) === null || _a === void 0 ? void 0 : _a.children) === null || _b === void 0 ? void 0 : _b.length);
        this._grid = GridStack.init(this._options, this.el);
        delete this._options; // GS has it now
        this.checkEmpty();
    }
    /** wait until after all DOM is ready to init gridstack children (after angular ngFor and sub-components run first) */
    ngAfterContentInit() {
        var _a;
        // track whenever the children list changes and update the layout...
        this._sub = (_a = this.gridstackItems) === null || _a === void 0 ? void 0 : _a.changes.subscribe(() => this.updateAll());
        // ...and do this once at least unless we loaded children already
        if (!this.loaded)
            this.updateAll();
        this.hookEvents(this.grid);
    }
    ngOnDestroy() {
        var _a, _b;
        this.unhookEvents(this._grid);
        (_a = this._sub) === null || _a === void 0 ? void 0 : _a.unsubscribe();
        (_b = this._grid) === null || _b === void 0 ? void 0 : _b.destroy();
        delete this._grid;
        delete this.el._gridComp;
        delete this.container;
        delete this.ref;
    }
    /**
     * called when the TEMPLATE list of items changes - get a list of nodes and
     * update the layout accordingly (which will take care of adding/removing items changed by Angular)
     */
    updateAll() {
        var _a;
        if (!this.grid)
            return;
        const layout = [];
        (_a = this.gridstackItems) === null || _a === void 0 ? void 0 : _a.forEach(item => {
            layout.push(item.options);
            item.clearOptions();
        });
        this.grid.load(layout); // efficient that does diffs only
    }
    /** check if the grid is empty, if so show alternative content */
    checkEmpty() {
        if (!this.grid)
            return;
        const isEmpty = !this.grid.engine.nodes.length;
        if (isEmpty === this.isEmpty)
            return;
        this.isEmpty = isEmpty;
        // this.cd.detectChanges();
    }
    /** get all known events as easy to use Outputs for convenience */
    hookEvents(grid) {
        if (!grid)
            return;
        grid
            .on('added', (event, nodes) => { this.checkEmpty(); this.addedCB.emit({ event, nodes }); })
            .on('change', (event, nodes) => this.changeCB.emit({ event, nodes }))
            .on('disable', (event) => this.disableCB.emit({ event }))
            .on('drag', (event, el) => this.dragCB.emit({ event, el }))
            .on('dragstart', (event, el) => this.dragStartCB.emit({ event, el }))
            .on('dragstop', (event, el) => this.dragStopCB.emit({ event, el }))
            .on('dropped', (event, previousNode, newNode) => this.droppedCB.emit({ event, previousNode, newNode }))
            .on('enable', (event) => this.enableCB.emit({ event }))
            .on('removed', (event, nodes) => { this.checkEmpty(); this.removedCB.emit({ event, nodes }); })
            .on('resize', (event, el) => this.resizeCB.emit({ event, el }))
            .on('resizestart', (event, el) => this.resizeStartCB.emit({ event, el }))
            .on('resizestop', (event, el) => this.resizeStopCB.emit({ event, el }));
    }
    unhookEvents(grid) {
        if (!grid)
            return;
        grid.off('added change disable drag dragstart dragstop dropped enable removed resize resizestart resizestop');
    }
}
/**
 * stores the selector -> Type mapping, so we can create items dynamically from a string.
 * Unfortunately Ng doesn't provide public access to that mapping.
 */
GridstackComponent.selectorToType = {};
GridstackComponent.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: GridstackComponent, deps: [{ token: i0.ElementRef }], target: i0.ɵɵFactoryTarget.Component });
GridstackComponent.ɵcmp = i0.ɵɵngDeclareComponent({ minVersion: "14.0.0", version: "14.3.0", type: GridstackComponent, isStandalone: true, selector: "gridstack", inputs: { options: "options", isEmpty: "isEmpty" }, outputs: { addedCB: "addedCB", changeCB: "changeCB", disableCB: "disableCB", dragCB: "dragCB", dragStartCB: "dragStartCB", dragStopCB: "dragStopCB", droppedCB: "droppedCB", enableCB: "enableCB", removedCB: "removedCB", resizeCB: "resizeCB", resizeStartCB: "resizeStartCB", resizeStopCB: "resizeStopCB" }, queries: [{ propertyName: "gridstackItems", predicate: GridstackItemComponent }], viewQueries: [{ propertyName: "container", first: true, predicate: ["container"], descendants: true, read: ViewContainerRef, static: true }], ngImport: i0, template: `
    <!-- content to show when when grid is empty, like instructions on how to add widgets -->
    <ng-content select="[empty-content]" *ngIf="isEmpty"></ng-content>
    <!-- where dynamic items go -->
    <ng-template #container></ng-template>
    <!-- where template items go -->
    <ng-content></ng-content>
  `, isInline: true, styles: [":host{display:block}\n"], dependencies: [{ kind: "directive", type: NgIf, selector: "[ngIf]", inputs: ["ngIf", "ngIfThen", "ngIfElse"] }] });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: GridstackComponent, decorators: [{
            type: Component,
            args: [{ selector: 'gridstack', template: `
    <!-- content to show when when grid is empty, like instructions on how to add widgets -->
    <ng-content select="[empty-content]" *ngIf="isEmpty"></ng-content>
    <!-- where dynamic items go -->
    <ng-template #container></ng-template>
    <!-- where template items go -->
    <ng-content></ng-content>
  `, standalone: true, imports: [NgIf], styles: [":host{display:block}\n"] }]
        }], ctorParameters: function () { return [{ type: i0.ElementRef }]; }, propDecorators: { gridstackItems: [{
                type: ContentChildren,
                args: [GridstackItemComponent]
            }], container: [{
                type: ViewChild,
                args: ['container', { read: ViewContainerRef, static: true }]
            }], options: [{
                type: Input
            }], isEmpty: [{
                type: Input
            }], addedCB: [{
                type: Output
            }], changeCB: [{
                type: Output
            }], disableCB: [{
                type: Output
            }], dragCB: [{
                type: Output
            }], dragStartCB: [{
                type: Output
            }], dragStopCB: [{
                type: Output
            }], droppedCB: [{
                type: Output
            }], enableCB: [{
                type: Output
            }], removedCB: [{
                type: Output
            }], resizeCB: [{
                type: Output
            }], resizeStartCB: [{
                type: Output
            }], resizeStopCB: [{
                type: Output
            }] } });
/**
 * can be used when a new item needs to be created, which we do as a Angular component, or deleted (skip)
 **/
function gsCreateNgComponents(host, n, add, isGrid) {
    var _a, _b, _c, _d, _e, _f, _g;
    if (add) {
        //
        // create the component dynamically - see https://angular.io/docs/ts/latest/cookbook/dynamic-component-loader.html
        //
        if (!host)
            return;
        if (isGrid) {
            // TODO: figure out how to create ng component inside regular Div. need to access app injectors...
            // if (!container) {
            //   const hostElement: Element = host;
            //   const environmentInjector: EnvironmentInjector;
            //   grid = createComponent(GridstackComponent, {environmentInjector, hostElement})?.instance;
            // }
            const gridItemComp = (_a = host.parentElement) === null || _a === void 0 ? void 0 : _a._gridItemComp;
            if (!gridItemComp)
                return;
            // check if gridItem has a child component with 'container' exposed to create under..
            const container = ((_b = gridItemComp.childWidget) === null || _b === void 0 ? void 0 : _b.container) || gridItemComp.container;
            const gridRef = container === null || container === void 0 ? void 0 : container.createComponent(GridstackComponent);
            const grid = gridRef === null || gridRef === void 0 ? void 0 : gridRef.instance;
            if (!grid)
                return;
            grid.ref = gridRef;
            grid.options = n;
            return grid.el;
        }
        else {
            const gridComp = host._gridComp;
            const gridItemRef = (_c = gridComp === null || gridComp === void 0 ? void 0 : gridComp.container) === null || _c === void 0 ? void 0 : _c.createComponent(GridstackItemComponent);
            const gridItem = gridItemRef === null || gridItemRef === void 0 ? void 0 : gridItemRef.instance;
            if (!gridItem)
                return;
            gridItem.ref = gridItemRef;
            // define what type of component to create as child, OR you can do it GridstackItemComponent template, but this is more generic
            const selector = n.selector;
            const type = selector ? GridstackComponent.selectorToType[selector] : undefined;
            if (type) {
                // shared code to create our selector component
                const createComp = () => {
                    var _a, _b;
                    const childWidget = (_b = (_a = gridItem.container) === null || _a === void 0 ? void 0 : _a.createComponent(type)) === null || _b === void 0 ? void 0 : _b.instance;
                    // if proper BaseWidget subclass, save it and load additional data
                    if (childWidget && typeof childWidget.serialize === 'function' && typeof childWidget.deserialize === 'function') {
                        gridItem.childWidget = childWidget;
                        childWidget.deserialize(n);
                    }
                };
                const lazyLoad = n.lazyLoad || ((_e = (_d = n.grid) === null || _d === void 0 ? void 0 : _d.opts) === null || _e === void 0 ? void 0 : _e.lazyLoad) && n.lazyLoad !== false;
                if (lazyLoad) {
                    if (!n.visibleObservable) {
                        n.visibleObservable = new IntersectionObserver(([entry]) => {
                            var _a;
                            if (entry.isIntersecting) {
                                (_a = n.visibleObservable) === null || _a === void 0 ? void 0 : _a.disconnect();
                                delete n.visibleObservable;
                                createComp();
                            }
                        });
                        window.setTimeout(() => { var _a; return (_a = n.visibleObservable) === null || _a === void 0 ? void 0 : _a.observe(gridItem.el); }); // wait until callee sets position attributes
                    }
                }
                else
                    createComp();
            }
            return gridItem.el;
        }
    }
    else {
        //
        // REMOVE - have to call ComponentRef:destroy() for dynamic objects to correctly remove themselves
        // Note: this will destroy all children dynamic components as well: gridItem -> childWidget
        //
        if (isGrid) {
            const grid = (_f = n.el) === null || _f === void 0 ? void 0 : _f._gridComp;
            if (grid === null || grid === void 0 ? void 0 : grid.ref)
                grid.ref.destroy();
            else
                grid === null || grid === void 0 ? void 0 : grid.ngOnDestroy();
        }
        else {
            const gridItem = (_g = n.el) === null || _g === void 0 ? void 0 : _g._gridItemComp;
            if (gridItem === null || gridItem === void 0 ? void 0 : gridItem.ref)
                gridItem.ref.destroy();
            else
                gridItem === null || gridItem === void 0 ? void 0 : gridItem.ngOnDestroy();
        }
    }
    return;
}
/**
 * called for each item in the grid - check if additional information needs to be saved.
 * Note: since this is options minus gridstack protected members using Utils.removeInternalForSave(),
 * this typically doesn't need to do anything. However your custom Component @Input() are now supported
 * using BaseWidget.serialize()
 */
function gsSaveAdditionalNgInfo(n, w) {
    var _a, _b, _c;
    const gridItem = (_a = n.el) === null || _a === void 0 ? void 0 : _a._gridItemComp;
    if (gridItem) {
        const input = (_b = gridItem.childWidget) === null || _b === void 0 ? void 0 : _b.serialize();
        if (input) {
            w.input = input;
        }
        return;
    }
    // else check if Grid
    const grid = (_c = n.el) === null || _c === void 0 ? void 0 : _c._gridComp;
    if (grid) {
        //.... save any custom data
    }
}

/**
 * gridstack-item.component.ts 11.3.0
 * Copyright (c) 2022-2024 Alain Dumesny - see GridStack root license
 */
class BaseWidget {
    /**
     * REDEFINE to return an object representing the data needed to re-create yourself, other than `selector` already handled.
     * This should map directly to the @Input() fields of this objects on create, so a simple apply can be used on read
     */
    serialize() { return; }
    /**
     * REDEFINE this if your widget needs to read from saved data and transform it to create itself - you do this for
     * things that are not mapped directly into @Input() fields for example.
     */
    deserialize(w) {
        // save full description for meta data
        this.widgetItem = w;
        if (!w)
            return;
        if (w.input)
            Object.assign(this, w.input);
    }
}
BaseWidget.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: BaseWidget, deps: [], target: i0.ɵɵFactoryTarget.Injectable });
BaseWidget.ɵprov = i0.ɵɵngDeclareInjectable({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: BaseWidget });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: BaseWidget, decorators: [{
            type: Injectable
        }] });

/**
 * gridstack.component.ts 11.3.0
 * Copyright (c) 2022-2024 Alain Dumesny - see GridStack root license
 */
// @deprecated use GridstackComponent and GridstackItemComponent as standalone components
class GridstackModule {
}
GridstackModule.ɵfac = i0.ɵɵngDeclareFactory({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: GridstackModule, deps: [], target: i0.ɵɵFactoryTarget.NgModule });
GridstackModule.ɵmod = i0.ɵɵngDeclareNgModule({ minVersion: "14.0.0", version: "14.3.0", ngImport: i0, type: GridstackModule, imports: [GridstackComponent,
        GridstackItemComponent], exports: [GridstackComponent,
        GridstackItemComponent] });
GridstackModule.ɵinj = i0.ɵɵngDeclareInjector({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: GridstackModule, imports: [GridstackComponent,
        GridstackItemComponent] });
i0.ɵɵngDeclareClassMetadata({ minVersion: "12.0.0", version: "14.3.0", ngImport: i0, type: GridstackModule, decorators: [{
            type: NgModule,
            args: [{
                    imports: [
                        GridstackComponent,
                        GridstackItemComponent,
                    ],
                    exports: [
                        GridstackComponent,
                        GridstackItemComponent,
                    ],
                }]
        }] });

/*
 * Public API Surface of gridstack-angular
 */

/**
 * Generated bundle index. Do not edit.
 */

export { BaseWidget, GridstackComponent, GridstackItemComponent, GridstackModule, gsCreateNgComponents, gsSaveAdditionalNgInfo };
//# sourceMappingURL=gridstack-angular.mjs.map
