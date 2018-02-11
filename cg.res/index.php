<?php
error_reporting(-1);
include_once 'php/cg.class.php';
include_once 'php/cg.widgets.php';

$control = cg::dom('-')->append([
    'DOCTYPE',
    'html(lang="es")'=>[
      'title Testeo de hosting',
      'meta(charset="utf-8")',
      'head'=>[
          'link(href="font/ionicons-2.0.1/css/ionicons.css",rel="stylesheet")',
          'link(href="css/style.css",rel="stylesheet")',
          'script(src="js/jquery-3.3.1.min.js")'
      ],
      'body#[body].cg-widgets_styles-orange'=>[
          'div#[wrap].initial'
      ]
    ]
]);
$body = cg::v('body');
$wrap = cg::v('wrap');

$cards = [];
$labels = [];
$lists = [];
$datalist = [];

for ($i=0; $i < 4; $i++) {
  $cards[$i] = cg_widgets::cardView_icon()->description('lorem impus dolor sit amet no se que rayos estoy escribiendo')->link('index.php')->attr('style','float:left;')->shadow(false);
  $wrap->append($cards[$i]);
}
$wrap->append(['div(style="clear: both;")']);
for ($i=0; $i < 4; $i++) {
  $labels[$i] = cg_widgets::label_icon()->description('lorem impus dolor sit amet no se que rayos estoy escribiendo')->title('index.php')->shadow(false);
  $wrap->append($labels[$i]);
}

for ($i=0; $i < 4; $i++) {
  array_push($datalist,['title'=>'titulO #'.($i+1),'link'=>'index.php']);
}

$wrap->append(['div(style="clear: both;")']);

for ($i=0; $i < 4; $i++) {
  $lists[$i] = cg_widgets::listILink()->title('titulito')->name('wachalatato');
  $lists[$i]->datasource($datalist);
  $wrap->append($lists[$i]);
}
$lists[2]->listItems[0]->target('_blank')->title('ESTE ES UN SUBARASHI');
$lists[2]->hiddenHeader(true);
$lists[1]->iconMode(true)->hiddenHeader(false);

$body->append(['script(src="js/cg_widgets.js")']);

$control->render();
?>
