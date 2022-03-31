!function(t){var e={};function n(r){if(e[r])return e[r].exports;var o=e[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)n.d(r,o,function(e){return t[e]}.bind(null,o));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="./",n(n.s="CEol")}({CEol:function(t,e,n){"use strict";function r(){var t={},e=!0,n=0;"[object Boolean]"===Object.prototype.toString.call(arguments[0])&&(e=arguments[0],n++);for(var o=function(n){for(var o in n)n.hasOwnProperty(o)&&(e&&"[object Object]"===Object.prototype.toString.call(n[o])?t[o]=r(t[o],n[o]):t[o]=n[o])};n<arguments.length;n++)o(arguments[n]);return t}function o(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var n=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null==n)return;var r,o,i=[],a=!0,u=!1;try{for(n=n.call(t);!(a=(r=n.next()).done)&&(i.push(r.value),!e||i.length!==e);a=!0);}catch(t){u=!0,o=t}finally{try{a||null==n.return||n.return()}finally{if(u)throw o}}return i}(t,e)||function(t,e){if(!t)return;if("string"==typeof t)return i(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);"Object"===n&&t.constructor&&(n=t.constructor.name);if("Map"===n||"Set"===n)return Array.from(t);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return i(t,e)}(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function i(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,r=new Array(e);n<e;n++)r[n]=t[n];return r}function a(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,r)}return n}function u(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?a(Object(n),!0).forEach((function(e){s(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):a(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function s(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function l(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}n.r(e);var c=function(){function t(e,n){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this._defaults={requestMethod:"GET",requestHeaders:{Accept:"application/json","Content-Type":"application/json; charset=utf-8"},requestArguments:{id:{type:"integer",active:!0,value:0},strategy:{type:"string",active:!1,value:""}},baseUrl:window.location.origin,nonce:null,status:[]},this.options=r(this._defaults,n,!0),this.url=e,this.init()}var e,n,i;return e=t,(n=[{key:"init",value:function(){if(null!==this.url&&null===this.options.nonce)throw new Error("No Nonce was given.")}},{key:"fetch",value:function(t){function e(e){return t.apply(this,arguments)}return e.toString=function(){return t.toString()},e}((function(t){return Object.keys(this.options.requestArguments).length>=1&&(this.url=this._buildRequestArguments(this.url,t)),fetch(this.url,this._buildRequest()).then((function(t){return t.json()})).then((function(t){return t}))}))},{key:"_buildRequest",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,e=this.options.requestHeaders,n={"X-WP-Nonce":this.options.nonce};return{headers:e=u(u({},e),n),method:null!=t?t:this.options.requestMethod}}},{key:"_buildRequestArguments",value:function(t,e){for(var n=new URL(t,this.options.baseUrl),i=r(this.options.requestArguments,{id:{value:e}}),a=0,u=Object.entries(i);a<u.length;a++){var s=o(u[a],2),l=s[0],c=s[1];c.active&&n.searchParams.append(l,c.value)}return n.toString()}}])&&l(e.prototype,n),i&&l(e,i),Object.defineProperty(e,"prototype",{writable:!1}),t}();function f(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}var d=function(){function t(e){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.statusMap=e||[]}var e,n,r;return e=t,(n=[{key:"match",value:function(t){var e=this,n=[];return t.forEach((function(t){n[t]=e.get(t)})),n}},{key:"get",value:function(t){return this.exists(t)?this.statusMap[t]:this.statusMap[void 0]}},{key:"set",value:function(t,e){this.statusMap[t]=e}},{key:"exists",value:function(t){return t in this.statusMap}},{key:"unset",value:function(t){delete this.statusMap[t]}}])&&f(e.prototype,n),r&&f(e,r),Object.defineProperty(e,"prototype",{writable:!1}),t}();function h(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}var b=function(){function t(e,n){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.lineBreak=e||"",this.listItem=n||""}var e,n,r;return e=t,(n=[{key:"render",value:function(t){var e=this;return Object.keys(t).map((function(n,r){var o=t[n];switch(n){case"synced":return'<b class="is-synced">'.concat(o,"</b>");case"not-synced":return'<b class="not-synced">'.concat(o,"</b>");case"syncable":case"not-syncable":return"".concat(e.lineBreak,"<small><b>").concat(o,"</b></small>");case"product-not-found":return'<span class="na">'.concat(o,"</span>");default:return"".concat(e.lineBreak,"<small> ").concat(e.listItem," ").concat(o,"</small>")}})).join("")}}])&&h(e.prototype,n),r&&h(e,r),Object.defineProperty(e,"prototype",{writable:!1}),t}();function y(t,e){var n="undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!n){if(Array.isArray(t)||(n=function(t,e){if(!t)return;if("string"==typeof t)return p(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);"Object"===n&&t.constructor&&(n=t.constructor.name);if("Map"===n||"Set"===n)return Array.from(t);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return p(t,e)}(t))||e&&t&&"number"==typeof t.length){n&&(t=n);var r=0,o=function(){};return{s:o,n:function(){return r>=t.length?{done:!0}:{done:!1,value:t[r++]}},e:function(t){throw t},f:o}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,a=!0,u=!1;return{s:function(){n=n.call(t)},n:function(){var t=n.next();return a=t.done,t},e:function(t){u=!0,i=t},f:function(){try{a||null==n.return||n.return()}finally{if(u)throw i}}}}function p(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,r=new Array(e);n<e;n++)r[n]=t[n];return r}function v(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}var m=function(){function t(e,n,r,o){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this.el=e||null,this.classToWatch=n,this.classAddedCallback=r,this.classRemovedCallback=o,this.observer=null,this.lastClassState=this.el.classList.contains(this.classToWatch),this.init()}var e,n,r;return e=t,(n=[{key:"init",value:function(){var t=this;if(null===this.el)throw new Error("No valid Element was given.");this.observer=new MutationObserver((function(e){var n,r=y(e);try{for(r.s();!(n=r.n()).done;){var o=n.value;if("attributes"===o.type&&"class"===o.attributeName){var i=o.target.classList.contains(t.classToWatch);t.lastClassState!==i&&(t.lastClassState=i,i?t.classAddedCallback():t.classRemovedCallback())}}}catch(t){r.e(t)}finally{r.f()}}))}},{key:"observe",value:function(){this.observer.observe(this.el,{attributes:!0})}},{key:"disconnect",value:function(){this.observer.disconnect()}}])&&v(e.prototype,n),r&&v(e,r),Object.defineProperty(e,"prototype",{writable:!1}),t}();function g(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}var w=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t)}var e,n,r;return e=t,r=[{key:"debounce",value:function(t,e){var n,r=arguments.length>2&&void 0!==arguments[2]&&arguments[2];return function(){for(var o=arguments.length,i=new Array(o),a=0;a<o;a++)i[a]=arguments[a];r&&!n&&setTimeout(t.bind.apply(t,[t].concat(i)),0),clearTimeout(n),n=setTimeout(t.bind.apply(t,[t].concat(i)),e)}}}],(n=null)&&g(e.prototype,n),r&&g(e,r),Object.defineProperty(e,"prototype",{writable:!1}),t}();function O(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}var j=function(){function t(e,n,o,i,a){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t),this._defaults={loadContentDebounceTime:100,isHidden:!1},this.options=r(this._defaults,a,!0),this.el=i||null,this.productId=null,this.fetcher=e,this.matcher=n,this.renderer=o,this.init()}var e,n,o;return e=t,(n=[{key:"init",value:function(){if(null===this.el)throw new Error("No valid Element was given.");if(null===this.el.dataset.syncStatusId)throw new Error("No ProductId for Element was setted.");this.productId=parseInt(this.el.dataset.syncStatusId),this.assignDebouncedOnLoadContent(),this.registerEvents()}},{key:"registerEvents",value:function(){this.options.isHidden||window.addEventListener("load",this.debounceOnLoadContent,!1)}},{key:"assignDebouncedOnLoadContent",value:function(){this.debounceOnLoadContent=w.debounce(this.loadContent.bind(this),this.options.loadContentDebounceTime)}},{key:"loadContent",value:function(){var t=this;this.fetcher.fetch(this.productId).then((function(e){var n=t.matcher.match(e.result.error);t.el.innerHTML=t.renderer.render(n)}))}}])&&O(e.prototype,n),o&&O(e,o),Object.defineProperty(e,"prototype",{writable:!1}),t}(),P=document.getElementById("zettle_synced")||null;if(!P)throw new Error("PayPal Zettle Column not found.");var k=document.querySelectorAll('*[data-sync-status="true"]')||null;if(!k||k.length<1)throw new Error("Product Elements not found.");if("undefined"==typeof zettleProductValidation)throw Error("Url and Configuration Variable are not defined.");k.forEach((function(t){null!==t.dataset.id?t.status=new j(new c(zettleProductValidation.url,{nonce:zettleProductValidation.nonce,requestArguments:zettleProductValidation.requestArguments}),new d(zettleProductValidation.status),new b("<br>"," - "),t,{isHidden:P.classList.contains("hidden")}):t.status=null})),new m(P,"hidden",(function(){}),(function(){k.forEach((function(t){if(!(!t.status instanceof j)){var e=t.querySelector(".loader");t.contains(e)&&t.status.loadContent()}}))})).observe()}});