/**
 * gridstack-item.component.ts 11.3.0
 * Copyright (c) 2022-2024 Alain Dumesny - see GridStack root license
 */
/**
 * Base interface that all widgets need to implement in order for GridstackItemComponent to correctly save/load/delete/..
 */
import { Injectable } from '@angular/core';
import * as i0 from "@angular/core";
export class BaseWidget {
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
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYmFzZS13aWRnZXQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlcyI6WyIuLi8uLi8uLi8uLi9hbmd1bGFyL3Byb2plY3RzL2xpYi9zcmMvbGliL2Jhc2Utd2lkZ2V0LnRzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBOzs7R0FHRztBQUVIOztHQUVHO0FBRUgsT0FBTyxFQUFFLFVBQVUsRUFBRSxNQUFNLGVBQWUsQ0FBQzs7QUFJMUMsTUFBTSxPQUFnQixVQUFVO0lBSy9COzs7T0FHRztJQUNJLFNBQVMsS0FBZ0MsT0FBTyxDQUFDLENBQUM7SUFFekQ7OztPQUdHO0lBQ0ksV0FBVyxDQUFDLENBQW9CO1FBQ3JDLHNDQUFzQztRQUN0QyxJQUFJLENBQUMsVUFBVSxHQUFHLENBQUMsQ0FBQztRQUNwQixJQUFJLENBQUMsQ0FBQztZQUFFLE9BQU87UUFFZixJQUFJLENBQUMsQ0FBQyxLQUFLO1lBQUcsTUFBTSxDQUFDLE1BQU0sQ0FBQyxJQUFJLEVBQUUsQ0FBQyxDQUFDLEtBQUssQ0FBQyxDQUFDO0lBQzdDLENBQUM7O3VHQXJCb0IsVUFBVTsyR0FBVixVQUFVOzJGQUFWLFVBQVU7a0JBRC9CLFVBQVUiLCJzb3VyY2VzQ29udGVudCI6WyIvKipcclxuICogZ3JpZHN0YWNrLWl0ZW0uY29tcG9uZW50LnRzIDExLjMuMFxyXG4gKiBDb3B5cmlnaHQgKGMpIDIwMjItMjAyNCBBbGFpbiBEdW1lc255IC0gc2VlIEdyaWRTdGFjayByb290IGxpY2Vuc2VcclxuICovXHJcblxyXG4vKipcclxuICogQmFzZSBpbnRlcmZhY2UgdGhhdCBhbGwgd2lkZ2V0cyBuZWVkIHRvIGltcGxlbWVudCBpbiBvcmRlciBmb3IgR3JpZHN0YWNrSXRlbUNvbXBvbmVudCB0byBjb3JyZWN0bHkgc2F2ZS9sb2FkL2RlbGV0ZS8uLlxyXG4gKi9cclxuXHJcbmltcG9ydCB7IEluamVjdGFibGUgfSBmcm9tICdAYW5ndWxhci9jb3JlJztcclxuaW1wb3J0IHsgTmdDb21wSW5wdXRzLCBOZ0dyaWRTdGFja1dpZGdldCB9IGZyb20gJy4vZ3JpZHN0YWNrLmNvbXBvbmVudCc7XHJcblxyXG4gQEluamVjdGFibGUoKVxyXG4gZXhwb3J0IGFic3RyYWN0IGNsYXNzIEJhc2VXaWRnZXQge1xyXG5cclxuICAvKiogdmFyaWFibGUgdGhhdCBob2xkcyB0aGUgY29tcGxldGUgZGVmaW5pdGlvbiBvZiB0aGlzIHdpZGdldHMgKHdpdGggc2VsZWN0b3IseCx5LHcsaCkgKi9cclxuICBwdWJsaWMgd2lkZ2V0SXRlbT86IE5nR3JpZFN0YWNrV2lkZ2V0O1xyXG5cclxuICAvKipcclxuICAgKiBSRURFRklORSB0byByZXR1cm4gYW4gb2JqZWN0IHJlcHJlc2VudGluZyB0aGUgZGF0YSBuZWVkZWQgdG8gcmUtY3JlYXRlIHlvdXJzZWxmLCBvdGhlciB0aGFuIGBzZWxlY3RvcmAgYWxyZWFkeSBoYW5kbGVkLlxyXG4gICAqIFRoaXMgc2hvdWxkIG1hcCBkaXJlY3RseSB0byB0aGUgQElucHV0KCkgZmllbGRzIG9mIHRoaXMgb2JqZWN0cyBvbiBjcmVhdGUsIHNvIGEgc2ltcGxlIGFwcGx5IGNhbiBiZSB1c2VkIG9uIHJlYWRcclxuICAgKi9cclxuICBwdWJsaWMgc2VyaWFsaXplKCk6IE5nQ29tcElucHV0cyB8IHVuZGVmaW5lZCAgeyByZXR1cm47IH1cclxuXHJcbiAgLyoqXHJcbiAgICogUkVERUZJTkUgdGhpcyBpZiB5b3VyIHdpZGdldCBuZWVkcyB0byByZWFkIGZyb20gc2F2ZWQgZGF0YSBhbmQgdHJhbnNmb3JtIGl0IHRvIGNyZWF0ZSBpdHNlbGYgLSB5b3UgZG8gdGhpcyBmb3JcclxuICAgKiB0aGluZ3MgdGhhdCBhcmUgbm90IG1hcHBlZCBkaXJlY3RseSBpbnRvIEBJbnB1dCgpIGZpZWxkcyBmb3IgZXhhbXBsZS5cclxuICAgKi9cclxuICBwdWJsaWMgZGVzZXJpYWxpemUodzogTmdHcmlkU3RhY2tXaWRnZXQpICB7XHJcbiAgICAvLyBzYXZlIGZ1bGwgZGVzY3JpcHRpb24gZm9yIG1ldGEgZGF0YVxyXG4gICAgdGhpcy53aWRnZXRJdGVtID0gdztcclxuICAgIGlmICghdykgcmV0dXJuO1xyXG5cclxuICAgIGlmICh3LmlucHV0KSAgT2JqZWN0LmFzc2lnbih0aGlzLCB3LmlucHV0KTtcclxuICB9XHJcbiB9XHJcbiJdfQ==