<?php

namespace cg;

class __base_widgets extends Dom {
  function name($set) {
    if(is_null($set)) return $this->attr('cg_name');
    $this->attr('cg_name',$set);
    return $this;
  }
  function border($set) { $this->attr('cg_border',$set); return $this; }
  function padding($set) { $this->attr('cg_padding',$set); return $this; }
}
class __base_wrap extends __base_widgets {
    public $body;
    function __construct()
    {
        $this->body = dom('div');
        $this->append($this->body);
    }

}
class __base_label extends __base_widgets {
  public $banda;
  public $title;
  public $description;
  public $body;
  public $wrap;
  function __construct()
  {
      $this->append([
        'div#[body]'=>[
          'div#[banda]',
          'div#[wrap]'=>['span#[title]','span#[description]']
        ]
      ]);
      $this->body = v('body');
      $this->banda = v('banda');
      $this->wrap = v('wrap');
      $this->title = v('title');
      $this->description = v('description');
  }
  function title($set)
  {
      $this->title->text($set);
      return $this;
  }
  function description($set)
  {
      $this->description->text($set);
      return $this;
  }
}
class __base_link extends __base_widgets {
    public $title;
    public $icon;
    public $_icon = "ion-cube";
    function __construct() {
        $this->icon = dom('i');
        $this->title = dom('span TITLE');
        $this
        ->dom("a")
        ->append($this->title,$this->icon);
    }
    function icon($set) { $this->icon->replaceClass($this->_icon,$set)->attr('cg_icon',$set); $this->_icon = $set; return $this; }
    function title($set) { $this->title->text($set); return $this; }
    function link($set) { $this->attr('href',$set); return $this; }
    function target($set) { $this->attr('target',$set); return $this; }
}
class __base_list extends __base_wrap {
  public $header;
  public $wrap_childs;
  function __construct()
  {
    parent::__construct();
    $this->header = new __base_link();
    $this->wrap_childs = dom('div');
    $this->body->append($this->header,$this->wrap_childs);
  }
  function showHeader($set)
  {
    $this->header->generate($set);
  }
}
class __base_link_description extends __base_wrap {

  public $_icon = "ion-cube";
  function __construct() {
    parent::__construct();
    $this->body->dom('a')->append([
      'i#[icon]','div'=>['span#[title]','span#[description]']
    ]);
    $this->icon = v('icon');
    $this->title = v('title');
    $this->description = v('description');
  }
  function description($set) { $this->description->text($set); return $this; }
  function icon($set) { $this->icon->replaceClass($this->_icon,$set)->attr('cg_icon',$set); $this->_icon = $set; return $this; }
  function title($set) { $this->title->text($set); return $this; }
  function link($set) { $this->body->attr('href',$set); return $this; }
  function target($set) { $this->body->attr('target',$set); return $this; }
}
class __base_key_panel extends __base_widgets {
  function __construct() {$this->key = dom('div');}
  function key($key) { $this->key->text($key); return $this;}
  function panel($panel) { $this->emptyDom()->append($panel); return $this;}
}

class CardView_icon extends __base_wrap {
    private $_icon = "ion-cube";
    private $title;
    private $subTitle;
    private $icon;
    private $description;
    private $button;
    private $link_2;

