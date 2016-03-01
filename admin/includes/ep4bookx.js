!function(t,e){"object"==typeof exports?e(exports):"function"==typeof define&&define.amd?define(["exports"],e):e(t)}(this,function(t){return t.Nanobar=function(){"use strict";var t,e,i,s,o,h,n={width:"100%",height:"10px",zIndex:9999,top:"0"},r={width:0,height:"100%",clear:"both",transition:"height .3s"};return t=function(t,e){var i;for(i in e)t.style[i]=e[i];t.style["float"]="left"},s=function(){var t=this,e=this.width-this.here;.1>e&&e>-.1?(o.call(this,this.here),this.moving=!1,100==this.width&&(this.el.style.height=0,setTimeout(function(){t.cont.el.removeChild(t.el)},300))):(o.call(this,this.width-e/4),setTimeout(function(){t.go()},16))},o=function(t){this.width=t,this.el.style.width=this.width+"%"},h=function(){var t=new e(this);this.bars.unshift(t)},e=function(e){this.el=document.createElement("div"),this.el.style.backgroundColor=e.opts.bg,this.width=0,this.here=0,this.moving=!1,this.cont=e,t(this.el,r),e.el.appendChild(this.el)},e.prototype.go=function(t){t?(this.here=t,this.moving||(this.moving=!0,s.call(this))):this.moving&&s.call(this)},i=function(e){var i,s=this.opts=e||{};s.bg=s.bg||"#000",this.bars=[],i=this.el=document.createElement("div"),t(this.el,n),s.id&&(i.id=s.id),s.height&&(i.style.height=s.height),i.style.position=s.target?"relative":"fixed",s.target?s.target.insertBefore(i,s.target.firstChild):document.getElementsByTagName("body")[0].appendChild(i),h.call(this)},i.prototype.go=function(t){this.bars[0].go(t),100==t&&h.call(this)},i.prototype.setHeight=function(t){"string"==typeof t&&(this.bars[0].el.style.height=t)},i.prototype.setColor=function(t){"string"==typeof t&&(this.bars[0].el.style["background-color"]=t)},i.prototype.setState=function(t){var e=null;if("string"==typeof t)switch(t.toLowerCase()){case"success":e="#5cb85c";break;case"info":e="#5bc0de";break;case"warning":e="#f0ad4e";break;case"danger":e="#d9534f"}e&&this.setColor(e)},i}(),t.Nanobar});


/* Collapse 
function toggle() {
    var ele = document.getElementById("toggleText");
    var text = document.getElementById("displayText");
    if(ele.style.display == "block") {
            ele.style.display = "none";
        text.innerHTML = "<h3><i>Show Export Options</i></h3>";
    }
    else {
        ele.style.display = "block";
        text.innerHTML = '<h3><i>Hide</i></h3>';
    }
} 
*/