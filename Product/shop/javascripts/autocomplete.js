;(function(d){var k=d.scrollTo=function(a,i,e){d(window).scrollTo(a,i,e)};k.defaults={axis:'xy',duration:parseFloat(d.fn.jquery)>=1.3?0:1};k.window=function(a){return d(window)._scrollable()};d.fn._scrollable=function(){return this.map(function(){var a=this,i=!a.nodeName||d.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!i)return a;var e=(a.contentWindow||a).document||a.ownerDocument||a;return d.browser.safari||e.compatMode=='BackCompat'?e.body:e.documentElement})};d.fn.scrollTo=function(n,j,b){if(typeof j=='object'){b=j;j=0}if(typeof b=='function')b={onAfter:b};if(n=='max')n=9e9;b=d.extend({},k.defaults,b);j=j||b.speed||b.duration;b.queue=b.queue&&b.axis.length>1;if(b.queue)j/=2;b.offset=p(b.offset);b.over=p(b.over);return this._scrollable().each(function(){var q=this,r=d(q),f=n,s,g={},u=r.is('html,body');switch(typeof f){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)){f=p(f);break}f=d(f,this);case'object':if(f.is||f.style)s=(f=d(f)).offset()}d.each(b.axis.split(''),function(a,i){var e=i=='x'?'Left':'Top',h=e.toLowerCase(),c='scroll'+e,l=q[c],m=k.max(q,i);if(s){g[c]=s[h]+(u?0:l-r.offset()[h]);if(b.margin){g[c]-=parseInt(f.css('margin'+e))||0;g[c]-=parseInt(f.css('border'+e+'Width'))||0}g[c]+=b.offset[h]||0;if(b.over[h])g[c]+=f[i=='x'?'width':'height']()*b.over[h]}else{var o=f[h];g[c]=o.slice&&o.slice(-1)=='%'?parseFloat(o)/100*m:o}if(/^\d+$/.test(g[c]))g[c]=g[c]<=0?0:Math.min(g[c],m);if(!a&&b.queue){if(l!=g[c])t(b.onAfterFirst);delete g[c]}});t(b.onAfter);function t(a){r.animate(g,j,b.easing,a&&function(){a.call(this,n,b)})}}).end()};k.max=function(a,i){var e=i=='x'?'Width':'Height',h='scroll'+e;if(!d(a).is('html,body'))return a[h]-d(a)[e.toLowerCase()]();var c='client'+e,l=a.ownerDocument.documentElement,m=a.ownerDocument.body;return Math.max(l[h],m[h])-Math.min(l[c],m[c])};function p(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);
;(function(a){var r=a.fn.domManip,d="_tmplitem",q=/^[^<]*(<[\w\W]+>)[^>]*$|\{\{\! /,b={},f={},e,p={key:0,data:{}},i=0,c=0,l=[];function g(g,d,h,e){var c={data:e||(e===0||e===false)?e:d?d.data:{},_wrap:d?d._wrap:null,tmpl:null,parent:d||null,nodes:[],calls:u,nest:w,wrap:x,html:v,update:t};g&&a.extend(c,g,{nodes:[],parent:d});if(h){c.tmpl=h;c._ctnt=c._ctnt||c.tmpl(a,c);c.key=++i;(l.length?f:b)[i]=c}return c}a.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(f,d){a.fn[f]=function(n){var g=[],i=a(n),k,h,m,l,j=this.length===1&&this[0].parentNode;e=b||{};if(j&&j.nodeType===11&&j.childNodes.length===1&&i.length===1){i[d](this[0]);g=this}else{for(h=0,m=i.length;h<m;h++){c=h;k=(h>0?this.clone(true):this).get();a(i[h])[d](k);g=g.concat(k)}c=0;g=this.pushStack(g,f,i.selector)}l=e;e=null;a.tmpl.complete(l);return g}});a.fn.extend({tmpl:function(d,c,b){return a.tmpl(this[0],d,c,b)},tmplItem:function(){return a.tmplItem(this[0])},template:function(b){return a.template(b,this[0])},domManip:function(d,m,k){if(d[0]&&a.isArray(d[0])){var g=a.makeArray(arguments),h=d[0],j=h.length,i=0,f;while(i<j&&!(f=a.data(h[i++],"tmplItem")));if(f&&c)g[2]=function(b){a.tmpl.afterManip(this,b,k)};r.apply(this,g)}else r.apply(this,arguments);c=0;!e&&a.tmpl.complete(b);return this}});a.extend({tmpl:function(d,h,e,c){var i,k=!c;if(k){c=p;d=a.template[d]||a.template(null,d);f={}}else if(!d){d=c.tmpl;b[c.key]=c;c.nodes=[];c.wrapped&&n(c,c.wrapped);return a(j(c,null,c.tmpl(a,c)))}if(!d)return[];if(typeof h==="function")h=h.call(c||{});e&&e.wrapped&&n(e,e.wrapped);i=a.isArray(h)?a.map(h,function(a){return a?g(e,c,d,a):null}):[g(e,c,d,h)];return k?a(j(c,null,i)):i},tmplItem:function(b){var c;if(b instanceof a)b=b[0];while(b&&b.nodeType===1&&!(c=a.data(b,"tmplItem"))&&(b=b.parentNode));return c||p},template:function(c,b){if(b){if(typeof b==="string")b=o(b);else if(b instanceof a)b=b[0]||{};if(b.nodeType)b=a.data(b,"tmpl")||a.data(b,"tmpl",o(b.innerHTML));return typeof c==="string"?(a.template[c]=b):b}return c?typeof c!=="string"?a.template(null,c):a.template[c]||a.template(null,q.test(c)?c:a(c)):null},encode:function(a){return(""+a).split("<").join("&lt;").split(">").join("&gt;").split('"').join("&#34;").split("'").join("&#39;")}});a.extend(a.tmpl,{tag:{tmpl:{_default:{$2:"null"},open:"if($notnull_1){__=__.concat($item.nest($1,$2));}"},wrap:{_default:{$2:"null"},open:"$item.calls(__,$1,$2);__=[];",close:"call=$item.calls();__=call._.concat($item.wrap(call,__));"},each:{_default:{$2:"$index, $value"},open:"if($notnull_1){$.each($1a,function($2){with(this){",close:"}});}"},"if":{open:"if(($notnull_1) && $1a){",close:"}"},"else":{_default:{$1:"true"},open:"}else if(($notnull_1) && $1a){"},html:{open:"if($notnull_1){__.push($1a);}"},"=":{_default:{$1:"$data"},open:"if($notnull_1){__.push($.encode($1a));}"},"!":{open:""}},complete:function(){b={}},afterManip:function(f,b,d){var e=b.nodeType===11?a.makeArray(b.childNodes):b.nodeType===1?[b]:[];d.call(f,b);m(e);c++}});function j(e,g,f){var b,c=f?a.map(f,function(a){return typeof a==="string"?e.key?a.replace(/(<\w+)(?=[\s>])(?![^>]*_tmplitem)([^>]*)/g,"$1 "+d+'="'+e.key+'" $2'):a:j(a,e,a._ctnt)}):e;if(g)return c;c=c.join("");c.replace(/^\s*([^<\s][^<]*)?(<[\w\W]+>)([^>]*[^>\s])?\s*$/,function(f,c,e,d){b=a(e).get();m(b);if(c)b=k(c).concat(b);if(d)b=b.concat(k(d))});return b?b:k(c)}function k(c){var b=document.createElement("div");b.innerHTML=c;return a.makeArray(b.childNodes)}function o(b){return new Function("jQuery","$item","var $=jQuery,call,__=[],$data=$item.data;with($data){__.push('"+a.trim(b).replace(/([\\'])/g,"\\$1").replace(/[\r\t\n]/g," ").replace(/\$\{([^\}]*)\}/g,"{{= $1}}").replace(/\{\{(\/?)(\w+|.)(?:\(((?:[^\}]|\}(?!\}))*?)?\))?(?:\s+(.*?)?)?(\(((?:[^\}]|\}(?!\}))*?)\))?\s*\}\}/g,function(m,l,k,g,b,c,d){var j=a.tmpl.tag[k],i,e,f;if(!j)throw"Unknown template tag: "+k;i=j._default||[];if(c&&!/\w$/.test(b)){b+=c;c=""}if(b){b=h(b);d=d?","+h(d)+")":c?")":"";e=c?b.indexOf(".")>-1?b+h(c):"("+b+").call($item"+d:b;f=c?e:"(typeof("+b+")==='function'?("+b+").call($item):("+b+"))"}else f=e=i.$1||"null";g=h(g);return"');"+j[l?"close":"open"].split("$notnull_1").join(b?"typeof("+b+")!=='undefined' && ("+b+")!=null":"true").split("$1a").join(f).split("$1").join(e).split("$2").join(g||i.$2||"")+"__.push('"})+"');}return __;")}function n(c,b){c._wrap=j(c,true,a.isArray(b)?b:[q.test(b)?b:a(b).html()]).join("")}function h(a){return a?a.replace(/\\'/g,"'").replace(/\\\\/g,"\\"):null}function s(b){var a=document.createElement("div");a.appendChild(b.cloneNode(true));return a.innerHTML}function m(o){var n="_"+c,k,j,l={},e,p,h;for(e=0,p=o.length;e<p;e++){if((k=o[e]).nodeType!==1)continue;j=k.getElementsByTagName("*");for(h=j.length-1;h>=0;h--)m(j[h]);m(k)}function m(j){var p,h=j,k,e,m;if(m=j.getAttribute(d)){while(h.parentNode&&(h=h.parentNode).nodeType===1&&!(p=h.getAttribute(d)));if(p!==m){h=h.parentNode?h.nodeType===11?0:h.getAttribute(d)||0:0;if(!(e=b[m])){e=f[m];e=g(e,b[h]||f[h]);e.key=++i;b[i]=e}c&&o(m)}j.removeAttribute(d)}else if(c&&(e=a.data(j,"tmplItem"))){o(e.key);b[e.key]=e;h=a.data(j.parentNode,"tmplItem");h=h?h.key:0}if(e){k=e;while(k&&k.key!=h){k.nodes.push(j);k=k.parent}delete e._ctnt;delete e._wrap;a.data(j,"tmplItem",e)}function o(a){a=a+n;e=l[a]=l[a]||g(e,b[e.parent.key+n]||e.parent)}}}function u(a,d,c,b){if(!a)return l.pop();l.push({_:a,tmpl:d,item:this,data:c,options:b})}function w(d,c,b){return a.tmpl(a.template(d),c,b,this)}function x(b,d){var c=b.options||{};c.wrapped=d;return a.tmpl(a.template(b.tmpl),b.data,c,b.item)}function v(d,c){var b=this._wrap;return a.map(a(a.isArray(b)?b.join(""):b).filter(d||"*"),function(a){return c?a.innerText||a.textContent:a.outerHTML||s(a)})}function t(){var b=this.nodes;a.tmpl(null,null,null,this).insertBefore(b[0]);a(b).remove()}})(jQuery);

var autocompletegif = '<div id="autocompletegif" class="autocompletegif_cnt"><img class="autocompletegif" src="/images/autocomplete.gif"></div>';
var AutoComplete = {
    defaults : {
        url : '/',
        element_selector : '',
        delay : 100,
        template : ''
    },
    element : '',
    container : '',
    searchResult : [],
    timeOut : false,
    reservedKeys : [13,37,39,38,40],
    searchText : '',
    request : false,
    
    activeItem : [],
    items : [],
    
    init : function(options){
        AutoComplete.defaults = $.extend({}, AutoComplete.defaults, options);
        
        AutoComplete.element = $(AutoComplete.defaults.element_selector);
        AutoComplete.element.wrap('<div id="ac_container"></div>');
        AutoComplete.element.attr({autocomplete:'off'});
        AutoComplete.container = $('#ac_container');
        AutoComplete.container.append('<div id="ac_list" class="cf"></div>');
        AutoComplete.list = $('#ac_list');

        AutoComplete.element.on('keyup', function(e){
            if(!AutoComplete.reservedKeyPressed(e.keyCode)){
                AutoComplete.search();
            }else{
                AutoComplete.keyControl(e.keyCode);
            }
        });
        AutoComplete.element.on('keydown', function(e){
            if(!AutoComplete.reservedKeyPressed(e.keyCode)){
                if(AutoComplete.searchTextChanged()){
                    $('#ac_list').hide(300, function(){
                        $('#ac_list').empty();
                    });
                }
            }
        });
        AutoComplete.element.on('focus', function(){
            AutoComplete.showResult();
        });
        $(document).on('click', function(e){
            if($(e.target).parents('#ac_container').length == 0 && $(e.target)[0] != $('.popup_bg')[0] && $(e.target).parents('#popup').length == 0){
                AutoComplete.hideResult();
            }else if($('#ac_list').is(':visible')){
                AutoComplete.element.trigger('focus');
            }
        });
    },
    search:function(){
        AutoComplete.searchText = AutoComplete.element.val();
        AutoComplete.searchText = AutoComplete.searchText.trim();
        if(AutoComplete.searchText != '' && AutoComplete.searchText.length > 2){
            if($('#autocompletegif').length == 0 && AutoComplete.searchTextChanged()) AutoComplete.container.append(autocompletegif);
            if(AutoComplete.defaults.timeOut) clearTimeout(AutoComplete.defaults.timeOut);
            AutoComplete.defaults.timeOut = setTimeout('AutoComplete.doSearch()',AutoComplete.delay);
        }
    },
    doSearch:function(){
        if(AutoComplete.request != false) AutoComplete.request.abort();
        if(AutoComplete.searchText.length > 2 && AutoComplete.searchTextChanged())
        {
            AutoComplete.cachedSearchText = AutoComplete.searchText;
            AutoComplete.request = $.ajax({
                type:'GET',
                url:AutoComplete.defaults.url,
                dataType:'JSON',
                data:{
                    search_text:AutoComplete.searchText
                },
                success:function(data){
                    if(data.found_cnt>0){
                        AutoComplete.searchResult = data;
                        AutoComplete.showResult();
                    }else{
                        $("#autocompletegif").remove();
                    }
                }
            });
        }else $("#autocompletegif").remove();
    },
    hideResult:function(){
        if($('#ac_list').is(':visible')){
            $('#ac_list').slideUp(300,function(){
                AutoComplete.emptyList();
            });
        }
    },
    emptyList:function(){
        $('#ac_list').empty();
        AutoComplete.items = [];
        AutoComplete.activeItem = [];
    },
    showResult:function(){
        $("#autocompletegif").remove();
        AutoComplete.emptyList();
        if(AutoComplete.searchResult.data){
            var item = '';
            $.map(AutoComplete.searchResult.data, function(val, index){
                item = $($(AutoComplete.defaults.template).tmpl(val));
                AutoComplete.items.push(item);
                AutoComplete.list.append(item);
            });
            
            var show_all_item = '<div class="ac_item_container_text cf">';
                show_all_item+= '   <a style="float:right; font-size:11px;" href="javascript:;" onclick="AutoComplete.navigateTo(\'/search/'+AutoComplete.searchText+'\')">';
                show_all_item+= '       Megjelenítés &gt;&gt;';
                show_all_item+= '   </a>';
                show_all_item+= '   <span>Találatok : '+AutoComplete.searchResult.found_cnt+' darab</span>';
                show_all_item+= '</div>';
                
            AutoComplete.items.push(show_all_item);
            AutoComplete.list.append(show_all_item);

            $(AutoComplete.items).each(function(key, val){
                $(this).hover(
                    function(){
                        AutoComplete.setActive(this);
                    },
                    function(){
                        AutoComplete.setInActive(this);
                    }
                );
                $(this).addClass(((key%2 == 0)?'ac_item_even':'ac_item_odd'));
            });

            AutoComplete.list.slideDown(300);            
        }
    },
    setInActive:function(){
        $(AutoComplete.items).each(function(key, val){
            $(this).removeClass('ac_active');
        });
    },
    setActive:function(item){
        AutoComplete.activeItem = item;
        $(item).addClass('ac_active');
    },
    keyControl:function(keycode)
    {
        var list_items = $('.ac_item_container');
        if(list_items != []){
            var ActivePos = $.inArray(AutoComplete.activeItem,list_items), href;
            AutoComplete.setInActive(AutoComplete.activeItem);
            switch(keycode)
            {
                case 13:
                    var href = $(list_items[ActivePos]).find('a:first').attr('href');
                    if(href != undefined || typeof(href) != 'undefined'){
                        window.location.href=href;
                    }
                break;                
                case 37:
                case 38:
                    if(ActivePos == -1)
                    {
                        AutoComplete.setActive(list_items.last()[0]);
                    }else{
                        AutoComplete.setActive(list_items[ActivePos-1]);
                    }
                    AutoComplete.scroll(-1);
                break;
                case 39:
                case 40:
                    if(ActivePos == -1){
                        AutoComplete.setActive(list_items.first()[0]);
                    }else{

                        AutoComplete.setActive(list_items[ActivePos+1]);
                    }
                    AutoComplete.scroll(1);
                break;
            }
        }
    },
    searchTextChanged:function(){
        return (AutoComplete.cachedSearchText != AutoComplete.searchText) ? true : false;
    },
    reservedKeyPressed:function(keyCode){
        return ($.inArray(keyCode, AutoComplete.reservedKeys) == -1) ? false : true;
    },
    scroll:function(irany){
        var scrolltoelement, add = 40;
        if(typeof(AutoComplete.activeItem) != 'undefined'){
            scrolltoelement = AutoComplete.activeItem;
        }else{
            scrolltoelement = AutoComplete.element;
            irany = -1;
        }
        add *= irany;
        
        var outerHeight = $(scrolltoelement).outerHeight(true);
        var offsetTop = $(scrolltoelement).offset().top;
        var to;
        if(window.pageYOffset + window.innerHeight <= offsetTop + 40){
            to = ((outerHeight + offsetTop) - window.innerHeight) + add;
            $('body').scrollTo( to, 300, {easing:'swing', queue:true, axis:'xy'} );
        }else if(window.pageYOffset > offsetTop + 40){
            to = offsetTop + add;
            $('body').scrollTo( to, 300, {easing:'swing', queue:true, axis:'xy'} );
        }
    },
    navigateTo:function(url){
        window.location.href = url;
    }
};