    function __construct()
    {
        parent::__construct();
        $this->addClass('CardView_icon');
        $this->body->addClass('CardView_icon_content')->append([
            'div.CardView_icon-w_description'=>[
                '.CardView_icon-w_title'=>[
                    'span#[title].CardView_icon-title.ion-android-apps TITLE',
                    'span#[subTitle].CardView_icon-subtitle SUBTITLE'
                ],
                'div#[description].CardView_icon-description',
                'div#[w_link].CardView_icon-w_link_1'
            ],
            'a.CardView_icon-w_illustration#[link_2]'=>[
                "span#[icon].CardView_icon-icon"
            ]
        ]);
        $this->button = new __base_link();
        $this->button->addClass('CardView_icon-link_1');
        $this->button->title->addClass('CardView_icon-link_1_title')->text('Más información');
        $this->button->icon->addClass('CardView_icon-_link_1_icon');
        $this->button->icon('ion-more');

        $this->title = v('title');
        $this->subTitle = v('subTitle');
        $this->description = v('description');
        $this->link_2 = v('link_2');
        $this->icon = v('icon')->addClass($this->_icon);

        $this->name('CardView_icon')->border('shadow');
        v('w_link')->append($this->button);
    }
    static function create() {
      return new CardView_icon();
    }
    function datasource($set,$order = null)
    {

      if (is_array($order)) foreach ($order as $k => $v) { if (isset($set[$k])) $set[$v] = $set[$k];}

      if (isset($set['icon'])) $this->icon($set['icon']);
      if (isset($set['description'])) $this->description($set['description']);
      if (isset($set['title'])) $this->title($set['title']);
      if (isset($set['subTitle'])) $this->subTitle($set['subTitle']);
      if (isset($set['link'])) $this->link($set['link']);
      if (isset($set['shadow'])) $this->shadow($set['shadow']);
      return $this;
    }
    function icon($set) {
        $this->icon->replaceClass($this->_icon,$set); $this->_icon = $set; return $this;
    }
    function description($set) {
        $this->description->text($set); return $this;
    }
    function title($set) {
        $this->title->text($set); return $this;
    }
    function subTitle($set) {
        $this->subTitle->text($set); return $this;
    }
    function link($set) {
        $this->link_2->attr('href',$set); $this->button->link($set); return $this;
    }

}
class Label_icon extends __base_label {
  private $_icon = "ion-cube";
  private $icon;
  function __construct() {
    parent::__construct();
    $this->addClass('Label_icon')->border('shadow-s')->name('Label_icon');

    $this->body->addClass('Label_icon_content');
    $this->banda->addClass('Label_icon-banda');
    $this->wrap->addClass('Label_icon-w_description');
    $this->title->addClass('Label_icon-title');
    $this->description->addClass('Label_icon-description');

    $this->icon = dom('i.Label_icon-icon')->addClass($this->_icon);
    $this->banda->after($this->icon);
  }
  static function create() {
    return new Label_icon();
  }
  function icon($set)
  {
      $this->icon->replaceClass($this->_icon,$set);
      $this->_icon = $set;
      return $this;
  }
}
class IList_icon extends __base_link {
  function __construct()
  {
    parent::__construct();
    $this->icon($this->_icon);
    $this->addClass('IList_icon');
    $this->title->addClass('IList_icon-title');
    $this->icon->addClass('IList_icon-icon');
  }
}
class ListILink extends __base_list {
  public $listItems = [];
  private $_hiddenHeaderClass = "ListILink__hiddenHeader";
  private $_iconModeClass = "ListILink__iconMode";
  function __construct() {
    parent::__construct();
    $this->addClass('ListILink');
    $this->body->addClass('ListILink_content');

    $this->header->addClass('ListILink-header');
    $this->header->icon($this->header->_icon);
    $this->header->title->addClass('ListILink-title');
    $this->header->icon->addClass('ListILink-icon');
    $this->wrap_childs->addClass('ListILink-body');
    $this->name('ListILink');
  }
  static function create() {
    return new ListILink();
  }
  function icon($set){
    $this->header->icon($set); return $this;
  }
  function title($set) {
    $this->header->title($set); return $this;
  }
  function hiddenHeader($set) {
    $this->toggleClass($this->_hiddenHeaderClass,$set); return $this;
  }
  function iconMode($set) {
    $this->toggleClass($this->_iconModeClass,$set); return $this;
  }
  function datasource($data,$customData = null) {
    $this->wrap_childs->emptyDom();
    $this->listItems = [];
    if (is_array($customData)) foreach ($customData as $k => $v) { if (isset($data[$k])) $data[$v] = $data[$k];}

    foreach ($data as $k => $item) {
      $__i = $this::item();
      if (isset($item['title'])) $__i->title($item['title']);
      if (isset($item['link'])) $__i->link($item['link']);
      if (isset($item['target'])) $__i->target($item['target']);
      if (isset($set['icon'])) $__i->icon($set['icon']);
      $this->addItem($__i);
    }

    return $this;
  }
  static function item() {
    $item = new __base_link();
    $item->addClass('ListILink-item')->icon($item->_icon);
    $item->title->addClass('ListILink-item-title');
    $item->icon->addClass('ListILink-item-icon');
    return $item;
  }
  function addItem($items) {
    foreach (func_get_args() as $key => $item) {
        array_push($this->listItems,$item);
        $this->wrap_childs->append($item);
    }
    return $this;
  }
}
class SimpleList extends __base_wrap {
  private $listItems = [];
  private $wrap_childs;
  private $title;
  private $icon;
  private $_iconClass = "ion-ios-circle-filled";

