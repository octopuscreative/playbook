!function t(e,n,i){function r(s,c){if(!n[s]){if(!e[s]){var a="function"==typeof require&&require;if(!c&&a)return a(s,!0);if(o)return o(s,!0);var u=new Error("Cannot find module '"+s+"'");throw u.code="MODULE_NOT_FOUND",u}var p=n[s]={exports:{}};e[s][0].call(p.exports,function(t){var n=e[s][1][t];return r(n?n:t)},p,p.exports,t,e,n,i)}return n[s].exports}for(var o="function"==typeof require&&require,s=0;s<i.length;s++)r(i[s]);return r}({1:[function(t,e,n){(function(e){"use strict";var n=window.$;e.cp_url=function(t){return t=Statamic.siteRoot+"/"+Statamic.cpRoot+"/"+t,t.replace(/\/+/g,"/")},e.resource_url=function(t){return t=Statamic.resourceUrl+"/"+t,t.replace(/\/+/g,"/")},e.get_from_segment=function(t){return Statamic.urlPath.split("/").splice(t).join("/")},e.format_input_options=function(t){if("string"==typeof t[0])return t;var e=[];return _.each(t,function(t,n,i){e.push({value:n,text:t})}),e},e.dd=function(t){console.log(t)},t("./l10n/l10n"),t("./vendor/sticky"),n(document).ready(function(){n(".sticky").sticky({topSpacing:0,className:"stuck",widthFromWrapper:!1})})}).call(this,"undefined"!=typeof global?global:"undefined"!=typeof self?self:"undefined"!=typeof window?window:{})},{"./l10n/l10n":2,"./vendor/sticky":4}],2:[function(t,e,n){(function(e){"use strict";e.Lang=t("./lang"),e.translate=function(t,e){return Lang.get(t,e)},e.translate_choice=function(t,e,n){return Lang.choice(t,e,n)},Lang.setMessages(Statamic.translations)}).call(this,"undefined"!=typeof global?global:"undefined"!=typeof self?self:"undefined"!=typeof window?window:{})},{"./lang":3}],3:[function(t,e,n){"use strict";var i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol?"symbol":typeof t};!function(t,r){"function"==typeof define&&define.amd?define([],r):"object"===("undefined"==typeof n?"undefined":i(n))?e.exports=new(r()):t.Lang=new(r())}(void 0,function(){var t={defaultLocale:"en"},e=function(e){e=e||{},this.defaultLocale=e.defaultLocale||t.defaultLocale};return e.prototype.setMessages=function(t){this.messages=t},e.prototype.get=function(t,e){if(!this.has(t))return t;var n=this._getMessage(t,e);return null===n?t:(e&&(n=this._applyReplacements(n,e)),n)},e.prototype.has=function(t){return"string"==typeof t&&this.messages?null!==this._getMessage(t):!1},e.prototype.choice=function(t,e,n){n="undefined"!=typeof n?n:{},n.count=e;var i=this.get(t,n);if(null===i||void 0===i)return i;for(var r=i.split("|"),o=[],s=/{\d+}\s(.+)|\[\d+,\d+\]\s(.+)|\[\d+,Inf\]\s(.+)/,c=0;c<r.length;c++)if(r[c]=r[c].trim(),s.test(r[c])){var a=r[c].split(/\s/);o.push(a.shift()),r[c]=a.join(" ")}if(1===r.length)return i;for(var c=0;c<o.length;c++)if(this._testInterval(e,o[c]))return r[c];return 1===e?r[0]:r[1]},e.prototype.setLocale=function(t){this.locale=t},e.prototype.getLocale=function(){return this.locale||this.defaultLocale},e.prototype._parseKey=function(t){if("string"!=typeof t)return null;var e=t.split(".");return{source:this.getLocale()+"."+e[0],entries:e.slice(1)}},e.prototype._getMessage=function(t){if(t=this._parseKey(t),void 0===this.messages[t.source])return null;for(var e=this.messages[t.source];t.entries.length&&(e=e[t.entries.shift()]););return"string"!=typeof e?null:e},e.prototype._applyReplacements=function(t,e){for(var n in e)t=t.split(":"+n).join(e[n]);return t},e.prototype._testInterval=function(t,e){return!1},e})},{}],4:[function(t,e,n){"use strict";var i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol?"symbol":typeof t};!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):"object"===("undefined"==typeof e?"undefined":i(e))&&e.exports?e.exports=t(window.$):t(jQuery)}(function(t){var e=Array.prototype.slice,n=Array.prototype.splice,r={topSpacing:0,bottomSpacing:0,className:"is-sticky",wrapperClassName:"sticky-wrapper",center:!1,getWidthFrom:"",widthFromWrapper:!0,responsiveWidth:!1},o=t(window),s=t(document),c=[],a=o.height(),u=function(){for(var e=o.scrollTop(),n=s.height(),i=n-a,r=e>i?i-e:0,u=0,p=c.length;p>u;u++){var l=c[u],f=l.stickyWrapper.offset().top,d=f-l.topSpacing-r;if(l.stickyWrapper.css("height",l.stickyElement.outerHeight()),d>=e)null!==l.currentTop&&(l.stickyElement.css({width:"",position:"",top:""}),l.stickyElement.parent().removeClass(l.className),l.stickyElement.trigger("sticky-end",[l]),l.currentTop=null);else{var h=n-l.stickyElement.outerHeight()-l.topSpacing-l.bottomSpacing-e-r;if(0>h?h+=l.topSpacing:h=l.topSpacing,l.currentTop!==h){var y;l.getWidthFrom?y=t(l.getWidthFrom).width()||null:l.widthFromWrapper&&(y=l.stickyWrapper.width()),null==y&&(y=l.stickyElement.width()),l.stickyElement.css("width",y).css("position","fixed").css("top",h),l.stickyElement.parent().addClass(l.className),null===l.currentTop?l.stickyElement.trigger("sticky-start",[l]):l.stickyElement.trigger("sticky-update",[l]),l.currentTop===l.topSpacing&&l.currentTop>h||null===l.currentTop&&h<l.topSpacing?l.stickyElement.trigger("sticky-bottom-reached",[l]):null!==l.currentTop&&h===l.topSpacing&&l.currentTop<h&&l.stickyElement.trigger("sticky-bottom-unreached",[l]),l.currentTop=h}var g=l.stickyWrapper.parent(),m=l.stickyElement.offset().top+l.stickyElement.outerHeight()>=g.offset().top+g.outerHeight()&&l.stickyElement.offset().top<=l.topSpacing;m?l.stickyElement.css("position","absolute").css("top","").css("bottom",0):l.stickyElement.css("position","fixed").css("top",h).css("bottom","")}}},p=function(){a=o.height();for(var e=0,n=c.length;n>e;e++){var i=c[e],r=null;i.getWidthFrom?i.responsiveWidth&&(r=t(i.getWidthFrom).width()):i.widthFromWrapper&&(r=i.stickyWrapper.width()),null!=r&&i.stickyElement.css("width",r)}},l={init:function(e){var n=t.extend({},r,e);return this.each(function(){var e=t(this),i=e.attr("id"),o=i?i+"-"+r.wrapperClassName:r.wrapperClassName,s=t("<div></div>").attr("id",o).addClass(n.wrapperClassName);e.wrapAll(s);var a=e.parent();n.center&&a.css({width:e.outerWidth(),marginLeft:"auto",marginRight:"auto"}),"right"===e.css("float")&&e.css({"float":"none"}).parent().css({"float":"right"}),n.stickyElement=e,n.stickyWrapper=a,n.currentTop=null,c.push(n),l.setWrapperHeight(this),l.setupChangeListeners(this)})},setWrapperHeight:function(e){var n=t(e),i=n.parent();i&&i.css("height",n.outerHeight())},setupChangeListeners:function(t){if(window.MutationObserver){var e=new window.MutationObserver(function(e){(e[0].addedNodes.length||e[0].removedNodes.length)&&l.setWrapperHeight(t)});e.observe(t,{subtree:!0,childList:!0})}else t.addEventListener("DOMNodeInserted",function(){l.setWrapperHeight(t)},!1),t.addEventListener("DOMNodeRemoved",function(){l.setWrapperHeight(t)},!1)},update:u,unstick:function(e){return this.each(function(){for(var e=this,i=t(e),r=-1,o=c.length;o-- >0;)c[o].stickyElement.get(0)===e&&(n.call(c,o,1),r=o);-1!==r&&(i.unwrap(),i.css({width:"",position:"",top:"","float":""}))})}};window.addEventListener?(window.addEventListener("scroll",u,!1),window.addEventListener("resize",p,!1)):window.attachEvent&&(window.attachEvent("onscroll",u),window.attachEvent("onresize",p)),t.fn.sticky=function(n){return l[n]?l[n].apply(this,e.call(arguments,1)):"object"!==("undefined"==typeof n?"undefined":i(n))&&n?void t.error("Method "+n+" does not exist on jQuery.sticky"):l.init.apply(this,arguments)},t.fn.unstick=function(n){return l[n]?l[n].apply(this,e.call(arguments,1)):"object"!==("undefined"==typeof n?"undefined":i(n))&&n?void t.error("Method "+n+" does not exist on jQuery.sticky"):l.unstick.apply(this,arguments)},t(function(){setTimeout(u,0)})})},{}]},{},[1]);