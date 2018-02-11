<?php
class __cg_base_widgets extends CgDom
{
  function name($set)
  {
    if(is_null($set)) return $this->attr('cg_name');
    $this->attr('cg_name',$set);
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
  function title($set = null)
  {
      if(is_null($set)) return $this->title->text();
      $this->title->text($set);
      return $this;
  }
  function description($set = null)
  {
      if(is_null($set)) return $this->description->text();
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
    function icon($set = null)
    {
        if(is_null($set)) return $this->_icon;
        $this->icon->removeClass($this->_icon)->addClass($set);
        $this->icon->attr('cg_icon',$set);
        $this->_icon = $set;
        return $this;
    }
    function title($set = null)
    {
        if(is_null($set)) return $this->title->text();
        $this->title->text($set);
        return $this;
    }
    function link($set = null)
    {
        if(is_null($set)) return $this->attr('href');
        $this->attr('href',$set);
        return $this;
    }
    function target($set = null)
    {
        if(is_null($set)) return $this->attr('target');
        $this->attr('target',$set);
        return $this;
    }
}
class __cg_base_list extends __cg_base_wrap
{
  public $header;
  public $content;
  function __construct()
  {
    parent::__construct();
    $this->header = new __cg_base_link();
    $this->content = cg::dom('div');
    $this->body->append($this->header,$this->content);
  }
  function showHeader($set)
  {
    $this->header->generate($set);
  }
}
class Cg_CardView_icon extends __cg_base_wrap
{
    private $_icon = "ion-cube";
    private $_shadowClass = "CardView_icon__shadow";
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

        $this->shadow(true);
        $this->name('CardView_icon');
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
    function shadow($set = null)
    {
      if(is_null($set)) return $this->_shadowClass;
      if($set) $this->addClass($this->_shadowClass); else $this->removeClass($this->_shadowClass);
      return $this;
    }
    function icon($set = null)
    {
        if(is_null($set)) return $this->_icon;
        $this->icon->removeClass($this->_icon)->addClass($set);
        $this->_icon = $set;
        return $this;
    }
    function description($set = null)
    {
        if(is_null($set)) return $this->description->text();
        $this->description->text($set);
        return $this;
    }
    function title($set = null)
    {
        if(is_null($set)) return $this->title->text();
        $this->title->text($set);
        return $this;
    }
    function subTitle($set = null)
    {
        if(is_null($set)) return $this->subTitle->text();
        $this->subTitle->text($set);
        return $this;
    }
    function link($set = null)
    {
        if(is_null($set)) return $this->link_2->attr('href');
        $this->link_2->attr('href',$set);
        $this->button->link($set);
        return $this;
    }

}

class Cg_Label_icon extends __cg_base_label
{
  private $_icon = "ion-cube";
  private $_shadowClass = "Label_icon__shadow";
  private $icon;
  function __construct()
  {
    parent::__construct();
    $this->addClass('Label_icon');
    $this->body->addClass('Label_icon_content');
    $this->banda->addClass('Label_icon-banda');
    $this->wrap->addClass('Label_icon-w_description');
    $this->title->addClass('Label_icon-title');
    $this->description->addClass('Label_icon-description');

    $this->icon = cg::dom('i.Label_icon-icon')->addClass($this->_icon);
    $this->banda->after($this->icon);
    $this->shadow(true);
    $this->name('Label_icon');
  }

  function shadow($set)
  {
    if($set) $this->addClass($this->_shadowClass); else $this->removeClass($this->_shadowClass);
    return $this;
  }
  function icon($set = null)
  {
      if(is_null($set)) return $this->_icon;
      $this->icon->removeClass($this->_icon)->addClass($set);
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
    $this->content->addClass('ListILink-body');
    $this->name('ListILink');
  }
  function icon($set = null)
  {
    $this->header->icon($set);
    return is_null($set) ? $this->header->icon() : $this;
  }
  function title($set = null)
  {
    $this->header->title($set);
    return is_null($set) ? $this->header->title() : $this;
  }
  function hiddenHeader($set)
  {
    if($set) $this->addClass($this->_hiddenHeaderClass); else $this->removeClass($this->_hiddenHeaderClass);
    return $this;
  }
  function iconMode($set)
  {
    if($set) $this->addClass($this->_iconModeClass); else $this->removeClass($this->_iconModeClass);
    return $this;
  }
  function addItem($set,$order = null)
  {

    if (is_array($order)) foreach ($order as $k => $v) { if (isset($set[$k])) $set[$v] = $set[$k];}
    $__i = new __cg_base_link();
    $__i->icon($__i->_icon);
    $__i->addClass('ListILink-item');
    $__i->title->addClass('ListILink-item-title');
    $__i->icon->addClass('ListILink-item-icon');

    if (isset($set['title'])) $__i->title($set['title']);
    if (isset($set['icon'])) $__i->icon($set['icon']);
    if (isset($set['link'])) $__i->link($set['link']);
    if (isset($set['target'])) $__i->target($set['target']);
    array_push($this->listItems,$__i);
    $this->content->append($__i);
    return $this;
  }
  function datasource($set,$order = null)
  {
    foreach ($set as $k => $v) $this->addItem($v,$order);
    return $this;
  }
}


class cg_widgets{
    static function cardView_icon(){
        return new cg_CardView_Icon();
    }
    static function label_icon(){
        return new cg_Label_icon();
    }
    static function iList_icon(){
        return new Cg_IList_icon();
    }
    static function listILink(){
        return new Cg_ListILink();
    }

}


?>