  function __construct() {
    parent::__construct();
    $this->body->addClass('SimpleList_content')->append([
      'a.SimpleList-header'=>[
        'i#[icon].SimpleList-icon',
        'span#[title].SimpleList-title Title list'
      ],
      'div#[body].SimpleList-body'
    ]);

    $this->wrap_childs = v('body');
    $this->title = v('title');
    $this->icon = v('icon');
    $this->icon($this->_iconClass)->addClass('SimpleList');;
  }
  function addItem($items) {

    foreach (func_get_args() as $key => $item) {
        array_push($this->listItems,$item);
        $this->wrap_childs->append($item);
    }
    return $this;
  }
  static function item() {
    $item = new __base_link();
    $item->addClass('SimpleList-item');
    $item->title->addClass('SimpleList-item-title');
    $item->icon->addClass('SimpleList-item-icon');
    $item->icon('ion-ios-circle-outline');
    return $item;
  }
  static function create() { return new SimpleList();}
  function icon($set) {
      $this->icon->replaceClass($this->_iconClass,$set)->attr('cg_icon',$set); $this->_iconClass = $set;
      return $this;
  }
  function datasource($data,$customData = null) {
    $this->wrap_childs->emptyDom();
    $this->listItems = [];
    if (is_array($customData)) foreach ($customData as $k => $v) { if (isset($data[$k])) $data[$v] = $data[$k];}

    foreach ($data as $k => $item) {
      $__i = $this::item();
      if (isset($item['title'])) $__i->title($item['title']);
      if (isset($item['link'])) $__i->link($item['link']);
      if (isset($item['target'])) $__i->target($item['target']);
      $this->addItem($__i);
    }
    return $this;
  }
  function title($set) {
    $this->title->text($set); return $this;
  }
}
class SimpleButton extends __base_link {
  function __construct() {
    parent::__construct();
    $this->addClass('SimpleButton')->border('shadow-s');
    $this->icon->addClass('SimpleButton-icon');
    $this->title->addClass('SimpleButton-title');
    $this->icon('ion-link');
  }
  static function create(){
    return new SimpleButton();
  }
}
class MiniList extends __base_wrap {
  public $listItems = [];
  public $_iconItemClass = "";
  function __construct() {
    parent::__construct();
    $this->addClass('MiniList');
    $this->body->addClass('MiniList-body');
  }
  static function create() { return new MiniList(); }
  static function item() {
    $item = new __base_link_description();
    $item->addClass('MiniList-item')->icon('ion-ios-circle-outline');
    $item->body->addClass('MiniList-item_content')->attr('cg_border','shadow-s');
    $item->title->addClass('MiniList-item-title');
    $item->icon->addClass('MiniList-item-icon');
    $item->description->addClass('MiniList-item-description');
    return $item;
  }
  function addItem($items) {
    foreach (func_get_args() as $key => $item) {
        array_push($this->listItems,$item);
        $this->body->append($item->icon($this->_iconItemClass));
    }
    return $this;
  }
  function datasource($data,$customData = null) {
    $this->wrap_childs->emptyDom();
    $this->listItems = [];
    if (is_array($customData)) foreach ($customData as $k => $v) { if (isset($data[$k])) $data[$v] = $data[$k];}

    foreach ($data as $k => $item) {
      $__i = $this::item();
      if (isset($item['title'])) $__i->title($item['title']);
      if (isset($item['link'])) $__i->link($item['link']);
      if (isset($item['target'])) $__i->target($item['target']);
      if (isset($item['description'])) $__i->description($item['description']);
      $this->addItem($__i);
    }
    return $this;
  }
  function iconList($set) { foreach ($this->listItems as $k => $val) $this->listItems[$k]->icon($set); $this->_iconItemClass = $set; return $this;}

}

class GPanels extends __base_widgets {
  private $listItems = [];
  private $_activeItem = 0;
  function __construct() {
    $this->addClass('GPanels')->append([
      'div#[header].GPanels-header',
      'div#[wrap_childs].GPanels-body'
    ]);
    $this->wrap_childs = v('wrap_childs');
    $this->header = v('header');
    $this->name('GPanels');
  }
  function indexActive($key) {$this->_activeItem = $key < 0 ? 0 : $key; return $this;}
  function addItem($item) {
    foreach (func_get_args() as $key => $item) {
        array_push($this->listItems,$item);
        $this->wrap_childs->append($item);
        $this->header->append($item->key);
    }
    return $this;
  }
  static function create() { return new GPanels(); }
  static function item()
  {
    $item = new __base_key_panel();
    $item->addClass('GPanels-item');
    $item->key->addClass('GPanels-key');
    $item->padding('padding');
    return $item;
  }
  function datasource($data,$customData = null) {
    $this->wrap_childs->emptyDom();
    $this->header->emptyDom();
    $this->listItems = [];
    if (is_array($customData)) foreach ($customData as $k => $v) { if (isset($data[$k])) $data[$v] = $data[$k];}

    foreach ($data as $k => $item) {
      $__i = $this::item();
      if (isset($item['key'])) $__i->key($item['key']);
      if (isset($item['panel'])) $__i->panel($item['panel']);
      $this->addItem($__i);
    }
    return $this;
  }
  function preRender(){
    $num_items = count($this->listItems);
    $this->_activeItem = $this->_activeItem <= $num_items - 1 ? $this->_activeItem : 0;
    if ($num_items) {
      $this->listItems[$this->_activeItem]->addClass('GPanels-panel__active');
      $this->listItems[$this->_activeItem]->key->addClass('GPanels-key__active');
    }
  }
}



?>
