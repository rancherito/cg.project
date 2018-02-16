<?php
class __cg_base_widgets extends CgDom
{
  function name($set)
  {
    if(is_null($set)) return $this->attr('cg_name');
    $this->attr('cg_name',$set);
    return $this;
  }
  function border($set)
  {
    if(is_null($set)) return $this->attr('cg_border');
    $this->attr('cg_border',$set);
    return $this;
  }

}
class __cg_base_wrap extends __cg_base_widgets
{
    public $body;
    function __construct()
    {
        $this->body = cg::dom('div');
        $this->append($this->body);
    }

}
class __cg_base_label extends __cg_base_widgets
{
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
      $this->body = cg::v('body');
      $this->banda = cg::v('banda');
      $this->wrap = cg::v('wrap');
      $this->title = cg::v('title');
      $this->description = cg::v('description');
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
class __cg_base_link extends __cg_base_widgets
{
    public $title;
    public $icon;
    public $_icon = "ion-cube";
    function __construct()
    {
        $this->icon = cg::dom('i');
        $this->title = cg::dom('span TITLE');
        $this
        ->dom("a")
        ->append($this->title,$this->icon);
    }
    function icon($set)
    {
        $this->icon->removeClass($this->_icon)->addClass($set);
        $this->icon->attr('cg_icon',$set);
        $this->_icon = $set;
        return $this;
    }
    function title($set)
    {
        $this->title->text($set);
        return $this;
    }
    function link($set)
    {
        $this->attr('href',$set);
        return $this;
    }
    function target($set)
    {
        $this->attr('target',$set);
        return $this;
    }
}
class __cg_base_list extends __cg_base_wrap
{
  public $header;
  public $wrap_childs;
  function __construct()
  {
    parent::__construct();
    $this->header = new __cg_base_link();
    $this->wrap_childs = cg::dom('div');
    $this->body->append($this->header,$this->wrap_childs);
  }
  function showHeader($set)
  {
    $this->header->generate($set);
  }
}
class Cg_CardView_icon extends __cg_base_wrap
{
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
        $this->button = new __cg_base_link();
        $this->button->addClass('CardView_icon-link_1');
        $this->button->title->addClass('CardView_icon-link_1_title')->text('Más información');
        $this->button->icon->addClass('CardView_icon-_link_1_icon');
        $this->button->icon('ion-more');

        $this->title = cg::v('title');
        $this->subTitle = cg::v('subTitle');
        $this->description = cg::v('description');
        $this->link_2 = cg::v('link_2');
        $this->icon = cg::v('icon')->addClass($this->_icon);

        $this->name('CardView_icon')->border('shadow');
        cg::v('w_link')->append($this->button);
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
class Cg_Label_icon extends __cg_base_label
{
  private $_icon = "ion-cube";
  private $icon;
  function __construct()
  {
    parent::__construct();
    $this->addClass('Label_icon')->border('shadow-s')->name('Label_icon');

    $this->body->addClass('Label_icon_content');
    $this->banda->addClass('Label_icon-banda');
    $this->wrap->addClass('Label_icon-w_description');
    $this->title->addClass('Label_icon-title');
    $this->description->addClass('Label_icon-description');

    $this->icon = cg::dom('i.Label_icon-icon')->addClass($this->_icon);
    $this->banda->after($this->icon);
  }
  function icon($set)
  {
      $this->icon->replaceClass($this->_icon,$set);
      $this->_icon = $set;
      return $this;
  }
}
class Cg_IList_icon extends __cg_base_link
{
  function __construct()
  {
    parent::__construct();
    $this->icon($this->_icon);
    $this->addClass('IList_icon');
    $this->title->addClass('IList_icon-title');
    $this->icon->addClass('IList_icon-icon');
  }
}
class Cg_ListILink extends __cg_base_list
{
  public $listItems = [];
  private $_hiddenHeaderClass = "ListILink__hiddenHeader";
  private $_iconModeClass = "ListILink__iconMode";
  function __construct()
  {
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
    $item = new __cg_base_link();
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
class Cg_SimpleIListLink extends __cg_base_wrap
{
  private $listItems = [];
  private $wrap_childs;
  private $title;
  private $icon;
  private $_iconClass = "ion-ios-circle-filled";

  function __construct()
  {
    parent::__construct();
    $this->body->addClass('SimpleIListLink_content')->append([
      'a.SimpleIListLink-header'=>[
        'i#[icon].SimpleIListLink-icon',
        'span#[title].SimpleIListLink-title Title list'
      ],
      'div#[body].SimpleIListLink-body'
    ]);

    $this->wrap_childs = cg::v('body');
    $this->title = cg::v('title');
    $this->icon = cg::v('icon');
    $this->icon($this->_iconClass)->addClass('SimpleIListLink');;
  }
  function addItem($items) {

    foreach (func_get_args() as $key => $item) {
        array_push($this->listItems,$item);
        $this->wrap_childs->append($item);
    }
    return $this;
  }
  static function item() {
    $item = new __cg_base_link();
    $item->addClass('SimpleIListLink-item');
    $item->title->addClass('SimpleIListLink-item-title');
    $item->icon->addClass('SimpleIListLink-item-icon');
    $item->icon('ion-ios-circle-outline');
    return $item;
  }
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
}

class Cg_SimpleButton extends __cg_base_link {

  function __construct() {
    parent::__construct();
    $this->addClass('SimpleButton')->border('shadow-s');
    $this->icon->addClass('SimpleButton-icon');
    $this->title->addClass('SimpleButton-title');
  }
}

class cg_widgets{
    static function cardView_icon() { return new cg_CardView_Icon();}
    static function label_icon(){ return new cg_Label_icon(); }
    static function iList_icon(){ return new Cg_IList_icon(); }
    static function listILink(){ return new Cg_ListILink(); }
    static function simpleIListLink() { return new Cg_SimpleIListLink(); }
    static function simpleButton() { return new Cg_SimpleButton(); }
}


?>
