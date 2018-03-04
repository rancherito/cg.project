<?php
namespace cg;
error_reporting(-1);

include_once 'php/cg.class.php';
include_once 'php/cg.widgets.php';

$control = dom('-')->append([
    'DOCTYPE',
    'html(lang="es")'=>[
      'title Testeo de hosting',
      'meta(charset="utf-8")',
      'head'=>[
          'link(href="font/ionicons-2.0.1/css/ionicons.css",rel="stylesheet")',
          'link(href="css/cg.widgets.css",rel="stylesheet")',
          'script(src="js/jquery-3.3.1.min.js")'
      ],
      'body#[body].cg-widgets_styles-orange'=>[
          'div#[wrap].initial'
      ]
    ]
]);
$body = v('body');
$wrap = v('wrap');
/*
$cards = [];
$labels = [];
$lists = [];
$datalist = [];

for ($i=0; $i < 4; $i++) {
  $cards[$i] = CardView_icon::create()->description('lorem impus dolor sit amet no se que rayos estoy escribiendo')->link('index.php')->attr('style','float:left;');
  $wrap->append($cards[$i]);
}
$wrap->append(['div(style="clear: both;")']);
for ($i=0; $i < 5; $i++) {
  $labels[$i] = Label_icon::create()->description('lorem impus dolor sit amet no se que rayos estoy escribiendo')->title('index.php');
  $wrap->append($labels[$i]);
}

for ($i=0; $i < 4; $i++) {
  array_push($datalist,['title'=>'titulO #'.($i+1),'link'=>'index.php']);
}

$wrap->append(['div(style="clear: both;")']);

for ($i=0; $i < 4; $i++) {
  $lists[$i] = ListILink::create()->title('titulito')->name('wachalatato');
  $lists[$i]->datasource($datalist);
  $wrap->append($lists[$i]);
}

$la = SimpleList::create()->datasource($datalist);
$wrap->append(['div(style="clear: both;")']);
$wrap->append($la);

$listButtons = [];

for ($i=0; $i < 3; $i++) {
  $listButtons[$i] = SimpleButton::create()->title("Button #".($i + 1))->link('index.php')->icon('ion-ios-folder-outline');
  $wrap->append(['div(style="clear: both;")']);
  $wrap->append($listButtons[$i]);
}

$wrap->append(['div(style="clear: both;")']);*/

/*$wrap->append(
  MiniList::create()->iconList('ion-ios-heart-outline')->addItem(
    MiniList::item()->title('MI TITULASO')->description('Lorem Inpus Dolor sit amet'),
    MiniList::item()->title('MI TITULASO')->description('Lorem Inpus Dolor sit amet')
  ));*/

$body->append(['script(src="js/cg.widgets.js")','script(src="js/index.js")']);

$wrap->append(
  GPanels::create()->border('shadow')->addItem(
    GPanels::item()->key('HELLO WORLD')->panel(['strong HOLA PATITA','div poquetal','strong aragato','i konichiwa'])->attr('style','background: #555; color: white;'),
    GPanels::item()->key('GOOG BYE WORLD')->panel(['i Lorem impus dolor sit amet jajaja patatus patatan wafa fkf dsj fks d  dksf']),
    GPanels::item()->key('The WORLD')->panel(['i Lorem impus dolor sit amet jajaja patatus patatan wafa fkf dsj fks d  dksf','p patata','div enchiladas'])
  ),
  GPanels::create()->border('shadow')->addItem(
    GPanels::item()->key('HELLO WORLD')->panel(['strong HOLA PATITA','div poquetal','strong aragato','i konichiwa'])->attr('style','background: #555; color: white;'),
    GPanels::item()->key('GOOG BYE WORLD')->panel(['i Lorem impus dolor sit amet jajaja patatus patatan wafa fkf dsj fks d  dksf']),
    GPanels::item()->key('The WORLD')->panel(['i Lorem impus dolor sit amet jajaja patatus patatan wafa fkf dsj fks d  dksf','p patata','div enchiladas'])
  )
);


$control->render();
?>
