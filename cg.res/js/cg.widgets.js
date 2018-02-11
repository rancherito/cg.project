//ion-ios-arrow-right

var cg = {
  widgets:{},
  objects:{
    ListILink:{}
    //,CardView_icon:{},
    //Label_icon:{}
  }};
  cg.v = function v(get){
    for (var i in cg.objects) if(typeof cg.objects[i][get] !== 'undefined'){ return cg.objects[i][get];}
    return null;
  }
cg.widgets.ListILink = function ListILink(from){
  var base = typeof from !== 'undefined' ? from : $("<div class='ListILink'><div class='ListILink_content'><a class='ListILink-header'><span class='ListILink-title'>Title</span><i class='ListILink-icon'></i></a><div class='ListILink-body'></div></div></div>");
  base.listItems = [];
  var title = $(base.find('.ListILink-title'));
  var icon = $(base.find('.ListILink-icon'));
  var body = $(base.find('.ListILink-body'));
  var header = $(base.find('.ListILink-header'));

  var _iconClass = 'ion-cube';
  var _iconCLass_header = "ion-cube";
  var _is_hiddenClass = "ListILink__hidden";
  var _hiddenHeaderClass = "ListILink__hiddenHeader";
  var _iconModeClass = "ListILink__iconMode";

  var __items = body.find('.ListILink-item');

  var hidden = false;
  if(icon.attr('cg_icon')) {_iconClass = icon.attr('cg_icon');icon.removeAttr('cg_icon');}
  var button_show = $('<button class="ListILink-toggle_show ion-ios-arrow-down"></button>');
  header.append(button_show);
  header.click(function(){
    hidden = !hidden;
    base.hidden(hidden);
  });
  var _item_base = function(from){
    var _i_base = typeof from !== 'undefined' ? from : $("<a class='ListILink-item'><span class='ListILink-item-title'>titulo</span><i class='ion-cube ListILink-item-icon'></i></a>");
    var _i_base_iconCLass = "ion-cube";
    var _i_base_icon = $(_i_base.find('.ListILink-item-icon'));
    var _i_base_title = $(_i_base.find('.ListILink-item-title'));
    if(_i_base_icon.attr('cg_icon')){_i_base_iconCLass = _i_base_icon.attr('cg_icon');_i_base_icon.removeAttr('cg_icon');}
    _i_base.icon = function(set){
      if(typeof set === 'undefined') return _i_base_iconCLass;
      _i_base_icon.removeClass(_i_base_iconCLass).addClass(set); _i_base_iconCLass = set;
      return _i_base;
    }
    _i_base.title = function (set){
      if(typeof set === 'undefined') return _i_base_title.text();
      _i_base_title.text(set);
      return _i_base;
    }
    _i_base.link = function (set){
      if(typeof set === 'undefined') return _i_base.attr('href');
      _i_base.attr('href',set);
      return _i_base;
    }
    _i_base.target = function (set){
      if(typeof set === 'undefined') return _i_base.attr('target');
      _i_base.attr('target',set);
      return _i_base;
    }
    return _i_base;
  }

  for (var i = 0; i < __items.length; i++) {
    base.listItems.push(new _item_base($(__items[i])));
  }
  base.hidden = function(set){
    if(typeof set === 'undefined') return hidden;
    hidden = set;
    if(hidden) {base.addClass(_is_hiddenClass); body.slideUp(200);} else {base.removeClass(_is_hiddenClass); body.slideDown(200);}
    button_show.addClass(hidden ? 'ion-ios-arrow-right' : 'ion-ios-arrow-down').removeClass(hidden ? 'ion-ios-arrow-down' : 'ion-ios-arrow-right');

    return base;
  }
  base.hiddenHeader = function (set)
  {
    if(set) base.addClass(_hiddenHeaderClass); else base.removeClass(_hiddenHeaderClass);
    return base;
  }
  base.iconMode = function (set)
  {
    if(set) base.addClass(_iconModeClass); else base.removeClass(_iconModeClass);
    return base;
  }
  base.title = function (set){
    if(typeof set === 'undefined') return title.text();
    title.text(set);
    return base;
  }
  base.icon = function (set){
    if(typeof set === 'undefined') return _iconClass;
    icon.removeClass(_iconClass).addClass(set); _iconClass = set;
    return base;
  }
  base.addItem = function(set,order){
    if (typeof order !== 'undefined') for (var k in order) { if (typeof set[k] !== 'undefined') set[order[k]] = set[k];}
    var __i = new _item_base();
    base.listItems.push(__i);
    body.append(__i);
    if (typeof set['title'] !== 'undefined') __i.title(set['title']);
    if (typeof set['icon'] !== 'undefined') __i.icon(set['icon']);
    if (typeof set['link'] !== 'undefined') __i.link(set['link']);
    if (typeof set['target'] !== 'undefined') __i.target(set['target']);
    return base;
  }
  return base;
}
cg.widgets.listILink = function listILink(){
  return new cg.widgets.ListILink();
}
cg.f_searchVars = function f_searchVars(__cg_name_var){
  var __cg_object = $('.' + __cg_name_var);
  for (var i = 0; i < __cg_object.length; i++) {
    var __cg_single = new cg.widgets.ListILink($(__cg_object[i]));
    if(typeof cg.objects[__cg_name_var][__cg_single.attr('cg_name')] === 'undefined')   cg.objects[__cg_name_var][__cg_single.attr('cg_name')] = __cg_single;
    else{
      var __count = 1;
      while (true) {
        if(typeof cg.objects[__cg_name_var][__cg_single.attr('cg_name') + __count] === 'undefined') { cg.objects[__cg_name_var][$(__cg_object[i]).attr('cg_name') + __count] = __cg_single.attr('cg_name',__cg_single.attr('cg_name') + __count); break;}
       __count++;
     }
   }

  }
}
cg.load = function load(){
  for (var i in cg.objects) cg.f_searchVars(i);
}
$(document).ready(function(){
cg.load();
/*cg.v('wachalatato1').title('BUSQUEDA');
cg.v('wachalatato1').listItems[1].title('lala').icon('ion-android-radio-button-on');
cg.v('wachalatato1').addItem({title:'michilala',icon: 'ion-home',link: '//www.youtube.com', target: '_blank'});

cg.v('wachalatato1').hiddenHeader(true).title('Lala land');*/
//$('body').append(aa);

});
