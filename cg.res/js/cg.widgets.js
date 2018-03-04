//ion-ios-arrow-right

var cg = {
  widgets:{},
  objects:{
    ListILink:{},
    GPanels:{}
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
  base.datasourse = function(set,order){
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
cg.widgets.listILink = function listILink(){ return new cg.widgets.ListILink();}
cg.widgets.GPanels = function GPanels(from) {
  var base = typeof from !== 'undefined' ? $(from) : $("<div class='GPanels' cg_border='shadow'><div class='GPanels-header'></div><div class='GPanels-body'></div></div>");
  var listItems = [];
  var _activeItem = 0;
  var header = $(base.find('.GPanels-header'));
  var body = $(base.find('.GPanels-body'));

  var item = function (_item){
    var item_base = typeof _item !== 'undefined' ? $(_item[0]) : $("<div class='GPanels-item'></div>");
    var item_key = typeof _item !== 'undefined' ? $(_item[1]) : $("<div class='GPanels-key'></div>");
    item_base.Key = item_key;
    item_base.key = function(set){
      item_base.Key.text(set); return item_base;
    }
    item_base.panel = function(set){
      item_base.empty().append(set); return item_base;
    }

    return item_base;
  }
  base.addItem = function addItem(item){
    listItems.push(item);
    header.append(item.key);
    body.append(item);
    item.Key.click(function(e){
      for (var i in listItems) { listItems[i].Key.removeClass('GPanels-key__active'); listItems[i].removeClass('GPanels-panel__active');}
      item.key.addClass('GPanels-key__active'); item.addClass('GPanels-panel__active');
    });
    return base;
  }
  var preListItem_panel = base.find('.GPanels-item');
  var preListItem_key = base.find('.GPanels-key');
  function lata(_item){
    _item.Key.click(function(f){
      for (var e in listItems) { listItems[e].Key.removeClass('GPanels-key__active'); listItems[e].removeClass('GPanels-panel__active');}
      _item.Key.addClass('GPanels-key__active'); _item.addClass('GPanels-panel__active');
      console.log(_item);
    });
  }
  for (var i = 0; i < preListItem_panel.length; i++) {
    var _item = new item([preListItem_panel[i],preListItem_key[i]]);
    lata(_item);
    listItems.push(_item);
  }

  return base;
}
cg.f_searchVars = function f_searchVars(__cg_name_var){
  var __cg_object = $('.' + __cg_name_var);
  for (var i = 0; i < __cg_object.length; i++) {
    var __cg_single = eval("new cg.widgets."+__cg_name_var+"(__cg_object[i])");
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
