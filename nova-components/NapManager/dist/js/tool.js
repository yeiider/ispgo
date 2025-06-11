/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@headlessui/vue/dist/components/tabs/tabs.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/components/tabs/tabs.js ***!
  \*******************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Tab: () => (/* binding */ xe),
/* harmony export */   TabGroup: () => (/* binding */ me),
/* harmony export */   TabList: () => (/* binding */ pe),
/* harmony export */   TabPanel: () => (/* binding */ ye),
/* harmony export */   TabPanels: () => (/* binding */ Ie)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var _hooks_use_id_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../hooks/use-id.js */ "./node_modules/@headlessui/vue/dist/hooks/use-id.js");
/* harmony import */ var _hooks_use_resolve_button_type_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../../hooks/use-resolve-button-type.js */ "./node_modules/@headlessui/vue/dist/hooks/use-resolve-button-type.js");
/* harmony import */ var _internal_focus_sentinel_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../internal/focus-sentinel.js */ "./node_modules/@headlessui/vue/dist/internal/focus-sentinel.js");
/* harmony import */ var _internal_hidden_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../../internal/hidden.js */ "./node_modules/@headlessui/vue/dist/internal/hidden.js");
/* harmony import */ var _keyboard_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../keyboard.js */ "./node_modules/@headlessui/vue/dist/keyboard.js");
/* harmony import */ var _utils_dom_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utils/dom.js */ "./node_modules/@headlessui/vue/dist/utils/dom.js");
/* harmony import */ var _utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utils/focus-management.js */ "./node_modules/@headlessui/vue/dist/utils/focus-management.js");
/* harmony import */ var _utils_match_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../utils/match.js */ "./node_modules/@headlessui/vue/dist/utils/match.js");
/* harmony import */ var _utils_micro_task_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../../utils/micro-task.js */ "./node_modules/@headlessui/vue/dist/utils/micro-task.js");
/* harmony import */ var _utils_owner_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../utils/owner.js */ "./node_modules/@headlessui/vue/dist/utils/owner.js");
/* harmony import */ var _utils_render_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../utils/render.js */ "./node_modules/@headlessui/vue/dist/utils/render.js");
var te=(s=>(s[s.Forwards=0]="Forwards",s[s.Backwards=1]="Backwards",s))(te||{}),le=(d=>(d[d.Less=-1]="Less",d[d.Equal=0]="Equal",d[d.Greater=1]="Greater",d))(le||{});let U=Symbol("TabsContext");function C(a){let b=(0,vue__WEBPACK_IMPORTED_MODULE_0__.inject)(U,null);if(b===null){let s=new Error(`<${a} /> is missing a parent <TabGroup /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(s,C),s}return b}let G=Symbol("TabsSSRContext"),me=(0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({name:"TabGroup",emits:{change:a=>!0},props:{as:{type:[Object,String],default:"template"},selectedIndex:{type:[Number],default:null},defaultIndex:{type:[Number],default:0},vertical:{type:[Boolean],default:!1},manual:{type:[Boolean],default:!1}},inheritAttrs:!1,setup(a,{slots:b,attrs:s,emit:d}){var E;let i=(0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)((E=a.selectedIndex)!=null?E:a.defaultIndex),l=(0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)([]),r=(0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)([]),p=(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>a.selectedIndex!==null),R=(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>p.value?a.selectedIndex:i.value);function y(t){var c;let n=(0,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.sortByDomNode)(u.tabs.value,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom),o=(0,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.sortByDomNode)(u.panels.value,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom),e=n.filter(I=>{var m;return!((m=(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom)(I))!=null&&m.hasAttribute("disabled"))});if(t<0||t>n.length-1){let I=(0,_utils_match_js__WEBPACK_IMPORTED_MODULE_3__.match)(i.value===null?0:Math.sign(t-i.value),{[-1]:()=>1,[0]:()=>(0,_utils_match_js__WEBPACK_IMPORTED_MODULE_3__.match)(Math.sign(t),{[-1]:()=>0,[0]:()=>0,[1]:()=>1}),[1]:()=>0}),m=(0,_utils_match_js__WEBPACK_IMPORTED_MODULE_3__.match)(I,{[0]:()=>n.indexOf(e[0]),[1]:()=>n.indexOf(e[e.length-1])});m!==-1&&(i.value=m),u.tabs.value=n,u.panels.value=o}else{let I=n.slice(0,t),h=[...n.slice(t),...I].find(W=>e.includes(W));if(!h)return;let O=(c=n.indexOf(h))!=null?c:u.selectedIndex.value;O===-1&&(O=u.selectedIndex.value),i.value=O,u.tabs.value=n,u.panels.value=o}}let u={selectedIndex:(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>{var t,n;return(n=(t=i.value)!=null?t:a.defaultIndex)!=null?n:null}),orientation:(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>a.vertical?"vertical":"horizontal"),activation:(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>a.manual?"manual":"auto"),tabs:l,panels:r,setSelectedIndex(t){R.value!==t&&d("change",t),p.value||y(t)},registerTab(t){var o;if(l.value.includes(t))return;let n=l.value[i.value];if(l.value.push(t),l.value=(0,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.sortByDomNode)(l.value,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom),!p.value){let e=(o=l.value.indexOf(n))!=null?o:i.value;e!==-1&&(i.value=e)}},unregisterTab(t){let n=l.value.indexOf(t);n!==-1&&l.value.splice(n,1)},registerPanel(t){r.value.includes(t)||(r.value.push(t),r.value=(0,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.sortByDomNode)(r.value,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom))},unregisterPanel(t){let n=r.value.indexOf(t);n!==-1&&r.value.splice(n,1)}};(0,vue__WEBPACK_IMPORTED_MODULE_0__.provide)(U,u);let T=(0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)({tabs:[],panels:[]}),x=(0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(!1);(0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(()=>{x.value=!0}),(0,vue__WEBPACK_IMPORTED_MODULE_0__.provide)(G,(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>x.value?null:T.value));let w=(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>a.selectedIndex);return (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(()=>{(0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)([w],()=>{var t;return y((t=a.selectedIndex)!=null?t:a.defaultIndex)},{immediate:!0})}),(0,vue__WEBPACK_IMPORTED_MODULE_0__.watchEffect)(()=>{if(!p.value||R.value==null||u.tabs.value.length<=0)return;let t=(0,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.sortByDomNode)(u.tabs.value,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom);t.some((o,e)=>(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom)(u.tabs.value[e])!==(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom)(o))&&u.setSelectedIndex(t.findIndex(o=>(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom)(o)===(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom)(u.tabs.value[R.value])))}),()=>{let t={selectedIndex:i.value};return (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment,[l.value.length<=0&&(0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(_internal_focus_sentinel_js__WEBPACK_IMPORTED_MODULE_4__.FocusSentinel,{onFocus:()=>{for(let n of l.value){let o=(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom)(n);if((o==null?void 0:o.tabIndex)===0)return o.focus(),!0}return!1}}),(0,_utils_render_js__WEBPACK_IMPORTED_MODULE_5__.render)({theirProps:{...s,...(0,_utils_render_js__WEBPACK_IMPORTED_MODULE_5__.omit)(a,["selectedIndex","defaultIndex","manual","vertical","onChange"])},ourProps:{},slot:t,slots:b,attrs:s,name:"TabGroup"})])}}}),pe=(0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({name:"TabList",props:{as:{type:[Object,String],default:"div"}},setup(a,{attrs:b,slots:s}){let d=C("TabList");return()=>{let i={selectedIndex:d.selectedIndex.value},l={role:"tablist","aria-orientation":d.orientation.value};return (0,_utils_render_js__WEBPACK_IMPORTED_MODULE_5__.render)({ourProps:l,theirProps:a,slot:i,attrs:b,slots:s,name:"TabList"})}}}),xe=(0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({name:"Tab",props:{as:{type:[Object,String],default:"button"},disabled:{type:[Boolean],default:!1},id:{type:String,default:null}},setup(a,{attrs:b,slots:s,expose:d}){var o;let i=(o=a.id)!=null?o:`headlessui-tabs-tab-${(0,_hooks_use_id_js__WEBPACK_IMPORTED_MODULE_6__.useId)()}`,l=C("Tab"),r=(0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);d({el:r,$el:r}),(0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(()=>l.registerTab(r)),(0,vue__WEBPACK_IMPORTED_MODULE_0__.onUnmounted)(()=>l.unregisterTab(r));let p=(0,vue__WEBPACK_IMPORTED_MODULE_0__.inject)(G),R=(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>{if(p.value){let e=p.value.tabs.indexOf(i);return e===-1?p.value.tabs.push(i)-1:e}return-1}),y=(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>{let e=l.tabs.value.indexOf(r);return e===-1?R.value:e}),u=(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>y.value===l.selectedIndex.value);function T(e){var I;let c=e();if(c===_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.FocusResult.Success&&l.activation.value==="auto"){let m=(I=(0,_utils_owner_js__WEBPACK_IMPORTED_MODULE_7__.getOwnerDocument)(r))==null?void 0:I.activeElement,h=l.tabs.value.findIndex(O=>(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom)(O)===m);h!==-1&&l.setSelectedIndex(h)}return c}function x(e){let c=l.tabs.value.map(m=>(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom)(m)).filter(Boolean);if(e.key===_keyboard_js__WEBPACK_IMPORTED_MODULE_8__.Keys.Space||e.key===_keyboard_js__WEBPACK_IMPORTED_MODULE_8__.Keys.Enter){e.preventDefault(),e.stopPropagation(),l.setSelectedIndex(y.value);return}switch(e.key){case _keyboard_js__WEBPACK_IMPORTED_MODULE_8__.Keys.Home:case _keyboard_js__WEBPACK_IMPORTED_MODULE_8__.Keys.PageUp:return e.preventDefault(),e.stopPropagation(),T(()=>(0,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.focusIn)(c,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.Focus.First));case _keyboard_js__WEBPACK_IMPORTED_MODULE_8__.Keys.End:case _keyboard_js__WEBPACK_IMPORTED_MODULE_8__.Keys.PageDown:return e.preventDefault(),e.stopPropagation(),T(()=>(0,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.focusIn)(c,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.Focus.Last))}if(T(()=>(0,_utils_match_js__WEBPACK_IMPORTED_MODULE_3__.match)(l.orientation.value,{vertical(){return e.key===_keyboard_js__WEBPACK_IMPORTED_MODULE_8__.Keys.ArrowUp?(0,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.focusIn)(c,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.Focus.Previous|_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.Focus.WrapAround):e.key===_keyboard_js__WEBPACK_IMPORTED_MODULE_8__.Keys.ArrowDown?(0,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.focusIn)(c,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.Focus.Next|_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.Focus.WrapAround):_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.FocusResult.Error},horizontal(){return e.key===_keyboard_js__WEBPACK_IMPORTED_MODULE_8__.Keys.ArrowLeft?(0,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.focusIn)(c,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.Focus.Previous|_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.Focus.WrapAround):e.key===_keyboard_js__WEBPACK_IMPORTED_MODULE_8__.Keys.ArrowRight?(0,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.focusIn)(c,_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.Focus.Next|_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.Focus.WrapAround):_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.FocusResult.Error}}))===_utils_focus_management_js__WEBPACK_IMPORTED_MODULE_1__.FocusResult.Success)return e.preventDefault()}let w=(0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(!1);function E(){var e;w.value||(w.value=!0,!a.disabled&&((e=(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom)(r))==null||e.focus({preventScroll:!0}),l.setSelectedIndex(y.value),(0,_utils_micro_task_js__WEBPACK_IMPORTED_MODULE_9__.microTask)(()=>{w.value=!1})))}function t(e){e.preventDefault()}let n=(0,_hooks_use_resolve_button_type_js__WEBPACK_IMPORTED_MODULE_10__.useResolveButtonType)((0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>({as:a.as,type:b.type})),r);return()=>{var m,h;let e={selected:u.value,disabled:(m=a.disabled)!=null?m:!1},{...c}=a,I={ref:r,onKeydown:x,onMousedown:t,onClick:E,id:i,role:"tab",type:n.value,"aria-controls":(h=(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom)(l.panels.value[y.value]))==null?void 0:h.id,"aria-selected":u.value,tabIndex:u.value?0:-1,disabled:a.disabled?!0:void 0};return (0,_utils_render_js__WEBPACK_IMPORTED_MODULE_5__.render)({ourProps:I,theirProps:c,slot:e,attrs:b,slots:s,name:"Tab"})}}}),Ie=(0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({name:"TabPanels",props:{as:{type:[Object,String],default:"div"}},setup(a,{slots:b,attrs:s}){let d=C("TabPanels");return()=>{let i={selectedIndex:d.selectedIndex.value};return (0,_utils_render_js__WEBPACK_IMPORTED_MODULE_5__.render)({theirProps:a,ourProps:{},slot:i,attrs:s,slots:b,name:"TabPanels"})}}}),ye=(0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({name:"TabPanel",props:{as:{type:[Object,String],default:"div"},static:{type:Boolean,default:!1},unmount:{type:Boolean,default:!0},id:{type:String,default:null},tabIndex:{type:Number,default:0}},setup(a,{attrs:b,slots:s,expose:d}){var T;let i=(T=a.id)!=null?T:`headlessui-tabs-panel-${(0,_hooks_use_id_js__WEBPACK_IMPORTED_MODULE_6__.useId)()}`,l=C("TabPanel"),r=(0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);d({el:r,$el:r}),(0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(()=>l.registerPanel(r)),(0,vue__WEBPACK_IMPORTED_MODULE_0__.onUnmounted)(()=>l.unregisterPanel(r));let p=(0,vue__WEBPACK_IMPORTED_MODULE_0__.inject)(G),R=(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>{if(p.value){let x=p.value.panels.indexOf(i);return x===-1?p.value.panels.push(i)-1:x}return-1}),y=(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>{let x=l.panels.value.indexOf(r);return x===-1?R.value:x}),u=(0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(()=>y.value===l.selectedIndex.value);return()=>{var n;let x={selected:u.value},{tabIndex:w,...E}=a,t={ref:r,id:i,role:"tabpanel","aria-labelledby":(n=(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_2__.dom)(l.tabs.value[y.value]))==null?void 0:n.id,tabIndex:u.value?w:-1};return!u.value&&a.unmount&&!a.static?(0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(_internal_hidden_js__WEBPACK_IMPORTED_MODULE_11__.Hidden,{as:"span","aria-hidden":!0,...t}):(0,_utils_render_js__WEBPACK_IMPORTED_MODULE_5__.render)({ourProps:t,theirProps:E,slot:x,attrs:b,slots:s,features:_utils_render_js__WEBPACK_IMPORTED_MODULE_5__.Features.Static|_utils_render_js__WEBPACK_IMPORTED_MODULE_5__.Features.RenderStrategy,visible:u.value,name:"TabPanel"})}}});


/***/ }),

/***/ "./node_modules/@headlessui/vue/dist/hooks/use-id.js":
/*!***********************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/hooks/use-id.js ***!
  \***********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   provideUseId: () => (/* binding */ s),
/* harmony export */   useId: () => (/* binding */ i)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
var r;let n=Symbol("headlessui.useid"),o=0;const i=(r=vue__WEBPACK_IMPORTED_MODULE_0__.useId)!=null?r:function(){return vue__WEBPACK_IMPORTED_MODULE_0__.inject(n,()=>`${++o}`)()};function s(t){vue__WEBPACK_IMPORTED_MODULE_0__.provide(n,t)}


/***/ }),

/***/ "./node_modules/@headlessui/vue/dist/hooks/use-resolve-button-type.js":
/*!****************************************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/hooks/use-resolve-button-type.js ***!
  \****************************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   useResolveButtonType: () => (/* binding */ s)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var _utils_dom_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/dom.js */ "./node_modules/@headlessui/vue/dist/utils/dom.js");
function r(t,e){if(t)return t;let n=e!=null?e:"button";if(typeof n=="string"&&n.toLowerCase()==="button")return"button"}function s(t,e){let n=(0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(r(t.value.type,t.value.as));return (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(()=>{n.value=r(t.value.type,t.value.as)}),(0,vue__WEBPACK_IMPORTED_MODULE_0__.watchEffect)(()=>{var u;n.value||(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_1__.dom)(e)&&(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_1__.dom)(e)instanceof HTMLButtonElement&&!((u=(0,_utils_dom_js__WEBPACK_IMPORTED_MODULE_1__.dom)(e))!=null&&u.hasAttribute("type"))&&(n.value="button")}),n}


/***/ }),

/***/ "./node_modules/@headlessui/vue/dist/internal/focus-sentinel.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/internal/focus-sentinel.js ***!
  \**********************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   FocusSentinel: () => (/* binding */ d)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var _hidden_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./hidden.js */ "./node_modules/@headlessui/vue/dist/internal/hidden.js");
let d=(0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({props:{onFocus:{type:Function,required:!0}},setup(t){let n=(0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(!0);return()=>n.value?(0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(_hidden_js__WEBPACK_IMPORTED_MODULE_1__.Hidden,{as:"button",type:"button",features:_hidden_js__WEBPACK_IMPORTED_MODULE_1__.Features.Focusable,onFocus(o){o.preventDefault();let e,a=50;function r(){var u;if(a--<=0){e&&cancelAnimationFrame(e);return}if((u=t.onFocus)!=null&&u.call(t)){n.value=!1,cancelAnimationFrame(e);return}e=requestAnimationFrame(r)}e=requestAnimationFrame(r)}}):null}});


/***/ }),

/***/ "./node_modules/@headlessui/vue/dist/internal/hidden.js":
/*!**************************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/internal/hidden.js ***!
  \**************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Features: () => (/* binding */ u),
/* harmony export */   Hidden: () => (/* binding */ f)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var _utils_render_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/render.js */ "./node_modules/@headlessui/vue/dist/utils/render.js");
var u=(e=>(e[e.None=1]="None",e[e.Focusable=2]="Focusable",e[e.Hidden=4]="Hidden",e))(u||{});let f=(0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({name:"Hidden",props:{as:{type:[Object,String],default:"div"},features:{type:Number,default:1}},setup(t,{slots:n,attrs:i}){return()=>{var r;let{features:e,...d}=t,o={"aria-hidden":(e&2)===2?!0:(r=d["aria-hidden"])!=null?r:void 0,hidden:(e&4)===4?!0:void 0,style:{position:"fixed",top:1,left:1,width:1,height:0,padding:0,margin:-1,overflow:"hidden",clip:"rect(0, 0, 0, 0)",whiteSpace:"nowrap",borderWidth:"0",...(e&4)===4&&(e&2)!==2&&{display:"none"}}};return (0,_utils_render_js__WEBPACK_IMPORTED_MODULE_1__.render)({ourProps:o,theirProps:d,slot:{},attrs:i,slots:n,name:"Hidden"})}}});


/***/ }),

/***/ "./node_modules/@headlessui/vue/dist/keyboard.js":
/*!*******************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/keyboard.js ***!
  \*******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Keys: () => (/* binding */ o)
/* harmony export */ });
var o=(r=>(r.Space=" ",r.Enter="Enter",r.Escape="Escape",r.Backspace="Backspace",r.Delete="Delete",r.ArrowLeft="ArrowLeft",r.ArrowUp="ArrowUp",r.ArrowRight="ArrowRight",r.ArrowDown="ArrowDown",r.Home="Home",r.End="End",r.PageUp="PageUp",r.PageDown="PageDown",r.Tab="Tab",r))(o||{});


/***/ }),

/***/ "./node_modules/@headlessui/vue/dist/utils/dom.js":
/*!********************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/utils/dom.js ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   dom: () => (/* binding */ o)
/* harmony export */ });
function o(e){var l;if(e==null||e.value==null)return null;let n=(l=e.value.$el)!=null?l:e.value;return n instanceof Node?n:null}


/***/ }),

/***/ "./node_modules/@headlessui/vue/dist/utils/env.js":
/*!********************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/utils/env.js ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   env: () => (/* binding */ c)
/* harmony export */ });
var i=Object.defineProperty;var d=(t,e,r)=>e in t?i(t,e,{enumerable:!0,configurable:!0,writable:!0,value:r}):t[e]=r;var n=(t,e,r)=>(d(t,typeof e!="symbol"?e+"":e,r),r);class s{constructor(){n(this,"current",this.detect());n(this,"currentId",0)}set(e){this.current!==e&&(this.currentId=0,this.current=e)}reset(){this.set(this.detect())}nextId(){return++this.currentId}get isServer(){return this.current==="server"}get isClient(){return this.current==="client"}detect(){return typeof window=="undefined"||typeof document=="undefined"?"server":"client"}}let c=new s;


/***/ }),

/***/ "./node_modules/@headlessui/vue/dist/utils/focus-management.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/utils/focus-management.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Focus: () => (/* binding */ N),
/* harmony export */   FocusResult: () => (/* binding */ T),
/* harmony export */   FocusableMode: () => (/* binding */ h),
/* harmony export */   focusElement: () => (/* binding */ S),
/* harmony export */   focusFrom: () => (/* binding */ v),
/* harmony export */   focusIn: () => (/* binding */ P),
/* harmony export */   getFocusableElements: () => (/* binding */ E),
/* harmony export */   isFocusableElement: () => (/* binding */ w),
/* harmony export */   restoreFocusIfNecessary: () => (/* binding */ _),
/* harmony export */   sortByDomNode: () => (/* binding */ O)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var _match_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./match.js */ "./node_modules/@headlessui/vue/dist/utils/match.js");
/* harmony import */ var _owner_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./owner.js */ "./node_modules/@headlessui/vue/dist/utils/owner.js");
let c=["[contentEditable=true]","[tabindex]","a[href]","area[href]","button:not([disabled])","iframe","input:not([disabled])","select:not([disabled])","textarea:not([disabled])"].map(e=>`${e}:not([tabindex='-1'])`).join(",");var N=(n=>(n[n.First=1]="First",n[n.Previous=2]="Previous",n[n.Next=4]="Next",n[n.Last=8]="Last",n[n.WrapAround=16]="WrapAround",n[n.NoScroll=32]="NoScroll",n))(N||{}),T=(o=>(o[o.Error=0]="Error",o[o.Overflow=1]="Overflow",o[o.Success=2]="Success",o[o.Underflow=3]="Underflow",o))(T||{}),F=(t=>(t[t.Previous=-1]="Previous",t[t.Next=1]="Next",t))(F||{});function E(e=document.body){return e==null?[]:Array.from(e.querySelectorAll(c)).sort((r,t)=>Math.sign((r.tabIndex||Number.MAX_SAFE_INTEGER)-(t.tabIndex||Number.MAX_SAFE_INTEGER)))}var h=(t=>(t[t.Strict=0]="Strict",t[t.Loose=1]="Loose",t))(h||{});function w(e,r=0){var t;return e===((t=(0,_owner_js__WEBPACK_IMPORTED_MODULE_1__.getOwnerDocument)(e))==null?void 0:t.body)?!1:(0,_match_js__WEBPACK_IMPORTED_MODULE_2__.match)(r,{[0](){return e.matches(c)},[1](){let l=e;for(;l!==null;){if(l.matches(c))return!0;l=l.parentElement}return!1}})}function _(e){let r=(0,_owner_js__WEBPACK_IMPORTED_MODULE_1__.getOwnerDocument)(e);(0,vue__WEBPACK_IMPORTED_MODULE_0__.nextTick)(()=>{r&&!w(r.activeElement,0)&&S(e)})}var y=(t=>(t[t.Keyboard=0]="Keyboard",t[t.Mouse=1]="Mouse",t))(y||{});typeof window!="undefined"&&typeof document!="undefined"&&(document.addEventListener("keydown",e=>{e.metaKey||e.altKey||e.ctrlKey||(document.documentElement.dataset.headlessuiFocusVisible="")},!0),document.addEventListener("click",e=>{e.detail===1?delete document.documentElement.dataset.headlessuiFocusVisible:e.detail===0&&(document.documentElement.dataset.headlessuiFocusVisible="")},!0));function S(e){e==null||e.focus({preventScroll:!0})}let H=["textarea","input"].join(",");function I(e){var r,t;return(t=(r=e==null?void 0:e.matches)==null?void 0:r.call(e,H))!=null?t:!1}function O(e,r=t=>t){return e.slice().sort((t,l)=>{let o=r(t),i=r(l);if(o===null||i===null)return 0;let n=o.compareDocumentPosition(i);return n&Node.DOCUMENT_POSITION_FOLLOWING?-1:n&Node.DOCUMENT_POSITION_PRECEDING?1:0})}function v(e,r){return P(E(),r,{relativeTo:e})}function P(e,r,{sorted:t=!0,relativeTo:l=null,skipElements:o=[]}={}){var m;let i=(m=Array.isArray(e)?e.length>0?e[0].ownerDocument:document:e==null?void 0:e.ownerDocument)!=null?m:document,n=Array.isArray(e)?t?O(e):e:E(e);o.length>0&&n.length>1&&(n=n.filter(s=>!o.includes(s))),l=l!=null?l:i.activeElement;let x=(()=>{if(r&5)return 1;if(r&10)return-1;throw new Error("Missing Focus.First, Focus.Previous, Focus.Next or Focus.Last")})(),p=(()=>{if(r&1)return 0;if(r&2)return Math.max(0,n.indexOf(l))-1;if(r&4)return Math.max(0,n.indexOf(l))+1;if(r&8)return n.length-1;throw new Error("Missing Focus.First, Focus.Previous, Focus.Next or Focus.Last")})(),L=r&32?{preventScroll:!0}:{},a=0,d=n.length,u;do{if(a>=d||a+d<=0)return 0;let s=p+a;if(r&16)s=(s+d)%d;else{if(s<0)return 3;if(s>=d)return 1}u=n[s],u==null||u.focus(L),a+=x}while(u!==i.activeElement);return r&6&&I(u)&&u.select(),2}


/***/ }),

/***/ "./node_modules/@headlessui/vue/dist/utils/match.js":
/*!**********************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/utils/match.js ***!
  \**********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   match: () => (/* binding */ u)
/* harmony export */ });
function u(r,n,...a){if(r in n){let e=n[r];return typeof e=="function"?e(...a):e}let t=new Error(`Tried to handle "${r}" but there is no handler defined. Only defined handlers are: ${Object.keys(n).map(e=>`"${e}"`).join(", ")}.`);throw Error.captureStackTrace&&Error.captureStackTrace(t,u),t}


/***/ }),

/***/ "./node_modules/@headlessui/vue/dist/utils/micro-task.js":
/*!***************************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/utils/micro-task.js ***!
  \***************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   microTask: () => (/* binding */ t)
/* harmony export */ });
function t(e){typeof queueMicrotask=="function"?queueMicrotask(e):Promise.resolve().then(e).catch(o=>setTimeout(()=>{throw o}))}


/***/ }),

/***/ "./node_modules/@headlessui/vue/dist/utils/owner.js":
/*!**********************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/utils/owner.js ***!
  \**********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   getOwnerDocument: () => (/* binding */ i)
/* harmony export */ });
/* harmony import */ var _dom_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./dom.js */ "./node_modules/@headlessui/vue/dist/utils/dom.js");
/* harmony import */ var _env_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./env.js */ "./node_modules/@headlessui/vue/dist/utils/env.js");
function i(r){if(_env_js__WEBPACK_IMPORTED_MODULE_0__.env.isServer)return null;if(r instanceof Node)return r.ownerDocument;if(r!=null&&r.hasOwnProperty("value")){let n=(0,_dom_js__WEBPACK_IMPORTED_MODULE_1__.dom)(r);if(n)return n.ownerDocument}return document}


/***/ }),

/***/ "./node_modules/@headlessui/vue/dist/utils/render.js":
/*!***********************************************************!*\
  !*** ./node_modules/@headlessui/vue/dist/utils/render.js ***!
  \***********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Features: () => (/* binding */ N),
/* harmony export */   RenderStrategy: () => (/* binding */ S),
/* harmony export */   compact: () => (/* binding */ E),
/* harmony export */   omit: () => (/* binding */ T),
/* harmony export */   render: () => (/* binding */ A)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var _match_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./match.js */ "./node_modules/@headlessui/vue/dist/utils/match.js");
var N=(o=>(o[o.None=0]="None",o[o.RenderStrategy=1]="RenderStrategy",o[o.Static=2]="Static",o))(N||{}),S=(e=>(e[e.Unmount=0]="Unmount",e[e.Hidden=1]="Hidden",e))(S||{});function A({visible:r=!0,features:t=0,ourProps:e,theirProps:o,...i}){var a;let n=j(o,e),l=Object.assign(i,{props:n});if(r||t&2&&n.static)return y(l);if(t&1){let d=(a=n.unmount)==null||a?0:1;return (0,_match_js__WEBPACK_IMPORTED_MODULE_1__.match)(d,{[0](){return null},[1](){return y({...i,props:{...n,hidden:!0,style:{display:"none"}}})}})}return y(l)}function y({props:r,attrs:t,slots:e,slot:o,name:i}){var m,h;let{as:n,...l}=T(r,["unmount","static"]),a=(m=e.default)==null?void 0:m.call(e,o),d={};if(o){let u=!1,c=[];for(let[p,f]of Object.entries(o))typeof f=="boolean"&&(u=!0),f===!0&&c.push(p);u&&(d["data-headlessui-state"]=c.join(" "))}if(n==="template"){if(a=b(a!=null?a:[]),Object.keys(l).length>0||Object.keys(t).length>0){let[u,...c]=a!=null?a:[];if(!v(u)||c.length>0)throw new Error(['Passing props on "template"!',"",`The current component <${i} /> is rendering a "template".`,"However we need to passthrough the following props:",Object.keys(l).concat(Object.keys(t)).map(s=>s.trim()).filter((s,g,R)=>R.indexOf(s)===g).sort((s,g)=>s.localeCompare(g)).map(s=>`  - ${s}`).join(`
`),"","You can apply a few solutions:",['Add an `as="..."` prop, to ensure that we render an actual element instead of a "template".',"Render a single element as the child so that we can forward the props onto that element."].map(s=>`  - ${s}`).join(`
`)].join(`
`));let p=j((h=u.props)!=null?h:{},l,d),f=(0,vue__WEBPACK_IMPORTED_MODULE_0__.cloneVNode)(u,p,!0);for(let s in p)s.startsWith("on")&&(f.props||(f.props={}),f.props[s]=p[s]);return f}return Array.isArray(a)&&a.length===1?a[0]:a}return (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(n,Object.assign({},l,d),{default:()=>a})}function b(r){return r.flatMap(t=>t.type===vue__WEBPACK_IMPORTED_MODULE_0__.Fragment?b(t.children):[t])}function j(...r){var o;if(r.length===0)return{};if(r.length===1)return r[0];let t={},e={};for(let i of r)for(let n in i)n.startsWith("on")&&typeof i[n]=="function"?((o=e[n])!=null||(e[n]=[]),e[n].push(i[n])):t[n]=i[n];if(t.disabled||t["aria-disabled"])return Object.assign(t,Object.fromEntries(Object.keys(e).map(i=>[i,void 0])));for(let i in e)Object.assign(t,{[i](n,...l){let a=e[i];for(let d of a){if(n instanceof Event&&n.defaultPrevented)return;d(n,...l)}}});return t}function E(r){let t=Object.assign({},r);for(let e in t)t[e]===void 0&&delete t[e];return t}function T(r,t=[]){let e=Object.assign({},r);for(let o of t)o in e&&delete e[o];return e}function v(r){return r==null?!1:typeof r.type=="string"||typeof r.type=="object"||typeof r.type=="function"}


/***/ }),

/***/ "./node_modules/@vue-flow/core/dist/style.css":
/*!****************************************************!*\
  !*** ./node_modules/@vue-flow/core/dist/style.css ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _css_loader_dist_cjs_js_clonedRuleSet_9_use_1_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_style_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./style.css */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/@vue-flow/core/dist/style.css");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_style_css__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_style_css__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/@vue-flow/core/dist/vue-flow-core.mjs":
/*!************************************************************!*\
  !*** ./node_modules/@vue-flow/core/dist/vue-flow-core.mjs ***!
  \************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   BaseEdge: () => (/* binding */ _sfc_main$d),
/* harmony export */   BezierEdge: () => (/* binding */ BezierEdge$1),
/* harmony export */   ConnectionLineType: () => (/* binding */ ConnectionLineType),
/* harmony export */   ConnectionMode: () => (/* binding */ ConnectionMode),
/* harmony export */   EdgeLabelRenderer: () => (/* binding */ _sfc_main$3),
/* harmony export */   EdgeText: () => (/* binding */ _sfc_main$e),
/* harmony export */   ErrorCode: () => (/* binding */ ErrorCode),
/* harmony export */   Handle: () => (/* binding */ _sfc_main$f),
/* harmony export */   MarkerType: () => (/* binding */ MarkerType),
/* harmony export */   NodeIdInjection: () => (/* binding */ NodeId),
/* harmony export */   PanOnScrollMode: () => (/* binding */ PanOnScrollMode),
/* harmony export */   Panel: () => (/* binding */ _sfc_main),
/* harmony export */   PanelPosition: () => (/* binding */ PanelPosition),
/* harmony export */   Position: () => (/* binding */ Position),
/* harmony export */   SelectionMode: () => (/* binding */ SelectionMode),
/* harmony export */   SimpleBezierEdge: () => (/* binding */ SimpleBezierEdge$1),
/* harmony export */   SmoothStepEdge: () => (/* binding */ SmoothStepEdge$1),
/* harmony export */   StepEdge: () => (/* binding */ StepEdge$1),
/* harmony export */   StraightEdge: () => (/* binding */ StraightEdge$1),
/* harmony export */   VueFlow: () => (/* binding */ _sfc_main$1),
/* harmony export */   VueFlowError: () => (/* binding */ VueFlowError),
/* harmony export */   VueFlowInjection: () => (/* binding */ VueFlow),
/* harmony export */   addEdge: () => (/* binding */ addEdge),
/* harmony export */   applyChanges: () => (/* binding */ applyChanges),
/* harmony export */   applyEdgeChanges: () => (/* binding */ applyEdgeChanges),
/* harmony export */   applyNodeChanges: () => (/* binding */ applyNodeChanges),
/* harmony export */   clamp: () => (/* binding */ clamp),
/* harmony export */   connectionExists: () => (/* binding */ connectionExists),
/* harmony export */   defaultEdgeTypes: () => (/* binding */ defaultEdgeTypes),
/* harmony export */   defaultNodeTypes: () => (/* binding */ defaultNodeTypes),
/* harmony export */   getBezierEdgeCenter: () => (/* binding */ getBezierEdgeCenter),
/* harmony export */   getBezierPath: () => (/* binding */ getBezierPath),
/* harmony export */   getBoundsofRects: () => (/* binding */ getBoundsofRects),
/* harmony export */   getConnectedEdges: () => (/* binding */ getConnectedEdges),
/* harmony export */   getIncomers: () => (/* binding */ getIncomers),
/* harmony export */   getMarkerId: () => (/* binding */ getMarkerId),
/* harmony export */   getNodesInside: () => (/* binding */ getNodesInside),
/* harmony export */   getOutgoers: () => (/* binding */ getOutgoers),
/* harmony export */   getRectOfNodes: () => (/* binding */ getRectOfNodes),
/* harmony export */   getSimpleBezierPath: () => (/* binding */ getSimpleBezierPath),
/* harmony export */   getSimpleEdgeCenter: () => (/* binding */ getSimpleEdgeCenter),
/* harmony export */   getSmoothStepPath: () => (/* binding */ getSmoothStepPath),
/* harmony export */   getStraightPath: () => (/* binding */ getStraightPath),
/* harmony export */   getTransformForBounds: () => (/* binding */ getTransformForBounds),
/* harmony export */   graphPosToZoomedPos: () => (/* binding */ rendererPointToPoint),
/* harmony export */   isEdge: () => (/* binding */ isEdge),
/* harmony export */   isErrorOfType: () => (/* binding */ isErrorOfType),
/* harmony export */   isGraphEdge: () => (/* binding */ isGraphEdge),
/* harmony export */   isGraphNode: () => (/* binding */ isGraphNode),
/* harmony export */   isNode: () => (/* binding */ isNode),
/* harmony export */   pointToRendererPoint: () => (/* binding */ pointToRendererPoint),
/* harmony export */   rendererPointToPoint: () => (/* binding */ rendererPointToPoint),
/* harmony export */   updateEdge: () => (/* binding */ updateEdge),
/* harmony export */   useConnection: () => (/* binding */ useConnection),
/* harmony export */   useEdge: () => (/* binding */ useEdge),
/* harmony export */   useEdgesData: () => (/* binding */ useEdgesData),
/* harmony export */   useGetPointerPosition: () => (/* binding */ useGetPointerPosition),
/* harmony export */   useHandle: () => (/* binding */ useHandle),
/* harmony export */   useHandleConnections: () => (/* binding */ useHandleConnections),
/* harmony export */   useKeyPress: () => (/* binding */ useKeyPress),
/* harmony export */   useNode: () => (/* binding */ useNode),
/* harmony export */   useNodeConnections: () => (/* binding */ useNodeConnections),
/* harmony export */   useNodeId: () => (/* binding */ useNodeId),
/* harmony export */   useNodesData: () => (/* binding */ useNodesData),
/* harmony export */   useNodesInitialized: () => (/* binding */ useNodesInitialized),
/* harmony export */   useVueFlow: () => (/* binding */ useVueFlow),
/* harmony export */   useZoomPanHelper: () => (/* binding */ useZoomPanHelper)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");

function tryOnScopeDispose(fn) {
  if ((0,vue__WEBPACK_IMPORTED_MODULE_0__.getCurrentScope)()) {
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.onScopeDispose)(fn);
    return true;
  }
  return false;
}
function toValue(r) {
  return typeof r === "function" ? r() : (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(r);
}
function toReactive(objectRef) {
  if (!(0,vue__WEBPACK_IMPORTED_MODULE_0__.isRef)(objectRef))
    return (0,vue__WEBPACK_IMPORTED_MODULE_0__.reactive)(objectRef);
  const proxy = new Proxy({}, {
    get(_, p, receiver) {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(Reflect.get(objectRef.value, p, receiver));
    },
    set(_, p, value) {
      if ((0,vue__WEBPACK_IMPORTED_MODULE_0__.isRef)(objectRef.value[p]) && !(0,vue__WEBPACK_IMPORTED_MODULE_0__.isRef)(value))
        objectRef.value[p].value = value;
      else
        objectRef.value[p] = value;
      return true;
    },
    deleteProperty(_, p) {
      return Reflect.deleteProperty(objectRef.value, p);
    },
    has(_, p) {
      return Reflect.has(objectRef.value, p);
    },
    ownKeys() {
      return Object.keys(objectRef.value);
    },
    getOwnPropertyDescriptor() {
      return {
        enumerable: true,
        configurable: true
      };
    }
  });
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.reactive)(proxy);
}
const isClient = typeof window !== "undefined" && typeof document !== "undefined";
const isDef$1 = (val) => typeof val !== "undefined";
const toString = Object.prototype.toString;
const isObject = (val) => toString.call(val) === "[object Object]";
const noop$2 = () => {
};
function createFilterWrapper(filter2, fn) {
  function wrapper(...args) {
    return new Promise((resolve, reject) => {
      Promise.resolve(filter2(() => fn.apply(this, args), { fn, thisArg: this, args })).then(resolve).catch(reject);
    });
  }
  return wrapper;
}
const bypassFilter = (invoke) => {
  return invoke();
};
function pausableFilter(extendFilter = bypassFilter) {
  const isActive = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(true);
  function pause() {
    isActive.value = false;
  }
  function resume() {
    isActive.value = true;
  }
  const eventFilter = (...args) => {
    if (isActive.value)
      extendFilter(...args);
  };
  return { isActive: (0,vue__WEBPACK_IMPORTED_MODULE_0__.readonly)(isActive), pause, resume, eventFilter };
}
function promiseTimeout(ms, throwOnTimeout = false, reason = "Timeout") {
  return new Promise((resolve, reject) => {
    if (throwOnTimeout)
      setTimeout(() => reject(reason), ms);
    else
      setTimeout(resolve, ms);
  });
}
function watchWithFilter(source, cb, options = {}) {
  const {
    eventFilter = bypassFilter,
    ...watchOptions
  } = options;
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
    source,
    createFilterWrapper(
      eventFilter,
      cb
    ),
    watchOptions
  );
}
function watchPausable(source, cb, options = {}) {
  const {
    eventFilter: filter2,
    ...watchOptions
  } = options;
  const { eventFilter, pause, resume, isActive } = pausableFilter(filter2);
  const stop = watchWithFilter(
    source,
    cb,
    {
      ...watchOptions,
      eventFilter
    }
  );
  return { stop, pause, resume, isActive };
}
function toRefs(objectRef, options = {}) {
  if (!(0,vue__WEBPACK_IMPORTED_MODULE_0__.isRef)(objectRef))
    return (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRefs)(objectRef);
  const result = Array.isArray(objectRef.value) ? Array.from({ length: objectRef.value.length }) : {};
  for (const key in objectRef.value) {
    result[key] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.customRef)(() => ({
      get() {
        return objectRef.value[key];
      },
      set(v) {
        var _a;
        const replaceRef = (_a = toValue(options.replaceRef)) != null ? _a : true;
        if (replaceRef) {
          if (Array.isArray(objectRef.value)) {
            const copy = [...objectRef.value];
            copy[key] = v;
            objectRef.value = copy;
          } else {
            const newObject = { ...objectRef.value, [key]: v };
            Object.setPrototypeOf(newObject, Object.getPrototypeOf(objectRef.value));
            objectRef.value = newObject;
          }
        } else {
          objectRef.value[key] = v;
        }
      }
    }));
  }
  return result;
}
function createUntil(r, isNot = false) {
  function toMatch(condition, { flush = "sync", deep = false, timeout: timeout2, throwOnTimeout } = {}) {
    let stop = null;
    const watcher = new Promise((resolve) => {
      stop = (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
        r,
        (v) => {
          if (condition(v) !== isNot) {
            stop == null ? void 0 : stop();
            resolve(v);
          }
        },
        {
          flush,
          deep,
          immediate: true
        }
      );
    });
    const promises = [watcher];
    if (timeout2 != null) {
      promises.push(
        promiseTimeout(timeout2, throwOnTimeout).then(() => toValue(r)).finally(() => stop == null ? void 0 : stop())
      );
    }
    return Promise.race(promises);
  }
  function toBe(value, options) {
    if (!(0,vue__WEBPACK_IMPORTED_MODULE_0__.isRef)(value))
      return toMatch((v) => v === value, options);
    const { flush = "sync", deep = false, timeout: timeout2, throwOnTimeout } = options != null ? options : {};
    let stop = null;
    const watcher = new Promise((resolve) => {
      stop = (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
        [r, value],
        ([v1, v2]) => {
          if (isNot !== (v1 === v2)) {
            stop == null ? void 0 : stop();
            resolve(v1);
          }
        },
        {
          flush,
          deep,
          immediate: true
        }
      );
    });
    const promises = [watcher];
    if (timeout2 != null) {
      promises.push(
        promiseTimeout(timeout2, throwOnTimeout).then(() => toValue(r)).finally(() => {
          stop == null ? void 0 : stop();
          return toValue(r);
        })
      );
    }
    return Promise.race(promises);
  }
  function toBeTruthy(options) {
    return toMatch((v) => Boolean(v), options);
  }
  function toBeNull(options) {
    return toBe(null, options);
  }
  function toBeUndefined(options) {
    return toBe(void 0, options);
  }
  function toBeNaN(options) {
    return toMatch(Number.isNaN, options);
  }
  function toContains(value, options) {
    return toMatch((v) => {
      const array2 = Array.from(v);
      return array2.includes(value) || array2.includes(toValue(value));
    }, options);
  }
  function changed(options) {
    return changedTimes(1, options);
  }
  function changedTimes(n = 1, options) {
    let count = -1;
    return toMatch(() => {
      count += 1;
      return count >= n;
    }, options);
  }
  if (Array.isArray(toValue(r))) {
    const instance = {
      toMatch,
      toContains,
      changed,
      changedTimes,
      get not() {
        return createUntil(r, !isNot);
      }
    };
    return instance;
  } else {
    const instance = {
      toMatch,
      toBe,
      toBeTruthy,
      toBeNull,
      toBeNaN,
      toBeUndefined,
      changed,
      changedTimes,
      get not() {
        return createUntil(r, !isNot);
      }
    };
    return instance;
  }
}
function until(r) {
  return createUntil(r);
}
function unrefElement(elRef) {
  var _a;
  const plain = toValue(elRef);
  return (_a = plain == null ? void 0 : plain.$el) != null ? _a : plain;
}
const defaultWindow = isClient ? window : void 0;
function useEventListener(...args) {
  let target;
  let events;
  let listeners;
  let options;
  if (typeof args[0] === "string" || Array.isArray(args[0])) {
    [events, listeners, options] = args;
    target = defaultWindow;
  } else {
    [target, events, listeners, options] = args;
  }
  if (!target)
    return noop$2;
  if (!Array.isArray(events))
    events = [events];
  if (!Array.isArray(listeners))
    listeners = [listeners];
  const cleanups = [];
  const cleanup = () => {
    cleanups.forEach((fn) => fn());
    cleanups.length = 0;
  };
  const register = (el, event, listener, options2) => {
    el.addEventListener(event, listener, options2);
    return () => el.removeEventListener(event, listener, options2);
  };
  const stopWatch = (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
    () => [unrefElement(target), toValue(options)],
    ([el, options2]) => {
      cleanup();
      if (!el)
        return;
      const optionsClone = isObject(options2) ? { ...options2 } : options2;
      cleanups.push(
        ...events.flatMap((event) => {
          return listeners.map((listener) => register(el, event, listener, optionsClone));
        })
      );
    },
    { immediate: true, flush: "post" }
  );
  const stop = () => {
    stopWatch();
    cleanup();
  };
  tryOnScopeDispose(stop);
  return stop;
}
function createKeyPredicate$1(keyFilter) {
  if (typeof keyFilter === "function")
    return keyFilter;
  else if (typeof keyFilter === "string")
    return (event) => event.key === keyFilter;
  else if (Array.isArray(keyFilter))
    return (event) => keyFilter.includes(event.key);
  return () => true;
}
function onKeyStroke(...args) {
  let key;
  let handler;
  let options = {};
  if (args.length === 3) {
    key = args[0];
    handler = args[1];
    options = args[2];
  } else if (args.length === 2) {
    if (typeof args[1] === "object") {
      key = true;
      handler = args[0];
      options = args[1];
    } else {
      key = args[0];
      handler = args[1];
    }
  } else {
    key = true;
    handler = args[0];
  }
  const {
    target = defaultWindow,
    eventName = "keydown",
    passive = false,
    dedupe = false
  } = options;
  const predicate = createKeyPredicate$1(key);
  const listener = (e) => {
    if (e.repeat && toValue(dedupe))
      return;
    if (predicate(e))
      handler(e);
  };
  return useEventListener(target, eventName, listener, passive);
}
function cloneFnJSON(source) {
  return JSON.parse(JSON.stringify(source));
}
function useVModel(props, key, emit, options = {}) {
  var _a, _b, _c;
  const {
    clone = false,
    passive = false,
    eventName,
    deep = false,
    defaultValue,
    shouldEmit
  } = options;
  const vm = (0,vue__WEBPACK_IMPORTED_MODULE_0__.getCurrentInstance)();
  const _emit = emit || (vm == null ? void 0 : vm.emit) || ((_a = vm == null ? void 0 : vm.$emit) == null ? void 0 : _a.bind(vm)) || ((_c = (_b = vm == null ? void 0 : vm.proxy) == null ? void 0 : _b.$emit) == null ? void 0 : _c.bind(vm == null ? void 0 : vm.proxy));
  let event = eventName;
  if (!key) {
    {
      key = "modelValue";
    }
  }
  event = event || `update:${key.toString()}`;
  const cloneFn = (val) => !clone ? val : typeof clone === "function" ? clone(val) : cloneFnJSON(val);
  const getValue = () => isDef$1(props[key]) ? cloneFn(props[key]) : defaultValue;
  const triggerEmit = (value) => {
    if (shouldEmit) {
      if (shouldEmit(value))
        _emit(event, value);
    } else {
      _emit(event, value);
    }
  };
  if (passive) {
    const initialValue = getValue();
    const proxy = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(initialValue);
    let isUpdating = false;
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
      () => props[key],
      (v) => {
        if (!isUpdating) {
          isUpdating = true;
          proxy.value = cloneFn(v);
          (0,vue__WEBPACK_IMPORTED_MODULE_0__.nextTick)(() => isUpdating = false);
        }
      }
    );
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
      proxy,
      (v) => {
        if (!isUpdating && (v !== props[key] || deep))
          triggerEmit(v);
      },
      { deep }
    );
    return proxy;
  } else {
    return (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)({
      get() {
        return getValue();
      },
      set(value) {
        triggerEmit(value);
      }
    });
  }
}
var noop$1 = { value: () => {
} };
function dispatch() {
  for (var i = 0, n = arguments.length, _ = {}, t; i < n; ++i) {
    if (!(t = arguments[i] + "") || t in _ || /[\s.]/.test(t))
      throw new Error("illegal type: " + t);
    _[t] = [];
  }
  return new Dispatch(_);
}
function Dispatch(_) {
  this._ = _;
}
function parseTypenames$1(typenames, types) {
  return typenames.trim().split(/^|\s+/).map(function(t) {
    var name = "", i = t.indexOf(".");
    if (i >= 0)
      name = t.slice(i + 1), t = t.slice(0, i);
    if (t && !types.hasOwnProperty(t))
      throw new Error("unknown type: " + t);
    return { type: t, name };
  });
}
Dispatch.prototype = dispatch.prototype = {
  constructor: Dispatch,
  on: function(typename, callback) {
    var _ = this._, T = parseTypenames$1(typename + "", _), t, i = -1, n = T.length;
    if (arguments.length < 2) {
      while (++i < n)
        if ((t = (typename = T[i]).type) && (t = get$1(_[t], typename.name)))
          return t;
      return;
    }
    if (callback != null && typeof callback !== "function")
      throw new Error("invalid callback: " + callback);
    while (++i < n) {
      if (t = (typename = T[i]).type)
        _[t] = set$1(_[t], typename.name, callback);
      else if (callback == null)
        for (t in _)
          _[t] = set$1(_[t], typename.name, null);
    }
    return this;
  },
  copy: function() {
    var copy = {}, _ = this._;
    for (var t in _)
      copy[t] = _[t].slice();
    return new Dispatch(copy);
  },
  call: function(type, that) {
    if ((n = arguments.length - 2) > 0)
      for (var args = new Array(n), i = 0, n, t; i < n; ++i)
        args[i] = arguments[i + 2];
    if (!this._.hasOwnProperty(type))
      throw new Error("unknown type: " + type);
    for (t = this._[type], i = 0, n = t.length; i < n; ++i)
      t[i].value.apply(that, args);
  },
  apply: function(type, that, args) {
    if (!this._.hasOwnProperty(type))
      throw new Error("unknown type: " + type);
    for (var t = this._[type], i = 0, n = t.length; i < n; ++i)
      t[i].value.apply(that, args);
  }
};
function get$1(type, name) {
  for (var i = 0, n = type.length, c; i < n; ++i) {
    if ((c = type[i]).name === name) {
      return c.value;
    }
  }
}
function set$1(type, name, callback) {
  for (var i = 0, n = type.length; i < n; ++i) {
    if (type[i].name === name) {
      type[i] = noop$1, type = type.slice(0, i).concat(type.slice(i + 1));
      break;
    }
  }
  if (callback != null)
    type.push({ name, value: callback });
  return type;
}
var xhtml = "http://www.w3.org/1999/xhtml";
const namespaces = {
  svg: "http://www.w3.org/2000/svg",
  xhtml,
  xlink: "http://www.w3.org/1999/xlink",
  xml: "http://www.w3.org/XML/1998/namespace",
  xmlns: "http://www.w3.org/2000/xmlns/"
};
function namespace(name) {
  var prefix = name += "", i = prefix.indexOf(":");
  if (i >= 0 && (prefix = name.slice(0, i)) !== "xmlns")
    name = name.slice(i + 1);
  return namespaces.hasOwnProperty(prefix) ? { space: namespaces[prefix], local: name } : name;
}
function creatorInherit(name) {
  return function() {
    var document2 = this.ownerDocument, uri = this.namespaceURI;
    return uri === xhtml && document2.documentElement.namespaceURI === xhtml ? document2.createElement(name) : document2.createElementNS(uri, name);
  };
}
function creatorFixed(fullname) {
  return function() {
    return this.ownerDocument.createElementNS(fullname.space, fullname.local);
  };
}
function creator(name) {
  var fullname = namespace(name);
  return (fullname.local ? creatorFixed : creatorInherit)(fullname);
}
function none() {
}
function selector(selector2) {
  return selector2 == null ? none : function() {
    return this.querySelector(selector2);
  };
}
function selection_select(select2) {
  if (typeof select2 !== "function")
    select2 = selector(select2);
  for (var groups = this._groups, m = groups.length, subgroups = new Array(m), j = 0; j < m; ++j) {
    for (var group = groups[j], n = group.length, subgroup = subgroups[j] = new Array(n), node, subnode, i = 0; i < n; ++i) {
      if ((node = group[i]) && (subnode = select2.call(node, node.__data__, i, group))) {
        if ("__data__" in node)
          subnode.__data__ = node.__data__;
        subgroup[i] = subnode;
      }
    }
  }
  return new Selection$1(subgroups, this._parents);
}
function array(x) {
  return x == null ? [] : Array.isArray(x) ? x : Array.from(x);
}
function empty() {
  return [];
}
function selectorAll(selector2) {
  return selector2 == null ? empty : function() {
    return this.querySelectorAll(selector2);
  };
}
function arrayAll(select2) {
  return function() {
    return array(select2.apply(this, arguments));
  };
}
function selection_selectAll(select2) {
  if (typeof select2 === "function")
    select2 = arrayAll(select2);
  else
    select2 = selectorAll(select2);
  for (var groups = this._groups, m = groups.length, subgroups = [], parents = [], j = 0; j < m; ++j) {
    for (var group = groups[j], n = group.length, node, i = 0; i < n; ++i) {
      if (node = group[i]) {
        subgroups.push(select2.call(node, node.__data__, i, group));
        parents.push(node);
      }
    }
  }
  return new Selection$1(subgroups, parents);
}
function matcher(selector2) {
  return function() {
    return this.matches(selector2);
  };
}
function childMatcher(selector2) {
  return function(node) {
    return node.matches(selector2);
  };
}
var find = Array.prototype.find;
function childFind(match) {
  return function() {
    return find.call(this.children, match);
  };
}
function childFirst() {
  return this.firstElementChild;
}
function selection_selectChild(match) {
  return this.select(match == null ? childFirst : childFind(typeof match === "function" ? match : childMatcher(match)));
}
var filter = Array.prototype.filter;
function children() {
  return Array.from(this.children);
}
function childrenFilter(match) {
  return function() {
    return filter.call(this.children, match);
  };
}
function selection_selectChildren(match) {
  return this.selectAll(match == null ? children : childrenFilter(typeof match === "function" ? match : childMatcher(match)));
}
function selection_filter(match) {
  if (typeof match !== "function")
    match = matcher(match);
  for (var groups = this._groups, m = groups.length, subgroups = new Array(m), j = 0; j < m; ++j) {
    for (var group = groups[j], n = group.length, subgroup = subgroups[j] = [], node, i = 0; i < n; ++i) {
      if ((node = group[i]) && match.call(node, node.__data__, i, group)) {
        subgroup.push(node);
      }
    }
  }
  return new Selection$1(subgroups, this._parents);
}
function sparse(update) {
  return new Array(update.length);
}
function selection_enter() {
  return new Selection$1(this._enter || this._groups.map(sparse), this._parents);
}
function EnterNode(parent, datum2) {
  this.ownerDocument = parent.ownerDocument;
  this.namespaceURI = parent.namespaceURI;
  this._next = null;
  this._parent = parent;
  this.__data__ = datum2;
}
EnterNode.prototype = {
  constructor: EnterNode,
  appendChild: function(child) {
    return this._parent.insertBefore(child, this._next);
  },
  insertBefore: function(child, next) {
    return this._parent.insertBefore(child, next);
  },
  querySelector: function(selector2) {
    return this._parent.querySelector(selector2);
  },
  querySelectorAll: function(selector2) {
    return this._parent.querySelectorAll(selector2);
  }
};
function constant$3(x) {
  return function() {
    return x;
  };
}
function bindIndex(parent, group, enter, update, exit, data) {
  var i = 0, node, groupLength = group.length, dataLength = data.length;
  for (; i < dataLength; ++i) {
    if (node = group[i]) {
      node.__data__ = data[i];
      update[i] = node;
    } else {
      enter[i] = new EnterNode(parent, data[i]);
    }
  }
  for (; i < groupLength; ++i) {
    if (node = group[i]) {
      exit[i] = node;
    }
  }
}
function bindKey(parent, group, enter, update, exit, data, key) {
  var i, node, nodeByKeyValue = /* @__PURE__ */ new Map(), groupLength = group.length, dataLength = data.length, keyValues = new Array(groupLength), keyValue;
  for (i = 0; i < groupLength; ++i) {
    if (node = group[i]) {
      keyValues[i] = keyValue = key.call(node, node.__data__, i, group) + "";
      if (nodeByKeyValue.has(keyValue)) {
        exit[i] = node;
      } else {
        nodeByKeyValue.set(keyValue, node);
      }
    }
  }
  for (i = 0; i < dataLength; ++i) {
    keyValue = key.call(parent, data[i], i, data) + "";
    if (node = nodeByKeyValue.get(keyValue)) {
      update[i] = node;
      node.__data__ = data[i];
      nodeByKeyValue.delete(keyValue);
    } else {
      enter[i] = new EnterNode(parent, data[i]);
    }
  }
  for (i = 0; i < groupLength; ++i) {
    if ((node = group[i]) && nodeByKeyValue.get(keyValues[i]) === node) {
      exit[i] = node;
    }
  }
}
function datum(node) {
  return node.__data__;
}
function selection_data(value, key) {
  if (!arguments.length)
    return Array.from(this, datum);
  var bind = key ? bindKey : bindIndex, parents = this._parents, groups = this._groups;
  if (typeof value !== "function")
    value = constant$3(value);
  for (var m = groups.length, update = new Array(m), enter = new Array(m), exit = new Array(m), j = 0; j < m; ++j) {
    var parent = parents[j], group = groups[j], groupLength = group.length, data = arraylike(value.call(parent, parent && parent.__data__, j, parents)), dataLength = data.length, enterGroup = enter[j] = new Array(dataLength), updateGroup = update[j] = new Array(dataLength), exitGroup = exit[j] = new Array(groupLength);
    bind(parent, group, enterGroup, updateGroup, exitGroup, data, key);
    for (var i0 = 0, i1 = 0, previous, next; i0 < dataLength; ++i0) {
      if (previous = enterGroup[i0]) {
        if (i0 >= i1)
          i1 = i0 + 1;
        while (!(next = updateGroup[i1]) && ++i1 < dataLength)
          ;
        previous._next = next || null;
      }
    }
  }
  update = new Selection$1(update, parents);
  update._enter = enter;
  update._exit = exit;
  return update;
}
function arraylike(data) {
  return typeof data === "object" && "length" in data ? data : Array.from(data);
}
function selection_exit() {
  return new Selection$1(this._exit || this._groups.map(sparse), this._parents);
}
function selection_join(onenter, onupdate, onexit) {
  var enter = this.enter(), update = this, exit = this.exit();
  if (typeof onenter === "function") {
    enter = onenter(enter);
    if (enter)
      enter = enter.selection();
  } else {
    enter = enter.append(onenter + "");
  }
  if (onupdate != null) {
    update = onupdate(update);
    if (update)
      update = update.selection();
  }
  if (onexit == null)
    exit.remove();
  else
    onexit(exit);
  return enter && update ? enter.merge(update).order() : update;
}
function selection_merge(context) {
  var selection2 = context.selection ? context.selection() : context;
  for (var groups0 = this._groups, groups1 = selection2._groups, m0 = groups0.length, m1 = groups1.length, m = Math.min(m0, m1), merges = new Array(m0), j = 0; j < m; ++j) {
    for (var group0 = groups0[j], group1 = groups1[j], n = group0.length, merge = merges[j] = new Array(n), node, i = 0; i < n; ++i) {
      if (node = group0[i] || group1[i]) {
        merge[i] = node;
      }
    }
  }
  for (; j < m0; ++j) {
    merges[j] = groups0[j];
  }
  return new Selection$1(merges, this._parents);
}
function selection_order() {
  for (var groups = this._groups, j = -1, m = groups.length; ++j < m; ) {
    for (var group = groups[j], i = group.length - 1, next = group[i], node; --i >= 0; ) {
      if (node = group[i]) {
        if (next && node.compareDocumentPosition(next) ^ 4)
          next.parentNode.insertBefore(node, next);
        next = node;
      }
    }
  }
  return this;
}
function selection_sort(compare) {
  if (!compare)
    compare = ascending;
  function compareNode(a, b) {
    return a && b ? compare(a.__data__, b.__data__) : !a - !b;
  }
  for (var groups = this._groups, m = groups.length, sortgroups = new Array(m), j = 0; j < m; ++j) {
    for (var group = groups[j], n = group.length, sortgroup = sortgroups[j] = new Array(n), node, i = 0; i < n; ++i) {
      if (node = group[i]) {
        sortgroup[i] = node;
      }
    }
    sortgroup.sort(compareNode);
  }
  return new Selection$1(sortgroups, this._parents).order();
}
function ascending(a, b) {
  return a < b ? -1 : a > b ? 1 : a >= b ? 0 : NaN;
}
function selection_call() {
  var callback = arguments[0];
  arguments[0] = this;
  callback.apply(null, arguments);
  return this;
}
function selection_nodes() {
  return Array.from(this);
}
function selection_node() {
  for (var groups = this._groups, j = 0, m = groups.length; j < m; ++j) {
    for (var group = groups[j], i = 0, n = group.length; i < n; ++i) {
      var node = group[i];
      if (node)
        return node;
    }
  }
  return null;
}
function selection_size() {
  let size = 0;
  for (const node of this)
    ++size;
  return size;
}
function selection_empty() {
  return !this.node();
}
function selection_each(callback) {
  for (var groups = this._groups, j = 0, m = groups.length; j < m; ++j) {
    for (var group = groups[j], i = 0, n = group.length, node; i < n; ++i) {
      if (node = group[i])
        callback.call(node, node.__data__, i, group);
    }
  }
  return this;
}
function attrRemove$1(name) {
  return function() {
    this.removeAttribute(name);
  };
}
function attrRemoveNS$1(fullname) {
  return function() {
    this.removeAttributeNS(fullname.space, fullname.local);
  };
}
function attrConstant$1(name, value) {
  return function() {
    this.setAttribute(name, value);
  };
}
function attrConstantNS$1(fullname, value) {
  return function() {
    this.setAttributeNS(fullname.space, fullname.local, value);
  };
}
function attrFunction$1(name, value) {
  return function() {
    var v = value.apply(this, arguments);
    if (v == null)
      this.removeAttribute(name);
    else
      this.setAttribute(name, v);
  };
}
function attrFunctionNS$1(fullname, value) {
  return function() {
    var v = value.apply(this, arguments);
    if (v == null)
      this.removeAttributeNS(fullname.space, fullname.local);
    else
      this.setAttributeNS(fullname.space, fullname.local, v);
  };
}
function selection_attr(name, value) {
  var fullname = namespace(name);
  if (arguments.length < 2) {
    var node = this.node();
    return fullname.local ? node.getAttributeNS(fullname.space, fullname.local) : node.getAttribute(fullname);
  }
  return this.each((value == null ? fullname.local ? attrRemoveNS$1 : attrRemove$1 : typeof value === "function" ? fullname.local ? attrFunctionNS$1 : attrFunction$1 : fullname.local ? attrConstantNS$1 : attrConstant$1)(fullname, value));
}
function defaultView(node) {
  return node.ownerDocument && node.ownerDocument.defaultView || node.document && node || node.defaultView;
}
function styleRemove$1(name) {
  return function() {
    this.style.removeProperty(name);
  };
}
function styleConstant$1(name, value, priority) {
  return function() {
    this.style.setProperty(name, value, priority);
  };
}
function styleFunction$1(name, value, priority) {
  return function() {
    var v = value.apply(this, arguments);
    if (v == null)
      this.style.removeProperty(name);
    else
      this.style.setProperty(name, v, priority);
  };
}
function selection_style(name, value, priority) {
  return arguments.length > 1 ? this.each((value == null ? styleRemove$1 : typeof value === "function" ? styleFunction$1 : styleConstant$1)(name, value, priority == null ? "" : priority)) : styleValue(this.node(), name);
}
function styleValue(node, name) {
  return node.style.getPropertyValue(name) || defaultView(node).getComputedStyle(node, null).getPropertyValue(name);
}
function propertyRemove(name) {
  return function() {
    delete this[name];
  };
}
function propertyConstant(name, value) {
  return function() {
    this[name] = value;
  };
}
function propertyFunction(name, value) {
  return function() {
    var v = value.apply(this, arguments);
    if (v == null)
      delete this[name];
    else
      this[name] = v;
  };
}
function selection_property(name, value) {
  return arguments.length > 1 ? this.each((value == null ? propertyRemove : typeof value === "function" ? propertyFunction : propertyConstant)(name, value)) : this.node()[name];
}
function classArray(string) {
  return string.trim().split(/^|\s+/);
}
function classList(node) {
  return node.classList || new ClassList(node);
}
function ClassList(node) {
  this._node = node;
  this._names = classArray(node.getAttribute("class") || "");
}
ClassList.prototype = {
  add: function(name) {
    var i = this._names.indexOf(name);
    if (i < 0) {
      this._names.push(name);
      this._node.setAttribute("class", this._names.join(" "));
    }
  },
  remove: function(name) {
    var i = this._names.indexOf(name);
    if (i >= 0) {
      this._names.splice(i, 1);
      this._node.setAttribute("class", this._names.join(" "));
    }
  },
  contains: function(name) {
    return this._names.indexOf(name) >= 0;
  }
};
function classedAdd(node, names) {
  var list = classList(node), i = -1, n = names.length;
  while (++i < n)
    list.add(names[i]);
}
function classedRemove(node, names) {
  var list = classList(node), i = -1, n = names.length;
  while (++i < n)
    list.remove(names[i]);
}
function classedTrue(names) {
  return function() {
    classedAdd(this, names);
  };
}
function classedFalse(names) {
  return function() {
    classedRemove(this, names);
  };
}
function classedFunction(names, value) {
  return function() {
    (value.apply(this, arguments) ? classedAdd : classedRemove)(this, names);
  };
}
function selection_classed(name, value) {
  var names = classArray(name + "");
  if (arguments.length < 2) {
    var list = classList(this.node()), i = -1, n = names.length;
    while (++i < n)
      if (!list.contains(names[i]))
        return false;
    return true;
  }
  return this.each((typeof value === "function" ? classedFunction : value ? classedTrue : classedFalse)(names, value));
}
function textRemove() {
  this.textContent = "";
}
function textConstant$1(value) {
  return function() {
    this.textContent = value;
  };
}
function textFunction$1(value) {
  return function() {
    var v = value.apply(this, arguments);
    this.textContent = v == null ? "" : v;
  };
}
function selection_text(value) {
  return arguments.length ? this.each(value == null ? textRemove : (typeof value === "function" ? textFunction$1 : textConstant$1)(value)) : this.node().textContent;
}
function htmlRemove() {
  this.innerHTML = "";
}
function htmlConstant(value) {
  return function() {
    this.innerHTML = value;
  };
}
function htmlFunction(value) {
  return function() {
    var v = value.apply(this, arguments);
    this.innerHTML = v == null ? "" : v;
  };
}
function selection_html(value) {
  return arguments.length ? this.each(value == null ? htmlRemove : (typeof value === "function" ? htmlFunction : htmlConstant)(value)) : this.node().innerHTML;
}
function raise() {
  if (this.nextSibling)
    this.parentNode.appendChild(this);
}
function selection_raise() {
  return this.each(raise);
}
function lower() {
  if (this.previousSibling)
    this.parentNode.insertBefore(this, this.parentNode.firstChild);
}
function selection_lower() {
  return this.each(lower);
}
function selection_append(name) {
  var create2 = typeof name === "function" ? name : creator(name);
  return this.select(function() {
    return this.appendChild(create2.apply(this, arguments));
  });
}
function constantNull() {
  return null;
}
function selection_insert(name, before) {
  var create2 = typeof name === "function" ? name : creator(name), select2 = before == null ? constantNull : typeof before === "function" ? before : selector(before);
  return this.select(function() {
    return this.insertBefore(create2.apply(this, arguments), select2.apply(this, arguments) || null);
  });
}
function remove() {
  var parent = this.parentNode;
  if (parent)
    parent.removeChild(this);
}
function selection_remove() {
  return this.each(remove);
}
function selection_cloneShallow() {
  var clone = this.cloneNode(false), parent = this.parentNode;
  return parent ? parent.insertBefore(clone, this.nextSibling) : clone;
}
function selection_cloneDeep() {
  var clone = this.cloneNode(true), parent = this.parentNode;
  return parent ? parent.insertBefore(clone, this.nextSibling) : clone;
}
function selection_clone(deep) {
  return this.select(deep ? selection_cloneDeep : selection_cloneShallow);
}
function selection_datum(value) {
  return arguments.length ? this.property("__data__", value) : this.node().__data__;
}
function contextListener(listener) {
  return function(event) {
    listener.call(this, event, this.__data__);
  };
}
function parseTypenames(typenames) {
  return typenames.trim().split(/^|\s+/).map(function(t) {
    var name = "", i = t.indexOf(".");
    if (i >= 0)
      name = t.slice(i + 1), t = t.slice(0, i);
    return { type: t, name };
  });
}
function onRemove(typename) {
  return function() {
    var on = this.__on;
    if (!on)
      return;
    for (var j = 0, i = -1, m = on.length, o; j < m; ++j) {
      if (o = on[j], (!typename.type || o.type === typename.type) && o.name === typename.name) {
        this.removeEventListener(o.type, o.listener, o.options);
      } else {
        on[++i] = o;
      }
    }
    if (++i)
      on.length = i;
    else
      delete this.__on;
  };
}
function onAdd(typename, value, options) {
  return function() {
    var on = this.__on, o, listener = contextListener(value);
    if (on)
      for (var j = 0, m = on.length; j < m; ++j) {
        if ((o = on[j]).type === typename.type && o.name === typename.name) {
          this.removeEventListener(o.type, o.listener, o.options);
          this.addEventListener(o.type, o.listener = listener, o.options = options);
          o.value = value;
          return;
        }
      }
    this.addEventListener(typename.type, listener, options);
    o = { type: typename.type, name: typename.name, value, listener, options };
    if (!on)
      this.__on = [o];
    else
      on.push(o);
  };
}
function selection_on(typename, value, options) {
  var typenames = parseTypenames(typename + ""), i, n = typenames.length, t;
  if (arguments.length < 2) {
    var on = this.node().__on;
    if (on)
      for (var j = 0, m = on.length, o; j < m; ++j) {
        for (i = 0, o = on[j]; i < n; ++i) {
          if ((t = typenames[i]).type === o.type && t.name === o.name) {
            return o.value;
          }
        }
      }
    return;
  }
  on = value ? onAdd : onRemove;
  for (i = 0; i < n; ++i)
    this.each(on(typenames[i], value, options));
  return this;
}
function dispatchEvent(node, type, params) {
  var window2 = defaultView(node), event = window2.CustomEvent;
  if (typeof event === "function") {
    event = new event(type, params);
  } else {
    event = window2.document.createEvent("Event");
    if (params)
      event.initEvent(type, params.bubbles, params.cancelable), event.detail = params.detail;
    else
      event.initEvent(type, false, false);
  }
  node.dispatchEvent(event);
}
function dispatchConstant(type, params) {
  return function() {
    return dispatchEvent(this, type, params);
  };
}
function dispatchFunction(type, params) {
  return function() {
    return dispatchEvent(this, type, params.apply(this, arguments));
  };
}
function selection_dispatch(type, params) {
  return this.each((typeof params === "function" ? dispatchFunction : dispatchConstant)(type, params));
}
function* selection_iterator() {
  for (var groups = this._groups, j = 0, m = groups.length; j < m; ++j) {
    for (var group = groups[j], i = 0, n = group.length, node; i < n; ++i) {
      if (node = group[i])
        yield node;
    }
  }
}
var root = [null];
function Selection$1(groups, parents) {
  this._groups = groups;
  this._parents = parents;
}
function selection() {
  return new Selection$1([[document.documentElement]], root);
}
function selection_selection() {
  return this;
}
Selection$1.prototype = selection.prototype = {
  constructor: Selection$1,
  select: selection_select,
  selectAll: selection_selectAll,
  selectChild: selection_selectChild,
  selectChildren: selection_selectChildren,
  filter: selection_filter,
  data: selection_data,
  enter: selection_enter,
  exit: selection_exit,
  join: selection_join,
  merge: selection_merge,
  selection: selection_selection,
  order: selection_order,
  sort: selection_sort,
  call: selection_call,
  nodes: selection_nodes,
  node: selection_node,
  size: selection_size,
  empty: selection_empty,
  each: selection_each,
  attr: selection_attr,
  style: selection_style,
  property: selection_property,
  classed: selection_classed,
  text: selection_text,
  html: selection_html,
  raise: selection_raise,
  lower: selection_lower,
  append: selection_append,
  insert: selection_insert,
  remove: selection_remove,
  clone: selection_clone,
  datum: selection_datum,
  on: selection_on,
  dispatch: selection_dispatch,
  [Symbol.iterator]: selection_iterator
};
function select(selector2) {
  return typeof selector2 === "string" ? new Selection$1([[document.querySelector(selector2)]], [document.documentElement]) : new Selection$1([[selector2]], root);
}
function sourceEvent(event) {
  let sourceEvent2;
  while (sourceEvent2 = event.sourceEvent)
    event = sourceEvent2;
  return event;
}
function pointer(event, node) {
  event = sourceEvent(event);
  if (node === void 0)
    node = event.currentTarget;
  if (node) {
    var svg = node.ownerSVGElement || node;
    if (svg.createSVGPoint) {
      var point = svg.createSVGPoint();
      point.x = event.clientX, point.y = event.clientY;
      point = point.matrixTransform(node.getScreenCTM().inverse());
      return [point.x, point.y];
    }
    if (node.getBoundingClientRect) {
      var rect = node.getBoundingClientRect();
      return [event.clientX - rect.left - node.clientLeft, event.clientY - rect.top - node.clientTop];
    }
  }
  return [event.pageX, event.pageY];
}
const nonpassive = { passive: false };
const nonpassivecapture = { capture: true, passive: false };
function nopropagation$1(event) {
  event.stopImmediatePropagation();
}
function noevent$1(event) {
  event.preventDefault();
  event.stopImmediatePropagation();
}
function dragDisable(view) {
  var root2 = view.document.documentElement, selection2 = select(view).on("dragstart.drag", noevent$1, nonpassivecapture);
  if ("onselectstart" in root2) {
    selection2.on("selectstart.drag", noevent$1, nonpassivecapture);
  } else {
    root2.__noselect = root2.style.MozUserSelect;
    root2.style.MozUserSelect = "none";
  }
}
function yesdrag(view, noclick) {
  var root2 = view.document.documentElement, selection2 = select(view).on("dragstart.drag", null);
  if (noclick) {
    selection2.on("click.drag", noevent$1, nonpassivecapture);
    setTimeout(function() {
      selection2.on("click.drag", null);
    }, 0);
  }
  if ("onselectstart" in root2) {
    selection2.on("selectstart.drag", null);
  } else {
    root2.style.MozUserSelect = root2.__noselect;
    delete root2.__noselect;
  }
}
const constant$2 = (x) => () => x;
function DragEvent(type, {
  sourceEvent: sourceEvent2,
  subject,
  target,
  identifier,
  active,
  x,
  y,
  dx,
  dy,
  dispatch: dispatch2
}) {
  Object.defineProperties(this, {
    type: { value: type, enumerable: true, configurable: true },
    sourceEvent: { value: sourceEvent2, enumerable: true, configurable: true },
    subject: { value: subject, enumerable: true, configurable: true },
    target: { value: target, enumerable: true, configurable: true },
    identifier: { value: identifier, enumerable: true, configurable: true },
    active: { value: active, enumerable: true, configurable: true },
    x: { value: x, enumerable: true, configurable: true },
    y: { value: y, enumerable: true, configurable: true },
    dx: { value: dx, enumerable: true, configurable: true },
    dy: { value: dy, enumerable: true, configurable: true },
    _: { value: dispatch2 }
  });
}
DragEvent.prototype.on = function() {
  var value = this._.on.apply(this._, arguments);
  return value === this._ ? this : value;
};
function defaultFilter$1(event) {
  return !event.ctrlKey && !event.button;
}
function defaultContainer() {
  return this.parentNode;
}
function defaultSubject(event, d) {
  return d == null ? { x: event.x, y: event.y } : d;
}
function defaultTouchable$1() {
  return navigator.maxTouchPoints || "ontouchstart" in this;
}
function drag() {
  var filter2 = defaultFilter$1, container = defaultContainer, subject = defaultSubject, touchable = defaultTouchable$1, gestures = {}, listeners = dispatch("start", "drag", "end"), active = 0, mousedownx, mousedowny, mousemoving, touchending, clickDistance2 = 0;
  function drag2(selection2) {
    selection2.on("mousedown.drag", mousedowned).filter(touchable).on("touchstart.drag", touchstarted).on("touchmove.drag", touchmoved, nonpassive).on("touchend.drag touchcancel.drag", touchended).style("touch-action", "none").style("-webkit-tap-highlight-color", "rgba(0,0,0,0)");
  }
  function mousedowned(event, d) {
    if (touchending || !filter2.call(this, event, d))
      return;
    var gesture = beforestart(this, container.call(this, event, d), event, d, "mouse");
    if (!gesture)
      return;
    select(event.view).on("mousemove.drag", mousemoved, nonpassivecapture).on("mouseup.drag", mouseupped, nonpassivecapture);
    dragDisable(event.view);
    nopropagation$1(event);
    mousemoving = false;
    mousedownx = event.clientX;
    mousedowny = event.clientY;
    gesture("start", event);
  }
  function mousemoved(event) {
    noevent$1(event);
    if (!mousemoving) {
      var dx = event.clientX - mousedownx, dy = event.clientY - mousedowny;
      mousemoving = dx * dx + dy * dy > clickDistance2;
    }
    gestures.mouse("drag", event);
  }
  function mouseupped(event) {
    select(event.view).on("mousemove.drag mouseup.drag", null);
    yesdrag(event.view, mousemoving);
    noevent$1(event);
    gestures.mouse("end", event);
  }
  function touchstarted(event, d) {
    if (!filter2.call(this, event, d))
      return;
    var touches = event.changedTouches, c = container.call(this, event, d), n = touches.length, i, gesture;
    for (i = 0; i < n; ++i) {
      if (gesture = beforestart(this, c, event, d, touches[i].identifier, touches[i])) {
        nopropagation$1(event);
        gesture("start", event, touches[i]);
      }
    }
  }
  function touchmoved(event) {
    var touches = event.changedTouches, n = touches.length, i, gesture;
    for (i = 0; i < n; ++i) {
      if (gesture = gestures[touches[i].identifier]) {
        noevent$1(event);
        gesture("drag", event, touches[i]);
      }
    }
  }
  function touchended(event) {
    var touches = event.changedTouches, n = touches.length, i, gesture;
    if (touchending)
      clearTimeout(touchending);
    touchending = setTimeout(function() {
      touchending = null;
    }, 500);
    for (i = 0; i < n; ++i) {
      if (gesture = gestures[touches[i].identifier]) {
        nopropagation$1(event);
        gesture("end", event, touches[i]);
      }
    }
  }
  function beforestart(that, container2, event, d, identifier, touch) {
    var dispatch2 = listeners.copy(), p = pointer(touch || event, container2), dx, dy, s;
    if ((s = subject.call(that, new DragEvent("beforestart", {
      sourceEvent: event,
      target: drag2,
      identifier,
      active,
      x: p[0],
      y: p[1],
      dx: 0,
      dy: 0,
      dispatch: dispatch2
    }), d)) == null)
      return;
    dx = s.x - p[0] || 0;
    dy = s.y - p[1] || 0;
    return function gesture(type, event2, touch2) {
      var p0 = p, n;
      switch (type) {
        case "start":
          gestures[identifier] = gesture, n = active++;
          break;
        case "end":
          delete gestures[identifier], --active;
        case "drag":
          p = pointer(touch2 || event2, container2), n = active;
          break;
      }
      dispatch2.call(
        type,
        that,
        new DragEvent(type, {
          sourceEvent: event2,
          subject: s,
          target: drag2,
          identifier,
          active: n,
          x: p[0] + dx,
          y: p[1] + dy,
          dx: p[0] - p0[0],
          dy: p[1] - p0[1],
          dispatch: dispatch2
        }),
        d
      );
    };
  }
  drag2.filter = function(_) {
    return arguments.length ? (filter2 = typeof _ === "function" ? _ : constant$2(!!_), drag2) : filter2;
  };
  drag2.container = function(_) {
    return arguments.length ? (container = typeof _ === "function" ? _ : constant$2(_), drag2) : container;
  };
  drag2.subject = function(_) {
    return arguments.length ? (subject = typeof _ === "function" ? _ : constant$2(_), drag2) : subject;
  };
  drag2.touchable = function(_) {
    return arguments.length ? (touchable = typeof _ === "function" ? _ : constant$2(!!_), drag2) : touchable;
  };
  drag2.on = function() {
    var value = listeners.on.apply(listeners, arguments);
    return value === listeners ? drag2 : value;
  };
  drag2.clickDistance = function(_) {
    return arguments.length ? (clickDistance2 = (_ = +_) * _, drag2) : Math.sqrt(clickDistance2);
  };
  return drag2;
}
function define(constructor, factory, prototype) {
  constructor.prototype = factory.prototype = prototype;
  prototype.constructor = constructor;
}
function extend(parent, definition) {
  var prototype = Object.create(parent.prototype);
  for (var key in definition)
    prototype[key] = definition[key];
  return prototype;
}
function Color() {
}
var darker = 0.7;
var brighter = 1 / darker;
var reI = "\\s*([+-]?\\d+)\\s*", reN = "\\s*([+-]?(?:\\d*\\.)?\\d+(?:[eE][+-]?\\d+)?)\\s*", reP = "\\s*([+-]?(?:\\d*\\.)?\\d+(?:[eE][+-]?\\d+)?)%\\s*", reHex = /^#([0-9a-f]{3,8})$/, reRgbInteger = new RegExp(`^rgb\\(${reI},${reI},${reI}\\)$`), reRgbPercent = new RegExp(`^rgb\\(${reP},${reP},${reP}\\)$`), reRgbaInteger = new RegExp(`^rgba\\(${reI},${reI},${reI},${reN}\\)$`), reRgbaPercent = new RegExp(`^rgba\\(${reP},${reP},${reP},${reN}\\)$`), reHslPercent = new RegExp(`^hsl\\(${reN},${reP},${reP}\\)$`), reHslaPercent = new RegExp(`^hsla\\(${reN},${reP},${reP},${reN}\\)$`);
var named = {
  aliceblue: 15792383,
  antiquewhite: 16444375,
  aqua: 65535,
  aquamarine: 8388564,
  azure: 15794175,
  beige: 16119260,
  bisque: 16770244,
  black: 0,
  blanchedalmond: 16772045,
  blue: 255,
  blueviolet: 9055202,
  brown: 10824234,
  burlywood: 14596231,
  cadetblue: 6266528,
  chartreuse: 8388352,
  chocolate: 13789470,
  coral: 16744272,
  cornflowerblue: 6591981,
  cornsilk: 16775388,
  crimson: 14423100,
  cyan: 65535,
  darkblue: 139,
  darkcyan: 35723,
  darkgoldenrod: 12092939,
  darkgray: 11119017,
  darkgreen: 25600,
  darkgrey: 11119017,
  darkkhaki: 12433259,
  darkmagenta: 9109643,
  darkolivegreen: 5597999,
  darkorange: 16747520,
  darkorchid: 10040012,
  darkred: 9109504,
  darksalmon: 15308410,
  darkseagreen: 9419919,
  darkslateblue: 4734347,
  darkslategray: 3100495,
  darkslategrey: 3100495,
  darkturquoise: 52945,
  darkviolet: 9699539,
  deeppink: 16716947,
  deepskyblue: 49151,
  dimgray: 6908265,
  dimgrey: 6908265,
  dodgerblue: 2003199,
  firebrick: 11674146,
  floralwhite: 16775920,
  forestgreen: 2263842,
  fuchsia: 16711935,
  gainsboro: 14474460,
  ghostwhite: 16316671,
  gold: 16766720,
  goldenrod: 14329120,
  gray: 8421504,
  green: 32768,
  greenyellow: 11403055,
  grey: 8421504,
  honeydew: 15794160,
  hotpink: 16738740,
  indianred: 13458524,
  indigo: 4915330,
  ivory: 16777200,
  khaki: 15787660,
  lavender: 15132410,
  lavenderblush: 16773365,
  lawngreen: 8190976,
  lemonchiffon: 16775885,
  lightblue: 11393254,
  lightcoral: 15761536,
  lightcyan: 14745599,
  lightgoldenrodyellow: 16448210,
  lightgray: 13882323,
  lightgreen: 9498256,
  lightgrey: 13882323,
  lightpink: 16758465,
  lightsalmon: 16752762,
  lightseagreen: 2142890,
  lightskyblue: 8900346,
  lightslategray: 7833753,
  lightslategrey: 7833753,
  lightsteelblue: 11584734,
  lightyellow: 16777184,
  lime: 65280,
  limegreen: 3329330,
  linen: 16445670,
  magenta: 16711935,
  maroon: 8388608,
  mediumaquamarine: 6737322,
  mediumblue: 205,
  mediumorchid: 12211667,
  mediumpurple: 9662683,
  mediumseagreen: 3978097,
  mediumslateblue: 8087790,
  mediumspringgreen: 64154,
  mediumturquoise: 4772300,
  mediumvioletred: 13047173,
  midnightblue: 1644912,
  mintcream: 16121850,
  mistyrose: 16770273,
  moccasin: 16770229,
  navajowhite: 16768685,
  navy: 128,
  oldlace: 16643558,
  olive: 8421376,
  olivedrab: 7048739,
  orange: 16753920,
  orangered: 16729344,
  orchid: 14315734,
  palegoldenrod: 15657130,
  palegreen: 10025880,
  paleturquoise: 11529966,
  palevioletred: 14381203,
  papayawhip: 16773077,
  peachpuff: 16767673,
  peru: 13468991,
  pink: 16761035,
  plum: 14524637,
  powderblue: 11591910,
  purple: 8388736,
  rebeccapurple: 6697881,
  red: 16711680,
  rosybrown: 12357519,
  royalblue: 4286945,
  saddlebrown: 9127187,
  salmon: 16416882,
  sandybrown: 16032864,
  seagreen: 3050327,
  seashell: 16774638,
  sienna: 10506797,
  silver: 12632256,
  skyblue: 8900331,
  slateblue: 6970061,
  slategray: 7372944,
  slategrey: 7372944,
  snow: 16775930,
  springgreen: 65407,
  steelblue: 4620980,
  tan: 13808780,
  teal: 32896,
  thistle: 14204888,
  tomato: 16737095,
  turquoise: 4251856,
  violet: 15631086,
  wheat: 16113331,
  white: 16777215,
  whitesmoke: 16119285,
  yellow: 16776960,
  yellowgreen: 10145074
};
define(Color, color, {
  copy(channels) {
    return Object.assign(new this.constructor(), this, channels);
  },
  displayable() {
    return this.rgb().displayable();
  },
  hex: color_formatHex,
  // Deprecated! Use color.formatHex.
  formatHex: color_formatHex,
  formatHex8: color_formatHex8,
  formatHsl: color_formatHsl,
  formatRgb: color_formatRgb,
  toString: color_formatRgb
});
function color_formatHex() {
  return this.rgb().formatHex();
}
function color_formatHex8() {
  return this.rgb().formatHex8();
}
function color_formatHsl() {
  return hslConvert(this).formatHsl();
}
function color_formatRgb() {
  return this.rgb().formatRgb();
}
function color(format) {
  var m, l;
  format = (format + "").trim().toLowerCase();
  return (m = reHex.exec(format)) ? (l = m[1].length, m = parseInt(m[1], 16), l === 6 ? rgbn(m) : l === 3 ? new Rgb(m >> 8 & 15 | m >> 4 & 240, m >> 4 & 15 | m & 240, (m & 15) << 4 | m & 15, 1) : l === 8 ? rgba(m >> 24 & 255, m >> 16 & 255, m >> 8 & 255, (m & 255) / 255) : l === 4 ? rgba(m >> 12 & 15 | m >> 8 & 240, m >> 8 & 15 | m >> 4 & 240, m >> 4 & 15 | m & 240, ((m & 15) << 4 | m & 15) / 255) : null) : (m = reRgbInteger.exec(format)) ? new Rgb(m[1], m[2], m[3], 1) : (m = reRgbPercent.exec(format)) ? new Rgb(m[1] * 255 / 100, m[2] * 255 / 100, m[3] * 255 / 100, 1) : (m = reRgbaInteger.exec(format)) ? rgba(m[1], m[2], m[3], m[4]) : (m = reRgbaPercent.exec(format)) ? rgba(m[1] * 255 / 100, m[2] * 255 / 100, m[3] * 255 / 100, m[4]) : (m = reHslPercent.exec(format)) ? hsla(m[1], m[2] / 100, m[3] / 100, 1) : (m = reHslaPercent.exec(format)) ? hsla(m[1], m[2] / 100, m[3] / 100, m[4]) : named.hasOwnProperty(format) ? rgbn(named[format]) : format === "transparent" ? new Rgb(NaN, NaN, NaN, 0) : null;
}
function rgbn(n) {
  return new Rgb(n >> 16 & 255, n >> 8 & 255, n & 255, 1);
}
function rgba(r, g, b, a) {
  if (a <= 0)
    r = g = b = NaN;
  return new Rgb(r, g, b, a);
}
function rgbConvert(o) {
  if (!(o instanceof Color))
    o = color(o);
  if (!o)
    return new Rgb();
  o = o.rgb();
  return new Rgb(o.r, o.g, o.b, o.opacity);
}
function rgb(r, g, b, opacity) {
  return arguments.length === 1 ? rgbConvert(r) : new Rgb(r, g, b, opacity == null ? 1 : opacity);
}
function Rgb(r, g, b, opacity) {
  this.r = +r;
  this.g = +g;
  this.b = +b;
  this.opacity = +opacity;
}
define(Rgb, rgb, extend(Color, {
  brighter(k) {
    k = k == null ? brighter : Math.pow(brighter, k);
    return new Rgb(this.r * k, this.g * k, this.b * k, this.opacity);
  },
  darker(k) {
    k = k == null ? darker : Math.pow(darker, k);
    return new Rgb(this.r * k, this.g * k, this.b * k, this.opacity);
  },
  rgb() {
    return this;
  },
  clamp() {
    return new Rgb(clampi(this.r), clampi(this.g), clampi(this.b), clampa(this.opacity));
  },
  displayable() {
    return -0.5 <= this.r && this.r < 255.5 && (-0.5 <= this.g && this.g < 255.5) && (-0.5 <= this.b && this.b < 255.5) && (0 <= this.opacity && this.opacity <= 1);
  },
  hex: rgb_formatHex,
  // Deprecated! Use color.formatHex.
  formatHex: rgb_formatHex,
  formatHex8: rgb_formatHex8,
  formatRgb: rgb_formatRgb,
  toString: rgb_formatRgb
}));
function rgb_formatHex() {
  return `#${hex(this.r)}${hex(this.g)}${hex(this.b)}`;
}
function rgb_formatHex8() {
  return `#${hex(this.r)}${hex(this.g)}${hex(this.b)}${hex((isNaN(this.opacity) ? 1 : this.opacity) * 255)}`;
}
function rgb_formatRgb() {
  const a = clampa(this.opacity);
  return `${a === 1 ? "rgb(" : "rgba("}${clampi(this.r)}, ${clampi(this.g)}, ${clampi(this.b)}${a === 1 ? ")" : `, ${a})`}`;
}
function clampa(opacity) {
  return isNaN(opacity) ? 1 : Math.max(0, Math.min(1, opacity));
}
function clampi(value) {
  return Math.max(0, Math.min(255, Math.round(value) || 0));
}
function hex(value) {
  value = clampi(value);
  return (value < 16 ? "0" : "") + value.toString(16);
}
function hsla(h2, s, l, a) {
  if (a <= 0)
    h2 = s = l = NaN;
  else if (l <= 0 || l >= 1)
    h2 = s = NaN;
  else if (s <= 0)
    h2 = NaN;
  return new Hsl(h2, s, l, a);
}
function hslConvert(o) {
  if (o instanceof Hsl)
    return new Hsl(o.h, o.s, o.l, o.opacity);
  if (!(o instanceof Color))
    o = color(o);
  if (!o)
    return new Hsl();
  if (o instanceof Hsl)
    return o;
  o = o.rgb();
  var r = o.r / 255, g = o.g / 255, b = o.b / 255, min = Math.min(r, g, b), max = Math.max(r, g, b), h2 = NaN, s = max - min, l = (max + min) / 2;
  if (s) {
    if (r === max)
      h2 = (g - b) / s + (g < b) * 6;
    else if (g === max)
      h2 = (b - r) / s + 2;
    else
      h2 = (r - g) / s + 4;
    s /= l < 0.5 ? max + min : 2 - max - min;
    h2 *= 60;
  } else {
    s = l > 0 && l < 1 ? 0 : h2;
  }
  return new Hsl(h2, s, l, o.opacity);
}
function hsl(h2, s, l, opacity) {
  return arguments.length === 1 ? hslConvert(h2) : new Hsl(h2, s, l, opacity == null ? 1 : opacity);
}
function Hsl(h2, s, l, opacity) {
  this.h = +h2;
  this.s = +s;
  this.l = +l;
  this.opacity = +opacity;
}
define(Hsl, hsl, extend(Color, {
  brighter(k) {
    k = k == null ? brighter : Math.pow(brighter, k);
    return new Hsl(this.h, this.s, this.l * k, this.opacity);
  },
  darker(k) {
    k = k == null ? darker : Math.pow(darker, k);
    return new Hsl(this.h, this.s, this.l * k, this.opacity);
  },
  rgb() {
    var h2 = this.h % 360 + (this.h < 0) * 360, s = isNaN(h2) || isNaN(this.s) ? 0 : this.s, l = this.l, m2 = l + (l < 0.5 ? l : 1 - l) * s, m1 = 2 * l - m2;
    return new Rgb(
      hsl2rgb(h2 >= 240 ? h2 - 240 : h2 + 120, m1, m2),
      hsl2rgb(h2, m1, m2),
      hsl2rgb(h2 < 120 ? h2 + 240 : h2 - 120, m1, m2),
      this.opacity
    );
  },
  clamp() {
    return new Hsl(clamph(this.h), clampt(this.s), clampt(this.l), clampa(this.opacity));
  },
  displayable() {
    return (0 <= this.s && this.s <= 1 || isNaN(this.s)) && (0 <= this.l && this.l <= 1) && (0 <= this.opacity && this.opacity <= 1);
  },
  formatHsl() {
    const a = clampa(this.opacity);
    return `${a === 1 ? "hsl(" : "hsla("}${clamph(this.h)}, ${clampt(this.s) * 100}%, ${clampt(this.l) * 100}%${a === 1 ? ")" : `, ${a})`}`;
  }
}));
function clamph(value) {
  value = (value || 0) % 360;
  return value < 0 ? value + 360 : value;
}
function clampt(value) {
  return Math.max(0, Math.min(1, value || 0));
}
function hsl2rgb(h2, m1, m2) {
  return (h2 < 60 ? m1 + (m2 - m1) * h2 / 60 : h2 < 180 ? m2 : h2 < 240 ? m1 + (m2 - m1) * (240 - h2) / 60 : m1) * 255;
}
const constant$1 = (x) => () => x;
function linear(a, d) {
  return function(t) {
    return a + t * d;
  };
}
function exponential(a, b, y) {
  return a = Math.pow(a, y), b = Math.pow(b, y) - a, y = 1 / y, function(t) {
    return Math.pow(a + t * b, y);
  };
}
function gamma(y) {
  return (y = +y) === 1 ? nogamma : function(a, b) {
    return b - a ? exponential(a, b, y) : constant$1(isNaN(a) ? b : a);
  };
}
function nogamma(a, b) {
  var d = b - a;
  return d ? linear(a, d) : constant$1(isNaN(a) ? b : a);
}
const interpolateRgb = function rgbGamma(y) {
  var color2 = gamma(y);
  function rgb$1(start2, end) {
    var r = color2((start2 = rgb(start2)).r, (end = rgb(end)).r), g = color2(start2.g, end.g), b = color2(start2.b, end.b), opacity = nogamma(start2.opacity, end.opacity);
    return function(t) {
      start2.r = r(t);
      start2.g = g(t);
      start2.b = b(t);
      start2.opacity = opacity(t);
      return start2 + "";
    };
  }
  rgb$1.gamma = rgbGamma;
  return rgb$1;
}(1);
function numberArray(a, b) {
  if (!b)
    b = [];
  var n = a ? Math.min(b.length, a.length) : 0, c = b.slice(), i;
  return function(t) {
    for (i = 0; i < n; ++i)
      c[i] = a[i] * (1 - t) + b[i] * t;
    return c;
  };
}
function isNumberArray(x) {
  return ArrayBuffer.isView(x) && !(x instanceof DataView);
}
function genericArray(a, b) {
  var nb = b ? b.length : 0, na = a ? Math.min(nb, a.length) : 0, x = new Array(na), c = new Array(nb), i;
  for (i = 0; i < na; ++i)
    x[i] = interpolate$1(a[i], b[i]);
  for (; i < nb; ++i)
    c[i] = b[i];
  return function(t) {
    for (i = 0; i < na; ++i)
      c[i] = x[i](t);
    return c;
  };
}
function date(a, b) {
  var d = /* @__PURE__ */ new Date();
  return a = +a, b = +b, function(t) {
    return d.setTime(a * (1 - t) + b * t), d;
  };
}
function interpolateNumber(a, b) {
  return a = +a, b = +b, function(t) {
    return a * (1 - t) + b * t;
  };
}
function object(a, b) {
  var i = {}, c = {}, k;
  if (a === null || typeof a !== "object")
    a = {};
  if (b === null || typeof b !== "object")
    b = {};
  for (k in b) {
    if (k in a) {
      i[k] = interpolate$1(a[k], b[k]);
    } else {
      c[k] = b[k];
    }
  }
  return function(t) {
    for (k in i)
      c[k] = i[k](t);
    return c;
  };
}
var reA = /[-+]?(?:\d+\.?\d*|\.?\d+)(?:[eE][-+]?\d+)?/g, reB = new RegExp(reA.source, "g");
function zero(b) {
  return function() {
    return b;
  };
}
function one(b) {
  return function(t) {
    return b(t) + "";
  };
}
function interpolateString(a, b) {
  var bi = reA.lastIndex = reB.lastIndex = 0, am, bm, bs, i = -1, s = [], q = [];
  a = a + "", b = b + "";
  while ((am = reA.exec(a)) && (bm = reB.exec(b))) {
    if ((bs = bm.index) > bi) {
      bs = b.slice(bi, bs);
      if (s[i])
        s[i] += bs;
      else
        s[++i] = bs;
    }
    if ((am = am[0]) === (bm = bm[0])) {
      if (s[i])
        s[i] += bm;
      else
        s[++i] = bm;
    } else {
      s[++i] = null;
      q.push({ i, x: interpolateNumber(am, bm) });
    }
    bi = reB.lastIndex;
  }
  if (bi < b.length) {
    bs = b.slice(bi);
    if (s[i])
      s[i] += bs;
    else
      s[++i] = bs;
  }
  return s.length < 2 ? q[0] ? one(q[0].x) : zero(b) : (b = q.length, function(t) {
    for (var i2 = 0, o; i2 < b; ++i2)
      s[(o = q[i2]).i] = o.x(t);
    return s.join("");
  });
}
function interpolate$1(a, b) {
  var t = typeof b, c;
  return b == null || t === "boolean" ? constant$1(b) : (t === "number" ? interpolateNumber : t === "string" ? (c = color(b)) ? (b = c, interpolateRgb) : interpolateString : b instanceof color ? interpolateRgb : b instanceof Date ? date : isNumberArray(b) ? numberArray : Array.isArray(b) ? genericArray : typeof b.valueOf !== "function" && typeof b.toString !== "function" || isNaN(b) ? object : interpolateNumber)(a, b);
}
var degrees = 180 / Math.PI;
var identity$1 = {
  translateX: 0,
  translateY: 0,
  rotate: 0,
  skewX: 0,
  scaleX: 1,
  scaleY: 1
};
function decompose(a, b, c, d, e, f) {
  var scaleX, scaleY, skewX;
  if (scaleX = Math.sqrt(a * a + b * b))
    a /= scaleX, b /= scaleX;
  if (skewX = a * c + b * d)
    c -= a * skewX, d -= b * skewX;
  if (scaleY = Math.sqrt(c * c + d * d))
    c /= scaleY, d /= scaleY, skewX /= scaleY;
  if (a * d < b * c)
    a = -a, b = -b, skewX = -skewX, scaleX = -scaleX;
  return {
    translateX: e,
    translateY: f,
    rotate: Math.atan2(b, a) * degrees,
    skewX: Math.atan(skewX) * degrees,
    scaleX,
    scaleY
  };
}
var svgNode;
function parseCss(value) {
  const m = new (typeof DOMMatrix === "function" ? DOMMatrix : WebKitCSSMatrix)(value + "");
  return m.isIdentity ? identity$1 : decompose(m.a, m.b, m.c, m.d, m.e, m.f);
}
function parseSvg(value) {
  if (value == null)
    return identity$1;
  if (!svgNode)
    svgNode = document.createElementNS("http://www.w3.org/2000/svg", "g");
  svgNode.setAttribute("transform", value);
  if (!(value = svgNode.transform.baseVal.consolidate()))
    return identity$1;
  value = value.matrix;
  return decompose(value.a, value.b, value.c, value.d, value.e, value.f);
}
function interpolateTransform(parse, pxComma, pxParen, degParen) {
  function pop(s) {
    return s.length ? s.pop() + " " : "";
  }
  function translate(xa, ya, xb, yb, s, q) {
    if (xa !== xb || ya !== yb) {
      var i = s.push("translate(", null, pxComma, null, pxParen);
      q.push({ i: i - 4, x: interpolateNumber(xa, xb) }, { i: i - 2, x: interpolateNumber(ya, yb) });
    } else if (xb || yb) {
      s.push("translate(" + xb + pxComma + yb + pxParen);
    }
  }
  function rotate(a, b, s, q) {
    if (a !== b) {
      if (a - b > 180)
        b += 360;
      else if (b - a > 180)
        a += 360;
      q.push({ i: s.push(pop(s) + "rotate(", null, degParen) - 2, x: interpolateNumber(a, b) });
    } else if (b) {
      s.push(pop(s) + "rotate(" + b + degParen);
    }
  }
  function skewX(a, b, s, q) {
    if (a !== b) {
      q.push({ i: s.push(pop(s) + "skewX(", null, degParen) - 2, x: interpolateNumber(a, b) });
    } else if (b) {
      s.push(pop(s) + "skewX(" + b + degParen);
    }
  }
  function scale(xa, ya, xb, yb, s, q) {
    if (xa !== xb || ya !== yb) {
      var i = s.push(pop(s) + "scale(", null, ",", null, ")");
      q.push({ i: i - 4, x: interpolateNumber(xa, xb) }, { i: i - 2, x: interpolateNumber(ya, yb) });
    } else if (xb !== 1 || yb !== 1) {
      s.push(pop(s) + "scale(" + xb + "," + yb + ")");
    }
  }
  return function(a, b) {
    var s = [], q = [];
    a = parse(a), b = parse(b);
    translate(a.translateX, a.translateY, b.translateX, b.translateY, s, q);
    rotate(a.rotate, b.rotate, s, q);
    skewX(a.skewX, b.skewX, s, q);
    scale(a.scaleX, a.scaleY, b.scaleX, b.scaleY, s, q);
    a = b = null;
    return function(t) {
      var i = -1, n = q.length, o;
      while (++i < n)
        s[(o = q[i]).i] = o.x(t);
      return s.join("");
    };
  };
}
var interpolateTransformCss = interpolateTransform(parseCss, "px, ", "px)", "deg)");
var interpolateTransformSvg = interpolateTransform(parseSvg, ", ", ")", ")");
var epsilon2 = 1e-12;
function cosh(x) {
  return ((x = Math.exp(x)) + 1 / x) / 2;
}
function sinh(x) {
  return ((x = Math.exp(x)) - 1 / x) / 2;
}
function tanh(x) {
  return ((x = Math.exp(2 * x)) - 1) / (x + 1);
}
const interpolateZoom = function zoomRho(rho, rho2, rho4) {
  function zoom2(p0, p1) {
    var ux0 = p0[0], uy0 = p0[1], w0 = p0[2], ux1 = p1[0], uy1 = p1[1], w1 = p1[2], dx = ux1 - ux0, dy = uy1 - uy0, d2 = dx * dx + dy * dy, i, S;
    if (d2 < epsilon2) {
      S = Math.log(w1 / w0) / rho;
      i = function(t) {
        return [
          ux0 + t * dx,
          uy0 + t * dy,
          w0 * Math.exp(rho * t * S)
        ];
      };
    } else {
      var d1 = Math.sqrt(d2), b0 = (w1 * w1 - w0 * w0 + rho4 * d2) / (2 * w0 * rho2 * d1), b1 = (w1 * w1 - w0 * w0 - rho4 * d2) / (2 * w1 * rho2 * d1), r0 = Math.log(Math.sqrt(b0 * b0 + 1) - b0), r1 = Math.log(Math.sqrt(b1 * b1 + 1) - b1);
      S = (r1 - r0) / rho;
      i = function(t) {
        var s = t * S, coshr0 = cosh(r0), u = w0 / (rho2 * d1) * (coshr0 * tanh(rho * s + r0) - sinh(r0));
        return [
          ux0 + u * dx,
          uy0 + u * dy,
          w0 * coshr0 / cosh(rho * s + r0)
        ];
      };
    }
    i.duration = S * 1e3 * rho / Math.SQRT2;
    return i;
  }
  zoom2.rho = function(_) {
    var _1 = Math.max(1e-3, +_), _2 = _1 * _1, _4 = _2 * _2;
    return zoomRho(_1, _2, _4);
  };
  return zoom2;
}(Math.SQRT2, 2, 4);
var frame = 0, timeout$1 = 0, interval = 0, pokeDelay = 1e3, taskHead, taskTail, clockLast = 0, clockNow = 0, clockSkew = 0, clock = typeof performance === "object" && performance.now ? performance : Date, setFrame = typeof window === "object" && window.requestAnimationFrame ? window.requestAnimationFrame.bind(window) : function(f) {
  setTimeout(f, 17);
};
function now() {
  return clockNow || (setFrame(clearNow), clockNow = clock.now() + clockSkew);
}
function clearNow() {
  clockNow = 0;
}
function Timer() {
  this._call = this._time = this._next = null;
}
Timer.prototype = timer.prototype = {
  constructor: Timer,
  restart: function(callback, delay, time) {
    if (typeof callback !== "function")
      throw new TypeError("callback is not a function");
    time = (time == null ? now() : +time) + (delay == null ? 0 : +delay);
    if (!this._next && taskTail !== this) {
      if (taskTail)
        taskTail._next = this;
      else
        taskHead = this;
      taskTail = this;
    }
    this._call = callback;
    this._time = time;
    sleep();
  },
  stop: function() {
    if (this._call) {
      this._call = null;
      this._time = Infinity;
      sleep();
    }
  }
};
function timer(callback, delay, time) {
  var t = new Timer();
  t.restart(callback, delay, time);
  return t;
}
function timerFlush() {
  now();
  ++frame;
  var t = taskHead, e;
  while (t) {
    if ((e = clockNow - t._time) >= 0)
      t._call.call(void 0, e);
    t = t._next;
  }
  --frame;
}
function wake() {
  clockNow = (clockLast = clock.now()) + clockSkew;
  frame = timeout$1 = 0;
  try {
    timerFlush();
  } finally {
    frame = 0;
    nap();
    clockNow = 0;
  }
}
function poke() {
  var now2 = clock.now(), delay = now2 - clockLast;
  if (delay > pokeDelay)
    clockSkew -= delay, clockLast = now2;
}
function nap() {
  var t0, t1 = taskHead, t2, time = Infinity;
  while (t1) {
    if (t1._call) {
      if (time > t1._time)
        time = t1._time;
      t0 = t1, t1 = t1._next;
    } else {
      t2 = t1._next, t1._next = null;
      t1 = t0 ? t0._next = t2 : taskHead = t2;
    }
  }
  taskTail = t0;
  sleep(time);
}
function sleep(time) {
  if (frame)
    return;
  if (timeout$1)
    timeout$1 = clearTimeout(timeout$1);
  var delay = time - clockNow;
  if (delay > 24) {
    if (time < Infinity)
      timeout$1 = setTimeout(wake, time - clock.now() - clockSkew);
    if (interval)
      interval = clearInterval(interval);
  } else {
    if (!interval)
      clockLast = clock.now(), interval = setInterval(poke, pokeDelay);
    frame = 1, setFrame(wake);
  }
}
function timeout(callback, delay, time) {
  var t = new Timer();
  delay = delay == null ? 0 : +delay;
  t.restart((elapsed) => {
    t.stop();
    callback(elapsed + delay);
  }, delay, time);
  return t;
}
var emptyOn = dispatch("start", "end", "cancel", "interrupt");
var emptyTween = [];
var CREATED = 0;
var SCHEDULED = 1;
var STARTING = 2;
var STARTED = 3;
var RUNNING = 4;
var ENDING = 5;
var ENDED = 6;
function schedule(node, name, id2, index, group, timing) {
  var schedules = node.__transition;
  if (!schedules)
    node.__transition = {};
  else if (id2 in schedules)
    return;
  create(node, id2, {
    name,
    index,
    // For context during callback.
    group,
    // For context during callback.
    on: emptyOn,
    tween: emptyTween,
    time: timing.time,
    delay: timing.delay,
    duration: timing.duration,
    ease: timing.ease,
    timer: null,
    state: CREATED
  });
}
function init(node, id2) {
  var schedule2 = get(node, id2);
  if (schedule2.state > CREATED)
    throw new Error("too late; already scheduled");
  return schedule2;
}
function set(node, id2) {
  var schedule2 = get(node, id2);
  if (schedule2.state > STARTED)
    throw new Error("too late; already running");
  return schedule2;
}
function get(node, id2) {
  var schedule2 = node.__transition;
  if (!schedule2 || !(schedule2 = schedule2[id2]))
    throw new Error("transition not found");
  return schedule2;
}
function create(node, id2, self) {
  var schedules = node.__transition, tween;
  schedules[id2] = self;
  self.timer = timer(schedule2, 0, self.time);
  function schedule2(elapsed) {
    self.state = SCHEDULED;
    self.timer.restart(start2, self.delay, self.time);
    if (self.delay <= elapsed)
      start2(elapsed - self.delay);
  }
  function start2(elapsed) {
    var i, j, n, o;
    if (self.state !== SCHEDULED)
      return stop();
    for (i in schedules) {
      o = schedules[i];
      if (o.name !== self.name)
        continue;
      if (o.state === STARTED)
        return timeout(start2);
      if (o.state === RUNNING) {
        o.state = ENDED;
        o.timer.stop();
        o.on.call("interrupt", node, node.__data__, o.index, o.group);
        delete schedules[i];
      } else if (+i < id2) {
        o.state = ENDED;
        o.timer.stop();
        o.on.call("cancel", node, node.__data__, o.index, o.group);
        delete schedules[i];
      }
    }
    timeout(function() {
      if (self.state === STARTED) {
        self.state = RUNNING;
        self.timer.restart(tick, self.delay, self.time);
        tick(elapsed);
      }
    });
    self.state = STARTING;
    self.on.call("start", node, node.__data__, self.index, self.group);
    if (self.state !== STARTING)
      return;
    self.state = STARTED;
    tween = new Array(n = self.tween.length);
    for (i = 0, j = -1; i < n; ++i) {
      if (o = self.tween[i].value.call(node, node.__data__, self.index, self.group)) {
        tween[++j] = o;
      }
    }
    tween.length = j + 1;
  }
  function tick(elapsed) {
    var t = elapsed < self.duration ? self.ease.call(null, elapsed / self.duration) : (self.timer.restart(stop), self.state = ENDING, 1), i = -1, n = tween.length;
    while (++i < n) {
      tween[i].call(node, t);
    }
    if (self.state === ENDING) {
      self.on.call("end", node, node.__data__, self.index, self.group);
      stop();
    }
  }
  function stop() {
    self.state = ENDED;
    self.timer.stop();
    delete schedules[id2];
    for (var i in schedules)
      return;
    delete node.__transition;
  }
}
function interrupt(node, name) {
  var schedules = node.__transition, schedule2, active, empty2 = true, i;
  if (!schedules)
    return;
  name = name == null ? null : name + "";
  for (i in schedules) {
    if ((schedule2 = schedules[i]).name !== name) {
      empty2 = false;
      continue;
    }
    active = schedule2.state > STARTING && schedule2.state < ENDING;
    schedule2.state = ENDED;
    schedule2.timer.stop();
    schedule2.on.call(active ? "interrupt" : "cancel", node, node.__data__, schedule2.index, schedule2.group);
    delete schedules[i];
  }
  if (empty2)
    delete node.__transition;
}
function selection_interrupt(name) {
  return this.each(function() {
    interrupt(this, name);
  });
}
function tweenRemove(id2, name) {
  var tween0, tween1;
  return function() {
    var schedule2 = set(this, id2), tween = schedule2.tween;
    if (tween !== tween0) {
      tween1 = tween0 = tween;
      for (var i = 0, n = tween1.length; i < n; ++i) {
        if (tween1[i].name === name) {
          tween1 = tween1.slice();
          tween1.splice(i, 1);
          break;
        }
      }
    }
    schedule2.tween = tween1;
  };
}
function tweenFunction(id2, name, value) {
  var tween0, tween1;
  if (typeof value !== "function")
    throw new Error();
  return function() {
    var schedule2 = set(this, id2), tween = schedule2.tween;
    if (tween !== tween0) {
      tween1 = (tween0 = tween).slice();
      for (var t = { name, value }, i = 0, n = tween1.length; i < n; ++i) {
        if (tween1[i].name === name) {
          tween1[i] = t;
          break;
        }
      }
      if (i === n)
        tween1.push(t);
    }
    schedule2.tween = tween1;
  };
}
function transition_tween(name, value) {
  var id2 = this._id;
  name += "";
  if (arguments.length < 2) {
    var tween = get(this.node(), id2).tween;
    for (var i = 0, n = tween.length, t; i < n; ++i) {
      if ((t = tween[i]).name === name) {
        return t.value;
      }
    }
    return null;
  }
  return this.each((value == null ? tweenRemove : tweenFunction)(id2, name, value));
}
function tweenValue(transition, name, value) {
  var id2 = transition._id;
  transition.each(function() {
    var schedule2 = set(this, id2);
    (schedule2.value || (schedule2.value = {}))[name] = value.apply(this, arguments);
  });
  return function(node) {
    return get(node, id2).value[name];
  };
}
function interpolate(a, b) {
  var c;
  return (typeof b === "number" ? interpolateNumber : b instanceof color ? interpolateRgb : (c = color(b)) ? (b = c, interpolateRgb) : interpolateString)(a, b);
}
function attrRemove(name) {
  return function() {
    this.removeAttribute(name);
  };
}
function attrRemoveNS(fullname) {
  return function() {
    this.removeAttributeNS(fullname.space, fullname.local);
  };
}
function attrConstant(name, interpolate2, value1) {
  var string00, string1 = value1 + "", interpolate0;
  return function() {
    var string0 = this.getAttribute(name);
    return string0 === string1 ? null : string0 === string00 ? interpolate0 : interpolate0 = interpolate2(string00 = string0, value1);
  };
}
function attrConstantNS(fullname, interpolate2, value1) {
  var string00, string1 = value1 + "", interpolate0;
  return function() {
    var string0 = this.getAttributeNS(fullname.space, fullname.local);
    return string0 === string1 ? null : string0 === string00 ? interpolate0 : interpolate0 = interpolate2(string00 = string0, value1);
  };
}
function attrFunction(name, interpolate2, value) {
  var string00, string10, interpolate0;
  return function() {
    var string0, value1 = value(this), string1;
    if (value1 == null)
      return void this.removeAttribute(name);
    string0 = this.getAttribute(name);
    string1 = value1 + "";
    return string0 === string1 ? null : string0 === string00 && string1 === string10 ? interpolate0 : (string10 = string1, interpolate0 = interpolate2(string00 = string0, value1));
  };
}
function attrFunctionNS(fullname, interpolate2, value) {
  var string00, string10, interpolate0;
  return function() {
    var string0, value1 = value(this), string1;
    if (value1 == null)
      return void this.removeAttributeNS(fullname.space, fullname.local);
    string0 = this.getAttributeNS(fullname.space, fullname.local);
    string1 = value1 + "";
    return string0 === string1 ? null : string0 === string00 && string1 === string10 ? interpolate0 : (string10 = string1, interpolate0 = interpolate2(string00 = string0, value1));
  };
}
function transition_attr(name, value) {
  var fullname = namespace(name), i = fullname === "transform" ? interpolateTransformSvg : interpolate;
  return this.attrTween(name, typeof value === "function" ? (fullname.local ? attrFunctionNS : attrFunction)(fullname, i, tweenValue(this, "attr." + name, value)) : value == null ? (fullname.local ? attrRemoveNS : attrRemove)(fullname) : (fullname.local ? attrConstantNS : attrConstant)(fullname, i, value));
}
function attrInterpolate(name, i) {
  return function(t) {
    this.setAttribute(name, i.call(this, t));
  };
}
function attrInterpolateNS(fullname, i) {
  return function(t) {
    this.setAttributeNS(fullname.space, fullname.local, i.call(this, t));
  };
}
function attrTweenNS(fullname, value) {
  var t0, i0;
  function tween() {
    var i = value.apply(this, arguments);
    if (i !== i0)
      t0 = (i0 = i) && attrInterpolateNS(fullname, i);
    return t0;
  }
  tween._value = value;
  return tween;
}
function attrTween(name, value) {
  var t0, i0;
  function tween() {
    var i = value.apply(this, arguments);
    if (i !== i0)
      t0 = (i0 = i) && attrInterpolate(name, i);
    return t0;
  }
  tween._value = value;
  return tween;
}
function transition_attrTween(name, value) {
  var key = "attr." + name;
  if (arguments.length < 2)
    return (key = this.tween(key)) && key._value;
  if (value == null)
    return this.tween(key, null);
  if (typeof value !== "function")
    throw new Error();
  var fullname = namespace(name);
  return this.tween(key, (fullname.local ? attrTweenNS : attrTween)(fullname, value));
}
function delayFunction(id2, value) {
  return function() {
    init(this, id2).delay = +value.apply(this, arguments);
  };
}
function delayConstant(id2, value) {
  return value = +value, function() {
    init(this, id2).delay = value;
  };
}
function transition_delay(value) {
  var id2 = this._id;
  return arguments.length ? this.each((typeof value === "function" ? delayFunction : delayConstant)(id2, value)) : get(this.node(), id2).delay;
}
function durationFunction(id2, value) {
  return function() {
    set(this, id2).duration = +value.apply(this, arguments);
  };
}
function durationConstant(id2, value) {
  return value = +value, function() {
    set(this, id2).duration = value;
  };
}
function transition_duration(value) {
  var id2 = this._id;
  return arguments.length ? this.each((typeof value === "function" ? durationFunction : durationConstant)(id2, value)) : get(this.node(), id2).duration;
}
function easeConstant(id2, value) {
  if (typeof value !== "function")
    throw new Error();
  return function() {
    set(this, id2).ease = value;
  };
}
function transition_ease(value) {
  var id2 = this._id;
  return arguments.length ? this.each(easeConstant(id2, value)) : get(this.node(), id2).ease;
}
function easeVarying(id2, value) {
  return function() {
    var v = value.apply(this, arguments);
    if (typeof v !== "function")
      throw new Error();
    set(this, id2).ease = v;
  };
}
function transition_easeVarying(value) {
  if (typeof value !== "function")
    throw new Error();
  return this.each(easeVarying(this._id, value));
}
function transition_filter(match) {
  if (typeof match !== "function")
    match = matcher(match);
  for (var groups = this._groups, m = groups.length, subgroups = new Array(m), j = 0; j < m; ++j) {
    for (var group = groups[j], n = group.length, subgroup = subgroups[j] = [], node, i = 0; i < n; ++i) {
      if ((node = group[i]) && match.call(node, node.__data__, i, group)) {
        subgroup.push(node);
      }
    }
  }
  return new Transition(subgroups, this._parents, this._name, this._id);
}
function transition_merge(transition) {
  if (transition._id !== this._id)
    throw new Error();
  for (var groups0 = this._groups, groups1 = transition._groups, m0 = groups0.length, m1 = groups1.length, m = Math.min(m0, m1), merges = new Array(m0), j = 0; j < m; ++j) {
    for (var group0 = groups0[j], group1 = groups1[j], n = group0.length, merge = merges[j] = new Array(n), node, i = 0; i < n; ++i) {
      if (node = group0[i] || group1[i]) {
        merge[i] = node;
      }
    }
  }
  for (; j < m0; ++j) {
    merges[j] = groups0[j];
  }
  return new Transition(merges, this._parents, this._name, this._id);
}
function start(name) {
  return (name + "").trim().split(/^|\s+/).every(function(t) {
    var i = t.indexOf(".");
    if (i >= 0)
      t = t.slice(0, i);
    return !t || t === "start";
  });
}
function onFunction(id2, name, listener) {
  var on0, on1, sit = start(name) ? init : set;
  return function() {
    var schedule2 = sit(this, id2), on = schedule2.on;
    if (on !== on0)
      (on1 = (on0 = on).copy()).on(name, listener);
    schedule2.on = on1;
  };
}
function transition_on(name, listener) {
  var id2 = this._id;
  return arguments.length < 2 ? get(this.node(), id2).on.on(name) : this.each(onFunction(id2, name, listener));
}
function removeFunction(id2) {
  return function() {
    var parent = this.parentNode;
    for (var i in this.__transition)
      if (+i !== id2)
        return;
    if (parent)
      parent.removeChild(this);
  };
}
function transition_remove() {
  return this.on("end.remove", removeFunction(this._id));
}
function transition_select(select2) {
  var name = this._name, id2 = this._id;
  if (typeof select2 !== "function")
    select2 = selector(select2);
  for (var groups = this._groups, m = groups.length, subgroups = new Array(m), j = 0; j < m; ++j) {
    for (var group = groups[j], n = group.length, subgroup = subgroups[j] = new Array(n), node, subnode, i = 0; i < n; ++i) {
      if ((node = group[i]) && (subnode = select2.call(node, node.__data__, i, group))) {
        if ("__data__" in node)
          subnode.__data__ = node.__data__;
        subgroup[i] = subnode;
        schedule(subgroup[i], name, id2, i, subgroup, get(node, id2));
      }
    }
  }
  return new Transition(subgroups, this._parents, name, id2);
}
function transition_selectAll(select2) {
  var name = this._name, id2 = this._id;
  if (typeof select2 !== "function")
    select2 = selectorAll(select2);
  for (var groups = this._groups, m = groups.length, subgroups = [], parents = [], j = 0; j < m; ++j) {
    for (var group = groups[j], n = group.length, node, i = 0; i < n; ++i) {
      if (node = group[i]) {
        for (var children2 = select2.call(node, node.__data__, i, group), child, inherit2 = get(node, id2), k = 0, l = children2.length; k < l; ++k) {
          if (child = children2[k]) {
            schedule(child, name, id2, k, children2, inherit2);
          }
        }
        subgroups.push(children2);
        parents.push(node);
      }
    }
  }
  return new Transition(subgroups, parents, name, id2);
}
var Selection = selection.prototype.constructor;
function transition_selection() {
  return new Selection(this._groups, this._parents);
}
function styleNull(name, interpolate2) {
  var string00, string10, interpolate0;
  return function() {
    var string0 = styleValue(this, name), string1 = (this.style.removeProperty(name), styleValue(this, name));
    return string0 === string1 ? null : string0 === string00 && string1 === string10 ? interpolate0 : interpolate0 = interpolate2(string00 = string0, string10 = string1);
  };
}
function styleRemove(name) {
  return function() {
    this.style.removeProperty(name);
  };
}
function styleConstant(name, interpolate2, value1) {
  var string00, string1 = value1 + "", interpolate0;
  return function() {
    var string0 = styleValue(this, name);
    return string0 === string1 ? null : string0 === string00 ? interpolate0 : interpolate0 = interpolate2(string00 = string0, value1);
  };
}
function styleFunction(name, interpolate2, value) {
  var string00, string10, interpolate0;
  return function() {
    var string0 = styleValue(this, name), value1 = value(this), string1 = value1 + "";
    if (value1 == null)
      string1 = value1 = (this.style.removeProperty(name), styleValue(this, name));
    return string0 === string1 ? null : string0 === string00 && string1 === string10 ? interpolate0 : (string10 = string1, interpolate0 = interpolate2(string00 = string0, value1));
  };
}
function styleMaybeRemove(id2, name) {
  var on0, on1, listener0, key = "style." + name, event = "end." + key, remove2;
  return function() {
    var schedule2 = set(this, id2), on = schedule2.on, listener = schedule2.value[key] == null ? remove2 || (remove2 = styleRemove(name)) : void 0;
    if (on !== on0 || listener0 !== listener)
      (on1 = (on0 = on).copy()).on(event, listener0 = listener);
    schedule2.on = on1;
  };
}
function transition_style(name, value, priority) {
  var i = (name += "") === "transform" ? interpolateTransformCss : interpolate;
  return value == null ? this.styleTween(name, styleNull(name, i)).on("end.style." + name, styleRemove(name)) : typeof value === "function" ? this.styleTween(name, styleFunction(name, i, tweenValue(this, "style." + name, value))).each(styleMaybeRemove(this._id, name)) : this.styleTween(name, styleConstant(name, i, value), priority).on("end.style." + name, null);
}
function styleInterpolate(name, i, priority) {
  return function(t) {
    this.style.setProperty(name, i.call(this, t), priority);
  };
}
function styleTween(name, value, priority) {
  var t, i0;
  function tween() {
    var i = value.apply(this, arguments);
    if (i !== i0)
      t = (i0 = i) && styleInterpolate(name, i, priority);
    return t;
  }
  tween._value = value;
  return tween;
}
function transition_styleTween(name, value, priority) {
  var key = "style." + (name += "");
  if (arguments.length < 2)
    return (key = this.tween(key)) && key._value;
  if (value == null)
    return this.tween(key, null);
  if (typeof value !== "function")
    throw new Error();
  return this.tween(key, styleTween(name, value, priority == null ? "" : priority));
}
function textConstant(value) {
  return function() {
    this.textContent = value;
  };
}
function textFunction(value) {
  return function() {
    var value1 = value(this);
    this.textContent = value1 == null ? "" : value1;
  };
}
function transition_text(value) {
  return this.tween("text", typeof value === "function" ? textFunction(tweenValue(this, "text", value)) : textConstant(value == null ? "" : value + ""));
}
function textInterpolate(i) {
  return function(t) {
    this.textContent = i.call(this, t);
  };
}
function textTween(value) {
  var t0, i0;
  function tween() {
    var i = value.apply(this, arguments);
    if (i !== i0)
      t0 = (i0 = i) && textInterpolate(i);
    return t0;
  }
  tween._value = value;
  return tween;
}
function transition_textTween(value) {
  var key = "text";
  if (arguments.length < 1)
    return (key = this.tween(key)) && key._value;
  if (value == null)
    return this.tween(key, null);
  if (typeof value !== "function")
    throw new Error();
  return this.tween(key, textTween(value));
}
function transition_transition() {
  var name = this._name, id0 = this._id, id1 = newId();
  for (var groups = this._groups, m = groups.length, j = 0; j < m; ++j) {
    for (var group = groups[j], n = group.length, node, i = 0; i < n; ++i) {
      if (node = group[i]) {
        var inherit2 = get(node, id0);
        schedule(node, name, id1, i, group, {
          time: inherit2.time + inherit2.delay + inherit2.duration,
          delay: 0,
          duration: inherit2.duration,
          ease: inherit2.ease
        });
      }
    }
  }
  return new Transition(groups, this._parents, name, id1);
}
function transition_end() {
  var on0, on1, that = this, id2 = that._id, size = that.size();
  return new Promise(function(resolve, reject) {
    var cancel = { value: reject }, end = { value: function() {
      if (--size === 0)
        resolve();
    } };
    that.each(function() {
      var schedule2 = set(this, id2), on = schedule2.on;
      if (on !== on0) {
        on1 = (on0 = on).copy();
        on1._.cancel.push(cancel);
        on1._.interrupt.push(cancel);
        on1._.end.push(end);
      }
      schedule2.on = on1;
    });
    if (size === 0)
      resolve();
  });
}
var id = 0;
function Transition(groups, parents, name, id2) {
  this._groups = groups;
  this._parents = parents;
  this._name = name;
  this._id = id2;
}
function newId() {
  return ++id;
}
var selection_prototype = selection.prototype;
Transition.prototype = {
  constructor: Transition,
  select: transition_select,
  selectAll: transition_selectAll,
  selectChild: selection_prototype.selectChild,
  selectChildren: selection_prototype.selectChildren,
  filter: transition_filter,
  merge: transition_merge,
  selection: transition_selection,
  transition: transition_transition,
  call: selection_prototype.call,
  nodes: selection_prototype.nodes,
  node: selection_prototype.node,
  size: selection_prototype.size,
  empty: selection_prototype.empty,
  each: selection_prototype.each,
  on: transition_on,
  attr: transition_attr,
  attrTween: transition_attrTween,
  style: transition_style,
  styleTween: transition_styleTween,
  text: transition_text,
  textTween: transition_textTween,
  remove: transition_remove,
  tween: transition_tween,
  delay: transition_delay,
  duration: transition_duration,
  ease: transition_ease,
  easeVarying: transition_easeVarying,
  end: transition_end,
  [Symbol.iterator]: selection_prototype[Symbol.iterator]
};
function cubicInOut(t) {
  return ((t *= 2) <= 1 ? t * t * t : (t -= 2) * t * t + 2) / 2;
}
var defaultTiming = {
  time: null,
  // Set on use.
  delay: 0,
  duration: 250,
  ease: cubicInOut
};
function inherit(node, id2) {
  var timing;
  while (!(timing = node.__transition) || !(timing = timing[id2])) {
    if (!(node = node.parentNode)) {
      throw new Error(`transition ${id2} not found`);
    }
  }
  return timing;
}
function selection_transition(name) {
  var id2, timing;
  if (name instanceof Transition) {
    id2 = name._id, name = name._name;
  } else {
    id2 = newId(), (timing = defaultTiming).time = now(), name = name == null ? null : name + "";
  }
  for (var groups = this._groups, m = groups.length, j = 0; j < m; ++j) {
    for (var group = groups[j], n = group.length, node, i = 0; i < n; ++i) {
      if (node = group[i]) {
        schedule(node, name, id2, i, group, timing || inherit(node, id2));
      }
    }
  }
  return new Transition(groups, this._parents, name, id2);
}
selection.prototype.interrupt = selection_interrupt;
selection.prototype.transition = selection_transition;
const constant = (x) => () => x;
function ZoomEvent(type, {
  sourceEvent: sourceEvent2,
  target,
  transform,
  dispatch: dispatch2
}) {
  Object.defineProperties(this, {
    type: { value: type, enumerable: true, configurable: true },
    sourceEvent: { value: sourceEvent2, enumerable: true, configurable: true },
    target: { value: target, enumerable: true, configurable: true },
    transform: { value: transform, enumerable: true, configurable: true },
    _: { value: dispatch2 }
  });
}
function Transform(k, x, y) {
  this.k = k;
  this.x = x;
  this.y = y;
}
Transform.prototype = {
  constructor: Transform,
  scale: function(k) {
    return k === 1 ? this : new Transform(this.k * k, this.x, this.y);
  },
  translate: function(x, y) {
    return x === 0 & y === 0 ? this : new Transform(this.k, this.x + this.k * x, this.y + this.k * y);
  },
  apply: function(point) {
    return [point[0] * this.k + this.x, point[1] * this.k + this.y];
  },
  applyX: function(x) {
    return x * this.k + this.x;
  },
  applyY: function(y) {
    return y * this.k + this.y;
  },
  invert: function(location) {
    return [(location[0] - this.x) / this.k, (location[1] - this.y) / this.k];
  },
  invertX: function(x) {
    return (x - this.x) / this.k;
  },
  invertY: function(y) {
    return (y - this.y) / this.k;
  },
  rescaleX: function(x) {
    return x.copy().domain(x.range().map(this.invertX, this).map(x.invert, x));
  },
  rescaleY: function(y) {
    return y.copy().domain(y.range().map(this.invertY, this).map(y.invert, y));
  },
  toString: function() {
    return "translate(" + this.x + "," + this.y + ") scale(" + this.k + ")";
  }
};
var identity = new Transform(1, 0, 0);
Transform.prototype;
function nopropagation(event) {
  event.stopImmediatePropagation();
}
function noevent(event) {
  event.preventDefault();
  event.stopImmediatePropagation();
}
function defaultFilter(event) {
  return (!event.ctrlKey || event.type === "wheel") && !event.button;
}
function defaultExtent() {
  var e = this;
  if (e instanceof SVGElement) {
    e = e.ownerSVGElement || e;
    if (e.hasAttribute("viewBox")) {
      e = e.viewBox.baseVal;
      return [[e.x, e.y], [e.x + e.width, e.y + e.height]];
    }
    return [[0, 0], [e.width.baseVal.value, e.height.baseVal.value]];
  }
  return [[0, 0], [e.clientWidth, e.clientHeight]];
}
function defaultTransform() {
  return this.__zoom || identity;
}
function defaultWheelDelta(event) {
  return -event.deltaY * (event.deltaMode === 1 ? 0.05 : event.deltaMode ? 1 : 2e-3) * (event.ctrlKey ? 10 : 1);
}
function defaultTouchable() {
  return navigator.maxTouchPoints || "ontouchstart" in this;
}
function defaultConstrain(transform, extent, translateExtent) {
  var dx0 = transform.invertX(extent[0][0]) - translateExtent[0][0], dx1 = transform.invertX(extent[1][0]) - translateExtent[1][0], dy0 = transform.invertY(extent[0][1]) - translateExtent[0][1], dy1 = transform.invertY(extent[1][1]) - translateExtent[1][1];
  return transform.translate(
    dx1 > dx0 ? (dx0 + dx1) / 2 : Math.min(0, dx0) || Math.max(0, dx1),
    dy1 > dy0 ? (dy0 + dy1) / 2 : Math.min(0, dy0) || Math.max(0, dy1)
  );
}
function zoom() {
  var filter2 = defaultFilter, extent = defaultExtent, constrain = defaultConstrain, wheelDelta = defaultWheelDelta, touchable = defaultTouchable, scaleExtent = [0, Infinity], translateExtent = [[-Infinity, -Infinity], [Infinity, Infinity]], duration = 250, interpolate2 = interpolateZoom, listeners = dispatch("start", "zoom", "end"), touchstarting, touchfirst, touchending, touchDelay = 500, wheelDelay = 150, clickDistance2 = 0, tapDistance = 10;
  function zoom2(selection2) {
    selection2.property("__zoom", defaultTransform).on("wheel.zoom", wheeled, { passive: false }).on("mousedown.zoom", mousedowned).on("dblclick.zoom", dblclicked).filter(touchable).on("touchstart.zoom", touchstarted).on("touchmove.zoom", touchmoved).on("touchend.zoom touchcancel.zoom", touchended).style("-webkit-tap-highlight-color", "rgba(0,0,0,0)");
  }
  zoom2.transform = function(collection, transform, point, event) {
    var selection2 = collection.selection ? collection.selection() : collection;
    selection2.property("__zoom", defaultTransform);
    if (collection !== selection2) {
      schedule2(collection, transform, point, event);
    } else {
      selection2.interrupt().each(function() {
        gesture(this, arguments).event(event).start().zoom(null, typeof transform === "function" ? transform.apply(this, arguments) : transform).end();
      });
    }
  };
  zoom2.scaleBy = function(selection2, k, p, event) {
    zoom2.scaleTo(selection2, function() {
      var k0 = this.__zoom.k, k1 = typeof k === "function" ? k.apply(this, arguments) : k;
      return k0 * k1;
    }, p, event);
  };
  zoom2.scaleTo = function(selection2, k, p, event) {
    zoom2.transform(selection2, function() {
      var e = extent.apply(this, arguments), t0 = this.__zoom, p0 = p == null ? centroid(e) : typeof p === "function" ? p.apply(this, arguments) : p, p1 = t0.invert(p0), k1 = typeof k === "function" ? k.apply(this, arguments) : k;
      return constrain(translate(scale(t0, k1), p0, p1), e, translateExtent);
    }, p, event);
  };
  zoom2.translateBy = function(selection2, x, y, event) {
    zoom2.transform(selection2, function() {
      return constrain(this.__zoom.translate(
        typeof x === "function" ? x.apply(this, arguments) : x,
        typeof y === "function" ? y.apply(this, arguments) : y
      ), extent.apply(this, arguments), translateExtent);
    }, null, event);
  };
  zoom2.translateTo = function(selection2, x, y, p, event) {
    zoom2.transform(selection2, function() {
      var e = extent.apply(this, arguments), t = this.__zoom, p0 = p == null ? centroid(e) : typeof p === "function" ? p.apply(this, arguments) : p;
      return constrain(identity.translate(p0[0], p0[1]).scale(t.k).translate(
        typeof x === "function" ? -x.apply(this, arguments) : -x,
        typeof y === "function" ? -y.apply(this, arguments) : -y
      ), e, translateExtent);
    }, p, event);
  };
  function scale(transform, k) {
    k = Math.max(scaleExtent[0], Math.min(scaleExtent[1], k));
    return k === transform.k ? transform : new Transform(k, transform.x, transform.y);
  }
  function translate(transform, p0, p1) {
    var x = p0[0] - p1[0] * transform.k, y = p0[1] - p1[1] * transform.k;
    return x === transform.x && y === transform.y ? transform : new Transform(transform.k, x, y);
  }
  function centroid(extent2) {
    return [(+extent2[0][0] + +extent2[1][0]) / 2, (+extent2[0][1] + +extent2[1][1]) / 2];
  }
  function schedule2(transition, transform, point, event) {
    transition.on("start.zoom", function() {
      gesture(this, arguments).event(event).start();
    }).on("interrupt.zoom end.zoom", function() {
      gesture(this, arguments).event(event).end();
    }).tween("zoom", function() {
      var that = this, args = arguments, g = gesture(that, args).event(event), e = extent.apply(that, args), p = point == null ? centroid(e) : typeof point === "function" ? point.apply(that, args) : point, w = Math.max(e[1][0] - e[0][0], e[1][1] - e[0][1]), a = that.__zoom, b = typeof transform === "function" ? transform.apply(that, args) : transform, i = interpolate2(a.invert(p).concat(w / a.k), b.invert(p).concat(w / b.k));
      return function(t) {
        if (t === 1)
          t = b;
        else {
          var l = i(t), k = w / l[2];
          t = new Transform(k, p[0] - l[0] * k, p[1] - l[1] * k);
        }
        g.zoom(null, t);
      };
    });
  }
  function gesture(that, args, clean) {
    return !clean && that.__zooming || new Gesture(that, args);
  }
  function Gesture(that, args) {
    this.that = that;
    this.args = args;
    this.active = 0;
    this.sourceEvent = null;
    this.extent = extent.apply(that, args);
    this.taps = 0;
  }
  Gesture.prototype = {
    event: function(event) {
      if (event)
        this.sourceEvent = event;
      return this;
    },
    start: function() {
      if (++this.active === 1) {
        this.that.__zooming = this;
        this.emit("start");
      }
      return this;
    },
    zoom: function(key, transform) {
      if (this.mouse && key !== "mouse")
        this.mouse[1] = transform.invert(this.mouse[0]);
      if (this.touch0 && key !== "touch")
        this.touch0[1] = transform.invert(this.touch0[0]);
      if (this.touch1 && key !== "touch")
        this.touch1[1] = transform.invert(this.touch1[0]);
      this.that.__zoom = transform;
      this.emit("zoom");
      return this;
    },
    end: function() {
      if (--this.active === 0) {
        delete this.that.__zooming;
        this.emit("end");
      }
      return this;
    },
    emit: function(type) {
      var d = select(this.that).datum();
      listeners.call(
        type,
        this.that,
        new ZoomEvent(type, {
          sourceEvent: this.sourceEvent,
          target: zoom2,
          type,
          transform: this.that.__zoom,
          dispatch: listeners
        }),
        d
      );
    }
  };
  function wheeled(event, ...args) {
    if (!filter2.apply(this, arguments))
      return;
    var g = gesture(this, args).event(event), t = this.__zoom, k = Math.max(scaleExtent[0], Math.min(scaleExtent[1], t.k * Math.pow(2, wheelDelta.apply(this, arguments)))), p = pointer(event);
    if (g.wheel) {
      if (g.mouse[0][0] !== p[0] || g.mouse[0][1] !== p[1]) {
        g.mouse[1] = t.invert(g.mouse[0] = p);
      }
      clearTimeout(g.wheel);
    } else if (t.k === k)
      return;
    else {
      g.mouse = [p, t.invert(p)];
      interrupt(this);
      g.start();
    }
    noevent(event);
    g.wheel = setTimeout(wheelidled, wheelDelay);
    g.zoom("mouse", constrain(translate(scale(t, k), g.mouse[0], g.mouse[1]), g.extent, translateExtent));
    function wheelidled() {
      g.wheel = null;
      g.end();
    }
  }
  function mousedowned(event, ...args) {
    if (touchending || !filter2.apply(this, arguments))
      return;
    var currentTarget = event.currentTarget, g = gesture(this, args, true).event(event), v = select(event.view).on("mousemove.zoom", mousemoved, true).on("mouseup.zoom", mouseupped, true), p = pointer(event, currentTarget), x0 = event.clientX, y0 = event.clientY;
    dragDisable(event.view);
    nopropagation(event);
    g.mouse = [p, this.__zoom.invert(p)];
    interrupt(this);
    g.start();
    function mousemoved(event2) {
      noevent(event2);
      if (!g.moved) {
        var dx = event2.clientX - x0, dy = event2.clientY - y0;
        g.moved = dx * dx + dy * dy > clickDistance2;
      }
      g.event(event2).zoom("mouse", constrain(translate(g.that.__zoom, g.mouse[0] = pointer(event2, currentTarget), g.mouse[1]), g.extent, translateExtent));
    }
    function mouseupped(event2) {
      v.on("mousemove.zoom mouseup.zoom", null);
      yesdrag(event2.view, g.moved);
      noevent(event2);
      g.event(event2).end();
    }
  }
  function dblclicked(event, ...args) {
    if (!filter2.apply(this, arguments))
      return;
    var t0 = this.__zoom, p0 = pointer(event.changedTouches ? event.changedTouches[0] : event, this), p1 = t0.invert(p0), k1 = t0.k * (event.shiftKey ? 0.5 : 2), t1 = constrain(translate(scale(t0, k1), p0, p1), extent.apply(this, args), translateExtent);
    noevent(event);
    if (duration > 0)
      select(this).transition().duration(duration).call(schedule2, t1, p0, event);
    else
      select(this).call(zoom2.transform, t1, p0, event);
  }
  function touchstarted(event, ...args) {
    if (!filter2.apply(this, arguments))
      return;
    var touches = event.touches, n = touches.length, g = gesture(this, args, event.changedTouches.length === n).event(event), started, i, t, p;
    nopropagation(event);
    for (i = 0; i < n; ++i) {
      t = touches[i], p = pointer(t, this);
      p = [p, this.__zoom.invert(p), t.identifier];
      if (!g.touch0)
        g.touch0 = p, started = true, g.taps = 1 + !!touchstarting;
      else if (!g.touch1 && g.touch0[2] !== p[2])
        g.touch1 = p, g.taps = 0;
    }
    if (touchstarting)
      touchstarting = clearTimeout(touchstarting);
    if (started) {
      if (g.taps < 2)
        touchfirst = p[0], touchstarting = setTimeout(function() {
          touchstarting = null;
        }, touchDelay);
      interrupt(this);
      g.start();
    }
  }
  function touchmoved(event, ...args) {
    if (!this.__zooming)
      return;
    var g = gesture(this, args).event(event), touches = event.changedTouches, n = touches.length, i, t, p, l;
    noevent(event);
    for (i = 0; i < n; ++i) {
      t = touches[i], p = pointer(t, this);
      if (g.touch0 && g.touch0[2] === t.identifier)
        g.touch0[0] = p;
      else if (g.touch1 && g.touch1[2] === t.identifier)
        g.touch1[0] = p;
    }
    t = g.that.__zoom;
    if (g.touch1) {
      var p0 = g.touch0[0], l0 = g.touch0[1], p1 = g.touch1[0], l1 = g.touch1[1], dp = (dp = p1[0] - p0[0]) * dp + (dp = p1[1] - p0[1]) * dp, dl = (dl = l1[0] - l0[0]) * dl + (dl = l1[1] - l0[1]) * dl;
      t = scale(t, Math.sqrt(dp / dl));
      p = [(p0[0] + p1[0]) / 2, (p0[1] + p1[1]) / 2];
      l = [(l0[0] + l1[0]) / 2, (l0[1] + l1[1]) / 2];
    } else if (g.touch0)
      p = g.touch0[0], l = g.touch0[1];
    else
      return;
    g.zoom("touch", constrain(translate(t, p, l), g.extent, translateExtent));
  }
  function touchended(event, ...args) {
    if (!this.__zooming)
      return;
    var g = gesture(this, args).event(event), touches = event.changedTouches, n = touches.length, i, t;
    nopropagation(event);
    if (touchending)
      clearTimeout(touchending);
    touchending = setTimeout(function() {
      touchending = null;
    }, touchDelay);
    for (i = 0; i < n; ++i) {
      t = touches[i];
      if (g.touch0 && g.touch0[2] === t.identifier)
        delete g.touch0;
      else if (g.touch1 && g.touch1[2] === t.identifier)
        delete g.touch1;
    }
    if (g.touch1 && !g.touch0)
      g.touch0 = g.touch1, delete g.touch1;
    if (g.touch0)
      g.touch0[1] = this.__zoom.invert(g.touch0[0]);
    else {
      g.end();
      if (g.taps === 2) {
        t = pointer(t, this);
        if (Math.hypot(touchfirst[0] - t[0], touchfirst[1] - t[1]) < tapDistance) {
          var p = select(this).on("dblclick.zoom");
          if (p)
            p.apply(this, arguments);
        }
      }
    }
  }
  zoom2.wheelDelta = function(_) {
    return arguments.length ? (wheelDelta = typeof _ === "function" ? _ : constant(+_), zoom2) : wheelDelta;
  };
  zoom2.filter = function(_) {
    return arguments.length ? (filter2 = typeof _ === "function" ? _ : constant(!!_), zoom2) : filter2;
  };
  zoom2.touchable = function(_) {
    return arguments.length ? (touchable = typeof _ === "function" ? _ : constant(!!_), zoom2) : touchable;
  };
  zoom2.extent = function(_) {
    return arguments.length ? (extent = typeof _ === "function" ? _ : constant([[+_[0][0], +_[0][1]], [+_[1][0], +_[1][1]]]), zoom2) : extent;
  };
  zoom2.scaleExtent = function(_) {
    return arguments.length ? (scaleExtent[0] = +_[0], scaleExtent[1] = +_[1], zoom2) : [scaleExtent[0], scaleExtent[1]];
  };
  zoom2.translateExtent = function(_) {
    return arguments.length ? (translateExtent[0][0] = +_[0][0], translateExtent[1][0] = +_[1][0], translateExtent[0][1] = +_[0][1], translateExtent[1][1] = +_[1][1], zoom2) : [[translateExtent[0][0], translateExtent[0][1]], [translateExtent[1][0], translateExtent[1][1]]];
  };
  zoom2.constrain = function(_) {
    return arguments.length ? (constrain = _, zoom2) : constrain;
  };
  zoom2.duration = function(_) {
    return arguments.length ? (duration = +_, zoom2) : duration;
  };
  zoom2.interpolate = function(_) {
    return arguments.length ? (interpolate2 = _, zoom2) : interpolate2;
  };
  zoom2.on = function() {
    var value = listeners.on.apply(listeners, arguments);
    return value === listeners ? zoom2 : value;
  };
  zoom2.clickDistance = function(_) {
    return arguments.length ? (clickDistance2 = (_ = +_) * _, zoom2) : Math.sqrt(clickDistance2);
  };
  zoom2.tapDistance = function(_) {
    return arguments.length ? (tapDistance = +_, zoom2) : tapDistance;
  };
  return zoom2;
}
var Position = /* @__PURE__ */ ((Position2) => {
  Position2["Left"] = "left";
  Position2["Top"] = "top";
  Position2["Right"] = "right";
  Position2["Bottom"] = "bottom";
  return Position2;
})(Position || {});
var SelectionMode = /* @__PURE__ */ ((SelectionMode2) => {
  SelectionMode2["Partial"] = "partial";
  SelectionMode2["Full"] = "full";
  return SelectionMode2;
})(SelectionMode || {});
var ConnectionLineType = /* @__PURE__ */ ((ConnectionLineType2) => {
  ConnectionLineType2["Bezier"] = "default";
  ConnectionLineType2["SimpleBezier"] = "simple-bezier";
  ConnectionLineType2["Straight"] = "straight";
  ConnectionLineType2["Step"] = "step";
  ConnectionLineType2["SmoothStep"] = "smoothstep";
  return ConnectionLineType2;
})(ConnectionLineType || {});
var ConnectionMode = /* @__PURE__ */ ((ConnectionMode2) => {
  ConnectionMode2["Strict"] = "strict";
  ConnectionMode2["Loose"] = "loose";
  return ConnectionMode2;
})(ConnectionMode || {});
var MarkerType = /* @__PURE__ */ ((MarkerType2) => {
  MarkerType2["Arrow"] = "arrow";
  MarkerType2["ArrowClosed"] = "arrowclosed";
  return MarkerType2;
})(MarkerType || {});
var PanOnScrollMode = /* @__PURE__ */ ((PanOnScrollMode2) => {
  PanOnScrollMode2["Free"] = "free";
  PanOnScrollMode2["Vertical"] = "vertical";
  PanOnScrollMode2["Horizontal"] = "horizontal";
  return PanOnScrollMode2;
})(PanOnScrollMode || {});
var PanelPosition = /* @__PURE__ */ ((PanelPosition2) => {
  PanelPosition2["TopLeft"] = "top-left";
  PanelPosition2["TopCenter"] = "top-center";
  PanelPosition2["TopRight"] = "top-right";
  PanelPosition2["BottomLeft"] = "bottom-left";
  PanelPosition2["BottomCenter"] = "bottom-center";
  PanelPosition2["BottomRight"] = "bottom-right";
  return PanelPosition2;
})(PanelPosition || {});
const inputTags = ["INPUT", "SELECT", "TEXTAREA"];
const defaultDoc = typeof document !== "undefined" ? document : null;
function isInputDOMNode(event) {
  var _a, _b;
  const target = ((_b = (_a = event.composedPath) == null ? void 0 : _a.call(event)) == null ? void 0 : _b[0]) || event.target;
  const hasAttribute = typeof (target == null ? void 0 : target.hasAttribute) === "function" ? target.hasAttribute("contenteditable") : false;
  const closest = typeof (target == null ? void 0 : target.closest) === "function" ? target.closest(".nokey") : null;
  return inputTags.includes(target == null ? void 0 : target.nodeName) || hasAttribute || !!closest;
}
function wasModifierPressed(event) {
  return event.ctrlKey || event.metaKey || event.shiftKey || event.altKey;
}
function isKeyMatch(pressedKey, keyToMatch, pressedKeys, isKeyUp) {
  const keyCombination = keyToMatch.replace("+", "\n").replace("\n\n", "\n+").split("\n").map((k) => k.trim().toLowerCase());
  if (keyCombination.length === 1) {
    return pressedKey.toLowerCase() === keyToMatch.toLowerCase();
  }
  if (!isKeyUp) {
    pressedKeys.add(pressedKey.toLowerCase());
  }
  const isMatch = keyCombination.every(
    (key, index) => pressedKeys.has(key) && Array.from(pressedKeys.values())[index] === keyCombination[index]
  );
  if (isKeyUp) {
    pressedKeys.delete(pressedKey.toLowerCase());
  }
  return isMatch;
}
function createKeyPredicate(keyFilter, pressedKeys) {
  return (event) => {
    if (!event.code && !event.key) {
      return false;
    }
    const keyOrCode = useKeyOrCode(event.code, keyFilter);
    if (Array.isArray(keyFilter)) {
      return keyFilter.some((key) => isKeyMatch(event[keyOrCode], key, pressedKeys, event.type === "keyup"));
    }
    return isKeyMatch(event[keyOrCode], keyFilter, pressedKeys, event.type === "keyup");
  };
}
function useKeyOrCode(code, keysToWatch) {
  return keysToWatch.includes(code) ? "code" : "key";
}
function useKeyPress(keyFilter, options) {
  const target = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(options == null ? void 0 : options.target) ?? defaultDoc);
  const isPressed = (0,vue__WEBPACK_IMPORTED_MODULE_0__.shallowRef)((0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(keyFilter) === true);
  let modifierPressed = false;
  const pressedKeys = /* @__PURE__ */ new Set();
  let currentFilter = createKeyFilterFn((0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(keyFilter));
  (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
    () => (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(keyFilter),
    (nextKeyFilter, previousKeyFilter) => {
      if (typeof previousKeyFilter === "boolean" && typeof nextKeyFilter !== "boolean") {
        reset();
      }
      currentFilter = createKeyFilterFn(nextKeyFilter);
    },
    {
      immediate: true
    }
  );
  useEventListener(["blur", "contextmenu"], reset);
  onKeyStroke(
    (...args) => currentFilter(...args),
    (e) => {
      var _a, _b;
      const actInsideInputWithModifier = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(options == null ? void 0 : options.actInsideInputWithModifier) ?? true;
      const preventDefault = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(options == null ? void 0 : options.preventDefault) ?? false;
      modifierPressed = wasModifierPressed(e);
      const preventAction = (!modifierPressed || modifierPressed && !actInsideInputWithModifier) && isInputDOMNode(e);
      if (preventAction) {
        return;
      }
      const target2 = ((_b = (_a = e.composedPath) == null ? void 0 : _a.call(e)) == null ? void 0 : _b[0]) || e.target;
      const isInteractiveElement = (target2 == null ? void 0 : target2.nodeName) === "BUTTON" || (target2 == null ? void 0 : target2.nodeName) === "A";
      if (!preventDefault && (modifierPressed || !isInteractiveElement)) {
        e.preventDefault();
      }
      isPressed.value = true;
    },
    { eventName: "keydown", target }
  );
  onKeyStroke(
    (...args) => currentFilter(...args),
    (e) => {
      const actInsideInputWithModifier = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(options == null ? void 0 : options.actInsideInputWithModifier) ?? true;
      if (isPressed.value) {
        const preventAction = (!modifierPressed || modifierPressed && !actInsideInputWithModifier) && isInputDOMNode(e);
        if (preventAction) {
          return;
        }
        modifierPressed = false;
        isPressed.value = false;
      }
    },
    { eventName: "keyup", target }
  );
  function reset() {
    modifierPressed = false;
    pressedKeys.clear();
    isPressed.value = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(keyFilter) === true;
  }
  function createKeyFilterFn(keyFilter2) {
    if (keyFilter2 === null) {
      reset();
      return () => false;
    }
    if (typeof keyFilter2 === "boolean") {
      reset();
      isPressed.value = keyFilter2;
      return () => false;
    }
    if (Array.isArray(keyFilter2) || typeof keyFilter2 === "string") {
      return createKeyPredicate(keyFilter2, pressedKeys);
    }
    return keyFilter2;
  }
  return isPressed;
}
const ARIA_NODE_DESC_KEY = "vue-flow__node-desc";
const ARIA_EDGE_DESC_KEY = "vue-flow__edge-desc";
const ARIA_LIVE_MESSAGE = "vue-flow__aria-live";
const elementSelectionKeys = ["Enter", " ", "Escape"];
const arrowKeyDiffs = {
  ArrowUp: { x: 0, y: -1 },
  ArrowDown: { x: 0, y: 1 },
  ArrowLeft: { x: -1, y: 0 },
  ArrowRight: { x: 1, y: 0 }
};
function nodeToRect(node) {
  return {
    ...node.computedPosition || { x: 0, y: 0 },
    width: node.dimensions.width || 0,
    height: node.dimensions.height || 0
  };
}
function getOverlappingArea(rectA, rectB) {
  const xOverlap = Math.max(0, Math.min(rectA.x + rectA.width, rectB.x + rectB.width) - Math.max(rectA.x, rectB.x));
  const yOverlap = Math.max(0, Math.min(rectA.y + rectA.height, rectB.y + rectB.height) - Math.max(rectA.y, rectB.y));
  return Math.ceil(xOverlap * yOverlap);
}
function getDimensions(node) {
  return {
    width: node.offsetWidth,
    height: node.offsetHeight
  };
}
function clamp(val, min = 0, max = 1) {
  return Math.min(Math.max(val, min), max);
}
function clampPosition(position, extent) {
  return {
    x: clamp(position.x, extent[0][0], extent[1][0]),
    y: clamp(position.y, extent[0][1], extent[1][1])
  };
}
function getHostForElement(element) {
  const doc = element.getRootNode();
  if ("elementFromPoint" in doc) {
    return doc;
  }
  return window.document;
}
function isEdge(element) {
  return element && typeof element === "object" && "id" in element && "source" in element && "target" in element;
}
function isGraphEdge(element) {
  return isEdge(element) && "sourceNode" in element && "targetNode" in element;
}
function isNode(element) {
  return element && typeof element === "object" && "id" in element && "position" in element && !isEdge(element);
}
function isGraphNode(element) {
  return isNode(element) && "computedPosition" in element;
}
function isNumeric(n) {
  return !Number.isNaN(n) && Number.isFinite(n);
}
function isRect(obj) {
  return isNumeric(obj.width) && isNumeric(obj.height) && isNumeric(obj.x) && isNumeric(obj.y);
}
function parseNode(node, existingNode, parentNode) {
  const initialState = {
    id: node.id.toString(),
    type: node.type ?? "default",
    dimensions: (0,vue__WEBPACK_IMPORTED_MODULE_0__.markRaw)({
      width: 0,
      height: 0
    }),
    computedPosition: (0,vue__WEBPACK_IMPORTED_MODULE_0__.markRaw)({
      z: 0,
      ...node.position
    }),
    // todo: shouldn't be defined initially, as we want to use handleBounds to check if a node was actually initialized or not
    handleBounds: {
      source: [],
      target: []
    },
    draggable: void 0,
    selectable: void 0,
    connectable: void 0,
    focusable: void 0,
    selected: false,
    dragging: false,
    resizing: false,
    initialized: false,
    isParent: false,
    position: {
      x: 0,
      y: 0
    },
    data: isDef(node.data) ? node.data : {},
    events: (0,vue__WEBPACK_IMPORTED_MODULE_0__.markRaw)(isDef(node.events) ? node.events : {})
  };
  return Object.assign(existingNode ?? initialState, node, { id: node.id.toString(), parentNode });
}
function parseEdge(edge, existingEdge, defaultEdgeOptions) {
  var _a, _b;
  const initialState = {
    id: edge.id.toString(),
    type: edge.type ?? (existingEdge == null ? void 0 : existingEdge.type) ?? "default",
    source: edge.source.toString(),
    target: edge.target.toString(),
    sourceHandle: (_a = edge.sourceHandle) == null ? void 0 : _a.toString(),
    targetHandle: (_b = edge.targetHandle) == null ? void 0 : _b.toString(),
    updatable: edge.updatable ?? (defaultEdgeOptions == null ? void 0 : defaultEdgeOptions.updatable),
    selectable: edge.selectable ?? (defaultEdgeOptions == null ? void 0 : defaultEdgeOptions.selectable),
    focusable: edge.focusable ?? (defaultEdgeOptions == null ? void 0 : defaultEdgeOptions.focusable),
    data: isDef(edge.data) ? edge.data : {},
    events: (0,vue__WEBPACK_IMPORTED_MODULE_0__.markRaw)(isDef(edge.events) ? edge.events : {}),
    label: edge.label ?? "",
    interactionWidth: edge.interactionWidth ?? (defaultEdgeOptions == null ? void 0 : defaultEdgeOptions.interactionWidth),
    ...defaultEdgeOptions ?? {}
  };
  return Object.assign(existingEdge ?? initialState, edge, { id: edge.id.toString() });
}
function getConnectedElements(nodeOrId, nodes, edges, dir) {
  const id2 = typeof nodeOrId === "string" ? nodeOrId : nodeOrId.id;
  const connectedIds = /* @__PURE__ */ new Set();
  const origin = dir === "source" ? "target" : "source";
  for (const edge of edges) {
    if (edge[origin] === id2) {
      connectedIds.add(edge[dir]);
    }
  }
  return nodes.filter((n) => connectedIds.has(n.id));
}
function getOutgoers(...args) {
  if (args.length === 3) {
    const [nodeOrId2, nodes, edges] = args;
    return getConnectedElements(nodeOrId2, nodes, edges, "target");
  }
  const [nodeOrId, elements] = args;
  const nodeId = typeof nodeOrId === "string" ? nodeOrId : nodeOrId.id;
  const outgoers = elements.filter((el) => isEdge(el) && el.source === nodeId);
  return outgoers.map((edge) => elements.find((el) => isNode(el) && el.id === edge.target));
}
function getIncomers(...args) {
  if (args.length === 3) {
    const [nodeOrId2, nodes, edges] = args;
    return getConnectedElements(nodeOrId2, nodes, edges, "source");
  }
  const [nodeOrId, elements] = args;
  const nodeId = typeof nodeOrId === "string" ? nodeOrId : nodeOrId.id;
  const incomers = elements.filter((el) => isEdge(el) && el.target === nodeId);
  return incomers.map((edge) => elements.find((el) => isNode(el) && el.id === edge.source));
}
function getEdgeId({ source, sourceHandle, target, targetHandle }) {
  return `vueflow__edge-${source}${sourceHandle ?? ""}-${target}${targetHandle ?? ""}`;
}
function connectionExists(edge, elements) {
  return elements.some(
    (el) => isEdge(el) && el.source === edge.source && el.target === edge.target && (el.sourceHandle === edge.sourceHandle || !el.sourceHandle && !edge.sourceHandle) && (el.targetHandle === edge.targetHandle || !el.targetHandle && !edge.targetHandle)
  );
}
function addEdge(edgeParams, elements, defaults) {
  if (!edgeParams.source || !edgeParams.target) {
    warn("Can't create edge. An edge needs a source and a target.");
    return elements;
  }
  let edge;
  if (isEdge(edgeParams)) {
    edge = { ...edgeParams };
  } else {
    edge = {
      ...edgeParams,
      id: getEdgeId(edgeParams)
    };
  }
  edge = parseEdge(edge, void 0, defaults);
  if (connectionExists(edge, elements)) {
    return elements;
  }
  elements.push(edge);
  return elements;
}
function updateEdge(oldEdge, newConnection, elements) {
  if (!newConnection.source || !newConnection.target) {
    warn("Can't create new edge. An edge needs a source and a target.");
    return elements;
  }
  const foundEdge = elements.find((e) => isEdge(e) && e.id === oldEdge.id);
  if (!foundEdge) {
    warn(`The old edge with id=${oldEdge.id} does not exist.`);
    return elements;
  }
  const edge = {
    ...oldEdge,
    id: getEdgeId(newConnection),
    source: newConnection.source,
    target: newConnection.target,
    sourceHandle: newConnection.sourceHandle,
    targetHandle: newConnection.targetHandle
  };
  elements.splice(elements.indexOf(foundEdge), 1, edge);
  return elements.filter((e) => e.id !== oldEdge.id);
}
function rendererPointToPoint({ x, y }, { x: tx, y: ty, zoom: tScale }) {
  return {
    x: x * tScale + tx,
    y: y * tScale + ty
  };
}
function pointToRendererPoint({ x, y }, { x: tx, y: ty, zoom: tScale }, snapToGrid = false, snapGrid = [1, 1]) {
  const position = {
    x: (x - tx) / tScale,
    y: (y - ty) / tScale
  };
  return snapToGrid ? snapPosition(position, snapGrid) : position;
}
function getBoundsOfBoxes(box1, box2) {
  return {
    x: Math.min(box1.x, box2.x),
    y: Math.min(box1.y, box2.y),
    x2: Math.max(box1.x2, box2.x2),
    y2: Math.max(box1.y2, box2.y2)
  };
}
function rectToBox({ x, y, width, height }) {
  return {
    x,
    y,
    x2: x + width,
    y2: y + height
  };
}
function boxToRect({ x, y, x2, y2 }) {
  return {
    x,
    y,
    width: x2 - x,
    height: y2 - y
  };
}
function getBoundsofRects(rect1, rect2) {
  return boxToRect(getBoundsOfBoxes(rectToBox(rect1), rectToBox(rect2)));
}
function getRectOfNodes(nodes) {
  let box = {
    x: Number.POSITIVE_INFINITY,
    y: Number.POSITIVE_INFINITY,
    x2: Number.NEGATIVE_INFINITY,
    y2: Number.NEGATIVE_INFINITY
  };
  for (let i = 0; i < nodes.length; i++) {
    const node = nodes[i];
    box = getBoundsOfBoxes(
      box,
      rectToBox({
        ...node.computedPosition,
        ...node.dimensions
      })
    );
  }
  return boxToRect(box);
}
function getNodesInside(nodes, rect, viewport = { x: 0, y: 0, zoom: 1 }, partially = false, excludeNonSelectableNodes = false) {
  const paneRect = {
    ...pointToRendererPoint(rect, viewport),
    width: rect.width / viewport.zoom,
    height: rect.height / viewport.zoom
  };
  const visibleNodes = [];
  for (const node of nodes) {
    const { dimensions, selectable = true, hidden = false } = node;
    const width = dimensions.width ?? node.width ?? null;
    const height = dimensions.height ?? node.height ?? null;
    if (excludeNonSelectableNodes && !selectable || hidden) {
      continue;
    }
    const overlappingArea = getOverlappingArea(paneRect, nodeToRect(node));
    const notInitialized = width === null || height === null;
    const partiallyVisible = partially && overlappingArea > 0;
    const area = (width ?? 0) * (height ?? 0);
    const isVisible = notInitialized || partiallyVisible || overlappingArea >= area;
    if (isVisible || node.dragging) {
      visibleNodes.push(node);
    }
  }
  return visibleNodes;
}
function getConnectedEdges(nodesOrId, edges) {
  const nodeIds = /* @__PURE__ */ new Set();
  if (typeof nodesOrId === "string") {
    nodeIds.add(nodesOrId);
  } else if (nodesOrId.length >= 1) {
    for (const n of nodesOrId) {
      nodeIds.add(n.id);
    }
  }
  return edges.filter((edge) => nodeIds.has(edge.source) || nodeIds.has(edge.target));
}
function getTransformForBounds(bounds, width, height, minZoom, maxZoom, padding = 0.1, offset = { x: 0, y: 0 }) {
  const xZoom = width / (bounds.width * (1 + padding));
  const yZoom = height / (bounds.height * (1 + padding));
  const zoom2 = Math.min(xZoom, yZoom);
  const clampedZoom = clamp(zoom2, minZoom, maxZoom);
  const boundsCenterX = bounds.x + bounds.width / 2;
  const boundsCenterY = bounds.y + bounds.height / 2;
  const x = width / 2 - boundsCenterX * clampedZoom + (offset.x ?? 0);
  const y = height / 2 - boundsCenterY * clampedZoom + (offset.y ?? 0);
  return { x, y, zoom: clampedZoom };
}
function getXYZPos(parentPos, computedPosition) {
  return {
    x: computedPosition.x + parentPos.x,
    y: computedPosition.y + parentPos.y,
    z: (parentPos.z > computedPosition.z ? parentPos.z : computedPosition.z) + 1
  };
}
function isParentSelected(node, findNode) {
  if (!node.parentNode) {
    return false;
  }
  const parent = findNode(node.parentNode);
  if (!parent) {
    return false;
  }
  if (parent.selected) {
    return true;
  }
  return isParentSelected(parent, findNode);
}
function getMarkerId(marker, vueFlowId) {
  if (typeof marker === "undefined") {
    return "";
  }
  if (typeof marker === "string") {
    return marker;
  }
  const idPrefix = vueFlowId ? `${vueFlowId}__` : "";
  return `${idPrefix}${Object.keys(marker).sort().map((key) => `${key}=${marker[key]}`).join("&")}`;
}
function calcAutoPanVelocity(value, min, max) {
  if (value < min) {
    return clamp(Math.abs(value - min), 1, min) / min;
  }
  if (value > max) {
    return -clamp(Math.abs(value - max), 1, min) / min;
  }
  return 0;
}
function calcAutoPan(pos, bounds, speed = 15, distance2 = 40) {
  const xMovement = calcAutoPanVelocity(pos.x, distance2, bounds.width - distance2) * speed;
  const yMovement = calcAutoPanVelocity(pos.y, distance2, bounds.height - distance2) * speed;
  return [xMovement, yMovement];
}
function handleParentExpand(updateItem, parent) {
  if (parent) {
    const extendWidth = updateItem.position.x + updateItem.dimensions.width - parent.dimensions.width;
    const extendHeight = updateItem.position.y + updateItem.dimensions.height - parent.dimensions.height;
    if (extendWidth > 0 || extendHeight > 0 || updateItem.position.x < 0 || updateItem.position.y < 0) {
      let parentStyles = {};
      if (typeof parent.style === "function") {
        parentStyles = { ...parent.style(parent) };
      } else if (parent.style) {
        parentStyles = { ...parent.style };
      }
      parentStyles.width = parentStyles.width ?? `${parent.dimensions.width}px`;
      parentStyles.height = parentStyles.height ?? `${parent.dimensions.height}px`;
      if (extendWidth > 0) {
        if (typeof parentStyles.width === "string") {
          const currWidth = Number(parentStyles.width.replace("px", ""));
          parentStyles.width = `${currWidth + extendWidth}px`;
        } else {
          parentStyles.width += extendWidth;
        }
      }
      if (extendHeight > 0) {
        if (typeof parentStyles.height === "string") {
          const currWidth = Number(parentStyles.height.replace("px", ""));
          parentStyles.height = `${currWidth + extendHeight}px`;
        } else {
          parentStyles.height += extendHeight;
        }
      }
      if (updateItem.position.x < 0) {
        const xDiff = Math.abs(updateItem.position.x);
        parent.position.x = parent.position.x - xDiff;
        if (typeof parentStyles.width === "string") {
          const currWidth = Number(parentStyles.width.replace("px", ""));
          parentStyles.width = `${currWidth + xDiff}px`;
        } else {
          parentStyles.width += xDiff;
        }
        updateItem.position.x = 0;
      }
      if (updateItem.position.y < 0) {
        const yDiff = Math.abs(updateItem.position.y);
        parent.position.y = parent.position.y - yDiff;
        if (typeof parentStyles.height === "string") {
          const currWidth = Number(parentStyles.height.replace("px", ""));
          parentStyles.height = `${currWidth + yDiff}px`;
        } else {
          parentStyles.height += yDiff;
        }
        updateItem.position.y = 0;
      }
      parent.dimensions.width = Number(parentStyles.width.toString().replace("px", ""));
      parent.dimensions.height = Number(parentStyles.height.toString().replace("px", ""));
      if (typeof parent.style === "function") {
        parent.style = (p) => {
          const styleFunc = parent.style;
          return {
            ...styleFunc(p),
            ...parentStyles
          };
        };
      } else {
        parent.style = {
          ...parent.style,
          ...parentStyles
        };
      }
    }
  }
}
function applyChanges(changes, elements) {
  var _a, _b;
  const addRemoveChanges = changes.filter((c) => c.type === "add" || c.type === "remove");
  for (const change of addRemoveChanges) {
    if (change.type === "add") {
      const index = elements.findIndex((el) => el.id === change.item.id);
      if (index === -1) {
        elements.push(change.item);
      }
    } else if (change.type === "remove") {
      const index = elements.findIndex((el) => el.id === change.id);
      if (index !== -1) {
        elements.splice(index, 1);
      }
    }
  }
  const elementIds = elements.map((el) => el.id);
  for (const element of elements) {
    for (const currentChange of changes) {
      if (currentChange.id !== element.id) {
        continue;
      }
      switch (currentChange.type) {
        case "select":
          element.selected = currentChange.selected;
          break;
        case "position":
          if (isGraphNode(element)) {
            if (typeof currentChange.position !== "undefined") {
              element.position = currentChange.position;
            }
            if (typeof currentChange.dragging !== "undefined") {
              element.dragging = currentChange.dragging;
            }
            if (element.expandParent && element.parentNode) {
              const parent = elements[elementIds.indexOf(element.parentNode)];
              if (parent && isGraphNode(parent)) {
                handleParentExpand(element, parent);
              }
            }
          }
          break;
        case "dimensions":
          if (isGraphNode(element)) {
            if (typeof currentChange.dimensions !== "undefined") {
              element.dimensions = currentChange.dimensions;
            }
            if (typeof currentChange.updateStyle !== "undefined" && currentChange.updateStyle) {
              element.style = {
                ...element.style || {},
                width: `${(_a = currentChange.dimensions) == null ? void 0 : _a.width}px`,
                height: `${(_b = currentChange.dimensions) == null ? void 0 : _b.height}px`
              };
            }
            if (typeof currentChange.resizing !== "undefined") {
              element.resizing = currentChange.resizing;
            }
            if (element.expandParent && element.parentNode) {
              const parent = elements[elementIds.indexOf(element.parentNode)];
              if (parent && isGraphNode(parent)) {
                const parentInit = !!parent.dimensions.width && !!parent.dimensions.height;
                if (!parentInit) {
                  (0,vue__WEBPACK_IMPORTED_MODULE_0__.nextTick)(() => {
                    handleParentExpand(element, parent);
                  });
                } else {
                  handleParentExpand(element, parent);
                }
              }
            }
          }
          break;
      }
    }
  }
  return elements;
}
function applyEdgeChanges(changes, edges) {
  return applyChanges(changes, edges);
}
function applyNodeChanges(changes, nodes) {
  return applyChanges(changes, nodes);
}
function createSelectionChange(id2, selected) {
  return {
    id: id2,
    type: "select",
    selected
  };
}
function createAdditionChange(item) {
  return {
    item,
    type: "add"
  };
}
function createNodeRemoveChange(id2) {
  return {
    id: id2,
    type: "remove"
  };
}
function createEdgeRemoveChange(id2, source, target, sourceHandle, targetHandle) {
  return {
    id: id2,
    source,
    target,
    sourceHandle: sourceHandle || null,
    targetHandle: targetHandle || null,
    type: "remove"
  };
}
function getSelectionChanges(items, selectedIds = /* @__PURE__ */ new Set(), mutateItem = false) {
  const changes = [];
  for (const [id2, item] of items) {
    const willBeSelected = selectedIds.has(id2);
    if (!(item.selected === void 0 && !willBeSelected) && item.selected !== willBeSelected) {
      if (mutateItem) {
        item.selected = willBeSelected;
      }
      changes.push(createSelectionChange(item.id, willBeSelected));
    }
  }
  return changes;
}
function createExtendedEventHook(defaultHandler) {
  const fns = /* @__PURE__ */ new Set();
  let hasDefaultHandler = false;
  const hasListeners = () => fns.size > 0;
  if (defaultHandler) {
    hasDefaultHandler = true;
    fns.add(defaultHandler);
  }
  const off = (fn) => {
    fns.delete(fn);
  };
  const on = (fn) => {
    if (defaultHandler && hasDefaultHandler) {
      fns.delete(defaultHandler);
    }
    fns.add(fn);
    const offFn = () => {
      off(fn);
      if (defaultHandler && hasDefaultHandler) {
        fns.add(defaultHandler);
      }
    };
    tryOnScopeDispose(offFn);
    return {
      off: offFn
    };
  };
  const trigger = (param) => {
    return Promise.all(Array.from(fns).map((fn) => fn(param)));
  };
  return {
    on,
    off,
    trigger,
    hasListeners,
    fns
  };
}
function hasSelector(target, selector2, node) {
  let current = target;
  do {
    if (current && current.matches(selector2)) {
      return true;
    } else if (current === node) {
      return false;
    }
    current = current.parentElement;
  } while (current);
  return false;
}
function getDragItems(nodes, nodesDraggable, mousePos, findNode, nodeId) {
  var _a, _b;
  const dragItems = [];
  for (const node of nodes) {
    if ((node.selected || node.id === nodeId) && (!node.parentNode || !isParentSelected(node, findNode)) && (node.draggable || nodesDraggable && typeof node.draggable === "undefined")) {
      dragItems.push(
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.markRaw)({
          id: node.id,
          position: node.position || { x: 0, y: 0 },
          distance: {
            x: mousePos.x - ((_a = node.computedPosition) == null ? void 0 : _a.x) || 0,
            y: mousePos.y - ((_b = node.computedPosition) == null ? void 0 : _b.y) || 0
          },
          from: node.computedPosition,
          extent: node.extent,
          parentNode: node.parentNode,
          dimensions: node.dimensions,
          expandParent: node.expandParent
        })
      );
    }
  }
  return dragItems;
}
function getEventHandlerParams({
  id: id2,
  dragItems,
  findNode
}) {
  const extendedDragItems = [];
  for (const dragItem of dragItems) {
    const node = findNode(dragItem.id);
    if (node) {
      extendedDragItems.push(node);
    }
  }
  return [id2 ? extendedDragItems.find((n) => n.id === id2) : extendedDragItems[0], extendedDragItems];
}
function getExtentPadding(padding) {
  if (Array.isArray(padding)) {
    switch (padding.length) {
      case 1:
        return [padding[0], padding[0], padding[0], padding[0]];
      case 2:
        return [padding[0], padding[1], padding[0], padding[1]];
      case 3:
        return [padding[0], padding[1], padding[2], padding[1]];
      case 4:
        return padding;
      default:
        return [0, 0, 0, 0];
    }
  }
  return [padding, padding, padding, padding];
}
function getParentExtent(currentExtent, node, parent) {
  const [top, right, bottom, left] = typeof currentExtent !== "string" ? getExtentPadding(currentExtent.padding) : [0, 0, 0, 0];
  if (parent && typeof parent.computedPosition.x !== "undefined" && typeof parent.computedPosition.y !== "undefined" && typeof parent.dimensions.width !== "undefined" && typeof parent.dimensions.height !== "undefined") {
    return [
      [parent.computedPosition.x + left, parent.computedPosition.y + top],
      [
        parent.computedPosition.x + parent.dimensions.width - right,
        parent.computedPosition.y + parent.dimensions.height - bottom
      ]
    ];
  }
  return false;
}
function getExtent(item, triggerError, extent, parent) {
  let currentExtent = item.extent || extent;
  if ((currentExtent === "parent" || !Array.isArray(currentExtent) && (currentExtent == null ? void 0 : currentExtent.range) === "parent") && !item.expandParent) {
    if (item.parentNode && parent && item.dimensions.width && item.dimensions.height) {
      const parentExtent = getParentExtent(currentExtent, item, parent);
      if (parentExtent) {
        currentExtent = parentExtent;
      }
    } else {
      triggerError(new VueFlowError(ErrorCode.NODE_EXTENT_INVALID, item.id));
      currentExtent = extent;
    }
  } else if (Array.isArray(currentExtent)) {
    const parentX = (parent == null ? void 0 : parent.computedPosition.x) || 0;
    const parentY = (parent == null ? void 0 : parent.computedPosition.y) || 0;
    currentExtent = [
      [currentExtent[0][0] + parentX, currentExtent[0][1] + parentY],
      [currentExtent[1][0] + parentX, currentExtent[1][1] + parentY]
    ];
  } else if (currentExtent !== "parent" && (currentExtent == null ? void 0 : currentExtent.range) && Array.isArray(currentExtent.range)) {
    const [top, right, bottom, left] = getExtentPadding(currentExtent.padding);
    const parentX = (parent == null ? void 0 : parent.computedPosition.x) || 0;
    const parentY = (parent == null ? void 0 : parent.computedPosition.y) || 0;
    currentExtent = [
      [currentExtent.range[0][0] + parentX + left, currentExtent.range[0][1] + parentY + top],
      [currentExtent.range[1][0] + parentX - right, currentExtent.range[1][1] + parentY - bottom]
    ];
  }
  return currentExtent === "parent" ? [
    [Number.NEGATIVE_INFINITY, Number.NEGATIVE_INFINITY],
    [Number.POSITIVE_INFINITY, Number.POSITIVE_INFINITY]
  ] : currentExtent;
}
function clampNodeExtent({ width, height }, extent) {
  return [extent[0], [extent[1][0] - (width || 0), extent[1][1] - (height || 0)]];
}
function calcNextPosition(node, nextPosition, triggerError, nodeExtent, parentNode) {
  const extent = clampNodeExtent(node.dimensions, getExtent(node, triggerError, nodeExtent, parentNode));
  const clampedPos = clampPosition(nextPosition, extent);
  return {
    position: {
      x: clampedPos.x - ((parentNode == null ? void 0 : parentNode.computedPosition.x) || 0),
      y: clampedPos.y - ((parentNode == null ? void 0 : parentNode.computedPosition.y) || 0)
    },
    computedPosition: clampedPos
  };
}
function getHandlePosition(node, handle, fallbackPosition = Position.Left, center = false) {
  const x = ((handle == null ? void 0 : handle.x) ?? 0) + node.computedPosition.x;
  const y = ((handle == null ? void 0 : handle.y) ?? 0) + node.computedPosition.y;
  const { width, height } = handle ?? getNodeDimensions(node);
  if (center) {
    return { x: x + width / 2, y: y + height / 2 };
  }
  const position = (handle == null ? void 0 : handle.position) ?? fallbackPosition;
  switch (position) {
    case Position.Top:
      return { x: x + width / 2, y };
    case Position.Right:
      return { x: x + width, y: y + height / 2 };
    case Position.Bottom:
      return { x: x + width / 2, y: y + height };
    case Position.Left:
      return { x, y: y + height / 2 };
  }
}
function getEdgeHandle(bounds, handleId) {
  if (!bounds) {
    return null;
  }
  return (!handleId ? bounds[0] : bounds.find((d) => d.id === handleId)) || null;
}
function isEdgeVisible({
  sourcePos,
  targetPos,
  sourceWidth,
  sourceHeight,
  targetWidth,
  targetHeight,
  width,
  height,
  viewport
}) {
  const edgeBox = {
    x: Math.min(sourcePos.x, targetPos.x),
    y: Math.min(sourcePos.y, targetPos.y),
    x2: Math.max(sourcePos.x + sourceWidth, targetPos.x + targetWidth),
    y2: Math.max(sourcePos.y + sourceHeight, targetPos.y + targetHeight)
  };
  if (edgeBox.x === edgeBox.x2) {
    edgeBox.x2 += 1;
  }
  if (edgeBox.y === edgeBox.y2) {
    edgeBox.y2 += 1;
  }
  const viewBox = rectToBox({
    x: (0 - viewport.x) / viewport.zoom,
    y: (0 - viewport.y) / viewport.zoom,
    width: width / viewport.zoom,
    height: height / viewport.zoom
  });
  const xOverlap = Math.max(0, Math.min(viewBox.x2, edgeBox.x2) - Math.max(viewBox.x, edgeBox.x));
  const yOverlap = Math.max(0, Math.min(viewBox.y2, edgeBox.y2) - Math.max(viewBox.y, edgeBox.y));
  const overlappingArea = Math.ceil(xOverlap * yOverlap);
  return overlappingArea > 0;
}
function getEdgeZIndex(edge, findNode, elevateEdgesOnSelect = false) {
  const hasZIndex = typeof edge.zIndex === "number";
  let z = hasZIndex ? edge.zIndex : 0;
  const source = findNode(edge.source);
  const target = findNode(edge.target);
  if (!source || !target) {
    return 0;
  }
  if (elevateEdgesOnSelect) {
    z = hasZIndex ? edge.zIndex : Math.max(source.computedPosition.z || 0, target.computedPosition.z || 0);
  }
  return z;
}
var ErrorCode = /* @__PURE__ */ ((ErrorCode2) => {
  ErrorCode2["MISSING_STYLES"] = "MISSING_STYLES";
  ErrorCode2["MISSING_VIEWPORT_DIMENSIONS"] = "MISSING_VIEWPORT_DIMENSIONS";
  ErrorCode2["NODE_INVALID"] = "NODE_INVALID";
  ErrorCode2["NODE_NOT_FOUND"] = "NODE_NOT_FOUND";
  ErrorCode2["NODE_MISSING_PARENT"] = "NODE_MISSING_PARENT";
  ErrorCode2["NODE_TYPE_MISSING"] = "NODE_TYPE_MISSING";
  ErrorCode2["NODE_EXTENT_INVALID"] = "NODE_EXTENT_INVALID";
  ErrorCode2["EDGE_INVALID"] = "EDGE_INVALID";
  ErrorCode2["EDGE_NOT_FOUND"] = "EDGE_NOT_FOUND";
  ErrorCode2["EDGE_SOURCE_MISSING"] = "EDGE_SOURCE_MISSING";
  ErrorCode2["EDGE_TARGET_MISSING"] = "EDGE_TARGET_MISSING";
  ErrorCode2["EDGE_TYPE_MISSING"] = "EDGE_TYPE_MISSING";
  ErrorCode2["EDGE_SOURCE_TARGET_SAME"] = "EDGE_SOURCE_TARGET_SAME";
  ErrorCode2["EDGE_SOURCE_TARGET_MISSING"] = "EDGE_SOURCE_TARGET_MISSING";
  ErrorCode2["EDGE_ORPHANED"] = "EDGE_ORPHANED";
  ErrorCode2["USEVUEFLOW_OPTIONS"] = "USEVUEFLOW_OPTIONS";
  return ErrorCode2;
})(ErrorCode || {});
const messages = {
  [
    "MISSING_STYLES"
    /* MISSING_STYLES */
  ]: () => `It seems that you haven't loaded the necessary styles. Please import '@vue-flow/core/dist/style.css' to ensure that the graph is rendered correctly`,
  [
    "MISSING_VIEWPORT_DIMENSIONS"
    /* MISSING_VIEWPORT_DIMENSIONS */
  ]: () => "The Vue Flow parent container needs a width and a height to render the graph",
  [
    "NODE_INVALID"
    /* NODE_INVALID */
  ]: (id2) => `Node is invalid
Node: ${id2}`,
  [
    "NODE_NOT_FOUND"
    /* NODE_NOT_FOUND */
  ]: (id2) => `Node not found
Node: ${id2}`,
  [
    "NODE_MISSING_PARENT"
    /* NODE_MISSING_PARENT */
  ]: (id2, parentId) => `Node is missing a parent
Node: ${id2}
Parent: ${parentId}`,
  [
    "NODE_TYPE_MISSING"
    /* NODE_TYPE_MISSING */
  ]: (type) => `Node type is missing
Type: ${type}`,
  [
    "NODE_EXTENT_INVALID"
    /* NODE_EXTENT_INVALID */
  ]: (id2) => `Only child nodes can use a parent extent
Node: ${id2}`,
  [
    "EDGE_INVALID"
    /* EDGE_INVALID */
  ]: (id2) => `An edge needs a source and a target
Edge: ${id2}`,
  [
    "EDGE_SOURCE_MISSING"
    /* EDGE_SOURCE_MISSING */
  ]: (id2, source) => `Edge source is missing
Edge: ${id2} 
Source: ${source}`,
  [
    "EDGE_TARGET_MISSING"
    /* EDGE_TARGET_MISSING */
  ]: (id2, target) => `Edge target is missing
Edge: ${id2} 
Target: ${target}`,
  [
    "EDGE_TYPE_MISSING"
    /* EDGE_TYPE_MISSING */
  ]: (type) => `Edge type is missing
Type: ${type}`,
  [
    "EDGE_SOURCE_TARGET_SAME"
    /* EDGE_SOURCE_TARGET_SAME */
  ]: (id2, source, target) => `Edge source and target are the same
Edge: ${id2} 
Source: ${source} 
Target: ${target}`,
  [
    "EDGE_SOURCE_TARGET_MISSING"
    /* EDGE_SOURCE_TARGET_MISSING */
  ]: (id2, source, target) => `Edge source or target is missing
Edge: ${id2} 
Source: ${source} 
Target: ${target}`,
  [
    "EDGE_ORPHANED"
    /* EDGE_ORPHANED */
  ]: (id2) => `Edge was orphaned (suddenly missing source or target) and has been removed
Edge: ${id2}`,
  [
    "EDGE_NOT_FOUND"
    /* EDGE_NOT_FOUND */
  ]: (id2) => `Edge not found
Edge: ${id2}`,
  // deprecation errors
  [
    "USEVUEFLOW_OPTIONS"
    /* USEVUEFLOW_OPTIONS */
  ]: () => `The options parameter is deprecated and will be removed in the next major version. Please use the id parameter instead`
};
class VueFlowError extends Error {
  constructor(code, ...args) {
    var _a;
    super((_a = messages[code]) == null ? void 0 : _a.call(messages, ...args));
    this.name = "VueFlowError";
    this.code = code;
    this.args = args;
  }
}
function isErrorOfType(error, code) {
  return error.code === code;
}
function isMouseEvent(event) {
  return "clientX" in event;
}
function isUseDragEvent(event) {
  return "sourceEvent" in event;
}
function getEventPosition(event, bounds) {
  const isMouse = isMouseEvent(event);
  let evtX;
  let evtY;
  if (isMouse) {
    evtX = event.clientX;
    evtY = event.clientY;
  } else if ("touches" in event && event.touches.length > 0) {
    evtX = event.touches[0].clientX;
    evtY = event.touches[0].clientY;
  } else if ("changedTouches" in event && event.changedTouches.length > 0) {
    evtX = event.changedTouches[0].clientX;
    evtY = event.changedTouches[0].clientY;
  } else {
    evtX = 0;
    evtY = 0;
  }
  return {
    x: evtX - ((bounds == null ? void 0 : bounds.left) ?? 0),
    y: evtY - ((bounds == null ? void 0 : bounds.top) ?? 0)
  };
}
const isMacOs = () => {
  var _a;
  return typeof navigator !== "undefined" && ((_a = navigator == null ? void 0 : navigator.userAgent) == null ? void 0 : _a.indexOf("Mac")) >= 0;
};
function getNodeDimensions(node) {
  var _a, _b;
  return {
    width: ((_a = node.dimensions) == null ? void 0 : _a.width) ?? node.width ?? 0,
    height: ((_b = node.dimensions) == null ? void 0 : _b.height) ?? node.height ?? 0
  };
}
function snapPosition(position, snapGrid = [1, 1]) {
  return {
    x: snapGrid[0] * Math.round(position.x / snapGrid[0]),
    y: snapGrid[1] * Math.round(position.y / snapGrid[1])
  };
}
const alwaysValid$1 = () => true;
function resetRecentHandle(handleDomNode) {
  handleDomNode == null ? void 0 : handleDomNode.classList.remove("valid", "connecting", "vue-flow__handle-valid", "vue-flow__handle-connecting");
}
function getNodesWithinDistance(position, nodeLookup, distance2) {
  const nodes = [];
  const rect = {
    x: position.x - distance2,
    y: position.y - distance2,
    width: distance2 * 2,
    height: distance2 * 2
  };
  for (const node of nodeLookup.values()) {
    if (getOverlappingArea(rect, nodeToRect(node)) > 0) {
      nodes.push(node);
    }
  }
  return nodes;
}
const ADDITIONAL_DISTANCE = 250;
function getClosestHandle(position, connectionRadius, nodeLookup, fromHandle) {
  var _a, _b;
  let closestHandles = [];
  let minDistance = Number.POSITIVE_INFINITY;
  const closeNodes = getNodesWithinDistance(position, nodeLookup, connectionRadius + ADDITIONAL_DISTANCE);
  for (const node of closeNodes) {
    const allHandles = [...((_a = node.handleBounds) == null ? void 0 : _a.source) ?? [], ...((_b = node.handleBounds) == null ? void 0 : _b.target) ?? []];
    for (const handle of allHandles) {
      if (fromHandle.nodeId === handle.nodeId && fromHandle.type === handle.type && fromHandle.id === handle.id) {
        continue;
      }
      const { x, y } = getHandlePosition(node, handle, handle.position, true);
      const distance2 = Math.sqrt((x - position.x) ** 2 + (y - position.y) ** 2);
      if (distance2 > connectionRadius) {
        continue;
      }
      if (distance2 < minDistance) {
        closestHandles = [{ ...handle, x, y }];
        minDistance = distance2;
      } else if (distance2 === minDistance) {
        closestHandles.push({ ...handle, x, y });
      }
    }
  }
  if (!closestHandles.length) {
    return null;
  }
  if (closestHandles.length > 1) {
    const oppositeHandleType = fromHandle.type === "source" ? "target" : "source";
    return closestHandles.find((handle) => handle.type === oppositeHandleType) ?? closestHandles[0];
  }
  return closestHandles[0];
}
function isValidHandle(event, {
  handle,
  connectionMode,
  fromNodeId,
  fromHandleId,
  fromType,
  doc,
  lib,
  flowId,
  isValidConnection = alwaysValid$1
}, edges, nodes, findNode) {
  const isTarget = fromType === "target";
  const handleDomNode = handle ? doc.querySelector(`.${lib}-flow__handle[data-id="${flowId}-${handle == null ? void 0 : handle.nodeId}-${handle == null ? void 0 : handle.id}-${handle == null ? void 0 : handle.type}"]`) : null;
  const { x, y } = getEventPosition(event);
  const handleBelow = doc.elementFromPoint(x, y);
  const handleToCheck = (handleBelow == null ? void 0 : handleBelow.classList.contains(`${lib}-flow__handle`)) ? handleBelow : handleDomNode;
  const result = {
    handleDomNode: handleToCheck,
    isValid: false,
    connection: null,
    toHandle: null
  };
  if (handleToCheck) {
    const handleType = getHandleType(void 0, handleToCheck);
    const handleNodeId = handleToCheck.getAttribute("data-nodeid");
    const handleId = handleToCheck.getAttribute("data-handleid");
    const connectable = handleToCheck.classList.contains("connectable");
    const connectableEnd = handleToCheck.classList.contains("connectableend");
    if (!handleNodeId || !handleType) {
      return result;
    }
    const connection = {
      source: isTarget ? handleNodeId : fromNodeId,
      sourceHandle: isTarget ? handleId : fromHandleId,
      target: isTarget ? fromNodeId : handleNodeId,
      targetHandle: isTarget ? fromHandleId : handleId
    };
    result.connection = connection;
    const isConnectable = connectable && connectableEnd;
    const isValid = isConnectable && (connectionMode === ConnectionMode.Strict ? isTarget && handleType === "source" || !isTarget && handleType === "target" : handleNodeId !== fromNodeId || handleId !== fromHandleId);
    result.isValid = isValid && isValidConnection(connection, {
      nodes,
      edges,
      sourceNode: findNode(fromNodeId),
      targetNode: findNode(handleNodeId)
    });
    result.toHandle = handle;
  }
  return result;
}
function getHandleType(edgeUpdaterType, handleDomNode) {
  if (edgeUpdaterType) {
    return edgeUpdaterType;
  } else if (handleDomNode == null ? void 0 : handleDomNode.classList.contains("target")) {
    return "target";
  } else if (handleDomNode == null ? void 0 : handleDomNode.classList.contains("source")) {
    return "source";
  }
  return null;
}
function getConnectionStatus(isInsideConnectionRadius, isHandleValid) {
  let connectionStatus = null;
  if (isHandleValid) {
    connectionStatus = "valid";
  } else if (isInsideConnectionRadius && !isHandleValid) {
    connectionStatus = "invalid";
  }
  return connectionStatus;
}
function isConnectionValid(isInsideConnectionRadius, isHandleValid) {
  let isValid = null;
  if (isHandleValid) {
    isValid = true;
  } else if (isInsideConnectionRadius && !isHandleValid) {
    isValid = false;
  }
  return isValid;
}
function getHandle(nodeId, handleType, handleId, nodeLookup, connectionMode, withAbsolutePosition = false) {
  var _a, _b, _c;
  const node = nodeLookup.get(nodeId);
  if (!node) {
    return null;
  }
  const handles = connectionMode === ConnectionMode.Strict ? (_a = node.handleBounds) == null ? void 0 : _a[handleType] : [...((_b = node.handleBounds) == null ? void 0 : _b.source) ?? [], ...((_c = node.handleBounds) == null ? void 0 : _c.target) ?? []];
  const handle = (handleId ? handles == null ? void 0 : handles.find((h2) => h2.id === handleId) : handles == null ? void 0 : handles[0]) ?? null;
  return handle && withAbsolutePosition ? { ...handle, ...getHandlePosition(node, handle, handle.position, true) } : handle;
}
const oppositePosition = {
  [Position.Left]: Position.Right,
  [Position.Right]: Position.Left,
  [Position.Top]: Position.Bottom,
  [Position.Bottom]: Position.Top
};
const productionEnvs = ["production", "prod"];
function warn(message, ...args) {
  if (isDev()) {
    console.warn(`[Vue Flow]: ${message}`, ...args);
  }
}
function isDev() {
  return !productionEnvs.includes("development" || 0);
}
function getHandleBounds(type, nodeElement, nodeBounds, zoom2, nodeId) {
  const handles = nodeElement.querySelectorAll(`.vue-flow__handle.${type}`);
  if (!(handles == null ? void 0 : handles.length)) {
    return null;
  }
  return Array.from(handles).map((handle) => {
    const handleBounds = handle.getBoundingClientRect();
    return {
      id: handle.getAttribute("data-handleid"),
      type,
      nodeId,
      position: handle.getAttribute("data-handlepos"),
      x: (handleBounds.left - nodeBounds.left) / zoom2,
      y: (handleBounds.top - nodeBounds.top) / zoom2,
      ...getDimensions(handle)
    };
  });
}
function handleNodeClick(node, multiSelectionActive, addSelectedNodes, removeSelectedNodes, nodesSelectionActive, unselect = false, nodeEl) {
  nodesSelectionActive.value = false;
  if (!node.selected) {
    addSelectedNodes([node]);
  } else if (unselect || node.selected && multiSelectionActive) {
    removeSelectedNodes([node]);
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.nextTick)(() => {
      nodeEl.blur();
    });
  }
}
function isDef(val) {
  const unrefVal = (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(val);
  return typeof unrefVal !== "undefined";
}
function addEdgeToStore(edgeParams, edges, triggerError, defaultEdgeOptions) {
  if (!edgeParams || !edgeParams.source || !edgeParams.target) {
    triggerError(new VueFlowError(ErrorCode.EDGE_INVALID, (edgeParams == null ? void 0 : edgeParams.id) ?? `[ID UNKNOWN]`));
    return false;
  }
  let edge;
  if (isEdge(edgeParams)) {
    edge = edgeParams;
  } else {
    edge = {
      ...edgeParams,
      id: getEdgeId(edgeParams)
    };
  }
  edge = parseEdge(edge, void 0, defaultEdgeOptions);
  if (connectionExists(edge, edges)) {
    return false;
  }
  return edge;
}
function updateEdgeAction(edge, newConnection, prevEdge, shouldReplaceId, triggerError) {
  if (!newConnection.source || !newConnection.target) {
    triggerError(new VueFlowError(ErrorCode.EDGE_INVALID, edge.id));
    return false;
  }
  if (!prevEdge) {
    triggerError(new VueFlowError(ErrorCode.EDGE_NOT_FOUND, edge.id));
    return false;
  }
  const { id: id2, ...rest } = edge;
  return {
    ...rest,
    id: shouldReplaceId ? getEdgeId(newConnection) : id2,
    source: newConnection.source,
    target: newConnection.target,
    sourceHandle: newConnection.sourceHandle,
    targetHandle: newConnection.targetHandle
  };
}
function createGraphNodes(nodes, findNode, triggerError) {
  const parentNodes = {};
  const nextNodes = [];
  for (let i = 0; i < nodes.length; ++i) {
    const node = nodes[i];
    if (!isNode(node)) {
      triggerError(
        new VueFlowError(ErrorCode.NODE_INVALID, node == null ? void 0 : node.id) || `[ID UNKNOWN|INDEX ${i}]`
      );
      continue;
    }
    const parsed = parseNode(node, findNode(node.id), node.parentNode);
    if (node.parentNode) {
      parentNodes[node.parentNode] = true;
    }
    nextNodes[i] = parsed;
  }
  for (const node of nextNodes) {
    const parentNode = findNode(node.parentNode) || nextNodes.find((n) => n.id === node.parentNode);
    if (node.parentNode && !parentNode) {
      triggerError(new VueFlowError(ErrorCode.NODE_MISSING_PARENT, node.id, node.parentNode));
    }
    if (node.parentNode || parentNodes[node.id]) {
      if (parentNodes[node.id]) {
        node.isParent = true;
      }
      if (parentNode) {
        parentNode.isParent = true;
      }
    }
  }
  return nextNodes;
}
function addConnectionToLookup(type, connection, connectionKey, connectionLookup, nodeId, handleId) {
  let key = nodeId;
  const nodeMap = connectionLookup.get(key) || /* @__PURE__ */ new Map();
  connectionLookup.set(key, nodeMap.set(connectionKey, connection));
  key = `${nodeId}-${type}`;
  const typeMap = connectionLookup.get(key) || /* @__PURE__ */ new Map();
  connectionLookup.set(key, typeMap.set(connectionKey, connection));
  if (handleId) {
    key = `${nodeId}-${type}-${handleId}`;
    const handleMap = connectionLookup.get(key) || /* @__PURE__ */ new Map();
    connectionLookup.set(key, handleMap.set(connectionKey, connection));
  }
}
function updateConnectionLookup(connectionLookup, edgeLookup, edges) {
  connectionLookup.clear();
  for (const edge of edges) {
    const { source: sourceNode, target: targetNode, sourceHandle = null, targetHandle = null } = edge;
    const connection = { edgeId: edge.id, source: sourceNode, target: targetNode, sourceHandle, targetHandle };
    const sourceKey = `${sourceNode}-${sourceHandle}--${targetNode}-${targetHandle}`;
    const targetKey = `${targetNode}-${targetHandle}--${sourceNode}-${sourceHandle}`;
    addConnectionToLookup("source", connection, targetKey, connectionLookup, sourceNode, sourceHandle);
    addConnectionToLookup("target", connection, sourceKey, connectionLookup, targetNode, targetHandle);
  }
}
function handleConnectionChange(a, b, cb) {
  if (!cb) {
    return;
  }
  const diff = [];
  for (const key of a.keys()) {
    if (!b.has(key)) {
      diff.push(a.get(key));
    }
  }
  if (diff.length) {
    cb(diff);
  }
}
function areConnectionMapsEqual(a, b) {
  if (!a && !b) {
    return true;
  }
  if (!a || !b || a.size !== b.size) {
    return false;
  }
  if (!a.size && !b.size) {
    return true;
  }
  for (const key of a.keys()) {
    if (!b.has(key)) {
      return false;
    }
  }
  return true;
}
function areSetsEqual(a, b) {
  if (a.size !== b.size) {
    return false;
  }
  for (const item of a) {
    if (!b.has(item)) {
      return false;
    }
  }
  return true;
}
function createGraphEdges(nextEdges, isValidConnection, findNode, findEdge, onError, defaultEdgeOptions, nodes, edges) {
  const validEdges = [];
  for (const edgeOrConnection of nextEdges) {
    const edge = isEdge(edgeOrConnection) ? edgeOrConnection : addEdgeToStore(edgeOrConnection, edges, onError, defaultEdgeOptions);
    if (!edge) {
      continue;
    }
    const sourceNode = findNode(edge.source);
    const targetNode = findNode(edge.target);
    if (!sourceNode || !targetNode) {
      onError(new VueFlowError(ErrorCode.EDGE_SOURCE_TARGET_MISSING, edge.id, edge.source, edge.target));
      continue;
    }
    if (!sourceNode) {
      onError(new VueFlowError(ErrorCode.EDGE_SOURCE_MISSING, edge.id, edge.source));
      continue;
    }
    if (!targetNode) {
      onError(new VueFlowError(ErrorCode.EDGE_TARGET_MISSING, edge.id, edge.target));
      continue;
    }
    if (isValidConnection) {
      const isValid = isValidConnection(edge, {
        edges,
        nodes,
        sourceNode,
        targetNode
      });
      if (!isValid) {
        onError(new VueFlowError(ErrorCode.EDGE_INVALID, edge.id));
        continue;
      }
    }
    const existingEdge = findEdge(edge.id);
    validEdges.push({
      ...parseEdge(edge, existingEdge, defaultEdgeOptions),
      sourceNode,
      targetNode
    });
  }
  return validEdges;
}
const VueFlow = Symbol("vueFlow");
const NodeId = Symbol("nodeId");
const NodeRef = Symbol("nodeRef");
const EdgeId = Symbol("edgeId");
const EdgeRef = Symbol("edgeRef");
const Slots = Symbol("slots");
function useDrag(params) {
  const {
    vueFlowRef,
    snapToGrid,
    snapGrid,
    noDragClassName,
    nodes,
    nodeExtent,
    nodeDragThreshold,
    viewport,
    autoPanOnNodeDrag,
    autoPanSpeed,
    nodesDraggable,
    panBy,
    findNode,
    multiSelectionActive,
    nodesSelectionActive,
    selectNodesOnDrag,
    removeSelectedElements,
    addSelectedNodes,
    updateNodePositions,
    emits
  } = useVueFlow();
  const { onStart, onDrag, onStop, onClick, el, disabled, id: id2, selectable, dragHandle } = params;
  const dragging = (0,vue__WEBPACK_IMPORTED_MODULE_0__.shallowRef)(false);
  let dragItems = [];
  let dragHandler;
  let containerBounds = null;
  let lastPos = { x: void 0, y: void 0 };
  let mousePosition = { x: 0, y: 0 };
  let dragEvent = null;
  let dragStarted = false;
  let autoPanId = 0;
  let autoPanStarted = false;
  const getPointerPosition = useGetPointerPosition();
  const updateNodes = ({ x, y }) => {
    lastPos = { x, y };
    let hasChange = false;
    dragItems = dragItems.map((n) => {
      const nextPosition = { x: x - n.distance.x, y: y - n.distance.y };
      const { computedPosition } = calcNextPosition(
        n,
        snapToGrid.value ? snapPosition(nextPosition, snapGrid.value) : nextPosition,
        emits.error,
        nodeExtent.value,
        n.parentNode ? findNode(n.parentNode) : void 0
      );
      hasChange = hasChange || n.position.x !== computedPosition.x || n.position.y !== computedPosition.y;
      n.position = computedPosition;
      return n;
    });
    if (!hasChange) {
      return;
    }
    updateNodePositions(dragItems, true, true);
    dragging.value = true;
    if (dragEvent) {
      const [currentNode, nodes2] = getEventHandlerParams({
        id: id2,
        dragItems,
        findNode
      });
      onDrag({ event: dragEvent, node: currentNode, nodes: nodes2 });
    }
  };
  const autoPan = () => {
    if (!containerBounds) {
      return;
    }
    const [xMovement, yMovement] = calcAutoPan(mousePosition, containerBounds, autoPanSpeed.value);
    if (xMovement !== 0 || yMovement !== 0) {
      const nextPos = {
        x: (lastPos.x ?? 0) - xMovement / viewport.value.zoom,
        y: (lastPos.y ?? 0) - yMovement / viewport.value.zoom
      };
      if (panBy({ x: xMovement, y: yMovement })) {
        updateNodes(nextPos);
      }
    }
    autoPanId = requestAnimationFrame(autoPan);
  };
  const startDrag = (event, nodeEl) => {
    dragStarted = true;
    const node = findNode(id2);
    if (!selectNodesOnDrag.value && !multiSelectionActive.value && node) {
      if (!node.selected) {
        removeSelectedElements();
      }
    }
    if (node && (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(selectable) && selectNodesOnDrag.value) {
      handleNodeClick(
        node,
        multiSelectionActive.value,
        addSelectedNodes,
        removeSelectedElements,
        nodesSelectionActive,
        false,
        nodeEl
      );
    }
    const pointerPos = getPointerPosition(event.sourceEvent);
    lastPos = pointerPos;
    dragItems = getDragItems(nodes.value, nodesDraggable.value, pointerPos, findNode, id2);
    if (dragItems.length) {
      const [currentNode, nodes2] = getEventHandlerParams({
        id: id2,
        dragItems,
        findNode
      });
      onStart({ event: event.sourceEvent, node: currentNode, nodes: nodes2 });
    }
  };
  const eventStart = (event, nodeEl) => {
    var _a;
    if (event.sourceEvent.type === "touchmove" && event.sourceEvent.touches.length > 1) {
      return;
    }
    if (nodeDragThreshold.value === 0) {
      startDrag(event, nodeEl);
    }
    lastPos = getPointerPosition(event.sourceEvent);
    containerBounds = ((_a = vueFlowRef.value) == null ? void 0 : _a.getBoundingClientRect()) || null;
    mousePosition = getEventPosition(event.sourceEvent, containerBounds);
  };
  const eventDrag = (event, nodeEl) => {
    const pointerPos = getPointerPosition(event.sourceEvent);
    if (!autoPanStarted && dragStarted && autoPanOnNodeDrag.value) {
      autoPanStarted = true;
      autoPan();
    }
    if (!dragStarted) {
      const x = pointerPos.xSnapped - (lastPos.x ?? 0);
      const y = pointerPos.ySnapped - (lastPos.y ?? 0);
      const distance2 = Math.sqrt(x * x + y * y);
      if (distance2 > nodeDragThreshold.value) {
        startDrag(event, nodeEl);
      }
    }
    if ((lastPos.x !== pointerPos.xSnapped || lastPos.y !== pointerPos.ySnapped) && dragItems.length && dragStarted) {
      dragEvent = event.sourceEvent;
      mousePosition = getEventPosition(event.sourceEvent, containerBounds);
      updateNodes(pointerPos);
    }
  };
  const eventEnd = (event) => {
    let isClick = false;
    if (!dragStarted && !dragging.value && !multiSelectionActive.value) {
      const evt = event.sourceEvent;
      const pointerPos = getPointerPosition(evt);
      const x = pointerPos.xSnapped - (lastPos.x ?? 0);
      const y = pointerPos.ySnapped - (lastPos.y ?? 0);
      const distance2 = Math.sqrt(x * x + y * y);
      if (distance2 !== 0 && distance2 <= nodeDragThreshold.value) {
        onClick == null ? void 0 : onClick(evt);
        isClick = true;
      }
    }
    if (dragItems.length && !isClick) {
      updateNodePositions(dragItems, false, false);
      const [currentNode, nodes2] = getEventHandlerParams({
        id: id2,
        dragItems,
        findNode
      });
      onStop({ event: event.sourceEvent, node: currentNode, nodes: nodes2 });
    }
    dragItems = [];
    dragging.value = false;
    autoPanStarted = false;
    dragStarted = false;
    lastPos = { x: void 0, y: void 0 };
    cancelAnimationFrame(autoPanId);
  };
  (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)([() => (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(disabled), el], ([isDisabled, nodeEl], _, onCleanup) => {
    if (nodeEl) {
      const selection2 = select(nodeEl);
      if (!isDisabled) {
        dragHandler = drag().on("start", (event) => eventStart(event, nodeEl)).on("drag", (event) => eventDrag(event, nodeEl)).on("end", (event) => eventEnd(event)).filter((event) => {
          const target = event.target;
          const unrefDragHandle = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(dragHandle);
          return !event.button && (!noDragClassName.value || !hasSelector(target, `.${noDragClassName.value}`, nodeEl) && (!unrefDragHandle || hasSelector(target, unrefDragHandle, nodeEl)));
        });
        selection2.call(dragHandler);
      }
      onCleanup(() => {
        selection2.on(".drag", null);
        if (dragHandler) {
          dragHandler.on("start", null);
          dragHandler.on("drag", null);
          dragHandler.on("end", null);
        }
      });
    }
  });
  return dragging;
}
function useEdge(id2) {
  const edgeId = id2 ?? (0,vue__WEBPACK_IMPORTED_MODULE_0__.inject)(EdgeId, "");
  const edgeEl = (0,vue__WEBPACK_IMPORTED_MODULE_0__.inject)(EdgeRef, (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null));
  const { findEdge, emits } = useVueFlow();
  const edge = findEdge(edgeId);
  if (!edge) {
    emits.error(new VueFlowError(ErrorCode.EDGE_NOT_FOUND, edgeId));
  }
  return {
    id: edgeId,
    edge,
    edgeEl
  };
}
function createEdgeHooks() {
  return {
    doubleClick: createExtendedEventHook(),
    click: createExtendedEventHook(),
    mouseEnter: createExtendedEventHook(),
    mouseMove: createExtendedEventHook(),
    mouseLeave: createExtendedEventHook(),
    contextMenu: createExtendedEventHook(),
    updateStart: createExtendedEventHook(),
    update: createExtendedEventHook(),
    updateEnd: createExtendedEventHook()
  };
}
function useEdgeHooks(edge, emits) {
  const edgeHooks = createEdgeHooks();
  edgeHooks.doubleClick.on((event) => {
    var _a, _b;
    emits.edgeDoubleClick(event);
    (_b = (_a = edge.events) == null ? void 0 : _a.doubleClick) == null ? void 0 : _b.call(_a, event);
  });
  edgeHooks.click.on((event) => {
    var _a, _b;
    emits.edgeClick(event);
    (_b = (_a = edge.events) == null ? void 0 : _a.click) == null ? void 0 : _b.call(_a, event);
  });
  edgeHooks.mouseEnter.on((event) => {
    var _a, _b;
    emits.edgeMouseEnter(event);
    (_b = (_a = edge.events) == null ? void 0 : _a.mouseEnter) == null ? void 0 : _b.call(_a, event);
  });
  edgeHooks.mouseMove.on((event) => {
    var _a, _b;
    emits.edgeMouseMove(event);
    (_b = (_a = edge.events) == null ? void 0 : _a.mouseMove) == null ? void 0 : _b.call(_a, event);
  });
  edgeHooks.mouseLeave.on((event) => {
    var _a, _b;
    emits.edgeMouseLeave(event);
    (_b = (_a = edge.events) == null ? void 0 : _a.mouseLeave) == null ? void 0 : _b.call(_a, event);
  });
  edgeHooks.contextMenu.on((event) => {
    var _a, _b;
    emits.edgeContextMenu(event);
    (_b = (_a = edge.events) == null ? void 0 : _a.contextMenu) == null ? void 0 : _b.call(_a, event);
  });
  edgeHooks.updateStart.on((event) => {
    var _a, _b;
    emits.edgeUpdateStart(event);
    (_b = (_a = edge.events) == null ? void 0 : _a.updateStart) == null ? void 0 : _b.call(_a, event);
  });
  edgeHooks.update.on((event) => {
    var _a, _b;
    emits.edgeUpdate(event);
    (_b = (_a = edge.events) == null ? void 0 : _a.update) == null ? void 0 : _b.call(_a, event);
  });
  edgeHooks.updateEnd.on((event) => {
    var _a, _b;
    emits.edgeUpdateEnd(event);
    (_b = (_a = edge.events) == null ? void 0 : _a.updateEnd) == null ? void 0 : _b.call(_a, event);
  });
  return Object.entries(edgeHooks).reduce(
    (hooks, [key, value]) => {
      hooks.emit[key] = value.trigger;
      hooks.on[key] = value.on;
      return hooks;
    },
    { emit: {}, on: {} }
  );
}
function useGetPointerPosition() {
  const { viewport, snapGrid, snapToGrid, vueFlowRef } = useVueFlow();
  return (event) => {
    var _a;
    const containerBounds = ((_a = vueFlowRef.value) == null ? void 0 : _a.getBoundingClientRect()) ?? { left: 0, top: 0 };
    const evt = isUseDragEvent(event) ? event.sourceEvent : event;
    const { x, y } = getEventPosition(evt, containerBounds);
    const pointerPos = pointToRendererPoint({ x, y }, viewport.value);
    const { x: xSnapped, y: ySnapped } = snapToGrid.value ? snapPosition(pointerPos, snapGrid.value) : pointerPos;
    return {
      xSnapped,
      ySnapped,
      ...pointerPos
    };
  };
}
function alwaysValid() {
  return true;
}
function useHandle({
  handleId,
  nodeId,
  type,
  isValidConnection,
  edgeUpdaterType,
  onEdgeUpdate,
  onEdgeUpdateEnd
}) {
  const {
    id: flowId,
    vueFlowRef,
    connectionMode,
    connectionRadius,
    connectOnClick,
    connectionClickStartHandle,
    nodesConnectable,
    autoPanOnConnect,
    autoPanSpeed,
    findNode,
    panBy,
    startConnection,
    updateConnection,
    endConnection,
    emits,
    viewport,
    edges,
    nodes,
    isValidConnection: isValidConnectionProp,
    nodeLookup
  } = useVueFlow();
  let connection = null;
  let isValid = false;
  let handleDomNode = null;
  function handlePointerDown(event) {
    var _a;
    const isTarget = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(type) === "target";
    const isMouseTriggered = isMouseEvent(event);
    const doc = getHostForElement(event.target);
    if (isMouseTriggered && event.button === 0 || !isMouseTriggered) {
      let onPointerMove = function(event2) {
        connectionPosition = getEventPosition(event2, containerBounds);
        closestHandle = getClosestHandle(
          pointToRendererPoint(connectionPosition, viewport.value, false, [1, 1]),
          connectionRadius.value,
          nodeLookup.value,
          fromHandle
        );
        if (!autoPanStarted) {
          autoPan();
          autoPanStarted = true;
        }
        const result = isValidHandle(
          event2,
          {
            handle: closestHandle,
            connectionMode: connectionMode.value,
            fromNodeId: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId),
            fromHandleId: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(handleId),
            fromType: isTarget ? "target" : "source",
            isValidConnection: isValidConnectionHandler,
            doc,
            lib: "vue",
            flowId,
            nodeLookup: nodeLookup.value
          },
          edges.value,
          nodes.value,
          findNode
        );
        handleDomNode = result.handleDomNode;
        connection = result.connection;
        isValid = isConnectionValid(!!closestHandle, result.isValid);
        const newConnection2 = {
          // from stays the same
          ...previousConnection,
          isValid,
          to: result.toHandle && isValid ? rendererPointToPoint({ x: result.toHandle.x, y: result.toHandle.y }, viewport.value) : connectionPosition,
          toHandle: result.toHandle,
          toPosition: isValid && result.toHandle ? result.toHandle.position : oppositePosition[fromHandle.position],
          toNode: result.toHandle ? nodeLookup.value.get(result.toHandle.nodeId) : null
        };
        if (isValid && closestHandle && (previousConnection == null ? void 0 : previousConnection.toHandle) && newConnection2.toHandle && previousConnection.toHandle.type === newConnection2.toHandle.type && previousConnection.toHandle.nodeId === newConnection2.toHandle.nodeId && previousConnection.toHandle.id === newConnection2.toHandle.id && previousConnection.to.x === newConnection2.to.x && previousConnection.to.y === newConnection2.to.y) {
          return;
        }
        updateConnection(
          closestHandle && isValid ? rendererPointToPoint(
            {
              x: closestHandle.x,
              y: closestHandle.y
            },
            viewport.value
          ) : connectionPosition,
          result.toHandle,
          getConnectionStatus(!!closestHandle, isValid)
        );
        previousConnection = newConnection2;
        if (!closestHandle && !isValid && !handleDomNode) {
          return resetRecentHandle(prevActiveHandle);
        }
        if (connection && connection.source !== connection.target && handleDomNode) {
          resetRecentHandle(prevActiveHandle);
          prevActiveHandle = handleDomNode;
          handleDomNode.classList.add("connecting", "vue-flow__handle-connecting");
          handleDomNode.classList.toggle("valid", !!isValid);
          handleDomNode.classList.toggle("vue-flow__handle-valid", !!isValid);
        }
      }, onPointerUp = function(event2) {
        if ((closestHandle || handleDomNode) && connection && isValid) {
          if (!onEdgeUpdate) {
            emits.connect(connection);
          } else {
            onEdgeUpdate(event2, connection);
          }
        }
        emits.connectEnd(event2);
        if (edgeUpdaterType) {
          onEdgeUpdateEnd == null ? void 0 : onEdgeUpdateEnd(event2);
        }
        resetRecentHandle(prevActiveHandle);
        cancelAnimationFrame(autoPanId);
        endConnection(event2);
        autoPanStarted = false;
        isValid = false;
        connection = null;
        handleDomNode = null;
        doc.removeEventListener("mousemove", onPointerMove);
        doc.removeEventListener("mouseup", onPointerUp);
        doc.removeEventListener("touchmove", onPointerMove);
        doc.removeEventListener("touchend", onPointerUp);
      };
      const node = findNode((0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId));
      let isValidConnectionHandler = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(isValidConnection) || isValidConnectionProp.value || alwaysValid;
      if (!isValidConnectionHandler && node) {
        isValidConnectionHandler = (!isTarget ? node.isValidTargetPos : node.isValidSourcePos) || alwaysValid;
      }
      let closestHandle;
      let autoPanId = 0;
      const { x, y } = getEventPosition(event);
      const clickedHandle = doc == null ? void 0 : doc.elementFromPoint(x, y);
      const handleType = getHandleType((0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(edgeUpdaterType), clickedHandle);
      const containerBounds = (_a = vueFlowRef.value) == null ? void 0 : _a.getBoundingClientRect();
      if (!containerBounds || !handleType) {
        return;
      }
      const fromHandleInternal = getHandle((0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId), handleType, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(handleId), nodeLookup.value, connectionMode.value);
      if (!fromHandleInternal) {
        return;
      }
      let prevActiveHandle;
      let connectionPosition = getEventPosition(event, containerBounds);
      let autoPanStarted = false;
      const autoPan = () => {
        if (!autoPanOnConnect.value) {
          return;
        }
        const [xMovement, yMovement] = calcAutoPan(connectionPosition, containerBounds, autoPanSpeed.value);
        panBy({ x: xMovement, y: yMovement });
        autoPanId = requestAnimationFrame(autoPan);
      };
      const fromHandle = {
        ...fromHandleInternal,
        nodeId: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId),
        type: handleType,
        position: fromHandleInternal.position
      };
      const fromNodeInternal = nodeLookup.value.get((0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId));
      const from = getHandlePosition(fromNodeInternal, fromHandle, Position.Left, true);
      const newConnection = {
        inProgress: true,
        isValid: null,
        from,
        fromHandle,
        fromPosition: fromHandle.position,
        fromNode: fromNodeInternal,
        to: connectionPosition,
        toHandle: null,
        toPosition: oppositePosition[fromHandle.position],
        toNode: null
      };
      startConnection(
        {
          nodeId: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId),
          id: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(handleId),
          type: handleType,
          position: (clickedHandle == null ? void 0 : clickedHandle.getAttribute("data-handlepos")) || Position.Top,
          ...connectionPosition
        },
        {
          x: x - containerBounds.left,
          y: y - containerBounds.top
        }
      );
      emits.connectStart({ event, nodeId: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId), handleId: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(handleId), handleType });
      let previousConnection = newConnection;
      doc.addEventListener("mousemove", onPointerMove);
      doc.addEventListener("mouseup", onPointerUp);
      doc.addEventListener("touchmove", onPointerMove);
      doc.addEventListener("touchend", onPointerUp);
    }
  }
  function handleClick(event) {
    var _a, _b;
    if (!connectOnClick.value) {
      return;
    }
    const isTarget = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(type) === "target";
    if (!connectionClickStartHandle.value) {
      emits.clickConnectStart({ event, nodeId: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId), handleId: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(handleId) });
      startConnection(
        {
          nodeId: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId),
          type: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(type),
          id: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(handleId),
          position: Position.Top,
          ...getEventPosition(event)
        },
        void 0,
        true
      );
      return;
    }
    let isValidConnectionHandler = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(isValidConnection) || isValidConnectionProp.value || alwaysValid;
    const node = findNode((0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId));
    if (!isValidConnectionHandler && node) {
      isValidConnectionHandler = (!isTarget ? node.isValidTargetPos : node.isValidSourcePos) || alwaysValid;
    }
    if (node && (typeof node.connectable === "undefined" ? nodesConnectable.value : node.connectable) === false) {
      return;
    }
    const doc = getHostForElement(event.target);
    const result = isValidHandle(
      event,
      {
        handle: {
          nodeId: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId),
          id: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(handleId),
          type: (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(type),
          position: Position.Top,
          ...getEventPosition(event)
        },
        connectionMode: connectionMode.value,
        fromNodeId: connectionClickStartHandle.value.nodeId,
        fromHandleId: connectionClickStartHandle.value.id ?? null,
        fromType: connectionClickStartHandle.value.type,
        isValidConnection: isValidConnectionHandler,
        doc,
        lib: "vue",
        flowId,
        nodeLookup: nodeLookup.value
      },
      edges.value,
      nodes.value,
      findNode
    );
    const isOwnHandle = ((_a = result.connection) == null ? void 0 : _a.source) === ((_b = result.connection) == null ? void 0 : _b.target);
    if (result.isValid && result.connection && !isOwnHandle) {
      emits.connect(result.connection);
    }
    emits.clickConnectEnd(event);
    endConnection(event, true);
  }
  return {
    handlePointerDown,
    handleClick
  };
}
function useNodeId() {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.inject)(NodeId, "");
}
function useNode(id2) {
  const nodeId = id2 ?? useNodeId() ?? "";
  const nodeEl = (0,vue__WEBPACK_IMPORTED_MODULE_0__.inject)(NodeRef, (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null));
  const { findNode, edges, emits } = useVueFlow();
  const node = findNode(nodeId);
  if (!node) {
    emits.error(new VueFlowError(ErrorCode.NODE_NOT_FOUND, nodeId));
  }
  return {
    id: nodeId,
    nodeEl,
    node,
    parentNode: (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => findNode(node.parentNode)),
    connectedEdges: (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => getConnectedEdges([node], edges.value))
  };
}
function createNodeHooks() {
  return {
    doubleClick: createExtendedEventHook(),
    click: createExtendedEventHook(),
    mouseEnter: createExtendedEventHook(),
    mouseMove: createExtendedEventHook(),
    mouseLeave: createExtendedEventHook(),
    contextMenu: createExtendedEventHook(),
    dragStart: createExtendedEventHook(),
    drag: createExtendedEventHook(),
    dragStop: createExtendedEventHook()
  };
}
function useNodeHooks(node, emits) {
  const nodeHooks = createNodeHooks();
  nodeHooks.doubleClick.on((event) => {
    var _a, _b;
    emits.nodeDoubleClick(event);
    (_b = (_a = node.events) == null ? void 0 : _a.doubleClick) == null ? void 0 : _b.call(_a, event);
  });
  nodeHooks.click.on((event) => {
    var _a, _b;
    emits.nodeClick(event);
    (_b = (_a = node.events) == null ? void 0 : _a.click) == null ? void 0 : _b.call(_a, event);
  });
  nodeHooks.mouseEnter.on((event) => {
    var _a, _b;
    emits.nodeMouseEnter(event);
    (_b = (_a = node.events) == null ? void 0 : _a.mouseEnter) == null ? void 0 : _b.call(_a, event);
  });
  nodeHooks.mouseMove.on((event) => {
    var _a, _b;
    emits.nodeMouseMove(event);
    (_b = (_a = node.events) == null ? void 0 : _a.mouseMove) == null ? void 0 : _b.call(_a, event);
  });
  nodeHooks.mouseLeave.on((event) => {
    var _a, _b;
    emits.nodeMouseLeave(event);
    (_b = (_a = node.events) == null ? void 0 : _a.mouseLeave) == null ? void 0 : _b.call(_a, event);
  });
  nodeHooks.contextMenu.on((event) => {
    var _a, _b;
    emits.nodeContextMenu(event);
    (_b = (_a = node.events) == null ? void 0 : _a.contextMenu) == null ? void 0 : _b.call(_a, event);
  });
  nodeHooks.dragStart.on((event) => {
    var _a, _b;
    emits.nodeDragStart(event);
    (_b = (_a = node.events) == null ? void 0 : _a.dragStart) == null ? void 0 : _b.call(_a, event);
  });
  nodeHooks.drag.on((event) => {
    var _a, _b;
    emits.nodeDrag(event);
    (_b = (_a = node.events) == null ? void 0 : _a.drag) == null ? void 0 : _b.call(_a, event);
  });
  nodeHooks.dragStop.on((event) => {
    var _a, _b;
    emits.nodeDragStop(event);
    (_b = (_a = node.events) == null ? void 0 : _a.dragStop) == null ? void 0 : _b.call(_a, event);
  });
  return Object.entries(nodeHooks).reduce(
    (hooks, [key, value]) => {
      hooks.emit[key] = value.trigger;
      hooks.on[key] = value.on;
      return hooks;
    },
    { emit: {}, on: {} }
  );
}
function useUpdateNodePositions() {
  const { getSelectedNodes, nodeExtent, updateNodePositions, findNode, snapGrid, snapToGrid, nodesDraggable, emits } = useVueFlow();
  return (positionDiff, isShiftPressed = false) => {
    const xVelo = snapToGrid.value ? snapGrid.value[0] : 5;
    const yVelo = snapToGrid.value ? snapGrid.value[1] : 5;
    const factor = isShiftPressed ? 4 : 1;
    const positionDiffX = positionDiff.x * xVelo * factor;
    const positionDiffY = positionDiff.y * yVelo * factor;
    const nodeUpdates = [];
    for (const node of getSelectedNodes.value) {
      if (node.draggable || nodesDraggable && typeof node.draggable === "undefined") {
        const nextPosition = { x: node.computedPosition.x + positionDiffX, y: node.computedPosition.y + positionDiffY };
        const { computedPosition } = calcNextPosition(
          node,
          nextPosition,
          emits.error,
          nodeExtent.value,
          node.parentNode ? findNode(node.parentNode) : void 0
        );
        nodeUpdates.push({
          id: node.id,
          position: computedPosition,
          from: node.position,
          distance: { x: positionDiff.x, y: positionDiff.y },
          dimensions: node.dimensions
        });
      }
    }
    updateNodePositions(nodeUpdates, true, false);
  };
}
const DEFAULT_PADDING = 0.1;
const defaultEase = (t) => ((t *= 2) <= 1 ? t * t * t : (t -= 2) * t * t + 2) / 2;
function noop() {
  warn("Viewport not initialized yet.");
  return Promise.resolve(false);
}
const initialViewportHelper = {
  zoomIn: noop,
  zoomOut: noop,
  zoomTo: noop,
  fitView: noop,
  setCenter: noop,
  fitBounds: noop,
  project: (position) => position,
  screenToFlowCoordinate: (position) => position,
  flowToScreenCoordinate: (position) => position,
  setViewport: noop,
  setTransform: noop,
  getViewport: () => ({ x: 0, y: 0, zoom: 1 }),
  getTransform: () => ({ x: 0, y: 0, zoom: 1 }),
  viewportInitialized: false
};
function useViewportHelper(state) {
  function zoom2(scale, transitionOptions) {
    return new Promise((resolve) => {
      if (state.d3Selection && state.d3Zoom) {
        state.d3Zoom.interpolate((transitionOptions == null ? void 0 : transitionOptions.interpolate) === "linear" ? interpolate$1 : interpolateZoom).scaleBy(
          getD3Transition(state.d3Selection, transitionOptions == null ? void 0 : transitionOptions.duration, transitionOptions == null ? void 0 : transitionOptions.ease, () => {
            resolve(true);
          }),
          scale
        );
      } else {
        resolve(false);
      }
    });
  }
  function transformViewport(x, y, zoom22, transitionOptions) {
    return new Promise((resolve) => {
      var _a;
      const { x: clampedX, y: clampedY } = clampPosition({ x: -x, y: -y }, state.translateExtent);
      const nextTransform = identity.translate(-clampedX, -clampedY).scale(zoom22);
      if (state.d3Selection && state.d3Zoom) {
        (_a = state.d3Zoom) == null ? void 0 : _a.interpolate((transitionOptions == null ? void 0 : transitionOptions.interpolate) === "linear" ? interpolate$1 : interpolateZoom).transform(
          getD3Transition(state.d3Selection, transitionOptions == null ? void 0 : transitionOptions.duration, transitionOptions == null ? void 0 : transitionOptions.ease, () => {
            resolve(true);
          }),
          nextTransform
        );
      } else {
        resolve(false);
      }
    });
  }
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
    const isInitialized = state.d3Zoom && state.d3Selection && state.dimensions.width && state.dimensions.height;
    if (!isInitialized) {
      return initialViewportHelper;
    }
    return {
      viewportInitialized: true,
      // todo: allow passing scale as option
      zoomIn: (options) => {
        return zoom2(1.2, options);
      },
      zoomOut: (options) => {
        return zoom2(1 / 1.2, options);
      },
      zoomTo: (zoomLevel, options) => {
        return new Promise((resolve) => {
          if (state.d3Selection && state.d3Zoom) {
            state.d3Zoom.interpolate((options == null ? void 0 : options.interpolate) === "linear" ? interpolate$1 : interpolateZoom).scaleTo(
              getD3Transition(state.d3Selection, options == null ? void 0 : options.duration, options == null ? void 0 : options.ease, () => {
                resolve(true);
              }),
              zoomLevel
            );
          } else {
            resolve(false);
          }
        });
      },
      setViewport: (transform, options) => {
        return transformViewport(transform.x, transform.y, transform.zoom, options);
      },
      setTransform: (transform, options) => {
        return transformViewport(transform.x, transform.y, transform.zoom, options);
      },
      getViewport: () => ({
        x: state.viewport.x,
        y: state.viewport.y,
        zoom: state.viewport.zoom
      }),
      getTransform: () => {
        return {
          x: state.viewport.x,
          y: state.viewport.y,
          zoom: state.viewport.zoom
        };
      },
      fitView: (options = {
        padding: DEFAULT_PADDING,
        includeHiddenNodes: false,
        duration: 0
      }) => {
        var _a, _b;
        const nodesToFit = [];
        for (const node of state.nodes) {
          const isVisible = node.dimensions.width && node.dimensions.height && ((options == null ? void 0 : options.includeHiddenNodes) || !node.hidden);
          if (isVisible) {
            if (!((_a = options.nodes) == null ? void 0 : _a.length) || ((_b = options.nodes) == null ? void 0 : _b.length) && options.nodes.includes(node.id)) {
              nodesToFit.push(node);
            }
          }
        }
        if (!nodesToFit.length) {
          return Promise.resolve(false);
        }
        const bounds = getRectOfNodes(nodesToFit);
        const { x, y, zoom: zoom22 } = getTransformForBounds(
          bounds,
          state.dimensions.width,
          state.dimensions.height,
          options.minZoom ?? state.minZoom,
          options.maxZoom ?? state.maxZoom,
          options.padding ?? DEFAULT_PADDING,
          options.offset
        );
        return transformViewport(x, y, zoom22, options);
      },
      setCenter: (x, y, options) => {
        const nextZoom = typeof (options == null ? void 0 : options.zoom) !== "undefined" ? options.zoom : state.maxZoom;
        const centerX = state.dimensions.width / 2 - x * nextZoom;
        const centerY = state.dimensions.height / 2 - y * nextZoom;
        return transformViewport(centerX, centerY, nextZoom, options);
      },
      fitBounds: (bounds, options = { padding: DEFAULT_PADDING }) => {
        const { x, y, zoom: zoom22 } = getTransformForBounds(
          bounds,
          state.dimensions.width,
          state.dimensions.height,
          state.minZoom,
          state.maxZoom,
          options.padding
        );
        return transformViewport(x, y, zoom22, options);
      },
      project: (position) => pointToRendererPoint(position, state.viewport, state.snapToGrid, state.snapGrid),
      screenToFlowCoordinate: (position) => {
        if (state.vueFlowRef) {
          const { x: domX, y: domY } = state.vueFlowRef.getBoundingClientRect();
          const correctedPosition = {
            x: position.x - domX,
            y: position.y - domY
          };
          return pointToRendererPoint(correctedPosition, state.viewport, state.snapToGrid, state.snapGrid);
        }
        return { x: 0, y: 0 };
      },
      flowToScreenCoordinate: (position) => {
        if (state.vueFlowRef) {
          const { x: domX, y: domY } = state.vueFlowRef.getBoundingClientRect();
          const correctedPosition = {
            x: position.x + domX,
            y: position.y + domY
          };
          return rendererPointToPoint(correctedPosition, state.viewport);
        }
        return { x: 0, y: 0 };
      }
    };
  });
}
function getD3Transition(selection2, duration = 0, ease = defaultEase, onEnd = () => {
}) {
  const hasDuration = typeof duration === "number" && duration > 0;
  if (!hasDuration) {
    onEnd();
  }
  return hasDuration ? selection2.transition().duration(duration).ease(ease).on("end", onEnd) : selection2;
}
function useWatchProps(models, props, store) {
  const scope = (0,vue__WEBPACK_IMPORTED_MODULE_0__.effectScope)(true);
  scope.run(() => {
    const watchModelValue = () => {
      scope.run(() => {
        let pauseModel;
        let pauseStore;
        let immediateStore = !!(store.nodes.value.length || store.edges.value.length);
        pauseModel = watchPausable([models.modelValue, () => {
          var _a, _b;
          return (_b = (_a = models.modelValue) == null ? void 0 : _a.value) == null ? void 0 : _b.length;
        }], ([elements]) => {
          if (elements && Array.isArray(elements)) {
            pauseStore == null ? void 0 : pauseStore.pause();
            store.setElements(elements);
            if (!pauseStore && !immediateStore && elements.length) {
              immediateStore = true;
            } else {
              pauseStore == null ? void 0 : pauseStore.resume();
            }
          }
        });
        pauseStore = watchPausable(
          [store.nodes, store.edges, () => store.edges.value.length, () => store.nodes.value.length],
          ([nodes, edges]) => {
            var _a;
            if (((_a = models.modelValue) == null ? void 0 : _a.value) && Array.isArray(models.modelValue.value)) {
              pauseModel == null ? void 0 : pauseModel.pause();
              models.modelValue.value = [...nodes, ...edges];
              (0,vue__WEBPACK_IMPORTED_MODULE_0__.nextTick)(() => {
                pauseModel == null ? void 0 : pauseModel.resume();
              });
            }
          },
          { immediate: immediateStore }
        );
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.onScopeDispose)(() => {
          pauseModel == null ? void 0 : pauseModel.stop();
          pauseStore == null ? void 0 : pauseStore.stop();
        });
      });
    };
    const watchNodesValue = () => {
      scope.run(() => {
        let pauseModel;
        let pauseStore;
        let immediateStore = !!store.nodes.value.length;
        pauseModel = watchPausable([models.nodes, () => {
          var _a, _b;
          return (_b = (_a = models.nodes) == null ? void 0 : _a.value) == null ? void 0 : _b.length;
        }], ([nodes]) => {
          if (nodes && Array.isArray(nodes)) {
            pauseStore == null ? void 0 : pauseStore.pause();
            store.setNodes(nodes);
            if (!pauseStore && !immediateStore && nodes.length) {
              immediateStore = true;
            } else {
              pauseStore == null ? void 0 : pauseStore.resume();
            }
          }
        });
        pauseStore = watchPausable(
          [store.nodes, () => store.nodes.value.length],
          ([nodes]) => {
            var _a;
            if (((_a = models.nodes) == null ? void 0 : _a.value) && Array.isArray(models.nodes.value)) {
              pauseModel == null ? void 0 : pauseModel.pause();
              models.nodes.value = [...nodes];
              (0,vue__WEBPACK_IMPORTED_MODULE_0__.nextTick)(() => {
                pauseModel == null ? void 0 : pauseModel.resume();
              });
            }
          },
          { immediate: immediateStore }
        );
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.onScopeDispose)(() => {
          pauseModel == null ? void 0 : pauseModel.stop();
          pauseStore == null ? void 0 : pauseStore.stop();
        });
      });
    };
    const watchEdgesValue = () => {
      scope.run(() => {
        let pauseModel;
        let pauseStore;
        let immediateStore = !!store.edges.value.length;
        pauseModel = watchPausable([models.edges, () => {
          var _a, _b;
          return (_b = (_a = models.edges) == null ? void 0 : _a.value) == null ? void 0 : _b.length;
        }], ([edges]) => {
          if (edges && Array.isArray(edges)) {
            pauseStore == null ? void 0 : pauseStore.pause();
            store.setEdges(edges);
            if (!pauseStore && !immediateStore && edges.length) {
              immediateStore = true;
            } else {
              pauseStore == null ? void 0 : pauseStore.resume();
            }
          }
        });
        pauseStore = watchPausable(
          [store.edges, () => store.edges.value.length],
          ([edges]) => {
            var _a;
            if (((_a = models.edges) == null ? void 0 : _a.value) && Array.isArray(models.edges.value)) {
              pauseModel == null ? void 0 : pauseModel.pause();
              models.edges.value = [...edges];
              (0,vue__WEBPACK_IMPORTED_MODULE_0__.nextTick)(() => {
                pauseModel == null ? void 0 : pauseModel.resume();
              });
            }
          },
          { immediate: immediateStore }
        );
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.onScopeDispose)(() => {
          pauseModel == null ? void 0 : pauseModel.stop();
          pauseStore == null ? void 0 : pauseStore.stop();
        });
      });
    };
    const watchMaxZoom = () => {
      scope.run(() => {
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
          () => props.maxZoom,
          () => {
            if (props.maxZoom && isDef(props.maxZoom)) {
              store.setMaxZoom(props.maxZoom);
            }
          },
          {
            immediate: true
          }
        );
      });
    };
    const watchMinZoom = () => {
      scope.run(() => {
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
          () => props.minZoom,
          () => {
            if (props.minZoom && isDef(props.minZoom)) {
              store.setMinZoom(props.minZoom);
            }
          },
          { immediate: true }
        );
      });
    };
    const watchTranslateExtent = () => {
      scope.run(() => {
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
          () => props.translateExtent,
          () => {
            if (props.translateExtent && isDef(props.translateExtent)) {
              store.setTranslateExtent(props.translateExtent);
            }
          },
          {
            immediate: true
          }
        );
      });
    };
    const watchNodeExtent = () => {
      scope.run(() => {
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
          () => props.nodeExtent,
          () => {
            if (props.nodeExtent && isDef(props.nodeExtent)) {
              store.setNodeExtent(props.nodeExtent);
            }
          },
          {
            immediate: true
          }
        );
      });
    };
    const watchApplyDefault = () => {
      scope.run(() => {
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
          () => props.applyDefault,
          () => {
            if (isDef(props.applyDefault)) {
              store.applyDefault.value = props.applyDefault;
            }
          },
          {
            immediate: true
          }
        );
      });
    };
    const watchAutoConnect = () => {
      scope.run(() => {
        const autoConnector = async (params) => {
          let connection = params;
          if (typeof props.autoConnect === "function") {
            connection = await props.autoConnect(params);
          }
          if (connection !== false) {
            store.addEdges([connection]);
          }
        };
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
          () => props.autoConnect,
          () => {
            if (isDef(props.autoConnect)) {
              store.autoConnect.value = props.autoConnect;
            }
          },
          { immediate: true }
        );
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
          store.autoConnect,
          (autoConnectEnabled, _, onCleanup) => {
            if (autoConnectEnabled) {
              store.onConnect(autoConnector);
            } else {
              store.hooks.value.connect.off(autoConnector);
            }
            onCleanup(() => {
              store.hooks.value.connect.off(autoConnector);
            });
          },
          { immediate: true }
        );
      });
    };
    const watchRest = () => {
      const skip = [
        "id",
        "modelValue",
        "translateExtent",
        "nodeExtent",
        "edges",
        "nodes",
        "maxZoom",
        "minZoom",
        "applyDefault",
        "autoConnect"
      ];
      for (const key of Object.keys(props)) {
        const propKey = key;
        if (!skip.includes(propKey)) {
          const propValue = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => props[propKey]);
          const storeRef = store[propKey];
          if ((0,vue__WEBPACK_IMPORTED_MODULE_0__.isRef)(storeRef)) {
            scope.run(() => {
              (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
                propValue,
                (nextValue) => {
                  if (isDef(nextValue)) {
                    storeRef.value = nextValue;
                  }
                },
                { immediate: true }
              );
            });
          }
        }
      }
    };
    const runAll = () => {
      watchModelValue();
      watchNodesValue();
      watchEdgesValue();
      watchMinZoom();
      watchMaxZoom();
      watchTranslateExtent();
      watchNodeExtent();
      watchApplyDefault();
      watchAutoConnect();
      watchRest();
    };
    runAll();
  });
  return () => scope.stop();
}
function useZoomPanHelper(vueFlowId) {
  const state = useVueFlow({ id: vueFlowId });
  const viewportHelper = useViewportHelper(toReactive(state));
  return {
    fitView: (params) => viewportHelper.value.fitView(params),
    zoomIn: (transitionOpts) => viewportHelper.value.zoomIn(transitionOpts),
    zoomOut: (transitionOpts) => viewportHelper.value.zoomOut(transitionOpts),
    zoomTo: (zoomLevel, transitionOpts) => viewportHelper.value.zoomTo(zoomLevel, transitionOpts),
    setViewport: (params, transitionOpts) => viewportHelper.value.setViewport(params, transitionOpts),
    setTransform: (params, transitionOpts) => viewportHelper.value.setTransform(params, transitionOpts),
    getViewport: () => viewportHelper.value.getViewport(),
    getTransform: () => viewportHelper.value.getTransform(),
    setCenter: (x, y, opts) => viewportHelper.value.setCenter(x, y, opts),
    fitBounds: (params, opts) => viewportHelper.value.fitBounds(params, opts),
    project: (params) => viewportHelper.value.project(params)
  };
}
function createHooks() {
  return {
    edgesChange: createExtendedEventHook(),
    nodesChange: createExtendedEventHook(),
    nodeDoubleClick: createExtendedEventHook(),
    nodeClick: createExtendedEventHook(),
    nodeMouseEnter: createExtendedEventHook(),
    nodeMouseMove: createExtendedEventHook(),
    nodeMouseLeave: createExtendedEventHook(),
    nodeContextMenu: createExtendedEventHook(),
    nodeDragStart: createExtendedEventHook(),
    nodeDrag: createExtendedEventHook(),
    nodeDragStop: createExtendedEventHook(),
    nodesInitialized: createExtendedEventHook(),
    miniMapNodeClick: createExtendedEventHook(),
    miniMapNodeDoubleClick: createExtendedEventHook(),
    miniMapNodeMouseEnter: createExtendedEventHook(),
    miniMapNodeMouseMove: createExtendedEventHook(),
    miniMapNodeMouseLeave: createExtendedEventHook(),
    connect: createExtendedEventHook(),
    connectStart: createExtendedEventHook(),
    connectEnd: createExtendedEventHook(),
    clickConnectStart: createExtendedEventHook(),
    clickConnectEnd: createExtendedEventHook(),
    paneReady: createExtendedEventHook(),
    init: createExtendedEventHook(),
    move: createExtendedEventHook(),
    moveStart: createExtendedEventHook(),
    moveEnd: createExtendedEventHook(),
    selectionDragStart: createExtendedEventHook(),
    selectionDrag: createExtendedEventHook(),
    selectionDragStop: createExtendedEventHook(),
    selectionContextMenu: createExtendedEventHook(),
    selectionStart: createExtendedEventHook(),
    selectionEnd: createExtendedEventHook(),
    viewportChangeStart: createExtendedEventHook(),
    viewportChange: createExtendedEventHook(),
    viewportChangeEnd: createExtendedEventHook(),
    paneScroll: createExtendedEventHook(),
    paneClick: createExtendedEventHook(),
    paneContextMenu: createExtendedEventHook(),
    paneMouseEnter: createExtendedEventHook(),
    paneMouseMove: createExtendedEventHook(),
    paneMouseLeave: createExtendedEventHook(),
    edgeContextMenu: createExtendedEventHook(),
    edgeMouseEnter: createExtendedEventHook(),
    edgeMouseMove: createExtendedEventHook(),
    edgeMouseLeave: createExtendedEventHook(),
    edgeDoubleClick: createExtendedEventHook(),
    edgeClick: createExtendedEventHook(),
    edgeUpdateStart: createExtendedEventHook(),
    edgeUpdate: createExtendedEventHook(),
    edgeUpdateEnd: createExtendedEventHook(),
    updateNodeInternals: createExtendedEventHook(),
    error: createExtendedEventHook((err) => warn(err.message))
  };
}
function useHooks(emit, hooks) {
  (0,vue__WEBPACK_IMPORTED_MODULE_0__.onBeforeMount)(() => {
    for (const [key, value] of Object.entries(hooks.value)) {
      const listener = (data) => {
        emit(key, data);
      };
      value.fns.add(listener);
      tryOnScopeDispose(() => {
        value.off(listener);
      });
    }
  });
}
function useState() {
  return {
    vueFlowRef: null,
    viewportRef: null,
    nodes: [],
    edges: [],
    connectionLookup: /* @__PURE__ */ new Map(),
    nodeTypes: {},
    edgeTypes: {},
    initialized: false,
    dimensions: {
      width: 0,
      height: 0
    },
    viewport: { x: 0, y: 0, zoom: 1 },
    d3Zoom: null,
    d3Selection: null,
    d3ZoomHandler: null,
    minZoom: 0.5,
    maxZoom: 2,
    translateExtent: [
      [Number.NEGATIVE_INFINITY, Number.NEGATIVE_INFINITY],
      [Number.POSITIVE_INFINITY, Number.POSITIVE_INFINITY]
    ],
    nodeExtent: [
      [Number.NEGATIVE_INFINITY, Number.NEGATIVE_INFINITY],
      [Number.POSITIVE_INFINITY, Number.POSITIVE_INFINITY]
    ],
    selectionMode: SelectionMode.Full,
    paneDragging: false,
    preventScrolling: true,
    zoomOnScroll: true,
    zoomOnPinch: true,
    zoomOnDoubleClick: true,
    panOnScroll: false,
    panOnScrollSpeed: 0.5,
    panOnScrollMode: PanOnScrollMode.Free,
    paneClickDistance: 0,
    panOnDrag: true,
    edgeUpdaterRadius: 10,
    onlyRenderVisibleElements: false,
    defaultViewport: { x: 0, y: 0, zoom: 1 },
    nodesSelectionActive: false,
    userSelectionActive: false,
    userSelectionRect: null,
    defaultMarkerColor: "#b1b1b7",
    connectionLineStyle: {},
    connectionLineType: null,
    connectionLineOptions: {
      type: ConnectionLineType.Bezier,
      style: {}
    },
    connectionMode: ConnectionMode.Loose,
    connectionStartHandle: null,
    connectionEndHandle: null,
    connectionClickStartHandle: null,
    connectionPosition: { x: Number.NaN, y: Number.NaN },
    connectionRadius: 20,
    connectOnClick: true,
    connectionStatus: null,
    isValidConnection: null,
    snapGrid: [15, 15],
    snapToGrid: false,
    edgesUpdatable: false,
    edgesFocusable: true,
    nodesFocusable: true,
    nodesConnectable: true,
    nodesDraggable: true,
    nodeDragThreshold: 1,
    elementsSelectable: true,
    selectNodesOnDrag: true,
    multiSelectionActive: false,
    selectionKeyCode: "Shift",
    multiSelectionKeyCode: isMacOs() ? "Meta" : "Control",
    zoomActivationKeyCode: isMacOs() ? "Meta" : "Control",
    deleteKeyCode: "Backspace",
    panActivationKeyCode: "Space",
    hooks: createHooks(),
    applyDefault: true,
    autoConnect: false,
    fitViewOnInit: false,
    fitViewOnInitDone: false,
    noDragClassName: "nodrag",
    noWheelClassName: "nowheel",
    noPanClassName: "nopan",
    defaultEdgeOptions: void 0,
    elevateEdgesOnSelect: false,
    elevateNodesOnSelect: true,
    autoPanOnNodeDrag: true,
    autoPanOnConnect: true,
    autoPanSpeed: 15,
    disableKeyboardA11y: false,
    ariaLiveMessage: ""
  };
}
const storeOptionsToSkip = [
  "id",
  "vueFlowRef",
  "viewportRef",
  "initialized",
  "modelValue",
  "nodes",
  "edges",
  "maxZoom",
  "minZoom",
  "translateExtent",
  "hooks",
  "defaultEdgeOptions"
];
function useActions(state, nodeLookup, edgeLookup) {
  const viewportHelper = useViewportHelper(state);
  const updateNodeInternals = (ids) => {
    const updateIds = ids ?? [];
    state.hooks.updateNodeInternals.trigger(updateIds);
  };
  const getIncomers$1 = (nodeOrId) => {
    return getIncomers(nodeOrId, state.nodes, state.edges);
  };
  const getOutgoers$1 = (nodeOrId) => {
    return getOutgoers(nodeOrId, state.nodes, state.edges);
  };
  const getConnectedEdges$1 = (nodesOrId) => {
    return getConnectedEdges(nodesOrId, state.edges);
  };
  const getHandleConnections = ({ id: id2, type, nodeId }) => {
    var _a;
    return Array.from(((_a = state.connectionLookup.get(`${nodeId}-${type}-${id2 ?? null}`)) == null ? void 0 : _a.values()) ?? []);
  };
  const findNode = (id2) => {
    if (!id2) {
      return;
    }
    return nodeLookup.value.get(id2);
  };
  const findEdge = (id2) => {
    if (!id2) {
      return;
    }
    return edgeLookup.value.get(id2);
  };
  const updateNodePositions = (dragItems, changed, dragging) => {
    var _a, _b;
    const changes = [];
    for (const node of dragItems) {
      const change = {
        id: node.id,
        type: "position",
        dragging,
        from: node.from
      };
      if (changed) {
        change.position = node.position;
        if (node.parentNode) {
          const parentNode = findNode(node.parentNode);
          change.position = {
            x: change.position.x - (((_a = parentNode == null ? void 0 : parentNode.computedPosition) == null ? void 0 : _a.x) ?? 0),
            y: change.position.y - (((_b = parentNode == null ? void 0 : parentNode.computedPosition) == null ? void 0 : _b.y) ?? 0)
          };
        }
      }
      changes.push(change);
    }
    if (changes == null ? void 0 : changes.length) {
      state.hooks.nodesChange.trigger(changes);
    }
  };
  const updateNodeDimensions = (updates) => {
    if (!state.vueFlowRef) {
      return;
    }
    const viewportNode = state.vueFlowRef.querySelector(".vue-flow__transformationpane");
    if (!viewportNode) {
      return;
    }
    const style = window.getComputedStyle(viewportNode);
    const { m22: zoom2 } = new window.DOMMatrixReadOnly(style.transform);
    const changes = [];
    for (const element of updates) {
      const update = element;
      const node = findNode(update.id);
      if (node) {
        const dimensions = getDimensions(update.nodeElement);
        const doUpdate = !!(dimensions.width && dimensions.height && (node.dimensions.width !== dimensions.width || node.dimensions.height !== dimensions.height || update.forceUpdate));
        if (doUpdate) {
          const nodeBounds = update.nodeElement.getBoundingClientRect();
          node.dimensions = dimensions;
          node.handleBounds.source = getHandleBounds("source", update.nodeElement, nodeBounds, zoom2, node.id);
          node.handleBounds.target = getHandleBounds("target", update.nodeElement, nodeBounds, zoom2, node.id);
          changes.push({
            id: node.id,
            type: "dimensions",
            dimensions
          });
        }
      }
    }
    if (!state.fitViewOnInitDone && state.fitViewOnInit) {
      viewportHelper.value.fitView().then(() => {
        state.fitViewOnInitDone = true;
      });
    }
    if (changes.length) {
      state.hooks.nodesChange.trigger(changes);
    }
  };
  const elementSelectionHandler = (elements, selected) => {
    const nodeIds = /* @__PURE__ */ new Set();
    const edgeIds = /* @__PURE__ */ new Set();
    for (const element of elements) {
      if (isNode(element)) {
        nodeIds.add(element.id);
      } else if (isEdge(element)) {
        edgeIds.add(element.id);
      }
    }
    const changedNodes = getSelectionChanges(nodeLookup.value, nodeIds, true);
    const changedEdges = getSelectionChanges(edgeLookup.value, edgeIds);
    if (state.multiSelectionActive) {
      for (const nodeId of nodeIds) {
        changedNodes.push(createSelectionChange(nodeId, selected));
      }
      for (const edgeId of edgeIds) {
        changedEdges.push(createSelectionChange(edgeId, selected));
      }
    }
    if (changedNodes.length) {
      state.hooks.nodesChange.trigger(changedNodes);
    }
    if (changedEdges.length) {
      state.hooks.edgesChange.trigger(changedEdges);
    }
  };
  const addSelectedNodes = (nodes) => {
    if (state.multiSelectionActive) {
      const nodeChanges = nodes.map((node) => createSelectionChange(node.id, true));
      state.hooks.nodesChange.trigger(nodeChanges);
      return;
    }
    state.hooks.nodesChange.trigger(getSelectionChanges(nodeLookup.value, new Set(nodes.map((n) => n.id)), true));
    state.hooks.edgesChange.trigger(getSelectionChanges(edgeLookup.value));
  };
  const addSelectedEdges = (edges) => {
    if (state.multiSelectionActive) {
      const changedEdges = edges.map((edge) => createSelectionChange(edge.id, true));
      state.hooks.edgesChange.trigger(changedEdges);
      return;
    }
    state.hooks.edgesChange.trigger(getSelectionChanges(edgeLookup.value, new Set(edges.map((e) => e.id))));
    state.hooks.nodesChange.trigger(getSelectionChanges(nodeLookup.value, /* @__PURE__ */ new Set(), true));
  };
  const addSelectedElements = (elements) => {
    elementSelectionHandler(elements, true);
  };
  const removeSelectedNodes = (nodes) => {
    const nodesToUnselect = nodes || state.nodes;
    const nodeChanges = nodesToUnselect.map((n) => {
      n.selected = false;
      return createSelectionChange(n.id, false);
    });
    state.hooks.nodesChange.trigger(nodeChanges);
  };
  const removeSelectedEdges = (edges) => {
    const edgesToUnselect = edges || state.edges;
    const edgeChanges = edgesToUnselect.map((e) => {
      e.selected = false;
      return createSelectionChange(e.id, false);
    });
    state.hooks.edgesChange.trigger(edgeChanges);
  };
  const removeSelectedElements = (elements) => {
    if (!elements || !elements.length) {
      return elementSelectionHandler([], false);
    }
    const changes = elements.reduce(
      (changes2, curr) => {
        const selectionChange = createSelectionChange(curr.id, false);
        if (isNode(curr)) {
          changes2.nodes.push(selectionChange);
        } else {
          changes2.edges.push(selectionChange);
        }
        return changes2;
      },
      { nodes: [], edges: [] }
    );
    if (changes.nodes.length) {
      state.hooks.nodesChange.trigger(changes.nodes);
    }
    if (changes.edges.length) {
      state.hooks.edgesChange.trigger(changes.edges);
    }
  };
  const setMinZoom = (minZoom) => {
    var _a;
    (_a = state.d3Zoom) == null ? void 0 : _a.scaleExtent([minZoom, state.maxZoom]);
    state.minZoom = minZoom;
  };
  const setMaxZoom = (maxZoom) => {
    var _a;
    (_a = state.d3Zoom) == null ? void 0 : _a.scaleExtent([state.minZoom, maxZoom]);
    state.maxZoom = maxZoom;
  };
  const setTranslateExtent = (translateExtent) => {
    var _a;
    (_a = state.d3Zoom) == null ? void 0 : _a.translateExtent(translateExtent);
    state.translateExtent = translateExtent;
  };
  const setNodeExtent = (nodeExtent) => {
    state.nodeExtent = nodeExtent;
    updateNodeInternals();
  };
  const setPaneClickDistance = (clickDistance) => {
    var _a;
    (_a = state.d3Zoom) == null ? void 0 : _a.clickDistance(clickDistance);
  };
  const setInteractive = (isInteractive) => {
    state.nodesDraggable = isInteractive;
    state.nodesConnectable = isInteractive;
    state.elementsSelectable = isInteractive;
  };
  const setNodes = (nodes) => {
    const nextNodes = nodes instanceof Function ? nodes(state.nodes) : nodes;
    if (!state.initialized && !nextNodes.length) {
      return;
    }
    state.nodes = createGraphNodes(nextNodes, findNode, state.hooks.error.trigger);
  };
  const setEdges = (edges) => {
    const nextEdges = edges instanceof Function ? edges(state.edges) : edges;
    if (!state.initialized && !nextEdges.length) {
      return;
    }
    const validEdges = createGraphEdges(
      nextEdges,
      state.isValidConnection,
      findNode,
      findEdge,
      state.hooks.error.trigger,
      state.defaultEdgeOptions,
      state.nodes,
      state.edges
    );
    updateConnectionLookup(state.connectionLookup, edgeLookup.value, validEdges);
    state.edges = validEdges;
  };
  const setElements = (elements) => {
    const nextElements = elements instanceof Function ? elements([...state.nodes, ...state.edges]) : elements;
    if (!state.initialized && !nextElements.length) {
      return;
    }
    setNodes(nextElements.filter(isNode));
    setEdges(nextElements.filter(isEdge));
  };
  const addNodes = (nodes) => {
    let nextNodes = nodes instanceof Function ? nodes(state.nodes) : nodes;
    nextNodes = Array.isArray(nextNodes) ? nextNodes : [nextNodes];
    const graphNodes = createGraphNodes(nextNodes, findNode, state.hooks.error.trigger);
    const changes = [];
    for (const node of graphNodes) {
      changes.push(createAdditionChange(node));
    }
    if (changes.length) {
      state.hooks.nodesChange.trigger(changes);
    }
  };
  const addEdges = (params) => {
    let nextEdges = params instanceof Function ? params(state.edges) : params;
    nextEdges = Array.isArray(nextEdges) ? nextEdges : [nextEdges];
    const validEdges = createGraphEdges(
      nextEdges,
      state.isValidConnection,
      findNode,
      findEdge,
      state.hooks.error.trigger,
      state.defaultEdgeOptions,
      state.nodes,
      state.edges
    );
    const changes = [];
    for (const edge of validEdges) {
      changes.push(createAdditionChange(edge));
    }
    if (changes.length) {
      state.hooks.edgesChange.trigger(changes);
    }
  };
  const removeNodes = (nodes, removeConnectedEdges = true, removeChildren = false) => {
    const nextNodes = nodes instanceof Function ? nodes(state.nodes) : nodes;
    const nodesToRemove = Array.isArray(nextNodes) ? nextNodes : [nextNodes];
    const nodeChanges = [];
    const edgeChanges = [];
    function createEdgeRemovalChanges(nodes2) {
      const connectedEdges = getConnectedEdges$1(nodes2);
      for (const edge of connectedEdges) {
        if (isDef(edge.deletable) ? edge.deletable : true) {
          edgeChanges.push(createEdgeRemoveChange(edge.id, edge.source, edge.target, edge.sourceHandle, edge.targetHandle));
        }
      }
    }
    function createChildrenRemovalChanges(id2) {
      const children2 = [];
      for (const node of state.nodes) {
        if (node.parentNode === id2) {
          children2.push(node);
        }
      }
      if (children2.length) {
        for (const child of children2) {
          nodeChanges.push(createNodeRemoveChange(child.id));
        }
        if (removeConnectedEdges) {
          createEdgeRemovalChanges(children2);
        }
        for (const child of children2) {
          createChildrenRemovalChanges(child.id);
        }
      }
    }
    for (const item of nodesToRemove) {
      const currNode = typeof item === "string" ? findNode(item) : item;
      if (!currNode) {
        continue;
      }
      if (isDef(currNode.deletable) && !currNode.deletable) {
        continue;
      }
      nodeChanges.push(createNodeRemoveChange(currNode.id));
      if (removeConnectedEdges) {
        createEdgeRemovalChanges([currNode]);
      }
      if (removeChildren) {
        createChildrenRemovalChanges(currNode.id);
      }
    }
    if (edgeChanges.length) {
      state.hooks.edgesChange.trigger(edgeChanges);
    }
    if (nodeChanges.length) {
      state.hooks.nodesChange.trigger(nodeChanges);
    }
  };
  const removeEdges = (edges) => {
    const nextEdges = edges instanceof Function ? edges(state.edges) : edges;
    const edgesToRemove = Array.isArray(nextEdges) ? nextEdges : [nextEdges];
    const changes = [];
    for (const item of edgesToRemove) {
      const currEdge = typeof item === "string" ? findEdge(item) : item;
      if (!currEdge) {
        continue;
      }
      if (isDef(currEdge.deletable) && !currEdge.deletable) {
        continue;
      }
      changes.push(
        createEdgeRemoveChange(
          typeof item === "string" ? item : item.id,
          currEdge.source,
          currEdge.target,
          currEdge.sourceHandle,
          currEdge.targetHandle
        )
      );
    }
    state.hooks.edgesChange.trigger(changes);
  };
  const updateEdge2 = (oldEdge, newConnection, shouldReplaceId = true) => {
    const prevEdge = findEdge(oldEdge.id);
    if (!prevEdge) {
      return false;
    }
    const prevEdgeIndex = state.edges.indexOf(prevEdge);
    const newEdge = updateEdgeAction(oldEdge, newConnection, prevEdge, shouldReplaceId, state.hooks.error.trigger);
    if (newEdge) {
      const [validEdge] = createGraphEdges(
        [newEdge],
        state.isValidConnection,
        findNode,
        findEdge,
        state.hooks.error.trigger,
        state.defaultEdgeOptions,
        state.nodes,
        state.edges
      );
      state.edges = state.edges.map((edge, index) => index === prevEdgeIndex ? validEdge : edge);
      updateConnectionLookup(state.connectionLookup, edgeLookup.value, [validEdge]);
      return validEdge;
    }
    return false;
  };
  const updateEdgeData = (id2, dataUpdate, options = { replace: false }) => {
    const edge = findEdge(id2);
    if (!edge) {
      return;
    }
    const nextData = typeof dataUpdate === "function" ? dataUpdate(edge) : dataUpdate;
    edge.data = options.replace ? nextData : { ...edge.data, ...nextData };
  };
  const applyNodeChanges2 = (changes) => {
    return applyChanges(changes, state.nodes);
  };
  const applyEdgeChanges2 = (changes) => {
    const changedEdges = applyChanges(changes, state.edges);
    updateConnectionLookup(state.connectionLookup, edgeLookup.value, changedEdges);
    return changedEdges;
  };
  const updateNode = (id2, nodeUpdate, options = { replace: false }) => {
    const node = findNode(id2);
    if (!node) {
      return;
    }
    const nextNode = typeof nodeUpdate === "function" ? nodeUpdate(node) : nodeUpdate;
    if (options.replace) {
      state.nodes.splice(state.nodes.indexOf(node), 1, nextNode);
    } else {
      Object.assign(node, nextNode);
    }
  };
  const updateNodeData = (id2, dataUpdate, options = { replace: false }) => {
    const node = findNode(id2);
    if (!node) {
      return;
    }
    const nextData = typeof dataUpdate === "function" ? dataUpdate(node) : dataUpdate;
    node.data = options.replace ? nextData : { ...node.data, ...nextData };
  };
  const startConnection = (startHandle, position, isClick = false) => {
    if (isClick) {
      state.connectionClickStartHandle = startHandle;
    } else {
      state.connectionStartHandle = startHandle;
    }
    state.connectionEndHandle = null;
    state.connectionStatus = null;
    if (position) {
      state.connectionPosition = position;
    }
  };
  const updateConnection = (position, result = null, status = null) => {
    if (state.connectionStartHandle) {
      state.connectionPosition = position;
      state.connectionEndHandle = result;
      state.connectionStatus = status;
    }
  };
  const endConnection = (event, isClick) => {
    state.connectionPosition = { x: Number.NaN, y: Number.NaN };
    state.connectionEndHandle = null;
    state.connectionStatus = null;
    if (isClick) {
      state.connectionClickStartHandle = null;
    } else {
      state.connectionStartHandle = null;
    }
  };
  const getNodeRect = (nodeOrRect) => {
    const isRectObj = isRect(nodeOrRect);
    const node = isRectObj ? null : isGraphNode(nodeOrRect) ? nodeOrRect : findNode(nodeOrRect.id);
    if (!isRectObj && !node) {
      return [null, null, isRectObj];
    }
    const nodeRect = isRectObj ? nodeOrRect : nodeToRect(node);
    return [nodeRect, node, isRectObj];
  };
  const getIntersectingNodes = (nodeOrRect, partially = true, nodes = state.nodes) => {
    const [nodeRect, node, isRect2] = getNodeRect(nodeOrRect);
    if (!nodeRect) {
      return [];
    }
    const intersections = [];
    for (const n of nodes || state.nodes) {
      if (!isRect2 && (n.id === node.id || !n.computedPosition)) {
        continue;
      }
      const currNodeRect = nodeToRect(n);
      const overlappingArea = getOverlappingArea(currNodeRect, nodeRect);
      const partiallyVisible = partially && overlappingArea > 0;
      if (partiallyVisible || overlappingArea >= Number(nodeRect.width) * Number(nodeRect.height)) {
        intersections.push(n);
      }
    }
    return intersections;
  };
  const isNodeIntersecting = (nodeOrRect, area, partially = true) => {
    const [nodeRect] = getNodeRect(nodeOrRect);
    if (!nodeRect) {
      return false;
    }
    const overlappingArea = getOverlappingArea(nodeRect, area);
    const partiallyVisible = partially && overlappingArea > 0;
    return partiallyVisible || overlappingArea >= Number(nodeRect.width) * Number(nodeRect.height);
  };
  const panBy = (delta) => {
    const { viewport, dimensions, d3Zoom, d3Selection, translateExtent } = state;
    if (!d3Zoom || !d3Selection || !delta.x && !delta.y) {
      return false;
    }
    const nextTransform = identity.translate(viewport.x + delta.x, viewport.y + delta.y).scale(viewport.zoom);
    const extent = [
      [0, 0],
      [dimensions.width, dimensions.height]
    ];
    const constrainedTransform = d3Zoom.constrain()(nextTransform, extent, translateExtent);
    const transformChanged = state.viewport.x !== constrainedTransform.x || state.viewport.y !== constrainedTransform.y || state.viewport.zoom !== constrainedTransform.k;
    d3Zoom.transform(d3Selection, constrainedTransform);
    return transformChanged;
  };
  const setState = (options) => {
    const opts = options instanceof Function ? options(state) : options;
    const exclude = [
      "d3Zoom",
      "d3Selection",
      "d3ZoomHandler",
      "viewportRef",
      "vueFlowRef",
      "dimensions",
      "hooks"
    ];
    if (isDef(opts.defaultEdgeOptions)) {
      state.defaultEdgeOptions = opts.defaultEdgeOptions;
    }
    const elements = opts.modelValue || opts.nodes || opts.edges ? [] : void 0;
    if (elements) {
      if (opts.modelValue) {
        elements.push(...opts.modelValue);
      }
      if (opts.nodes) {
        elements.push(...opts.nodes);
      }
      if (opts.edges) {
        elements.push(...opts.edges);
      }
      setElements(elements);
    }
    const setSkippedOptions = () => {
      if (isDef(opts.maxZoom)) {
        setMaxZoom(opts.maxZoom);
      }
      if (isDef(opts.minZoom)) {
        setMinZoom(opts.minZoom);
      }
      if (isDef(opts.translateExtent)) {
        setTranslateExtent(opts.translateExtent);
      }
    };
    for (const o of Object.keys(opts)) {
      const key = o;
      const option = opts[key];
      if (![...storeOptionsToSkip, ...exclude].includes(key) && isDef(option)) {
        state[key] = option;
      }
    }
    until(() => state.d3Zoom).not.toBeNull().then(setSkippedOptions);
    if (!state.initialized) {
      state.initialized = true;
    }
  };
  const toObject = () => {
    const nodes = [];
    const edges = [];
    for (const node of state.nodes) {
      const {
        computedPosition: _,
        handleBounds: __,
        selected: ___,
        dimensions: ____,
        isParent: _____,
        resizing: ______,
        dragging: _______,
        events: _________,
        ...rest
      } = node;
      nodes.push(rest);
    }
    for (const edge of state.edges) {
      const { selected: _, sourceNode: __, targetNode: ___, events: ____, ...rest } = edge;
      edges.push(rest);
    }
    return JSON.parse(
      JSON.stringify({
        nodes,
        edges,
        position: [state.viewport.x, state.viewport.y],
        zoom: state.viewport.zoom,
        viewport: state.viewport
      })
    );
  };
  const fromObject = (obj) => {
    return new Promise((resolve) => {
      const { nodes, edges, position, zoom: zoom2, viewport } = obj;
      if (nodes) {
        setNodes(nodes);
      }
      if (edges) {
        setEdges(edges);
      }
      if ((viewport == null ? void 0 : viewport.x) && (viewport == null ? void 0 : viewport.y) || position) {
        const x = (viewport == null ? void 0 : viewport.x) || position[0];
        const y = (viewport == null ? void 0 : viewport.y) || position[1];
        const nextZoom = (viewport == null ? void 0 : viewport.zoom) || zoom2 || state.viewport.zoom;
        return until(() => viewportHelper.value.viewportInitialized).toBe(true).then(() => {
          viewportHelper.value.setViewport({
            x,
            y,
            zoom: nextZoom
          }).then(() => {
            resolve(true);
          });
        });
      } else {
        resolve(true);
      }
    });
  };
  const $reset = () => {
    const resetState = useState();
    state.edges = [];
    state.nodes = [];
    if (state.d3Zoom && state.d3Selection) {
      const updatedTransform = identity.translate(resetState.defaultViewport.x ?? 0, resetState.defaultViewport.y ?? 0).scale(clamp(resetState.defaultViewport.zoom ?? 1, resetState.minZoom, resetState.maxZoom));
      const bbox = state.viewportRef.getBoundingClientRect();
      const extent = [
        [0, 0],
        [bbox.width, bbox.height]
      ];
      const constrainedTransform = state.d3Zoom.constrain()(updatedTransform, extent, resetState.translateExtent);
      state.d3Zoom.transform(state.d3Selection, constrainedTransform);
    }
    setState(resetState);
  };
  return {
    updateNodePositions,
    updateNodeDimensions,
    setElements,
    setNodes,
    setEdges,
    addNodes,
    addEdges,
    removeNodes,
    removeEdges,
    findNode,
    findEdge,
    updateEdge: updateEdge2,
    updateEdgeData,
    updateNode,
    updateNodeData,
    applyEdgeChanges: applyEdgeChanges2,
    applyNodeChanges: applyNodeChanges2,
    addSelectedElements,
    addSelectedNodes,
    addSelectedEdges,
    setMinZoom,
    setMaxZoom,
    setTranslateExtent,
    setNodeExtent,
    setPaneClickDistance,
    removeSelectedElements,
    removeSelectedNodes,
    removeSelectedEdges,
    startConnection,
    updateConnection,
    endConnection,
    setInteractive,
    setState,
    getIntersectingNodes,
    getIncomers: getIncomers$1,
    getOutgoers: getOutgoers$1,
    getConnectedEdges: getConnectedEdges$1,
    getHandleConnections,
    isNodeIntersecting,
    panBy,
    fitView: (params) => viewportHelper.value.fitView(params),
    zoomIn: (transitionOpts) => viewportHelper.value.zoomIn(transitionOpts),
    zoomOut: (transitionOpts) => viewportHelper.value.zoomOut(transitionOpts),
    zoomTo: (zoomLevel, transitionOpts) => viewportHelper.value.zoomTo(zoomLevel, transitionOpts),
    setViewport: (params, transitionOpts) => viewportHelper.value.setViewport(params, transitionOpts),
    setTransform: (params, transitionOpts) => viewportHelper.value.setTransform(params, transitionOpts),
    getViewport: () => viewportHelper.value.getViewport(),
    getTransform: () => viewportHelper.value.getTransform(),
    setCenter: (x, y, opts) => viewportHelper.value.setCenter(x, y, opts),
    fitBounds: (params, opts) => viewportHelper.value.fitBounds(params, opts),
    project: (params) => viewportHelper.value.project(params),
    screenToFlowCoordinate: (params) => viewportHelper.value.screenToFlowCoordinate(params),
    flowToScreenCoordinate: (params) => viewportHelper.value.flowToScreenCoordinate(params),
    toObject,
    fromObject,
    updateNodeInternals,
    viewportHelper,
    $reset,
    $destroy: () => {
    }
  };
}
const _hoisted_1$9 = ["data-id", "data-handleid", "data-nodeid", "data-handlepos"];
const __default__$f = {
  name: "Handle",
  compatConfig: { MODE: 3 }
};
const _sfc_main$f = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$f,
  props: {
    id: { default: null },
    type: {},
    position: { default: () => Position.Top },
    isValidConnection: { type: Function },
    connectable: { type: [Boolean, Number, String, Function], default: void 0 },
    connectableStart: { type: Boolean, default: true },
    connectableEnd: { type: Boolean, default: true }
  },
  setup(__props, { expose: __expose }) {
    const props = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createPropsRestProxy)(__props, ["position", "connectable", "connectableStart", "connectableEnd", "id"]);
    const type = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => props.type ?? "source");
    const isValidConnection = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => props.isValidConnection ?? null);
    const {
      id: flowId,
      connectionStartHandle,
      connectionClickStartHandle,
      connectionEndHandle,
      vueFlowRef,
      nodesConnectable,
      noDragClassName,
      noPanClassName
    } = useVueFlow();
    const { id: nodeId, node, nodeEl, connectedEdges } = useNode();
    const handle = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)();
    const isConnectableStart = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => typeof __props.connectableStart !== "undefined" ? __props.connectableStart : true);
    const isConnectableEnd = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => typeof __props.connectableEnd !== "undefined" ? __props.connectableEnd : true);
    const isConnecting = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(
      () => {
        var _a, _b, _c, _d, _e, _f;
        return ((_a = connectionStartHandle.value) == null ? void 0 : _a.nodeId) === nodeId && ((_b = connectionStartHandle.value) == null ? void 0 : _b.id) === __props.id && ((_c = connectionStartHandle.value) == null ? void 0 : _c.type) === type.value || ((_d = connectionEndHandle.value) == null ? void 0 : _d.nodeId) === nodeId && ((_e = connectionEndHandle.value) == null ? void 0 : _e.id) === __props.id && ((_f = connectionEndHandle.value) == null ? void 0 : _f.type) === type.value;
      }
    );
    const isClickConnecting = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(
      () => {
        var _a, _b, _c;
        return ((_a = connectionClickStartHandle.value) == null ? void 0 : _a.nodeId) === nodeId && ((_b = connectionClickStartHandle.value) == null ? void 0 : _b.id) === __props.id && ((_c = connectionClickStartHandle.value) == null ? void 0 : _c.type) === type.value;
      }
    );
    const { handlePointerDown, handleClick } = useHandle({
      nodeId,
      handleId: __props.id,
      isValidConnection,
      type
    });
    const isConnectable = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
      if (typeof __props.connectable === "string" && __props.connectable === "single") {
        return !connectedEdges.value.some((edge) => {
          const id2 = edge[`${type.value}Handle`];
          if (edge[type.value] !== nodeId) {
            return false;
          }
          return id2 ? id2 === __props.id : true;
        });
      }
      if (typeof __props.connectable === "number") {
        return connectedEdges.value.filter((edge) => {
          const id2 = edge[`${type.value}Handle`];
          if (edge[type.value] !== nodeId) {
            return false;
          }
          return id2 ? id2 === __props.id : true;
        }).length < __props.connectable;
      }
      if (typeof __props.connectable === "function") {
        return __props.connectable(node, connectedEdges.value);
      }
      return isDef(__props.connectable) ? __props.connectable : nodesConnectable.value;
    });
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(() => {
      var _a;
      if (!node.dimensions.width || !node.dimensions.height) {
        return;
      }
      const existingBounds = (_a = node.handleBounds[type.value]) == null ? void 0 : _a.find((b) => b.id === __props.id);
      if (!vueFlowRef.value || existingBounds) {
        return;
      }
      const viewportNode = vueFlowRef.value.querySelector(".vue-flow__transformationpane");
      if (!nodeEl.value || !handle.value || !viewportNode || !__props.id) {
        return;
      }
      const nodeBounds = nodeEl.value.getBoundingClientRect();
      const handleBounds = handle.value.getBoundingClientRect();
      const style = window.getComputedStyle(viewportNode);
      const { m22: zoom2 } = new window.DOMMatrixReadOnly(style.transform);
      const nextBounds = {
        id: __props.id,
        position: __props.position,
        x: (handleBounds.left - nodeBounds.left) / zoom2,
        y: (handleBounds.top - nodeBounds.top) / zoom2,
        type: type.value,
        nodeId,
        ...getDimensions(handle.value)
      };
      node.handleBounds[type.value] = [...node.handleBounds[type.value] ?? [], nextBounds];
    });
    function onPointerDown(event) {
      const isMouseTriggered = isMouseEvent(event);
      if (isConnectable.value && isConnectableStart.value && (isMouseTriggered && event.button === 0 || !isMouseTriggered)) {
        handlePointerDown(event);
      }
    }
    function onClick(event) {
      if (!nodeId || !connectionClickStartHandle.value && !isConnectableStart.value) {
        return;
      }
      if (isConnectable.value) {
        handleClick(event);
      }
    }
    __expose({
      handleClick,
      handlePointerDown,
      onClick,
      onPointerDown
    });
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
        ref_key: "handle",
        ref: handle,
        "data-id": `${(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(flowId)}-${(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(nodeId)}-${__props.id}-${type.value}`,
        "data-handleid": __props.id,
        "data-nodeid": (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(nodeId),
        "data-handlepos": _ctx.position,
        class: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(["vue-flow__handle", [
          `vue-flow__handle-${_ctx.position}`,
          `vue-flow__handle-${__props.id}`,
          (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(noDragClassName),
          (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(noPanClassName),
          type.value,
          {
            connectable: isConnectable.value,
            connecting: isClickConnecting.value,
            connectablestart: isConnectableStart.value,
            connectableend: isConnectableEnd.value,
            connectionindicator: isConnectable.value && (isConnectableStart.value && !isConnecting.value || isConnectableEnd.value && isConnecting.value)
          }
        ]]),
        onMousedown: onPointerDown,
        onTouchstartPassive: onPointerDown,
        onClick
      }, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderSlot)(_ctx.$slots, "default", { id: _ctx.id })
      ], 42, _hoisted_1$9);
    };
  }
});
const DefaultNode = function({
  sourcePosition = Position.Bottom,
  targetPosition = Position.Top,
  label: _label,
  connectable = true,
  isValidTargetPos,
  isValidSourcePos,
  data
}) {
  const label = data.label ?? _label;
  return [
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(_sfc_main$f, { type: "target", position: targetPosition, connectable, isValidConnection: isValidTargetPos }),
    typeof label !== "string" && label ? (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(label) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, [label]),
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(_sfc_main$f, { type: "source", position: sourcePosition, connectable, isValidConnection: isValidSourcePos })
  ];
};
DefaultNode.props = ["sourcePosition", "targetPosition", "label", "isValidTargetPos", "isValidSourcePos", "connectable", "data"];
DefaultNode.inheritAttrs = false;
DefaultNode.compatConfig = { MODE: 3 };
const DefaultNode$1 = DefaultNode;
const OutputNode = function({
  targetPosition = Position.Top,
  label: _label,
  connectable = true,
  isValidTargetPos,
  data
}) {
  const label = data.label ?? _label;
  return [
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(_sfc_main$f, { type: "target", position: targetPosition, connectable, isValidConnection: isValidTargetPos }),
    typeof label !== "string" && label ? (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(label) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, [label])
  ];
};
OutputNode.props = ["targetPosition", "label", "isValidTargetPos", "connectable", "data"];
OutputNode.inheritAttrs = false;
OutputNode.compatConfig = { MODE: 3 };
const OutputNode$1 = OutputNode;
const InputNode = function({
  sourcePosition = Position.Bottom,
  label: _label,
  connectable = true,
  isValidSourcePos,
  data
}) {
  const label = data.label ?? _label;
  return [
    typeof label !== "string" && label ? (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(label) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, [label]),
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(_sfc_main$f, { type: "source", position: sourcePosition, connectable, isValidConnection: isValidSourcePos })
  ];
};
InputNode.props = ["sourcePosition", "label", "isValidSourcePos", "connectable", "data"];
InputNode.inheritAttrs = false;
InputNode.compatConfig = { MODE: 3 };
const InputNode$1 = InputNode;
const _hoisted_1$8 = ["transform"];
const _hoisted_2$2 = ["width", "height", "x", "y", "rx", "ry"];
const _hoisted_3$1 = ["y"];
const __default__$e = {
  name: "EdgeText",
  compatConfig: { MODE: 3 }
};
const _sfc_main$e = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$e,
  props: {
    x: {},
    y: {},
    label: {},
    labelStyle: { default: () => ({}) },
    labelShowBg: { type: Boolean, default: true },
    labelBgStyle: { default: () => ({}) },
    labelBgPadding: { default: () => [2, 4] },
    labelBgBorderRadius: { default: 2 }
  },
  setup(__props) {
    const box = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)({ x: 0, y: 0, width: 0, height: 0 });
    const el = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    const transform = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => `translate(${__props.x - box.value.width / 2} ${__props.y - box.value.height / 2})`);
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(getBox);
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)([() => __props.x, () => __props.y, el, () => __props.label], getBox);
    function getBox() {
      if (!el.value) {
        return;
      }
      const nextBox = el.value.getBBox();
      if (nextBox.width !== box.value.width || nextBox.height !== box.value.height) {
        box.value = nextBox;
      }
    }
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("g", {
        transform: transform.value,
        class: "vue-flow__edge-textwrapper"
      }, [
        _ctx.labelShowBg ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("rect", {
          key: 0,
          class: "vue-flow__edge-textbg",
          width: `${box.value.width + 2 * _ctx.labelBgPadding[0]}px`,
          height: `${box.value.height + 2 * _ctx.labelBgPadding[1]}px`,
          x: -_ctx.labelBgPadding[0],
          y: -_ctx.labelBgPadding[1],
          style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)(_ctx.labelBgStyle),
          rx: _ctx.labelBgBorderRadius,
          ry: _ctx.labelBgBorderRadius
        }, null, 12, _hoisted_2$2)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("", true),
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("text", (0,vue__WEBPACK_IMPORTED_MODULE_0__.mergeProps)(_ctx.$attrs, {
          ref_key: "el",
          ref: el,
          class: "vue-flow__edge-text",
          y: box.value.height / 2,
          dy: "0.3em",
          style: _ctx.labelStyle
        }), [
          (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderSlot)(_ctx.$slots, "default", {}, () => [
            typeof _ctx.label !== "string" ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)((0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveDynamicComponent)(_ctx.label), { key: 0 })) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, { key: 1 }, [
              (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)((0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.label), 1)
            ], 64))
          ])
        ], 16, _hoisted_3$1)
      ], 8, _hoisted_1$8);
    };
  }
});
const _hoisted_1$7 = ["id", "d", "marker-end", "marker-start"];
const _hoisted_2$1 = ["d", "stroke-width"];
const __default__$d = {
  name: "BaseEdge",
  inheritAttrs: false,
  compatConfig: { MODE: 3 }
};
const _sfc_main$d = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$d,
  props: {
    id: {},
    labelX: {},
    labelY: {},
    path: {},
    label: {},
    markerStart: {},
    markerEnd: {},
    interactionWidth: { default: 20 },
    labelStyle: {},
    labelShowBg: { type: Boolean },
    labelBgStyle: {},
    labelBgPadding: {},
    labelBgBorderRadius: {}
  },
  setup(__props, { expose: __expose }) {
    const pathEl = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    const interactionEl = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    const labelEl = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    const attrs = (0,vue__WEBPACK_IMPORTED_MODULE_0__.useAttrs)();
    __expose({
      pathEl,
      interactionEl,
      labelEl
    });
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("path", (0,vue__WEBPACK_IMPORTED_MODULE_0__.mergeProps)((0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(attrs), {
          id: _ctx.id,
          ref_key: "pathEl",
          ref: pathEl,
          d: _ctx.path,
          class: "vue-flow__edge-path",
          "marker-end": _ctx.markerEnd,
          "marker-start": _ctx.markerStart
        }), null, 16, _hoisted_1$7),
        _ctx.interactionWidth ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("path", {
          key: 0,
          ref_key: "interactionEl",
          ref: interactionEl,
          fill: "none",
          d: _ctx.path,
          "stroke-width": _ctx.interactionWidth,
          "stroke-opacity": 0,
          class: "vue-flow__edge-interaction"
        }, null, 8, _hoisted_2$1)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("", true),
        _ctx.label && _ctx.labelX && _ctx.labelY ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_sfc_main$e, {
          key: 1,
          ref_key: "labelEl",
          ref: labelEl,
          x: _ctx.labelX,
          y: _ctx.labelY,
          label: _ctx.label,
          "label-show-bg": _ctx.labelShowBg,
          "label-bg-style": _ctx.labelBgStyle,
          "label-bg-padding": _ctx.labelBgPadding,
          "label-bg-border-radius": _ctx.labelBgBorderRadius,
          "label-style": _ctx.labelStyle
        }, null, 8, ["x", "y", "label", "label-show-bg", "label-bg-style", "label-bg-padding", "label-bg-border-radius", "label-style"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("", true)
      ], 64);
    };
  }
});
function getSimpleEdgeCenter({
  sourceX,
  sourceY,
  targetX,
  targetY
}) {
  const xOffset = Math.abs(targetX - sourceX) / 2;
  const centerX = targetX < sourceX ? targetX + xOffset : targetX - xOffset;
  const yOffset = Math.abs(targetY - sourceY) / 2;
  const centerY = targetY < sourceY ? targetY + yOffset : targetY - yOffset;
  return [centerX, centerY, xOffset, yOffset];
}
function getBezierEdgeCenter({
  sourceX,
  sourceY,
  targetX,
  targetY,
  sourceControlX,
  sourceControlY,
  targetControlX,
  targetControlY
}) {
  const centerX = sourceX * 0.125 + sourceControlX * 0.375 + targetControlX * 0.375 + targetX * 0.125;
  const centerY = sourceY * 0.125 + sourceControlY * 0.375 + targetControlY * 0.375 + targetY * 0.125;
  const offsetX = Math.abs(centerX - sourceX);
  const offsetY = Math.abs(centerY - sourceY);
  return [centerX, centerY, offsetX, offsetY];
}
function calculateControlOffset(distance2, curvature) {
  if (distance2 >= 0) {
    return 0.5 * distance2;
  } else {
    return curvature * 25 * Math.sqrt(-distance2);
  }
}
function getControlWithCurvature({ pos, x1, y1, x2, y2, c }) {
  let ctX, ctY;
  switch (pos) {
    case Position.Left:
      ctX = x1 - calculateControlOffset(x1 - x2, c);
      ctY = y1;
      break;
    case Position.Right:
      ctX = x1 + calculateControlOffset(x2 - x1, c);
      ctY = y1;
      break;
    case Position.Top:
      ctX = x1;
      ctY = y1 - calculateControlOffset(y1 - y2, c);
      break;
    case Position.Bottom:
      ctX = x1;
      ctY = y1 + calculateControlOffset(y2 - y1, c);
      break;
  }
  return [ctX, ctY];
}
function getBezierPath(bezierPathParams) {
  const {
    sourceX,
    sourceY,
    sourcePosition = Position.Bottom,
    targetX,
    targetY,
    targetPosition = Position.Top,
    curvature = 0.25
  } = bezierPathParams;
  const [sourceControlX, sourceControlY] = getControlWithCurvature({
    pos: sourcePosition,
    x1: sourceX,
    y1: sourceY,
    x2: targetX,
    y2: targetY,
    c: curvature
  });
  const [targetControlX, targetControlY] = getControlWithCurvature({
    pos: targetPosition,
    x1: targetX,
    y1: targetY,
    x2: sourceX,
    y2: sourceY,
    c: curvature
  });
  const [labelX, labelY, offsetX, offsetY] = getBezierEdgeCenter({
    sourceX,
    sourceY,
    targetX,
    targetY,
    sourceControlX,
    sourceControlY,
    targetControlX,
    targetControlY
  });
  return [
    `M${sourceX},${sourceY} C${sourceControlX},${sourceControlY} ${targetControlX},${targetControlY} ${targetX},${targetY}`,
    labelX,
    labelY,
    offsetX,
    offsetY
  ];
}
function getControl({ pos, x1, y1, x2, y2 }) {
  let ctX, ctY;
  switch (pos) {
    case Position.Left:
    case Position.Right:
      ctX = 0.5 * (x1 + x2);
      ctY = y1;
      break;
    case Position.Top:
    case Position.Bottom:
      ctX = x1;
      ctY = 0.5 * (y1 + y2);
      break;
  }
  return [ctX, ctY];
}
function getSimpleBezierPath(simpleBezierPathParams) {
  const {
    sourceX,
    sourceY,
    sourcePosition = Position.Bottom,
    targetX,
    targetY,
    targetPosition = Position.Top
  } = simpleBezierPathParams;
  const [sourceControlX, sourceControlY] = getControl({
    pos: sourcePosition,
    x1: sourceX,
    y1: sourceY,
    x2: targetX,
    y2: targetY
  });
  const [targetControlX, targetControlY] = getControl({
    pos: targetPosition,
    x1: targetX,
    y1: targetY,
    x2: sourceX,
    y2: sourceY
  });
  const [centerX, centerY, offsetX, offsetY] = getBezierEdgeCenter({
    sourceX,
    sourceY,
    targetX,
    targetY,
    sourceControlX,
    sourceControlY,
    targetControlX,
    targetControlY
  });
  return [
    `M${sourceX},${sourceY} C${sourceControlX},${sourceControlY} ${targetControlX},${targetControlY} ${targetX},${targetY}`,
    centerX,
    centerY,
    offsetX,
    offsetY
  ];
}
const handleDirections = {
  [Position.Left]: { x: -1, y: 0 },
  [Position.Right]: { x: 1, y: 0 },
  [Position.Top]: { x: 0, y: -1 },
  [Position.Bottom]: { x: 0, y: 1 }
};
function getDirection({
  source,
  sourcePosition = Position.Bottom,
  target
}) {
  if (sourcePosition === Position.Left || sourcePosition === Position.Right) {
    return source.x < target.x ? { x: 1, y: 0 } : { x: -1, y: 0 };
  }
  return source.y < target.y ? { x: 0, y: 1 } : { x: 0, y: -1 };
}
function distance(a, b) {
  return Math.sqrt((b.x - a.x) ** 2 + (b.y - a.y) ** 2);
}
function getPoints({
  source,
  sourcePosition = Position.Bottom,
  target,
  targetPosition = Position.Top,
  center,
  offset
}) {
  const sourceDir = handleDirections[sourcePosition];
  const targetDir = handleDirections[targetPosition];
  const sourceGapped = { x: source.x + sourceDir.x * offset, y: source.y + sourceDir.y * offset };
  const targetGapped = { x: target.x + targetDir.x * offset, y: target.y + targetDir.y * offset };
  const dir = getDirection({
    source: sourceGapped,
    sourcePosition,
    target: targetGapped
  });
  const dirAccessor = dir.x !== 0 ? "x" : "y";
  const currDir = dir[dirAccessor];
  let points;
  let centerX, centerY;
  const sourceGapOffset = { x: 0, y: 0 };
  const targetGapOffset = { x: 0, y: 0 };
  const [defaultCenterX, defaultCenterY, defaultOffsetX, defaultOffsetY] = getSimpleEdgeCenter({
    sourceX: source.x,
    sourceY: source.y,
    targetX: target.x,
    targetY: target.y
  });
  if (sourceDir[dirAccessor] * targetDir[dirAccessor] === -1) {
    centerX = center.x ?? defaultCenterX;
    centerY = center.y ?? defaultCenterY;
    const verticalSplit = [
      { x: centerX, y: sourceGapped.y },
      { x: centerX, y: targetGapped.y }
    ];
    const horizontalSplit = [
      { x: sourceGapped.x, y: centerY },
      { x: targetGapped.x, y: centerY }
    ];
    if (sourceDir[dirAccessor] === currDir) {
      points = dirAccessor === "x" ? verticalSplit : horizontalSplit;
    } else {
      points = dirAccessor === "x" ? horizontalSplit : verticalSplit;
    }
  } else {
    const sourceTarget = [{ x: sourceGapped.x, y: targetGapped.y }];
    const targetSource = [{ x: targetGapped.x, y: sourceGapped.y }];
    if (dirAccessor === "x") {
      points = sourceDir.x === currDir ? targetSource : sourceTarget;
    } else {
      points = sourceDir.y === currDir ? sourceTarget : targetSource;
    }
    if (sourcePosition === targetPosition) {
      const diff = Math.abs(source[dirAccessor] - target[dirAccessor]);
      if (diff <= offset) {
        const gapOffset = Math.min(offset - 1, offset - diff);
        if (sourceDir[dirAccessor] === currDir) {
          sourceGapOffset[dirAccessor] = (sourceGapped[dirAccessor] > source[dirAccessor] ? -1 : 1) * gapOffset;
        } else {
          targetGapOffset[dirAccessor] = (targetGapped[dirAccessor] > target[dirAccessor] ? -1 : 1) * gapOffset;
        }
      }
    }
    if (sourcePosition !== targetPosition) {
      const dirAccessorOpposite = dirAccessor === "x" ? "y" : "x";
      const isSameDir = sourceDir[dirAccessor] === targetDir[dirAccessorOpposite];
      const sourceGtTargetOppo = sourceGapped[dirAccessorOpposite] > targetGapped[dirAccessorOpposite];
      const sourceLtTargetOppo = sourceGapped[dirAccessorOpposite] < targetGapped[dirAccessorOpposite];
      const flipSourceTarget = sourceDir[dirAccessor] === 1 && (!isSameDir && sourceGtTargetOppo || isSameDir && sourceLtTargetOppo) || sourceDir[dirAccessor] !== 1 && (!isSameDir && sourceLtTargetOppo || isSameDir && sourceGtTargetOppo);
      if (flipSourceTarget) {
        points = dirAccessor === "x" ? sourceTarget : targetSource;
      }
    }
    const sourceGapPoint = { x: sourceGapped.x + sourceGapOffset.x, y: sourceGapped.y + sourceGapOffset.y };
    const targetGapPoint = { x: targetGapped.x + targetGapOffset.x, y: targetGapped.y + targetGapOffset.y };
    const maxXDistance = Math.max(Math.abs(sourceGapPoint.x - points[0].x), Math.abs(targetGapPoint.x - points[0].x));
    const maxYDistance = Math.max(Math.abs(sourceGapPoint.y - points[0].y), Math.abs(targetGapPoint.y - points[0].y));
    if (maxXDistance >= maxYDistance) {
      centerX = (sourceGapPoint.x + targetGapPoint.x) / 2;
      centerY = points[0].y;
    } else {
      centerX = points[0].x;
      centerY = (sourceGapPoint.y + targetGapPoint.y) / 2;
    }
  }
  const pathPoints = [
    source,
    { x: sourceGapped.x + sourceGapOffset.x, y: sourceGapped.y + sourceGapOffset.y },
    ...points,
    { x: targetGapped.x + targetGapOffset.x, y: targetGapped.y + targetGapOffset.y },
    target
  ];
  return [pathPoints, centerX, centerY, defaultOffsetX, defaultOffsetY];
}
function getBend(a, b, c, size) {
  const bendSize = Math.min(distance(a, b) / 2, distance(b, c) / 2, size);
  const { x, y } = b;
  if (a.x === x && x === c.x || a.y === y && y === c.y) {
    return `L${x} ${y}`;
  }
  if (a.y === y) {
    const xDir2 = a.x < c.x ? -1 : 1;
    const yDir2 = a.y < c.y ? 1 : -1;
    return `L ${x + bendSize * xDir2},${y}Q ${x},${y} ${x},${y + bendSize * yDir2}`;
  }
  const xDir = a.x < c.x ? 1 : -1;
  const yDir = a.y < c.y ? -1 : 1;
  return `L ${x},${y + bendSize * yDir}Q ${x},${y} ${x + bendSize * xDir},${y}`;
}
function getSmoothStepPath(smoothStepPathParams) {
  const {
    sourceX,
    sourceY,
    sourcePosition = Position.Bottom,
    targetX,
    targetY,
    targetPosition = Position.Top,
    borderRadius = 5,
    centerX,
    centerY,
    offset = 20
  } = smoothStepPathParams;
  const [points, labelX, labelY, offsetX, offsetY] = getPoints({
    source: { x: sourceX, y: sourceY },
    sourcePosition,
    target: { x: targetX, y: targetY },
    targetPosition,
    center: { x: centerX, y: centerY },
    offset
  });
  const path = points.reduce((res, p, i) => {
    let segment;
    if (i > 0 && i < points.length - 1) {
      segment = getBend(points[i - 1], p, points[i + 1], borderRadius);
    } else {
      segment = `${i === 0 ? "M" : "L"}${p.x} ${p.y}`;
    }
    res += segment;
    return res;
  }, "");
  return [path, labelX, labelY, offsetX, offsetY];
}
function getStraightPath(straightEdgeParams) {
  const { sourceX, sourceY, targetX, targetY } = straightEdgeParams;
  const [centerX, centerY, offsetX, offsetY] = getSimpleEdgeCenter({
    sourceX,
    sourceY,
    targetX,
    targetY
  });
  return [`M ${sourceX},${sourceY}L ${targetX},${targetY}`, centerX, centerY, offsetX, offsetY];
}
const StraightEdge = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "StraightEdge",
  props: [
    "label",
    "labelStyle",
    "labelShowBg",
    "labelBgStyle",
    "labelBgPadding",
    "labelBgBorderRadius",
    "sourceY",
    "sourceX",
    "targetX",
    "targetY",
    "markerEnd",
    "markerStart",
    "interactionWidth"
  ],
  compatConfig: { MODE: 3 },
  setup(props, { attrs }) {
    return () => {
      const [path, labelX, labelY] = getStraightPath(props);
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(_sfc_main$d, {
        path,
        labelX,
        labelY,
        ...attrs,
        ...props
      });
    };
  }
});
const StraightEdge$1 = StraightEdge;
const SmoothStepEdge = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "SmoothStepEdge",
  props: [
    "sourcePosition",
    "targetPosition",
    "label",
    "labelStyle",
    "labelShowBg",
    "labelBgStyle",
    "labelBgPadding",
    "labelBgBorderRadius",
    "sourceY",
    "sourceX",
    "targetX",
    "targetY",
    "borderRadius",
    "markerEnd",
    "markerStart",
    "interactionWidth",
    "offset"
  ],
  compatConfig: { MODE: 3 },
  setup(props, { attrs }) {
    return () => {
      const [path, labelX, labelY] = getSmoothStepPath({
        ...props,
        sourcePosition: props.sourcePosition ?? Position.Bottom,
        targetPosition: props.targetPosition ?? Position.Top
      });
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(_sfc_main$d, {
        path,
        labelX,
        labelY,
        ...attrs,
        ...props
      });
    };
  }
});
const SmoothStepEdge$1 = SmoothStepEdge;
const StepEdge = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "StepEdge",
  props: [
    "sourcePosition",
    "targetPosition",
    "label",
    "labelStyle",
    "labelShowBg",
    "labelBgStyle",
    "labelBgPadding",
    "labelBgBorderRadius",
    "sourceY",
    "sourceX",
    "targetX",
    "targetY",
    "markerEnd",
    "markerStart",
    "interactionWidth"
  ],
  setup(props, { attrs }) {
    return () => (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(SmoothStepEdge$1, { ...props, ...attrs, borderRadius: 0 });
  }
});
const StepEdge$1 = StepEdge;
const BezierEdge = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "BezierEdge",
  props: [
    "sourcePosition",
    "targetPosition",
    "label",
    "labelStyle",
    "labelShowBg",
    "labelBgStyle",
    "labelBgPadding",
    "labelBgBorderRadius",
    "sourceY",
    "sourceX",
    "targetX",
    "targetY",
    "curvature",
    "markerEnd",
    "markerStart",
    "interactionWidth"
  ],
  compatConfig: { MODE: 3 },
  setup(props, { attrs }) {
    return () => {
      const [path, labelX, labelY] = getBezierPath({
        ...props,
        sourcePosition: props.sourcePosition ?? Position.Bottom,
        targetPosition: props.targetPosition ?? Position.Top
      });
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(_sfc_main$d, {
        path,
        labelX,
        labelY,
        ...attrs,
        ...props
      });
    };
  }
});
const BezierEdge$1 = BezierEdge;
const SimpleBezierEdge = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "SimpleBezierEdge",
  props: [
    "sourcePosition",
    "targetPosition",
    "label",
    "labelStyle",
    "labelShowBg",
    "labelBgStyle",
    "labelBgPadding",
    "labelBgBorderRadius",
    "sourceY",
    "sourceX",
    "targetX",
    "targetY",
    "markerEnd",
    "markerStart",
    "interactionWidth"
  ],
  compatConfig: { MODE: 3 },
  setup(props, { attrs }) {
    return () => {
      const [path, labelX, labelY] = getSimpleBezierPath({
        ...props,
        sourcePosition: props.sourcePosition ?? Position.Bottom,
        targetPosition: props.targetPosition ?? Position.Top
      });
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(_sfc_main$d, {
        path,
        labelX,
        labelY,
        ...attrs,
        ...props
      });
    };
  }
});
const SimpleBezierEdge$1 = SimpleBezierEdge;
const defaultNodeTypes = {
  input: InputNode$1,
  default: DefaultNode$1,
  output: OutputNode$1
};
const defaultEdgeTypes = {
  default: BezierEdge$1,
  straight: StraightEdge$1,
  step: StepEdge$1,
  smoothstep: SmoothStepEdge$1,
  simplebezier: SimpleBezierEdge$1
};
function useGetters(state, nodeLookup, edgeLookup) {
  const getNode = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => (id2) => nodeLookup.value.get(id2));
  const getEdge = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => (id2) => edgeLookup.value.get(id2));
  const getEdgeTypes = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
    const edgeTypes = {
      ...defaultEdgeTypes,
      ...state.edgeTypes
    };
    const keys = Object.keys(edgeTypes);
    for (const e of state.edges) {
      e.type && !keys.includes(e.type) && (edgeTypes[e.type] = e.type);
    }
    return edgeTypes;
  });
  const getNodeTypes = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
    const nodeTypes = {
      ...defaultNodeTypes,
      ...state.nodeTypes
    };
    const keys = Object.keys(nodeTypes);
    for (const n of state.nodes) {
      n.type && !keys.includes(n.type) && (nodeTypes[n.type] = n.type);
    }
    return nodeTypes;
  });
  const getNodes = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
    if (state.onlyRenderVisibleElements) {
      return getNodesInside(
        state.nodes,
        {
          x: 0,
          y: 0,
          width: state.dimensions.width,
          height: state.dimensions.height
        },
        state.viewport,
        true
      );
    }
    return state.nodes;
  });
  const getEdges = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
    if (state.onlyRenderVisibleElements) {
      const visibleEdges = [];
      for (const edge of state.edges) {
        const source = nodeLookup.value.get(edge.source);
        const target = nodeLookup.value.get(edge.target);
        if (isEdgeVisible({
          sourcePos: source.computedPosition || { x: 0, y: 0 },
          targetPos: target.computedPosition || { x: 0, y: 0 },
          sourceWidth: source.dimensions.width,
          sourceHeight: source.dimensions.height,
          targetWidth: target.dimensions.width,
          targetHeight: target.dimensions.height,
          width: state.dimensions.width,
          height: state.dimensions.height,
          viewport: state.viewport
        })) {
          visibleEdges.push(edge);
        }
      }
      return visibleEdges;
    }
    return state.edges;
  });
  const getElements = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => [...getNodes.value, ...getEdges.value]);
  const getSelectedNodes = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
    const selectedNodes = [];
    for (const node of state.nodes) {
      if (node.selected) {
        selectedNodes.push(node);
      }
    }
    return selectedNodes;
  });
  const getSelectedEdges = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
    const selectedEdges = [];
    for (const edge of state.edges) {
      if (edge.selected) {
        selectedEdges.push(edge);
      }
    }
    return selectedEdges;
  });
  const getSelectedElements = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => [
    ...getSelectedNodes.value,
    ...getSelectedEdges.value
  ]);
  const getNodesInitialized = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
    const initializedNodes = [];
    for (const node of state.nodes) {
      if (!!node.dimensions.width && !!node.dimensions.height && node.handleBounds !== void 0) {
        initializedNodes.push(node);
      }
    }
    return initializedNodes;
  });
  const areNodesInitialized = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(
    () => getNodes.value.length > 0 && getNodesInitialized.value.length === getNodes.value.length
  );
  return {
    getNode,
    getEdge,
    getElements,
    getEdgeTypes,
    getNodeTypes,
    getEdges,
    getNodes,
    getSelectedElements,
    getSelectedNodes,
    getSelectedEdges,
    getNodesInitialized,
    areNodesInitialized
  };
}
class Storage {
  constructor() {
    this.currentId = 0;
    this.flows = /* @__PURE__ */ new Map();
  }
  static getInstance() {
    var _a;
    const vueApp = (_a = (0,vue__WEBPACK_IMPORTED_MODULE_0__.getCurrentInstance)()) == null ? void 0 : _a.appContext.app;
    const existingInstance = (vueApp == null ? void 0 : vueApp.config.globalProperties.$vueFlowStorage) ?? Storage.instance;
    Storage.instance = existingInstance ?? new Storage();
    if (vueApp) {
      vueApp.config.globalProperties.$vueFlowStorage = Storage.instance;
    }
    return Storage.instance;
  }
  set(id2, flow) {
    return this.flows.set(id2, flow);
  }
  get(id2) {
    return this.flows.get(id2);
  }
  remove(id2) {
    return this.flows.delete(id2);
  }
  create(id2, preloadedState) {
    const state = useState();
    const reactiveState = (0,vue__WEBPACK_IMPORTED_MODULE_0__.reactive)(state);
    const hooksOn = {};
    for (const [n, h2] of Object.entries(reactiveState.hooks)) {
      const name = `on${n.charAt(0).toUpperCase() + n.slice(1)}`;
      hooksOn[name] = h2.on;
    }
    const emits = {};
    for (const [n, h2] of Object.entries(reactiveState.hooks)) {
      emits[n] = h2.trigger;
    }
    const nodeLookup = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
      const nodesMap = /* @__PURE__ */ new Map();
      for (const node of reactiveState.nodes) {
        nodesMap.set(node.id, node);
      }
      return nodesMap;
    });
    const edgeLookup = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
      const edgesMap = /* @__PURE__ */ new Map();
      for (const edge of reactiveState.edges) {
        edgesMap.set(edge.id, edge);
      }
      return edgesMap;
    });
    const getters = useGetters(reactiveState, nodeLookup, edgeLookup);
    const actions = useActions(reactiveState, nodeLookup, edgeLookup);
    actions.setState({ ...reactiveState, ...preloadedState });
    const flow = {
      ...hooksOn,
      ...getters,
      ...actions,
      ...toRefs(reactiveState),
      nodeLookup,
      edgeLookup,
      emits,
      id: id2,
      vueFlowVersion: "1.44.0",
      $destroy: () => {
        this.remove(id2);
      }
    };
    this.set(id2, flow);
    return flow;
  }
  getId() {
    return `vue-flow-${this.currentId++}`;
  }
}
function useVueFlow(idOrOpts) {
  const storage = Storage.getInstance();
  const scope = (0,vue__WEBPACK_IMPORTED_MODULE_0__.getCurrentScope)();
  const isOptsObj = typeof idOrOpts === "object";
  const options = isOptsObj ? idOrOpts : { id: idOrOpts };
  const id2 = options.id;
  const vueFlowId = id2 ?? (scope == null ? void 0 : scope.vueFlowId);
  let vueFlow;
  if (scope) {
    const injectedState = (0,vue__WEBPACK_IMPORTED_MODULE_0__.inject)(VueFlow, null);
    if (typeof injectedState !== "undefined" && injectedState !== null && (!vueFlowId || injectedState.id === vueFlowId)) {
      vueFlow = injectedState;
    }
  }
  if (!vueFlow) {
    if (vueFlowId) {
      vueFlow = storage.get(vueFlowId);
    }
  }
  if (!vueFlow || vueFlowId && vueFlow.id !== vueFlowId) {
    const name = id2 ?? storage.getId();
    const state = storage.create(name, options);
    vueFlow = state;
    const vfScope = scope ?? (0,vue__WEBPACK_IMPORTED_MODULE_0__.effectScope)(true);
    vfScope.run(() => {
      (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
        state.applyDefault,
        (shouldApplyDefault, __, onCleanup) => {
          const nodesChangeHandler = (changes) => {
            state.applyNodeChanges(changes);
          };
          const edgesChangeHandler = (changes) => {
            state.applyEdgeChanges(changes);
          };
          if (shouldApplyDefault) {
            state.onNodesChange(nodesChangeHandler);
            state.onEdgesChange(edgesChangeHandler);
          } else {
            state.hooks.value.nodesChange.off(nodesChangeHandler);
            state.hooks.value.edgesChange.off(edgesChangeHandler);
          }
          onCleanup(() => {
            state.hooks.value.nodesChange.off(nodesChangeHandler);
            state.hooks.value.edgesChange.off(edgesChangeHandler);
          });
        },
        { immediate: true }
      );
      tryOnScopeDispose(() => {
        if (vueFlow) {
          const storedInstance = storage.get(vueFlow.id);
          if (storedInstance) {
            storedInstance.$destroy();
          } else {
            warn(`No store instance found for id ${vueFlow.id} in storage.`);
          }
        }
      });
    });
  } else {
    if (isOptsObj) {
      vueFlow.setState(options);
    }
  }
  if (scope) {
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.provide)(VueFlow, vueFlow);
    scope.vueFlowId = vueFlow.id;
  }
  if (isOptsObj) {
    const instance = (0,vue__WEBPACK_IMPORTED_MODULE_0__.getCurrentInstance)();
    if ((instance == null ? void 0 : instance.type.name) !== "VueFlow") {
      vueFlow.emits.error(new VueFlowError(ErrorCode.USEVUEFLOW_OPTIONS));
    }
  }
  return vueFlow;
}
function useResizeHandler(viewportEl) {
  const { emits, dimensions } = useVueFlow();
  let resizeObserver;
  (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(() => {
    const rendererNode = viewportEl.value;
    const updateDimensions = () => {
      if (!rendererNode) {
        return;
      }
      const size = getDimensions(rendererNode);
      if (size.width === 0 || size.height === 0) {
        emits.error(new VueFlowError(ErrorCode.MISSING_VIEWPORT_DIMENSIONS));
      }
      dimensions.value = { width: size.width || 500, height: size.height || 500 };
    };
    updateDimensions();
    window.addEventListener("resize", updateDimensions);
    if (rendererNode) {
      resizeObserver = new ResizeObserver(() => updateDimensions());
      resizeObserver.observe(rendererNode);
    }
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.onBeforeUnmount)(() => {
      window.removeEventListener("resize", updateDimensions);
      if (resizeObserver && rendererNode) {
        resizeObserver.unobserve(rendererNode);
      }
    });
  });
}
const __default__$c = {
  name: "UserSelection",
  compatConfig: { MODE: 3 }
};
const _sfc_main$c = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$c,
  props: {
    userSelectionRect: {}
  },
  setup(__props) {
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
        class: "vue-flow__selection vue-flow__container",
        style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)({
          width: `${_ctx.userSelectionRect.width}px`,
          height: `${_ctx.userSelectionRect.height}px`,
          transform: `translate(${_ctx.userSelectionRect.x}px, ${_ctx.userSelectionRect.y}px)`
        })
      }, null, 4);
    };
  }
});
const _hoisted_1$6 = ["tabIndex"];
const __default__$b = {
  name: "NodesSelection",
  compatConfig: { MODE: 3 }
};
const _sfc_main$b = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$b,
  setup(__props) {
    const { emits, viewport, getSelectedNodes, noPanClassName, disableKeyboardA11y, userSelectionActive } = useVueFlow();
    const updatePositions = useUpdateNodePositions();
    const el = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    const dragging = useDrag({
      el,
      onStart(args) {
        emits.selectionDragStart(args);
      },
      onDrag(args) {
        emits.selectionDrag(args);
      },
      onStop(args) {
        emits.selectionDragStop(args);
      }
    });
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(() => {
      var _a;
      if (!disableKeyboardA11y.value) {
        (_a = el.value) == null ? void 0 : _a.focus({ preventScroll: true });
      }
    });
    const selectedNodesBBox = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => getRectOfNodes(getSelectedNodes.value));
    const innerStyle = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => ({
      width: `${selectedNodesBBox.value.width}px`,
      height: `${selectedNodesBBox.value.height}px`,
      top: `${selectedNodesBBox.value.y}px`,
      left: `${selectedNodesBBox.value.x}px`
    }));
    function onContextMenu(event) {
      emits.selectionContextMenu({ event, nodes: getSelectedNodes.value });
    }
    function onKeyDown(event) {
      if (disableKeyboardA11y) {
        return;
      }
      if (arrowKeyDiffs[event.key]) {
        event.preventDefault();
        updatePositions(
          {
            x: arrowKeyDiffs[event.key].x,
            y: arrowKeyDiffs[event.key].y
          },
          event.shiftKey
        );
      }
    }
    return (_ctx, _cache) => {
      return !(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(userSelectionActive) && selectedNodesBBox.value.width && selectedNodesBBox.value.height ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
        key: 0,
        class: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(["vue-flow__nodesselection vue-flow__container", (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(noPanClassName)]),
        style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)({ transform: `translate(${(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(viewport).x}px,${(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(viewport).y}px) scale(${(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(viewport).zoom})` })
      }, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
          ref_key: "el",
          ref: el,
          class: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)([{ dragging: (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(dragging) }, "vue-flow__nodesselection-rect"]),
          style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)(innerStyle.value),
          tabIndex: (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(disableKeyboardA11y) ? void 0 : -1,
          onContextmenu: onContextMenu,
          onKeydown: onKeyDown
        }, null, 46, _hoisted_1$6)
      ], 6)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("", true);
    };
  }
});
function getMousePosition(event, containerBounds) {
  return {
    x: event.clientX - containerBounds.left,
    y: event.clientY - containerBounds.top
  };
}
const __default__$a = {
  name: "Pane",
  compatConfig: { MODE: 3 }
};
const _sfc_main$a = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$a,
  props: {
    isSelecting: { type: Boolean },
    selectionKeyPressed: { type: Boolean }
  },
  setup(__props) {
    const {
      vueFlowRef,
      nodes,
      viewport,
      emits,
      userSelectionActive,
      removeSelectedElements,
      userSelectionRect,
      elementsSelectable,
      nodesSelectionActive,
      getSelectedEdges,
      getSelectedNodes,
      removeNodes,
      removeEdges,
      selectionMode,
      deleteKeyCode,
      multiSelectionKeyCode,
      multiSelectionActive,
      edgeLookup,
      nodeLookup,
      connectionLookup,
      defaultEdgeOptions,
      connectionStartHandle
    } = useVueFlow();
    const container = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    const selectedNodeIds = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(/* @__PURE__ */ new Set());
    const selectedEdgeIds = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(/* @__PURE__ */ new Set());
    const containerBounds = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)();
    const hasActiveSelection = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => elementsSelectable.value && (__props.isSelecting || userSelectionActive.value));
    const connectionInProgress = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => connectionStartHandle.value !== null);
    let selectionInProgress = false;
    let selectionStarted = false;
    const deleteKeyPressed = useKeyPress(deleteKeyCode, { actInsideInputWithModifier: false });
    const multiSelectKeyPressed = useKeyPress(multiSelectionKeyCode);
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(deleteKeyPressed, (isKeyPressed) => {
      if (!isKeyPressed) {
        return;
      }
      removeNodes(getSelectedNodes.value);
      removeEdges(getSelectedEdges.value);
      nodesSelectionActive.value = false;
    });
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(multiSelectKeyPressed, (isKeyPressed) => {
      multiSelectionActive.value = isKeyPressed;
    });
    function wrapHandler(handler, containerRef) {
      return (event) => {
        if (event.target !== containerRef) {
          return;
        }
        handler == null ? void 0 : handler(event);
      };
    }
    function onClick(event) {
      if (selectionInProgress || connectionInProgress.value) {
        selectionInProgress = false;
        return;
      }
      emits.paneClick(event);
      removeSelectedElements();
      nodesSelectionActive.value = false;
    }
    function onContextMenu(event) {
      event.preventDefault();
      event.stopPropagation();
      emits.paneContextMenu(event);
    }
    function onWheel(event) {
      emits.paneScroll(event);
    }
    function onPointerDown(event) {
      var _a, _b, _c;
      containerBounds.value = (_a = vueFlowRef.value) == null ? void 0 : _a.getBoundingClientRect();
      if (!elementsSelectable.value || !__props.isSelecting || event.button !== 0 || event.target !== container.value || !containerBounds.value) {
        return;
      }
      (_c = (_b = event.target) == null ? void 0 : _b.setPointerCapture) == null ? void 0 : _c.call(_b, event.pointerId);
      const { x, y } = getMousePosition(event, containerBounds.value);
      selectionStarted = true;
      selectionInProgress = false;
      removeSelectedElements();
      userSelectionRect.value = {
        width: 0,
        height: 0,
        startX: x,
        startY: y,
        x,
        y
      };
      emits.selectionStart(event);
    }
    function onPointerMove(event) {
      var _a;
      if (!containerBounds.value || !userSelectionRect.value) {
        return;
      }
      selectionInProgress = true;
      const { x: mouseX, y: mouseY } = getEventPosition(event, containerBounds.value);
      const { startX = 0, startY = 0 } = userSelectionRect.value;
      const nextUserSelectRect = {
        startX,
        startY,
        x: mouseX < startX ? mouseX : startX,
        y: mouseY < startY ? mouseY : startY,
        width: Math.abs(mouseX - startX),
        height: Math.abs(mouseY - startY)
      };
      const prevSelectedNodeIds = selectedNodeIds.value;
      const prevSelectedEdgeIds = selectedEdgeIds.value;
      selectedNodeIds.value = new Set(
        getNodesInside(nodes.value, nextUserSelectRect, viewport.value, selectionMode.value === SelectionMode.Partial, true).map(
          (node) => node.id
        )
      );
      selectedEdgeIds.value = /* @__PURE__ */ new Set();
      const edgesSelectable = ((_a = defaultEdgeOptions.value) == null ? void 0 : _a.selectable) ?? true;
      for (const nodeId of selectedNodeIds.value) {
        const connections = connectionLookup.value.get(nodeId);
        if (!connections) {
          continue;
        }
        for (const { edgeId } of connections.values()) {
          const edge = edgeLookup.value.get(edgeId);
          if (edge && (edge.selectable ?? edgesSelectable)) {
            selectedEdgeIds.value.add(edgeId);
          }
        }
      }
      if (!areSetsEqual(prevSelectedNodeIds, selectedNodeIds.value)) {
        const changes = getSelectionChanges(nodeLookup.value, selectedNodeIds.value, true);
        emits.nodesChange(changes);
      }
      if (!areSetsEqual(prevSelectedEdgeIds, selectedEdgeIds.value)) {
        const changes = getSelectionChanges(edgeLookup.value, selectedEdgeIds.value);
        emits.edgesChange(changes);
      }
      userSelectionRect.value = nextUserSelectRect;
      userSelectionActive.value = true;
      nodesSelectionActive.value = false;
    }
    function onPointerUp(event) {
      var _a;
      if (event.button !== 0 || !selectionStarted) {
        return;
      }
      (_a = event.target) == null ? void 0 : _a.releasePointerCapture(event.pointerId);
      if (!userSelectionActive.value && userSelectionRect.value && event.target === container.value) {
        onClick(event);
      }
      userSelectionActive.value = false;
      userSelectionRect.value = null;
      nodesSelectionActive.value = selectedNodeIds.value.size > 0;
      emits.selectionEnd(event);
      if (__props.selectionKeyPressed) {
        selectionInProgress = false;
      }
      selectionStarted = false;
    }
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
        ref_key: "container",
        ref: container,
        class: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(["vue-flow__pane vue-flow__container", { selection: _ctx.isSelecting }]),
        onClick: _cache[0] || (_cache[0] = (event) => hasActiveSelection.value ? void 0 : wrapHandler(onClick, container.value)(event)),
        onContextmenu: _cache[1] || (_cache[1] = ($event) => wrapHandler(onContextMenu, container.value)($event)),
        onWheelPassive: _cache[2] || (_cache[2] = ($event) => wrapHandler(onWheel, container.value)($event)),
        onPointerenter: _cache[3] || (_cache[3] = (event) => hasActiveSelection.value ? void 0 : (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(emits).paneMouseEnter(event)),
        onPointerdown: _cache[4] || (_cache[4] = (event) => hasActiveSelection.value ? onPointerDown(event) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(emits).paneMouseMove(event)),
        onPointermove: _cache[5] || (_cache[5] = (event) => hasActiveSelection.value ? onPointerMove(event) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(emits).paneMouseMove(event)),
        onPointerup: _cache[6] || (_cache[6] = (event) => hasActiveSelection.value ? onPointerUp(event) : void 0),
        onPointerleave: _cache[7] || (_cache[7] = ($event) => (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(emits).paneMouseLeave($event))
      }, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderSlot)(_ctx.$slots, "default"),
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(userSelectionActive) && (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(userSelectionRect) ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_sfc_main$c, {
          key: 0,
          "user-selection-rect": (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(userSelectionRect)
        }, null, 8, ["user-selection-rect"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("", true),
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(nodesSelectionActive) && (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(getSelectedNodes).length ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_sfc_main$b, { key: 1 })) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("", true)
      ], 34);
    };
  }
});
const __default__$9 = {
  name: "Transform",
  compatConfig: { MODE: 3 }
};
const _sfc_main$9 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$9,
  setup(__props) {
    const { viewport, fitViewOnInit, fitViewOnInitDone } = useVueFlow();
    const isHidden = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
      if (fitViewOnInit.value) {
        return !fitViewOnInitDone.value;
      }
      return false;
    });
    const transform = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => `translate(${viewport.value.x}px,${viewport.value.y}px) scale(${viewport.value.zoom})`);
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
        class: "vue-flow__transformationpane vue-flow__container",
        style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)({ transform: transform.value, opacity: isHidden.value ? 0 : void 0 })
      }, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderSlot)(_ctx.$slots, "default")
      ], 4);
    };
  }
});
const __default__$8 = {
  name: "Viewport",
  compatConfig: { MODE: 3 }
};
const _sfc_main$8 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$8,
  setup(__props) {
    const {
      minZoom,
      maxZoom,
      defaultViewport,
      translateExtent,
      zoomActivationKeyCode,
      selectionKeyCode,
      panActivationKeyCode,
      panOnScroll,
      panOnScrollMode,
      panOnScrollSpeed,
      panOnDrag,
      zoomOnDoubleClick,
      zoomOnPinch,
      zoomOnScroll,
      preventScrolling,
      noWheelClassName,
      noPanClassName,
      emits,
      connectionStartHandle,
      userSelectionActive,
      paneDragging,
      d3Zoom: storeD3Zoom,
      d3Selection: storeD3Selection,
      d3ZoomHandler: storeD3ZoomHandler,
      viewport,
      viewportRef,
      paneClickDistance
    } = useVueFlow();
    useResizeHandler(viewportRef);
    const isZoomingOrPanning = (0,vue__WEBPACK_IMPORTED_MODULE_0__.shallowRef)(false);
    const isPanScrolling = (0,vue__WEBPACK_IMPORTED_MODULE_0__.shallowRef)(false);
    let panScrollTimeout = null;
    let zoomedWithRightMouseButton = false;
    let mouseButton = 0;
    let prevTransform = {
      x: 0,
      y: 0,
      zoom: 0
    };
    const panKeyPressed = useKeyPress(panActivationKeyCode);
    const selectionKeyPressed = useKeyPress(selectionKeyCode);
    const zoomKeyPressed = useKeyPress(zoomActivationKeyCode);
    const shouldPanOnDrag = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(
      () => (!selectionKeyPressed.value || selectionKeyPressed.value && selectionKeyCode.value === true) && (panKeyPressed.value || panOnDrag.value)
    );
    const shouldPanOnScroll = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => panKeyPressed.value || panOnScroll.value);
    const isSelecting = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => selectionKeyPressed.value || selectionKeyCode.value === true && shouldPanOnDrag.value !== true);
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(() => {
      if (!viewportRef.value) {
        warn("Viewport element is missing");
        return;
      }
      const viewportElement = viewportRef.value;
      const bbox = viewportElement.getBoundingClientRect();
      const d3Zoom = zoom().clickDistance(paneClickDistance.value).scaleExtent([minZoom.value, maxZoom.value]).translateExtent(translateExtent.value);
      const d3Selection = select(viewportElement).call(d3Zoom);
      const d3ZoomHandler = d3Selection.on("wheel.zoom");
      const updatedTransform = identity.translate(defaultViewport.value.x ?? 0, defaultViewport.value.y ?? 0).scale(clamp(defaultViewport.value.zoom ?? 1, minZoom.value, maxZoom.value));
      const extent = [
        [0, 0],
        [bbox.width, bbox.height]
      ];
      const constrainedTransform = d3Zoom.constrain()(updatedTransform, extent, translateExtent.value);
      d3Zoom.transform(d3Selection, constrainedTransform);
      d3Zoom.wheelDelta(wheelDelta);
      storeD3Zoom.value = d3Zoom;
      storeD3Selection.value = d3Selection;
      storeD3ZoomHandler.value = d3ZoomHandler;
      viewport.value = { x: constrainedTransform.x, y: constrainedTransform.y, zoom: constrainedTransform.k };
      d3Zoom.on("start", (event) => {
        var _a;
        if (!event.sourceEvent) {
          return null;
        }
        mouseButton = event.sourceEvent.button;
        isZoomingOrPanning.value = true;
        const flowTransform = eventToFlowTransform(event.transform);
        if (((_a = event.sourceEvent) == null ? void 0 : _a.type) === "mousedown") {
          paneDragging.value = true;
        }
        prevTransform = flowTransform;
        emits.viewportChangeStart(flowTransform);
        emits.moveStart({ event, flowTransform });
      });
      d3Zoom.on("end", (event) => {
        if (!event.sourceEvent) {
          return null;
        }
        isZoomingOrPanning.value = false;
        paneDragging.value = false;
        if (isRightClickPan(shouldPanOnDrag.value, mouseButton ?? 0) && !zoomedWithRightMouseButton) {
          emits.paneContextMenu(event.sourceEvent);
        }
        zoomedWithRightMouseButton = false;
        if (viewChanged(prevTransform, event.transform)) {
          const flowTransform = eventToFlowTransform(event.transform);
          prevTransform = flowTransform;
          emits.viewportChangeEnd(flowTransform);
          emits.moveEnd({ event, flowTransform });
        }
      });
      d3Zoom.filter((event) => {
        var _a;
        const zoomScroll = zoomKeyPressed.value || zoomOnScroll.value;
        const pinchZoom = zoomOnPinch.value && event.ctrlKey;
        const eventButton = event.button;
        if (eventButton === 1 && event.type === "mousedown" && (isWrappedWithClass(event, "vue-flow__node") || isWrappedWithClass(event, "vue-flow__edge"))) {
          return true;
        }
        if (!shouldPanOnDrag.value && !zoomScroll && !shouldPanOnScroll.value && !zoomOnDoubleClick.value && !zoomOnPinch.value) {
          return false;
        }
        if (userSelectionActive.value) {
          return false;
        }
        if (!zoomOnDoubleClick.value && event.type === "dblclick") {
          return false;
        }
        if (isWrappedWithClass(event, noWheelClassName.value) && event.type === "wheel") {
          return false;
        }
        if (isWrappedWithClass(event, noPanClassName.value) && (event.type !== "wheel" || shouldPanOnScroll.value && event.type === "wheel" && !zoomKeyPressed.value)) {
          return false;
        }
        if (!zoomOnPinch.value && event.ctrlKey && event.type === "wheel") {
          return false;
        }
        if (!zoomScroll && !shouldPanOnScroll.value && !pinchZoom && event.type === "wheel") {
          return false;
        }
        if (!zoomOnPinch && event.type === "touchstart" && ((_a = event.touches) == null ? void 0 : _a.length) > 1) {
          event.preventDefault();
          return false;
        }
        if (!shouldPanOnDrag.value && (event.type === "mousedown" || event.type === "touchstart")) {
          return false;
        }
        if (selectionKeyCode.value === true && Array.isArray(panOnDrag.value) && panOnDrag.value.includes(0) && eventButton === 0) {
          return false;
        }
        if (Array.isArray(panOnDrag.value) && !panOnDrag.value.includes(eventButton) && (event.type === "mousedown" || event.type === "touchstart")) {
          return false;
        }
        const buttonAllowed = Array.isArray(panOnDrag.value) && panOnDrag.value.includes(eventButton) || selectionKeyCode.value === true && Array.isArray(panOnDrag.value) && !panOnDrag.value.includes(0) || !eventButton || eventButton <= 1;
        return (!event.ctrlKey || panKeyPressed.value || event.type === "wheel") && buttonAllowed;
      });
      (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
        [userSelectionActive, shouldPanOnDrag],
        () => {
          if (userSelectionActive.value && !isZoomingOrPanning.value) {
            d3Zoom.on("zoom", null);
          } else if (!userSelectionActive.value) {
            d3Zoom.on("zoom", (event) => {
              viewport.value = { x: event.transform.x, y: event.transform.y, zoom: event.transform.k };
              const flowTransform = eventToFlowTransform(event.transform);
              zoomedWithRightMouseButton = isRightClickPan(shouldPanOnDrag.value, mouseButton ?? 0);
              emits.viewportChange(flowTransform);
              emits.move({ event, flowTransform });
            });
          }
        },
        { immediate: true }
      );
      (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
        [userSelectionActive, shouldPanOnScroll, panOnScrollMode, zoomKeyPressed, zoomOnPinch, preventScrolling, noWheelClassName],
        () => {
          if (shouldPanOnScroll.value && !zoomKeyPressed.value && !userSelectionActive.value) {
            d3Selection.on(
              "wheel.zoom",
              (event) => {
                if (isWrappedWithClass(event, noWheelClassName.value)) {
                  return false;
                }
                const zoomScroll = zoomKeyPressed.value || zoomOnScroll.value;
                const pinchZoom = zoomOnPinch.value && event.ctrlKey;
                const scrollEventEnabled = !preventScrolling.value || shouldPanOnScroll.value || zoomScroll || pinchZoom;
                if (!scrollEventEnabled) {
                  return false;
                }
                event.preventDefault();
                event.stopImmediatePropagation();
                const currentZoom = d3Selection.property("__zoom").k || 1;
                const _isMacOs = isMacOs();
                if (!panKeyPressed.value && event.ctrlKey && zoomOnPinch.value && _isMacOs) {
                  const point = pointer(event);
                  const pinchDelta = wheelDelta(event);
                  const zoom2 = currentZoom * 2 ** pinchDelta;
                  d3Zoom.scaleTo(d3Selection, zoom2, point, event);
                  return;
                }
                const deltaNormalize = event.deltaMode === 1 ? 20 : 1;
                let deltaX = panOnScrollMode.value === PanOnScrollMode.Vertical ? 0 : event.deltaX * deltaNormalize;
                let deltaY = panOnScrollMode.value === PanOnScrollMode.Horizontal ? 0 : event.deltaY * deltaNormalize;
                if (!_isMacOs && event.shiftKey && panOnScrollMode.value !== PanOnScrollMode.Vertical && !deltaX && deltaY) {
                  deltaX = deltaY;
                  deltaY = 0;
                }
                d3Zoom.translateBy(
                  d3Selection,
                  -(deltaX / currentZoom) * panOnScrollSpeed.value,
                  -(deltaY / currentZoom) * panOnScrollSpeed.value
                );
                const nextViewport = eventToFlowTransform(d3Selection.property("__zoom"));
                if (panScrollTimeout) {
                  clearTimeout(panScrollTimeout);
                }
                if (!isPanScrolling.value) {
                  isPanScrolling.value = true;
                  emits.moveStart({ event, flowTransform: nextViewport });
                  emits.viewportChangeStart(nextViewport);
                } else {
                  emits.move({ event, flowTransform: nextViewport });
                  emits.viewportChange(nextViewport);
                  panScrollTimeout = setTimeout(() => {
                    emits.moveEnd({ event, flowTransform: nextViewport });
                    emits.viewportChangeEnd(nextViewport);
                    isPanScrolling.value = false;
                  }, 150);
                }
              },
              { passive: false }
            );
          } else if (typeof d3ZoomHandler !== "undefined") {
            d3Selection.on(
              "wheel.zoom",
              function(event, d) {
                const invalidEvent = !preventScrolling.value && event.type === "wheel" && !event.ctrlKey;
                const zoomScroll = zoomKeyPressed.value || zoomOnScroll.value;
                const pinchZoom = zoomOnPinch.value && event.ctrlKey;
                const scrollEventsDisabled = !zoomScroll && !panOnScroll.value && !pinchZoom && event.type === "wheel";
                if (scrollEventsDisabled || invalidEvent || isWrappedWithClass(event, noWheelClassName.value)) {
                  return null;
                }
                event.preventDefault();
                d3ZoomHandler.call(this, event, d);
              },
              { passive: false }
            );
          }
        },
        { immediate: true }
      );
    });
    function isRightClickPan(pan, usedButton) {
      return usedButton === 2 && Array.isArray(pan) && pan.includes(2);
    }
    function wheelDelta(event) {
      const factor = event.ctrlKey && isMacOs() ? 10 : 1;
      return -event.deltaY * (event.deltaMode === 1 ? 0.05 : event.deltaMode ? 1 : 2e-3) * factor;
    }
    function viewChanged(prevViewport, eventTransform) {
      return prevViewport.x !== eventTransform.x && !Number.isNaN(eventTransform.x) || prevViewport.y !== eventTransform.y && !Number.isNaN(eventTransform.y) || prevViewport.zoom !== eventTransform.k && !Number.isNaN(eventTransform.k);
    }
    function eventToFlowTransform(eventTransform) {
      return {
        x: eventTransform.x,
        y: eventTransform.y,
        zoom: eventTransform.k
      };
    }
    function isWrappedWithClass(event, className) {
      return event.target.closest(`.${className}`);
    }
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
        ref_key: "viewportRef",
        ref: viewportRef,
        class: "vue-flow__viewport vue-flow__container"
      }, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_sfc_main$a, {
          "is-selecting": isSelecting.value,
          "selection-key-pressed": (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(selectionKeyPressed),
          class: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
            connecting: !!(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(connectionStartHandle),
            dragging: (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(paneDragging),
            draggable: (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(panOnDrag) === true || Array.isArray((0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(panOnDrag)) && (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(panOnDrag).includes(0)
          })
        }, {
          default: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(() => [
            (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_sfc_main$9, null, {
              default: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(() => [
                (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderSlot)(_ctx.$slots, "default")
              ]),
              _: 3
            })
          ]),
          _: 3
        }, 8, ["is-selecting", "selection-key-pressed", "class"])
      ], 512);
    };
  }
});
const _hoisted_1$5 = ["id"];
const _hoisted_2 = ["id"];
const _hoisted_3 = ["id"];
const __default__$7 = {
  name: "A11yDescriptions",
  compatConfig: { MODE: 3 }
};
const _sfc_main$7 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$7,
  setup(__props) {
    const { id: id2, disableKeyboardA11y, ariaLiveMessage } = useVueFlow();
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
          id: `${(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(ARIA_NODE_DESC_KEY)}-${(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(id2)}`,
          style: { "display": "none" }
        }, " Press enter or space to select a node. " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(!(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(disableKeyboardA11y) ? "You can then use the arrow keys to move the node around." : "") + " You can then use the arrow keys to move the node around, press delete to remove it and press escape to cancel. ", 9, _hoisted_1$5),
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
          id: `${(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(ARIA_EDGE_DESC_KEY)}-${(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(id2)}`,
          style: { "display": "none" }
        }, " Press enter or space to select an edge. You can then press delete to remove it or press escape to cancel. ", 8, _hoisted_2),
        !(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(disableKeyboardA11y) ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
          key: 0,
          id: `${(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(ARIA_LIVE_MESSAGE)}-${(0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(id2)}`,
          "aria-live": "assertive",
          "aria-atomic": "true",
          style: { "position": "absolute", "width": "1px", "height": "1px", "margin": "-1px", "border": "0", "padding": "0", "overflow": "hidden", "clip": "rect(0px, 0px, 0px, 0px)", "clip-path": "inset(100%)" }
        }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)((0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(ariaLiveMessage)), 9, _hoisted_3)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("", true)
      ], 64);
    };
  }
});
function useOnInitHandler() {
  const vfInstance = useVueFlow();
  (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
    () => vfInstance.viewportHelper.value.viewportInitialized,
    (isInitialized) => {
      if (isInitialized) {
        setTimeout(() => {
          vfInstance.emits.init(vfInstance);
          vfInstance.emits.paneReady(vfInstance);
        }, 1);
      }
    }
  );
}
function shiftX(x, shift, position) {
  if (position === Position.Left) {
    return x - shift;
  }
  if (position === Position.Right) {
    return x + shift;
  }
  return x;
}
function shiftY(y, shift, position) {
  if (position === Position.Top) {
    return y - shift;
  }
  if (position === Position.Bottom) {
    return y + shift;
  }
  return y;
}
const EdgeAnchor = function({
  radius = 10,
  centerX = 0,
  centerY = 0,
  position = Position.Top,
  type
}) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)("circle", {
    class: `vue-flow__edgeupdater vue-flow__edgeupdater-${type}`,
    cx: shiftX(centerX, radius, position),
    cy: shiftY(centerY, radius, position),
    r: radius,
    stroke: "transparent",
    fill: "transparent"
  });
};
EdgeAnchor.props = ["radius", "centerX", "centerY", "position", "type"];
EdgeAnchor.compatConfig = { MODE: 3 };
const EdgeAnchor$1 = EdgeAnchor;
const EdgeWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "Edge",
  compatConfig: { MODE: 3 },
  props: ["id"],
  setup(props) {
    const {
      id: vueFlowId,
      addSelectedEdges,
      connectionMode,
      edgeUpdaterRadius,
      emits,
      nodesSelectionActive,
      noPanClassName,
      getEdgeTypes,
      removeSelectedEdges,
      findEdge,
      findNode,
      isValidConnection,
      multiSelectionActive,
      disableKeyboardA11y,
      elementsSelectable,
      edgesUpdatable,
      edgesFocusable,
      hooks
    } = useVueFlow();
    const edge = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => findEdge(props.id));
    const { emit, on } = useEdgeHooks(edge.value, emits);
    const slots = (0,vue__WEBPACK_IMPORTED_MODULE_0__.inject)(Slots);
    const instance = (0,vue__WEBPACK_IMPORTED_MODULE_0__.getCurrentInstance)();
    const mouseOver = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(false);
    const updating = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(false);
    const nodeId = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)("");
    const handleId = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    const edgeUpdaterType = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)("source");
    const edgeEl = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    const isSelectable = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(
      () => typeof edge.value.selectable === "undefined" ? elementsSelectable.value : edge.value.selectable
    );
    const isUpdatable = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => typeof edge.value.updatable === "undefined" ? edgesUpdatable.value : edge.value.updatable);
    const isFocusable = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => typeof edge.value.focusable === "undefined" ? edgesFocusable.value : edge.value.focusable);
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.provide)(EdgeId, props.id);
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.provide)(EdgeRef, edgeEl);
    const edgeClass = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => edge.value.class instanceof Function ? edge.value.class(edge.value) : edge.value.class);
    const edgeStyle = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => edge.value.style instanceof Function ? edge.value.style(edge.value) : edge.value.style);
    const edgeCmp = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
      const name = edge.value.type || "default";
      const slot = slots == null ? void 0 : slots[`edge-${name}`];
      if (slot) {
        return slot;
      }
      let edgeType = edge.value.template ?? getEdgeTypes.value[name];
      if (typeof edgeType === "string") {
        if (instance) {
          const components = Object.keys(instance.appContext.components);
          if (components && components.includes(name)) {
            edgeType = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)(name, false);
          }
        }
      }
      if (edgeType && typeof edgeType !== "string") {
        return edgeType;
      }
      emits.error(new VueFlowError(ErrorCode.EDGE_TYPE_MISSING, edgeType));
      return false;
    });
    const { handlePointerDown } = useHandle({
      nodeId,
      handleId,
      type: edgeUpdaterType,
      isValidConnection,
      edgeUpdaterType,
      onEdgeUpdate,
      onEdgeUpdateEnd
    });
    return () => {
      const sourceNode = findNode(edge.value.source);
      const targetNode = findNode(edge.value.target);
      const pathOptions = "pathOptions" in edge.value ? edge.value.pathOptions : {};
      if (!sourceNode && !targetNode) {
        emits.error(new VueFlowError(ErrorCode.EDGE_SOURCE_TARGET_MISSING, edge.value.id, edge.value.source, edge.value.target));
        return null;
      }
      if (!sourceNode) {
        emits.error(new VueFlowError(ErrorCode.EDGE_SOURCE_MISSING, edge.value.id, edge.value.source));
        return null;
      }
      if (!targetNode) {
        emits.error(new VueFlowError(ErrorCode.EDGE_TARGET_MISSING, edge.value.id, edge.value.target));
        return null;
      }
      if (!edge.value || edge.value.hidden || sourceNode.hidden || targetNode.hidden) {
        return null;
      }
      let sourceNodeHandles;
      if (connectionMode.value === ConnectionMode.Strict) {
        sourceNodeHandles = sourceNode.handleBounds.source;
      } else {
        sourceNodeHandles = [...sourceNode.handleBounds.source || [], ...sourceNode.handleBounds.target || []];
      }
      const sourceHandle = getEdgeHandle(sourceNodeHandles, edge.value.sourceHandle);
      let targetNodeHandles;
      if (connectionMode.value === ConnectionMode.Strict) {
        targetNodeHandles = targetNode.handleBounds.target;
      } else {
        targetNodeHandles = [...targetNode.handleBounds.target || [], ...targetNode.handleBounds.source || []];
      }
      const targetHandle = getEdgeHandle(targetNodeHandles, edge.value.targetHandle);
      const sourcePosition = (sourceHandle == null ? void 0 : sourceHandle.position) || Position.Bottom;
      const targetPosition = (targetHandle == null ? void 0 : targetHandle.position) || Position.Top;
      const { x: sourceX, y: sourceY } = getHandlePosition(sourceNode, sourceHandle, sourcePosition);
      const { x: targetX, y: targetY } = getHandlePosition(targetNode, targetHandle, targetPosition);
      edge.value.sourceX = sourceX;
      edge.value.sourceY = sourceY;
      edge.value.targetX = targetX;
      edge.value.targetY = targetY;
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(
        "g",
        {
          "ref": edgeEl,
          "key": props.id,
          "data-id": props.id,
          "class": [
            "vue-flow__edge",
            `vue-flow__edge-${edgeCmp.value === false ? "default" : edge.value.type || "default"}`,
            noPanClassName.value,
            edgeClass.value,
            {
              updating: mouseOver.value,
              selected: edge.value.selected,
              animated: edge.value.animated,
              inactive: !isSelectable.value && !hooks.value.edgeClick.hasListeners()
            }
          ],
          "onClick": onEdgeClick,
          "onContextmenu": onEdgeContextMenu,
          "onDblclick": onDoubleClick,
          "onMouseenter": onEdgeMouseEnter,
          "onMousemove": onEdgeMouseMove,
          "onMouseleave": onEdgeMouseLeave,
          "onKeyDown": isFocusable.value ? onKeyDown : void 0,
          "tabIndex": isFocusable.value ? 0 : void 0,
          "aria-label": edge.value.ariaLabel === null ? void 0 : edge.value.ariaLabel || `Edge from ${edge.value.source} to ${edge.value.target}`,
          "aria-describedby": isFocusable.value ? `${ARIA_EDGE_DESC_KEY}-${vueFlowId}` : void 0,
          "role": isFocusable.value ? "button" : "img"
        },
        [
          updating.value ? null : (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(edgeCmp.value === false ? getEdgeTypes.value.default : edgeCmp.value, {
            id: props.id,
            sourceNode,
            targetNode,
            source: edge.value.source,
            target: edge.value.target,
            type: edge.value.type,
            updatable: isUpdatable.value,
            selected: edge.value.selected,
            animated: edge.value.animated,
            label: edge.value.label,
            labelStyle: edge.value.labelStyle,
            labelShowBg: edge.value.labelShowBg,
            labelBgStyle: edge.value.labelBgStyle,
            labelBgPadding: edge.value.labelBgPadding,
            labelBgBorderRadius: edge.value.labelBgBorderRadius,
            data: edge.value.data,
            events: { ...edge.value.events, ...on },
            style: edgeStyle.value,
            markerStart: `url('#${getMarkerId(edge.value.markerStart, vueFlowId)}')`,
            markerEnd: `url('#${getMarkerId(edge.value.markerEnd, vueFlowId)}')`,
            sourcePosition,
            targetPosition,
            sourceX,
            sourceY,
            targetX,
            targetY,
            sourceHandleId: edge.value.sourceHandle,
            targetHandleId: edge.value.targetHandle,
            interactionWidth: edge.value.interactionWidth,
            ...pathOptions
          }),
          [
            isUpdatable.value === "source" || isUpdatable.value === true ? [
              (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(
                "g",
                {
                  onMousedown: onEdgeUpdaterSourceMouseDown,
                  onMouseenter: onEdgeUpdaterMouseEnter,
                  onMouseout: onEdgeUpdaterMouseOut
                },
                (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(EdgeAnchor$1, {
                  "position": sourcePosition,
                  "centerX": sourceX,
                  "centerY": sourceY,
                  "radius": edgeUpdaterRadius.value,
                  "type": "source",
                  "data-type": "source"
                })
              )
            ] : null,
            isUpdatable.value === "target" || isUpdatable.value === true ? [
              (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(
                "g",
                {
                  onMousedown: onEdgeUpdaterTargetMouseDown,
                  onMouseenter: onEdgeUpdaterMouseEnter,
                  onMouseout: onEdgeUpdaterMouseOut
                },
                (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(EdgeAnchor$1, {
                  "position": targetPosition,
                  "centerX": targetX,
                  "centerY": targetY,
                  "radius": edgeUpdaterRadius.value,
                  "type": "target",
                  "data-type": "target"
                })
              )
            ] : null
          ]
        ]
      );
    };
    function onEdgeUpdaterMouseEnter() {
      mouseOver.value = true;
    }
    function onEdgeUpdaterMouseOut() {
      mouseOver.value = false;
    }
    function onEdgeUpdate(event, connection) {
      emit.update({ event, edge: edge.value, connection });
    }
    function onEdgeUpdateEnd(event) {
      emit.updateEnd({ event, edge: edge.value });
      updating.value = false;
    }
    function handleEdgeUpdater(event, isSourceHandle) {
      if (event.button !== 0) {
        return;
      }
      updating.value = true;
      nodeId.value = isSourceHandle ? edge.value.target : edge.value.source;
      handleId.value = (isSourceHandle ? edge.value.targetHandle : edge.value.sourceHandle) ?? null;
      edgeUpdaterType.value = isSourceHandle ? "target" : "source";
      emit.updateStart({ event, edge: edge.value });
      handlePointerDown(event);
    }
    function onEdgeClick(event) {
      var _a;
      const data = { event, edge: edge.value };
      if (isSelectable.value) {
        nodesSelectionActive.value = false;
        if (edge.value.selected && multiSelectionActive.value) {
          removeSelectedEdges([edge.value]);
          (_a = edgeEl.value) == null ? void 0 : _a.blur();
        } else {
          addSelectedEdges([edge.value]);
        }
      }
      emit.click(data);
    }
    function onEdgeContextMenu(event) {
      emit.contextMenu({ event, edge: edge.value });
    }
    function onDoubleClick(event) {
      emit.doubleClick({ event, edge: edge.value });
    }
    function onEdgeMouseEnter(event) {
      emit.mouseEnter({ event, edge: edge.value });
    }
    function onEdgeMouseMove(event) {
      emit.mouseMove({ event, edge: edge.value });
    }
    function onEdgeMouseLeave(event) {
      emit.mouseLeave({ event, edge: edge.value });
    }
    function onEdgeUpdaterSourceMouseDown(event) {
      handleEdgeUpdater(event, true);
    }
    function onEdgeUpdaterTargetMouseDown(event) {
      handleEdgeUpdater(event, false);
    }
    function onKeyDown(event) {
      var _a;
      if (!disableKeyboardA11y.value && elementSelectionKeys.includes(event.key) && isSelectable.value) {
        const unselect = event.key === "Escape";
        if (unselect) {
          (_a = edgeEl.value) == null ? void 0 : _a.blur();
          removeSelectedEdges([findEdge(props.id)]);
        } else {
          addSelectedEdges([findEdge(props.id)]);
        }
      }
    }
  }
});
const EdgeWrapper$1 = EdgeWrapper;
const ConnectionLine = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "ConnectionLine",
  compatConfig: { MODE: 3 },
  setup() {
    var _a;
    const {
      id: id2,
      connectionMode,
      connectionStartHandle,
      connectionEndHandle,
      connectionPosition,
      connectionLineType,
      connectionLineStyle,
      connectionLineOptions,
      connectionStatus,
      viewport,
      findNode
    } = useVueFlow();
    const connectionLineComponent = (_a = (0,vue__WEBPACK_IMPORTED_MODULE_0__.inject)(Slots)) == null ? void 0 : _a["connection-line"];
    const fromNode = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
      var _a2;
      return findNode((_a2 = connectionStartHandle.value) == null ? void 0 : _a2.nodeId);
    });
    const toNode = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
      var _a2;
      return findNode((_a2 = connectionEndHandle.value) == null ? void 0 : _a2.nodeId) ?? null;
    });
    const toXY = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
      return {
        x: (connectionPosition.value.x - viewport.value.x) / viewport.value.zoom,
        y: (connectionPosition.value.y - viewport.value.y) / viewport.value.zoom
      };
    });
    const markerStart = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(
      () => connectionLineOptions.value.markerStart ? `url(#${getMarkerId(connectionLineOptions.value.markerStart, id2)})` : ""
    );
    const markerEnd = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(
      () => connectionLineOptions.value.markerEnd ? `url(#${getMarkerId(connectionLineOptions.value.markerEnd, id2)})` : ""
    );
    return () => {
      var _a2, _b, _c;
      if (!fromNode.value || !connectionStartHandle.value) {
        return null;
      }
      const startHandleId = connectionStartHandle.value.id;
      const handleType = connectionStartHandle.value.type;
      const fromHandleBounds = fromNode.value.handleBounds;
      let handleBounds = (fromHandleBounds == null ? void 0 : fromHandleBounds[handleType]) ?? [];
      if (connectionMode.value === ConnectionMode.Loose) {
        const oppositeBounds = (fromHandleBounds == null ? void 0 : fromHandleBounds[handleType === "source" ? "target" : "source"]) ?? [];
        handleBounds = [...handleBounds, ...oppositeBounds];
      }
      if (!handleBounds) {
        return null;
      }
      const fromHandle = (startHandleId ? handleBounds.find((d) => d.id === startHandleId) : handleBounds[0]) ?? null;
      const fromPosition = (fromHandle == null ? void 0 : fromHandle.position) ?? Position.Top;
      const { x: fromX, y: fromY } = getHandlePosition(fromNode.value, fromHandle, fromPosition);
      let toHandle = null;
      if (toNode.value) {
        if (connectionMode.value === ConnectionMode.Strict) {
          toHandle = ((_a2 = toNode.value.handleBounds[handleType === "source" ? "target" : "source"]) == null ? void 0 : _a2.find(
            (d) => {
              var _a3;
              return d.id === ((_a3 = connectionEndHandle.value) == null ? void 0 : _a3.id);
            }
          )) || null;
        } else {
          toHandle = ((_b = [...toNode.value.handleBounds.source ?? [], ...toNode.value.handleBounds.target ?? []]) == null ? void 0 : _b.find(
            (d) => {
              var _a3;
              return d.id === ((_a3 = connectionEndHandle.value) == null ? void 0 : _a3.id);
            }
          )) || null;
        }
      }
      const toPosition = ((_c = connectionEndHandle.value) == null ? void 0 : _c.position) ?? (fromPosition ? oppositePosition[fromPosition] : null);
      if (!fromPosition || !toPosition) {
        return null;
      }
      const type = connectionLineType.value ?? connectionLineOptions.value.type ?? ConnectionLineType.Bezier;
      let dAttr = "";
      const pathParams = {
        sourceX: fromX,
        sourceY: fromY,
        sourcePosition: fromPosition,
        targetX: toXY.value.x,
        targetY: toXY.value.y,
        targetPosition: toPosition
      };
      if (type === ConnectionLineType.Bezier) {
        [dAttr] = getBezierPath(pathParams);
      } else if (type === ConnectionLineType.Step) {
        [dAttr] = getSmoothStepPath({
          ...pathParams,
          borderRadius: 0
        });
      } else if (type === ConnectionLineType.SmoothStep) {
        [dAttr] = getSmoothStepPath(pathParams);
      } else if (type === ConnectionLineType.SimpleBezier) {
        [dAttr] = getSimpleBezierPath(pathParams);
      } else {
        dAttr = `M${fromX},${fromY} ${toXY.value.x},${toXY.value.y}`;
      }
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(
        "svg",
        { class: "vue-flow__edges vue-flow__connectionline vue-flow__container" },
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(
          "g",
          { class: "vue-flow__connection" },
          connectionLineComponent ? (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(connectionLineComponent, {
            sourceX: fromX,
            sourceY: fromY,
            sourcePosition: fromPosition,
            targetX: toXY.value.x,
            targetY: toXY.value.y,
            targetPosition: toPosition,
            sourceNode: fromNode.value,
            sourceHandle: fromHandle,
            targetNode: toNode.value,
            targetHandle: toHandle,
            markerEnd: markerEnd.value,
            markerStart: markerStart.value,
            connectionStatus: connectionStatus.value
          }) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)("path", {
            "d": dAttr,
            "class": [connectionLineOptions.value.class, connectionStatus, "vue-flow__connection-path"],
            "style": {
              ...connectionLineStyle.value,
              ...connectionLineOptions.value.style
            },
            "marker-end": markerEnd.value,
            "marker-start": markerStart.value
          })
        )
      );
    };
  }
});
const ConnectionLine$1 = ConnectionLine;
const _hoisted_1$4 = ["id", "markerWidth", "markerHeight", "markerUnits", "orient"];
const __default__$6 = {
  name: "MarkerType",
  compatConfig: { MODE: 3 }
};
const _sfc_main$6 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$6,
  props: {
    id: {},
    type: {},
    color: { default: "none" },
    width: { default: 12.5 },
    height: { default: 12.5 },
    markerUnits: { default: "strokeWidth" },
    orient: { default: "auto-start-reverse" },
    strokeWidth: { default: 1 }
  },
  setup(__props) {
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("marker", {
        id: _ctx.id,
        class: "vue-flow__arrowhead",
        viewBox: "-10 -10 20 20",
        refX: "0",
        refY: "0",
        markerWidth: `${_ctx.width}`,
        markerHeight: `${_ctx.height}`,
        markerUnits: _ctx.markerUnits,
        orient: _ctx.orient
      }, [
        _ctx.type === (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(MarkerType).ArrowClosed ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("polyline", {
          key: 0,
          style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)({
            stroke: _ctx.color,
            fill: _ctx.color,
            strokeWidth: _ctx.strokeWidth
          }),
          "stroke-linecap": "round",
          "stroke-linejoin": "round",
          points: "-5,-4 0,0 -5,4 -5,-4"
        }, null, 4)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("", true),
        _ctx.type === (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(MarkerType).Arrow ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("polyline", {
          key: 1,
          style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)({
            stroke: _ctx.color,
            strokeWidth: _ctx.strokeWidth
          }),
          "stroke-linecap": "round",
          "stroke-linejoin": "round",
          fill: "none",
          points: "-5,-4 0,0 -5,4"
        }, null, 4)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("", true)
      ], 8, _hoisted_1$4);
    };
  }
});
const _hoisted_1$3 = {
  class: "vue-flow__marker vue-flow__container",
  "aria-hidden": "true"
};
const __default__$5 = {
  name: "MarkerDefinitions",
  compatConfig: { MODE: 3 }
};
const _sfc_main$5 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$5,
  setup(__props) {
    const { id: vueFlowId, edges, connectionLineOptions, defaultMarkerColor: defaultColor } = useVueFlow();
    const markers = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
      const ids = /* @__PURE__ */ new Set();
      const markers2 = [];
      const createMarkers = (marker) => {
        if (marker) {
          const markerId = getMarkerId(marker, vueFlowId);
          if (!ids.has(markerId)) {
            if (typeof marker === "object") {
              markers2.push({ ...marker, id: markerId, color: marker.color || defaultColor.value });
            } else {
              markers2.push({ id: markerId, color: defaultColor.value, type: marker });
            }
            ids.add(markerId);
          }
        }
      };
      for (const marker of [connectionLineOptions.value.markerEnd, connectionLineOptions.value.markerStart]) {
        createMarkers(marker);
      }
      for (const edge of edges.value) {
        for (const marker of [edge.markerStart, edge.markerEnd]) {
          createMarkers(marker);
        }
      }
      return markers2.sort((a, b) => a.id.localeCompare(b.id));
    });
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("svg", _hoisted_1$3, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("defs", null, [
          ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)(markers.value, (marker) => {
            return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_sfc_main$6, {
              id: marker.id,
              key: marker.id,
              type: marker.type,
              color: marker.color,
              width: marker.width,
              height: marker.height,
              markerUnits: marker.markerUnits,
              "stroke-width": marker.strokeWidth,
              orient: marker.orient
            }, null, 8, ["id", "type", "color", "width", "height", "markerUnits", "stroke-width", "orient"]);
          }), 128))
        ])
      ]);
    };
  }
});
const __default__$4 = {
  name: "Edges",
  compatConfig: { MODE: 3 }
};
const _sfc_main$4 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$4,
  setup(__props) {
    const { findNode, getEdges, elevateEdgesOnSelect } = useVueFlow();
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_sfc_main$5),
        ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)((0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(getEdges), (edge) => {
          return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("svg", {
            key: edge.id,
            class: "vue-flow__edges vue-flow__container",
            style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)({ zIndex: (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(getEdgeZIndex)(edge, (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(findNode), (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(elevateEdgesOnSelect)) })
          }, [
            (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)((0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(EdgeWrapper$1), {
              id: edge.id
            }, null, 8, ["id"])
          ], 4);
        }), 128)),
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)((0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(ConnectionLine$1))
      ], 64);
    };
  }
});
const NodeWrapper = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "Node",
  compatConfig: { MODE: 3 },
  props: ["id", "resizeObserver"],
  setup(props) {
    const {
      id: vueFlowId,
      noPanClassName,
      selectNodesOnDrag,
      nodesSelectionActive,
      multiSelectionActive,
      emits,
      removeSelectedNodes,
      addSelectedNodes,
      updateNodeDimensions,
      onUpdateNodeInternals,
      getNodeTypes,
      nodeExtent,
      elevateNodesOnSelect,
      disableKeyboardA11y,
      ariaLiveMessage,
      snapToGrid,
      snapGrid,
      nodeDragThreshold,
      nodesDraggable,
      elementsSelectable,
      nodesConnectable,
      nodesFocusable,
      hooks
    } = useVueFlow();
    const nodeElement = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.provide)(NodeRef, nodeElement);
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.provide)(NodeId, props.id);
    const slots = (0,vue__WEBPACK_IMPORTED_MODULE_0__.inject)(Slots);
    const instance = (0,vue__WEBPACK_IMPORTED_MODULE_0__.getCurrentInstance)();
    const updateNodePositions = useUpdateNodePositions();
    const { node, parentNode } = useNode(props.id);
    const { emit, on } = useNodeHooks(node, emits);
    const isDraggable = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => typeof node.draggable === "undefined" ? nodesDraggable.value : node.draggable);
    const isSelectable = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => typeof node.selectable === "undefined" ? elementsSelectable.value : node.selectable);
    const isConnectable = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => typeof node.connectable === "undefined" ? nodesConnectable.value : node.connectable);
    const isFocusable = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => typeof node.focusable === "undefined" ? nodesFocusable.value : node.focusable);
    const hasPointerEvents = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(
      () => isSelectable.value || isDraggable.value || hooks.value.nodeClick.hasListeners() || hooks.value.nodeDoubleClick.hasListeners() || hooks.value.nodeMouseEnter.hasListeners() || hooks.value.nodeMouseMove.hasListeners() || hooks.value.nodeMouseLeave.hasListeners()
    );
    const isInit = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => !!node.dimensions.width && !!node.dimensions.height);
    const nodeCmp = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
      const name = node.type || "default";
      const slot = slots == null ? void 0 : slots[`node-${name}`];
      if (slot) {
        return slot;
      }
      let nodeType = node.template || getNodeTypes.value[name];
      if (typeof nodeType === "string") {
        if (instance) {
          const components = Object.keys(instance.appContext.components);
          if (components && components.includes(name)) {
            nodeType = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)(name, false);
          }
        }
      }
      if (nodeType && typeof nodeType !== "string") {
        return nodeType;
      }
      emits.error(new VueFlowError(ErrorCode.NODE_TYPE_MISSING, nodeType));
      return false;
    });
    const dragging = useDrag({
      id: props.id,
      el: nodeElement,
      disabled: () => !isDraggable.value,
      selectable: isSelectable,
      dragHandle: () => node.dragHandle,
      onStart(event) {
        emit.dragStart(event);
      },
      onDrag(event) {
        emit.drag(event);
      },
      onStop(event) {
        emit.dragStop(event);
      },
      onClick(event) {
        onSelectNode(event);
      }
    });
    const getClass = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => node.class instanceof Function ? node.class(node) : node.class);
    const getStyle = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
      const styles = (node.style instanceof Function ? node.style(node) : node.style) || {};
      const width = node.width instanceof Function ? node.width(node) : node.width;
      const height = node.height instanceof Function ? node.height(node) : node.height;
      if (!styles.width && width) {
        styles.width = typeof width === "string" ? width : `${width}px`;
      }
      if (!styles.height && height) {
        styles.height = typeof height === "string" ? height : `${height}px`;
      }
      return styles;
    });
    const zIndex = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => Number(node.zIndex ?? getStyle.value.zIndex ?? 0));
    onUpdateNodeInternals((updateIds) => {
      if (updateIds.includes(props.id) || !updateIds.length) {
        updateInternals();
      }
    });
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(() => {
      (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
        () => node.hidden,
        (isHidden = false, _, onCleanup) => {
          if (!isHidden && nodeElement.value) {
            props.resizeObserver.observe(nodeElement.value);
            onCleanup(() => {
              if (nodeElement.value) {
                props.resizeObserver.unobserve(nodeElement.value);
              }
            });
          }
        },
        { immediate: true, flush: "post" }
      );
    });
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)([() => node.type, () => node.sourcePosition, () => node.targetPosition], () => {
      (0,vue__WEBPACK_IMPORTED_MODULE_0__.nextTick)(() => {
        updateNodeDimensions([{ id: props.id, nodeElement: nodeElement.value, forceUpdate: true }]);
      });
    });
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
      [
        () => node.position.x,
        () => node.position.y,
        () => {
          var _a;
          return (_a = parentNode.value) == null ? void 0 : _a.computedPosition.x;
        },
        () => {
          var _a;
          return (_a = parentNode.value) == null ? void 0 : _a.computedPosition.y;
        },
        () => {
          var _a;
          return (_a = parentNode.value) == null ? void 0 : _a.computedPosition.z;
        },
        zIndex,
        () => node.selected,
        () => node.dimensions.height,
        () => node.dimensions.width,
        () => {
          var _a;
          return (_a = parentNode.value) == null ? void 0 : _a.dimensions.height;
        },
        () => {
          var _a;
          return (_a = parentNode.value) == null ? void 0 : _a.dimensions.width;
        }
      ],
      ([newX, newY, parentX, parentY, parentZ, nodeZIndex]) => {
        const xyzPos = {
          x: newX,
          y: newY,
          z: nodeZIndex + (elevateNodesOnSelect.value ? node.selected ? 1e3 : 0 : 0)
        };
        if (typeof parentX !== "undefined" && typeof parentY !== "undefined") {
          node.computedPosition = getXYZPos({ x: parentX, y: parentY, z: parentZ }, xyzPos);
        } else {
          node.computedPosition = xyzPos;
        }
      },
      { flush: "post", immediate: true }
    );
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)([() => node.extent, nodeExtent], ([nodeExtent2, globalExtent], [oldNodeExtent, oldGlobalExtent]) => {
      if (nodeExtent2 !== oldNodeExtent || globalExtent !== oldGlobalExtent) {
        clampPosition2();
      }
    });
    if (node.extent === "parent" || typeof node.extent === "object" && "range" in node.extent && node.extent.range === "parent") {
      until(() => isInit).toBe(true).then(clampPosition2);
    } else {
      clampPosition2();
    }
    return () => {
      if (node.hidden) {
        return null;
      }
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(
        "div",
        {
          "ref": nodeElement,
          "data-id": node.id,
          "class": [
            "vue-flow__node",
            `vue-flow__node-${nodeCmp.value === false ? "default" : node.type || "default"}`,
            {
              [noPanClassName.value]: isDraggable.value,
              dragging: dragging == null ? void 0 : dragging.value,
              draggable: isDraggable.value,
              selected: node.selected,
              selectable: isSelectable.value,
              parent: node.isParent
            },
            getClass.value
          ],
          "style": {
            visibility: isInit.value ? "visible" : "hidden",
            zIndex: node.computedPosition.z ?? zIndex.value,
            transform: `translate(${node.computedPosition.x}px,${node.computedPosition.y}px)`,
            pointerEvents: hasPointerEvents.value ? "all" : "none",
            ...getStyle.value
          },
          "tabIndex": isFocusable.value ? 0 : void 0,
          "role": isFocusable.value ? "button" : void 0,
          "aria-describedby": disableKeyboardA11y.value ? void 0 : `${ARIA_NODE_DESC_KEY}-${vueFlowId}`,
          "aria-label": node.ariaLabel,
          "onMouseenter": onMouseEnter,
          "onMousemove": onMouseMove,
          "onMouseleave": onMouseLeave,
          "onContextmenu": onContextMenu,
          "onClick": onSelectNode,
          "onDblclick": onDoubleClick,
          "onKeydown": onKeyDown
        },
        [
          (0,vue__WEBPACK_IMPORTED_MODULE_0__.h)(nodeCmp.value === false ? getNodeTypes.value.default : nodeCmp.value, {
            id: node.id,
            type: node.type,
            data: node.data,
            events: { ...node.events, ...on },
            selected: node.selected,
            resizing: node.resizing,
            dragging: dragging.value,
            connectable: isConnectable.value,
            position: node.computedPosition,
            dimensions: node.dimensions,
            isValidTargetPos: node.isValidTargetPos,
            isValidSourcePos: node.isValidSourcePos,
            parent: node.parentNode,
            parentNodeId: node.parentNode,
            zIndex: node.computedPosition.z ?? zIndex.value,
            targetPosition: node.targetPosition,
            sourcePosition: node.sourcePosition,
            label: node.label,
            dragHandle: node.dragHandle,
            onUpdateNodeInternals: updateInternals
          })
        ]
      );
    };
    function clampPosition2() {
      const nextPosition = node.computedPosition;
      const { computedPosition, position } = calcNextPosition(
        node,
        snapToGrid.value ? snapPosition(nextPosition, snapGrid.value) : nextPosition,
        emits.error,
        nodeExtent.value,
        parentNode.value
      );
      if (node.computedPosition.x !== computedPosition.x || node.computedPosition.y !== computedPosition.y) {
        node.computedPosition = { ...node.computedPosition, ...computedPosition };
      }
      if (node.position.x !== position.x || node.position.y !== position.y) {
        node.position = position;
      }
    }
    function updateInternals() {
      if (nodeElement.value) {
        updateNodeDimensions([{ id: props.id, nodeElement: nodeElement.value, forceUpdate: true }]);
      }
    }
    function onMouseEnter(event) {
      if (!(dragging == null ? void 0 : dragging.value)) {
        emit.mouseEnter({ event, node });
      }
    }
    function onMouseMove(event) {
      if (!(dragging == null ? void 0 : dragging.value)) {
        emit.mouseMove({ event, node });
      }
    }
    function onMouseLeave(event) {
      if (!(dragging == null ? void 0 : dragging.value)) {
        emit.mouseLeave({ event, node });
      }
    }
    function onContextMenu(event) {
      return emit.contextMenu({ event, node });
    }
    function onDoubleClick(event) {
      return emit.doubleClick({ event, node });
    }
    function onSelectNode(event) {
      if (isSelectable.value && (!selectNodesOnDrag.value || !isDraggable.value || nodeDragThreshold.value > 0)) {
        handleNodeClick(
          node,
          multiSelectionActive.value,
          addSelectedNodes,
          removeSelectedNodes,
          nodesSelectionActive,
          false,
          nodeElement.value
        );
      }
      emit.click({ event, node });
    }
    function onKeyDown(event) {
      if (isInputDOMNode(event) || disableKeyboardA11y.value) {
        return;
      }
      if (elementSelectionKeys.includes(event.key) && isSelectable.value) {
        const unselect = event.key === "Escape";
        handleNodeClick(
          node,
          multiSelectionActive.value,
          addSelectedNodes,
          removeSelectedNodes,
          nodesSelectionActive,
          unselect,
          nodeElement.value
        );
      } else if (isDraggable.value && node.selected && arrowKeyDiffs[event.key]) {
        event.preventDefault();
        ariaLiveMessage.value = `Moved selected node ${event.key.replace("Arrow", "").toLowerCase()}. New position, x: ${~~node.position.x}, y: ${~~node.position.y}`;
        updateNodePositions(
          {
            x: arrowKeyDiffs[event.key].x,
            y: arrowKeyDiffs[event.key].y
          },
          event.shiftKey
        );
      }
    }
  }
});
const NodeWrapper$1 = NodeWrapper;
const _hoisted_1$2 = {
  height: "0",
  width: "0"
};
const __default__$3 = {
  name: "EdgeLabelRenderer",
  compatConfig: { MODE: 3 }
};
const _sfc_main$3 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$3,
  setup(__props) {
    const { viewportRef } = useVueFlow();
    const teleportTarget = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => {
      var _a;
      return (_a = viewportRef.value) == null ? void 0 : _a.getElementsByClassName("vue-flow__edge-labels")[0];
    });
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("svg", null, [
        ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("foreignObject", _hoisted_1$2, [
          ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Teleport, {
            to: teleportTarget.value,
            disabled: !teleportTarget.value
          }, [
            (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderSlot)(_ctx.$slots, "default")
          ], 8, ["to", "disabled"]))
        ]))
      ]);
    };
  }
});
function useNodesInitialized(options = { includeHiddenNodes: false }) {
  const { nodes } = useVueFlow();
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
    if (nodes.value.length === 0) {
      return false;
    }
    for (const node of nodes.value) {
      if (options.includeHiddenNodes || !node.hidden) {
        if ((node == null ? void 0 : node.handleBounds) === void 0 || node.dimensions.width === 0 || node.dimensions.height === 0) {
          return false;
        }
      }
    }
    return true;
  });
}
const _hoisted_1$1 = { class: "vue-flow__nodes vue-flow__container" };
const __default__$2 = {
  name: "Nodes",
  compatConfig: { MODE: 3 }
};
const _sfc_main$2 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$2,
  setup(__props) {
    const { getNodes, updateNodeDimensions, emits } = useVueFlow();
    const nodesInitialized = useNodesInitialized();
    const resizeObserver = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)();
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
      nodesInitialized,
      (isInit) => {
        if (isInit) {
          (0,vue__WEBPACK_IMPORTED_MODULE_0__.nextTick)(() => {
            emits.nodesInitialized(getNodes.value);
          });
        }
      },
      { immediate: true }
    );
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(() => {
      resizeObserver.value = new ResizeObserver((entries) => {
        const updates = entries.map((entry) => {
          const id2 = entry.target.getAttribute("data-id");
          return {
            id: id2,
            nodeElement: entry.target,
            forceUpdate: true
          };
        });
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.nextTick)(() => updateNodeDimensions(updates));
      });
    });
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.onBeforeUnmount)(() => {
      var _a;
      return (_a = resizeObserver.value) == null ? void 0 : _a.disconnect();
    });
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1$1, [
        resizeObserver.value ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, { key: 0 }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)((0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(getNodes), (node, __, ___, _cached) => {
          const _memo = [node.id];
          if (_cached && _cached.key === node.id && (0,vue__WEBPACK_IMPORTED_MODULE_0__.isMemoSame)(_cached, _memo))
            return _cached;
          const _item = ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)((0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(NodeWrapper$1), {
            id: node.id,
            key: node.id,
            "resize-observer": resizeObserver.value
          }, null, 8, ["id", "resize-observer"]));
          _item.memo = _memo;
          return _item;
        }, _cache, 0), 128)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("", true)
      ]);
    };
  }
});
function useStylesLoadedWarning() {
  const { emits } = useVueFlow();
  (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(() => {
    if (isDev()) {
      const pane = document.querySelector(".vue-flow__pane");
      if (pane && !(window.getComputedStyle(pane).zIndex === "1")) {
        emits.error(new VueFlowError(ErrorCode.MISSING_STYLES));
      }
    }
  });
}
const _hoisted_1 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", { class: "vue-flow__edge-labels" }, null, -1);
const __default__$1 = {
  name: "VueFlow",
  compatConfig: { MODE: 3 }
};
const _sfc_main$1 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__$1,
  props: {
    id: {},
    modelValue: {},
    nodes: {},
    edges: {},
    edgeTypes: {},
    nodeTypes: {},
    connectionMode: {},
    connectionLineType: {},
    connectionLineStyle: { default: void 0 },
    connectionLineOptions: { default: void 0 },
    connectionRadius: {},
    isValidConnection: { type: [Function, null], default: void 0 },
    deleteKeyCode: { default: void 0 },
    selectionKeyCode: { type: [Boolean, null], default: void 0 },
    multiSelectionKeyCode: { default: void 0 },
    zoomActivationKeyCode: { default: void 0 },
    panActivationKeyCode: { default: void 0 },
    snapToGrid: { type: Boolean, default: void 0 },
    snapGrid: {},
    onlyRenderVisibleElements: { type: Boolean, default: void 0 },
    edgesUpdatable: { type: [Boolean, String], default: void 0 },
    nodesDraggable: { type: Boolean, default: void 0 },
    nodesConnectable: { type: Boolean, default: void 0 },
    nodeDragThreshold: {},
    elementsSelectable: { type: Boolean, default: void 0 },
    selectNodesOnDrag: { type: Boolean, default: void 0 },
    panOnDrag: { type: [Boolean, Array], default: void 0 },
    minZoom: {},
    maxZoom: {},
    defaultViewport: {},
    translateExtent: {},
    nodeExtent: {},
    defaultMarkerColor: {},
    zoomOnScroll: { type: Boolean, default: void 0 },
    zoomOnPinch: { type: Boolean, default: void 0 },
    panOnScroll: { type: Boolean, default: void 0 },
    panOnScrollSpeed: {},
    panOnScrollMode: {},
    paneClickDistance: {},
    zoomOnDoubleClick: { type: Boolean, default: void 0 },
    preventScrolling: { type: Boolean, default: void 0 },
    selectionMode: {},
    edgeUpdaterRadius: {},
    fitViewOnInit: { type: Boolean, default: void 0 },
    connectOnClick: { type: Boolean, default: void 0 },
    applyDefault: { type: Boolean, default: void 0 },
    autoConnect: { type: [Boolean, Function], default: void 0 },
    noDragClassName: {},
    noWheelClassName: {},
    noPanClassName: {},
    defaultEdgeOptions: {},
    elevateEdgesOnSelect: { type: Boolean, default: void 0 },
    elevateNodesOnSelect: { type: Boolean, default: void 0 },
    disableKeyboardA11y: { type: Boolean, default: void 0 },
    edgesFocusable: { type: Boolean, default: void 0 },
    nodesFocusable: { type: Boolean, default: void 0 },
    autoPanOnConnect: { type: Boolean, default: void 0 },
    autoPanOnNodeDrag: { type: Boolean, default: void 0 },
    autoPanSpeed: {}
  },
  emits: ["nodesChange", "edgesChange", "nodesInitialized", "paneReady", "init", "updateNodeInternals", "error", "connect", "connectStart", "connectEnd", "clickConnectStart", "clickConnectEnd", "moveStart", "move", "moveEnd", "selectionDragStart", "selectionDrag", "selectionDragStop", "selectionContextMenu", "selectionStart", "selectionEnd", "viewportChangeStart", "viewportChange", "viewportChangeEnd", "paneScroll", "paneClick", "paneContextMenu", "paneMouseEnter", "paneMouseMove", "paneMouseLeave", "edgeUpdate", "edgeContextMenu", "edgeMouseEnter", "edgeMouseMove", "edgeMouseLeave", "edgeDoubleClick", "edgeClick", "edgeUpdateStart", "edgeUpdateEnd", "nodeContextMenu", "nodeMouseEnter", "nodeMouseMove", "nodeMouseLeave", "nodeDoubleClick", "nodeClick", "nodeDragStart", "nodeDrag", "nodeDragStop", "miniMapNodeClick", "miniMapNodeDoubleClick", "miniMapNodeMouseEnter", "miniMapNodeMouseMove", "miniMapNodeMouseLeave", "update:modelValue", "update:nodes", "update:edges"],
  setup(__props, { expose: __expose, emit }) {
    const props = __props;
    const slots = (0,vue__WEBPACK_IMPORTED_MODULE_0__.useSlots)();
    const modelValue = useVModel(props, "modelValue", emit);
    const modelNodes = useVModel(props, "nodes", emit);
    const modelEdges = useVModel(props, "edges", emit);
    const instance = useVueFlow(props);
    const dispose = useWatchProps({ modelValue, nodes: modelNodes, edges: modelEdges }, props, instance);
    useHooks(emit, instance.hooks);
    useOnInitHandler();
    useStylesLoadedWarning();
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.provide)(Slots, slots);
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.onUnmounted)(() => {
      dispose();
    });
    __expose(instance);
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
        ref: (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(instance).vueFlowRef,
        class: "vue-flow"
      }, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_sfc_main$8, null, {
          default: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(() => [
            (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_sfc_main$4),
            _hoisted_1,
            (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_sfc_main$2),
            (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderSlot)(_ctx.$slots, "zoom-pane")
          ]),
          _: 3
        }),
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderSlot)(_ctx.$slots, "default"),
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_sfc_main$7)
      ], 512);
    };
  }
});
const __default__ = {
  name: "Panel",
  compatConfig: { MODE: 3 }
};
const _sfc_main = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  ...__default__,
  props: {
    position: {}
  },
  setup(__props) {
    const props = __props;
    const { userSelectionActive } = useVueFlow();
    const positionClasses = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => `${props.position}`.split("-"));
    return (_ctx, _cache) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
        class: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(["vue-flow__panel", positionClasses.value]),
        style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)({ pointerEvents: (0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(userSelectionActive) ? "none" : "all" })
      }, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderSlot)(_ctx.$slots, "default")
      ], 6);
    };
  }
});
function useConnection() {
  const {
    connectionStartHandle: startHandle,
    connectionEndHandle: endHandle,
    connectionStatus: status,
    connectionPosition: position
  } = useVueFlow();
  return {
    startHandle,
    endHandle,
    status,
    position
  };
}
function useHandleConnections(params) {
  const { type, id: id2, nodeId, onConnect, onDisconnect } = params;
  const { connectionLookup } = useVueFlow();
  const _nodeId = useNodeId();
  const currentNodeId = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId) ?? _nodeId);
  const handleType = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(type));
  const handleId = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRef)(() => (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(id2) ?? null);
  const prevConnections = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
  const connections = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)();
  (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
    () => connectionLookup.value.get(`${currentNodeId.value}-${handleType.value}-${handleId.value}`),
    (nextConnections) => {
      if (areConnectionMapsEqual(connections.value, nextConnections)) {
        return;
      }
      connections.value = nextConnections;
    },
    { immediate: true }
  );
  (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
    [connections, () => typeof onConnect !== "undefined", () => typeof onDisconnect !== "undefined"],
    ([currentConnections = /* @__PURE__ */ new Map()]) => {
      if (prevConnections.value && prevConnections.value !== currentConnections) {
        handleConnectionChange(prevConnections.value, currentConnections, onDisconnect);
        handleConnectionChange(currentConnections, prevConnections.value, onConnect);
      }
      prevConnections.value = currentConnections;
    },
    { immediate: true }
  );
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
    if (!connections.value) {
      return [];
    }
    return Array.from(connections.value.values());
  });
}
function useNodeConnections(params = {}) {
  const { handleType, handleId, nodeId, onConnect, onDisconnect } = params;
  const { connectionLookup } = useVueFlow();
  const _nodeId = useNodeId();
  const prevConnections = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
  const connections = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)();
  const lookupKey = (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
    const currNodeId = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(nodeId) ?? _nodeId;
    const currentHandleType = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(handleType);
    const currHandleId = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(handleId);
    let handleSuffix = "";
    if (currentHandleType) {
      handleSuffix = currHandleId ? `-${currentHandleType}-${currHandleId}` : `-${currentHandleType}`;
    }
    return `${currNodeId}${handleSuffix}`;
  });
  (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
    () => connectionLookup.value.get(lookupKey.value),
    (nextConnections) => {
      if (areConnectionMapsEqual(connections.value, nextConnections)) {
        return;
      }
      connections.value = nextConnections;
    },
    { immediate: true }
  );
  (0,vue__WEBPACK_IMPORTED_MODULE_0__.watch)(
    [connections, () => typeof onConnect !== "undefined", () => typeof onDisconnect !== "undefined"],
    ([currentConnections = /* @__PURE__ */ new Map()]) => {
      if (prevConnections.value && prevConnections.value !== currentConnections) {
        handleConnectionChange(prevConnections.value, currentConnections, onDisconnect);
        handleConnectionChange(currentConnections, prevConnections.value, onConnect);
      }
      prevConnections.value = currentConnections;
    },
    { immediate: true }
  );
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)(() => {
    if (!connections.value) {
      return [];
    }
    return Array.from(connections.value.values());
  });
}
function useNodesData(_nodeIds) {
  const { findNode } = useVueFlow();
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)({
    get() {
      const nodeIds = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(_nodeIds);
      if (!Array.isArray(nodeIds)) {
        const node = findNode(nodeIds);
        if (node) {
          return {
            id: node.id,
            type: node.type,
            data: node.data
          };
        }
        return null;
      }
      const data = [];
      for (const nodeId of nodeIds) {
        const node = findNode(nodeId);
        if (node) {
          data.push({
            id: node.id,
            type: node.type,
            data: node.data
          });
        }
      }
      return data;
    },
    set() {
      warn("You are trying to set node data via useNodesData. This is not supported.");
    }
  });
}
function useEdgesData(_edgeIds) {
  const { findEdge } = useVueFlow();
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.computed)({
    get() {
      const edgeIds = (0,vue__WEBPACK_IMPORTED_MODULE_0__.toValue)(_edgeIds);
      if (!Array.isArray(edgeIds)) {
        const edge = findEdge(edgeIds);
        if (edge) {
          return {
            id: edge.id,
            type: edge.type,
            data: edge.data ?? null
          };
        }
        return null;
      }
      const data = [];
      for (const edgeId of edgeIds) {
        const edge = findEdge(edgeId);
        if (edge) {
          data.push({
            id: edge.id,
            type: edge.type,
            data: edge.data ?? null
          });
        }
      }
      return data;
    },
    set() {
      warn("You are trying to set edge data via useEdgesData. This is not supported.");
    }
  });
}



/***/ }),

/***/ "./node_modules/axios/lib/adapters/adapters.js":
/*!*****************************************************!*\
  !*** ./node_modules/axios/lib/adapters/adapters.js ***!
  \*****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _http_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./http.js */ "./node_modules/axios/lib/helpers/null.js");
/* harmony import */ var _xhr_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./xhr.js */ "./node_modules/axios/lib/adapters/xhr.js");
/* harmony import */ var _fetch_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./fetch.js */ "./node_modules/axios/lib/adapters/fetch.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");






const knownAdapters = {
  http: _http_js__WEBPACK_IMPORTED_MODULE_0__["default"],
  xhr: _xhr_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  fetch: _fetch_js__WEBPACK_IMPORTED_MODULE_2__["default"]
}

_utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].forEach(knownAdapters, (fn, value) => {
  if (fn) {
    try {
      Object.defineProperty(fn, 'name', {value});
    } catch (e) {
      // eslint-disable-next-line no-empty
    }
    Object.defineProperty(fn, 'adapterName', {value});
  }
});

const renderReason = (reason) => `- ${reason}`;

const isResolvedHandle = (adapter) => _utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].isFunction(adapter) || adapter === null || adapter === false;

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  getAdapter: (adapters) => {
    adapters = _utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].isArray(adapters) ? adapters : [adapters];

    const {length} = adapters;
    let nameOrAdapter;
    let adapter;

    const rejectedReasons = {};

    for (let i = 0; i < length; i++) {
      nameOrAdapter = adapters[i];
      let id;

      adapter = nameOrAdapter;

      if (!isResolvedHandle(nameOrAdapter)) {
        adapter = knownAdapters[(id = String(nameOrAdapter)).toLowerCase()];

        if (adapter === undefined) {
          throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_4__["default"](`Unknown adapter '${id}'`);
        }
      }

      if (adapter) {
        break;
      }

      rejectedReasons[id || '#' + i] = adapter;
    }

    if (!adapter) {

      const reasons = Object.entries(rejectedReasons)
        .map(([id, state]) => `adapter ${id} ` +
          (state === false ? 'is not supported by the environment' : 'is not available in the build')
        );

      let s = length ?
        (reasons.length > 1 ? 'since :\n' + reasons.map(renderReason).join('\n') : ' ' + renderReason(reasons[0])) :
        'as no adapter specified';

      throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_4__["default"](
        `There is no suitable adapter to dispatch the request ` + s,
        'ERR_NOT_SUPPORT'
      );
    }

    return adapter;
  },
  adapters: knownAdapters
});


/***/ }),

/***/ "./node_modules/axios/lib/adapters/fetch.js":
/*!**************************************************!*\
  !*** ./node_modules/axios/lib/adapters/fetch.js ***!
  \**************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _helpers_composeSignals_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../helpers/composeSignals.js */ "./node_modules/axios/lib/helpers/composeSignals.js");
/* harmony import */ var _helpers_trackStream_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../helpers/trackStream.js */ "./node_modules/axios/lib/helpers/trackStream.js");
/* harmony import */ var _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../core/AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");
/* harmony import */ var _helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../helpers/progressEventReducer.js */ "./node_modules/axios/lib/helpers/progressEventReducer.js");
/* harmony import */ var _helpers_resolveConfig_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../helpers/resolveConfig.js */ "./node_modules/axios/lib/helpers/resolveConfig.js");
/* harmony import */ var _core_settle_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../core/settle.js */ "./node_modules/axios/lib/core/settle.js");










const isFetchSupported = typeof fetch === 'function' && typeof Request === 'function' && typeof Response === 'function';
const isReadableStreamSupported = isFetchSupported && typeof ReadableStream === 'function';

// used only inside the fetch adapter
const encodeText = isFetchSupported && (typeof TextEncoder === 'function' ?
    ((encoder) => (str) => encoder.encode(str))(new TextEncoder()) :
    async (str) => new Uint8Array(await new Response(str).arrayBuffer())
);

const test = (fn, ...args) => {
  try {
    return !!fn(...args);
  } catch (e) {
    return false
  }
}

const supportsRequestStream = isReadableStreamSupported && test(() => {
  let duplexAccessed = false;

  const hasContentType = new Request(_platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].origin, {
    body: new ReadableStream(),
    method: 'POST',
    get duplex() {
      duplexAccessed = true;
      return 'half';
    },
  }).headers.has('Content-Type');

  return duplexAccessed && !hasContentType;
});

const DEFAULT_CHUNK_SIZE = 64 * 1024;

const supportsResponseStream = isReadableStreamSupported &&
  test(() => _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isReadableStream(new Response('').body));


const resolvers = {
  stream: supportsResponseStream && ((res) => res.body)
};

isFetchSupported && (((res) => {
  ['text', 'arrayBuffer', 'blob', 'formData', 'stream'].forEach(type => {
    !resolvers[type] && (resolvers[type] = _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isFunction(res[type]) ? (res) => res[type]() :
      (_, config) => {
        throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__["default"](`Response type '${type}' is not supported`, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__["default"].ERR_NOT_SUPPORT, config);
      })
  });
})(new Response));

const getBodyLength = async (body) => {
  if (body == null) {
    return 0;
  }

  if(_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isBlob(body)) {
    return body.size;
  }

  if(_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isSpecCompliantForm(body)) {
    const _request = new Request(_platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].origin, {
      method: 'POST',
      body,
    });
    return (await _request.arrayBuffer()).byteLength;
  }

  if(_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isArrayBufferView(body) || _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isArrayBuffer(body)) {
    return body.byteLength;
  }

  if(_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isURLSearchParams(body)) {
    body = body + '';
  }

  if(_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isString(body)) {
    return (await encodeText(body)).byteLength;
  }
}

const resolveBodyLength = async (headers, body) => {
  const length = _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].toFiniteNumber(headers.getContentLength());

  return length == null ? getBodyLength(body) : length;
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (isFetchSupported && (async (config) => {
  let {
    url,
    method,
    data,
    signal,
    cancelToken,
    timeout,
    onDownloadProgress,
    onUploadProgress,
    responseType,
    headers,
    withCredentials = 'same-origin',
    fetchOptions
  } = (0,_helpers_resolveConfig_js__WEBPACK_IMPORTED_MODULE_3__["default"])(config);

  responseType = responseType ? (responseType + '').toLowerCase() : 'text';

  let composedSignal = (0,_helpers_composeSignals_js__WEBPACK_IMPORTED_MODULE_4__["default"])([signal, cancelToken && cancelToken.toAbortSignal()], timeout);

  let request;

  const unsubscribe = composedSignal && composedSignal.unsubscribe && (() => {
      composedSignal.unsubscribe();
  });

  let requestContentLength;

  try {
    if (
      onUploadProgress && supportsRequestStream && method !== 'get' && method !== 'head' &&
      (requestContentLength = await resolveBodyLength(headers, data)) !== 0
    ) {
      let _request = new Request(url, {
        method: 'POST',
        body: data,
        duplex: "half"
      });

      let contentTypeHeader;

      if (_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isFormData(data) && (contentTypeHeader = _request.headers.get('content-type'))) {
        headers.setContentType(contentTypeHeader)
      }

      if (_request.body) {
        const [onProgress, flush] = (0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__.progressEventDecorator)(
          requestContentLength,
          (0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__.progressEventReducer)((0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__.asyncDecorator)(onUploadProgress))
        );

        data = (0,_helpers_trackStream_js__WEBPACK_IMPORTED_MODULE_6__.trackStream)(_request.body, DEFAULT_CHUNK_SIZE, onProgress, flush);
      }
    }

    if (!_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isString(withCredentials)) {
      withCredentials = withCredentials ? 'include' : 'omit';
    }

    // Cloudflare Workers throws when credentials are defined
    // see https://github.com/cloudflare/workerd/issues/902
    const isCredentialsSupported = "credentials" in Request.prototype;
    request = new Request(url, {
      ...fetchOptions,
      signal: composedSignal,
      method: method.toUpperCase(),
      headers: headers.normalize().toJSON(),
      body: data,
      duplex: "half",
      credentials: isCredentialsSupported ? withCredentials : undefined
    });

    let response = await fetch(request);

    const isStreamResponse = supportsResponseStream && (responseType === 'stream' || responseType === 'response');

    if (supportsResponseStream && (onDownloadProgress || (isStreamResponse && unsubscribe))) {
      const options = {};

      ['status', 'statusText', 'headers'].forEach(prop => {
        options[prop] = response[prop];
      });

      const responseContentLength = _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].toFiniteNumber(response.headers.get('content-length'));

      const [onProgress, flush] = onDownloadProgress && (0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__.progressEventDecorator)(
        responseContentLength,
        (0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__.progressEventReducer)((0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__.asyncDecorator)(onDownloadProgress), true)
      ) || [];

      response = new Response(
        (0,_helpers_trackStream_js__WEBPACK_IMPORTED_MODULE_6__.trackStream)(response.body, DEFAULT_CHUNK_SIZE, onProgress, () => {
          flush && flush();
          unsubscribe && unsubscribe();
        }),
        options
      );
    }

    responseType = responseType || 'text';

    let responseData = await resolvers[_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].findKey(resolvers, responseType) || 'text'](response, config);

    !isStreamResponse && unsubscribe && unsubscribe();

    return await new Promise((resolve, reject) => {
      (0,_core_settle_js__WEBPACK_IMPORTED_MODULE_7__["default"])(resolve, reject, {
        data: responseData,
        headers: _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_8__["default"].from(response.headers),
        status: response.status,
        statusText: response.statusText,
        config,
        request
      })
    })
  } catch (err) {
    unsubscribe && unsubscribe();

    if (err && err.name === 'TypeError' && /Load failed|fetch/i.test(err.message)) {
      throw Object.assign(
        new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__["default"]('Network Error', _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__["default"].ERR_NETWORK, config, request),
        {
          cause: err.cause || err
        }
      )
    }

    throw _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__["default"].from(err, err && err.code, config, request);
  }
}));




/***/ }),

/***/ "./node_modules/axios/lib/adapters/xhr.js":
/*!************************************************!*\
  !*** ./node_modules/axios/lib/adapters/xhr.js ***!
  \************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _core_settle_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../core/settle.js */ "./node_modules/axios/lib/core/settle.js");
/* harmony import */ var _defaults_transitional_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../defaults/transitional.js */ "./node_modules/axios/lib/defaults/transitional.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../cancel/CanceledError.js */ "./node_modules/axios/lib/cancel/CanceledError.js");
/* harmony import */ var _helpers_parseProtocol_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../helpers/parseProtocol.js */ "./node_modules/axios/lib/helpers/parseProtocol.js");
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");
/* harmony import */ var _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../core/AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");
/* harmony import */ var _helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../helpers/progressEventReducer.js */ "./node_modules/axios/lib/helpers/progressEventReducer.js");
/* harmony import */ var _helpers_resolveConfig_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/resolveConfig.js */ "./node_modules/axios/lib/helpers/resolveConfig.js");











const isXHRAdapterSupported = typeof XMLHttpRequest !== 'undefined';

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (isXHRAdapterSupported && function (config) {
  return new Promise(function dispatchXhrRequest(resolve, reject) {
    const _config = (0,_helpers_resolveConfig_js__WEBPACK_IMPORTED_MODULE_0__["default"])(config);
    let requestData = _config.data;
    const requestHeaders = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(_config.headers).normalize();
    let {responseType, onUploadProgress, onDownloadProgress} = _config;
    let onCanceled;
    let uploadThrottled, downloadThrottled;
    let flushUpload, flushDownload;

    function done() {
      flushUpload && flushUpload(); // flush events
      flushDownload && flushDownload(); // flush events

      _config.cancelToken && _config.cancelToken.unsubscribe(onCanceled);

      _config.signal && _config.signal.removeEventListener('abort', onCanceled);
    }

    let request = new XMLHttpRequest();

    request.open(_config.method.toUpperCase(), _config.url, true);

    // Set the request timeout in MS
    request.timeout = _config.timeout;

    function onloadend() {
      if (!request) {
        return;
      }
      // Prepare the response
      const responseHeaders = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(
        'getAllResponseHeaders' in request && request.getAllResponseHeaders()
      );
      const responseData = !responseType || responseType === 'text' || responseType === 'json' ?
        request.responseText : request.response;
      const response = {
        data: responseData,
        status: request.status,
        statusText: request.statusText,
        headers: responseHeaders,
        config,
        request
      };

      (0,_core_settle_js__WEBPACK_IMPORTED_MODULE_2__["default"])(function _resolve(value) {
        resolve(value);
        done();
      }, function _reject(err) {
        reject(err);
        done();
      }, response);

      // Clean up request
      request = null;
    }

    if ('onloadend' in request) {
      // Use onloadend if available
      request.onloadend = onloadend;
    } else {
      // Listen for ready state to emulate onloadend
      request.onreadystatechange = function handleLoad() {
        if (!request || request.readyState !== 4) {
          return;
        }

        // The request errored out and we didn't get a response, this will be
        // handled by onerror instead
        // With one exception: request that using file: protocol, most browsers
        // will return status as 0 even though it's a successful request
        if (request.status === 0 && !(request.responseURL && request.responseURL.indexOf('file:') === 0)) {
          return;
        }
        // readystate handler is calling before onerror or ontimeout handlers,
        // so we should call onloadend on the next 'tick'
        setTimeout(onloadend);
      };
    }

    // Handle browser request cancellation (as opposed to a manual cancellation)
    request.onabort = function handleAbort() {
      if (!request) {
        return;
      }

      reject(new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"]('Request aborted', _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"].ECONNABORTED, config, request));

      // Clean up request
      request = null;
    };

    // Handle low level network errors
    request.onerror = function handleError() {
      // Real errors are hidden from us by the browser
      // onerror should only fire if it's a network error
      reject(new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"]('Network Error', _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"].ERR_NETWORK, config, request));

      // Clean up request
      request = null;
    };

    // Handle timeout
    request.ontimeout = function handleTimeout() {
      let timeoutErrorMessage = _config.timeout ? 'timeout of ' + _config.timeout + 'ms exceeded' : 'timeout exceeded';
      const transitional = _config.transitional || _defaults_transitional_js__WEBPACK_IMPORTED_MODULE_4__["default"];
      if (_config.timeoutErrorMessage) {
        timeoutErrorMessage = _config.timeoutErrorMessage;
      }
      reject(new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"](
        timeoutErrorMessage,
        transitional.clarifyTimeoutError ? _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"].ETIMEDOUT : _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"].ECONNABORTED,
        config,
        request));

      // Clean up request
      request = null;
    };

    // Remove Content-Type if data is undefined
    requestData === undefined && requestHeaders.setContentType(null);

    // Add headers to the request
    if ('setRequestHeader' in request) {
      _utils_js__WEBPACK_IMPORTED_MODULE_5__["default"].forEach(requestHeaders.toJSON(), function setRequestHeader(val, key) {
        request.setRequestHeader(key, val);
      });
    }

    // Add withCredentials to request if needed
    if (!_utils_js__WEBPACK_IMPORTED_MODULE_5__["default"].isUndefined(_config.withCredentials)) {
      request.withCredentials = !!_config.withCredentials;
    }

    // Add responseType to request if needed
    if (responseType && responseType !== 'json') {
      request.responseType = _config.responseType;
    }

    // Handle progress if needed
    if (onDownloadProgress) {
      ([downloadThrottled, flushDownload] = (0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_6__.progressEventReducer)(onDownloadProgress, true));
      request.addEventListener('progress', downloadThrottled);
    }

    // Not all browsers support upload events
    if (onUploadProgress && request.upload) {
      ([uploadThrottled, flushUpload] = (0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_6__.progressEventReducer)(onUploadProgress));

      request.upload.addEventListener('progress', uploadThrottled);

      request.upload.addEventListener('loadend', flushUpload);
    }

    if (_config.cancelToken || _config.signal) {
      // Handle cancellation
      // eslint-disable-next-line func-names
      onCanceled = cancel => {
        if (!request) {
          return;
        }
        reject(!cancel || cancel.type ? new _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_7__["default"](null, config, request) : cancel);
        request.abort();
        request = null;
      };

      _config.cancelToken && _config.cancelToken.subscribe(onCanceled);
      if (_config.signal) {
        _config.signal.aborted ? onCanceled() : _config.signal.addEventListener('abort', onCanceled);
      }
    }

    const protocol = (0,_helpers_parseProtocol_js__WEBPACK_IMPORTED_MODULE_8__["default"])(_config.url);

    if (protocol && _platform_index_js__WEBPACK_IMPORTED_MODULE_9__["default"].protocols.indexOf(protocol) === -1) {
      reject(new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"]('Unsupported protocol ' + protocol + ':', _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"].ERR_BAD_REQUEST, config));
      return;
    }


    // Send the request
    request.send(requestData || null);
  });
});


/***/ }),

/***/ "./node_modules/axios/lib/axios.js":
/*!*****************************************!*\
  !*** ./node_modules/axios/lib/axios.js ***!
  \*****************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _helpers_bind_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./helpers/bind.js */ "./node_modules/axios/lib/helpers/bind.js");
/* harmony import */ var _core_Axios_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./core/Axios.js */ "./node_modules/axios/lib/core/Axios.js");
/* harmony import */ var _core_mergeConfig_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./core/mergeConfig.js */ "./node_modules/axios/lib/core/mergeConfig.js");
/* harmony import */ var _defaults_index_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./defaults/index.js */ "./node_modules/axios/lib/defaults/index.js");
/* harmony import */ var _helpers_formDataToJSON_js__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./helpers/formDataToJSON.js */ "./node_modules/axios/lib/helpers/formDataToJSON.js");
/* harmony import */ var _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./cancel/CanceledError.js */ "./node_modules/axios/lib/cancel/CanceledError.js");
/* harmony import */ var _cancel_CancelToken_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./cancel/CancelToken.js */ "./node_modules/axios/lib/cancel/CancelToken.js");
/* harmony import */ var _cancel_isCancel_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./cancel/isCancel.js */ "./node_modules/axios/lib/cancel/isCancel.js");
/* harmony import */ var _env_data_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./env/data.js */ "./node_modules/axios/lib/env/data.js");
/* harmony import */ var _helpers_toFormData_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./helpers/toFormData.js */ "./node_modules/axios/lib/helpers/toFormData.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _helpers_spread_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./helpers/spread.js */ "./node_modules/axios/lib/helpers/spread.js");
/* harmony import */ var _helpers_isAxiosError_js__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./helpers/isAxiosError.js */ "./node_modules/axios/lib/helpers/isAxiosError.js");
/* harmony import */ var _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./core/AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");
/* harmony import */ var _adapters_adapters_js__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./adapters/adapters.js */ "./node_modules/axios/lib/adapters/adapters.js");
/* harmony import */ var _helpers_HttpStatusCode_js__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./helpers/HttpStatusCode.js */ "./node_modules/axios/lib/helpers/HttpStatusCode.js");




















/**
 * Create an instance of Axios
 *
 * @param {Object} defaultConfig The default config for the instance
 *
 * @returns {Axios} A new instance of Axios
 */
function createInstance(defaultConfig) {
  const context = new _core_Axios_js__WEBPACK_IMPORTED_MODULE_0__["default"](defaultConfig);
  const instance = (0,_helpers_bind_js__WEBPACK_IMPORTED_MODULE_1__["default"])(_core_Axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].prototype.request, context);

  // Copy axios.prototype to instance
  _utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].extend(instance, _core_Axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].prototype, context, {allOwnKeys: true});

  // Copy context to instance
  _utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].extend(instance, context, null, {allOwnKeys: true});

  // Factory for creating new instances
  instance.create = function create(instanceConfig) {
    return createInstance((0,_core_mergeConfig_js__WEBPACK_IMPORTED_MODULE_3__["default"])(defaultConfig, instanceConfig));
  };

  return instance;
}

// Create the default instance to be exported
const axios = createInstance(_defaults_index_js__WEBPACK_IMPORTED_MODULE_4__["default"]);

// Expose Axios class to allow class inheritance
axios.Axios = _core_Axios_js__WEBPACK_IMPORTED_MODULE_0__["default"];

// Expose Cancel & CancelToken
axios.CanceledError = _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_5__["default"];
axios.CancelToken = _cancel_CancelToken_js__WEBPACK_IMPORTED_MODULE_6__["default"];
axios.isCancel = _cancel_isCancel_js__WEBPACK_IMPORTED_MODULE_7__["default"];
axios.VERSION = _env_data_js__WEBPACK_IMPORTED_MODULE_8__.VERSION;
axios.toFormData = _helpers_toFormData_js__WEBPACK_IMPORTED_MODULE_9__["default"];

// Expose AxiosError class
axios.AxiosError = _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_10__["default"];

// alias for CanceledError for backward compatibility
axios.Cancel = axios.CanceledError;

// Expose all/spread
axios.all = function all(promises) {
  return Promise.all(promises);
};

axios.spread = _helpers_spread_js__WEBPACK_IMPORTED_MODULE_11__["default"];

// Expose isAxiosError
axios.isAxiosError = _helpers_isAxiosError_js__WEBPACK_IMPORTED_MODULE_12__["default"];

// Expose mergeConfig
axios.mergeConfig = _core_mergeConfig_js__WEBPACK_IMPORTED_MODULE_3__["default"];

axios.AxiosHeaders = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_13__["default"];

axios.formToJSON = thing => (0,_helpers_formDataToJSON_js__WEBPACK_IMPORTED_MODULE_14__["default"])(_utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].isHTMLForm(thing) ? new FormData(thing) : thing);

axios.getAdapter = _adapters_adapters_js__WEBPACK_IMPORTED_MODULE_15__["default"].getAdapter;

axios.HttpStatusCode = _helpers_HttpStatusCode_js__WEBPACK_IMPORTED_MODULE_16__["default"];

axios.default = axios;

// this module should only have a default export
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (axios);


/***/ }),

/***/ "./node_modules/axios/lib/cancel/CancelToken.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/cancel/CancelToken.js ***!
  \******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _CanceledError_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CanceledError.js */ "./node_modules/axios/lib/cancel/CanceledError.js");




/**
 * A `CancelToken` is an object that can be used to request cancellation of an operation.
 *
 * @param {Function} executor The executor function.
 *
 * @returns {CancelToken}
 */
class CancelToken {
  constructor(executor) {
    if (typeof executor !== 'function') {
      throw new TypeError('executor must be a function.');
    }

    let resolvePromise;

    this.promise = new Promise(function promiseExecutor(resolve) {
      resolvePromise = resolve;
    });

    const token = this;

    // eslint-disable-next-line func-names
    this.promise.then(cancel => {
      if (!token._listeners) return;

      let i = token._listeners.length;

      while (i-- > 0) {
        token._listeners[i](cancel);
      }
      token._listeners = null;
    });

    // eslint-disable-next-line func-names
    this.promise.then = onfulfilled => {
      let _resolve;
      // eslint-disable-next-line func-names
      const promise = new Promise(resolve => {
        token.subscribe(resolve);
        _resolve = resolve;
      }).then(onfulfilled);

      promise.cancel = function reject() {
        token.unsubscribe(_resolve);
      };

      return promise;
    };

    executor(function cancel(message, config, request) {
      if (token.reason) {
        // Cancellation has already been requested
        return;
      }

      token.reason = new _CanceledError_js__WEBPACK_IMPORTED_MODULE_0__["default"](message, config, request);
      resolvePromise(token.reason);
    });
  }

  /**
   * Throws a `CanceledError` if cancellation has been requested.
   */
  throwIfRequested() {
    if (this.reason) {
      throw this.reason;
    }
  }

  /**
   * Subscribe to the cancel signal
   */

  subscribe(listener) {
    if (this.reason) {
      listener(this.reason);
      return;
    }

    if (this._listeners) {
      this._listeners.push(listener);
    } else {
      this._listeners = [listener];
    }
  }

  /**
   * Unsubscribe from the cancel signal
   */

  unsubscribe(listener) {
    if (!this._listeners) {
      return;
    }
    const index = this._listeners.indexOf(listener);
    if (index !== -1) {
      this._listeners.splice(index, 1);
    }
  }

  toAbortSignal() {
    const controller = new AbortController();

    const abort = (err) => {
      controller.abort(err);
    };

    this.subscribe(abort);

    controller.signal.unsubscribe = () => this.unsubscribe(abort);

    return controller.signal;
  }

  /**
   * Returns an object that contains a new `CancelToken` and a function that, when called,
   * cancels the `CancelToken`.
   */
  static source() {
    let cancel;
    const token = new CancelToken(function executor(c) {
      cancel = c;
    });
    return {
      token,
      cancel
    };
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CancelToken);


/***/ }),

/***/ "./node_modules/axios/lib/cancel/CanceledError.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/cancel/CanceledError.js ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");





/**
 * A `CanceledError` is an object that is thrown when an operation is canceled.
 *
 * @param {string=} message The message.
 * @param {Object=} config The config.
 * @param {Object=} request The request.
 *
 * @returns {CanceledError} The created error.
 */
function CanceledError(message, config, request) {
  // eslint-disable-next-line no-eq-null,eqeqeq
  _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"].call(this, message == null ? 'canceled' : message, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"].ERR_CANCELED, config, request);
  this.name = 'CanceledError';
}

_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].inherits(CanceledError, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"], {
  __CANCEL__: true
});

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CanceledError);


/***/ }),

/***/ "./node_modules/axios/lib/cancel/isCancel.js":
/*!***************************************************!*\
  !*** ./node_modules/axios/lib/cancel/isCancel.js ***!
  \***************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ isCancel)
/* harmony export */ });


function isCancel(value) {
  return !!(value && value.__CANCEL__);
}


/***/ }),

/***/ "./node_modules/axios/lib/core/Axios.js":
/*!**********************************************!*\
  !*** ./node_modules/axios/lib/core/Axios.js ***!
  \**********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _helpers_buildURL_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../helpers/buildURL.js */ "./node_modules/axios/lib/helpers/buildURL.js");
/* harmony import */ var _InterceptorManager_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./InterceptorManager.js */ "./node_modules/axios/lib/core/InterceptorManager.js");
/* harmony import */ var _dispatchRequest_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./dispatchRequest.js */ "./node_modules/axios/lib/core/dispatchRequest.js");
/* harmony import */ var _mergeConfig_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./mergeConfig.js */ "./node_modules/axios/lib/core/mergeConfig.js");
/* harmony import */ var _buildFullPath_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./buildFullPath.js */ "./node_modules/axios/lib/core/buildFullPath.js");
/* harmony import */ var _helpers_validator_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/validator.js */ "./node_modules/axios/lib/helpers/validator.js");
/* harmony import */ var _AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");











const validators = _helpers_validator_js__WEBPACK_IMPORTED_MODULE_0__["default"].validators;

/**
 * Create a new instance of Axios
 *
 * @param {Object} instanceConfig The default config for the instance
 *
 * @return {Axios} A new instance of Axios
 */
class Axios {
  constructor(instanceConfig) {
    this.defaults = instanceConfig || {};
    this.interceptors = {
      request: new _InterceptorManager_js__WEBPACK_IMPORTED_MODULE_1__["default"](),
      response: new _InterceptorManager_js__WEBPACK_IMPORTED_MODULE_1__["default"]()
    };
  }

  /**
   * Dispatch a request
   *
   * @param {String|Object} configOrUrl The config specific for this request (merged with this.defaults)
   * @param {?Object} config
   *
   * @returns {Promise} The Promise to be fulfilled
   */
  async request(configOrUrl, config) {
    try {
      return await this._request(configOrUrl, config);
    } catch (err) {
      if (err instanceof Error) {
        let dummy = {};

        Error.captureStackTrace ? Error.captureStackTrace(dummy) : (dummy = new Error());

        // slice off the Error: ... line
        const stack = dummy.stack ? dummy.stack.replace(/^.+\n/, '') : '';
        try {
          if (!err.stack) {
            err.stack = stack;
            // match without the 2 top stack lines
          } else if (stack && !String(err.stack).endsWith(stack.replace(/^.+\n.+\n/, ''))) {
            err.stack += '\n' + stack
          }
        } catch (e) {
          // ignore the case where "stack" is an un-writable property
        }
      }

      throw err;
    }
  }

  _request(configOrUrl, config) {
    /*eslint no-param-reassign:0*/
    // Allow for axios('example/url'[, config]) a la fetch API
    if (typeof configOrUrl === 'string') {
      config = config || {};
      config.url = configOrUrl;
    } else {
      config = configOrUrl || {};
    }

    config = (0,_mergeConfig_js__WEBPACK_IMPORTED_MODULE_2__["default"])(this.defaults, config);

    const {transitional, paramsSerializer, headers} = config;

    if (transitional !== undefined) {
      _helpers_validator_js__WEBPACK_IMPORTED_MODULE_0__["default"].assertOptions(transitional, {
        silentJSONParsing: validators.transitional(validators.boolean),
        forcedJSONParsing: validators.transitional(validators.boolean),
        clarifyTimeoutError: validators.transitional(validators.boolean)
      }, false);
    }

    if (paramsSerializer != null) {
      if (_utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].isFunction(paramsSerializer)) {
        config.paramsSerializer = {
          serialize: paramsSerializer
        }
      } else {
        _helpers_validator_js__WEBPACK_IMPORTED_MODULE_0__["default"].assertOptions(paramsSerializer, {
          encode: validators.function,
          serialize: validators.function
        }, true);
      }
    }

    // Set config.allowAbsoluteUrls
    if (config.allowAbsoluteUrls !== undefined) {
      // do nothing
    } else if (this.defaults.allowAbsoluteUrls !== undefined) {
      config.allowAbsoluteUrls = this.defaults.allowAbsoluteUrls;
    } else {
      config.allowAbsoluteUrls = true;
    }

    _helpers_validator_js__WEBPACK_IMPORTED_MODULE_0__["default"].assertOptions(config, {
      baseUrl: validators.spelling('baseURL'),
      withXsrfToken: validators.spelling('withXSRFToken')
    }, true);

    // Set config.method
    config.method = (config.method || this.defaults.method || 'get').toLowerCase();

    // Flatten headers
    let contextHeaders = headers && _utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].merge(
      headers.common,
      headers[config.method]
    );

    headers && _utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].forEach(
      ['delete', 'get', 'head', 'post', 'put', 'patch', 'common'],
      (method) => {
        delete headers[method];
      }
    );

    config.headers = _AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_4__["default"].concat(contextHeaders, headers);

    // filter out skipped interceptors
    const requestInterceptorChain = [];
    let synchronousRequestInterceptors = true;
    this.interceptors.request.forEach(function unshiftRequestInterceptors(interceptor) {
      if (typeof interceptor.runWhen === 'function' && interceptor.runWhen(config) === false) {
        return;
      }

      synchronousRequestInterceptors = synchronousRequestInterceptors && interceptor.synchronous;

      requestInterceptorChain.unshift(interceptor.fulfilled, interceptor.rejected);
    });

    const responseInterceptorChain = [];
    this.interceptors.response.forEach(function pushResponseInterceptors(interceptor) {
      responseInterceptorChain.push(interceptor.fulfilled, interceptor.rejected);
    });

    let promise;
    let i = 0;
    let len;

    if (!synchronousRequestInterceptors) {
      const chain = [_dispatchRequest_js__WEBPACK_IMPORTED_MODULE_5__["default"].bind(this), undefined];
      chain.unshift.apply(chain, requestInterceptorChain);
      chain.push.apply(chain, responseInterceptorChain);
      len = chain.length;

      promise = Promise.resolve(config);

      while (i < len) {
        promise = promise.then(chain[i++], chain[i++]);
      }

      return promise;
    }

    len = requestInterceptorChain.length;

    let newConfig = config;

    i = 0;

    while (i < len) {
      const onFulfilled = requestInterceptorChain[i++];
      const onRejected = requestInterceptorChain[i++];
      try {
        newConfig = onFulfilled(newConfig);
      } catch (error) {
        onRejected.call(this, error);
        break;
      }
    }

    try {
      promise = _dispatchRequest_js__WEBPACK_IMPORTED_MODULE_5__["default"].call(this, newConfig);
    } catch (error) {
      return Promise.reject(error);
    }

    i = 0;
    len = responseInterceptorChain.length;

    while (i < len) {
      promise = promise.then(responseInterceptorChain[i++], responseInterceptorChain[i++]);
    }

    return promise;
  }

  getUri(config) {
    config = (0,_mergeConfig_js__WEBPACK_IMPORTED_MODULE_2__["default"])(this.defaults, config);
    const fullPath = (0,_buildFullPath_js__WEBPACK_IMPORTED_MODULE_6__["default"])(config.baseURL, config.url, config.allowAbsoluteUrls);
    return (0,_helpers_buildURL_js__WEBPACK_IMPORTED_MODULE_7__["default"])(fullPath, config.params, config.paramsSerializer);
  }
}

// Provide aliases for supported request methods
_utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].forEach(['delete', 'get', 'head', 'options'], function forEachMethodNoData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, config) {
    return this.request((0,_mergeConfig_js__WEBPACK_IMPORTED_MODULE_2__["default"])(config || {}, {
      method,
      url,
      data: (config || {}).data
    }));
  };
});

_utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  /*eslint func-names:0*/

  function generateHTTPMethod(isForm) {
    return function httpMethod(url, data, config) {
      return this.request((0,_mergeConfig_js__WEBPACK_IMPORTED_MODULE_2__["default"])(config || {}, {
        method,
        headers: isForm ? {
          'Content-Type': 'multipart/form-data'
        } : {},
        url,
        data
      }));
    };
  }

  Axios.prototype[method] = generateHTTPMethod();

  Axios.prototype[method + 'Form'] = generateHTTPMethod(true);
});

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Axios);


/***/ }),

/***/ "./node_modules/axios/lib/core/AxiosError.js":
/*!***************************************************!*\
  !*** ./node_modules/axios/lib/core/AxiosError.js ***!
  \***************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");




/**
 * Create an Error with the specified message, config, error code, request and response.
 *
 * @param {string} message The error message.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 * @param {Object} [config] The config.
 * @param {Object} [request] The request.
 * @param {Object} [response] The response.
 *
 * @returns {Error} The created error.
 */
function AxiosError(message, code, config, request, response) {
  Error.call(this);

  if (Error.captureStackTrace) {
    Error.captureStackTrace(this, this.constructor);
  } else {
    this.stack = (new Error()).stack;
  }

  this.message = message;
  this.name = 'AxiosError';
  code && (this.code = code);
  config && (this.config = config);
  request && (this.request = request);
  if (response) {
    this.response = response;
    this.status = response.status ? response.status : null;
  }
}

_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].inherits(AxiosError, Error, {
  toJSON: function toJSON() {
    return {
      // Standard
      message: this.message,
      name: this.name,
      // Microsoft
      description: this.description,
      number: this.number,
      // Mozilla
      fileName: this.fileName,
      lineNumber: this.lineNumber,
      columnNumber: this.columnNumber,
      stack: this.stack,
      // Axios
      config: _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toJSONObject(this.config),
      code: this.code,
      status: this.status
    };
  }
});

const prototype = AxiosError.prototype;
const descriptors = {};

[
  'ERR_BAD_OPTION_VALUE',
  'ERR_BAD_OPTION',
  'ECONNABORTED',
  'ETIMEDOUT',
  'ERR_NETWORK',
  'ERR_FR_TOO_MANY_REDIRECTS',
  'ERR_DEPRECATED',
  'ERR_BAD_RESPONSE',
  'ERR_BAD_REQUEST',
  'ERR_CANCELED',
  'ERR_NOT_SUPPORT',
  'ERR_INVALID_URL'
// eslint-disable-next-line func-names
].forEach(code => {
  descriptors[code] = {value: code};
});

Object.defineProperties(AxiosError, descriptors);
Object.defineProperty(prototype, 'isAxiosError', {value: true});

// eslint-disable-next-line func-names
AxiosError.from = (error, code, config, request, response, customProps) => {
  const axiosError = Object.create(prototype);

  _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toFlatObject(error, axiosError, function filter(obj) {
    return obj !== Error.prototype;
  }, prop => {
    return prop !== 'isAxiosError';
  });

  AxiosError.call(axiosError, error.message, code, config, request, response);

  axiosError.cause = error;

  axiosError.name = error.name;

  customProps && Object.assign(axiosError, customProps);

  return axiosError;
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (AxiosError);


/***/ }),

/***/ "./node_modules/axios/lib/core/AxiosHeaders.js":
/*!*****************************************************!*\
  !*** ./node_modules/axios/lib/core/AxiosHeaders.js ***!
  \*****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _helpers_parseHeaders_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers/parseHeaders.js */ "./node_modules/axios/lib/helpers/parseHeaders.js");





const $internals = Symbol('internals');

function normalizeHeader(header) {
  return header && String(header).trim().toLowerCase();
}

function normalizeValue(value) {
  if (value === false || value == null) {
    return value;
  }

  return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(value) ? value.map(normalizeValue) : String(value);
}

function parseTokens(str) {
  const tokens = Object.create(null);
  const tokensRE = /([^\s,;=]+)\s*(?:=\s*([^,;]+))?/g;
  let match;

  while ((match = tokensRE.exec(str))) {
    tokens[match[1]] = match[2];
  }

  return tokens;
}

const isValidHeaderName = (str) => /^[-_a-zA-Z0-9^`|~,!#$%&'*+.]+$/.test(str.trim());

function matchHeaderValue(context, value, header, filter, isHeaderNameFilter) {
  if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFunction(filter)) {
    return filter.call(this, value, header);
  }

  if (isHeaderNameFilter) {
    value = header;
  }

  if (!_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isString(value)) return;

  if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isString(filter)) {
    return value.indexOf(filter) !== -1;
  }

  if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegExp(filter)) {
    return filter.test(value);
  }
}

function formatHeader(header) {
  return header.trim()
    .toLowerCase().replace(/([a-z\d])(\w*)/g, (w, char, str) => {
      return char.toUpperCase() + str;
    });
}

function buildAccessors(obj, header) {
  const accessorName = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toCamelCase(' ' + header);

  ['get', 'set', 'has'].forEach(methodName => {
    Object.defineProperty(obj, methodName + accessorName, {
      value: function(arg1, arg2, arg3) {
        return this[methodName].call(this, header, arg1, arg2, arg3);
      },
      configurable: true
    });
  });
}

class AxiosHeaders {
  constructor(headers) {
    headers && this.set(headers);
  }

  set(header, valueOrRewrite, rewrite) {
    const self = this;

    function setHeader(_value, _header, _rewrite) {
      const lHeader = normalizeHeader(_header);

      if (!lHeader) {
        throw new Error('header name must be a non-empty string');
      }

      const key = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].findKey(self, lHeader);

      if(!key || self[key] === undefined || _rewrite === true || (_rewrite === undefined && self[key] !== false)) {
        self[key || _header] = normalizeValue(_value);
      }
    }

    const setHeaders = (headers, _rewrite) =>
      _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEach(headers, (_value, _header) => setHeader(_value, _header, _rewrite));

    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isPlainObject(header) || header instanceof this.constructor) {
      setHeaders(header, valueOrRewrite)
    } else if(_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isString(header) && (header = header.trim()) && !isValidHeaderName(header)) {
      setHeaders((0,_helpers_parseHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"])(header), valueOrRewrite);
    } else if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isObject(header) && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isIterable(header)) {
      let obj = {}, dest, key;
      for (const entry of header) {
        if (!_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(entry)) {
          throw TypeError('Object iterator must return a key-value pair');
        }

        obj[key = entry[0]] = (dest = obj[key]) ?
          (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(dest) ? [...dest, entry[1]] : [dest, entry[1]]) : entry[1];
      }

      setHeaders(obj, valueOrRewrite)
    } else {
      header != null && setHeader(valueOrRewrite, header, rewrite);
    }

    return this;
  }

  get(header, parser) {
    header = normalizeHeader(header);

    if (header) {
      const key = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].findKey(this, header);

      if (key) {
        const value = this[key];

        if (!parser) {
          return value;
        }

        if (parser === true) {
          return parseTokens(value);
        }

        if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFunction(parser)) {
          return parser.call(this, value, key);
        }

        if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegExp(parser)) {
          return parser.exec(value);
        }

        throw new TypeError('parser must be boolean|regexp|function');
      }
    }
  }

  has(header, matcher) {
    header = normalizeHeader(header);

    if (header) {
      const key = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].findKey(this, header);

      return !!(key && this[key] !== undefined && (!matcher || matchHeaderValue(this, this[key], key, matcher)));
    }

    return false;
  }

  delete(header, matcher) {
    const self = this;
    let deleted = false;

    function deleteHeader(_header) {
      _header = normalizeHeader(_header);

      if (_header) {
        const key = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].findKey(self, _header);

        if (key && (!matcher || matchHeaderValue(self, self[key], key, matcher))) {
          delete self[key];

          deleted = true;
        }
      }
    }

    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(header)) {
      header.forEach(deleteHeader);
    } else {
      deleteHeader(header);
    }

    return deleted;
  }

  clear(matcher) {
    const keys = Object.keys(this);
    let i = keys.length;
    let deleted = false;

    while (i--) {
      const key = keys[i];
      if(!matcher || matchHeaderValue(this, this[key], key, matcher, true)) {
        delete this[key];
        deleted = true;
      }
    }

    return deleted;
  }

  normalize(format) {
    const self = this;
    const headers = {};

    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEach(this, (value, header) => {
      const key = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].findKey(headers, header);

      if (key) {
        self[key] = normalizeValue(value);
        delete self[header];
        return;
      }

      const normalized = format ? formatHeader(header) : String(header).trim();

      if (normalized !== header) {
        delete self[header];
      }

      self[normalized] = normalizeValue(value);

      headers[normalized] = true;
    });

    return this;
  }

  concat(...targets) {
    return this.constructor.concat(this, ...targets);
  }

  toJSON(asStrings) {
    const obj = Object.create(null);

    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEach(this, (value, header) => {
      value != null && value !== false && (obj[header] = asStrings && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(value) ? value.join(', ') : value);
    });

    return obj;
  }

  [Symbol.iterator]() {
    return Object.entries(this.toJSON())[Symbol.iterator]();
  }

  toString() {
    return Object.entries(this.toJSON()).map(([header, value]) => header + ': ' + value).join('\n');
  }

  getSetCookie() {
    return this.get("set-cookie") || [];
  }

  get [Symbol.toStringTag]() {
    return 'AxiosHeaders';
  }

  static from(thing) {
    return thing instanceof this ? thing : new this(thing);
  }

  static concat(first, ...targets) {
    const computed = new this(first);

    targets.forEach((target) => computed.set(target));

    return computed;
  }

  static accessor(header) {
    const internals = this[$internals] = (this[$internals] = {
      accessors: {}
    });

    const accessors = internals.accessors;
    const prototype = this.prototype;

    function defineAccessor(_header) {
      const lHeader = normalizeHeader(_header);

      if (!accessors[lHeader]) {
        buildAccessors(prototype, _header);
        accessors[lHeader] = true;
      }
    }

    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(header) ? header.forEach(defineAccessor) : defineAccessor(header);

    return this;
  }
}

AxiosHeaders.accessor(['Content-Type', 'Content-Length', 'Accept', 'Accept-Encoding', 'User-Agent', 'Authorization']);

// reserved names hotfix
_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].reduceDescriptors(AxiosHeaders.prototype, ({value}, key) => {
  let mapped = key[0].toUpperCase() + key.slice(1); // map `set` => `Set`
  return {
    get: () => value,
    set(headerValue) {
      this[mapped] = headerValue;
    }
  }
});

_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].freezeMethods(AxiosHeaders);

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (AxiosHeaders);


/***/ }),

/***/ "./node_modules/axios/lib/core/InterceptorManager.js":
/*!***********************************************************!*\
  !*** ./node_modules/axios/lib/core/InterceptorManager.js ***!
  \***********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");




class InterceptorManager {
  constructor() {
    this.handlers = [];
  }

  /**
   * Add a new interceptor to the stack
   *
   * @param {Function} fulfilled The function to handle `then` for a `Promise`
   * @param {Function} rejected The function to handle `reject` for a `Promise`
   *
   * @return {Number} An ID used to remove interceptor later
   */
  use(fulfilled, rejected, options) {
    this.handlers.push({
      fulfilled,
      rejected,
      synchronous: options ? options.synchronous : false,
      runWhen: options ? options.runWhen : null
    });
    return this.handlers.length - 1;
  }

  /**
   * Remove an interceptor from the stack
   *
   * @param {Number} id The ID that was returned by `use`
   *
   * @returns {Boolean} `true` if the interceptor was removed, `false` otherwise
   */
  eject(id) {
    if (this.handlers[id]) {
      this.handlers[id] = null;
    }
  }

  /**
   * Clear all interceptors from the stack
   *
   * @returns {void}
   */
  clear() {
    if (this.handlers) {
      this.handlers = [];
    }
  }

  /**
   * Iterate over all the registered interceptors
   *
   * This method is particularly useful for skipping over any
   * interceptors that may have become `null` calling `eject`.
   *
   * @param {Function} fn The function to call for each interceptor
   *
   * @returns {void}
   */
  forEach(fn) {
    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEach(this.handlers, function forEachHandler(h) {
      if (h !== null) {
        fn(h);
      }
    });
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (InterceptorManager);


/***/ }),

/***/ "./node_modules/axios/lib/core/buildFullPath.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/core/buildFullPath.js ***!
  \******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ buildFullPath)
/* harmony export */ });
/* harmony import */ var _helpers_isAbsoluteURL_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/isAbsoluteURL.js */ "./node_modules/axios/lib/helpers/isAbsoluteURL.js");
/* harmony import */ var _helpers_combineURLs_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers/combineURLs.js */ "./node_modules/axios/lib/helpers/combineURLs.js");





/**
 * Creates a new URL by combining the baseURL with the requestedURL,
 * only when the requestedURL is not already an absolute URL.
 * If the requestURL is absolute, this function returns the requestedURL untouched.
 *
 * @param {string} baseURL The base URL
 * @param {string} requestedURL Absolute or relative URL to combine
 *
 * @returns {string} The combined full path
 */
function buildFullPath(baseURL, requestedURL, allowAbsoluteUrls) {
  let isRelativeUrl = !(0,_helpers_isAbsoluteURL_js__WEBPACK_IMPORTED_MODULE_0__["default"])(requestedURL);
  if (baseURL && (isRelativeUrl || allowAbsoluteUrls == false)) {
    return (0,_helpers_combineURLs_js__WEBPACK_IMPORTED_MODULE_1__["default"])(baseURL, requestedURL);
  }
  return requestedURL;
}


/***/ }),

/***/ "./node_modules/axios/lib/core/dispatchRequest.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/core/dispatchRequest.js ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ dispatchRequest)
/* harmony export */ });
/* harmony import */ var _transformData_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./transformData.js */ "./node_modules/axios/lib/core/transformData.js");
/* harmony import */ var _cancel_isCancel_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../cancel/isCancel.js */ "./node_modules/axios/lib/cancel/isCancel.js");
/* harmony import */ var _defaults_index_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../defaults/index.js */ "./node_modules/axios/lib/defaults/index.js");
/* harmony import */ var _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../cancel/CanceledError.js */ "./node_modules/axios/lib/cancel/CanceledError.js");
/* harmony import */ var _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../core/AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");
/* harmony import */ var _adapters_adapters_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../adapters/adapters.js */ "./node_modules/axios/lib/adapters/adapters.js");









/**
 * Throws a `CanceledError` if cancellation has been requested.
 *
 * @param {Object} config The config that is to be used for the request
 *
 * @returns {void}
 */
function throwIfCancellationRequested(config) {
  if (config.cancelToken) {
    config.cancelToken.throwIfRequested();
  }

  if (config.signal && config.signal.aborted) {
    throw new _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_0__["default"](null, config);
  }
}

/**
 * Dispatch a request to the server using the configured adapter.
 *
 * @param {object} config The config that is to be used for the request
 *
 * @returns {Promise} The Promise to be fulfilled
 */
function dispatchRequest(config) {
  throwIfCancellationRequested(config);

  config.headers = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(config.headers);

  // Transform request data
  config.data = _transformData_js__WEBPACK_IMPORTED_MODULE_2__["default"].call(
    config,
    config.transformRequest
  );

  if (['post', 'put', 'patch'].indexOf(config.method) !== -1) {
    config.headers.setContentType('application/x-www-form-urlencoded', false);
  }

  const adapter = _adapters_adapters_js__WEBPACK_IMPORTED_MODULE_3__["default"].getAdapter(config.adapter || _defaults_index_js__WEBPACK_IMPORTED_MODULE_4__["default"].adapter);

  return adapter(config).then(function onAdapterResolution(response) {
    throwIfCancellationRequested(config);

    // Transform response data
    response.data = _transformData_js__WEBPACK_IMPORTED_MODULE_2__["default"].call(
      config,
      config.transformResponse,
      response
    );

    response.headers = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(response.headers);

    return response;
  }, function onAdapterRejection(reason) {
    if (!(0,_cancel_isCancel_js__WEBPACK_IMPORTED_MODULE_5__["default"])(reason)) {
      throwIfCancellationRequested(config);

      // Transform response data
      if (reason && reason.response) {
        reason.response.data = _transformData_js__WEBPACK_IMPORTED_MODULE_2__["default"].call(
          config,
          config.transformResponse,
          reason.response
        );
        reason.response.headers = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(reason.response.headers);
      }
    }

    return Promise.reject(reason);
  });
}


/***/ }),

/***/ "./node_modules/axios/lib/core/mergeConfig.js":
/*!****************************************************!*\
  !*** ./node_modules/axios/lib/core/mergeConfig.js ***!
  \****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ mergeConfig)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");





const headersToObject = (thing) => thing instanceof _AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_0__["default"] ? { ...thing } : thing;

/**
 * Config-specific merge-function which creates a new config-object
 * by merging two configuration objects together.
 *
 * @param {Object} config1
 * @param {Object} config2
 *
 * @returns {Object} New object resulting from merging config2 to config1
 */
function mergeConfig(config1, config2) {
  // eslint-disable-next-line no-param-reassign
  config2 = config2 || {};
  const config = {};

  function getMergedValue(target, source, prop, caseless) {
    if (_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isPlainObject(target) && _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isPlainObject(source)) {
      return _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].merge.call({caseless}, target, source);
    } else if (_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isPlainObject(source)) {
      return _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].merge({}, source);
    } else if (_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isArray(source)) {
      return source.slice();
    }
    return source;
  }

  // eslint-disable-next-line consistent-return
  function mergeDeepProperties(a, b, prop , caseless) {
    if (!_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isUndefined(b)) {
      return getMergedValue(a, b, prop , caseless);
    } else if (!_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isUndefined(a)) {
      return getMergedValue(undefined, a, prop , caseless);
    }
  }

  // eslint-disable-next-line consistent-return
  function valueFromConfig2(a, b) {
    if (!_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isUndefined(b)) {
      return getMergedValue(undefined, b);
    }
  }

  // eslint-disable-next-line consistent-return
  function defaultToConfig2(a, b) {
    if (!_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isUndefined(b)) {
      return getMergedValue(undefined, b);
    } else if (!_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isUndefined(a)) {
      return getMergedValue(undefined, a);
    }
  }

  // eslint-disable-next-line consistent-return
  function mergeDirectKeys(a, b, prop) {
    if (prop in config2) {
      return getMergedValue(a, b);
    } else if (prop in config1) {
      return getMergedValue(undefined, a);
    }
  }

  const mergeMap = {
    url: valueFromConfig2,
    method: valueFromConfig2,
    data: valueFromConfig2,
    baseURL: defaultToConfig2,
    transformRequest: defaultToConfig2,
    transformResponse: defaultToConfig2,
    paramsSerializer: defaultToConfig2,
    timeout: defaultToConfig2,
    timeoutMessage: defaultToConfig2,
    withCredentials: defaultToConfig2,
    withXSRFToken: defaultToConfig2,
    adapter: defaultToConfig2,
    responseType: defaultToConfig2,
    xsrfCookieName: defaultToConfig2,
    xsrfHeaderName: defaultToConfig2,
    onUploadProgress: defaultToConfig2,
    onDownloadProgress: defaultToConfig2,
    decompress: defaultToConfig2,
    maxContentLength: defaultToConfig2,
    maxBodyLength: defaultToConfig2,
    beforeRedirect: defaultToConfig2,
    transport: defaultToConfig2,
    httpAgent: defaultToConfig2,
    httpsAgent: defaultToConfig2,
    cancelToken: defaultToConfig2,
    socketPath: defaultToConfig2,
    responseEncoding: defaultToConfig2,
    validateStatus: mergeDirectKeys,
    headers: (a, b , prop) => mergeDeepProperties(headersToObject(a), headersToObject(b),prop, true)
  };

  _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].forEach(Object.keys(Object.assign({}, config1, config2)), function computeConfigValue(prop) {
    const merge = mergeMap[prop] || mergeDeepProperties;
    const configValue = merge(config1[prop], config2[prop], prop);
    (_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isUndefined(configValue) && merge !== mergeDirectKeys) || (config[prop] = configValue);
  });

  return config;
}


/***/ }),

/***/ "./node_modules/axios/lib/core/settle.js":
/*!***********************************************!*\
  !*** ./node_modules/axios/lib/core/settle.js ***!
  \***********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ settle)
/* harmony export */ });
/* harmony import */ var _AxiosError_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");




/**
 * Resolve or reject a Promise based on response status.
 *
 * @param {Function} resolve A function that resolves the promise.
 * @param {Function} reject A function that rejects the promise.
 * @param {object} response The response.
 *
 * @returns {object} The response.
 */
function settle(resolve, reject, response) {
  const validateStatus = response.config.validateStatus;
  if (!response.status || !validateStatus || validateStatus(response.status)) {
    resolve(response);
  } else {
    reject(new _AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"](
      'Request failed with status code ' + response.status,
      [_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"].ERR_BAD_REQUEST, _AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"].ERR_BAD_RESPONSE][Math.floor(response.status / 100) - 4],
      response.config,
      response.request,
      response
    ));
  }
}


/***/ }),

/***/ "./node_modules/axios/lib/core/transformData.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/core/transformData.js ***!
  \******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ transformData)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _defaults_index_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../defaults/index.js */ "./node_modules/axios/lib/defaults/index.js");
/* harmony import */ var _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../core/AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");






/**
 * Transform the data for a request or a response
 *
 * @param {Array|Function} fns A single function or Array of functions
 * @param {?Object} response The response object
 *
 * @returns {*} The resulting transformed data
 */
function transformData(fns, response) {
  const config = this || _defaults_index_js__WEBPACK_IMPORTED_MODULE_0__["default"];
  const context = response || config;
  const headers = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(context.headers);
  let data = context.data;

  _utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].forEach(fns, function transform(fn) {
    data = fn.call(config, data, headers.normalize(), response ? response.status : undefined);
  });

  headers.normalize();

  return data;
}


/***/ }),

/***/ "./node_modules/axios/lib/defaults/index.js":
/*!**************************************************!*\
  !*** ./node_modules/axios/lib/defaults/index.js ***!
  \**************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _transitional_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./transitional.js */ "./node_modules/axios/lib/defaults/transitional.js");
/* harmony import */ var _helpers_toFormData_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../helpers/toFormData.js */ "./node_modules/axios/lib/helpers/toFormData.js");
/* harmony import */ var _helpers_toURLEncodedForm_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../helpers/toURLEncodedForm.js */ "./node_modules/axios/lib/helpers/toURLEncodedForm.js");
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");
/* harmony import */ var _helpers_formDataToJSON_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../helpers/formDataToJSON.js */ "./node_modules/axios/lib/helpers/formDataToJSON.js");










/**
 * It takes a string, tries to parse it, and if it fails, it returns the stringified version
 * of the input
 *
 * @param {any} rawValue - The value to be stringified.
 * @param {Function} parser - A function that parses a string into a JavaScript object.
 * @param {Function} encoder - A function that takes a value and returns a string.
 *
 * @returns {string} A stringified version of the rawValue.
 */
function stringifySafely(rawValue, parser, encoder) {
  if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isString(rawValue)) {
    try {
      (parser || JSON.parse)(rawValue);
      return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].trim(rawValue);
    } catch (e) {
      if (e.name !== 'SyntaxError') {
        throw e;
      }
    }
  }

  return (encoder || JSON.stringify)(rawValue);
}

const defaults = {

  transitional: _transitional_js__WEBPACK_IMPORTED_MODULE_1__["default"],

  adapter: ['xhr', 'http', 'fetch'],

  transformRequest: [function transformRequest(data, headers) {
    const contentType = headers.getContentType() || '';
    const hasJSONContentType = contentType.indexOf('application/json') > -1;
    const isObjectPayload = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isObject(data);

    if (isObjectPayload && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isHTMLForm(data)) {
      data = new FormData(data);
    }

    const isFormData = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFormData(data);

    if (isFormData) {
      return hasJSONContentType ? JSON.stringify((0,_helpers_formDataToJSON_js__WEBPACK_IMPORTED_MODULE_2__["default"])(data)) : data;
    }

    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArrayBuffer(data) ||
      _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isBuffer(data) ||
      _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isStream(data) ||
      _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFile(data) ||
      _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isBlob(data) ||
      _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isReadableStream(data)
    ) {
      return data;
    }
    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArrayBufferView(data)) {
      return data.buffer;
    }
    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isURLSearchParams(data)) {
      headers.setContentType('application/x-www-form-urlencoded;charset=utf-8', false);
      return data.toString();
    }

    let isFileList;

    if (isObjectPayload) {
      if (contentType.indexOf('application/x-www-form-urlencoded') > -1) {
        return (0,_helpers_toURLEncodedForm_js__WEBPACK_IMPORTED_MODULE_3__["default"])(data, this.formSerializer).toString();
      }

      if ((isFileList = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFileList(data)) || contentType.indexOf('multipart/form-data') > -1) {
        const _FormData = this.env && this.env.FormData;

        return (0,_helpers_toFormData_js__WEBPACK_IMPORTED_MODULE_4__["default"])(
          isFileList ? {'files[]': data} : data,
          _FormData && new _FormData(),
          this.formSerializer
        );
      }
    }

    if (isObjectPayload || hasJSONContentType ) {
      headers.setContentType('application/json', false);
      return stringifySafely(data);
    }

    return data;
  }],

  transformResponse: [function transformResponse(data) {
    const transitional = this.transitional || defaults.transitional;
    const forcedJSONParsing = transitional && transitional.forcedJSONParsing;
    const JSONRequested = this.responseType === 'json';

    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isResponse(data) || _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isReadableStream(data)) {
      return data;
    }

    if (data && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isString(data) && ((forcedJSONParsing && !this.responseType) || JSONRequested)) {
      const silentJSONParsing = transitional && transitional.silentJSONParsing;
      const strictJSONParsing = !silentJSONParsing && JSONRequested;

      try {
        return JSON.parse(data);
      } catch (e) {
        if (strictJSONParsing) {
          if (e.name === 'SyntaxError') {
            throw _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_5__["default"].from(e, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_5__["default"].ERR_BAD_RESPONSE, this, null, this.response);
          }
          throw e;
        }
      }
    }

    return data;
  }],

  /**
   * A timeout in milliseconds to abort a request. If set to 0 (default) a
   * timeout is not created.
   */
  timeout: 0,

  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',

  maxContentLength: -1,
  maxBodyLength: -1,

  env: {
    FormData: _platform_index_js__WEBPACK_IMPORTED_MODULE_6__["default"].classes.FormData,
    Blob: _platform_index_js__WEBPACK_IMPORTED_MODULE_6__["default"].classes.Blob
  },

  validateStatus: function validateStatus(status) {
    return status >= 200 && status < 300;
  },

  headers: {
    common: {
      'Accept': 'application/json, text/plain, */*',
      'Content-Type': undefined
    }
  }
};

_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEach(['delete', 'get', 'head', 'post', 'put', 'patch'], (method) => {
  defaults.headers[method] = {};
});

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (defaults);


/***/ }),

/***/ "./node_modules/axios/lib/defaults/transitional.js":
/*!*********************************************************!*\
  !*** ./node_modules/axios/lib/defaults/transitional.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  silentJSONParsing: true,
  forcedJSONParsing: true,
  clarifyTimeoutError: false
});


/***/ }),

/***/ "./node_modules/axios/lib/env/data.js":
/*!********************************************!*\
  !*** ./node_modules/axios/lib/env/data.js ***!
  \********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   VERSION: () => (/* binding */ VERSION)
/* harmony export */ });
const VERSION = "1.9.0";

/***/ }),

/***/ "./node_modules/axios/lib/helpers/AxiosURLSearchParams.js":
/*!****************************************************************!*\
  !*** ./node_modules/axios/lib/helpers/AxiosURLSearchParams.js ***!
  \****************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _toFormData_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./toFormData.js */ "./node_modules/axios/lib/helpers/toFormData.js");




/**
 * It encodes a string by replacing all characters that are not in the unreserved set with
 * their percent-encoded equivalents
 *
 * @param {string} str - The string to encode.
 *
 * @returns {string} The encoded string.
 */
function encode(str) {
  const charMap = {
    '!': '%21',
    "'": '%27',
    '(': '%28',
    ')': '%29',
    '~': '%7E',
    '%20': '+',
    '%00': '\x00'
  };
  return encodeURIComponent(str).replace(/[!'()~]|%20|%00/g, function replacer(match) {
    return charMap[match];
  });
}

/**
 * It takes a params object and converts it to a FormData object
 *
 * @param {Object<string, any>} params - The parameters to be converted to a FormData object.
 * @param {Object<string, any>} options - The options object passed to the Axios constructor.
 *
 * @returns {void}
 */
function AxiosURLSearchParams(params, options) {
  this._pairs = [];

  params && (0,_toFormData_js__WEBPACK_IMPORTED_MODULE_0__["default"])(params, this, options);
}

const prototype = AxiosURLSearchParams.prototype;

prototype.append = function append(name, value) {
  this._pairs.push([name, value]);
};

prototype.toString = function toString(encoder) {
  const _encode = encoder ? function(value) {
    return encoder.call(this, value, encode);
  } : encode;

  return this._pairs.map(function each(pair) {
    return _encode(pair[0]) + '=' + _encode(pair[1]);
  }, '').join('&');
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (AxiosURLSearchParams);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/HttpStatusCode.js":
/*!**********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/HttpStatusCode.js ***!
  \**********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const HttpStatusCode = {
  Continue: 100,
  SwitchingProtocols: 101,
  Processing: 102,
  EarlyHints: 103,
  Ok: 200,
  Created: 201,
  Accepted: 202,
  NonAuthoritativeInformation: 203,
  NoContent: 204,
  ResetContent: 205,
  PartialContent: 206,
  MultiStatus: 207,
  AlreadyReported: 208,
  ImUsed: 226,
  MultipleChoices: 300,
  MovedPermanently: 301,
  Found: 302,
  SeeOther: 303,
  NotModified: 304,
  UseProxy: 305,
  Unused: 306,
  TemporaryRedirect: 307,
  PermanentRedirect: 308,
  BadRequest: 400,
  Unauthorized: 401,
  PaymentRequired: 402,
  Forbidden: 403,
  NotFound: 404,
  MethodNotAllowed: 405,
  NotAcceptable: 406,
  ProxyAuthenticationRequired: 407,
  RequestTimeout: 408,
  Conflict: 409,
  Gone: 410,
  LengthRequired: 411,
  PreconditionFailed: 412,
  PayloadTooLarge: 413,
  UriTooLong: 414,
  UnsupportedMediaType: 415,
  RangeNotSatisfiable: 416,
  ExpectationFailed: 417,
  ImATeapot: 418,
  MisdirectedRequest: 421,
  UnprocessableEntity: 422,
  Locked: 423,
  FailedDependency: 424,
  TooEarly: 425,
  UpgradeRequired: 426,
  PreconditionRequired: 428,
  TooManyRequests: 429,
  RequestHeaderFieldsTooLarge: 431,
  UnavailableForLegalReasons: 451,
  InternalServerError: 500,
  NotImplemented: 501,
  BadGateway: 502,
  ServiceUnavailable: 503,
  GatewayTimeout: 504,
  HttpVersionNotSupported: 505,
  VariantAlsoNegotiates: 506,
  InsufficientStorage: 507,
  LoopDetected: 508,
  NotExtended: 510,
  NetworkAuthenticationRequired: 511,
};

Object.entries(HttpStatusCode).forEach(([key, value]) => {
  HttpStatusCode[value] = key;
});

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HttpStatusCode);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/bind.js":
/*!************************************************!*\
  !*** ./node_modules/axios/lib/helpers/bind.js ***!
  \************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ bind)
/* harmony export */ });


function bind(fn, thisArg) {
  return function wrap() {
    return fn.apply(thisArg, arguments);
  };
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/buildURL.js":
/*!****************************************************!*\
  !*** ./node_modules/axios/lib/helpers/buildURL.js ***!
  \****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ buildURL)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _helpers_AxiosURLSearchParams_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers/AxiosURLSearchParams.js */ "./node_modules/axios/lib/helpers/AxiosURLSearchParams.js");





/**
 * It replaces all instances of the characters `:`, `$`, `,`, `+`, `[`, and `]` with their
 * URI encoded counterparts
 *
 * @param {string} val The value to be encoded.
 *
 * @returns {string} The encoded value.
 */
function encode(val) {
  return encodeURIComponent(val).
    replace(/%3A/gi, ':').
    replace(/%24/g, '$').
    replace(/%2C/gi, ',').
    replace(/%20/g, '+').
    replace(/%5B/gi, '[').
    replace(/%5D/gi, ']');
}

/**
 * Build a URL by appending params to the end
 *
 * @param {string} url The base of the url (e.g., http://www.google.com)
 * @param {object} [params] The params to be appended
 * @param {?(object|Function)} options
 *
 * @returns {string} The formatted url
 */
function buildURL(url, params, options) {
  /*eslint no-param-reassign:0*/
  if (!params) {
    return url;
  }
  
  const _encode = options && options.encode || encode;

  if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFunction(options)) {
    options = {
      serialize: options
    };
  } 

  const serializeFn = options && options.serialize;

  let serializedParams;

  if (serializeFn) {
    serializedParams = serializeFn(params, options);
  } else {
    serializedParams = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isURLSearchParams(params) ?
      params.toString() :
      new _helpers_AxiosURLSearchParams_js__WEBPACK_IMPORTED_MODULE_1__["default"](params, options).toString(_encode);
  }

  if (serializedParams) {
    const hashmarkIndex = url.indexOf("#");

    if (hashmarkIndex !== -1) {
      url = url.slice(0, hashmarkIndex);
    }
    url += (url.indexOf('?') === -1 ? '?' : '&') + serializedParams;
  }

  return url;
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/combineURLs.js":
/*!*******************************************************!*\
  !*** ./node_modules/axios/lib/helpers/combineURLs.js ***!
  \*******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ combineURLs)
/* harmony export */ });


/**
 * Creates a new URL by combining the specified URLs
 *
 * @param {string} baseURL The base URL
 * @param {string} relativeURL The relative URL
 *
 * @returns {string} The combined URL
 */
function combineURLs(baseURL, relativeURL) {
  return relativeURL
    ? baseURL.replace(/\/?\/$/, '') + '/' + relativeURL.replace(/^\/+/, '')
    : baseURL;
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/composeSignals.js":
/*!**********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/composeSignals.js ***!
  \**********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../cancel/CanceledError.js */ "./node_modules/axios/lib/cancel/CanceledError.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");




const composeSignals = (signals, timeout) => {
  const {length} = (signals = signals ? signals.filter(Boolean) : []);

  if (timeout || length) {
    let controller = new AbortController();

    let aborted;

    const onabort = function (reason) {
      if (!aborted) {
        aborted = true;
        unsubscribe();
        const err = reason instanceof Error ? reason : this.reason;
        controller.abort(err instanceof _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"] ? err : new _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_1__["default"](err instanceof Error ? err.message : err));
      }
    }

    let timer = timeout && setTimeout(() => {
      timer = null;
      onabort(new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"](`timeout ${timeout} of ms exceeded`, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"].ETIMEDOUT))
    }, timeout)

    const unsubscribe = () => {
      if (signals) {
        timer && clearTimeout(timer);
        timer = null;
        signals.forEach(signal => {
          signal.unsubscribe ? signal.unsubscribe(onabort) : signal.removeEventListener('abort', onabort);
        });
        signals = null;
      }
    }

    signals.forEach((signal) => signal.addEventListener('abort', onabort));

    const {signal} = controller;

    signal.unsubscribe = () => _utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].asap(unsubscribe);

    return signal;
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (composeSignals);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/cookies.js":
/*!***************************************************!*\
  !*** ./node_modules/axios/lib/helpers/cookies.js ***!
  \***************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].hasStandardBrowserEnv ?

  // Standard browser envs support document.cookie
  {
    write(name, value, expires, path, domain, secure) {
      const cookie = [name + '=' + encodeURIComponent(value)];

      _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isNumber(expires) && cookie.push('expires=' + new Date(expires).toGMTString());

      _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isString(path) && cookie.push('path=' + path);

      _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isString(domain) && cookie.push('domain=' + domain);

      secure === true && cookie.push('secure');

      document.cookie = cookie.join('; ');
    },

    read(name) {
      const match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
      return (match ? decodeURIComponent(match[3]) : null);
    },

    remove(name) {
      this.write(name, '', Date.now() - 86400000);
    }
  }

  :

  // Non-standard browser env (web workers, react-native) lack needed support.
  {
    write() {},
    read() {
      return null;
    },
    remove() {}
  });



/***/ }),

/***/ "./node_modules/axios/lib/helpers/formDataToJSON.js":
/*!**********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/formDataToJSON.js ***!
  \**********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");




/**
 * It takes a string like `foo[x][y][z]` and returns an array like `['foo', 'x', 'y', 'z']
 *
 * @param {string} name - The name of the property to get.
 *
 * @returns An array of strings.
 */
function parsePropPath(name) {
  // foo[x][y][z]
  // foo.x.y.z
  // foo-x-y-z
  // foo x y z
  return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].matchAll(/\w+|\[(\w*)]/g, name).map(match => {
    return match[0] === '[]' ? '' : match[1] || match[0];
  });
}

/**
 * Convert an array to an object.
 *
 * @param {Array<any>} arr - The array to convert to an object.
 *
 * @returns An object with the same keys and values as the array.
 */
function arrayToObject(arr) {
  const obj = {};
  const keys = Object.keys(arr);
  let i;
  const len = keys.length;
  let key;
  for (i = 0; i < len; i++) {
    key = keys[i];
    obj[key] = arr[key];
  }
  return obj;
}

/**
 * It takes a FormData object and returns a JavaScript object
 *
 * @param {string} formData The FormData object to convert to JSON.
 *
 * @returns {Object<string, any> | null} The converted object.
 */
function formDataToJSON(formData) {
  function buildPath(path, value, target, index) {
    let name = path[index++];

    if (name === '__proto__') return true;

    const isNumericKey = Number.isFinite(+name);
    const isLast = index >= path.length;
    name = !name && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(target) ? target.length : name;

    if (isLast) {
      if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].hasOwnProp(target, name)) {
        target[name] = [target[name], value];
      } else {
        target[name] = value;
      }

      return !isNumericKey;
    }

    if (!target[name] || !_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isObject(target[name])) {
      target[name] = [];
    }

    const result = buildPath(path, value, target[name], index);

    if (result && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(target[name])) {
      target[name] = arrayToObject(target[name]);
    }

    return !isNumericKey;
  }

  if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFormData(formData) && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFunction(formData.entries)) {
    const obj = {};

    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEachEntry(formData, (name, value) => {
      buildPath(parsePropPath(name), value, obj, 0);
    });

    return obj;
  }

  return null;
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (formDataToJSON);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isAbsoluteURL.js":
/*!*********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/isAbsoluteURL.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ isAbsoluteURL)
/* harmony export */ });


/**
 * Determines whether the specified URL is absolute
 *
 * @param {string} url The URL to test
 *
 * @returns {boolean} True if the specified URL is absolute, otherwise false
 */
function isAbsoluteURL(url) {
  // A URL is considered absolute if it begins with "<scheme>://" or "//" (protocol-relative URL).
  // RFC 3986 defines scheme name as a sequence of characters beginning with a letter and followed
  // by any combination of letters, digits, plus, period, or hyphen.
  return /^([a-z][a-z\d+\-.]*:)?\/\//i.test(url);
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isAxiosError.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/isAxiosError.js ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ isAxiosError)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");




/**
 * Determines whether the payload is an error thrown by Axios
 *
 * @param {*} payload The value to test
 *
 * @returns {boolean} True if the payload is an error thrown by Axios, otherwise false
 */
function isAxiosError(payload) {
  return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isObject(payload) && (payload.isAxiosError === true);
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isURLSameOrigin.js":
/*!***********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/isURLSameOrigin.js ***!
  \***********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].hasStandardBrowserEnv ? ((origin, isMSIE) => (url) => {
  url = new URL(url, _platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].origin);

  return (
    origin.protocol === url.protocol &&
    origin.host === url.host &&
    (isMSIE || origin.port === url.port)
  );
})(
  new URL(_platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].origin),
  _platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].navigator && /(msie|trident)/i.test(_platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].navigator.userAgent)
) : () => true);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/null.js":
/*!************************************************!*\
  !*** ./node_modules/axios/lib/helpers/null.js ***!
  \************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
// eslint-disable-next-line strict
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (null);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/parseHeaders.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/parseHeaders.js ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");




// RawAxiosHeaders whose duplicates are ignored by node
// c.f. https://nodejs.org/api/http.html#http_message_headers
const ignoreDuplicateOf = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toObjectSet([
  'age', 'authorization', 'content-length', 'content-type', 'etag',
  'expires', 'from', 'host', 'if-modified-since', 'if-unmodified-since',
  'last-modified', 'location', 'max-forwards', 'proxy-authorization',
  'referer', 'retry-after', 'user-agent'
]);

/**
 * Parse headers into an object
 *
 * ```
 * Date: Wed, 27 Aug 2014 08:58:49 GMT
 * Content-Type: application/json
 * Connection: keep-alive
 * Transfer-Encoding: chunked
 * ```
 *
 * @param {String} rawHeaders Headers needing to be parsed
 *
 * @returns {Object} Headers parsed into an object
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (rawHeaders => {
  const parsed = {};
  let key;
  let val;
  let i;

  rawHeaders && rawHeaders.split('\n').forEach(function parser(line) {
    i = line.indexOf(':');
    key = line.substring(0, i).trim().toLowerCase();
    val = line.substring(i + 1).trim();

    if (!key || (parsed[key] && ignoreDuplicateOf[key])) {
      return;
    }

    if (key === 'set-cookie') {
      if (parsed[key]) {
        parsed[key].push(val);
      } else {
        parsed[key] = [val];
      }
    } else {
      parsed[key] = parsed[key] ? parsed[key] + ', ' + val : val;
    }
  });

  return parsed;
});


/***/ }),

/***/ "./node_modules/axios/lib/helpers/parseProtocol.js":
/*!*********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/parseProtocol.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ parseProtocol)
/* harmony export */ });


function parseProtocol(url) {
  const match = /^([-+\w]{1,25})(:?\/\/|:)/.exec(url);
  return match && match[1] || '';
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/progressEventReducer.js":
/*!****************************************************************!*\
  !*** ./node_modules/axios/lib/helpers/progressEventReducer.js ***!
  \****************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   asyncDecorator: () => (/* binding */ asyncDecorator),
/* harmony export */   progressEventDecorator: () => (/* binding */ progressEventDecorator),
/* harmony export */   progressEventReducer: () => (/* binding */ progressEventReducer)
/* harmony export */ });
/* harmony import */ var _speedometer_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./speedometer.js */ "./node_modules/axios/lib/helpers/speedometer.js");
/* harmony import */ var _throttle_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./throttle.js */ "./node_modules/axios/lib/helpers/throttle.js");
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");




const progressEventReducer = (listener, isDownloadStream, freq = 3) => {
  let bytesNotified = 0;
  const _speedometer = (0,_speedometer_js__WEBPACK_IMPORTED_MODULE_0__["default"])(50, 250);

  return (0,_throttle_js__WEBPACK_IMPORTED_MODULE_1__["default"])(e => {
    const loaded = e.loaded;
    const total = e.lengthComputable ? e.total : undefined;
    const progressBytes = loaded - bytesNotified;
    const rate = _speedometer(progressBytes);
    const inRange = loaded <= total;

    bytesNotified = loaded;

    const data = {
      loaded,
      total,
      progress: total ? (loaded / total) : undefined,
      bytes: progressBytes,
      rate: rate ? rate : undefined,
      estimated: rate && total && inRange ? (total - loaded) / rate : undefined,
      event: e,
      lengthComputable: total != null,
      [isDownloadStream ? 'download' : 'upload']: true
    };

    listener(data);
  }, freq);
}

const progressEventDecorator = (total, throttled) => {
  const lengthComputable = total != null;

  return [(loaded) => throttled[0]({
    lengthComputable,
    total,
    loaded
  }), throttled[1]];
}

const asyncDecorator = (fn) => (...args) => _utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].asap(() => fn(...args));


/***/ }),

/***/ "./node_modules/axios/lib/helpers/resolveConfig.js":
/*!*********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/resolveConfig.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _isURLSameOrigin_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./isURLSameOrigin.js */ "./node_modules/axios/lib/helpers/isURLSameOrigin.js");
/* harmony import */ var _cookies_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./cookies.js */ "./node_modules/axios/lib/helpers/cookies.js");
/* harmony import */ var _core_buildFullPath_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../core/buildFullPath.js */ "./node_modules/axios/lib/core/buildFullPath.js");
/* harmony import */ var _core_mergeConfig_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../core/mergeConfig.js */ "./node_modules/axios/lib/core/mergeConfig.js");
/* harmony import */ var _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../core/AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");
/* harmony import */ var _buildURL_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./buildURL.js */ "./node_modules/axios/lib/helpers/buildURL.js");









/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((config) => {
  const newConfig = (0,_core_mergeConfig_js__WEBPACK_IMPORTED_MODULE_0__["default"])({}, config);

  let {data, withXSRFToken, xsrfHeaderName, xsrfCookieName, headers, auth} = newConfig;

  newConfig.headers = headers = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(headers);

  newConfig.url = (0,_buildURL_js__WEBPACK_IMPORTED_MODULE_2__["default"])((0,_core_buildFullPath_js__WEBPACK_IMPORTED_MODULE_3__["default"])(newConfig.baseURL, newConfig.url, newConfig.allowAbsoluteUrls), config.params, config.paramsSerializer);

  // HTTP basic authentication
  if (auth) {
    headers.set('Authorization', 'Basic ' +
      btoa((auth.username || '') + ':' + (auth.password ? unescape(encodeURIComponent(auth.password)) : ''))
    );
  }

  let contentType;

  if (_utils_js__WEBPACK_IMPORTED_MODULE_4__["default"].isFormData(data)) {
    if (_platform_index_js__WEBPACK_IMPORTED_MODULE_5__["default"].hasStandardBrowserEnv || _platform_index_js__WEBPACK_IMPORTED_MODULE_5__["default"].hasStandardBrowserWebWorkerEnv) {
      headers.setContentType(undefined); // Let the browser set it
    } else if ((contentType = headers.getContentType()) !== false) {
      // fix semicolon duplication issue for ReactNative FormData implementation
      const [type, ...tokens] = contentType ? contentType.split(';').map(token => token.trim()).filter(Boolean) : [];
      headers.setContentType([type || 'multipart/form-data', ...tokens].join('; '));
    }
  }

  // Add xsrf header
  // This is only done if running in a standard browser environment.
  // Specifically not if we're in a web worker, or react-native.

  if (_platform_index_js__WEBPACK_IMPORTED_MODULE_5__["default"].hasStandardBrowserEnv) {
    withXSRFToken && _utils_js__WEBPACK_IMPORTED_MODULE_4__["default"].isFunction(withXSRFToken) && (withXSRFToken = withXSRFToken(newConfig));

    if (withXSRFToken || (withXSRFToken !== false && (0,_isURLSameOrigin_js__WEBPACK_IMPORTED_MODULE_6__["default"])(newConfig.url))) {
      // Add xsrf header
      const xsrfValue = xsrfHeaderName && xsrfCookieName && _cookies_js__WEBPACK_IMPORTED_MODULE_7__["default"].read(xsrfCookieName);

      if (xsrfValue) {
        headers.set(xsrfHeaderName, xsrfValue);
      }
    }
  }

  return newConfig;
});



/***/ }),

/***/ "./node_modules/axios/lib/helpers/speedometer.js":
/*!*******************************************************!*\
  !*** ./node_modules/axios/lib/helpers/speedometer.js ***!
  \*******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });


/**
 * Calculate data maxRate
 * @param {Number} [samplesCount= 10]
 * @param {Number} [min= 1000]
 * @returns {Function}
 */
function speedometer(samplesCount, min) {
  samplesCount = samplesCount || 10;
  const bytes = new Array(samplesCount);
  const timestamps = new Array(samplesCount);
  let head = 0;
  let tail = 0;
  let firstSampleTS;

  min = min !== undefined ? min : 1000;

  return function push(chunkLength) {
    const now = Date.now();

    const startedAt = timestamps[tail];

    if (!firstSampleTS) {
      firstSampleTS = now;
    }

    bytes[head] = chunkLength;
    timestamps[head] = now;

    let i = tail;
    let bytesCount = 0;

    while (i !== head) {
      bytesCount += bytes[i++];
      i = i % samplesCount;
    }

    head = (head + 1) % samplesCount;

    if (head === tail) {
      tail = (tail + 1) % samplesCount;
    }

    if (now - firstSampleTS < min) {
      return;
    }

    const passed = startedAt && now - startedAt;

    return passed ? Math.round(bytesCount * 1000 / passed) : undefined;
  };
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (speedometer);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/spread.js":
/*!**************************************************!*\
  !*** ./node_modules/axios/lib/helpers/spread.js ***!
  \**************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ spread)
/* harmony export */ });


/**
 * Syntactic sugar for invoking a function and expanding an array for arguments.
 *
 * Common use case would be to use `Function.prototype.apply`.
 *
 *  ```js
 *  function f(x, y, z) {}
 *  var args = [1, 2, 3];
 *  f.apply(null, args);
 *  ```
 *
 * With `spread` this example can be re-written.
 *
 *  ```js
 *  spread(function(x, y, z) {})([1, 2, 3]);
 *  ```
 *
 * @param {Function} callback
 *
 * @returns {Function}
 */
function spread(callback) {
  return function wrap(arr) {
    return callback.apply(null, arr);
  };
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/throttle.js":
/*!****************************************************!*\
  !*** ./node_modules/axios/lib/helpers/throttle.js ***!
  \****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Throttle decorator
 * @param {Function} fn
 * @param {Number} freq
 * @return {Function}
 */
function throttle(fn, freq) {
  let timestamp = 0;
  let threshold = 1000 / freq;
  let lastArgs;
  let timer;

  const invoke = (args, now = Date.now()) => {
    timestamp = now;
    lastArgs = null;
    if (timer) {
      clearTimeout(timer);
      timer = null;
    }
    fn.apply(null, args);
  }

  const throttled = (...args) => {
    const now = Date.now();
    const passed = now - timestamp;
    if ( passed >= threshold) {
      invoke(args, now);
    } else {
      lastArgs = args;
      if (!timer) {
        timer = setTimeout(() => {
          timer = null;
          invoke(lastArgs)
        }, threshold - passed);
      }
    }
  }

  const flush = () => lastArgs && invoke(lastArgs);

  return [throttled, flush];
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (throttle);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/toFormData.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/helpers/toFormData.js ***!
  \******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _platform_node_classes_FormData_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../platform/node/classes/FormData.js */ "./node_modules/axios/lib/helpers/null.js");
/* provided dependency */ var Buffer = __webpack_require__(/*! buffer */ "./node_modules/buffer/index.js")["Buffer"];




// temporary hotfix to avoid circular references until AxiosURLSearchParams is refactored


/**
 * Determines if the given thing is a array or js object.
 *
 * @param {string} thing - The object or array to be visited.
 *
 * @returns {boolean}
 */
function isVisitable(thing) {
  return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isPlainObject(thing) || _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(thing);
}

/**
 * It removes the brackets from the end of a string
 *
 * @param {string} key - The key of the parameter.
 *
 * @returns {string} the key without the brackets.
 */
function removeBrackets(key) {
  return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].endsWith(key, '[]') ? key.slice(0, -2) : key;
}

/**
 * It takes a path, a key, and a boolean, and returns a string
 *
 * @param {string} path - The path to the current key.
 * @param {string} key - The key of the current object being iterated over.
 * @param {string} dots - If true, the key will be rendered with dots instead of brackets.
 *
 * @returns {string} The path to the current key.
 */
function renderKey(path, key, dots) {
  if (!path) return key;
  return path.concat(key).map(function each(token, i) {
    // eslint-disable-next-line no-param-reassign
    token = removeBrackets(token);
    return !dots && i ? '[' + token + ']' : token;
  }).join(dots ? '.' : '');
}

/**
 * If the array is an array and none of its elements are visitable, then it's a flat array.
 *
 * @param {Array<any>} arr - The array to check
 *
 * @returns {boolean}
 */
function isFlatArray(arr) {
  return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(arr) && !arr.some(isVisitable);
}

const predicates = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toFlatObject(_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"], {}, null, function filter(prop) {
  return /^is[A-Z]/.test(prop);
});

/**
 * Convert a data object to FormData
 *
 * @param {Object} obj
 * @param {?Object} [formData]
 * @param {?Object} [options]
 * @param {Function} [options.visitor]
 * @param {Boolean} [options.metaTokens = true]
 * @param {Boolean} [options.dots = false]
 * @param {?Boolean} [options.indexes = false]
 *
 * @returns {Object}
 **/

/**
 * It converts an object into a FormData object
 *
 * @param {Object<any, any>} obj - The object to convert to form data.
 * @param {string} formData - The FormData object to append to.
 * @param {Object<string, any>} options
 *
 * @returns
 */
function toFormData(obj, formData, options) {
  if (!_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isObject(obj)) {
    throw new TypeError('target must be an object');
  }

  // eslint-disable-next-line no-param-reassign
  formData = formData || new (_platform_node_classes_FormData_js__WEBPACK_IMPORTED_MODULE_1__["default"] || FormData)();

  // eslint-disable-next-line no-param-reassign
  options = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toFlatObject(options, {
    metaTokens: true,
    dots: false,
    indexes: false
  }, false, function defined(option, source) {
    // eslint-disable-next-line no-eq-null,eqeqeq
    return !_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isUndefined(source[option]);
  });

  const metaTokens = options.metaTokens;
  // eslint-disable-next-line no-use-before-define
  const visitor = options.visitor || defaultVisitor;
  const dots = options.dots;
  const indexes = options.indexes;
  const _Blob = options.Blob || typeof Blob !== 'undefined' && Blob;
  const useBlob = _Blob && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isSpecCompliantForm(formData);

  if (!_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFunction(visitor)) {
    throw new TypeError('visitor must be a function');
  }

  function convertValue(value) {
    if (value === null) return '';

    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isDate(value)) {
      return value.toISOString();
    }

    if (!useBlob && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isBlob(value)) {
      throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__["default"]('Blob is not supported. Use a Buffer instead.');
    }

    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArrayBuffer(value) || _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isTypedArray(value)) {
      return useBlob && typeof Blob === 'function' ? new Blob([value]) : Buffer.from(value);
    }

    return value;
  }

  /**
   * Default visitor.
   *
   * @param {*} value
   * @param {String|Number} key
   * @param {Array<String|Number>} path
   * @this {FormData}
   *
   * @returns {boolean} return true to visit the each prop of the value recursively
   */
  function defaultVisitor(value, key, path) {
    let arr = value;

    if (value && !path && typeof value === 'object') {
      if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].endsWith(key, '{}')) {
        // eslint-disable-next-line no-param-reassign
        key = metaTokens ? key : key.slice(0, -2);
        // eslint-disable-next-line no-param-reassign
        value = JSON.stringify(value);
      } else if (
        (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(value) && isFlatArray(value)) ||
        ((_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFileList(value) || _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].endsWith(key, '[]')) && (arr = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toArray(value))
        )) {
        // eslint-disable-next-line no-param-reassign
        key = removeBrackets(key);

        arr.forEach(function each(el, index) {
          !(_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isUndefined(el) || el === null) && formData.append(
            // eslint-disable-next-line no-nested-ternary
            indexes === true ? renderKey([key], index, dots) : (indexes === null ? key : key + '[]'),
            convertValue(el)
          );
        });
        return false;
      }
    }

    if (isVisitable(value)) {
      return true;
    }

    formData.append(renderKey(path, key, dots), convertValue(value));

    return false;
  }

  const stack = [];

  const exposedHelpers = Object.assign(predicates, {
    defaultVisitor,
    convertValue,
    isVisitable
  });

  function build(value, path) {
    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isUndefined(value)) return;

    if (stack.indexOf(value) !== -1) {
      throw Error('Circular reference detected in ' + path.join('.'));
    }

    stack.push(value);

    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEach(value, function each(el, key) {
      const result = !(_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isUndefined(el) || el === null) && visitor.call(
        formData, el, _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isString(key) ? key.trim() : key, path, exposedHelpers
      );

      if (result === true) {
        build(el, path ? path.concat(key) : [key]);
      }
    });

    stack.pop();
  }

  if (!_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isObject(obj)) {
    throw new TypeError('data must be an object');
  }

  build(obj);

  return formData;
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (toFormData);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/toURLEncodedForm.js":
/*!************************************************************!*\
  !*** ./node_modules/axios/lib/helpers/toURLEncodedForm.js ***!
  \************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ toURLEncodedForm)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _toFormData_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./toFormData.js */ "./node_modules/axios/lib/helpers/toFormData.js");
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");






function toURLEncodedForm(data, options) {
  return (0,_toFormData_js__WEBPACK_IMPORTED_MODULE_0__["default"])(data, new _platform_index_js__WEBPACK_IMPORTED_MODULE_1__["default"].classes.URLSearchParams(), Object.assign({
    visitor: function(value, key, path, helpers) {
      if (_platform_index_js__WEBPACK_IMPORTED_MODULE_1__["default"].isNode && _utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].isBuffer(value)) {
        this.append(key, value.toString('base64'));
        return false;
      }

      return helpers.defaultVisitor.apply(this, arguments);
    }
  }, options));
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/trackStream.js":
/*!*******************************************************!*\
  !*** ./node_modules/axios/lib/helpers/trackStream.js ***!
  \*******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   readBytes: () => (/* binding */ readBytes),
/* harmony export */   streamChunk: () => (/* binding */ streamChunk),
/* harmony export */   trackStream: () => (/* binding */ trackStream)
/* harmony export */ });

const streamChunk = function* (chunk, chunkSize) {
  let len = chunk.byteLength;

  if (!chunkSize || len < chunkSize) {
    yield chunk;
    return;
  }

  let pos = 0;
  let end;

  while (pos < len) {
    end = pos + chunkSize;
    yield chunk.slice(pos, end);
    pos = end;
  }
}

const readBytes = async function* (iterable, chunkSize) {
  for await (const chunk of readStream(iterable)) {
    yield* streamChunk(chunk, chunkSize);
  }
}

const readStream = async function* (stream) {
  if (stream[Symbol.asyncIterator]) {
    yield* stream;
    return;
  }

  const reader = stream.getReader();
  try {
    for (;;) {
      const {done, value} = await reader.read();
      if (done) {
        break;
      }
      yield value;
    }
  } finally {
    await reader.cancel();
  }
}

const trackStream = (stream, chunkSize, onProgress, onFinish) => {
  const iterator = readBytes(stream, chunkSize);

  let bytes = 0;
  let done;
  let _onFinish = (e) => {
    if (!done) {
      done = true;
      onFinish && onFinish(e);
    }
  }

  return new ReadableStream({
    async pull(controller) {
      try {
        const {done, value} = await iterator.next();

        if (done) {
         _onFinish();
          controller.close();
          return;
        }

        let len = value.byteLength;
        if (onProgress) {
          let loadedBytes = bytes += len;
          onProgress(loadedBytes);
        }
        controller.enqueue(new Uint8Array(value));
      } catch (err) {
        _onFinish(err);
        throw err;
      }
    },
    cancel(reason) {
      _onFinish(reason);
      return iterator.return();
    }
  }, {
    highWaterMark: 2
  })
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/validator.js":
/*!*****************************************************!*\
  !*** ./node_modules/axios/lib/helpers/validator.js ***!
  \*****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _env_data_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../env/data.js */ "./node_modules/axios/lib/env/data.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");





const validators = {};

// eslint-disable-next-line func-names
['object', 'boolean', 'number', 'function', 'string', 'symbol'].forEach((type, i) => {
  validators[type] = function validator(thing) {
    return typeof thing === type || 'a' + (i < 1 ? 'n ' : ' ') + type;
  };
});

const deprecatedWarnings = {};

/**
 * Transitional option validator
 *
 * @param {function|boolean?} validator - set to false if the transitional option has been removed
 * @param {string?} version - deprecated version / removed since version
 * @param {string?} message - some message with additional info
 *
 * @returns {function}
 */
validators.transitional = function transitional(validator, version, message) {
  function formatMessage(opt, desc) {
    return '[Axios v' + _env_data_js__WEBPACK_IMPORTED_MODULE_0__.VERSION + '] Transitional option \'' + opt + '\'' + desc + (message ? '. ' + message : '');
  }

  // eslint-disable-next-line func-names
  return (value, opt, opts) => {
    if (validator === false) {
      throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"](
        formatMessage(opt, ' has been removed' + (version ? ' in ' + version : '')),
        _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"].ERR_DEPRECATED
      );
    }

    if (version && !deprecatedWarnings[opt]) {
      deprecatedWarnings[opt] = true;
      // eslint-disable-next-line no-console
      console.warn(
        formatMessage(
          opt,
          ' has been deprecated since v' + version + ' and will be removed in the near future'
        )
      );
    }

    return validator ? validator(value, opt, opts) : true;
  };
};

validators.spelling = function spelling(correctSpelling) {
  return (value, opt) => {
    // eslint-disable-next-line no-console
    console.warn(`${opt} is likely a misspelling of ${correctSpelling}`);
    return true;
  }
};

/**
 * Assert object's properties type
 *
 * @param {object} options
 * @param {object} schema
 * @param {boolean?} allowUnknown
 *
 * @returns {object}
 */

function assertOptions(options, schema, allowUnknown) {
  if (typeof options !== 'object') {
    throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"]('options must be an object', _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"].ERR_BAD_OPTION_VALUE);
  }
  const keys = Object.keys(options);
  let i = keys.length;
  while (i-- > 0) {
    const opt = keys[i];
    const validator = schema[opt];
    if (validator) {
      const value = options[opt];
      const result = value === undefined || validator(value, opt, options);
      if (result !== true) {
        throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"]('option ' + opt + ' must be ' + result, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"].ERR_BAD_OPTION_VALUE);
      }
      continue;
    }
    if (allowUnknown !== true) {
      throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"]('Unknown option ' + opt, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"].ERR_BAD_OPTION);
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  assertOptions,
  validators
});


/***/ }),

/***/ "./node_modules/axios/lib/platform/browser/classes/Blob.js":
/*!*****************************************************************!*\
  !*** ./node_modules/axios/lib/platform/browser/classes/Blob.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (typeof Blob !== 'undefined' ? Blob : null);


/***/ }),

/***/ "./node_modules/axios/lib/platform/browser/classes/FormData.js":
/*!*********************************************************************!*\
  !*** ./node_modules/axios/lib/platform/browser/classes/FormData.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (typeof FormData !== 'undefined' ? FormData : null);


/***/ }),

/***/ "./node_modules/axios/lib/platform/browser/classes/URLSearchParams.js":
/*!****************************************************************************!*\
  !*** ./node_modules/axios/lib/platform/browser/classes/URLSearchParams.js ***!
  \****************************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _helpers_AxiosURLSearchParams_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../helpers/AxiosURLSearchParams.js */ "./node_modules/axios/lib/helpers/AxiosURLSearchParams.js");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (typeof URLSearchParams !== 'undefined' ? URLSearchParams : _helpers_AxiosURLSearchParams_js__WEBPACK_IMPORTED_MODULE_0__["default"]);


/***/ }),

/***/ "./node_modules/axios/lib/platform/browser/index.js":
/*!**********************************************************!*\
  !*** ./node_modules/axios/lib/platform/browser/index.js ***!
  \**********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _classes_URLSearchParams_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./classes/URLSearchParams.js */ "./node_modules/axios/lib/platform/browser/classes/URLSearchParams.js");
/* harmony import */ var _classes_FormData_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./classes/FormData.js */ "./node_modules/axios/lib/platform/browser/classes/FormData.js");
/* harmony import */ var _classes_Blob_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./classes/Blob.js */ "./node_modules/axios/lib/platform/browser/classes/Blob.js");




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  isBrowser: true,
  classes: {
    URLSearchParams: _classes_URLSearchParams_js__WEBPACK_IMPORTED_MODULE_0__["default"],
    FormData: _classes_FormData_js__WEBPACK_IMPORTED_MODULE_1__["default"],
    Blob: _classes_Blob_js__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  protocols: ['http', 'https', 'file', 'blob', 'url', 'data']
});


/***/ }),

/***/ "./node_modules/axios/lib/platform/common/utils.js":
/*!*********************************************************!*\
  !*** ./node_modules/axios/lib/platform/common/utils.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   hasBrowserEnv: () => (/* binding */ hasBrowserEnv),
/* harmony export */   hasStandardBrowserEnv: () => (/* binding */ hasStandardBrowserEnv),
/* harmony export */   hasStandardBrowserWebWorkerEnv: () => (/* binding */ hasStandardBrowserWebWorkerEnv),
/* harmony export */   navigator: () => (/* binding */ _navigator),
/* harmony export */   origin: () => (/* binding */ origin)
/* harmony export */ });
const hasBrowserEnv = typeof window !== 'undefined' && typeof document !== 'undefined';

const _navigator = typeof navigator === 'object' && navigator || undefined;

/**
 * Determine if we're running in a standard browser environment
 *
 * This allows axios to run in a web worker, and react-native.
 * Both environments support XMLHttpRequest, but not fully standard globals.
 *
 * web workers:
 *  typeof window -> undefined
 *  typeof document -> undefined
 *
 * react-native:
 *  navigator.product -> 'ReactNative'
 * nativescript
 *  navigator.product -> 'NativeScript' or 'NS'
 *
 * @returns {boolean}
 */
const hasStandardBrowserEnv = hasBrowserEnv &&
  (!_navigator || ['ReactNative', 'NativeScript', 'NS'].indexOf(_navigator.product) < 0);

/**
 * Determine if we're running in a standard browser webWorker environment
 *
 * Although the `isStandardBrowserEnv` method indicates that
 * `allows axios to run in a web worker`, the WebWorker will still be
 * filtered out due to its judgment standard
 * `typeof window !== 'undefined' && typeof document !== 'undefined'`.
 * This leads to a problem when axios post `FormData` in webWorker
 */
const hasStandardBrowserWebWorkerEnv = (() => {
  return (
    typeof WorkerGlobalScope !== 'undefined' &&
    // eslint-disable-next-line no-undef
    self instanceof WorkerGlobalScope &&
    typeof self.importScripts === 'function'
  );
})();

const origin = hasBrowserEnv && window.location.href || 'http://localhost';




/***/ }),

/***/ "./node_modules/axios/lib/platform/index.js":
/*!**************************************************!*\
  !*** ./node_modules/axios/lib/platform/index.js ***!
  \**************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_index_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node/index.js */ "./node_modules/axios/lib/platform/browser/index.js");
/* harmony import */ var _common_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./common/utils.js */ "./node_modules/axios/lib/platform/common/utils.js");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  ..._common_utils_js__WEBPACK_IMPORTED_MODULE_0__,
  ..._node_index_js__WEBPACK_IMPORTED_MODULE_1__["default"]
});


/***/ }),

/***/ "./node_modules/axios/lib/utils.js":
/*!*****************************************!*\
  !*** ./node_modules/axios/lib/utils.js ***!
  \*****************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _helpers_bind_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helpers/bind.js */ "./node_modules/axios/lib/helpers/bind.js");
/* provided dependency */ var process = __webpack_require__(/*! process/browser.js */ "./node_modules/process/browser.js");




// utils is a library of generic helper functions non-specific to axios

const {toString} = Object.prototype;
const {getPrototypeOf} = Object;
const {iterator, toStringTag} = Symbol;

const kindOf = (cache => thing => {
    const str = toString.call(thing);
    return cache[str] || (cache[str] = str.slice(8, -1).toLowerCase());
})(Object.create(null));

const kindOfTest = (type) => {
  type = type.toLowerCase();
  return (thing) => kindOf(thing) === type
}

const typeOfTest = type => thing => typeof thing === type;

/**
 * Determine if a value is an Array
 *
 * @param {Object} val The value to test
 *
 * @returns {boolean} True if value is an Array, otherwise false
 */
const {isArray} = Array;

/**
 * Determine if a value is undefined
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if the value is undefined, otherwise false
 */
const isUndefined = typeOfTest('undefined');

/**
 * Determine if a value is a Buffer
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Buffer, otherwise false
 */
function isBuffer(val) {
  return val !== null && !isUndefined(val) && val.constructor !== null && !isUndefined(val.constructor)
    && isFunction(val.constructor.isBuffer) && val.constructor.isBuffer(val);
}

/**
 * Determine if a value is an ArrayBuffer
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is an ArrayBuffer, otherwise false
 */
const isArrayBuffer = kindOfTest('ArrayBuffer');


/**
 * Determine if a value is a view on an ArrayBuffer
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a view on an ArrayBuffer, otherwise false
 */
function isArrayBufferView(val) {
  let result;
  if ((typeof ArrayBuffer !== 'undefined') && (ArrayBuffer.isView)) {
    result = ArrayBuffer.isView(val);
  } else {
    result = (val) && (val.buffer) && (isArrayBuffer(val.buffer));
  }
  return result;
}

/**
 * Determine if a value is a String
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a String, otherwise false
 */
const isString = typeOfTest('string');

/**
 * Determine if a value is a Function
 *
 * @param {*} val The value to test
 * @returns {boolean} True if value is a Function, otherwise false
 */
const isFunction = typeOfTest('function');

/**
 * Determine if a value is a Number
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Number, otherwise false
 */
const isNumber = typeOfTest('number');

/**
 * Determine if a value is an Object
 *
 * @param {*} thing The value to test
 *
 * @returns {boolean} True if value is an Object, otherwise false
 */
const isObject = (thing) => thing !== null && typeof thing === 'object';

/**
 * Determine if a value is a Boolean
 *
 * @param {*} thing The value to test
 * @returns {boolean} True if value is a Boolean, otherwise false
 */
const isBoolean = thing => thing === true || thing === false;

/**
 * Determine if a value is a plain Object
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a plain Object, otherwise false
 */
const isPlainObject = (val) => {
  if (kindOf(val) !== 'object') {
    return false;
  }

  const prototype = getPrototypeOf(val);
  return (prototype === null || prototype === Object.prototype || Object.getPrototypeOf(prototype) === null) && !(toStringTag in val) && !(iterator in val);
}

/**
 * Determine if a value is a Date
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Date, otherwise false
 */
const isDate = kindOfTest('Date');

/**
 * Determine if a value is a File
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a File, otherwise false
 */
const isFile = kindOfTest('File');

/**
 * Determine if a value is a Blob
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Blob, otherwise false
 */
const isBlob = kindOfTest('Blob');

/**
 * Determine if a value is a FileList
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a File, otherwise false
 */
const isFileList = kindOfTest('FileList');

/**
 * Determine if a value is a Stream
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Stream, otherwise false
 */
const isStream = (val) => isObject(val) && isFunction(val.pipe);

/**
 * Determine if a value is a FormData
 *
 * @param {*} thing The value to test
 *
 * @returns {boolean} True if value is an FormData, otherwise false
 */
const isFormData = (thing) => {
  let kind;
  return thing && (
    (typeof FormData === 'function' && thing instanceof FormData) || (
      isFunction(thing.append) && (
        (kind = kindOf(thing)) === 'formdata' ||
        // detect form-data instance
        (kind === 'object' && isFunction(thing.toString) && thing.toString() === '[object FormData]')
      )
    )
  )
}

/**
 * Determine if a value is a URLSearchParams object
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a URLSearchParams object, otherwise false
 */
const isURLSearchParams = kindOfTest('URLSearchParams');

const [isReadableStream, isRequest, isResponse, isHeaders] = ['ReadableStream', 'Request', 'Response', 'Headers'].map(kindOfTest);

/**
 * Trim excess whitespace off the beginning and end of a string
 *
 * @param {String} str The String to trim
 *
 * @returns {String} The String freed of excess whitespace
 */
const trim = (str) => str.trim ?
  str.trim() : str.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');

/**
 * Iterate over an Array or an Object invoking a function for each item.
 *
 * If `obj` is an Array callback will be called passing
 * the value, index, and complete array for each item.
 *
 * If 'obj' is an Object callback will be called passing
 * the value, key, and complete object for each property.
 *
 * @param {Object|Array} obj The object to iterate
 * @param {Function} fn The callback to invoke for each item
 *
 * @param {Boolean} [allOwnKeys = false]
 * @returns {any}
 */
function forEach(obj, fn, {allOwnKeys = false} = {}) {
  // Don't bother if no value provided
  if (obj === null || typeof obj === 'undefined') {
    return;
  }

  let i;
  let l;

  // Force an array if not already something iterable
  if (typeof obj !== 'object') {
    /*eslint no-param-reassign:0*/
    obj = [obj];
  }

  if (isArray(obj)) {
    // Iterate over array values
    for (i = 0, l = obj.length; i < l; i++) {
      fn.call(null, obj[i], i, obj);
    }
  } else {
    // Iterate over object keys
    const keys = allOwnKeys ? Object.getOwnPropertyNames(obj) : Object.keys(obj);
    const len = keys.length;
    let key;

    for (i = 0; i < len; i++) {
      key = keys[i];
      fn.call(null, obj[key], key, obj);
    }
  }
}

function findKey(obj, key) {
  key = key.toLowerCase();
  const keys = Object.keys(obj);
  let i = keys.length;
  let _key;
  while (i-- > 0) {
    _key = keys[i];
    if (key === _key.toLowerCase()) {
      return _key;
    }
  }
  return null;
}

const _global = (() => {
  /*eslint no-undef:0*/
  if (typeof globalThis !== "undefined") return globalThis;
  return typeof self !== "undefined" ? self : (typeof window !== 'undefined' ? window : global)
})();

const isContextDefined = (context) => !isUndefined(context) && context !== _global;

/**
 * Accepts varargs expecting each argument to be an object, then
 * immutably merges the properties of each object and returns result.
 *
 * When multiple objects contain the same key the later object in
 * the arguments list will take precedence.
 *
 * Example:
 *
 * ```js
 * var result = merge({foo: 123}, {foo: 456});
 * console.log(result.foo); // outputs 456
 * ```
 *
 * @param {Object} obj1 Object to merge
 *
 * @returns {Object} Result of all merge properties
 */
function merge(/* obj1, obj2, obj3, ... */) {
  const {caseless} = isContextDefined(this) && this || {};
  const result = {};
  const assignValue = (val, key) => {
    const targetKey = caseless && findKey(result, key) || key;
    if (isPlainObject(result[targetKey]) && isPlainObject(val)) {
      result[targetKey] = merge(result[targetKey], val);
    } else if (isPlainObject(val)) {
      result[targetKey] = merge({}, val);
    } else if (isArray(val)) {
      result[targetKey] = val.slice();
    } else {
      result[targetKey] = val;
    }
  }

  for (let i = 0, l = arguments.length; i < l; i++) {
    arguments[i] && forEach(arguments[i], assignValue);
  }
  return result;
}

/**
 * Extends object a by mutably adding to it the properties of object b.
 *
 * @param {Object} a The object to be extended
 * @param {Object} b The object to copy properties from
 * @param {Object} thisArg The object to bind function to
 *
 * @param {Boolean} [allOwnKeys]
 * @returns {Object} The resulting value of object a
 */
const extend = (a, b, thisArg, {allOwnKeys}= {}) => {
  forEach(b, (val, key) => {
    if (thisArg && isFunction(val)) {
      a[key] = (0,_helpers_bind_js__WEBPACK_IMPORTED_MODULE_0__["default"])(val, thisArg);
    } else {
      a[key] = val;
    }
  }, {allOwnKeys});
  return a;
}

/**
 * Remove byte order marker. This catches EF BB BF (the UTF-8 BOM)
 *
 * @param {string} content with BOM
 *
 * @returns {string} content value without BOM
 */
const stripBOM = (content) => {
  if (content.charCodeAt(0) === 0xFEFF) {
    content = content.slice(1);
  }
  return content;
}

/**
 * Inherit the prototype methods from one constructor into another
 * @param {function} constructor
 * @param {function} superConstructor
 * @param {object} [props]
 * @param {object} [descriptors]
 *
 * @returns {void}
 */
const inherits = (constructor, superConstructor, props, descriptors) => {
  constructor.prototype = Object.create(superConstructor.prototype, descriptors);
  constructor.prototype.constructor = constructor;
  Object.defineProperty(constructor, 'super', {
    value: superConstructor.prototype
  });
  props && Object.assign(constructor.prototype, props);
}

/**
 * Resolve object with deep prototype chain to a flat object
 * @param {Object} sourceObj source object
 * @param {Object} [destObj]
 * @param {Function|Boolean} [filter]
 * @param {Function} [propFilter]
 *
 * @returns {Object}
 */
const toFlatObject = (sourceObj, destObj, filter, propFilter) => {
  let props;
  let i;
  let prop;
  const merged = {};

  destObj = destObj || {};
  // eslint-disable-next-line no-eq-null,eqeqeq
  if (sourceObj == null) return destObj;

  do {
    props = Object.getOwnPropertyNames(sourceObj);
    i = props.length;
    while (i-- > 0) {
      prop = props[i];
      if ((!propFilter || propFilter(prop, sourceObj, destObj)) && !merged[prop]) {
        destObj[prop] = sourceObj[prop];
        merged[prop] = true;
      }
    }
    sourceObj = filter !== false && getPrototypeOf(sourceObj);
  } while (sourceObj && (!filter || filter(sourceObj, destObj)) && sourceObj !== Object.prototype);

  return destObj;
}

/**
 * Determines whether a string ends with the characters of a specified string
 *
 * @param {String} str
 * @param {String} searchString
 * @param {Number} [position= 0]
 *
 * @returns {boolean}
 */
const endsWith = (str, searchString, position) => {
  str = String(str);
  if (position === undefined || position > str.length) {
    position = str.length;
  }
  position -= searchString.length;
  const lastIndex = str.indexOf(searchString, position);
  return lastIndex !== -1 && lastIndex === position;
}


/**
 * Returns new array from array like object or null if failed
 *
 * @param {*} [thing]
 *
 * @returns {?Array}
 */
const toArray = (thing) => {
  if (!thing) return null;
  if (isArray(thing)) return thing;
  let i = thing.length;
  if (!isNumber(i)) return null;
  const arr = new Array(i);
  while (i-- > 0) {
    arr[i] = thing[i];
  }
  return arr;
}

/**
 * Checking if the Uint8Array exists and if it does, it returns a function that checks if the
 * thing passed in is an instance of Uint8Array
 *
 * @param {TypedArray}
 *
 * @returns {Array}
 */
// eslint-disable-next-line func-names
const isTypedArray = (TypedArray => {
  // eslint-disable-next-line func-names
  return thing => {
    return TypedArray && thing instanceof TypedArray;
  };
})(typeof Uint8Array !== 'undefined' && getPrototypeOf(Uint8Array));

/**
 * For each entry in the object, call the function with the key and value.
 *
 * @param {Object<any, any>} obj - The object to iterate over.
 * @param {Function} fn - The function to call for each entry.
 *
 * @returns {void}
 */
const forEachEntry = (obj, fn) => {
  const generator = obj && obj[iterator];

  const _iterator = generator.call(obj);

  let result;

  while ((result = _iterator.next()) && !result.done) {
    const pair = result.value;
    fn.call(obj, pair[0], pair[1]);
  }
}

/**
 * It takes a regular expression and a string, and returns an array of all the matches
 *
 * @param {string} regExp - The regular expression to match against.
 * @param {string} str - The string to search.
 *
 * @returns {Array<boolean>}
 */
const matchAll = (regExp, str) => {
  let matches;
  const arr = [];

  while ((matches = regExp.exec(str)) !== null) {
    arr.push(matches);
  }

  return arr;
}

/* Checking if the kindOfTest function returns true when passed an HTMLFormElement. */
const isHTMLForm = kindOfTest('HTMLFormElement');

const toCamelCase = str => {
  return str.toLowerCase().replace(/[-_\s]([a-z\d])(\w*)/g,
    function replacer(m, p1, p2) {
      return p1.toUpperCase() + p2;
    }
  );
};

/* Creating a function that will check if an object has a property. */
const hasOwnProperty = (({hasOwnProperty}) => (obj, prop) => hasOwnProperty.call(obj, prop))(Object.prototype);

/**
 * Determine if a value is a RegExp object
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a RegExp object, otherwise false
 */
const isRegExp = kindOfTest('RegExp');

const reduceDescriptors = (obj, reducer) => {
  const descriptors = Object.getOwnPropertyDescriptors(obj);
  const reducedDescriptors = {};

  forEach(descriptors, (descriptor, name) => {
    let ret;
    if ((ret = reducer(descriptor, name, obj)) !== false) {
      reducedDescriptors[name] = ret || descriptor;
    }
  });

  Object.defineProperties(obj, reducedDescriptors);
}

/**
 * Makes all methods read-only
 * @param {Object} obj
 */

const freezeMethods = (obj) => {
  reduceDescriptors(obj, (descriptor, name) => {
    // skip restricted props in strict mode
    if (isFunction(obj) && ['arguments', 'caller', 'callee'].indexOf(name) !== -1) {
      return false;
    }

    const value = obj[name];

    if (!isFunction(value)) return;

    descriptor.enumerable = false;

    if ('writable' in descriptor) {
      descriptor.writable = false;
      return;
    }

    if (!descriptor.set) {
      descriptor.set = () => {
        throw Error('Can not rewrite read-only method \'' + name + '\'');
      };
    }
  });
}

const toObjectSet = (arrayOrString, delimiter) => {
  const obj = {};

  const define = (arr) => {
    arr.forEach(value => {
      obj[value] = true;
    });
  }

  isArray(arrayOrString) ? define(arrayOrString) : define(String(arrayOrString).split(delimiter));

  return obj;
}

const noop = () => {}

const toFiniteNumber = (value, defaultValue) => {
  return value != null && Number.isFinite(value = +value) ? value : defaultValue;
}

/**
 * If the thing is a FormData object, return true, otherwise return false.
 *
 * @param {unknown} thing - The thing to check.
 *
 * @returns {boolean}
 */
function isSpecCompliantForm(thing) {
  return !!(thing && isFunction(thing.append) && thing[toStringTag] === 'FormData' && thing[iterator]);
}

const toJSONObject = (obj) => {
  const stack = new Array(10);

  const visit = (source, i) => {

    if (isObject(source)) {
      if (stack.indexOf(source) >= 0) {
        return;
      }

      if(!('toJSON' in source)) {
        stack[i] = source;
        const target = isArray(source) ? [] : {};

        forEach(source, (value, key) => {
          const reducedValue = visit(value, i + 1);
          !isUndefined(reducedValue) && (target[key] = reducedValue);
        });

        stack[i] = undefined;

        return target;
      }
    }

    return source;
  }

  return visit(obj, 0);
}

const isAsyncFn = kindOfTest('AsyncFunction');

const isThenable = (thing) =>
  thing && (isObject(thing) || isFunction(thing)) && isFunction(thing.then) && isFunction(thing.catch);

// original code
// https://github.com/DigitalBrainJS/AxiosPromise/blob/16deab13710ec09779922131f3fa5954320f83ab/lib/utils.js#L11-L34

const _setImmediate = ((setImmediateSupported, postMessageSupported) => {
  if (setImmediateSupported) {
    return setImmediate;
  }

  return postMessageSupported ? ((token, callbacks) => {
    _global.addEventListener("message", ({source, data}) => {
      if (source === _global && data === token) {
        callbacks.length && callbacks.shift()();
      }
    }, false);

    return (cb) => {
      callbacks.push(cb);
      _global.postMessage(token, "*");
    }
  })(`axios@${Math.random()}`, []) : (cb) => setTimeout(cb);
})(
  typeof setImmediate === 'function',
  isFunction(_global.postMessage)
);

const asap = typeof queueMicrotask !== 'undefined' ?
  queueMicrotask.bind(_global) : ( typeof process !== 'undefined' && process.nextTick || _setImmediate);

// *********************


const isIterable = (thing) => thing != null && isFunction(thing[iterator]);


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  isArray,
  isArrayBuffer,
  isBuffer,
  isFormData,
  isArrayBufferView,
  isString,
  isNumber,
  isBoolean,
  isObject,
  isPlainObject,
  isReadableStream,
  isRequest,
  isResponse,
  isHeaders,
  isUndefined,
  isDate,
  isFile,
  isBlob,
  isRegExp,
  isFunction,
  isStream,
  isURLSearchParams,
  isTypedArray,
  isFileList,
  forEach,
  merge,
  extend,
  trim,
  stripBOM,
  inherits,
  toFlatObject,
  kindOf,
  kindOfTest,
  endsWith,
  toArray,
  forEachEntry,
  matchAll,
  isHTMLForm,
  hasOwnProperty,
  hasOwnProp: hasOwnProperty, // an alias to avoid ESLint no-prototype-builtins detection
  reduceDescriptors,
  freezeMethods,
  toObjectSet,
  toCamelCase,
  noop,
  toFiniteNumber,
  findKey,
  global: _global,
  isContextDefined,
  isSpecCompliantForm,
  toJSONObject,
  isAsyncFn,
  isThenable,
  setImmediate: _setImmediate,
  asap,
  isIterable
});


/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapFlowComponent.vue?vue&type=script&lang=js":
/*!**********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapFlowComponent.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! axios */ "./node_modules/axios/lib/axios.js");
/* harmony import */ var _vue_flow_core__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @vue-flow/core */ "./node_modules/@vue-flow/core/dist/vue-flow-core.mjs");
/* harmony import */ var _vue_flow_core_dist_style_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @vue-flow/core/dist/style.css */ "./node_modules/@vue-flow/core/dist/style.css");
/* harmony import */ var vue_toastification__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-toastification */ "./node_modules/vue-toastification/dist/index.mjs");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return r; }; var t, r = {}, e = Object.prototype, n = e.hasOwnProperty, o = "function" == typeof Symbol ? Symbol : {}, i = o.iterator || "@@iterator", a = o.asyncIterator || "@@asyncIterator", u = o.toStringTag || "@@toStringTag"; function c(t, r, e, n) { return Object.defineProperty(t, r, { value: e, enumerable: !n, configurable: !n, writable: !n }); } try { c({}, ""); } catch (t) { c = function c(t, r, e) { return t[r] = e; }; } function h(r, e, n, o) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype); return c(a, "_invoke", function (r, e, n) { var o = 1; return function (i, a) { if (3 === o) throw Error("Generator is already running"); if (4 === o) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var u = n.delegate; if (u) { var c = d(u, n); if (c) { if (c === f) continue; return c; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (1 === o) throw o = 4, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = 3; var h = s(r, e, n); if ("normal" === h.type) { if (o = n.done ? 4 : 2, h.arg === f) continue; return { value: h.arg, done: n.done }; } "throw" === h.type && (o = 4, n.method = "throw", n.arg = h.arg); } }; }(r, n, new Context(o || [])), !0), a; } function s(t, r, e) { try { return { type: "normal", arg: t.call(r, e) }; } catch (t) { return { type: "throw", arg: t }; } } r.wrap = h; var f = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var l = {}; c(l, i, function () { return this; }); var p = Object.getPrototypeOf, y = p && p(p(x([]))); y && y !== e && n.call(y, i) && (l = y); var v = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(l); function g(t) { ["next", "throw", "return"].forEach(function (r) { c(t, r, function (t) { return this._invoke(r, t); }); }); } function AsyncIterator(t, r) { function e(o, i, a, u) { var c = s(t[o], t, i); if ("throw" !== c.type) { var h = c.arg, f = h.value; return f && "object" == _typeof(f) && n.call(f, "__await") ? r.resolve(f.__await).then(function (t) { e("next", t, a, u); }, function (t) { e("throw", t, a, u); }) : r.resolve(f).then(function (t) { h.value = t, a(h); }, function (t) { return e("throw", t, a, u); }); } u(c.arg); } var o; c(this, "_invoke", function (t, n) { function i() { return new r(function (r, o) { e(t, n, r, o); }); } return o = o ? o.then(i, i) : i(); }, !0); } function d(r, e) { var n = e.method, o = r.i[n]; if (o === t) return e.delegate = null, "throw" === n && r.i["return"] && (e.method = "return", e.arg = t, d(r, e), "throw" === e.method) || "return" !== n && (e.method = "throw", e.arg = new TypeError("The iterator does not provide a '" + n + "' method")), f; var i = s(o, r.i, e.arg); if ("throw" === i.type) return e.method = "throw", e.arg = i.arg, e.delegate = null, f; var a = i.arg; return a ? a.done ? (e[r.r] = a.value, e.next = r.n, "return" !== e.method && (e.method = "next", e.arg = t), e.delegate = null, f) : a : (e.method = "throw", e.arg = new TypeError("iterator result is not an object"), e.delegate = null, f); } function w(t) { this.tryEntries.push(t); } function m(r) { var e = r[4] || {}; e.type = "normal", e.arg = t, r[4] = e; } function Context(t) { this.tryEntries = [[-1]], t.forEach(w, this), this.reset(!0); } function x(r) { if (null != r) { var e = r[i]; if (e) return e.call(r); if ("function" == typeof r.next) return r; if (!isNaN(r.length)) { var o = -1, a = function e() { for (; ++o < r.length;) if (n.call(r, o)) return e.value = r[o], e.done = !1, e; return e.value = t, e.done = !0, e; }; return a.next = a; } } throw new TypeError(_typeof(r) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, c(v, "constructor", GeneratorFunctionPrototype), c(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = c(GeneratorFunctionPrototype, u, "GeneratorFunction"), r.isGeneratorFunction = function (t) { var r = "function" == typeof t && t.constructor; return !!r && (r === GeneratorFunction || "GeneratorFunction" === (r.displayName || r.name)); }, r.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, c(t, u, "GeneratorFunction")), t.prototype = Object.create(v), t; }, r.awrap = function (t) { return { __await: t }; }, g(AsyncIterator.prototype), c(AsyncIterator.prototype, a, function () { return this; }), r.AsyncIterator = AsyncIterator, r.async = function (t, e, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(h(t, e, n, o), i); return r.isGeneratorFunction(e) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, g(v), c(v, u, "Generator"), c(v, i, function () { return this; }), c(v, "toString", function () { return "[object Generator]"; }), r.keys = function (t) { var r = Object(t), e = []; for (var n in r) e.unshift(n); return function t() { for (; e.length;) if ((n = e.pop()) in r) return t.value = n, t.done = !1, t; return t.done = !0, t; }; }, r.values = x, Context.prototype = { constructor: Context, reset: function reset(r) { if (this.prev = this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(m), !r) for (var e in this) "t" === e.charAt(0) && n.call(this, e) && !isNaN(+e.slice(1)) && (this[e] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0][4]; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(r) { if (this.done) throw r; var e = this; function n(t) { a.type = "throw", a.arg = r, e.next = t; } for (var o = e.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i[4], u = this.prev, c = i[1], h = i[2]; if (-1 === i[0]) return n("end"), !1; if (!c && !h) throw Error("try statement without catch or finally"); if (null != i[0] && i[0] <= u) { if (u < c) return this.method = "next", this.arg = t, n(c), !0; if (u < h) return n(h), !1; } } }, abrupt: function abrupt(t, r) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var n = this.tryEntries[e]; if (n[0] > -1 && n[0] <= this.prev && this.prev < n[2]) { var o = n; break; } } o && ("break" === t || "continue" === t) && o[0] <= r && r <= o[2] && (o = null); var i = o ? o[4] : {}; return i.type = t, i.arg = r, o ? (this.method = "next", this.next = o[2], f) : this.complete(i); }, complete: function complete(t, r) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && r && (this.next = r), f; }, finish: function finish(t) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var e = this.tryEntries[r]; if (e[2] === t) return this.complete(e[4], e[3]), m(e), f; } }, "catch": function _catch(t) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var e = this.tryEntries[r]; if (e[0] === t) { var n = e[4]; if ("throw" === n.type) { var o = n.arg; m(e); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(r, e, n) { return this.delegate = { i: x(r), r: e, n: n }, "next" === this.method && (this.arg = t), f; } }, r; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }






// Define custom NAP node component
var NapNode = {
  props: ['id', 'data', 'selected'],
  template: "\n    <div class=\"nap-node\" :class=\"['nap-node-' + data.status]\">\n      <div class=\"nap-node-header\">\n        <div class=\"nap-node-title\">{{ data.label }}</div>\n        <div class=\"nap-node-code\">{{ data.code }}</div>\n      </div>\n      <div class=\"nap-node-content\">\n        <div class=\"nap-node-status\">Status: {{ data.status }}</div>\n        <div class=\"nap-node-occupancy\">\n          Occupancy: {{ data.occupancy }}%\n          <div class=\"occupancy-bar\">\n            <div class=\"occupancy-fill\" :style=\"{ width: data.occupancy + '%' }\"></div>\n          </div>\n        </div>\n        <div class=\"nap-node-level\">Level: {{ data.level }}</div>\n      </div>\n    </div>\n  "
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "NapFlowComponent",
  components: {
    VueFlow: _vue_flow_core__WEBPACK_IMPORTED_MODULE_3__.VueFlow,
    Background: _vue_flow_core__WEBPACK_IMPORTED_MODULE_3__.Background,
    MiniMap: _vue_flow_core__WEBPACK_IMPORTED_MODULE_3__.MiniMap,
    Controls: _vue_flow_core__WEBPACK_IMPORTED_MODULE_3__.Controls,
    Panel: _vue_flow_core__WEBPACK_IMPORTED_MODULE_3__.Panel,
    NapNode: NapNode
  },
  setup: function setup() {
    var loading = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(true);
    var error = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    var nodes = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)([]);
    var edges = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)([]);
    var toast = (0,vue_toastification__WEBPACK_IMPORTED_MODULE_2__.useToast)();

    // Form state
    var showNapForm = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(false);
    var showPortForm = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(false);
    var isEditMode = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(false);
    var selectedNapBox = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    var selectedPort = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);

    // NAP Box form data
    var napBoxForm = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)({
      name: '',
      code: '',
      address: '',
      latitude: 0,
      longitude: 0,
      status: 'active',
      capacity: 8,
      technology_type: 'fiber',
      installation_date: new Date().toISOString().split('T')[0],
      brand: '',
      model: '',
      parent_nap_id: null
    });

    // Port form data
    var portForm = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)({
      port_number: 1,
      port_name: '',
      status: 'available',
      connection_type: 'fiber',
      technician_notes: ''
    });

    // Define node types
    var nodeTypes = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)({
      napNode: NapNode
    });

    // Define default edge options
    var defaultEdgeOptions = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)({
      type: 'smoothstep',
      animated: true
    });

    // Get node color based on status
    var getNodeColor = function getNodeColor(status) {
      switch (status) {
        case 'active':
          return '#4CAF50';
        case 'inactive':
          return '#9E9E9E';
        case 'maintenance':
          return '#FFC107';
        case 'damaged':
          return '#F44336';
        default:
          return '#2196F3';
      }
    };

    // Fetch flow data from API
    var fetchFlowData = /*#__PURE__*/function () {
      var _ref = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
        var response;
        return _regeneratorRuntime().wrap(function _callee$(_context) {
          while (1) switch (_context.prev = _context.next) {
            case 0:
              _context.prev = 0;
              loading.value = true;
              _context.next = 4;
              return axios__WEBPACK_IMPORTED_MODULE_4__["default"].get('/nova-vendor/nap-manager/flow-data');
            case 4:
              response = _context.sent;
              nodes.value = response.data.nodes;
              edges.value = response.data.edges;
              loading.value = false;
              _context.next = 15;
              break;
            case 10:
              _context.prev = 10;
              _context.t0 = _context["catch"](0);
              error.value = 'Error fetching flow data: ' + _context.t0.message;
              console.error('Error fetching flow data:', _context.t0);
              loading.value = false;
            case 15:
            case "end":
              return _context.stop();
          }
        }, _callee, null, [[0, 10]]);
      }));
      return function fetchFlowData() {
        return _ref.apply(this, arguments);
      };
    }();

    // Handle node drag stop event
    var onNodeDragEnd = /*#__PURE__*/function () {
      var _ref2 = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee2(event) {
        var node;
        return _regeneratorRuntime().wrap(function _callee2$(_context2) {
          while (1) switch (_context2.prev = _context2.next) {
            case 0:
              _context2.prev = 0;
              node = event.node; // Update node position in database
              _context2.next = 4;
              return axios__WEBPACK_IMPORTED_MODULE_4__["default"].post("/nova-vendor/nap-manager/update-node-position/".concat(node.id), {
                x: node.position.x,
                y: node.position.y
              });
            case 4:
              _context2.next = 9;
              break;
            case 6:
              _context2.prev = 6;
              _context2.t0 = _context2["catch"](0);
              console.error('Error updating node position:', _context2.t0);
            case 9:
            case "end":
              return _context2.stop();
          }
        }, _callee2, null, [[0, 6]]);
      }));
      return function onNodeDragEnd(_x) {
        return _ref2.apply(this, arguments);
      };
    }();

    // Handle connect event
    var onEdgeConnect = /*#__PURE__*/function () {
      var _ref3 = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee3(params) {
        return _regeneratorRuntime().wrap(function _callee3$(_context3) {
          while (1) switch (_context3.prev = _context3.next) {
            case 0:
              _context3.prev = 0;
              _context3.next = 3;
              return axios__WEBPACK_IMPORTED_MODULE_4__["default"].post('/nova-vendor/nap-manager/update-connection', {
                source: params.source,
                target: params.target
              });
            case 3:
              _context3.next = 5;
              return fetchFlowData();
            case 5:
              _context3.next = 10;
              break;
            case 7:
              _context3.prev = 7;
              _context3.t0 = _context3["catch"](0);
              console.error('Error updating connection:', _context3.t0);
            case 10:
            case "end":
              return _context3.stop();
          }
        }, _callee3, null, [[0, 7]]);
      }));
      return function onEdgeConnect(_x2) {
        return _ref3.apply(this, arguments);
      };
    }();

    // Handle node click event
    var onNodeClick = function onNodeClick(event) {
      console.log('Node clicked:', event.node);
      // Get the NAP box details for the clicked node
      var napBoxId = event.node.id;
      fetchNapBoxDetails(napBoxId);
    };

    // Fetch NAP box details
    var fetchNapBoxDetails = /*#__PURE__*/function () {
      var _ref4 = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee4(napBoxId) {
        var response;
        return _regeneratorRuntime().wrap(function _callee4$(_context4) {
          while (1) switch (_context4.prev = _context4.next) {
            case 0:
              _context4.prev = 0;
              _context4.next = 3;
              return axios__WEBPACK_IMPORTED_MODULE_4__["default"].get("/nova-vendor/nap-manager/nap-box/".concat(napBoxId));
            case 3:
              response = _context4.sent;
              selectedNapBox.value = response.data;
              _context4.next = 11;
              break;
            case 7:
              _context4.prev = 7;
              _context4.t0 = _context4["catch"](0);
              console.error('Error fetching NAP box details:', _context4.t0);
              toast.error('Error fetching NAP box details');
            case 11:
            case "end":
              return _context4.stop();
          }
        }, _callee4, null, [[0, 7]]);
      }));
      return function fetchNapBoxDetails(_x3) {
        return _ref4.apply(this, arguments);
      };
    }();

    // Get user's current location
    var getUserLocation = function getUserLocation() {
      return new Promise(function (resolve, reject) {
        if (!navigator.geolocation) {
          toast.warning('Geolocation is not supported by your browser');
          resolve({
            latitude: 0,
            longitude: 0
          });
          return;
        }
        navigator.geolocation.getCurrentPosition(function (position) {
          resolve({
            latitude: position.coords.latitude,
            longitude: position.coords.longitude
          });
        }, function (error) {
          console.error('Error getting user location:', error);
          toast.warning('Could not get your location: ' + error.message);
          resolve({
            latitude: 0,
            longitude: 0
          });
        }, {
          enableHighAccuracy: true,
          timeout: 5000,
          maximumAge: 0
        });
      });
    };

    // Open NAP box form for creating a new NAP box
    var openCreateNapForm = /*#__PURE__*/function () {
      var _ref5 = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee5() {
        var location;
        return _regeneratorRuntime().wrap(function _callee5$(_context5) {
          while (1) switch (_context5.prev = _context5.next) {
            case 0:
              isEditMode.value = false;

              // Get user's current location
              _context5.next = 3;
              return getUserLocation();
            case 3:
              location = _context5.sent;
              napBoxForm.value = {
                name: '',
                code: '',
                address: '',
                latitude: location.latitude,
                longitude: location.longitude,
                status: 'active',
                capacity: 8,
                technology_type: 'fiber',
                installation_date: new Date().toISOString().split('T')[0],
                brand: '',
                model: '',
                parent_nap_id: null
              };
              showNapForm.value = true;
            case 6:
            case "end":
              return _context5.stop();
          }
        }, _callee5);
      }));
      return function openCreateNapForm() {
        return _ref5.apply(this, arguments);
      };
    }();

    // Open NAP box form for editing an existing NAP box
    var openEditNapForm = function openEditNapForm(napBox) {
      isEditMode.value = true;
      napBoxForm.value = _objectSpread({}, napBox);
      showNapForm.value = true;
    };

    // Close NAP box form
    var closeNapForm = function closeNapForm() {
      showNapForm.value = false;
      selectedNapBox.value = null;
    };

    // Submit NAP box form
    var submitNapForm = /*#__PURE__*/function () {
      var _ref6 = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee6() {
        var _err$response;
        return _regeneratorRuntime().wrap(function _callee6$(_context6) {
          while (1) switch (_context6.prev = _context6.next) {
            case 0:
              _context6.prev = 0;
              loading.value = true;
              if (!isEditMode.value) {
                _context6.next = 8;
                break;
              }
              _context6.next = 5;
              return axios__WEBPACK_IMPORTED_MODULE_4__["default"].put("/nova-vendor/nap-manager/nap-box/".concat(napBoxForm.value.id), napBoxForm.value);
            case 5:
              toast.success('NAP box updated successfully');
              _context6.next = 11;
              break;
            case 8:
              _context6.next = 10;
              return axios__WEBPACK_IMPORTED_MODULE_4__["default"].post('/nova-vendor/nap-manager/nap-box', napBoxForm.value);
            case 10:
              toast.success('NAP box created successfully');
            case 11:
              _context6.next = 13;
              return fetchFlowData();
            case 13:
              closeNapForm();
              _context6.next = 20;
              break;
            case 16:
              _context6.prev = 16;
              _context6.t0 = _context6["catch"](0);
              console.error('Error submitting NAP box form:', _context6.t0);
              toast.error('Error saving NAP box: ' + (((_err$response = _context6.t0.response) === null || _err$response === void 0 || (_err$response = _err$response.data) === null || _err$response === void 0 ? void 0 : _err$response.message) || _context6.t0.message));
            case 20:
              _context6.prev = 20;
              loading.value = false;
              return _context6.finish(20);
            case 23:
            case "end":
              return _context6.stop();
          }
        }, _callee6, null, [[0, 16, 20, 23]]);
      }));
      return function submitNapForm() {
        return _ref6.apply(this, arguments);
      };
    }();

    // Delete NAP box
    var deleteNapBox = /*#__PURE__*/function () {
      var _ref7 = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee7(napBoxId) {
        var _err$response2;
        return _regeneratorRuntime().wrap(function _callee7$(_context7) {
          while (1) switch (_context7.prev = _context7.next) {
            case 0:
              if (confirm('Are you sure you want to delete this NAP box? This will also delete all associated ports.')) {
                _context7.next = 2;
                break;
              }
              return _context7.abrupt("return");
            case 2:
              _context7.prev = 2;
              loading.value = true;
              _context7.next = 6;
              return axios__WEBPACK_IMPORTED_MODULE_4__["default"]["delete"]("/nova-vendor/nap-manager/nap-box/".concat(napBoxId));
            case 6:
              toast.success('NAP box deleted successfully');
              _context7.next = 9;
              return fetchFlowData();
            case 9:
              selectedNapBox.value = null;
              _context7.next = 16;
              break;
            case 12:
              _context7.prev = 12;
              _context7.t0 = _context7["catch"](2);
              console.error('Error deleting NAP box:', _context7.t0);
              toast.error('Error deleting NAP box: ' + (((_err$response2 = _context7.t0.response) === null || _err$response2 === void 0 || (_err$response2 = _err$response2.data) === null || _err$response2 === void 0 ? void 0 : _err$response2.message) || _context7.t0.message));
            case 16:
              _context7.prev = 16;
              loading.value = false;
              return _context7.finish(16);
            case 19:
            case "end":
              return _context7.stop();
          }
        }, _callee7, null, [[2, 12, 16, 19]]);
      }));
      return function deleteNapBox(_x4) {
        return _ref7.apply(this, arguments);
      };
    }();

    // Open port form for creating a new port
    var openCreatePortForm = function openCreatePortForm(napBoxId) {
      isEditMode.value = false;
      portForm.value = {
        nap_box_id: napBoxId,
        port_number: 1,
        port_name: '',
        status: 'available',
        connection_type: 'fiber',
        technician_notes: ''
      };
      showPortForm.value = true;
    };

    // Open port form for editing an existing port
    var openEditPortForm = /*#__PURE__*/function () {
      var _ref8 = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee8(portId) {
        var response;
        return _regeneratorRuntime().wrap(function _callee8$(_context8) {
          while (1) switch (_context8.prev = _context8.next) {
            case 0:
              _context8.prev = 0;
              _context8.next = 3;
              return axios__WEBPACK_IMPORTED_MODULE_4__["default"].get("/nova-vendor/nap-manager/port/".concat(portId));
            case 3:
              response = _context8.sent;
              isEditMode.value = true;
              portForm.value = response.data;
              showPortForm.value = true;
              _context8.next = 13;
              break;
            case 9:
              _context8.prev = 9;
              _context8.t0 = _context8["catch"](0);
              console.error('Error fetching port details:', _context8.t0);
              toast.error('Error fetching port details');
            case 13:
            case "end":
              return _context8.stop();
          }
        }, _callee8, null, [[0, 9]]);
      }));
      return function openEditPortForm(_x5) {
        return _ref8.apply(this, arguments);
      };
    }();

    // Close port form
    var closePortForm = function closePortForm() {
      showPortForm.value = false;
      selectedPort.value = null;
    };

    // Submit port form
    var submitPortForm = /*#__PURE__*/function () {
      var _ref9 = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee9() {
        var _err$response3;
        return _regeneratorRuntime().wrap(function _callee9$(_context9) {
          while (1) switch (_context9.prev = _context9.next) {
            case 0:
              _context9.prev = 0;
              loading.value = true;
              if (!isEditMode.value) {
                _context9.next = 8;
                break;
              }
              _context9.next = 5;
              return axios__WEBPACK_IMPORTED_MODULE_4__["default"].put("/nova-vendor/nap-manager/port/".concat(portForm.value.id), portForm.value);
            case 5:
              toast.success('Port updated successfully');
              _context9.next = 11;
              break;
            case 8:
              _context9.next = 10;
              return axios__WEBPACK_IMPORTED_MODULE_4__["default"].post('/nova-vendor/nap-manager/port', portForm.value);
            case 10:
              toast.success('Port created successfully');
            case 11:
              if (!selectedNapBox.value) {
                _context9.next = 14;
                break;
              }
              _context9.next = 14;
              return fetchNapBoxDetails(selectedNapBox.value.id);
            case 14:
              closePortForm();
              _context9.next = 21;
              break;
            case 17:
              _context9.prev = 17;
              _context9.t0 = _context9["catch"](0);
              console.error('Error submitting port form:', _context9.t0);
              toast.error('Error saving port: ' + (((_err$response3 = _context9.t0.response) === null || _err$response3 === void 0 || (_err$response3 = _err$response3.data) === null || _err$response3 === void 0 ? void 0 : _err$response3.message) || _context9.t0.message));
            case 21:
              _context9.prev = 21;
              loading.value = false;
              return _context9.finish(21);
            case 24:
            case "end":
              return _context9.stop();
          }
        }, _callee9, null, [[0, 17, 21, 24]]);
      }));
      return function submitPortForm() {
        return _ref9.apply(this, arguments);
      };
    }();

    // Delete port
    var deletePort = /*#__PURE__*/function () {
      var _ref0 = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee0(portId) {
        var _err$response4;
        return _regeneratorRuntime().wrap(function _callee0$(_context0) {
          while (1) switch (_context0.prev = _context0.next) {
            case 0:
              if (confirm('Are you sure you want to delete this port?')) {
                _context0.next = 2;
                break;
              }
              return _context0.abrupt("return");
            case 2:
              _context0.prev = 2;
              loading.value = true;
              _context0.next = 6;
              return axios__WEBPACK_IMPORTED_MODULE_4__["default"]["delete"]("/nova-vendor/nap-manager/port/".concat(portId));
            case 6:
              toast.success('Port deleted successfully');

              // Refresh NAP box details
              if (!selectedNapBox.value) {
                _context0.next = 10;
                break;
              }
              _context0.next = 10;
              return fetchNapBoxDetails(selectedNapBox.value.id);
            case 10:
              _context0.next = 16;
              break;
            case 12:
              _context0.prev = 12;
              _context0.t0 = _context0["catch"](2);
              console.error('Error deleting port:', _context0.t0);
              toast.error('Error deleting port: ' + (((_err$response4 = _context0.t0.response) === null || _err$response4 === void 0 || (_err$response4 = _err$response4.data) === null || _err$response4 === void 0 ? void 0 : _err$response4.message) || _context0.t0.message));
            case 16:
              _context0.prev = 16;
              loading.value = false;
              return _context0.finish(16);
            case 19:
            case "end":
              return _context0.stop();
          }
        }, _callee0, null, [[2, 12, 16, 19]]);
      }));
      return function deletePort(_x6) {
        return _ref0.apply(this, arguments);
      };
    }();

    // Initialize on component mount
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(function () {
      fetchFlowData();
    });
    return {
      loading: loading,
      error: error,
      nodes: nodes,
      edges: edges,
      nodeTypes: nodeTypes,
      defaultEdgeOptions: defaultEdgeOptions,
      getNodeColor: getNodeColor,
      onNodeClick: onNodeClick,
      onNodeDragEnd: onNodeDragEnd,
      onEdgeConnect: onEdgeConnect,
      refreshFlow: fetchFlowData,
      // NAP box form
      showNapForm: showNapForm,
      napBoxForm: napBoxForm,
      isEditMode: isEditMode,
      openCreateNapForm: openCreateNapForm,
      openEditNapForm: openEditNapForm,
      closeNapForm: closeNapForm,
      submitNapForm: submitNapForm,
      deleteNapBox: deleteNapBox,
      // Port form
      showPortForm: showPortForm,
      portForm: portForm,
      openCreatePortForm: openCreatePortForm,
      openEditPortForm: openEditPortForm,
      closePortForm: closePortForm,
      submitPortForm: submitPortForm,
      deletePort: deletePort,
      // Selected items
      selectedNapBox: selectedNapBox,
      selectedPort: selectedPort
    };
  }
}));

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapMapComponent.vue?vue&type=script&lang=js":
/*!*********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapMapComponent.vue?vue&type=script&lang=js ***!
  \*********************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! axios */ "./node_modules/axios/lib/axios.js");
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return r; }; var t, r = {}, e = Object.prototype, n = e.hasOwnProperty, o = "function" == typeof Symbol ? Symbol : {}, i = o.iterator || "@@iterator", a = o.asyncIterator || "@@asyncIterator", u = o.toStringTag || "@@toStringTag"; function c(t, r, e, n) { return Object.defineProperty(t, r, { value: e, enumerable: !n, configurable: !n, writable: !n }); } try { c({}, ""); } catch (t) { c = function c(t, r, e) { return t[r] = e; }; } function h(r, e, n, o) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype); return c(a, "_invoke", function (r, e, n) { var o = 1; return function (i, a) { if (3 === o) throw Error("Generator is already running"); if (4 === o) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var u = n.delegate; if (u) { var c = d(u, n); if (c) { if (c === f) continue; return c; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (1 === o) throw o = 4, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = 3; var h = s(r, e, n); if ("normal" === h.type) { if (o = n.done ? 4 : 2, h.arg === f) continue; return { value: h.arg, done: n.done }; } "throw" === h.type && (o = 4, n.method = "throw", n.arg = h.arg); } }; }(r, n, new Context(o || [])), !0), a; } function s(t, r, e) { try { return { type: "normal", arg: t.call(r, e) }; } catch (t) { return { type: "throw", arg: t }; } } r.wrap = h; var f = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var l = {}; c(l, i, function () { return this; }); var p = Object.getPrototypeOf, y = p && p(p(x([]))); y && y !== e && n.call(y, i) && (l = y); var v = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(l); function g(t) { ["next", "throw", "return"].forEach(function (r) { c(t, r, function (t) { return this._invoke(r, t); }); }); } function AsyncIterator(t, r) { function e(o, i, a, u) { var c = s(t[o], t, i); if ("throw" !== c.type) { var h = c.arg, f = h.value; return f && "object" == _typeof(f) && n.call(f, "__await") ? r.resolve(f.__await).then(function (t) { e("next", t, a, u); }, function (t) { e("throw", t, a, u); }) : r.resolve(f).then(function (t) { h.value = t, a(h); }, function (t) { return e("throw", t, a, u); }); } u(c.arg); } var o; c(this, "_invoke", function (t, n) { function i() { return new r(function (r, o) { e(t, n, r, o); }); } return o = o ? o.then(i, i) : i(); }, !0); } function d(r, e) { var n = e.method, o = r.i[n]; if (o === t) return e.delegate = null, "throw" === n && r.i["return"] && (e.method = "return", e.arg = t, d(r, e), "throw" === e.method) || "return" !== n && (e.method = "throw", e.arg = new TypeError("The iterator does not provide a '" + n + "' method")), f; var i = s(o, r.i, e.arg); if ("throw" === i.type) return e.method = "throw", e.arg = i.arg, e.delegate = null, f; var a = i.arg; return a ? a.done ? (e[r.r] = a.value, e.next = r.n, "return" !== e.method && (e.method = "next", e.arg = t), e.delegate = null, f) : a : (e.method = "throw", e.arg = new TypeError("iterator result is not an object"), e.delegate = null, f); } function w(t) { this.tryEntries.push(t); } function m(r) { var e = r[4] || {}; e.type = "normal", e.arg = t, r[4] = e; } function Context(t) { this.tryEntries = [[-1]], t.forEach(w, this), this.reset(!0); } function x(r) { if (null != r) { var e = r[i]; if (e) return e.call(r); if ("function" == typeof r.next) return r; if (!isNaN(r.length)) { var o = -1, a = function e() { for (; ++o < r.length;) if (n.call(r, o)) return e.value = r[o], e.done = !1, e; return e.value = t, e.done = !0, e; }; return a.next = a; } } throw new TypeError(_typeof(r) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, c(v, "constructor", GeneratorFunctionPrototype), c(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = c(GeneratorFunctionPrototype, u, "GeneratorFunction"), r.isGeneratorFunction = function (t) { var r = "function" == typeof t && t.constructor; return !!r && (r === GeneratorFunction || "GeneratorFunction" === (r.displayName || r.name)); }, r.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, c(t, u, "GeneratorFunction")), t.prototype = Object.create(v), t; }, r.awrap = function (t) { return { __await: t }; }, g(AsyncIterator.prototype), c(AsyncIterator.prototype, a, function () { return this; }), r.AsyncIterator = AsyncIterator, r.async = function (t, e, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(h(t, e, n, o), i); return r.isGeneratorFunction(e) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, g(v), c(v, u, "Generator"), c(v, i, function () { return this; }), c(v, "toString", function () { return "[object Generator]"; }), r.keys = function (t) { var r = Object(t), e = []; for (var n in r) e.unshift(n); return function t() { for (; e.length;) if ((n = e.pop()) in r) return t.value = n, t.done = !1, t; return t.done = !0, t; }; }, r.values = x, Context.prototype = { constructor: Context, reset: function reset(r) { if (this.prev = this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(m), !r) for (var e in this) "t" === e.charAt(0) && n.call(this, e) && !isNaN(+e.slice(1)) && (this[e] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0][4]; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(r) { if (this.done) throw r; var e = this; function n(t) { a.type = "throw", a.arg = r, e.next = t; } for (var o = e.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i[4], u = this.prev, c = i[1], h = i[2]; if (-1 === i[0]) return n("end"), !1; if (!c && !h) throw Error("try statement without catch or finally"); if (null != i[0] && i[0] <= u) { if (u < c) return this.method = "next", this.arg = t, n(c), !0; if (u < h) return n(h), !1; } } }, abrupt: function abrupt(t, r) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var n = this.tryEntries[e]; if (n[0] > -1 && n[0] <= this.prev && this.prev < n[2]) { var o = n; break; } } o && ("break" === t || "continue" === t) && o[0] <= r && r <= o[2] && (o = null); var i = o ? o[4] : {}; return i.type = t, i.arg = r, o ? (this.method = "next", this.next = o[2], f) : this.complete(i); }, complete: function complete(t, r) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && r && (this.next = r), f; }, finish: function finish(t) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var e = this.tryEntries[r]; if (e[2] === t) return this.complete(e[4], e[3]), m(e), f; } }, "catch": function _catch(t) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var e = this.tryEntries[r]; if (e[0] === t) { var n = e[4]; if ("throw" === n.type) { var o = n.arg; m(e); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(r, e, n) { return this.delegate = { i: x(r), r: e, n: n }, "next" === this.method && (this.arg = t), f; } }, r; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "NapMapComponent",
  props: {
    googleMapsApiKey: {
      type: String,
      required: true
    }
  },
  setup: function setup(props) {
    var mapLoaded = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(false);
    var mapInstance = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);
    var markers = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)([]);
    var napBoxes = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)([]);
    var loading = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(true);
    var error = (0,vue__WEBPACK_IMPORTED_MODULE_0__.ref)(null);

    // Load Google Maps API
    var loadGoogleMapsApi = function loadGoogleMapsApi() {
      return new Promise(function (resolve, reject) {
        if (window.google && window.google.maps) {
          resolve(window.google.maps);
          return;
        }
        var script = document.createElement('script');
        script.src = "https://maps.googleapis.com/maps/api/js?key=".concat(props.googleMapsApiKey, "&libraries=places");
        script.async = true;
        script.defer = true;
        script.onload = function () {
          mapLoaded.value = true;
          resolve(window.google.maps);
        };
        script.onerror = function (error) {
          reject(error);
        };
        document.head.appendChild(script);
      });
    };

    // Initialize map
    var initMap = /*#__PURE__*/function () {
      var _ref = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
        var maps;
        return _regeneratorRuntime().wrap(function _callee$(_context) {
          while (1) switch (_context.prev = _context.next) {
            case 0:
              _context.prev = 0;
              _context.next = 3;
              return loadGoogleMapsApi();
            case 3:
              maps = _context.sent;
              // Create map instance
              mapInstance.value = new maps.Map(document.getElementById('map-container'), {
                center: {
                  lat: 4.6097,
                  lng: -74.0817
                },
                // Default to Bogotá, Colombia
                zoom: 12,
                mapTypeId: maps.MapTypeId.ROADMAP,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true
              });

              // Load NAP boxes data
              _context.next = 7;
              return fetchNapBoxes();
            case 7:
              _context.next = 13;
              break;
            case 9:
              _context.prev = 9;
              _context.t0 = _context["catch"](0);
              error.value = 'Error loading Google Maps: ' + _context.t0.message;
              console.error('Error loading Google Maps:', _context.t0);
            case 13:
            case "end":
              return _context.stop();
          }
        }, _callee, null, [[0, 9]]);
      }));
      return function initMap() {
        return _ref.apply(this, arguments);
      };
    }();

    // Fetch NAP boxes from API
    var fetchNapBoxes = /*#__PURE__*/function () {
      var _ref2 = _asyncToGenerator(/*#__PURE__*/_regeneratorRuntime().mark(function _callee2() {
        var response;
        return _regeneratorRuntime().wrap(function _callee2$(_context2) {
          while (1) switch (_context2.prev = _context2.next) {
            case 0:
              _context2.prev = 0;
              loading.value = true;
              _context2.next = 4;
              return axios__WEBPACK_IMPORTED_MODULE_1__["default"].get('/nova-vendor/nap-manager/map-data');
            case 4:
              response = _context2.sent;
              napBoxes.value = response.data;

              // Add markers for each NAP box
              addMarkers();
              loading.value = false;
              _context2.next = 15;
              break;
            case 10:
              _context2.prev = 10;
              _context2.t0 = _context2["catch"](0);
              error.value = 'Error fetching NAP boxes: ' + _context2.t0.message;
              console.error('Error fetching NAP boxes:', _context2.t0);
              loading.value = false;
            case 15:
            case "end":
              return _context2.stop();
          }
        }, _callee2, null, [[0, 10]]);
      }));
      return function fetchNapBoxes() {
        return _ref2.apply(this, arguments);
      };
    }();

    // Add markers to the map
    var addMarkers = function addMarkers() {
      if (!mapInstance.value || !napBoxes.value.length) return;
      var bounds = new google.maps.LatLngBounds();

      // Clear existing markers
      markers.value.forEach(function (marker) {
        return marker.setMap(null);
      });
      markers.value = [];

      // Add new markers
      napBoxes.value.forEach(function (nap) {
        var position = {
          lat: nap.lat,
          lng: nap.lng
        };

        // Skip invalid coordinates
        if (!position.lat || !position.lng) return;

        // Create marker
        var marker = new google.maps.Marker({
          position: position,
          map: mapInstance.value,
          title: nap.name,
          icon: getMarkerIcon(nap.status, nap.occupancy)
        });

        // Create info window
        var infoWindow = new google.maps.InfoWindow({
          content: "\n            <div class=\"info-window\">\n              <h3>".concat(nap.name, " (").concat(nap.code, ")</h3>\n              <p><strong>Status:</strong> ").concat(nap.status, "</p>\n              <p><strong>Occupancy:</strong> ").concat(nap.occupancy, "%</p>\n              <p><strong>Available Ports:</strong> ").concat(nap.available_ports, "/").concat(nap.total_capacity, "</p>\n              <p><strong>Address:</strong> ").concat(nap.address, "</p>\n            </div>\n          ")
        });

        // Add click event to marker
        marker.addListener('click', function () {
          infoWindow.open(mapInstance.value, marker);
        });

        // Add marker to array
        markers.value.push(marker);

        // Extend bounds
        bounds.extend(position);
      });

      // Fit map to bounds if we have markers
      if (markers.value.length > 0) {
        mapInstance.value.fitBounds(bounds);
      }
    };

    // Get marker icon based on status and occupancy
    var getMarkerIcon = function getMarkerIcon(status, occupancy) {
      var color = '#4CAF50'; // Green for active

      if (status === 'inactive') {
        color = '#9E9E9E'; // Gray for inactive
      } else if (status === 'maintenance') {
        color = '#FFC107'; // Yellow for maintenance
      } else if (status === 'damaged') {
        color = '#F44336'; // Red for damaged
      } else if (occupancy > 90) {
        color = '#FF9800'; // Orange for high occupancy
      }
      return {
        path: google.maps.SymbolPath.CIRCLE,
        fillColor: color,
        fillOpacity: 0.9,
        strokeWeight: 1,
        strokeColor: '#FFFFFF',
        scale: 10
      };
    };

    // Initialize map on component mount
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.onMounted)(function () {
      initMap();
    });
    return {
      mapLoaded: mapLoaded,
      loading: loading,
      error: error,
      napBoxes: napBoxes,
      refreshMap: fetchNapBoxes
    };
  }
}));

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Tool.vue?vue&type=script&lang=js":
/*!*****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Tool.vue?vue&type=script&lang=js ***!
  \*****************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_NapMapComponent_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../components/NapMapComponent.vue */ "./resources/js/components/NapMapComponent.vue");
/* harmony import */ var _components_NapFlowComponent_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../components/NapFlowComponent.vue */ "./resources/js/components/NapFlowComponent.vue");
/* harmony import */ var _headlessui_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @headlessui/vue */ "./node_modules/@headlessui/vue/dist/components/tabs/tabs.js");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    NapMapComponent: _components_NapMapComponent_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    NapFlowComponent: _components_NapFlowComponent_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
    TabGroup: _headlessui_vue__WEBPACK_IMPORTED_MODULE_2__.TabGroup,
    TabList: _headlessui_vue__WEBPACK_IMPORTED_MODULE_2__.TabList,
    Tab: _headlessui_vue__WEBPACK_IMPORTED_MODULE_2__.Tab,
    TabPanels: _headlessui_vue__WEBPACK_IMPORTED_MODULE_2__.TabPanels,
    TabPanel: _headlessui_vue__WEBPACK_IMPORTED_MODULE_2__.TabPanel
  },
  props: {
    GOOGLE_MAPS_API_KEY: {
      type: String,
      "default": ''
    }
  },
  data: function data() {
    return {
      activeTab: 0
    };
  },
  methods: {
    handleTabChange: function handleTabChange(index) {
      this.activeTab = index;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapFlowComponent.vue?vue&type=template&id=778e46bd&scoped=true":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapFlowComponent.vue?vue&type=template&id=778e46bd&scoped=true ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);

var _hoisted_1 = {
  "class": "nap-flow-container"
};
var _hoisted_2 = {
  "class": "flow-controls nap-mb-4"
};
var _hoisted_3 = {
  "class": "nap-flex nap-justify-between nap-items-center"
};
var _hoisted_4 = {
  "class": "nap-flex nap-space-x-2"
};
var _hoisted_5 = ["disabled"];
var _hoisted_6 = ["disabled"];
var _hoisted_7 = {
  key: 0
};
var _hoisted_8 = {
  key: 1
};
var _hoisted_9 = {
  key: 0,
  "class": "nap-fixed nap-inset-0 nap-bg-black nap-bg-opacity-50 nap-flex nap-items-center nap-justify-center nap-z-50"
};
var _hoisted_10 = {
  "class": "nap-bg-white nap-rounded-lg nap-p-6 nap-w-full nap-max-w-2xl nap-max-h-screen nap-overflow-y-auto"
};
var _hoisted_11 = {
  "class": "nap-flex nap-justify-between nap-items-center nap-mb-4"
};
var _hoisted_12 = {
  "class": "nap-text-xl nap-font-bold"
};
var _hoisted_13 = {
  "class": "nap-grid nap-grid-cols-1 md:nap-grid-cols-2 nap-gap-4"
};
var _hoisted_14 = {
  "class": "md:nap-col-span-2"
};
var _hoisted_15 = {
  "class": "flex justify-end space-x-2 pt-4"
};
var _hoisted_16 = ["disabled"];
var _hoisted_17 = {
  key: 1,
  "class": "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
};
var _hoisted_18 = {
  "class": "bg-white rounded-lg p-6 w-full max-w-lg max-h-screen overflow-y-auto"
};
var _hoisted_19 = {
  "class": "flex justify-between items-center mb-4"
};
var _hoisted_20 = {
  "class": "text-xl font-bold"
};
var _hoisted_21 = {
  "class": "grid grid-cols-1 md:grid-cols-2 gap-4"
};
var _hoisted_22 = {
  "class": "md:col-span-2"
};
var _hoisted_23 = {
  "class": "flex justify-end space-x-2 pt-4"
};
var _hoisted_24 = ["disabled"];
var _hoisted_25 = {
  key: 2,
  "class": "bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"
};
var _hoisted_26 = {
  "class": "flex flex-col md:flex-row gap-4"
};
var _hoisted_27 = {
  "class": "flex-grow"
};
var _hoisted_28 = {
  key: 0,
  "class": "flex justify-center items-center",
  style: {
    "height": "400px"
  }
};
var _hoisted_29 = {
  key: 1,
  "class": "flow-container"
};
var _hoisted_30 = {
  key: 0,
  "class": "w-full md:w-96 bg-white p-4 rounded-lg shadow-md"
};
var _hoisted_31 = {
  "class": "flex justify-between items-center mb-4"
};
var _hoisted_32 = {
  "class": "flex space-x-2"
};
var _hoisted_33 = {
  "class": "mb-4"
};
var _hoisted_34 = {
  "class": "grid grid-cols-2 gap-2"
};
var _hoisted_35 = {
  "class": "text-sm"
};
var _hoisted_36 = {
  "class": "text-sm"
};
var _hoisted_37 = {
  "class": "text-sm"
};
var _hoisted_38 = {
  "class": "text-sm"
};
var _hoisted_39 = {
  "class": "text-sm"
};
var _hoisted_40 = {
  "class": "text-sm"
};
var _hoisted_41 = {
  "class": "flex justify-between items-center mb-2"
};
var _hoisted_42 = {
  key: 0,
  "class": "space-y-2"
};
var _hoisted_43 = {
  "class": "flex justify-between items-center"
};
var _hoisted_44 = {
  "class": "font-medium"
};
var _hoisted_45 = {
  key: 0,
  "class": "text-sm text-gray-500 ml-1"
};
var _hoisted_46 = {
  "class": "flex space-x-1"
};
var _hoisted_47 = ["onClick"];
var _hoisted_48 = ["onClick"];
var _hoisted_49 = {
  "class": "grid grid-cols-2 gap-1 mt-1 text-xs"
};
var _hoisted_50 = {
  key: 1,
  "class": "text-center py-4 text-gray-500"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Background = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Background");
  var _component_MiniMap = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("MiniMap");
  var _component_Controls = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Controls");
  var _component_Panel = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Panel");
  var _component_VueFlow = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("VueFlow");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [_cache[31] || (_cache[31] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", {
    "class": "nap-text-xl nap-font-bold"
  }, "NAP Distribution Flow", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    onClick: _cache[0] || (_cache[0] = function () {
      return _ctx.openCreateNapForm && _ctx.openCreateNapForm.apply(_ctx, arguments);
    }),
    "class": "btn btn-default btn-success",
    disabled: _ctx.loading
  }, _cache[30] || (_cache[30] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, "Add NAP Box", -1 /* HOISTED */)]), 8 /* PROPS */, _hoisted_5), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    onClick: _cache[1] || (_cache[1] = function () {
      return _ctx.refreshFlow && _ctx.refreshFlow.apply(_ctx, arguments);
    }),
    "class": "btn btn-default btn-primary",
    disabled: _ctx.loading
  }, [_ctx.loading ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_7, "Loading...")) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_8, "Refresh Flow"))], 8 /* PROPS */, _hoisted_6)])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" NAP Box Form Modal "), _ctx.showNapForm ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_10, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_11, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", _hoisted_12, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.isEditMode ? 'Edit NAP Box' : 'Create NAP Box'), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    onClick: _cache[2] || (_cache[2] = function () {
      return _ctx.closeNapForm && _ctx.closeNapForm.apply(_ctx, arguments);
    }),
    "class": "nap-text-gray-500 hover:nap-text-gray-700"
  }, _cache[32] || (_cache[32] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
    "class": "nap-text-2xl"
  }, "×", -1 /* HOISTED */)]))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
    onSubmit: _cache[15] || (_cache[15] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.withModifiers)(function () {
      return _ctx.submitNapForm && _ctx.submitNapForm.apply(_ctx, arguments);
    }, ["prevent"])),
    "class": "nap-space-y-4"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_13, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Name "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[33] || (_cache[33] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "nap-block nap-text-sm nap-font-medium nap-text-gray-700"
  }, "Name", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "text",
    "onUpdate:modelValue": _cache[3] || (_cache[3] = function ($event) {
      return _ctx.napBoxForm.name = $event;
    }),
    required: "",
    "class": "nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, _ctx.napBoxForm.name]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Code "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[34] || (_cache[34] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "nap-block nap-text-sm nap-font-medium nap-text-gray-700"
  }, "Code", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "text",
    "onUpdate:modelValue": _cache[4] || (_cache[4] = function ($event) {
      return _ctx.napBoxForm.code = $event;
    }),
    required: "",
    "class": "nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, _ctx.napBoxForm.code]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Address "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_14, [_cache[35] || (_cache[35] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "nap-block nap-text-sm nap-font-medium nap-text-gray-700"
  }, "Address", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("textarea", {
    "onUpdate:modelValue": _cache[5] || (_cache[5] = function ($event) {
      return _ctx.napBoxForm.address = $event;
    }),
    required: "",
    "class": "nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none",
    rows: "2"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, _ctx.napBoxForm.address]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Latitude "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[36] || (_cache[36] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "nap-block nap-text-sm nap-font-medium nap-text-gray-700"
  }, "Latitude", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "number",
    "onUpdate:modelValue": _cache[6] || (_cache[6] = function ($event) {
      return _ctx.napBoxForm.latitude = $event;
    }),
    step: "0.00000001",
    required: "",
    "class": "nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, _ctx.napBoxForm.latitude]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Longitude "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[37] || (_cache[37] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "nap-block nap-text-sm nap-font-medium nap-text-gray-700"
  }, "Longitude", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "number",
    "onUpdate:modelValue": _cache[7] || (_cache[7] = function ($event) {
      return _ctx.napBoxForm.longitude = $event;
    }),
    step: "0.00000001",
    required: "",
    "class": "nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, _ctx.napBoxForm.longitude]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Status "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[39] || (_cache[39] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "block text-sm font-medium text-gray-700"
  }, "Status", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
    "onUpdate:modelValue": _cache[8] || (_cache[8] = function ($event) {
      return _ctx.napBoxForm.status = $event;
    }),
    required: "",
    "class": "nap-bg-gray-50 nap-border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9]"
  }, _cache[38] || (_cache[38] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
    value: "active"
  }, "Active", -1 /* HOISTED */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
    value: "inactive"
  }, "Inactive", -1 /* HOISTED */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
    value: "maintenance"
  }, "Maintenance", -1 /* HOISTED */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
    value: "damaged"
  }, "Damaged", -1 /* HOISTED */)]), 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelSelect, _ctx.napBoxForm.status]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Capacity "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[40] || (_cache[40] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "block text-sm font-medium text-gray-700"
  }, "Capacity", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "number",
    "onUpdate:modelValue": _cache[9] || (_cache[9] = function ($event) {
      return _ctx.napBoxForm.capacity = $event;
    }),
    min: "1",
    required: "",
    "class": "nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, _ctx.napBoxForm.capacity]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Technology Type "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[42] || (_cache[42] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "block text-sm font-medium text-gray-700"
  }, "Technology Type", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
    "onUpdate:modelValue": _cache[10] || (_cache[10] = function ($event) {
      return _ctx.napBoxForm.technology_type = $event;
    }),
    required: "",
    "class": "nap-bg-gray-50 nap-border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] nap-outline-none"
  }, _cache[41] || (_cache[41] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
    value: "fiber"
  }, "Fiber", -1 /* HOISTED */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
    value: "coaxial"
  }, "Coaxial", -1 /* HOISTED */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
    value: "ftth"
  }, "FTTH", -1 /* HOISTED */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
    value: "mixed"
  }, "Mixed", -1 /* HOISTED */)]), 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelSelect, _ctx.napBoxForm.technology_type]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Installation Date "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[43] || (_cache[43] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "block text-sm font-medium text-gray-700"
  }, "Installation Date", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "date",
    "onUpdate:modelValue": _cache[11] || (_cache[11] = function ($event) {
      return _ctx.napBoxForm.installation_date = $event;
    }),
    required: "",
    "class": "nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, _ctx.napBoxForm.installation_date]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Brand "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[44] || (_cache[44] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "block text-sm font-medium text-gray-700"
  }, "Brand", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "text",
    "onUpdate:modelValue": _cache[12] || (_cache[12] = function ($event) {
      return _ctx.napBoxForm.brand = $event;
    }),
    "class": "nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, _ctx.napBoxForm.brand]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Model "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[45] || (_cache[45] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "block text-sm font-medium text-gray-700"
  }, "Model", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "text",
    "onUpdate:modelValue": _cache[13] || (_cache[13] = function ($event) {
      return _ctx.napBoxForm.model = $event;
    }),
    "class": "nap-shadow-xs nap-bg-gray-50 border nap-border-gray-300 nap-text-gray-900 nap-text-sm nap-rounded-lg focus:nap-ring-[#0ea5e9] focus:nap-border-[#0ea5e9] nap-block nap-w-full nap-p-2.5 dark:nap-bg-gray-700 dark:nap-border-gray-600 dark:nap-placeholder-gray-400 dark:nap-text-white dark:focus:nap-ring-[#0ea5e9] dark:focus:nap-border-[#0ea5e9] dark:nap-shadow-xs-light nap-outline-none"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, _ctx.napBoxForm.model]])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_15, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    type: "button",
    onClick: _cache[14] || (_cache[14] = function () {
      return _ctx.closeNapForm && _ctx.closeNapForm.apply(_ctx, arguments);
    }),
    "class": "nap-px-4 nap-py-2 nap-bg-gray-200 nap-text-gray-800 nap-rounded-md hover:nap-bg-gray-300"
  }, " Cancel "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    type: "submit",
    "class": "px-4 py-2 nap-bg-blue-600 nap-text-white rounded-md hover:nap-bg-blue-700",
    disabled: _ctx.loading
  }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.loading ? 'Saving...' : _ctx.isEditMode ? 'Update' : 'Create'), 9 /* TEXT, PROPS */, _hoisted_16)])], 32 /* NEED_HYDRATION */)])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Port Form Modal "), _ctx.showPortForm ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_17, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_18, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_19, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", _hoisted_20, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.isEditMode ? 'Edit Port' : 'Create Port'), 1 /* TEXT */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    onClick: _cache[16] || (_cache[16] = function () {
      return _ctx.closePortForm && _ctx.closePortForm.apply(_ctx, arguments);
    }),
    "class": "text-gray-500 hover:text-gray-700"
  }, _cache[46] || (_cache[46] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
    "class": "text-2xl"
  }, "×", -1 /* HOISTED */)]))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("form", {
    onSubmit: _cache[23] || (_cache[23] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.withModifiers)(function () {
      return _ctx.submitPortForm && _ctx.submitPortForm.apply(_ctx, arguments);
    }, ["prevent"])),
    "class": "space-y-4"
  }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_21, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Port Number "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[47] || (_cache[47] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "block text-sm font-medium text-gray-700"
  }, "Port Number", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "number",
    "onUpdate:modelValue": _cache[17] || (_cache[17] = function ($event) {
      return _ctx.portForm.port_number = $event;
    }),
    min: "1",
    required: "",
    "class": "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, _ctx.portForm.port_number]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Port Name "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[48] || (_cache[48] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "block text-sm font-medium text-gray-700"
  }, "Port Name (Optional)", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("input", {
    type: "text",
    "onUpdate:modelValue": _cache[18] || (_cache[18] = function ($event) {
      return _ctx.portForm.port_name = $event;
    }),
    "class": "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, _ctx.portForm.port_name]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Status "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[50] || (_cache[50] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "block text-sm font-medium text-gray-700"
  }, "Status", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
    "onUpdate:modelValue": _cache[19] || (_cache[19] = function ($event) {
      return _ctx.portForm.status = $event;
    }),
    required: "",
    "class": "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
  }, _cache[49] || (_cache[49] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createStaticVNode)("<option value=\"available\" data-v-778e46bd>Available</option><option value=\"occupied\" data-v-778e46bd>Occupied</option><option value=\"damaged\" data-v-778e46bd>Damaged</option><option value=\"maintenance\" data-v-778e46bd>Maintenance</option><option value=\"reserved\" data-v-778e46bd>Reserved</option><option value=\"testing\" data-v-778e46bd>Testing</option>", 6)]), 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelSelect, _ctx.portForm.status]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Connection Type "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [_cache[52] || (_cache[52] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "block text-sm font-medium text-gray-700"
  }, "Connection Type", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("select", {
    "onUpdate:modelValue": _cache[20] || (_cache[20] = function ($event) {
      return _ctx.portForm.connection_type = $event;
    }),
    required: "",
    "class": "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
  }, _cache[51] || (_cache[51] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
    value: "fiber"
  }, "Fiber", -1 /* HOISTED */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
    value: "coaxial"
  }, "Coaxial", -1 /* HOISTED */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
    value: "ethernet"
  }, "Ethernet", -1 /* HOISTED */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("option", {
    value: "mixed"
  }, "Mixed", -1 /* HOISTED */)]), 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelSelect, _ctx.portForm.connection_type]])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Technician Notes "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_22, [_cache[53] || (_cache[53] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("label", {
    "class": "block text-sm font-medium text-gray-700"
  }, "Technician Notes", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.withDirectives)((0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("textarea", {
    "onUpdate:modelValue": _cache[21] || (_cache[21] = function ($event) {
      return _ctx.portForm.technician_notes = $event;
    }),
    "class": "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500",
    rows: "3"
  }, null, 512 /* NEED_PATCH */), [[vue__WEBPACK_IMPORTED_MODULE_0__.vModelText, _ctx.portForm.technician_notes]])])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_23, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    type: "button",
    onClick: _cache[22] || (_cache[22] = function () {
      return _ctx.closePortForm && _ctx.closePortForm.apply(_ctx, arguments);
    }),
    "class": "px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
  }, " Cancel "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    type: "submit",
    "class": "px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700",
    disabled: _ctx.loading
  }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.loading ? 'Saving...' : _ctx.isEditMode ? 'Update' : 'Create'), 9 /* TEXT, PROPS */, _hoisted_24)])], 32 /* NEED_HYDRATION */)])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), _ctx.error ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_25, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.error), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_26, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Flow diagram "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_27, [_ctx.loading && !_ctx.error ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_28, _cache[54] || (_cache[54] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "spinner"
  }, null, -1 /* HOISTED */), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
    "class": "ml-2"
  }, "Loading flow data...", -1 /* HOISTED */)]))) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_29, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_VueFlow, {
    modelValue: _ctx.nodes,
    "onUpdate:modelValue": _cache[25] || (_cache[25] = function ($event) {
      return _ctx.nodes = $event;
    }),
    edges: _ctx.edges,
    "onUpdate:edges": _cache[26] || (_cache[26] = function ($event) {
      return _ctx.edges = $event;
    }),
    "node-types": _ctx.nodeTypes,
    "default-edge-options": _ctx.defaultEdgeOptions,
    "fit-view-on-init": "",
    "min-zoom": 0.2,
    "max-zoom": 4,
    onNodeclick: _ctx.onNodeClick,
    onNodedragstop: _ctx.onNodeDragEnd,
    onConnect: _ctx.onEdgeConnect
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Background, {
        pattern: "dots",
        size: 1.5,
        gap: 20
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_MiniMap, {
        "node-color": _ctx.getNodeColor
      }, null, 8 /* PROPS */, ["node-color"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Controls), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Panel, {
        position: "top-right",
        "class": "custom-controls"
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
            onClick: _cache[24] || (_cache[24] = function () {
              return _ctx.refreshFlow && _ctx.refreshFlow.apply(_ctx, arguments);
            }),
            "class": "btn-refresh"
          }, _cache[55] || (_cache[55] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", null, "Refresh", -1 /* HOISTED */)]))];
        }),
        _: 1 /* STABLE */
      })];
    }),
    _: 1 /* STABLE */
  }, 8 /* PROPS */, ["modelValue", "edges", "node-types", "default-edge-options", "onNodeclick", "onNodedragstop", "onConnect"])]))]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" NAP Box Details Panel "), _ctx.selectedNapBox ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_30, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_31, [_cache[56] || (_cache[56] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", {
    "class": "text-lg font-bold"
  }, "NAP Box Details", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_32, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    onClick: _cache[27] || (_cache[27] = function ($event) {
      return _ctx.openEditNapForm(_ctx.selectedNapBox);
    }),
    "class": "px-2 py-1 bg-[#0ea5e9] text-white rounded-md hover:bg-blue-600 text-sm"
  }, " Edit "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    onClick: _cache[28] || (_cache[28] = function ($event) {
      return _ctx.deleteNapBox(_ctx.selectedNapBox.id);
    }),
    "class": "px-2 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm"
  }, " Delete ")])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_33, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_34, [_cache[57] || (_cache[57] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "text-sm font-medium text-gray-500"
  }, "Name:", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_35, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.selectedNapBox.name), 1 /* TEXT */), _cache[58] || (_cache[58] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "text-sm font-medium text-gray-500"
  }, "Code:", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_36, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.selectedNapBox.code), 1 /* TEXT */), _cache[59] || (_cache[59] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "text-sm font-medium text-gray-500"
  }, "Status:", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_37, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
    "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
      'text-green-600': _ctx.selectedNapBox.status === 'active',
      'text-gray-600': _ctx.selectedNapBox.status === 'inactive',
      'text-yellow-600': _ctx.selectedNapBox.status === 'maintenance',
      'text-red-600': _ctx.selectedNapBox.status === 'damaged'
    })
  }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.selectedNapBox.status), 3 /* TEXT, CLASS */)]), _cache[60] || (_cache[60] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "text-sm font-medium text-gray-500"
  }, "Capacity:", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_38, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.selectedNapBox.capacity), 1 /* TEXT */), _cache[61] || (_cache[61] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "text-sm font-medium text-gray-500"
  }, "Technology:", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_39, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.selectedNapBox.technology_type), 1 /* TEXT */), _cache[62] || (_cache[62] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "text-sm font-medium text-gray-500"
  }, "Installation Date:", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_40, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.selectedNapBox.installation_date), 1 /* TEXT */)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)(" Ports Section "), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_41, [_cache[63] || (_cache[63] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h4", {
    "class": "text-md font-bold"
  }, "Ports", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    onClick: _cache[29] || (_cache[29] = function ($event) {
      return _ctx.openCreatePortForm(_ctx.selectedNapBox.id);
    }),
    "class": "px-2 py-1 bg-green-500 text-white rounded-md hover:bg-green-600 text-sm"
  }, " Add Port ")]), _ctx.selectedNapBox.ports && _ctx.selectedNapBox.ports.length > 0 ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_42, [((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)(_ctx.selectedNapBox.ports, function (port) {
    return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
      key: port.id,
      "class": "border rounded-md p-2"
    }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_43, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", _hoisted_44, "Port " + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(port.port_number), 1 /* TEXT */), port.port_name ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_45, "(" + (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(port.port_name) + ")", 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_46, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
      onClick: function onClick($event) {
        return _ctx.openEditPortForm(port.id);
      },
      "class": "px-1.5 py-0.5 bg-[#0ea5e9] text-white rounded hover:bg-blue-600 text-xs"
    }, " Edit ", 8 /* PROPS */, _hoisted_47), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
      onClick: function onClick($event) {
        return _ctx.deletePort(port.id);
      },
      "class": "px-1.5 py-0.5 bg-red-500 text-white rounded hover:bg-red-600 text-xs"
    }, " Delete ", 8 /* PROPS */, _hoisted_48)])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_49, [_cache[64] || (_cache[64] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
      "class": "text-gray-500"
    }, "Status:", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
      "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)({
        'text-green-600': port.status === 'available',
        'text-blue-600': port.status === 'occupied',
        'text-yellow-600': port.status === 'maintenance' || port.status === 'testing',
        'text-red-600': port.status === 'damaged',
        'text-purple-600': port.status === 'reserved'
      })
    }, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(port.status), 3 /* TEXT, CLASS */)]), _cache[65] || (_cache[65] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
      "class": "text-gray-500"
    }, "Connection:", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(port.connection_type), 1 /* TEXT */)])]);
  }), 128 /* KEYED_FRAGMENT */))])) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_50, " No ports added yet. Click \"Add Port\" to create one. "))])])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)])]);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapMapComponent.vue?vue&type=template&id=1fa29623&scoped=true":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapMapComponent.vue?vue&type=template&id=1fa29623&scoped=true ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);

var _hoisted_1 = {
  "class": "nap-map-container"
};
var _hoisted_2 = {
  "class": "map-controls nap-mb-4"
};
var _hoisted_3 = {
  "class": "nap-flex nap-justify-between nap-items-center"
};
var _hoisted_4 = ["disabled"];
var _hoisted_5 = {
  key: 0
};
var _hoisted_6 = {
  key: 1
};
var _hoisted_7 = {
  key: 0,
  "class": "nap-bg-red-100 nap-border nap-border-red-400 nap-text-red-700 nap-px-4 nap-py-3 nap-rounded nap-mb-4"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [_cache[1] || (_cache[1] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h3", {
    "class": "nap-text-xl nap-font-bold"
  }, "NAP Boxes Map", -1 /* HOISTED */)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
    onClick: _cache[0] || (_cache[0] = function () {
      return _ctx.refreshMap && _ctx.refreshMap.apply(_ctx, arguments);
    }),
    "class": "btn btn-default btn-primary",
    disabled: _ctx.loading
  }, [_ctx.loading ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_5, "Loading...")) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("span", _hoisted_6, "Refresh Map"))], 8 /* PROPS */, _hoisted_4)])]), _ctx.error ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_7, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.error), 1 /* TEXT */)) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true), _cache[2] || (_cache[2] = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    id: "map-container",
    "class": "map-container"
  }, null, -1 /* HOISTED */))]);
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Tool.vue?vue&type=template&id=ef10eebe":
/*!*********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Tool.vue?vue&type=template&id=ef10eebe ***!
  \*********************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);

var _hoisted_1 = {
  "class": "nap-manager-wrapper"
};
var _hoisted_2 = {
  "class": "nap-mb-6"
};
var _hoisted_3 = {
  "class": "nap-h-full"
};
var _hoisted_4 = {
  "class": "nap-h-full"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Head = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Head");
  var _component_Heading = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Heading");
  var _component_Tab = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Tab");
  var _component_TabList = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("TabList");
  var _component_NapMapComponent = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("NapMapComponent");
  var _component_Card = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Card");
  var _component_TabPanel = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("TabPanel");
  var _component_NapFlowComponent = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("NapFlowComponent");
  var _component_TabPanels = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("TabPanels");
  var _component_TabGroup = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("TabGroup");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Head, {
    title: "NAP Manager"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Heading, {
    "class": "nap-mb-6"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return _cache[0] || (_cache[0] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)("NAP Manager")]);
    }),
    _: 1 /* STABLE */,
    __: [0]
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_TabGroup, {
    onChange: $options.handleTabChange
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_TabList, {
        "class": "tab-menu nap-divide-x dark:nap-divide-gray-700 nap-border-l-gray-200 nap-border-r-gray-200 nap-border-t-gray-200 nap-border-b-gray-200 dark:nap-border-l-gray-700 dark:nap-border-r-gray-700 dark:nap-border-t-gray-700 dark:nap-border-b-gray-700"
      }, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Tab, {
            as: "template"
          }, {
            "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function (_ref) {
              var selected = _ref.selected;
              return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
                "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)([[selected ? 'active nap-text-primary-500 nap-font-bold nap-border-b-2 !nap-border-b-primary-500' : 'nap-text-gray-600 hover:nap-text-gray-800 dark:nap-text-gray-400 hover:dark:nap-text-gray-200'], "tab-item"])
              }, _cache[1] || (_cache[1] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
                "class": "nap-flex nap-items-center"
              }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("svg", {
                xmlns: "http://www.w3.org/2000/svg",
                "class": "nap-h-5 nap-w-5 nap-mr-2",
                viewBox: "0 0 20 20",
                fill: "currentColor"
              }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("path", {
                "fill-rule": "evenodd",
                d: "M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z",
                "clip-rule": "evenodd"
              })]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Map View ")], -1 /* HOISTED */)]), 2 /* CLASS */)];
            }),
            _: 1 /* STABLE */
          }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Tab, {
            as: "template"
          }, {
            "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function (_ref2) {
              var selected = _ref2.selected;
              return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("button", {
                "class": (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)([[selected ? 'active nap-text-primary-500 nap-font-bold nap-border-b-2 !nap-border-b-primary-500' : 'nap-text-gray-600 hover:nap-text-gray-800 dark:nap-text-gray-400 hover:dark:nap-text-gray-200'], "tab-item"])
              }, _cache[2] || (_cache[2] = [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("span", {
                "class": "nap-flex nap-items-center"
              }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("svg", {
                xmlns: "http://www.w3.org/2000/svg",
                "class": "nap-h-5 nap-w-5 nap-mr-2",
                viewBox: "0 0 20 20",
                fill: "currentColor"
              }, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("path", {
                d: "M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"
              })]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Flow View ")], -1 /* HOISTED */)]), 2 /* CLASS */)];
            }),
            _: 1 /* STABLE */
          })];
        }),
        _: 1 /* STABLE */
      }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_TabPanels, null, {
        "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
          return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_TabPanel, null, {
            "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
              return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Card, {
                "class": "nap-overflow-hidden nap-p-4",
                style: {
                  "min-height": "600px"
                }
              }, {
                "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
                  return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_NapMapComponent, {
                    googleMapsApiKey: $props.GOOGLE_MAPS_API_KEY
                  }, null, 8 /* PROPS */, ["googleMapsApiKey"])])];
                }),
                _: 1 /* STABLE */
              })];
            }),
            _: 1 /* STABLE */
          }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_TabPanel, null, {
            "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
              return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Card, {
                "class": "nap-overflow-hidden nap-p-4",
                style: {
                  "min-height": "600px"
                }
              }, {
                "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
                  return [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_4, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_NapFlowComponent)])];
                }),
                _: 1 /* STABLE */
              })];
            }),
            _: 1 /* STABLE */
          })];
        }),
        _: 1 /* STABLE */
      })];
    }),
    _: 1 /* STABLE */
  }, 8 /* PROPS */, ["onChange"])])]);
}

/***/ }),

/***/ "./node_modules/base64-js/index.js":
/*!*****************************************!*\
  !*** ./node_modules/base64-js/index.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, exports) => {

"use strict";


exports.byteLength = byteLength
exports.toByteArray = toByteArray
exports.fromByteArray = fromByteArray

var lookup = []
var revLookup = []
var Arr = typeof Uint8Array !== 'undefined' ? Uint8Array : Array

var code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
for (var i = 0, len = code.length; i < len; ++i) {
  lookup[i] = code[i]
  revLookup[code.charCodeAt(i)] = i
}

// Support decoding URL-safe base64 strings, as Node.js does.
// See: https://en.wikipedia.org/wiki/Base64#URL_applications
revLookup['-'.charCodeAt(0)] = 62
revLookup['_'.charCodeAt(0)] = 63

function getLens (b64) {
  var len = b64.length

  if (len % 4 > 0) {
    throw new Error('Invalid string. Length must be a multiple of 4')
  }

  // Trim off extra bytes after placeholder bytes are found
  // See: https://github.com/beatgammit/base64-js/issues/42
  var validLen = b64.indexOf('=')
  if (validLen === -1) validLen = len

  var placeHoldersLen = validLen === len
    ? 0
    : 4 - (validLen % 4)

  return [validLen, placeHoldersLen]
}

// base64 is 4/3 + up to two characters of the original data
function byteLength (b64) {
  var lens = getLens(b64)
  var validLen = lens[0]
  var placeHoldersLen = lens[1]
  return ((validLen + placeHoldersLen) * 3 / 4) - placeHoldersLen
}

function _byteLength (b64, validLen, placeHoldersLen) {
  return ((validLen + placeHoldersLen) * 3 / 4) - placeHoldersLen
}

function toByteArray (b64) {
  var tmp
  var lens = getLens(b64)
  var validLen = lens[0]
  var placeHoldersLen = lens[1]

  var arr = new Arr(_byteLength(b64, validLen, placeHoldersLen))

  var curByte = 0

  // if there are placeholders, only get up to the last complete 4 chars
  var len = placeHoldersLen > 0
    ? validLen - 4
    : validLen

  var i
  for (i = 0; i < len; i += 4) {
    tmp =
      (revLookup[b64.charCodeAt(i)] << 18) |
      (revLookup[b64.charCodeAt(i + 1)] << 12) |
      (revLookup[b64.charCodeAt(i + 2)] << 6) |
      revLookup[b64.charCodeAt(i + 3)]
    arr[curByte++] = (tmp >> 16) & 0xFF
    arr[curByte++] = (tmp >> 8) & 0xFF
    arr[curByte++] = tmp & 0xFF
  }

  if (placeHoldersLen === 2) {
    tmp =
      (revLookup[b64.charCodeAt(i)] << 2) |
      (revLookup[b64.charCodeAt(i + 1)] >> 4)
    arr[curByte++] = tmp & 0xFF
  }

  if (placeHoldersLen === 1) {
    tmp =
      (revLookup[b64.charCodeAt(i)] << 10) |
      (revLookup[b64.charCodeAt(i + 1)] << 4) |
      (revLookup[b64.charCodeAt(i + 2)] >> 2)
    arr[curByte++] = (tmp >> 8) & 0xFF
    arr[curByte++] = tmp & 0xFF
  }

  return arr
}

function tripletToBase64 (num) {
  return lookup[num >> 18 & 0x3F] +
    lookup[num >> 12 & 0x3F] +
    lookup[num >> 6 & 0x3F] +
    lookup[num & 0x3F]
}

function encodeChunk (uint8, start, end) {
  var tmp
  var output = []
  for (var i = start; i < end; i += 3) {
    tmp =
      ((uint8[i] << 16) & 0xFF0000) +
      ((uint8[i + 1] << 8) & 0xFF00) +
      (uint8[i + 2] & 0xFF)
    output.push(tripletToBase64(tmp))
  }
  return output.join('')
}

function fromByteArray (uint8) {
  var tmp
  var len = uint8.length
  var extraBytes = len % 3 // if we have 1 byte left, pad 2 bytes
  var parts = []
  var maxChunkLength = 16383 // must be multiple of 3

  // go through the array every three bytes, we'll deal with trailing stuff later
  for (var i = 0, len2 = len - extraBytes; i < len2; i += maxChunkLength) {
    parts.push(encodeChunk(uint8, i, (i + maxChunkLength) > len2 ? len2 : (i + maxChunkLength)))
  }

  // pad the end with zeros, but make sure to not forget the extra bytes
  if (extraBytes === 1) {
    tmp = uint8[len - 1]
    parts.push(
      lookup[tmp >> 2] +
      lookup[(tmp << 4) & 0x3F] +
      '=='
    )
  } else if (extraBytes === 2) {
    tmp = (uint8[len - 2] << 8) + uint8[len - 1]
    parts.push(
      lookup[tmp >> 10] +
      lookup[(tmp >> 4) & 0x3F] +
      lookup[(tmp << 2) & 0x3F] +
      '='
    )
  }

  return parts.join('')
}


/***/ }),

/***/ "./node_modules/buffer/index.js":
/*!**************************************!*\
  !*** ./node_modules/buffer/index.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";
/*!
 * The buffer module from node.js, for the browser.
 *
 * @author   Feross Aboukhadijeh <http://feross.org>
 * @license  MIT
 */
/* eslint-disable no-proto */



var base64 = __webpack_require__(/*! base64-js */ "./node_modules/base64-js/index.js")
var ieee754 = __webpack_require__(/*! ieee754 */ "./node_modules/ieee754/index.js")
var isArray = __webpack_require__(/*! isarray */ "./node_modules/isarray/index.js")

exports.Buffer = Buffer
exports.SlowBuffer = SlowBuffer
exports.INSPECT_MAX_BYTES = 50

/**
 * If `Buffer.TYPED_ARRAY_SUPPORT`:
 *   === true    Use Uint8Array implementation (fastest)
 *   === false   Use Object implementation (most compatible, even IE6)
 *
 * Browsers that support typed arrays are IE 10+, Firefox 4+, Chrome 7+, Safari 5.1+,
 * Opera 11.6+, iOS 4.2+.
 *
 * Due to various browser bugs, sometimes the Object implementation will be used even
 * when the browser supports typed arrays.
 *
 * Note:
 *
 *   - Firefox 4-29 lacks support for adding new properties to `Uint8Array` instances,
 *     See: https://bugzilla.mozilla.org/show_bug.cgi?id=695438.
 *
 *   - Chrome 9-10 is missing the `TypedArray.prototype.subarray` function.
 *
 *   - IE10 has a broken `TypedArray.prototype.subarray` function which returns arrays of
 *     incorrect length in some situations.

 * We detect these buggy browsers and set `Buffer.TYPED_ARRAY_SUPPORT` to `false` so they
 * get the Object implementation, which is slower but behaves correctly.
 */
Buffer.TYPED_ARRAY_SUPPORT = __webpack_require__.g.TYPED_ARRAY_SUPPORT !== undefined
  ? __webpack_require__.g.TYPED_ARRAY_SUPPORT
  : typedArraySupport()

/*
 * Export kMaxLength after typed array support is determined.
 */
exports.kMaxLength = kMaxLength()

function typedArraySupport () {
  try {
    var arr = new Uint8Array(1)
    arr.__proto__ = {__proto__: Uint8Array.prototype, foo: function () { return 42 }}
    return arr.foo() === 42 && // typed array instances can be augmented
        typeof arr.subarray === 'function' && // chrome 9-10 lack `subarray`
        arr.subarray(1, 1).byteLength === 0 // ie10 has broken `subarray`
  } catch (e) {
    return false
  }
}

function kMaxLength () {
  return Buffer.TYPED_ARRAY_SUPPORT
    ? 0x7fffffff
    : 0x3fffffff
}

function createBuffer (that, length) {
  if (kMaxLength() < length) {
    throw new RangeError('Invalid typed array length')
  }
  if (Buffer.TYPED_ARRAY_SUPPORT) {
    // Return an augmented `Uint8Array` instance, for best performance
    that = new Uint8Array(length)
    that.__proto__ = Buffer.prototype
  } else {
    // Fallback: Return an object instance of the Buffer class
    if (that === null) {
      that = new Buffer(length)
    }
    that.length = length
  }

  return that
}

/**
 * The Buffer constructor returns instances of `Uint8Array` that have their
 * prototype changed to `Buffer.prototype`. Furthermore, `Buffer` is a subclass of
 * `Uint8Array`, so the returned instances will have all the node `Buffer` methods
 * and the `Uint8Array` methods. Square bracket notation works as expected -- it
 * returns a single octet.
 *
 * The `Uint8Array` prototype remains unmodified.
 */

function Buffer (arg, encodingOrOffset, length) {
  if (!Buffer.TYPED_ARRAY_SUPPORT && !(this instanceof Buffer)) {
    return new Buffer(arg, encodingOrOffset, length)
  }

  // Common case.
  if (typeof arg === 'number') {
    if (typeof encodingOrOffset === 'string') {
      throw new Error(
        'If encoding is specified then the first argument must be a string'
      )
    }
    return allocUnsafe(this, arg)
  }
  return from(this, arg, encodingOrOffset, length)
}

Buffer.poolSize = 8192 // not used by this implementation

// TODO: Legacy, not needed anymore. Remove in next major version.
Buffer._augment = function (arr) {
  arr.__proto__ = Buffer.prototype
  return arr
}

function from (that, value, encodingOrOffset, length) {
  if (typeof value === 'number') {
    throw new TypeError('"value" argument must not be a number')
  }

  if (typeof ArrayBuffer !== 'undefined' && value instanceof ArrayBuffer) {
    return fromArrayBuffer(that, value, encodingOrOffset, length)
  }

  if (typeof value === 'string') {
    return fromString(that, value, encodingOrOffset)
  }

  return fromObject(that, value)
}

/**
 * Functionally equivalent to Buffer(arg, encoding) but throws a TypeError
 * if value is a number.
 * Buffer.from(str[, encoding])
 * Buffer.from(array)
 * Buffer.from(buffer)
 * Buffer.from(arrayBuffer[, byteOffset[, length]])
 **/
Buffer.from = function (value, encodingOrOffset, length) {
  return from(null, value, encodingOrOffset, length)
}

if (Buffer.TYPED_ARRAY_SUPPORT) {
  Buffer.prototype.__proto__ = Uint8Array.prototype
  Buffer.__proto__ = Uint8Array
  if (typeof Symbol !== 'undefined' && Symbol.species &&
      Buffer[Symbol.species] === Buffer) {
    // Fix subarray() in ES2016. See: https://github.com/feross/buffer/pull/97
    Object.defineProperty(Buffer, Symbol.species, {
      value: null,
      configurable: true
    })
  }
}

function assertSize (size) {
  if (typeof size !== 'number') {
    throw new TypeError('"size" argument must be a number')
  } else if (size < 0) {
    throw new RangeError('"size" argument must not be negative')
  }
}

function alloc (that, size, fill, encoding) {
  assertSize(size)
  if (size <= 0) {
    return createBuffer(that, size)
  }
  if (fill !== undefined) {
    // Only pay attention to encoding if it's a string. This
    // prevents accidentally sending in a number that would
    // be interpretted as a start offset.
    return typeof encoding === 'string'
      ? createBuffer(that, size).fill(fill, encoding)
      : createBuffer(that, size).fill(fill)
  }
  return createBuffer(that, size)
}

/**
 * Creates a new filled Buffer instance.
 * alloc(size[, fill[, encoding]])
 **/
Buffer.alloc = function (size, fill, encoding) {
  return alloc(null, size, fill, encoding)
}

function allocUnsafe (that, size) {
  assertSize(size)
  that = createBuffer(that, size < 0 ? 0 : checked(size) | 0)
  if (!Buffer.TYPED_ARRAY_SUPPORT) {
    for (var i = 0; i < size; ++i) {
      that[i] = 0
    }
  }
  return that
}

/**
 * Equivalent to Buffer(num), by default creates a non-zero-filled Buffer instance.
 * */
Buffer.allocUnsafe = function (size) {
  return allocUnsafe(null, size)
}
/**
 * Equivalent to SlowBuffer(num), by default creates a non-zero-filled Buffer instance.
 */
Buffer.allocUnsafeSlow = function (size) {
  return allocUnsafe(null, size)
}

function fromString (that, string, encoding) {
  if (typeof encoding !== 'string' || encoding === '') {
    encoding = 'utf8'
  }

  if (!Buffer.isEncoding(encoding)) {
    throw new TypeError('"encoding" must be a valid string encoding')
  }

  var length = byteLength(string, encoding) | 0
  that = createBuffer(that, length)

  var actual = that.write(string, encoding)

  if (actual !== length) {
    // Writing a hex string, for example, that contains invalid characters will
    // cause everything after the first invalid character to be ignored. (e.g.
    // 'abxxcd' will be treated as 'ab')
    that = that.slice(0, actual)
  }

  return that
}

function fromArrayLike (that, array) {
  var length = array.length < 0 ? 0 : checked(array.length) | 0
  that = createBuffer(that, length)
  for (var i = 0; i < length; i += 1) {
    that[i] = array[i] & 255
  }
  return that
}

function fromArrayBuffer (that, array, byteOffset, length) {
  array.byteLength // this throws if `array` is not a valid ArrayBuffer

  if (byteOffset < 0 || array.byteLength < byteOffset) {
    throw new RangeError('\'offset\' is out of bounds')
  }

  if (array.byteLength < byteOffset + (length || 0)) {
    throw new RangeError('\'length\' is out of bounds')
  }

  if (byteOffset === undefined && length === undefined) {
    array = new Uint8Array(array)
  } else if (length === undefined) {
    array = new Uint8Array(array, byteOffset)
  } else {
    array = new Uint8Array(array, byteOffset, length)
  }

  if (Buffer.TYPED_ARRAY_SUPPORT) {
    // Return an augmented `Uint8Array` instance, for best performance
    that = array
    that.__proto__ = Buffer.prototype
  } else {
    // Fallback: Return an object instance of the Buffer class
    that = fromArrayLike(that, array)
  }
  return that
}

function fromObject (that, obj) {
  if (Buffer.isBuffer(obj)) {
    var len = checked(obj.length) | 0
    that = createBuffer(that, len)

    if (that.length === 0) {
      return that
    }

    obj.copy(that, 0, 0, len)
    return that
  }

  if (obj) {
    if ((typeof ArrayBuffer !== 'undefined' &&
        obj.buffer instanceof ArrayBuffer) || 'length' in obj) {
      if (typeof obj.length !== 'number' || isnan(obj.length)) {
        return createBuffer(that, 0)
      }
      return fromArrayLike(that, obj)
    }

    if (obj.type === 'Buffer' && isArray(obj.data)) {
      return fromArrayLike(that, obj.data)
    }
  }

  throw new TypeError('First argument must be a string, Buffer, ArrayBuffer, Array, or array-like object.')
}

function checked (length) {
  // Note: cannot use `length < kMaxLength()` here because that fails when
  // length is NaN (which is otherwise coerced to zero.)
  if (length >= kMaxLength()) {
    throw new RangeError('Attempt to allocate Buffer larger than maximum ' +
                         'size: 0x' + kMaxLength().toString(16) + ' bytes')
  }
  return length | 0
}

function SlowBuffer (length) {
  if (+length != length) { // eslint-disable-line eqeqeq
    length = 0
  }
  return Buffer.alloc(+length)
}

Buffer.isBuffer = function isBuffer (b) {
  return !!(b != null && b._isBuffer)
}

Buffer.compare = function compare (a, b) {
  if (!Buffer.isBuffer(a) || !Buffer.isBuffer(b)) {
    throw new TypeError('Arguments must be Buffers')
  }

  if (a === b) return 0

  var x = a.length
  var y = b.length

  for (var i = 0, len = Math.min(x, y); i < len; ++i) {
    if (a[i] !== b[i]) {
      x = a[i]
      y = b[i]
      break
    }
  }

  if (x < y) return -1
  if (y < x) return 1
  return 0
}

Buffer.isEncoding = function isEncoding (encoding) {
  switch (String(encoding).toLowerCase()) {
    case 'hex':
    case 'utf8':
    case 'utf-8':
    case 'ascii':
    case 'latin1':
    case 'binary':
    case 'base64':
    case 'ucs2':
    case 'ucs-2':
    case 'utf16le':
    case 'utf-16le':
      return true
    default:
      return false
  }
}

Buffer.concat = function concat (list, length) {
  if (!isArray(list)) {
    throw new TypeError('"list" argument must be an Array of Buffers')
  }

  if (list.length === 0) {
    return Buffer.alloc(0)
  }

  var i
  if (length === undefined) {
    length = 0
    for (i = 0; i < list.length; ++i) {
      length += list[i].length
    }
  }

  var buffer = Buffer.allocUnsafe(length)
  var pos = 0
  for (i = 0; i < list.length; ++i) {
    var buf = list[i]
    if (!Buffer.isBuffer(buf)) {
      throw new TypeError('"list" argument must be an Array of Buffers')
    }
    buf.copy(buffer, pos)
    pos += buf.length
  }
  return buffer
}

function byteLength (string, encoding) {
  if (Buffer.isBuffer(string)) {
    return string.length
  }
  if (typeof ArrayBuffer !== 'undefined' && typeof ArrayBuffer.isView === 'function' &&
      (ArrayBuffer.isView(string) || string instanceof ArrayBuffer)) {
    return string.byteLength
  }
  if (typeof string !== 'string') {
    string = '' + string
  }

  var len = string.length
  if (len === 0) return 0

  // Use a for loop to avoid recursion
  var loweredCase = false
  for (;;) {
    switch (encoding) {
      case 'ascii':
      case 'latin1':
      case 'binary':
        return len
      case 'utf8':
      case 'utf-8':
      case undefined:
        return utf8ToBytes(string).length
      case 'ucs2':
      case 'ucs-2':
      case 'utf16le':
      case 'utf-16le':
        return len * 2
      case 'hex':
        return len >>> 1
      case 'base64':
        return base64ToBytes(string).length
      default:
        if (loweredCase) return utf8ToBytes(string).length // assume utf8
        encoding = ('' + encoding).toLowerCase()
        loweredCase = true
    }
  }
}
Buffer.byteLength = byteLength

function slowToString (encoding, start, end) {
  var loweredCase = false

  // No need to verify that "this.length <= MAX_UINT32" since it's a read-only
  // property of a typed array.

  // This behaves neither like String nor Uint8Array in that we set start/end
  // to their upper/lower bounds if the value passed is out of range.
  // undefined is handled specially as per ECMA-262 6th Edition,
  // Section 13.3.3.7 Runtime Semantics: KeyedBindingInitialization.
  if (start === undefined || start < 0) {
    start = 0
  }
  // Return early if start > this.length. Done here to prevent potential uint32
  // coercion fail below.
  if (start > this.length) {
    return ''
  }

  if (end === undefined || end > this.length) {
    end = this.length
  }

  if (end <= 0) {
    return ''
  }

  // Force coersion to uint32. This will also coerce falsey/NaN values to 0.
  end >>>= 0
  start >>>= 0

  if (end <= start) {
    return ''
  }

  if (!encoding) encoding = 'utf8'

  while (true) {
    switch (encoding) {
      case 'hex':
        return hexSlice(this, start, end)

      case 'utf8':
      case 'utf-8':
        return utf8Slice(this, start, end)

      case 'ascii':
        return asciiSlice(this, start, end)

      case 'latin1':
      case 'binary':
        return latin1Slice(this, start, end)

      case 'base64':
        return base64Slice(this, start, end)

      case 'ucs2':
      case 'ucs-2':
      case 'utf16le':
      case 'utf-16le':
        return utf16leSlice(this, start, end)

      default:
        if (loweredCase) throw new TypeError('Unknown encoding: ' + encoding)
        encoding = (encoding + '').toLowerCase()
        loweredCase = true
    }
  }
}

// The property is used by `Buffer.isBuffer` and `is-buffer` (in Safari 5-7) to detect
// Buffer instances.
Buffer.prototype._isBuffer = true

function swap (b, n, m) {
  var i = b[n]
  b[n] = b[m]
  b[m] = i
}

Buffer.prototype.swap16 = function swap16 () {
  var len = this.length
  if (len % 2 !== 0) {
    throw new RangeError('Buffer size must be a multiple of 16-bits')
  }
  for (var i = 0; i < len; i += 2) {
    swap(this, i, i + 1)
  }
  return this
}

Buffer.prototype.swap32 = function swap32 () {
  var len = this.length
  if (len % 4 !== 0) {
    throw new RangeError('Buffer size must be a multiple of 32-bits')
  }
  for (var i = 0; i < len; i += 4) {
    swap(this, i, i + 3)
    swap(this, i + 1, i + 2)
  }
  return this
}

Buffer.prototype.swap64 = function swap64 () {
  var len = this.length
  if (len % 8 !== 0) {
    throw new RangeError('Buffer size must be a multiple of 64-bits')
  }
  for (var i = 0; i < len; i += 8) {
    swap(this, i, i + 7)
    swap(this, i + 1, i + 6)
    swap(this, i + 2, i + 5)
    swap(this, i + 3, i + 4)
  }
  return this
}

Buffer.prototype.toString = function toString () {
  var length = this.length | 0
  if (length === 0) return ''
  if (arguments.length === 0) return utf8Slice(this, 0, length)
  return slowToString.apply(this, arguments)
}

Buffer.prototype.equals = function equals (b) {
  if (!Buffer.isBuffer(b)) throw new TypeError('Argument must be a Buffer')
  if (this === b) return true
  return Buffer.compare(this, b) === 0
}

Buffer.prototype.inspect = function inspect () {
  var str = ''
  var max = exports.INSPECT_MAX_BYTES
  if (this.length > 0) {
    str = this.toString('hex', 0, max).match(/.{2}/g).join(' ')
    if (this.length > max) str += ' ... '
  }
  return '<Buffer ' + str + '>'
}

Buffer.prototype.compare = function compare (target, start, end, thisStart, thisEnd) {
  if (!Buffer.isBuffer(target)) {
    throw new TypeError('Argument must be a Buffer')
  }

  if (start === undefined) {
    start = 0
  }
  if (end === undefined) {
    end = target ? target.length : 0
  }
  if (thisStart === undefined) {
    thisStart = 0
  }
  if (thisEnd === undefined) {
    thisEnd = this.length
  }

  if (start < 0 || end > target.length || thisStart < 0 || thisEnd > this.length) {
    throw new RangeError('out of range index')
  }

  if (thisStart >= thisEnd && start >= end) {
    return 0
  }
  if (thisStart >= thisEnd) {
    return -1
  }
  if (start >= end) {
    return 1
  }

  start >>>= 0
  end >>>= 0
  thisStart >>>= 0
  thisEnd >>>= 0

  if (this === target) return 0

  var x = thisEnd - thisStart
  var y = end - start
  var len = Math.min(x, y)

  var thisCopy = this.slice(thisStart, thisEnd)
  var targetCopy = target.slice(start, end)

  for (var i = 0; i < len; ++i) {
    if (thisCopy[i] !== targetCopy[i]) {
      x = thisCopy[i]
      y = targetCopy[i]
      break
    }
  }

  if (x < y) return -1
  if (y < x) return 1
  return 0
}

// Finds either the first index of `val` in `buffer` at offset >= `byteOffset`,
// OR the last index of `val` in `buffer` at offset <= `byteOffset`.
//
// Arguments:
// - buffer - a Buffer to search
// - val - a string, Buffer, or number
// - byteOffset - an index into `buffer`; will be clamped to an int32
// - encoding - an optional encoding, relevant is val is a string
// - dir - true for indexOf, false for lastIndexOf
function bidirectionalIndexOf (buffer, val, byteOffset, encoding, dir) {
  // Empty buffer means no match
  if (buffer.length === 0) return -1

  // Normalize byteOffset
  if (typeof byteOffset === 'string') {
    encoding = byteOffset
    byteOffset = 0
  } else if (byteOffset > 0x7fffffff) {
    byteOffset = 0x7fffffff
  } else if (byteOffset < -0x80000000) {
    byteOffset = -0x80000000
  }
  byteOffset = +byteOffset  // Coerce to Number.
  if (isNaN(byteOffset)) {
    // byteOffset: it it's undefined, null, NaN, "foo", etc, search whole buffer
    byteOffset = dir ? 0 : (buffer.length - 1)
  }

  // Normalize byteOffset: negative offsets start from the end of the buffer
  if (byteOffset < 0) byteOffset = buffer.length + byteOffset
  if (byteOffset >= buffer.length) {
    if (dir) return -1
    else byteOffset = buffer.length - 1
  } else if (byteOffset < 0) {
    if (dir) byteOffset = 0
    else return -1
  }

  // Normalize val
  if (typeof val === 'string') {
    val = Buffer.from(val, encoding)
  }

  // Finally, search either indexOf (if dir is true) or lastIndexOf
  if (Buffer.isBuffer(val)) {
    // Special case: looking for empty string/buffer always fails
    if (val.length === 0) {
      return -1
    }
    return arrayIndexOf(buffer, val, byteOffset, encoding, dir)
  } else if (typeof val === 'number') {
    val = val & 0xFF // Search for a byte value [0-255]
    if (Buffer.TYPED_ARRAY_SUPPORT &&
        typeof Uint8Array.prototype.indexOf === 'function') {
      if (dir) {
        return Uint8Array.prototype.indexOf.call(buffer, val, byteOffset)
      } else {
        return Uint8Array.prototype.lastIndexOf.call(buffer, val, byteOffset)
      }
    }
    return arrayIndexOf(buffer, [ val ], byteOffset, encoding, dir)
  }

  throw new TypeError('val must be string, number or Buffer')
}

function arrayIndexOf (arr, val, byteOffset, encoding, dir) {
  var indexSize = 1
  var arrLength = arr.length
  var valLength = val.length

  if (encoding !== undefined) {
    encoding = String(encoding).toLowerCase()
    if (encoding === 'ucs2' || encoding === 'ucs-2' ||
        encoding === 'utf16le' || encoding === 'utf-16le') {
      if (arr.length < 2 || val.length < 2) {
        return -1
      }
      indexSize = 2
      arrLength /= 2
      valLength /= 2
      byteOffset /= 2
    }
  }

  function read (buf, i) {
    if (indexSize === 1) {
      return buf[i]
    } else {
      return buf.readUInt16BE(i * indexSize)
    }
  }

  var i
  if (dir) {
    var foundIndex = -1
    for (i = byteOffset; i < arrLength; i++) {
      if (read(arr, i) === read(val, foundIndex === -1 ? 0 : i - foundIndex)) {
        if (foundIndex === -1) foundIndex = i
        if (i - foundIndex + 1 === valLength) return foundIndex * indexSize
      } else {
        if (foundIndex !== -1) i -= i - foundIndex
        foundIndex = -1
      }
    }
  } else {
    if (byteOffset + valLength > arrLength) byteOffset = arrLength - valLength
    for (i = byteOffset; i >= 0; i--) {
      var found = true
      for (var j = 0; j < valLength; j++) {
        if (read(arr, i + j) !== read(val, j)) {
          found = false
          break
        }
      }
      if (found) return i
    }
  }

  return -1
}

Buffer.prototype.includes = function includes (val, byteOffset, encoding) {
  return this.indexOf(val, byteOffset, encoding) !== -1
}

Buffer.prototype.indexOf = function indexOf (val, byteOffset, encoding) {
  return bidirectionalIndexOf(this, val, byteOffset, encoding, true)
}

Buffer.prototype.lastIndexOf = function lastIndexOf (val, byteOffset, encoding) {
  return bidirectionalIndexOf(this, val, byteOffset, encoding, false)
}

function hexWrite (buf, string, offset, length) {
  offset = Number(offset) || 0
  var remaining = buf.length - offset
  if (!length) {
    length = remaining
  } else {
    length = Number(length)
    if (length > remaining) {
      length = remaining
    }
  }

  // must be an even number of digits
  var strLen = string.length
  if (strLen % 2 !== 0) throw new TypeError('Invalid hex string')

  if (length > strLen / 2) {
    length = strLen / 2
  }
  for (var i = 0; i < length; ++i) {
    var parsed = parseInt(string.substr(i * 2, 2), 16)
    if (isNaN(parsed)) return i
    buf[offset + i] = parsed
  }
  return i
}

function utf8Write (buf, string, offset, length) {
  return blitBuffer(utf8ToBytes(string, buf.length - offset), buf, offset, length)
}

function asciiWrite (buf, string, offset, length) {
  return blitBuffer(asciiToBytes(string), buf, offset, length)
}

function latin1Write (buf, string, offset, length) {
  return asciiWrite(buf, string, offset, length)
}

function base64Write (buf, string, offset, length) {
  return blitBuffer(base64ToBytes(string), buf, offset, length)
}

function ucs2Write (buf, string, offset, length) {
  return blitBuffer(utf16leToBytes(string, buf.length - offset), buf, offset, length)
}

Buffer.prototype.write = function write (string, offset, length, encoding) {
  // Buffer#write(string)
  if (offset === undefined) {
    encoding = 'utf8'
    length = this.length
    offset = 0
  // Buffer#write(string, encoding)
  } else if (length === undefined && typeof offset === 'string') {
    encoding = offset
    length = this.length
    offset = 0
  // Buffer#write(string, offset[, length][, encoding])
  } else if (isFinite(offset)) {
    offset = offset | 0
    if (isFinite(length)) {
      length = length | 0
      if (encoding === undefined) encoding = 'utf8'
    } else {
      encoding = length
      length = undefined
    }
  // legacy write(string, encoding, offset, length) - remove in v0.13
  } else {
    throw new Error(
      'Buffer.write(string, encoding, offset[, length]) is no longer supported'
    )
  }

  var remaining = this.length - offset
  if (length === undefined || length > remaining) length = remaining

  if ((string.length > 0 && (length < 0 || offset < 0)) || offset > this.length) {
    throw new RangeError('Attempt to write outside buffer bounds')
  }

  if (!encoding) encoding = 'utf8'

  var loweredCase = false
  for (;;) {
    switch (encoding) {
      case 'hex':
        return hexWrite(this, string, offset, length)

      case 'utf8':
      case 'utf-8':
        return utf8Write(this, string, offset, length)

      case 'ascii':
        return asciiWrite(this, string, offset, length)

      case 'latin1':
      case 'binary':
        return latin1Write(this, string, offset, length)

      case 'base64':
        // Warning: maxLength not taken into account in base64Write
        return base64Write(this, string, offset, length)

      case 'ucs2':
      case 'ucs-2':
      case 'utf16le':
      case 'utf-16le':
        return ucs2Write(this, string, offset, length)

      default:
        if (loweredCase) throw new TypeError('Unknown encoding: ' + encoding)
        encoding = ('' + encoding).toLowerCase()
        loweredCase = true
    }
  }
}

Buffer.prototype.toJSON = function toJSON () {
  return {
    type: 'Buffer',
    data: Array.prototype.slice.call(this._arr || this, 0)
  }
}

function base64Slice (buf, start, end) {
  if (start === 0 && end === buf.length) {
    return base64.fromByteArray(buf)
  } else {
    return base64.fromByteArray(buf.slice(start, end))
  }
}

function utf8Slice (buf, start, end) {
  end = Math.min(buf.length, end)
  var res = []

  var i = start
  while (i < end) {
    var firstByte = buf[i]
    var codePoint = null
    var bytesPerSequence = (firstByte > 0xEF) ? 4
      : (firstByte > 0xDF) ? 3
      : (firstByte > 0xBF) ? 2
      : 1

    if (i + bytesPerSequence <= end) {
      var secondByte, thirdByte, fourthByte, tempCodePoint

      switch (bytesPerSequence) {
        case 1:
          if (firstByte < 0x80) {
            codePoint = firstByte
          }
          break
        case 2:
          secondByte = buf[i + 1]
          if ((secondByte & 0xC0) === 0x80) {
            tempCodePoint = (firstByte & 0x1F) << 0x6 | (secondByte & 0x3F)
            if (tempCodePoint > 0x7F) {
              codePoint = tempCodePoint
            }
          }
          break
        case 3:
          secondByte = buf[i + 1]
          thirdByte = buf[i + 2]
          if ((secondByte & 0xC0) === 0x80 && (thirdByte & 0xC0) === 0x80) {
            tempCodePoint = (firstByte & 0xF) << 0xC | (secondByte & 0x3F) << 0x6 | (thirdByte & 0x3F)
            if (tempCodePoint > 0x7FF && (tempCodePoint < 0xD800 || tempCodePoint > 0xDFFF)) {
              codePoint = tempCodePoint
            }
          }
          break
        case 4:
          secondByte = buf[i + 1]
          thirdByte = buf[i + 2]
          fourthByte = buf[i + 3]
          if ((secondByte & 0xC0) === 0x80 && (thirdByte & 0xC0) === 0x80 && (fourthByte & 0xC0) === 0x80) {
            tempCodePoint = (firstByte & 0xF) << 0x12 | (secondByte & 0x3F) << 0xC | (thirdByte & 0x3F) << 0x6 | (fourthByte & 0x3F)
            if (tempCodePoint > 0xFFFF && tempCodePoint < 0x110000) {
              codePoint = tempCodePoint
            }
          }
      }
    }

    if (codePoint === null) {
      // we did not generate a valid codePoint so insert a
      // replacement char (U+FFFD) and advance only 1 byte
      codePoint = 0xFFFD
      bytesPerSequence = 1
    } else if (codePoint > 0xFFFF) {
      // encode to utf16 (surrogate pair dance)
      codePoint -= 0x10000
      res.push(codePoint >>> 10 & 0x3FF | 0xD800)
      codePoint = 0xDC00 | codePoint & 0x3FF
    }

    res.push(codePoint)
    i += bytesPerSequence
  }

  return decodeCodePointsArray(res)
}

// Based on http://stackoverflow.com/a/22747272/680742, the browser with
// the lowest limit is Chrome, with 0x10000 args.
// We go 1 magnitude less, for safety
var MAX_ARGUMENTS_LENGTH = 0x1000

function decodeCodePointsArray (codePoints) {
  var len = codePoints.length
  if (len <= MAX_ARGUMENTS_LENGTH) {
    return String.fromCharCode.apply(String, codePoints) // avoid extra slice()
  }

  // Decode in chunks to avoid "call stack size exceeded".
  var res = ''
  var i = 0
  while (i < len) {
    res += String.fromCharCode.apply(
      String,
      codePoints.slice(i, i += MAX_ARGUMENTS_LENGTH)
    )
  }
  return res
}

function asciiSlice (buf, start, end) {
  var ret = ''
  end = Math.min(buf.length, end)

  for (var i = start; i < end; ++i) {
    ret += String.fromCharCode(buf[i] & 0x7F)
  }
  return ret
}

function latin1Slice (buf, start, end) {
  var ret = ''
  end = Math.min(buf.length, end)

  for (var i = start; i < end; ++i) {
    ret += String.fromCharCode(buf[i])
  }
  return ret
}

function hexSlice (buf, start, end) {
  var len = buf.length

  if (!start || start < 0) start = 0
  if (!end || end < 0 || end > len) end = len

  var out = ''
  for (var i = start; i < end; ++i) {
    out += toHex(buf[i])
  }
  return out
}

function utf16leSlice (buf, start, end) {
  var bytes = buf.slice(start, end)
  var res = ''
  for (var i = 0; i < bytes.length; i += 2) {
    res += String.fromCharCode(bytes[i] + bytes[i + 1] * 256)
  }
  return res
}

Buffer.prototype.slice = function slice (start, end) {
  var len = this.length
  start = ~~start
  end = end === undefined ? len : ~~end

  if (start < 0) {
    start += len
    if (start < 0) start = 0
  } else if (start > len) {
    start = len
  }

  if (end < 0) {
    end += len
    if (end < 0) end = 0
  } else if (end > len) {
    end = len
  }

  if (end < start) end = start

  var newBuf
  if (Buffer.TYPED_ARRAY_SUPPORT) {
    newBuf = this.subarray(start, end)
    newBuf.__proto__ = Buffer.prototype
  } else {
    var sliceLen = end - start
    newBuf = new Buffer(sliceLen, undefined)
    for (var i = 0; i < sliceLen; ++i) {
      newBuf[i] = this[i + start]
    }
  }

  return newBuf
}

/*
 * Need to make sure that buffer isn't trying to write out of bounds.
 */
function checkOffset (offset, ext, length) {
  if ((offset % 1) !== 0 || offset < 0) throw new RangeError('offset is not uint')
  if (offset + ext > length) throw new RangeError('Trying to access beyond buffer length')
}

Buffer.prototype.readUIntLE = function readUIntLE (offset, byteLength, noAssert) {
  offset = offset | 0
  byteLength = byteLength | 0
  if (!noAssert) checkOffset(offset, byteLength, this.length)

  var val = this[offset]
  var mul = 1
  var i = 0
  while (++i < byteLength && (mul *= 0x100)) {
    val += this[offset + i] * mul
  }

  return val
}

Buffer.prototype.readUIntBE = function readUIntBE (offset, byteLength, noAssert) {
  offset = offset | 0
  byteLength = byteLength | 0
  if (!noAssert) {
    checkOffset(offset, byteLength, this.length)
  }

  var val = this[offset + --byteLength]
  var mul = 1
  while (byteLength > 0 && (mul *= 0x100)) {
    val += this[offset + --byteLength] * mul
  }

  return val
}

Buffer.prototype.readUInt8 = function readUInt8 (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 1, this.length)
  return this[offset]
}

Buffer.prototype.readUInt16LE = function readUInt16LE (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 2, this.length)
  return this[offset] | (this[offset + 1] << 8)
}

Buffer.prototype.readUInt16BE = function readUInt16BE (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 2, this.length)
  return (this[offset] << 8) | this[offset + 1]
}

Buffer.prototype.readUInt32LE = function readUInt32LE (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 4, this.length)

  return ((this[offset]) |
      (this[offset + 1] << 8) |
      (this[offset + 2] << 16)) +
      (this[offset + 3] * 0x1000000)
}

Buffer.prototype.readUInt32BE = function readUInt32BE (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 4, this.length)

  return (this[offset] * 0x1000000) +
    ((this[offset + 1] << 16) |
    (this[offset + 2] << 8) |
    this[offset + 3])
}

Buffer.prototype.readIntLE = function readIntLE (offset, byteLength, noAssert) {
  offset = offset | 0
  byteLength = byteLength | 0
  if (!noAssert) checkOffset(offset, byteLength, this.length)

  var val = this[offset]
  var mul = 1
  var i = 0
  while (++i < byteLength && (mul *= 0x100)) {
    val += this[offset + i] * mul
  }
  mul *= 0x80

  if (val >= mul) val -= Math.pow(2, 8 * byteLength)

  return val
}

Buffer.prototype.readIntBE = function readIntBE (offset, byteLength, noAssert) {
  offset = offset | 0
  byteLength = byteLength | 0
  if (!noAssert) checkOffset(offset, byteLength, this.length)

  var i = byteLength
  var mul = 1
  var val = this[offset + --i]
  while (i > 0 && (mul *= 0x100)) {
    val += this[offset + --i] * mul
  }
  mul *= 0x80

  if (val >= mul) val -= Math.pow(2, 8 * byteLength)

  return val
}

Buffer.prototype.readInt8 = function readInt8 (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 1, this.length)
  if (!(this[offset] & 0x80)) return (this[offset])
  return ((0xff - this[offset] + 1) * -1)
}

Buffer.prototype.readInt16LE = function readInt16LE (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 2, this.length)
  var val = this[offset] | (this[offset + 1] << 8)
  return (val & 0x8000) ? val | 0xFFFF0000 : val
}

Buffer.prototype.readInt16BE = function readInt16BE (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 2, this.length)
  var val = this[offset + 1] | (this[offset] << 8)
  return (val & 0x8000) ? val | 0xFFFF0000 : val
}

Buffer.prototype.readInt32LE = function readInt32LE (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 4, this.length)

  return (this[offset]) |
    (this[offset + 1] << 8) |
    (this[offset + 2] << 16) |
    (this[offset + 3] << 24)
}

Buffer.prototype.readInt32BE = function readInt32BE (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 4, this.length)

  return (this[offset] << 24) |
    (this[offset + 1] << 16) |
    (this[offset + 2] << 8) |
    (this[offset + 3])
}

Buffer.prototype.readFloatLE = function readFloatLE (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 4, this.length)
  return ieee754.read(this, offset, true, 23, 4)
}

Buffer.prototype.readFloatBE = function readFloatBE (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 4, this.length)
  return ieee754.read(this, offset, false, 23, 4)
}

Buffer.prototype.readDoubleLE = function readDoubleLE (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 8, this.length)
  return ieee754.read(this, offset, true, 52, 8)
}

Buffer.prototype.readDoubleBE = function readDoubleBE (offset, noAssert) {
  if (!noAssert) checkOffset(offset, 8, this.length)
  return ieee754.read(this, offset, false, 52, 8)
}

function checkInt (buf, value, offset, ext, max, min) {
  if (!Buffer.isBuffer(buf)) throw new TypeError('"buffer" argument must be a Buffer instance')
  if (value > max || value < min) throw new RangeError('"value" argument is out of bounds')
  if (offset + ext > buf.length) throw new RangeError('Index out of range')
}

Buffer.prototype.writeUIntLE = function writeUIntLE (value, offset, byteLength, noAssert) {
  value = +value
  offset = offset | 0
  byteLength = byteLength | 0
  if (!noAssert) {
    var maxBytes = Math.pow(2, 8 * byteLength) - 1
    checkInt(this, value, offset, byteLength, maxBytes, 0)
  }

  var mul = 1
  var i = 0
  this[offset] = value & 0xFF
  while (++i < byteLength && (mul *= 0x100)) {
    this[offset + i] = (value / mul) & 0xFF
  }

  return offset + byteLength
}

Buffer.prototype.writeUIntBE = function writeUIntBE (value, offset, byteLength, noAssert) {
  value = +value
  offset = offset | 0
  byteLength = byteLength | 0
  if (!noAssert) {
    var maxBytes = Math.pow(2, 8 * byteLength) - 1
    checkInt(this, value, offset, byteLength, maxBytes, 0)
  }

  var i = byteLength - 1
  var mul = 1
  this[offset + i] = value & 0xFF
  while (--i >= 0 && (mul *= 0x100)) {
    this[offset + i] = (value / mul) & 0xFF
  }

  return offset + byteLength
}

Buffer.prototype.writeUInt8 = function writeUInt8 (value, offset, noAssert) {
  value = +value
  offset = offset | 0
  if (!noAssert) checkInt(this, value, offset, 1, 0xff, 0)
  if (!Buffer.TYPED_ARRAY_SUPPORT) value = Math.floor(value)
  this[offset] = (value & 0xff)
  return offset + 1
}

function objectWriteUInt16 (buf, value, offset, littleEndian) {
  if (value < 0) value = 0xffff + value + 1
  for (var i = 0, j = Math.min(buf.length - offset, 2); i < j; ++i) {
    buf[offset + i] = (value & (0xff << (8 * (littleEndian ? i : 1 - i)))) >>>
      (littleEndian ? i : 1 - i) * 8
  }
}

Buffer.prototype.writeUInt16LE = function writeUInt16LE (value, offset, noAssert) {
  value = +value
  offset = offset | 0
  if (!noAssert) checkInt(this, value, offset, 2, 0xffff, 0)
  if (Buffer.TYPED_ARRAY_SUPPORT) {
    this[offset] = (value & 0xff)
    this[offset + 1] = (value >>> 8)
  } else {
    objectWriteUInt16(this, value, offset, true)
  }
  return offset + 2
}

Buffer.prototype.writeUInt16BE = function writeUInt16BE (value, offset, noAssert) {
  value = +value
  offset = offset | 0
  if (!noAssert) checkInt(this, value, offset, 2, 0xffff, 0)
  if (Buffer.TYPED_ARRAY_SUPPORT) {
    this[offset] = (value >>> 8)
    this[offset + 1] = (value & 0xff)
  } else {
    objectWriteUInt16(this, value, offset, false)
  }
  return offset + 2
}

function objectWriteUInt32 (buf, value, offset, littleEndian) {
  if (value < 0) value = 0xffffffff + value + 1
  for (var i = 0, j = Math.min(buf.length - offset, 4); i < j; ++i) {
    buf[offset + i] = (value >>> (littleEndian ? i : 3 - i) * 8) & 0xff
  }
}

Buffer.prototype.writeUInt32LE = function writeUInt32LE (value, offset, noAssert) {
  value = +value
  offset = offset | 0
  if (!noAssert) checkInt(this, value, offset, 4, 0xffffffff, 0)
  if (Buffer.TYPED_ARRAY_SUPPORT) {
    this[offset + 3] = (value >>> 24)
    this[offset + 2] = (value >>> 16)
    this[offset + 1] = (value >>> 8)
    this[offset] = (value & 0xff)
  } else {
    objectWriteUInt32(this, value, offset, true)
  }
  return offset + 4
}

Buffer.prototype.writeUInt32BE = function writeUInt32BE (value, offset, noAssert) {
  value = +value
  offset = offset | 0
  if (!noAssert) checkInt(this, value, offset, 4, 0xffffffff, 0)
  if (Buffer.TYPED_ARRAY_SUPPORT) {
    this[offset] = (value >>> 24)
    this[offset + 1] = (value >>> 16)
    this[offset + 2] = (value >>> 8)
    this[offset + 3] = (value & 0xff)
  } else {
    objectWriteUInt32(this, value, offset, false)
  }
  return offset + 4
}

Buffer.prototype.writeIntLE = function writeIntLE (value, offset, byteLength, noAssert) {
  value = +value
  offset = offset | 0
  if (!noAssert) {
    var limit = Math.pow(2, 8 * byteLength - 1)

    checkInt(this, value, offset, byteLength, limit - 1, -limit)
  }

  var i = 0
  var mul = 1
  var sub = 0
  this[offset] = value & 0xFF
  while (++i < byteLength && (mul *= 0x100)) {
    if (value < 0 && sub === 0 && this[offset + i - 1] !== 0) {
      sub = 1
    }
    this[offset + i] = ((value / mul) >> 0) - sub & 0xFF
  }

  return offset + byteLength
}

Buffer.prototype.writeIntBE = function writeIntBE (value, offset, byteLength, noAssert) {
  value = +value
  offset = offset | 0
  if (!noAssert) {
    var limit = Math.pow(2, 8 * byteLength - 1)

    checkInt(this, value, offset, byteLength, limit - 1, -limit)
  }

  var i = byteLength - 1
  var mul = 1
  var sub = 0
  this[offset + i] = value & 0xFF
  while (--i >= 0 && (mul *= 0x100)) {
    if (value < 0 && sub === 0 && this[offset + i + 1] !== 0) {
      sub = 1
    }
    this[offset + i] = ((value / mul) >> 0) - sub & 0xFF
  }

  return offset + byteLength
}

Buffer.prototype.writeInt8 = function writeInt8 (value, offset, noAssert) {
  value = +value
  offset = offset | 0
  if (!noAssert) checkInt(this, value, offset, 1, 0x7f, -0x80)
  if (!Buffer.TYPED_ARRAY_SUPPORT) value = Math.floor(value)
  if (value < 0) value = 0xff + value + 1
  this[offset] = (value & 0xff)
  return offset + 1
}

Buffer.prototype.writeInt16LE = function writeInt16LE (value, offset, noAssert) {
  value = +value
  offset = offset | 0
  if (!noAssert) checkInt(this, value, offset, 2, 0x7fff, -0x8000)
  if (Buffer.TYPED_ARRAY_SUPPORT) {
    this[offset] = (value & 0xff)
    this[offset + 1] = (value >>> 8)
  } else {
    objectWriteUInt16(this, value, offset, true)
  }
  return offset + 2
}

Buffer.prototype.writeInt16BE = function writeInt16BE (value, offset, noAssert) {
  value = +value
  offset = offset | 0
  if (!noAssert) checkInt(this, value, offset, 2, 0x7fff, -0x8000)
  if (Buffer.TYPED_ARRAY_SUPPORT) {
    this[offset] = (value >>> 8)
    this[offset + 1] = (value & 0xff)
  } else {
    objectWriteUInt16(this, value, offset, false)
  }
  return offset + 2
}

Buffer.prototype.writeInt32LE = function writeInt32LE (value, offset, noAssert) {
  value = +value
  offset = offset | 0
  if (!noAssert) checkInt(this, value, offset, 4, 0x7fffffff, -0x80000000)
  if (Buffer.TYPED_ARRAY_SUPPORT) {
    this[offset] = (value & 0xff)
    this[offset + 1] = (value >>> 8)
    this[offset + 2] = (value >>> 16)
    this[offset + 3] = (value >>> 24)
  } else {
    objectWriteUInt32(this, value, offset, true)
  }
  return offset + 4
}

Buffer.prototype.writeInt32BE = function writeInt32BE (value, offset, noAssert) {
  value = +value
  offset = offset | 0
  if (!noAssert) checkInt(this, value, offset, 4, 0x7fffffff, -0x80000000)
  if (value < 0) value = 0xffffffff + value + 1
  if (Buffer.TYPED_ARRAY_SUPPORT) {
    this[offset] = (value >>> 24)
    this[offset + 1] = (value >>> 16)
    this[offset + 2] = (value >>> 8)
    this[offset + 3] = (value & 0xff)
  } else {
    objectWriteUInt32(this, value, offset, false)
  }
  return offset + 4
}

function checkIEEE754 (buf, value, offset, ext, max, min) {
  if (offset + ext > buf.length) throw new RangeError('Index out of range')
  if (offset < 0) throw new RangeError('Index out of range')
}

function writeFloat (buf, value, offset, littleEndian, noAssert) {
  if (!noAssert) {
    checkIEEE754(buf, value, offset, 4, 3.4028234663852886e+38, -3.4028234663852886e+38)
  }
  ieee754.write(buf, value, offset, littleEndian, 23, 4)
  return offset + 4
}

Buffer.prototype.writeFloatLE = function writeFloatLE (value, offset, noAssert) {
  return writeFloat(this, value, offset, true, noAssert)
}

Buffer.prototype.writeFloatBE = function writeFloatBE (value, offset, noAssert) {
  return writeFloat(this, value, offset, false, noAssert)
}

function writeDouble (buf, value, offset, littleEndian, noAssert) {
  if (!noAssert) {
    checkIEEE754(buf, value, offset, 8, 1.7976931348623157E+308, -1.7976931348623157E+308)
  }
  ieee754.write(buf, value, offset, littleEndian, 52, 8)
  return offset + 8
}

Buffer.prototype.writeDoubleLE = function writeDoubleLE (value, offset, noAssert) {
  return writeDouble(this, value, offset, true, noAssert)
}

Buffer.prototype.writeDoubleBE = function writeDoubleBE (value, offset, noAssert) {
  return writeDouble(this, value, offset, false, noAssert)
}

// copy(targetBuffer, targetStart=0, sourceStart=0, sourceEnd=buffer.length)
Buffer.prototype.copy = function copy (target, targetStart, start, end) {
  if (!start) start = 0
  if (!end && end !== 0) end = this.length
  if (targetStart >= target.length) targetStart = target.length
  if (!targetStart) targetStart = 0
  if (end > 0 && end < start) end = start

  // Copy 0 bytes; we're done
  if (end === start) return 0
  if (target.length === 0 || this.length === 0) return 0

  // Fatal error conditions
  if (targetStart < 0) {
    throw new RangeError('targetStart out of bounds')
  }
  if (start < 0 || start >= this.length) throw new RangeError('sourceStart out of bounds')
  if (end < 0) throw new RangeError('sourceEnd out of bounds')

  // Are we oob?
  if (end > this.length) end = this.length
  if (target.length - targetStart < end - start) {
    end = target.length - targetStart + start
  }

  var len = end - start
  var i

  if (this === target && start < targetStart && targetStart < end) {
    // descending copy from end
    for (i = len - 1; i >= 0; --i) {
      target[i + targetStart] = this[i + start]
    }
  } else if (len < 1000 || !Buffer.TYPED_ARRAY_SUPPORT) {
    // ascending copy from start
    for (i = 0; i < len; ++i) {
      target[i + targetStart] = this[i + start]
    }
  } else {
    Uint8Array.prototype.set.call(
      target,
      this.subarray(start, start + len),
      targetStart
    )
  }

  return len
}

// Usage:
//    buffer.fill(number[, offset[, end]])
//    buffer.fill(buffer[, offset[, end]])
//    buffer.fill(string[, offset[, end]][, encoding])
Buffer.prototype.fill = function fill (val, start, end, encoding) {
  // Handle string cases:
  if (typeof val === 'string') {
    if (typeof start === 'string') {
      encoding = start
      start = 0
      end = this.length
    } else if (typeof end === 'string') {
      encoding = end
      end = this.length
    }
    if (val.length === 1) {
      var code = val.charCodeAt(0)
      if (code < 256) {
        val = code
      }
    }
    if (encoding !== undefined && typeof encoding !== 'string') {
      throw new TypeError('encoding must be a string')
    }
    if (typeof encoding === 'string' && !Buffer.isEncoding(encoding)) {
      throw new TypeError('Unknown encoding: ' + encoding)
    }
  } else if (typeof val === 'number') {
    val = val & 255
  }

  // Invalid ranges are not set to a default, so can range check early.
  if (start < 0 || this.length < start || this.length < end) {
    throw new RangeError('Out of range index')
  }

  if (end <= start) {
    return this
  }

  start = start >>> 0
  end = end === undefined ? this.length : end >>> 0

  if (!val) val = 0

  var i
  if (typeof val === 'number') {
    for (i = start; i < end; ++i) {
      this[i] = val
    }
  } else {
    var bytes = Buffer.isBuffer(val)
      ? val
      : utf8ToBytes(new Buffer(val, encoding).toString())
    var len = bytes.length
    for (i = 0; i < end - start; ++i) {
      this[i + start] = bytes[i % len]
    }
  }

  return this
}

// HELPER FUNCTIONS
// ================

var INVALID_BASE64_RE = /[^+\/0-9A-Za-z-_]/g

function base64clean (str) {
  // Node strips out invalid characters like \n and \t from the string, base64-js does not
  str = stringtrim(str).replace(INVALID_BASE64_RE, '')
  // Node converts strings with length < 2 to ''
  if (str.length < 2) return ''
  // Node allows for non-padded base64 strings (missing trailing ===), base64-js does not
  while (str.length % 4 !== 0) {
    str = str + '='
  }
  return str
}

function stringtrim (str) {
  if (str.trim) return str.trim()
  return str.replace(/^\s+|\s+$/g, '')
}

function toHex (n) {
  if (n < 16) return '0' + n.toString(16)
  return n.toString(16)
}

function utf8ToBytes (string, units) {
  units = units || Infinity
  var codePoint
  var length = string.length
  var leadSurrogate = null
  var bytes = []

  for (var i = 0; i < length; ++i) {
    codePoint = string.charCodeAt(i)

    // is surrogate component
    if (codePoint > 0xD7FF && codePoint < 0xE000) {
      // last char was a lead
      if (!leadSurrogate) {
        // no lead yet
        if (codePoint > 0xDBFF) {
          // unexpected trail
          if ((units -= 3) > -1) bytes.push(0xEF, 0xBF, 0xBD)
          continue
        } else if (i + 1 === length) {
          // unpaired lead
          if ((units -= 3) > -1) bytes.push(0xEF, 0xBF, 0xBD)
          continue
        }

        // valid lead
        leadSurrogate = codePoint

        continue
      }

      // 2 leads in a row
      if (codePoint < 0xDC00) {
        if ((units -= 3) > -1) bytes.push(0xEF, 0xBF, 0xBD)
        leadSurrogate = codePoint
        continue
      }

      // valid surrogate pair
      codePoint = (leadSurrogate - 0xD800 << 10 | codePoint - 0xDC00) + 0x10000
    } else if (leadSurrogate) {
      // valid bmp char, but last char was a lead
      if ((units -= 3) > -1) bytes.push(0xEF, 0xBF, 0xBD)
    }

    leadSurrogate = null

    // encode utf8
    if (codePoint < 0x80) {
      if ((units -= 1) < 0) break
      bytes.push(codePoint)
    } else if (codePoint < 0x800) {
      if ((units -= 2) < 0) break
      bytes.push(
        codePoint >> 0x6 | 0xC0,
        codePoint & 0x3F | 0x80
      )
    } else if (codePoint < 0x10000) {
      if ((units -= 3) < 0) break
      bytes.push(
        codePoint >> 0xC | 0xE0,
        codePoint >> 0x6 & 0x3F | 0x80,
        codePoint & 0x3F | 0x80
      )
    } else if (codePoint < 0x110000) {
      if ((units -= 4) < 0) break
      bytes.push(
        codePoint >> 0x12 | 0xF0,
        codePoint >> 0xC & 0x3F | 0x80,
        codePoint >> 0x6 & 0x3F | 0x80,
        codePoint & 0x3F | 0x80
      )
    } else {
      throw new Error('Invalid code point')
    }
  }

  return bytes
}

function asciiToBytes (str) {
  var byteArray = []
  for (var i = 0; i < str.length; ++i) {
    // Node's code seems to be doing this and not & 0x7F..
    byteArray.push(str.charCodeAt(i) & 0xFF)
  }
  return byteArray
}

function utf16leToBytes (str, units) {
  var c, hi, lo
  var byteArray = []
  for (var i = 0; i < str.length; ++i) {
    if ((units -= 2) < 0) break

    c = str.charCodeAt(i)
    hi = c >> 8
    lo = c % 256
    byteArray.push(lo)
    byteArray.push(hi)
  }

  return byteArray
}

function base64ToBytes (str) {
  return base64.toByteArray(base64clean(str))
}

function blitBuffer (src, dst, offset, length) {
  for (var i = 0; i < length; ++i) {
    if ((i + offset >= dst.length) || (i >= src.length)) break
    dst[i + offset] = src[i]
  }
  return i
}

function isnan (val) {
  return val !== val // eslint-disable-line no-self-compare
}


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/@vue-flow/core/dist/style.css":
/*!************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/@vue-flow/core/dist/style.css ***!
  \************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".vue-flow {\n  position: relative;\n  width: 100%;\n  height: 100%;\n  overflow: hidden;\n  z-index: 0;\n  direction: ltr;\n}\n\n.vue-flow__container {\n  position: absolute;\n  height: 100%;\n  width: 100%;\n  left: 0;\n  top: 0;\n}\n\n.vue-flow__pane {\n  z-index: 1;\n}\n\n.vue-flow__pane.draggable {\n     cursor: grab;\n   }\n\n.vue-flow__pane.selection {\n     cursor: pointer;\n   }\n\n.vue-flow__pane.dragging {\n    cursor: grabbing;\n  }\n\n.vue-flow__transformationpane {\n  transform-origin: 0 0;\n  z-index: 2;\n  pointer-events: none;\n}\n\n.vue-flow__viewport {\n  z-index: 4;\n  overflow: clip;\n}\n\n.vue-flow__selection {\n  z-index: 6;\n}\n\n.vue-flow__edge-labels {\n  position: absolute;\n  width: 100%;\n  height: 100%;\n  pointer-events: none;\n  -webkit-user-select: none;\n     -moz-user-select: none;\n          user-select: none;\n}\n\n.vue-flow__nodesselection-rect:focus,\n.vue-flow__nodesselection-rect:focus-visible {\n  outline: none;\n}\n\n.vue-flow .vue-flow__edges {\n  pointer-events: none;\n  overflow: visible;\n}\n\n.vue-flow__edge-path,\n.vue-flow__connection-path {\n  stroke: #b1b1b7;\n  stroke-width: 1;\n  fill: none;\n}\n\n.vue-flow__edge {\n  pointer-events: visibleStroke;\n  cursor: pointer;\n}\n\n.vue-flow__edge.animated path {\n     stroke-dasharray: 5;\n     animation: dashdraw 0.5s linear infinite;\n   }\n\n.vue-flow__edge.animated path.vue-flow__edge-interaction {\n     stroke-dasharray: none;\n     animation: none;\n   }\n\n.vue-flow__edge.inactive {\n     pointer-events: none;\n   }\n\n.vue-flow__edge.selected,\n  .vue-flow__edge:focus,\n  .vue-flow__edge:focus-visible {\n     outline: none;\n   }\n\n.vue-flow__edge.selected .vue-flow__edge-path,\n  .vue-flow__edge:focus .vue-flow__edge-path,\n  .vue-flow__edge:focus-visible .vue-flow__edge-path {\n     stroke: #555;\n   }\n\n.vue-flow__edge-textwrapper {\n     pointer-events: all;\n   }\n\n.vue-flow__edge-textbg {\n     fill: white;\n   }\n\n.vue-flow__edge-text {\n    pointer-events: none;\n    -webkit-user-select: none;\n       -moz-user-select: none;\n            user-select: none;\n  }\n\n.vue-flow__connection {\n  pointer-events: none;\n}\n\n.vue-flow__connection .animated {\n     stroke-dasharray: 5;\n     animation: dashdraw 0.5s linear infinite;\n   }\n\n.vue-flow__connectionline {\n  z-index: 1001;\n}\n\n.vue-flow__nodes {\n  pointer-events: none;\n  transform-origin: 0 0;\n}\n\n.vue-flow__node-default,\n.vue-flow__node-input,\n.vue-flow__node-output {\n  border-width: 1px;\n  border-style: solid;\n  border-color: #bbb;\n}\n\n.vue-flow__node-default.selected,\n  .vue-flow__node-default:focus,\n  .vue-flow__node-default:focus-visible,\n  .vue-flow__node-input.selected,\n  .vue-flow__node-input:focus,\n  .vue-flow__node-input:focus-visible,\n  .vue-flow__node-output.selected,\n  .vue-flow__node-output:focus,\n  .vue-flow__node-output:focus-visible {\n     outline: none;\n     border: 1px solid #555;\n   }\n\n.vue-flow__node {\n  position: absolute;\n  -webkit-user-select: none;\n     -moz-user-select: none;\n          user-select: none;\n  pointer-events: all;\n  transform-origin: 0 0;\n  box-sizing: border-box;\n  cursor: default;\n}\n\n.vue-flow__node.draggable {\n    cursor: grab;\n    pointer-events: all;\n  }\n\n.vue-flow__node.draggable.dragging {\n      cursor: grabbing;\n    }\n\n.vue-flow__nodesselection {\n  z-index: 3;\n  transform-origin: left top;\n  pointer-events: none;\n}\n\n.vue-flow__nodesselection-rect {\n     position: absolute;\n     pointer-events: all;\n     cursor: grab;\n   }\n\n.vue-flow__nodesselection-rect.dragging {\n          cursor: grabbing;\n        }\n\n.vue-flow__handle {\n  position: absolute;\n  pointer-events: none;\n  min-width: 5px;\n  min-height: 5px;\n}\n\n.vue-flow__handle.connectable {\n     pointer-events: all;\n     cursor: crosshair;\n   }\n\n.vue-flow__handle-bottom {\n     left: 50%;\n     bottom: 0;\n     transform: translate(-50%, 50%);\n   }\n\n.vue-flow__handle-top {\n     left: 50%;\n     top: 0;\n     transform: translate(-50%, -50%);\n   }\n\n.vue-flow__handle-left {\n     top: 50%;\n     left: 0;\n     transform: translate(-50%, -50%);\n   }\n\n.vue-flow__handle-right {\n     top: 50%;\n     right: 0;\n     transform: translate(50%, -50%);\n   }\n\n.vue-flow__edgeupdater {\n  cursor: move;\n  pointer-events: all;\n}\n\n.vue-flow__panel {\n  position: absolute;\n  z-index: 5;\n  margin: 15px;\n}\n\n.vue-flow__panel.top {\n     top: 0;\n   }\n\n.vue-flow__panel.bottom {\n     bottom: 0;\n   }\n\n.vue-flow__panel.left {\n     left: 0;\n   }\n\n.vue-flow__panel.right {\n     right: 0;\n   }\n\n.vue-flow__panel.center {\n     left: 50%;\n     transform: translateX(-50%);\n   }\n\n@keyframes dashdraw {\n  from {\n    stroke-dashoffset: 10;\n  }\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapFlowComponent.vue?vue&type=style&index=0&id=778e46bd&scoped=true&lang=css":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapFlowComponent.vue?vue&type=style&index=0&id=778e46bd&scoped=true&lang=css ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.nap-flow-container[data-v-778e46bd] {\n  height: 100%;\n  display: flex;\n  flex-direction: column;\n}\n.flow-container[data-v-778e46bd] {\n  flex-grow: 1;\n  min-height: 500px;\n  border-radius: 8px;\n  overflow: hidden;\n  background-color: #f5f5f5;\n}\n.legend-dot[data-v-778e46bd] {\n  width: 12px;\n  height: 12px;\n  border-radius: 50%;\n  display: inline-block;\n  margin-right: 8px;\n}\n.spinner[data-v-778e46bd] {\n  border: 4px solid rgba(0, 0, 0, 0.1);\n  width: 36px;\n  height: 36px;\n  border-radius: 50%;\n  border-left-color: #09f;\n  animation: spin-778e46bd 1s linear infinite;\n}\n@keyframes spin-778e46bd {\n0% { transform: rotate(0deg);\n}\n100% { transform: rotate(360deg);\n}\n}\n\n/* NAP Node Styles */\n[data-v-778e46bd] .nap-node {\n  padding: 10px;\n  border-radius: 5px;\n  width: 200px;\n  background-color: white;\n  border: 1px solid #ddd;\n  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);\n}\n[data-v-778e46bd] .nap-node-active {\n  border-left: 5px solid #4CAF50;\n}\n[data-v-778e46bd] .nap-node-inactive {\n  border-left: 5px solid #9E9E9E;\n}\n[data-v-778e46bd] .nap-node-maintenance {\n  border-left: 5px solid #FFC107;\n}\n[data-v-778e46bd] .nap-node-damaged {\n  border-left: 5px solid #F44336;\n}\n[data-v-778e46bd] .nap-node-header {\n  border-bottom: 1px solid #eee;\n  padding-bottom: 8px;\n  margin-bottom: 8px;\n}\n[data-v-778e46bd] .nap-node-title {\n  font-weight: bold;\n  font-size: 14px;\n}\n[data-v-778e46bd] .nap-node-code {\n  font-size: 12px;\n  color: #666;\n}\n[data-v-778e46bd] .nap-node-content {\n  font-size: 12px;\n}\n[data-v-778e46bd] .nap-node-status {\n  margin-bottom: 5px;\n}\n[data-v-778e46bd] .nap-node-occupancy {\n  margin-bottom: 5px;\n}\n[data-v-778e46bd] .nap-node-level {\n  color: #666;\n}\n[data-v-778e46bd] .occupancy-bar {\n  height: 5px;\n  background-color: #eee;\n  border-radius: 2px;\n  margin-top: 2px;\n}\n[data-v-778e46bd] .occupancy-fill {\n  height: 100%;\n  background-color: #2196F3;\n  border-radius: 2px;\n}\n\n/* Custom Controls Styles */\n[data-v-778e46bd] .custom-controls {\n  background-color: white;\n  padding: 5px;\n  border-radius: 4px;\n  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);\n}\n[data-v-778e46bd] .btn-refresh {\n  background-color: #2196F3;\n  color: white;\n  border: none;\n  border-radius: 4px;\n  padding: 5px 10px;\n  font-size: 12px;\n  cursor: pointer;\n  transition: background-color 0.3s;\n}\n[data-v-778e46bd] .btn-refresh:hover {\n  background-color: #0b7dda;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapMapComponent.vue?vue&type=style&index=0&id=1fa29623&scoped=true&lang=css":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapMapComponent.vue?vue&type=style&index=0&id=1fa29623&scoped=true&lang=css ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.nap-map-container[data-v-1fa29623] {\n  height: 100%;\n  display: flex;\n  flex-direction: column;\n}\n.map-container[data-v-1fa29623] {\n  flex-grow: 1;\n  min-height: 500px;\n  border-radius: 8px;\n  overflow: hidden;\n}\n.legend-dot[data-v-1fa29623] {\n  width: 12px;\n  height: 12px;\n  border-radius: 50%;\n  display: inline-block;\n  margin-right: 8px;\n}\n.info-window[data-v-1fa29623] {\n  padding: 8px;\n  max-width: 300px;\n}\n.info-window h3[data-v-1fa29623] {\n  margin-top: 0;\n  margin-bottom: 8px;\n  font-weight: bold;\n}\n.info-window p[data-v-1fa29623] {\n  margin: 4px 0;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Tool.vue?vue&type=style&index=0&id=ef10eebe&lang=css":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Tool.vue?vue&type=style&index=0&id=ef10eebe&lang=css ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.nap-manager-wrapper .h-full {\n  height: 100%;\n  min-height: 600px;\n}\n.nap-manager-wrapper .tab-menu {\n  margin-bottom: 15px;\n  display: flex;\n  gap: 10px;\nbutton {\n    outline: none;\n}\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/runtime/api.js":
/*!*****************************************************!*\
  !*** ./node_modules/css-loader/dist/runtime/api.js ***!
  \*****************************************************/
/***/ ((module) => {

"use strict";


/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
// eslint-disable-next-line func-names
module.exports = function (cssWithMappingToString) {
  var list = []; // return the list of modules as css string

  list.toString = function toString() {
    return this.map(function (item) {
      var content = cssWithMappingToString(item);

      if (item[2]) {
        return "@media ".concat(item[2], " {").concat(content, "}");
      }

      return content;
    }).join("");
  }; // import a list of modules into the list
  // eslint-disable-next-line func-names


  list.i = function (modules, mediaQuery, dedupe) {
    if (typeof modules === "string") {
      // eslint-disable-next-line no-param-reassign
      modules = [[null, modules, ""]];
    }

    var alreadyImportedModules = {};

    if (dedupe) {
      for (var i = 0; i < this.length; i++) {
        // eslint-disable-next-line prefer-destructuring
        var id = this[i][0];

        if (id != null) {
          alreadyImportedModules[id] = true;
        }
      }
    }

    for (var _i = 0; _i < modules.length; _i++) {
      var item = [].concat(modules[_i]);

      if (dedupe && alreadyImportedModules[item[0]]) {
        // eslint-disable-next-line no-continue
        continue;
      }

      if (mediaQuery) {
        if (!item[2]) {
          item[2] = mediaQuery;
        } else {
          item[2] = "".concat(mediaQuery, " and ").concat(item[2]);
        }
      }

      list.push(item);
    }
  };

  return list;
};

/***/ }),

/***/ "./node_modules/ieee754/index.js":
/*!***************************************!*\
  !*** ./node_modules/ieee754/index.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, exports) => {

/*! ieee754. BSD-3-Clause License. Feross Aboukhadijeh <https://feross.org/opensource> */
exports.read = function (buffer, offset, isLE, mLen, nBytes) {
  var e, m
  var eLen = (nBytes * 8) - mLen - 1
  var eMax = (1 << eLen) - 1
  var eBias = eMax >> 1
  var nBits = -7
  var i = isLE ? (nBytes - 1) : 0
  var d = isLE ? -1 : 1
  var s = buffer[offset + i]

  i += d

  e = s & ((1 << (-nBits)) - 1)
  s >>= (-nBits)
  nBits += eLen
  for (; nBits > 0; e = (e * 256) + buffer[offset + i], i += d, nBits -= 8) {}

  m = e & ((1 << (-nBits)) - 1)
  e >>= (-nBits)
  nBits += mLen
  for (; nBits > 0; m = (m * 256) + buffer[offset + i], i += d, nBits -= 8) {}

  if (e === 0) {
    e = 1 - eBias
  } else if (e === eMax) {
    return m ? NaN : ((s ? -1 : 1) * Infinity)
  } else {
    m = m + Math.pow(2, mLen)
    e = e - eBias
  }
  return (s ? -1 : 1) * m * Math.pow(2, e - mLen)
}

exports.write = function (buffer, value, offset, isLE, mLen, nBytes) {
  var e, m, c
  var eLen = (nBytes * 8) - mLen - 1
  var eMax = (1 << eLen) - 1
  var eBias = eMax >> 1
  var rt = (mLen === 23 ? Math.pow(2, -24) - Math.pow(2, -77) : 0)
  var i = isLE ? 0 : (nBytes - 1)
  var d = isLE ? 1 : -1
  var s = value < 0 || (value === 0 && 1 / value < 0) ? 1 : 0

  value = Math.abs(value)

  if (isNaN(value) || value === Infinity) {
    m = isNaN(value) ? 1 : 0
    e = eMax
  } else {
    e = Math.floor(Math.log(value) / Math.LN2)
    if (value * (c = Math.pow(2, -e)) < 1) {
      e--
      c *= 2
    }
    if (e + eBias >= 1) {
      value += rt / c
    } else {
      value += rt * Math.pow(2, 1 - eBias)
    }
    if (value * c >= 2) {
      e++
      c /= 2
    }

    if (e + eBias >= eMax) {
      m = 0
      e = eMax
    } else if (e + eBias >= 1) {
      m = ((value * c) - 1) * Math.pow(2, mLen)
      e = e + eBias
    } else {
      m = value * Math.pow(2, eBias - 1) * Math.pow(2, mLen)
      e = 0
    }
  }

  for (; mLen >= 8; buffer[offset + i] = m & 0xff, i += d, m /= 256, mLen -= 8) {}

  e = (e << mLen) | m
  eLen += mLen
  for (; eLen > 0; buffer[offset + i] = e & 0xff, i += d, e /= 256, eLen -= 8) {}

  buffer[offset + i - d] |= s * 128
}


/***/ }),

/***/ "./node_modules/isarray/index.js":
/*!***************************************!*\
  !*** ./node_modules/isarray/index.js ***!
  \***************************************/
/***/ ((module) => {

var toString = {}.toString;

module.exports = Array.isArray || function (arr) {
  return toString.call(arr) == '[object Array]';
};


/***/ }),

/***/ "./node_modules/process/browser.js":
/*!*****************************************!*\
  !*** ./node_modules/process/browser.js ***!
  \*****************************************/
/***/ ((module) => {

// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapFlowComponent.vue?vue&type=style&index=0&id=778e46bd&scoped=true&lang=css":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapFlowComponent.vue?vue&type=style&index=0&id=778e46bd&scoped=true&lang=css ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapFlowComponent_vue_vue_type_style_index_0_id_778e46bd_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./NapFlowComponent.vue?vue&type=style&index=0&id=778e46bd&scoped=true&lang=css */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapFlowComponent.vue?vue&type=style&index=0&id=778e46bd&scoped=true&lang=css");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapFlowComponent_vue_vue_type_style_index_0_id_778e46bd_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapFlowComponent_vue_vue_type_style_index_0_id_778e46bd_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapMapComponent.vue?vue&type=style&index=0&id=1fa29623&scoped=true&lang=css":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapMapComponent.vue?vue&type=style&index=0&id=1fa29623&scoped=true&lang=css ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapMapComponent_vue_vue_type_style_index_0_id_1fa29623_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./NapMapComponent.vue?vue&type=style&index=0&id=1fa29623&scoped=true&lang=css */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapMapComponent.vue?vue&type=style&index=0&id=1fa29623&scoped=true&lang=css");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapMapComponent_vue_vue_type_style_index_0_id_1fa29623_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapMapComponent_vue_vue_type_style_index_0_id_1fa29623_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Tool.vue?vue&type=style&index=0&id=ef10eebe&lang=css":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Tool.vue?vue&type=style&index=0&id=ef10eebe&lang=css ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Tool_vue_vue_type_style_index_0_id_ef10eebe_lang_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Tool.vue?vue&type=style&index=0&id=ef10eebe&lang=css */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Tool.vue?vue&type=style&index=0&id=ef10eebe&lang=css");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Tool_vue_vue_type_style_index_0_id_ef10eebe_lang_css__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Tool_vue_vue_type_style_index_0_id_ef10eebe_lang_css__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js":
/*!****************************************************************************!*\
  !*** ./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js ***!
  \****************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var isOldIE = function isOldIE() {
  var memo;
  return function memorize() {
    if (typeof memo === 'undefined') {
      // Test for IE <= 9 as proposed by Browserhacks
      // @see http://browserhacks.com/#hack-e71d8692f65334173fee715c222cb805
      // Tests for existence of standard globals is to allow style-loader
      // to operate correctly into non-standard environments
      // @see https://github.com/webpack-contrib/style-loader/issues/177
      memo = Boolean(window && document && document.all && !window.atob);
    }

    return memo;
  };
}();

var getTarget = function getTarget() {
  var memo = {};
  return function memorize(target) {
    if (typeof memo[target] === 'undefined') {
      var styleTarget = document.querySelector(target); // Special case to return head of iframe instead of iframe itself

      if (window.HTMLIFrameElement && styleTarget instanceof window.HTMLIFrameElement) {
        try {
          // This will throw an exception if access to iframe is blocked
          // due to cross-origin restrictions
          styleTarget = styleTarget.contentDocument.head;
        } catch (e) {
          // istanbul ignore next
          styleTarget = null;
        }
      }

      memo[target] = styleTarget;
    }

    return memo[target];
  };
}();

var stylesInDom = [];

function getIndexByIdentifier(identifier) {
  var result = -1;

  for (var i = 0; i < stylesInDom.length; i++) {
    if (stylesInDom[i].identifier === identifier) {
      result = i;
      break;
    }
  }

  return result;
}

function modulesToDom(list, options) {
  var idCountMap = {};
  var identifiers = [];

  for (var i = 0; i < list.length; i++) {
    var item = list[i];
    var id = options.base ? item[0] + options.base : item[0];
    var count = idCountMap[id] || 0;
    var identifier = "".concat(id, " ").concat(count);
    idCountMap[id] = count + 1;
    var index = getIndexByIdentifier(identifier);
    var obj = {
      css: item[1],
      media: item[2],
      sourceMap: item[3]
    };

    if (index !== -1) {
      stylesInDom[index].references++;
      stylesInDom[index].updater(obj);
    } else {
      stylesInDom.push({
        identifier: identifier,
        updater: addStyle(obj, options),
        references: 1
      });
    }

    identifiers.push(identifier);
  }

  return identifiers;
}

function insertStyleElement(options) {
  var style = document.createElement('style');
  var attributes = options.attributes || {};

  if (typeof attributes.nonce === 'undefined') {
    var nonce =  true ? __webpack_require__.nc : 0;

    if (nonce) {
      attributes.nonce = nonce;
    }
  }

  Object.keys(attributes).forEach(function (key) {
    style.setAttribute(key, attributes[key]);
  });

  if (typeof options.insert === 'function') {
    options.insert(style);
  } else {
    var target = getTarget(options.insert || 'head');

    if (!target) {
      throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");
    }

    target.appendChild(style);
  }

  return style;
}

function removeStyleElement(style) {
  // istanbul ignore if
  if (style.parentNode === null) {
    return false;
  }

  style.parentNode.removeChild(style);
}
/* istanbul ignore next  */


var replaceText = function replaceText() {
  var textStore = [];
  return function replace(index, replacement) {
    textStore[index] = replacement;
    return textStore.filter(Boolean).join('\n');
  };
}();

function applyToSingletonTag(style, index, remove, obj) {
  var css = remove ? '' : obj.media ? "@media ".concat(obj.media, " {").concat(obj.css, "}") : obj.css; // For old IE

  /* istanbul ignore if  */

  if (style.styleSheet) {
    style.styleSheet.cssText = replaceText(index, css);
  } else {
    var cssNode = document.createTextNode(css);
    var childNodes = style.childNodes;

    if (childNodes[index]) {
      style.removeChild(childNodes[index]);
    }

    if (childNodes.length) {
      style.insertBefore(cssNode, childNodes[index]);
    } else {
      style.appendChild(cssNode);
    }
  }
}

function applyToTag(style, options, obj) {
  var css = obj.css;
  var media = obj.media;
  var sourceMap = obj.sourceMap;

  if (media) {
    style.setAttribute('media', media);
  } else {
    style.removeAttribute('media');
  }

  if (sourceMap && typeof btoa !== 'undefined') {
    css += "\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))), " */");
  } // For old IE

  /* istanbul ignore if  */


  if (style.styleSheet) {
    style.styleSheet.cssText = css;
  } else {
    while (style.firstChild) {
      style.removeChild(style.firstChild);
    }

    style.appendChild(document.createTextNode(css));
  }
}

var singleton = null;
var singletonCounter = 0;

function addStyle(obj, options) {
  var style;
  var update;
  var remove;

  if (options.singleton) {
    var styleIndex = singletonCounter++;
    style = singleton || (singleton = insertStyleElement(options));
    update = applyToSingletonTag.bind(null, style, styleIndex, false);
    remove = applyToSingletonTag.bind(null, style, styleIndex, true);
  } else {
    style = insertStyleElement(options);
    update = applyToTag.bind(null, style, options);

    remove = function remove() {
      removeStyleElement(style);
    };
  }

  update(obj);
  return function updateStyle(newObj) {
    if (newObj) {
      if (newObj.css === obj.css && newObj.media === obj.media && newObj.sourceMap === obj.sourceMap) {
        return;
      }

      update(obj = newObj);
    } else {
      remove();
    }
  };
}

module.exports = function (list, options) {
  options = options || {}; // Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
  // tags it will allow on a page

  if (!options.singleton && typeof options.singleton !== 'boolean') {
    options.singleton = isOldIE();
  }

  list = list || [];
  var lastIdentifiers = modulesToDom(list, options);
  return function update(newList) {
    newList = newList || [];

    if (Object.prototype.toString.call(newList) !== '[object Array]') {
      return;
    }

    for (var i = 0; i < lastIdentifiers.length; i++) {
      var identifier = lastIdentifiers[i];
      var index = getIndexByIdentifier(identifier);
      stylesInDom[index].references--;
    }

    var newLastIdentifiers = modulesToDom(newList, options);

    for (var _i = 0; _i < lastIdentifiers.length; _i++) {
      var _identifier = lastIdentifiers[_i];

      var _index = getIndexByIdentifier(_identifier);

      if (stylesInDom[_index].references === 0) {
        stylesInDom[_index].updater();

        stylesInDom.splice(_index, 1);
      }
    }

    lastIdentifiers = newLastIdentifiers;
  };
};

/***/ }),

/***/ "./node_modules/vue-loader/dist/exportHelper.js":
/*!******************************************************!*\
  !*** ./node_modules/vue-loader/dist/exportHelper.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, exports) => {

"use strict";

Object.defineProperty(exports, "__esModule", ({ value: true }));
// runtime helper for setting properties on components
// in a tree-shakable way
exports["default"] = (sfc, props) => {
    const target = sfc.__vccOpts || sfc;
    for (const [key, val] of props) {
        target[key] = val;
    }
    return target;
};


/***/ }),

/***/ "./node_modules/vue-toastification/dist/index.mjs":
/*!********************************************************!*\
  !*** ./node_modules/vue-toastification/dist/index.mjs ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   EventBus: () => (/* binding */ EventBus),
/* harmony export */   POSITION: () => (/* binding */ POSITION),
/* harmony export */   TYPE: () => (/* binding */ TYPE),
/* harmony export */   createToastInterface: () => (/* binding */ createToastInterface),
/* harmony export */   "default": () => (/* binding */ src_default),
/* harmony export */   globalEventBus: () => (/* binding */ globalEventBus),
/* harmony export */   provideToast: () => (/* binding */ provideToast),
/* harmony export */   toastInjectionKey: () => (/* binding */ toastInjectionKey),
/* harmony export */   useToast: () => (/* binding */ useToast)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "vue");
var __defProp = Object.defineProperty;
var __getOwnPropSymbols = Object.getOwnPropertySymbols;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __propIsEnum = Object.prototype.propertyIsEnumerable;
var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: true, configurable: true, writable: true, value }) : obj[key] = value;
var __spreadValues = (a, b) => {
  for (var prop in b || (b = {}))
    if (__hasOwnProp.call(b, prop))
      __defNormalProp(a, prop, b[prop]);
  if (__getOwnPropSymbols)
    for (var prop of __getOwnPropSymbols(b)) {
      if (__propIsEnum.call(b, prop))
        __defNormalProp(a, prop, b[prop]);
    }
  return a;
};

// src/index.ts


// src/ts/interface.ts


// src/ts/utils.ts

var isFunction = (value) => typeof value === "function";
var isString = (value) => typeof value === "string";
var isNonEmptyString = (value) => isString(value) && value.trim().length > 0;
var isNumber = (value) => typeof value === "number";
var isUndefined = (value) => typeof value === "undefined";
var isObject = (value) => typeof value === "object" && value !== null;
var isJSX = (obj) => hasProp(obj, "tag") && isNonEmptyString(obj.tag);
var isTouchEvent = (event) => window.TouchEvent && event instanceof TouchEvent;
var isToastComponent = (obj) => hasProp(obj, "component") && isToastContent(obj.component);
var isVueComponent = (c) => isFunction(c) || isObject(c);
var isToastContent = (obj) => !isUndefined(obj) && (isString(obj) || isVueComponent(obj) || isToastComponent(obj));
var isDOMRect = (obj) => isObject(obj) && ["height", "width", "right", "left", "top", "bottom"].every((p) => isNumber(obj[p]));
var hasProp = (obj, propKey) => (isObject(obj) || isFunction(obj)) && propKey in obj;
var getId = ((i) => () => i++)(0);
function getX(event) {
  return isTouchEvent(event) ? event.targetTouches[0].clientX : event.clientX;
}
function getY(event) {
  return isTouchEvent(event) ? event.targetTouches[0].clientY : event.clientY;
}
var removeElement = (el) => {
  if (!isUndefined(el.remove)) {
    el.remove();
  } else if (el.parentNode) {
    el.parentNode.removeChild(el);
  }
};
var getVueComponentFromObj = (obj) => {
  if (isToastComponent(obj)) {
    return getVueComponentFromObj(obj.component);
  }
  if (isJSX(obj)) {
    return (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
      render() {
        return obj;
      }
    });
  }
  return typeof obj === "string" ? obj : (0,vue__WEBPACK_IMPORTED_MODULE_0__.toRaw)((0,vue__WEBPACK_IMPORTED_MODULE_0__.unref)(obj));
};
var normalizeToastComponent = (obj) => {
  if (typeof obj === "string") {
    return obj;
  }
  const props = hasProp(obj, "props") && isObject(obj.props) ? obj.props : {};
  const listeners = hasProp(obj, "listeners") && isObject(obj.listeners) ? obj.listeners : {};
  return { component: getVueComponentFromObj(obj), props, listeners };
};
var isBrowser = () => typeof window !== "undefined";

// src/ts/eventBus.ts
var EventBus = class {
  constructor() {
    this.allHandlers = {};
  }
  getHandlers(eventType) {
    return this.allHandlers[eventType] || [];
  }
  on(eventType, handler) {
    const handlers = this.getHandlers(eventType);
    handlers.push(handler);
    this.allHandlers[eventType] = handlers;
  }
  off(eventType, handler) {
    const handlers = this.getHandlers(eventType);
    handlers.splice(handlers.indexOf(handler) >>> 0, 1);
  }
  emit(eventType, event) {
    const handlers = this.getHandlers(eventType);
    handlers.forEach((handler) => handler(event));
  }
};
var isEventBusInterface = (e) => ["on", "off", "emit"].every((f) => hasProp(e, f) && isFunction(e[f]));

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtToastContainer.vue?vue&type=script


// src/ts/constants.ts
var TYPE;
(function(TYPE2) {
  TYPE2["SUCCESS"] = "success";
  TYPE2["ERROR"] = "error";
  TYPE2["WARNING"] = "warning";
  TYPE2["INFO"] = "info";
  TYPE2["DEFAULT"] = "default";
})(TYPE || (TYPE = {}));
var POSITION;
(function(POSITION2) {
  POSITION2["TOP_LEFT"] = "top-left";
  POSITION2["TOP_CENTER"] = "top-center";
  POSITION2["TOP_RIGHT"] = "top-right";
  POSITION2["BOTTOM_LEFT"] = "bottom-left";
  POSITION2["BOTTOM_CENTER"] = "bottom-center";
  POSITION2["BOTTOM_RIGHT"] = "bottom-right";
})(POSITION || (POSITION = {}));
var EVENTS;
(function(EVENTS2) {
  EVENTS2["ADD"] = "add";
  EVENTS2["DISMISS"] = "dismiss";
  EVENTS2["UPDATE"] = "update";
  EVENTS2["CLEAR"] = "clear";
  EVENTS2["UPDATE_DEFAULTS"] = "update_defaults";
})(EVENTS || (EVENTS = {}));
var VT_NAMESPACE = "Vue-Toastification";

// src/ts/propValidators.ts
var COMMON = {
  type: {
    type: String,
    default: TYPE.DEFAULT
  },
  classNames: {
    type: [String, Array],
    default: () => []
  },
  trueBoolean: {
    type: Boolean,
    default: true
  }
};
var ICON = {
  type: COMMON.type,
  customIcon: {
    type: [String, Boolean, Object, Function],
    default: true
  }
};
var CLOSE_BUTTON = {
  component: {
    type: [String, Object, Function, Boolean],
    default: "button"
  },
  classNames: COMMON.classNames,
  showOnHover: {
    type: Boolean,
    default: false
  },
  ariaLabel: {
    type: String,
    default: "close"
  }
};
var PROGRESS_BAR = {
  timeout: {
    type: [Number, Boolean],
    default: 5e3
  },
  hideProgressBar: {
    type: Boolean,
    default: false
  },
  isRunning: {
    type: Boolean,
    default: false
  }
};
var TRANSITION = {
  transition: {
    type: [Object, String],
    default: `${VT_NAMESPACE}__bounce`
  }
};
var CORE_TOAST = {
  position: {
    type: String,
    default: POSITION.TOP_RIGHT
  },
  draggable: COMMON.trueBoolean,
  draggablePercent: {
    type: Number,
    default: 0.6
  },
  pauseOnFocusLoss: COMMON.trueBoolean,
  pauseOnHover: COMMON.trueBoolean,
  closeOnClick: COMMON.trueBoolean,
  timeout: PROGRESS_BAR.timeout,
  hideProgressBar: PROGRESS_BAR.hideProgressBar,
  toastClassName: COMMON.classNames,
  bodyClassName: COMMON.classNames,
  icon: ICON.customIcon,
  closeButton: CLOSE_BUTTON.component,
  closeButtonClassName: CLOSE_BUTTON.classNames,
  showCloseButtonOnHover: CLOSE_BUTTON.showOnHover,
  accessibility: {
    type: Object,
    default: () => ({
      toastRole: "alert",
      closeButtonLabel: "close"
    })
  },
  rtl: {
    type: Boolean,
    default: false
  },
  eventBus: {
    type: Object,
    required: false,
    default: () => new EventBus()
  }
};
var TOAST = {
  id: {
    type: [String, Number],
    required: true,
    default: 0
  },
  type: COMMON.type,
  content: {
    type: [String, Object, Function],
    required: true,
    default: ""
  },
  onClick: {
    type: Function,
    default: void 0
  },
  onClose: {
    type: Function,
    default: void 0
  }
};
var CONTAINER = {
  container: {
    type: [
      Object,
      Function
    ],
    default: () => document.body
  },
  newestOnTop: COMMON.trueBoolean,
  maxToasts: {
    type: Number,
    default: 20
  },
  transition: TRANSITION.transition,
  toastDefaults: Object,
  filterBeforeCreate: {
    type: Function,
    default: (toast) => toast
  },
  filterToasts: {
    type: Function,
    default: (toasts) => toasts
  },
  containerClassName: COMMON.classNames,
  onMounted: Function,
  shareAppContext: [Boolean, Object]
};
var propValidators_default = {
  CORE_TOAST,
  TOAST,
  CONTAINER,
  PROGRESS_BAR,
  ICON,
  TRANSITION,
  CLOSE_BUTTON
};

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtToast.vue?vue&type=script


// vue:/Users/maronato/Developer/vue-toastification/src/components/VtProgressBar.vue?vue&type=script

var VtProgressBar_default = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "VtProgressBar",
  props: propValidators_default.PROGRESS_BAR,
  data() {
    return {
      hasClass: true
    };
  },
  computed: {
    style() {
      return {
        animationDuration: `${this.timeout}ms`,
        animationPlayState: this.isRunning ? "running" : "paused",
        opacity: this.hideProgressBar ? 0 : 1
      };
    },
    cpClass() {
      return this.hasClass ? `${VT_NAMESPACE}__progress-bar` : "";
    }
  },
  watch: {
    timeout() {
      this.hasClass = false;
      this.$nextTick(() => this.hasClass = true);
    }
  },
  mounted() {
    this.$el.addEventListener("animationend", this.animationEnded);
  },
  beforeUnmount() {
    this.$el.removeEventListener("animationend", this.animationEnded);
  },
  methods: {
    animationEnded() {
      this.$emit("close-toast");
    }
  }
});

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtProgressBar.vue?vue&type=template

function render(_ctx, _cache) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
    style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)(_ctx.style),
    class: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(_ctx.cpClass)
  }, null, 6);
}

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtProgressBar.vue
VtProgressBar_default.render = render;
var VtProgressBar_default2 = VtProgressBar_default;

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtCloseButton.vue?vue&type=script

var VtCloseButton_default = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "VtCloseButton",
  props: propValidators_default.CLOSE_BUTTON,
  computed: {
    buttonComponent() {
      if (this.component !== false) {
        return getVueComponentFromObj(this.component);
      }
      return "button";
    },
    classes() {
      const classes = [`${VT_NAMESPACE}__close-button`];
      if (this.showOnHover) {
        classes.push("show-on-hover");
      }
      return classes.concat(this.classNames);
    }
  }
});

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtCloseButton.vue?vue&type=template

var _hoisted_1 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" \xD7 ");
function render2(_ctx, _cache) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)((0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveDynamicComponent)(_ctx.buttonComponent), (0,vue__WEBPACK_IMPORTED_MODULE_0__.mergeProps)({
    "aria-label": _ctx.ariaLabel,
    class: _ctx.classes
  }, _ctx.$attrs), {
    default: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(() => [
      _hoisted_1
    ]),
    _: 1
  }, 16, ["aria-label", "class"]);
}

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtCloseButton.vue
VtCloseButton_default.render = render2;
var VtCloseButton_default2 = VtCloseButton_default;

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtIcon.vue?vue&type=script


// vue:/Users/maronato/Developer/vue-toastification/src/components/icons/VtSuccessIcon.vue?vue&type=script
var VtSuccessIcon_default = {};

// vue:/Users/maronato/Developer/vue-toastification/src/components/icons/VtSuccessIcon.vue?vue&type=template

var _hoisted_12 = {
  "aria-hidden": "true",
  focusable: "false",
  "data-prefix": "fas",
  "data-icon": "check-circle",
  class: "svg-inline--fa fa-check-circle fa-w-16",
  role: "img",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 512 512"
};
var _hoisted_2 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("path", {
  fill: "currentColor",
  d: "M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"
}, null, -1);
var _hoisted_3 = [
  _hoisted_2
];
function render3(_ctx, _cache) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("svg", _hoisted_12, _hoisted_3);
}

// vue:/Users/maronato/Developer/vue-toastification/src/components/icons/VtSuccessIcon.vue
VtSuccessIcon_default.render = render3;
var VtSuccessIcon_default2 = VtSuccessIcon_default;

// vue:/Users/maronato/Developer/vue-toastification/src/components/icons/VtInfoIcon.vue?vue&type=script
var VtInfoIcon_default = {};

// vue:/Users/maronato/Developer/vue-toastification/src/components/icons/VtInfoIcon.vue?vue&type=template

var _hoisted_13 = {
  "aria-hidden": "true",
  focusable: "false",
  "data-prefix": "fas",
  "data-icon": "info-circle",
  class: "svg-inline--fa fa-info-circle fa-w-16",
  role: "img",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 512 512"
};
var _hoisted_22 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("path", {
  fill: "currentColor",
  d: "M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"
}, null, -1);
var _hoisted_32 = [
  _hoisted_22
];
function render4(_ctx, _cache) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("svg", _hoisted_13, _hoisted_32);
}

// vue:/Users/maronato/Developer/vue-toastification/src/components/icons/VtInfoIcon.vue
VtInfoIcon_default.render = render4;
var VtInfoIcon_default2 = VtInfoIcon_default;

// vue:/Users/maronato/Developer/vue-toastification/src/components/icons/VtWarningIcon.vue?vue&type=script
var VtWarningIcon_default = {};

// vue:/Users/maronato/Developer/vue-toastification/src/components/icons/VtWarningIcon.vue?vue&type=template

var _hoisted_14 = {
  "aria-hidden": "true",
  focusable: "false",
  "data-prefix": "fas",
  "data-icon": "exclamation-circle",
  class: "svg-inline--fa fa-exclamation-circle fa-w-16",
  role: "img",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 512 512"
};
var _hoisted_23 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("path", {
  fill: "currentColor",
  d: "M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zm-248 50c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"
}, null, -1);
var _hoisted_33 = [
  _hoisted_23
];
function render5(_ctx, _cache) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("svg", _hoisted_14, _hoisted_33);
}

// vue:/Users/maronato/Developer/vue-toastification/src/components/icons/VtWarningIcon.vue
VtWarningIcon_default.render = render5;
var VtWarningIcon_default2 = VtWarningIcon_default;

// vue:/Users/maronato/Developer/vue-toastification/src/components/icons/VtErrorIcon.vue?vue&type=script
var VtErrorIcon_default = {};

// vue:/Users/maronato/Developer/vue-toastification/src/components/icons/VtErrorIcon.vue?vue&type=template

var _hoisted_15 = {
  "aria-hidden": "true",
  focusable: "false",
  "data-prefix": "fas",
  "data-icon": "exclamation-triangle",
  class: "svg-inline--fa fa-exclamation-triangle fa-w-18",
  role: "img",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 576 512"
};
var _hoisted_24 = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("path", {
  fill: "currentColor",
  d: "M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"
}, null, -1);
var _hoisted_34 = [
  _hoisted_24
];
function render6(_ctx, _cache) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("svg", _hoisted_15, _hoisted_34);
}

// vue:/Users/maronato/Developer/vue-toastification/src/components/icons/VtErrorIcon.vue
VtErrorIcon_default.render = render6;
var VtErrorIcon_default2 = VtErrorIcon_default;

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtIcon.vue?vue&type=script
var VtIcon_default = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "VtIcon",
  props: propValidators_default.ICON,
  computed: {
    customIconChildren() {
      return hasProp(this.customIcon, "iconChildren") ? this.trimValue(this.customIcon.iconChildren) : "";
    },
    customIconClass() {
      if (isString(this.customIcon)) {
        return this.trimValue(this.customIcon);
      } else if (hasProp(this.customIcon, "iconClass")) {
        return this.trimValue(this.customIcon.iconClass);
      }
      return "";
    },
    customIconTag() {
      if (hasProp(this.customIcon, "iconTag")) {
        return this.trimValue(this.customIcon.iconTag, "i");
      }
      return "i";
    },
    hasCustomIcon() {
      return this.customIconClass.length > 0;
    },
    component() {
      if (this.hasCustomIcon) {
        return this.customIconTag;
      }
      if (isToastContent(this.customIcon)) {
        return getVueComponentFromObj(this.customIcon);
      }
      return this.iconTypeComponent;
    },
    iconTypeComponent() {
      const types = {
        [TYPE.DEFAULT]: VtInfoIcon_default2,
        [TYPE.INFO]: VtInfoIcon_default2,
        [TYPE.SUCCESS]: VtSuccessIcon_default2,
        [TYPE.ERROR]: VtErrorIcon_default2,
        [TYPE.WARNING]: VtWarningIcon_default2
      };
      return types[this.type];
    },
    iconClasses() {
      const classes = [`${VT_NAMESPACE}__icon`];
      if (this.hasCustomIcon) {
        return classes.concat(this.customIconClass);
      }
      return classes;
    }
  },
  methods: {
    trimValue(value, empty = "") {
      return isNonEmptyString(value) ? value.trim() : empty;
    }
  }
});

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtIcon.vue?vue&type=template

function render7(_ctx, _cache) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)((0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveDynamicComponent)(_ctx.component), {
    class: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(_ctx.iconClasses)
  }, {
    default: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(() => [
      (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)((0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.customIconChildren), 1)
    ]),
    _: 1
  }, 8, ["class"]);
}

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtIcon.vue
VtIcon_default.render = render7;
var VtIcon_default2 = VtIcon_default;

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtToast.vue?vue&type=script
var VtToast_default = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "VtToast",
  components: { ProgressBar: VtProgressBar_default2, CloseButton: VtCloseButton_default2, Icon: VtIcon_default2 },
  inheritAttrs: false,
  props: Object.assign({}, propValidators_default.CORE_TOAST, propValidators_default.TOAST),
  data() {
    const data = {
      isRunning: true,
      disableTransitions: false,
      beingDragged: false,
      dragStart: 0,
      dragPos: { x: 0, y: 0 },
      dragRect: {}
    };
    return data;
  },
  computed: {
    classes() {
      const classes = [
        `${VT_NAMESPACE}__toast`,
        `${VT_NAMESPACE}__toast--${this.type}`,
        `${this.position}`
      ].concat(this.toastClassName);
      if (this.disableTransitions) {
        classes.push("disable-transition");
      }
      if (this.rtl) {
        classes.push(`${VT_NAMESPACE}__toast--rtl`);
      }
      return classes;
    },
    bodyClasses() {
      const classes = [
        `${VT_NAMESPACE}__toast-${isString(this.content) ? "body" : "component-body"}`
      ].concat(this.bodyClassName);
      return classes;
    },
    draggableStyle() {
      if (this.dragStart === this.dragPos.x) {
        return {};
      } else if (this.beingDragged) {
        return {
          transform: `translateX(${this.dragDelta}px)`,
          opacity: 1 - Math.abs(this.dragDelta / this.removalDistance)
        };
      } else {
        return {
          transition: "transform 0.2s, opacity 0.2s",
          transform: "translateX(0)",
          opacity: 1
        };
      }
    },
    dragDelta() {
      return this.beingDragged ? this.dragPos.x - this.dragStart : 0;
    },
    removalDistance() {
      if (isDOMRect(this.dragRect)) {
        return (this.dragRect.right - this.dragRect.left) * this.draggablePercent;
      }
      return 0;
    }
  },
  mounted() {
    if (this.draggable) {
      this.draggableSetup();
    }
    if (this.pauseOnFocusLoss) {
      this.focusSetup();
    }
  },
  beforeUnmount() {
    if (this.draggable) {
      this.draggableCleanup();
    }
    if (this.pauseOnFocusLoss) {
      this.focusCleanup();
    }
  },
  methods: {
    hasProp,
    getVueComponentFromObj,
    closeToast() {
      this.eventBus.emit(EVENTS.DISMISS, this.id);
    },
    clickHandler() {
      if (this.onClick) {
        this.onClick(this.closeToast);
      }
      if (this.closeOnClick) {
        if (!this.beingDragged || this.dragStart === this.dragPos.x) {
          this.closeToast();
        }
      }
    },
    timeoutHandler() {
      this.closeToast();
    },
    hoverPause() {
      if (this.pauseOnHover) {
        this.isRunning = false;
      }
    },
    hoverPlay() {
      if (this.pauseOnHover) {
        this.isRunning = true;
      }
    },
    focusPause() {
      this.isRunning = false;
    },
    focusPlay() {
      this.isRunning = true;
    },
    focusSetup() {
      addEventListener("blur", this.focusPause);
      addEventListener("focus", this.focusPlay);
    },
    focusCleanup() {
      removeEventListener("blur", this.focusPause);
      removeEventListener("focus", this.focusPlay);
    },
    draggableSetup() {
      const element = this.$el;
      element.addEventListener("touchstart", this.onDragStart, {
        passive: true
      });
      element.addEventListener("mousedown", this.onDragStart);
      addEventListener("touchmove", this.onDragMove, { passive: false });
      addEventListener("mousemove", this.onDragMove);
      addEventListener("touchend", this.onDragEnd);
      addEventListener("mouseup", this.onDragEnd);
    },
    draggableCleanup() {
      const element = this.$el;
      element.removeEventListener("touchstart", this.onDragStart);
      element.removeEventListener("mousedown", this.onDragStart);
      removeEventListener("touchmove", this.onDragMove);
      removeEventListener("mousemove", this.onDragMove);
      removeEventListener("touchend", this.onDragEnd);
      removeEventListener("mouseup", this.onDragEnd);
    },
    onDragStart(event) {
      this.beingDragged = true;
      this.dragPos = { x: getX(event), y: getY(event) };
      this.dragStart = getX(event);
      this.dragRect = this.$el.getBoundingClientRect();
    },
    onDragMove(event) {
      if (this.beingDragged) {
        event.preventDefault();
        if (this.isRunning) {
          this.isRunning = false;
        }
        this.dragPos = { x: getX(event), y: getY(event) };
      }
    },
    onDragEnd() {
      if (this.beingDragged) {
        if (Math.abs(this.dragDelta) >= this.removalDistance) {
          this.disableTransitions = true;
          this.$nextTick(() => this.closeToast());
        } else {
          setTimeout(() => {
            this.beingDragged = false;
            if (isDOMRect(this.dragRect) && this.pauseOnHover && this.dragRect.bottom >= this.dragPos.y && this.dragPos.y >= this.dragRect.top && this.dragRect.left <= this.dragPos.x && this.dragPos.x <= this.dragRect.right) {
              this.isRunning = false;
            } else {
              this.isRunning = true;
            }
          });
        }
      }
    }
  }
});

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtToast.vue?vue&type=template

var _hoisted_16 = ["role"];
function render8(_ctx, _cache) {
  const _component_Icon = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Icon");
  const _component_CloseButton = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("CloseButton");
  const _component_ProgressBar = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("ProgressBar");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", {
    class: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(_ctx.classes),
    style: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeStyle)(_ctx.draggableStyle),
    onClick: _cache[0] || (_cache[0] = (...args) => _ctx.clickHandler && _ctx.clickHandler(...args)),
    onMouseenter: _cache[1] || (_cache[1] = (...args) => _ctx.hoverPause && _ctx.hoverPause(...args)),
    onMouseleave: _cache[2] || (_cache[2] = (...args) => _ctx.hoverPlay && _ctx.hoverPlay(...args))
  }, [
    _ctx.icon ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Icon, {
      key: 0,
      "custom-icon": _ctx.icon,
      type: _ctx.type
    }, null, 8, ["custom-icon", "type"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true),
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
      role: _ctx.accessibility.toastRole || "alert",
      class: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(_ctx.bodyClasses)
    }, [
      typeof _ctx.content === "string" ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, { key: 0 }, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)((0,vue__WEBPACK_IMPORTED_MODULE_0__.toDisplayString)(_ctx.content), 1)
      ], 2112)) : ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)((0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveDynamicComponent)(_ctx.getVueComponentFromObj(_ctx.content)), (0,vue__WEBPACK_IMPORTED_MODULE_0__.mergeProps)({
        key: 1,
        "toast-id": _ctx.id
      }, _ctx.hasProp(_ctx.content, "props") ? _ctx.content.props : {}, (0,vue__WEBPACK_IMPORTED_MODULE_0__.toHandlers)(_ctx.hasProp(_ctx.content, "listeners") ? _ctx.content.listeners : {}), { onCloseToast: _ctx.closeToast }), null, 16, ["toast-id", "onCloseToast"]))
    ], 10, _hoisted_16),
    !!_ctx.closeButton ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_CloseButton, {
      key: 1,
      component: _ctx.closeButton,
      "class-names": _ctx.closeButtonClassName,
      "show-on-hover": _ctx.showCloseButtonOnHover,
      "aria-label": _ctx.accessibility.closeButtonLabel,
      onClick: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withModifiers)(_ctx.closeToast, ["stop"])
    }, null, 8, ["component", "class-names", "show-on-hover", "aria-label", "onClick"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true),
    _ctx.timeout ? ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_ProgressBar, {
      key: 2,
      "is-running": _ctx.isRunning,
      "hide-progress-bar": _ctx.hideProgressBar,
      timeout: _ctx.timeout,
      onCloseToast: _ctx.timeoutHandler
    }, null, 8, ["is-running", "hide-progress-bar", "timeout", "onCloseToast"])) : (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("v-if", true)
  ], 38);
}

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtToast.vue
VtToast_default.render = render8;
var VtToast_default2 = VtToast_default;

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtTransition.vue?vue&type=script

var VtTransition_default = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "VtTransition",
  props: propValidators_default.TRANSITION,
  emits: ["leave"],
  methods: {
    hasProp,
    leave(el) {
      if (el instanceof HTMLElement) {
        el.style.left = el.offsetLeft + "px";
        el.style.top = el.offsetTop + "px";
        el.style.width = getComputedStyle(el).width;
        el.style.position = "absolute";
      }
    }
  }
});

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtTransition.vue?vue&type=template

function render9(_ctx, _cache) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.TransitionGroup, {
    tag: "div",
    "enter-active-class": _ctx.transition.enter ? _ctx.transition.enter : `${_ctx.transition}-enter-active`,
    "move-class": _ctx.transition.move ? _ctx.transition.move : `${_ctx.transition}-move`,
    "leave-active-class": _ctx.transition.leave ? _ctx.transition.leave : `${_ctx.transition}-leave-active`,
    onLeave: _ctx.leave
  }, {
    default: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(() => [
      (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderSlot)(_ctx.$slots, "default")
    ]),
    _: 3
  }, 8, ["enter-active-class", "move-class", "leave-active-class", "onLeave"]);
}

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtTransition.vue
VtTransition_default.render = render9;
var VtTransition_default2 = VtTransition_default;

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtToastContainer.vue?vue&type=script
var VtToastContainer_default = (0,vue__WEBPACK_IMPORTED_MODULE_0__.defineComponent)({
  name: "VueToastification",
  devtools: {
    hide: true
  },
  components: { Toast: VtToast_default2, VtTransition: VtTransition_default2 },
  props: Object.assign({}, propValidators_default.CORE_TOAST, propValidators_default.CONTAINER, propValidators_default.TRANSITION),
  data() {
    const data = {
      count: 0,
      positions: Object.values(POSITION),
      toasts: {},
      defaults: {}
    };
    return data;
  },
  computed: {
    toastArray() {
      return Object.values(this.toasts);
    },
    filteredToasts() {
      return this.defaults.filterToasts(this.toastArray);
    }
  },
  beforeMount() {
    const events = this.eventBus;
    events.on(EVENTS.ADD, this.addToast);
    events.on(EVENTS.CLEAR, this.clearToasts);
    events.on(EVENTS.DISMISS, this.dismissToast);
    events.on(EVENTS.UPDATE, this.updateToast);
    events.on(EVENTS.UPDATE_DEFAULTS, this.updateDefaults);
    this.defaults = this.$props;
  },
  mounted() {
    this.setup(this.container);
  },
  methods: {
    async setup(container) {
      if (isFunction(container)) {
        container = await container();
      }
      removeElement(this.$el);
      container.appendChild(this.$el);
    },
    setToast(props) {
      if (!isUndefined(props.id)) {
        this.toasts[props.id] = props;
      }
    },
    addToast(params) {
      params.content = normalizeToastComponent(params.content);
      const props = Object.assign({}, this.defaults, params.type && this.defaults.toastDefaults && this.defaults.toastDefaults[params.type], params);
      const toast = this.defaults.filterBeforeCreate(props, this.toastArray);
      toast && this.setToast(toast);
    },
    dismissToast(id) {
      const toast = this.toasts[id];
      if (!isUndefined(toast) && !isUndefined(toast.onClose)) {
        toast.onClose();
      }
      delete this.toasts[id];
    },
    clearToasts() {
      Object.keys(this.toasts).forEach((id) => {
        this.dismissToast(id);
      });
    },
    getPositionToasts(position) {
      const toasts = this.filteredToasts.filter((toast) => toast.position === position).slice(0, this.defaults.maxToasts);
      return this.defaults.newestOnTop ? toasts.reverse() : toasts;
    },
    updateDefaults(update) {
      if (!isUndefined(update.container)) {
        this.setup(update.container);
      }
      this.defaults = Object.assign({}, this.defaults, update);
    },
    updateToast({
      id,
      options,
      create
    }) {
      if (this.toasts[id]) {
        if (options.timeout && options.timeout === this.toasts[id].timeout) {
          options.timeout++;
        }
        this.setToast(Object.assign({}, this.toasts[id], options));
      } else if (create) {
        this.addToast(Object.assign({}, { id }, options));
      }
    },
    getClasses(position) {
      const classes = [`${VT_NAMESPACE}__container`, position];
      return classes.concat(this.defaults.containerClassName);
    }
  }
});

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtToastContainer.vue?vue&type=template

function render10(_ctx, _cache) {
  const _component_Toast = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Toast");
  const _component_VtTransition = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("VtTransition");
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", null, [
    ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)(_ctx.positions, (pos) => {
      return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)("div", { key: pos }, [
        (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_VtTransition, {
          transition: _ctx.defaults.transition,
          class: (0,vue__WEBPACK_IMPORTED_MODULE_0__.normalizeClass)(_ctx.getClasses(pos))
        }, {
          default: (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(() => [
            ((0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(true), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderList)(_ctx.getPositionToasts(pos), (toast) => {
              return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createBlock)(_component_Toast, (0,vue__WEBPACK_IMPORTED_MODULE_0__.mergeProps)({
                key: toast.id
              }, toast), null, 16);
            }), 128))
          ]),
          _: 2
        }, 1032, ["transition", "class"])
      ]);
    }), 128))
  ]);
}

// vue:/Users/maronato/Developer/vue-toastification/src/components/VtToastContainer.vue
VtToastContainer_default.render = render10;
var VtToastContainer_default2 = VtToastContainer_default;

// src/ts/interface.ts
var buildInterface = (globalOptions = {}, mountContainer = true) => {
  const events = globalOptions.eventBus = globalOptions.eventBus || new EventBus();
  if (mountContainer) {
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.nextTick)(() => {
      const app = (0,vue__WEBPACK_IMPORTED_MODULE_0__.createApp)(VtToastContainer_default2, __spreadValues({}, globalOptions));
      const component = app.mount(document.createElement("div"));
      const onMounted = globalOptions.onMounted;
      if (!isUndefined(onMounted)) {
        onMounted(component, app);
      }
      if (globalOptions.shareAppContext) {
        const baseApp = globalOptions.shareAppContext;
        if (baseApp === true) {
          console.warn(`[${VT_NAMESPACE}] App to share context with was not provided.`);
        } else {
          app._context.components = baseApp._context.components;
          app._context.directives = baseApp._context.directives;
          app._context.mixins = baseApp._context.mixins;
          app._context.provides = baseApp._context.provides;
          app.config.globalProperties = baseApp.config.globalProperties;
        }
      }
    });
  }
  const toast = (content, options) => {
    const props = Object.assign({}, { id: getId(), type: TYPE.DEFAULT }, options, {
      content
    });
    events.emit(EVENTS.ADD, props);
    return props.id;
  };
  toast.clear = () => events.emit(EVENTS.CLEAR, void 0);
  toast.updateDefaults = (update) => {
    events.emit(EVENTS.UPDATE_DEFAULTS, update);
  };
  toast.dismiss = (id) => {
    events.emit(EVENTS.DISMISS, id);
  };
  function updateToast(id, { content, options }, create = false) {
    const opt = Object.assign({}, options, { content });
    events.emit(EVENTS.UPDATE, {
      id,
      options: opt,
      create
    });
  }
  toast.update = updateToast;
  toast.success = (content, options) => toast(content, Object.assign({}, options, { type: TYPE.SUCCESS }));
  toast.info = (content, options) => toast(content, Object.assign({}, options, { type: TYPE.INFO }));
  toast.error = (content, options) => toast(content, Object.assign({}, options, { type: TYPE.ERROR }));
  toast.warning = (content, options) => toast(content, Object.assign({}, options, { type: TYPE.WARNING }));
  return toast;
};

// src/index.ts
var createMockToastInterface = () => {
  const toast = () => console.warn(`[${VT_NAMESPACE}] This plugin does not support SSR!`);
  return new Proxy(toast, {
    get() {
      return toast;
    }
  });
};
function createToastInterface(optionsOrEventBus) {
  if (!isBrowser()) {
    return createMockToastInterface();
  }
  if (isEventBusInterface(optionsOrEventBus)) {
    return buildInterface({ eventBus: optionsOrEventBus }, false);
  }
  return buildInterface(optionsOrEventBus, true);
}
var toastInjectionKey = Symbol("VueToastification");
var globalEventBus = new EventBus();
var VueToastificationPlugin = (App, options) => {
  if ((options == null ? void 0 : options.shareAppContext) === true) {
    options.shareAppContext = App;
  }
  const inter = createToastInterface(__spreadValues({
    eventBus: globalEventBus
  }, options));
  App.provide(toastInjectionKey, inter);
};
var provideToast = (options) => {
  const toast = createToastInterface(options);
  if ((0,vue__WEBPACK_IMPORTED_MODULE_0__.getCurrentInstance)()) {
    (0,vue__WEBPACK_IMPORTED_MODULE_0__.provide)(toastInjectionKey, toast);
  }
};
var useToast = (eventBus) => {
  if (eventBus) {
    return createToastInterface(eventBus);
  }
  const toast = (0,vue__WEBPACK_IMPORTED_MODULE_0__.getCurrentInstance)() ? (0,vue__WEBPACK_IMPORTED_MODULE_0__.inject)(toastInjectionKey, void 0) : void 0;
  return toast ? toast : createToastInterface(globalEventBus);
};
var src_default = VueToastificationPlugin;



/***/ }),

/***/ "./resources/css/tool.css":
/*!********************************!*\
  !*** ./resources/css/tool.css ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/js/components/NapFlowComponent.vue":
/*!******************************************************!*\
  !*** ./resources/js/components/NapFlowComponent.vue ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _NapFlowComponent_vue_vue_type_template_id_778e46bd_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NapFlowComponent.vue?vue&type=template&id=778e46bd&scoped=true */ "./resources/js/components/NapFlowComponent.vue?vue&type=template&id=778e46bd&scoped=true");
/* harmony import */ var _NapFlowComponent_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NapFlowComponent.vue?vue&type=script&lang=js */ "./resources/js/components/NapFlowComponent.vue?vue&type=script&lang=js");
/* harmony import */ var _NapFlowComponent_vue_vue_type_style_index_0_id_778e46bd_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./NapFlowComponent.vue?vue&type=style&index=0&id=778e46bd&scoped=true&lang=css */ "./resources/js/components/NapFlowComponent.vue?vue&type=style&index=0&id=778e46bd&scoped=true&lang=css");
/* harmony import */ var _node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;


const __exports__ = /*#__PURE__*/(0,_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__["default"])(_NapFlowComponent_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_NapFlowComponent_vue_vue_type_template_id_778e46bd_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render],['__scopeId',"data-v-778e46bd"],['__file',"resources/js/components/NapFlowComponent.vue"]])
/* hot reload */
if (false) // removed by dead control flow
{}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/NapFlowComponent.vue?vue&type=script&lang=js":
/*!******************************************************************************!*\
  !*** ./resources/js/components/NapFlowComponent.vue?vue&type=script&lang=js ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapFlowComponent_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapFlowComponent_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./NapFlowComponent.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapFlowComponent.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/NapFlowComponent.vue?vue&type=style&index=0&id=778e46bd&scoped=true&lang=css":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/components/NapFlowComponent.vue?vue&type=style&index=0&id=778e46bd&scoped=true&lang=css ***!
  \**************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapFlowComponent_vue_vue_type_style_index_0_id_778e46bd_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./NapFlowComponent.vue?vue&type=style&index=0&id=778e46bd&scoped=true&lang=css */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapFlowComponent.vue?vue&type=style&index=0&id=778e46bd&scoped=true&lang=css");


/***/ }),

/***/ "./resources/js/components/NapFlowComponent.vue?vue&type=template&id=778e46bd&scoped=true":
/*!************************************************************************************************!*\
  !*** ./resources/js/components/NapFlowComponent.vue?vue&type=template&id=778e46bd&scoped=true ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapFlowComponent_vue_vue_type_template_id_778e46bd_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapFlowComponent_vue_vue_type_template_id_778e46bd_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./NapFlowComponent.vue?vue&type=template&id=778e46bd&scoped=true */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapFlowComponent.vue?vue&type=template&id=778e46bd&scoped=true");


/***/ }),

/***/ "./resources/js/components/NapMapComponent.vue":
/*!*****************************************************!*\
  !*** ./resources/js/components/NapMapComponent.vue ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _NapMapComponent_vue_vue_type_template_id_1fa29623_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NapMapComponent.vue?vue&type=template&id=1fa29623&scoped=true */ "./resources/js/components/NapMapComponent.vue?vue&type=template&id=1fa29623&scoped=true");
/* harmony import */ var _NapMapComponent_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NapMapComponent.vue?vue&type=script&lang=js */ "./resources/js/components/NapMapComponent.vue?vue&type=script&lang=js");
/* harmony import */ var _NapMapComponent_vue_vue_type_style_index_0_id_1fa29623_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./NapMapComponent.vue?vue&type=style&index=0&id=1fa29623&scoped=true&lang=css */ "./resources/js/components/NapMapComponent.vue?vue&type=style&index=0&id=1fa29623&scoped=true&lang=css");
/* harmony import */ var _node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;


const __exports__ = /*#__PURE__*/(0,_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__["default"])(_NapMapComponent_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_NapMapComponent_vue_vue_type_template_id_1fa29623_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render],['__scopeId',"data-v-1fa29623"],['__file',"resources/js/components/NapMapComponent.vue"]])
/* hot reload */
if (false) // removed by dead control flow
{}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/components/NapMapComponent.vue?vue&type=script&lang=js":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/NapMapComponent.vue?vue&type=script&lang=js ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapMapComponent_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapMapComponent_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./NapMapComponent.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapMapComponent.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/components/NapMapComponent.vue?vue&type=style&index=0&id=1fa29623&scoped=true&lang=css":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/components/NapMapComponent.vue?vue&type=style&index=0&id=1fa29623&scoped=true&lang=css ***!
  \*************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapMapComponent_vue_vue_type_style_index_0_id_1fa29623_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./NapMapComponent.vue?vue&type=style&index=0&id=1fa29623&scoped=true&lang=css */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapMapComponent.vue?vue&type=style&index=0&id=1fa29623&scoped=true&lang=css");


/***/ }),

/***/ "./resources/js/components/NapMapComponent.vue?vue&type=template&id=1fa29623&scoped=true":
/*!***********************************************************************************************!*\
  !*** ./resources/js/components/NapMapComponent.vue?vue&type=template&id=1fa29623&scoped=true ***!
  \***********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapMapComponent_vue_vue_type_template_id_1fa29623_scoped_true__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NapMapComponent_vue_vue_type_template_id_1fa29623_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./NapMapComponent.vue?vue&type=template&id=1fa29623&scoped=true */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/components/NapMapComponent.vue?vue&type=template&id=1fa29623&scoped=true");


/***/ }),

/***/ "./resources/js/pages/Tool.vue":
/*!*************************************!*\
  !*** ./resources/js/pages/Tool.vue ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Tool_vue_vue_type_template_id_ef10eebe__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Tool.vue?vue&type=template&id=ef10eebe */ "./resources/js/pages/Tool.vue?vue&type=template&id=ef10eebe");
/* harmony import */ var _Tool_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Tool.vue?vue&type=script&lang=js */ "./resources/js/pages/Tool.vue?vue&type=script&lang=js");
/* harmony import */ var _Tool_vue_vue_type_style_index_0_id_ef10eebe_lang_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Tool.vue?vue&type=style&index=0&id=ef10eebe&lang=css */ "./resources/js/pages/Tool.vue?vue&type=style&index=0&id=ef10eebe&lang=css");
/* harmony import */ var _node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;


const __exports__ = /*#__PURE__*/(0,_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_3__["default"])(_Tool_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Tool_vue_vue_type_template_id_ef10eebe__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/pages/Tool.vue"]])
/* hot reload */
if (false) // removed by dead control flow
{}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/pages/Tool.vue?vue&type=script&lang=js":
/*!*************************************************************!*\
  !*** ./resources/js/pages/Tool.vue?vue&type=script&lang=js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Tool_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Tool_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Tool.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Tool.vue?vue&type=script&lang=js");
 

/***/ }),

/***/ "./resources/js/pages/Tool.vue?vue&type=style&index=0&id=ef10eebe&lang=css":
/*!*********************************************************************************!*\
  !*** ./resources/js/pages/Tool.vue?vue&type=style&index=0&id=ef10eebe&lang=css ***!
  \*********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_dist_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Tool_vue_vue_type_style_index_0_id_ef10eebe_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../node_modules/vue-loader/dist/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Tool.vue?vue&type=style&index=0&id=ef10eebe&lang=css */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/dist/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Tool.vue?vue&type=style&index=0&id=ef10eebe&lang=css");


/***/ }),

/***/ "./resources/js/pages/Tool.vue?vue&type=template&id=ef10eebe":
/*!*******************************************************************!*\
  !*** ./resources/js/pages/Tool.vue?vue&type=template&id=ef10eebe ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   render: () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Tool_vue_vue_type_template_id_ef10eebe__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Tool_vue_vue_type_template_id_ef10eebe__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Tool.vue?vue&type=template&id=ef10eebe */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/pages/Tool.vue?vue&type=template&id=ef10eebe");


/***/ }),

/***/ "./resources/js/tool.js":
/*!******************************!*\
  !*** ./resources/js/tool.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _pages_Tool__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./pages/Tool */ "./resources/js/pages/Tool.vue");

Nova.inertia('NapManager', _pages_Tool__WEBPACK_IMPORTED_MODULE_0__["default"]);

/***/ }),

/***/ "vue":
/*!**********************!*\
  !*** external "Vue" ***!
  \**********************/
/***/ ((module) => {

"use strict";
module.exports = Vue;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			id: moduleId,
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/global */
/******/ 	(() => {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/tool": 0,
/******/ 			"css/tool": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkispgo_nap_manager"] = self["webpackChunkispgo_nap_manager"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/nonce */
/******/ 	(() => {
/******/ 		__webpack_require__.nc = undefined;
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/tool"], () => (__webpack_require__("./resources/js/tool.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/tool"], () => (__webpack_require__("./resources/css/tool.css")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;