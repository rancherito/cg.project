$(document).ready(function(){
cg.load();


/*cg.v('wachalatato1').title('BUSQUEDA');
cg.v('wachalatato1').listItems[1].title('lala').icon('ion-android-radio-button-on');
cg.v('wachalatato1').addItem({title:'michilala',icon: 'ion-home',link: '//www.youtube.com', target: '_blank'});

cg.v('wachalatato1').hiddenHeader(true).title('Lala land');*/
//$('body').append(aa);
var no = cg.v('GPanels');

var ne = new cg.widgets.GPanels.item();
ne.key('lall');
ne.panel('michilala');
no.addItem(ne);

});
