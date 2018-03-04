<?php

namespace cg;

$GLOBALS["_cgVars"] = [];

class FormValidator{
  private $items = ["value" => [], "validator" => [], "name" => []];
  private $allvalid = false;
  private $form = NULL;
  private $isForm = false;
  private $method = "get";
  private $nameForm = "";
  function allvalid(){
    return $this->allvalid;
  }
  function __construct(&$form,$nameForm){
    if (Dom::isDom($form)){
      if (strtolower($form->typeDom) === 'form') {
        $this->isForm = true;
        $this->form = $form;
        $this->nameForm = $nameForm;
        $this->form->append("@@input(type='hidden',name='$nameForm',value='on')");
        $this->method = strtolower($this->form->attr("method")) === 'post'? 'post':'get';
      }
    }
  }
  function isActive(){
    return !empty($_POST[$this->nameForm]);
  }
  function add($value,$validator,$name = NULL){
    $name = is_null($name) ? $value : $name;
    array_push($this->items["value"], $value);
    array_push($this->items["validator"], $validator);
    array_push($this->items["name"], $name);

    return $this;
  }
  function getResults(){
    $this->allvalid = true;
    $result = [];
    $r_vars = $this->method === 'post' ? $_POST : $_GET;
    if ($this->isForm && !empty($r_vars[$this->nameForm])) {
      $list = $this->form->find("input,select,textarea");
      foreach ($list as $i => $inp) {
        if (count($inp->attr("name"))) {
          $name_inp = $inp->attr("name");
          if (in_array($name_inp,$this->items["value"])) {
            $post_val = array_search($name_inp,$this->items["value"]);
            $eval = isset($r_vars[$name_inp]) ? $r_vars[$name_inp]:"";
            $vald = new Evaluator($eval);
            $vald->validation($this->items['validator'][$post_val]);
            $valev = $vald->isValid();

            array_push($result,
              ["name"=>$this->items['name'][$post_val],"value"=>$eval,"isValid"=>$valev,"typeError"=>$vald->getTypeError(),"object"=>NULL]
            );
            $result[count($result) - 1]['object'] = &$list[$i];

            $this->allvalid &= $valev;
          }
        }
      }
    }

    return $result;
  }
  function restore($exclude = []){

      if ($this->isForm) {
        $exclude = array_flip($exclude);
        $r_vars = $this->method === 'post' ? $_POST : $_GET;
        $r_vars = array_diff_key($r_vars, $exclude);
        foreach ($this->form->find("input,select,label,textarea") as $i => $items) {
          if (count($items->attr("name"))) {

            if (isset($r_vars[$items->attr("name")])) {
              if (strtolower($items->typeDom) === 'textarea' || strtolower($items->typeDom) === 'label') $items->text($r_vars[$items->attr("name")]);
              else {$items->val($r_vars[$items->attr("name")]);}
            }

          }
        }
      }

  }

}
class Evaluator{
  public $value = "";
  private $validations = ["novalues" => null, "novoid" => false, "regex" => null, "dimension" => null, "range" => null,"equalvalues"=>null];
  private $keyvalidations = ["novalues", "novoid", "regex", "dimension", "range", "equalvalues"];
  private $typeError = "NO ERROR DETECTED";
  function __construct($value){
    $this->value = $value;
  }
  function getTypeError(){
    return $this->typeError;
  }

  function validation($setvalidation){

    foreach ($setvalidation as $key => $value) {
      if (in_array($key,$this->keyvalidations)) {
        $this->validations[$key] = $setvalidation[$key];
      }
    }
    return $this;
  }
  function isValid(){
    $valid = true;

    if (!is_null($this->validations["novalues"])) {
      $valid = !in_array($this->value, $this->validations["novalues"]);
      $this->typeError = "value no accepted";
    }
    if ($valid) {
      if ($this->validations["novoid"]) {
        $valid = strlen($this->value) > 0;
        $this->typeError = "value is void";
      }
    }

    if ($valid) {
      if (!is_null($this->validations["regex"])) {
        if (strlen($this->value) > 0 && !is_null($this->value)) {
          $valid = preg_match($this->validations["regex"], $this->value) ? true: false;
        }
        $this->typeError = "incorrect format";
      }
    }

    if ($valid) {
      $dimension = $this->validations["dimension"];
      if (!is_null($dimension)) {
        $valDim = false;
        $dimvalue = strlen($this->value);
        //$valid = in_array(strlen($this->value), $dimension);
        foreach ($dimension as $key => $value) {
          if (gettype($value) === "integer") {
            $valDim |= $dimvalue === $value;
          }
          if (gettype($value) === "string") {
            if (is_numeric($value)) {
              $valDim |= $dimvalue === intval($value);
            }else {
              if (preg_match(cg\reg::$range,$value)) {
                $arrg = preg_split("/\-/i",$value);
                $arrg[0] = intval($arrg[0]);
                $arrg[1] = intval($arrg[1]);
                $valDim |= $dimvalue >= $arrg[0] && $dimvalue <= $arrg[1];
              }
            }
          }
        }
        $valid = $valDim;
        $this->typeError = "incorrect dimension";
      }
    }
    if ($valid) {
      $range = $this->validations["range"];
      if (!is_null($this->validations["range"])) {
        $this->typeError = "out range";
        if (is_numeric($this->value)) {
          $temp = floatval($this->value);
          $valid = $temp >= $range[0] && $temp <= $range[1];
        }
        else {
          $valid = false;
        }
      }
    }
    if ($valid) {
      if (!is_null($this->validations["equalvalues"])) {
        $valid = in_array($this->value, $this->validations["equalvalues"]);
        $this->typeError = "value no accepted";
      }
    }
    if($valid) $this->typeError = "NO ERROR DETECTED";
    return $valid;
  }

}
class Validator{
  public $error = ["value" => [], "description" => [], "desc_error" => []];
  public $allvalid = true;

  function onlyError(){
    $error = ["value" => [], "description" => []];
    foreach ($this->error["description"] as $key => $value) {
      if ($value !== "IS_VALID") {
        array_push($error["value"],$this->error["value"][$key]);
        array_push($error["description"],$value);
      }
    }
    return $error;
  }
  function addValue($value,$validator,$name = NULL){
    $name = is_null($name) ? $value : $name;
    $val = new Evaluator($value);
    $val->validation($validator);
    $valev = $val->isValid();
    $desc = ($valev ? "IS_VALID": "NO_IS_VALID(".$val->value.")");
    array_push($this->error["value"], $name);
    array_push($this->error["description"], $desc);
    array_push($this->error["desc_error"], $val->getTypeError());
    $this->allvalid &= $valev;


    return $this;
  }
}
class MDom{
  public $list = [];
  public $length = 0;
  public function __construct(&$set){
    $this->list = $set;
    $this->length = count($set);
  }
  function emptyDom(){
    foreach ($this->list as $i => $sel) if (Dom::isDom($sel)) $this->list[$i]->emptyDom();
    return $this;
  }
  function dom($set){
    foreach ($this->list as $i => $sel) if (Dom::isDom($sel)) $this->list[$i]->dom($set);
    return $this;
  }
  function addClass($set){
    foreach ($this->list as $i => $sel) if (Dom::isDom($sel)) $this->list[$i]->addClass($set);
    return $this;
  }
  function generate($set){
    foreach ($this->list as $i => $sel) if (Dom::isDom($sel)) $this->list[$i]->generate($set);
    return $this;
  }
  function removeClass($set){
    foreach ($this->list as $i => $sel) if (Dom::isDom($sel)) $this->list[$i]->removeClass($set);
    return $this;
  }
  function attr($set,$val = null){
    foreach ($this->list as $i => $sel) if (Dom::isDom($sel)) $this->list[$i]->attr($set,$val);
    return $this;
  }
  function removeAttr($set){
    foreach ($this->list as $i => $sel) if (Dom::isDom($sel)) $this->list[$i]->removeAttr($set);
    return $this;
  }
  function val($set){
    foreach ($this->list as $i => $sel) if (Dom::isDom($sel)) $this->list[$i]->val($set);
    return $this;
  }
  function append($set){
    foreach ($this->list as $i => $sel) {
      if (Dom::isDom($sel)) foreach (func_get_args() as $e => $item) $this->list[$i]->append($item);
    }
    return $this;
  }

  function prepend($set){
    foreach ($this->list as $i => $sel) {
      if (Dom::isDom($sel)) foreach (func_get_args() as $e => $item) $this->list[$i]->prepend($item);
    }
    return $this;
  }function after($set){
    foreach ($this->list as $i => $sel) {
      if (Dom::isDom($sel)) foreach (func_get_args() as $e => $item) $this->list[$i]->after($item);
    }
    return $this;
  }
  function before($set){
    foreach ($this->list as $i => $sel) {
      if (Dom::isDom($sel)) foreach (func_get_args() as $e => $item) $this->list[$i]->before($item);
    }
    return $this;
  }

  function removeParent(){
    foreach ($this->list as $i => $sel) if (Dom::isDom($sel)) $this->list[$i]->removeParent($set);
    return $this;
  }
  function text($set){
    foreach ($this->list as $i => $sel) if (Dom::isDom($sel)) $this->list[$i]->text($set);
    return $this;
  }
  function __tostring(){
    $t_S = "";
    foreach ($this->list as $i => $c) {
      $class = (strlen($c->attr("class")) > 0 ? ".":"").(str_replace(" ",".",$c->attr("class")));
      $t_S .= "typeDom: ".$c->typeDom.$class.(strlen($c->attr("id")) > 0 ? "#":"").$c->attr("id")." childrens(".count($c->childrens).")\n";
    }
    return $t_S;
  }
}
class Dom{
  public $typeDom = "div";
  private $append = "";
  private $attr = "";
  private $attr_list = [];
  private $noContentDom = ["input","img","hr","link","meta","br"];
  private $especialDom = ["DOCTYPE"=>"<!DOCTYPE html>"];
  private $childrens = [];
  private $generate = true;
  public $parent = null;
  public $acutab = "  ";
  private $isRender = false;
  function __construct($setDom){
    $pArray = null;
    if (is_array($setDom)) {
      foreach ($setDom as $k => $val) {
        $pArray = $val;
        $setDom = $k;
        break;
      }
    }
    $info = $this->extractinfo($setDom);

    if (strlen($info["var"]) > 0) $GLOBALS["_cgVars"][$info["var"]] = &$this;
    if (strlen($info['dom']) > 0){
      $this->extractAttr($info['attr']);$this->typeDom = $info['dom'];
    }
    else $this->typeDom = "-";

    if (count($info['prAt']) > 0 ) {
      if ($info['dom'] === '') $this->typeDom = 'div';

      if($this->typeDom !== "-"){
        //if(strlen($info['text']) > 0) $this->append($info['text']);
        foreach ($info['prAt'] as $i => $attr) {
          if (substr($attr,0,1) === ".") $this->addClass(substr($attr,1));
          else $this->attr('id',substr($attr,1));
        }
      }
    }
    if(strlen($info['text']) > 0) $this->append($info['text']);
    if (!is_null($pArray)) $this->append($pArray);

  }

  function remove($item){
    if (Dom::isDom($item)) {
      $pos = array_search($item,$this->childrens,true);
      if ($pos !== false) unset($this->childrens[$pos]);
    }
    return $this;
  }
  function removeParent(){
    if (!is_null($this->parent)) array_splice($this->parent->childrens,array_search($this,$this->parent->childrens,true),1);// $this->parent = null;
    return $this;
  }
  function emptyDom(){
    $this->childrens = [];
    return $this;
  }
  function find($filter,$fullMap = false){
    $founds = [];
    foreach (explode(',',$filter) as $i => $p) {
      $p = trim(preg_replace("/(> |>)/i"," >",preg_replace("/[ ]+/i"," ",$p)));
      $this->initFind($p,$this,$founds);
    }
    if ($fullMap) return new CgMDom($founds);
    return $founds;
  }
  function sibling($filter,$fullMap = false){
    $founds = [];
    foreach (explode(',',strtolower($filter)) as $i => $p) {
      if (preg_match("/[a-z\d_\-#.]+/i",trim($p),$fi)) {
        $this->initFind(">".$fi[0],$this->parent,$founds);
      }
    }
    $p_self = array_search($this,$founds,true);
    if (is_numeric($p_self) ? $p_self >= 0 : false) unset($founds[$p_self]);

    if ($fullMap) return new CgMDom($founds);
    return $founds;
  }
  function dom($set){$this->typeDom = $set; return $this;}
  function addClass($setClass){
    $class = $this->attr("class");
    $class .= " ".$setClass;
    $this->attr("class",trim($class));

    return $this;
  }
  function generate($set){
    $this->generate = $set;
    return $this;
  }
  function removeClass($setClass){
    $class = $this->attr("class");
    $claves = preg_split("/[ ]+/i", $setClass);
    $selfKeys = preg_split("/[ ]+/i", $class);
    $class = implode(" ",array_diff($selfKeys, $claves));
    $this->attr("class",$class);
    return $this;
  }
  function replaceClass($old,$new){
    $this->removeClass($old)->addClass($new);
    return $this;
  }
  function toggleClass($set,$flag){
    if ($flag) $this->addClass($set); else $this->removeClass($set); return $this;
  }
  function attr($attr,$val = null){
    if(!is_null($val)){
      if (strlen($attr) > 0) {
        if (strtolower($attr) === 'value' && strtolower($this->typeDom) === 'select') {
          foreach ($this->find("option") as $e => $op) {
            if ($op->attr("value")."" === $val."") {
              $this->find("option",1)->removeAttr("selected");
              $op->attr('selected','selected');
              break;
            }
          }
        }
        else $this->attr_list[$attr] = $val."";
      }
      return $this;
    }

    if(strtolower($attr) === 'value' && strtolower($this->typeDom) === 'select'){
      $ops = $this->find("option");
      $r_val = "";
      $isDis = true;
      foreach ($ops as $i => $op) {
        if (isset($op->attr_list["selected"])) return $op->val();
        if($isDis) {
          if (!isset($op->attr_list['disabled'])) {$isDis = false;$r_val = $op->val();}
        }
      }
      return $r_val;
    }
    elseif(isset($this->attr_list[strtolower($attr)])) return $this->attr_list[strtolower($attr)];
    return "";
  }
  function removeAttr($set){
    unset($this->attr_list[$set]);
    return $this;
  }
  function val($val=null) {
    if (!is_null($val)) {
      $this->attr("value",$val);
      return $this;
    }
    return $this->attr("value");
  }
  function preRender() {}
  function makeRender(){
    if ($this->generate && !$this->isRender) {
      $this->preRender();
      $this->isRender = true;
      $outClass = "";
      foreach ($this->attr_list as $i => $attr) {
        $outClass .= " $i='$attr'";
      }
      if(!empty($this->especialDom[$this->typeDom])) return $this->especialDom[$this->typeDom]."\n";
      if (in_array($this->typeDom,$this->noContentDom)) return "<$this->typeDom$outClass>\n";

      $outContent = "";
      $isOnlyText = true;
      $s_outContent = "";

      if (!is_null($this->parent)) {
        $this->acutab .= $this->parent->acutab;
      }

      if ($this->typeDom === "-" || $this->typeDom === "*") {
        $this->acutab = substr($this->acutab,2);
      }

      foreach ($this->childrens as $key => $value) {

        if (gettype($value) === "object") {
          $outContent .= $this->acutab.$value->makeRender();
          $isOnlyText = false;
        }
        else {
          if (gettype($value) === "string") {
            $outContent .= $this->acutab.$value."\n";
            $s_outContent = $value;
          }

        }
      }

      if ($this->typeDom !== "-" && $this->typeDom !== "*") {
        if ($outContent === "") return "<$this->typeDom$outClass></$this->typeDom>\n";
        elseif ($isOnlyText) {
          return "<$this->typeDom$outClass>$s_outContent</$this->typeDom>\n";
        }
        else{
          return "<$this->typeDom$outClass>\n$outContent".substr($this->acutab,2)."</$this->typeDom>\n";
        }
      }else {
        return $outContent;
      }
    }else {
      return "";
    }

  }
  function render($render = true){
    if ($render) echo $this->makeRender();
    else return $this->makeRender();
  }
  function lastChild(){
    if (count($this->childrens) > 0) {
      return $this->childrens[count($this->childrens) - 1];
    }
    return null;
  }
  function prepend($value){
    $temp = dom("pre");
    foreach (func_get_args() as $i=> $newAppend) $temp->append(func_get_arg($i));
    $this->fromAnother($temp,0);
    unset($temp);
    return $this;
  }
  function after($value){
    if (!is_null($this->parent)) {
      $temp = dom("pre");
      $pos = array_search($this,$this->parent->childrens,true);
      foreach (func_get_args() as $i=> $newAppend) $temp->append(func_get_arg($i));
      $this->parent->fromAnother($temp,$pos+1);
      unset($temp);
    }
    return $this;
  }
  function before($value){
    if (!is_null($this->parent)) {
      $temp = dom("pre");
      $pos = array_search($this,$this->parent->childrens,true);
      foreach (func_get_args() as $i=> $newAppend) $temp->append(func_get_arg($i));
      $this->parent->fromAnother($temp,$pos);
      unset($temp);
    }
    return $this;
  }
  static function isDom($cg){
    if (gettype($cg) === "object") {
      return (is_subclass_of($cg,"cg\Dom") || get_class($cg) === "cg\Dom");
    }
    return false;
  }
  function append($Dom){
    foreach (func_get_args() as $key => $value) {
      if(is_array($value)){
        foreach ($value as $e => $v_array) {
          if(Dom::isDom($v_array)) $this->append($v_array);
          else if(is_numeric($e)) $this->append('@@'.$v_array);
          else if(is_string($e)) $this->append(dom($e)->append($v_array));
        }
      }
      else if (gettype($value) === "object" || gettype($value) === "string") {
        if (gettype($value) === "object") {

          if (Dom::isDom($value)) {
            if (func_get_arg($key)->typeDom === "-") {
              // NOTE: VERIFICAR POSIBLE ERROR AL NO PASAR UN PUNTERO
              $this->fromAnother($value,count($this->childrens));
              func_get_arg($key)->removeParent();
            }else {
              array_push($this->childrens,null);
              func_get_arg($key)->removeParent();
              func_get_arg($key)->parent = &$this;

              $this->childrens[count($this->childrens)-1] = func_get_arg($key);
            }
          }
          else array_push($this->childrens,"[object:".get_class($value)."]");

        }
        else {
          if (substr($value,0,2) === "@@") {
            $this->unit($this,$this->clearStxPug(substr($value,2)));
          }
          elseif (preg_match("/^\[#\[[ \|,.\-_#\[\]\da-z]+\]\]$/i",$value)) {
            $value = explode(',',substr($value,3,-2));
            foreach ($value as $e => $item) {
              $temp_cg = dom($item);
              if ($temp_cg->typeDom !== "-") $this->append($temp_cg);
              else unset($temp_cg);
            }
          }
          else {
            if(gettype($this->lastChild()) === "string"){
              $this->childrens[count($this->childrens)-1] = $this->lastChild().$value;
            }else {
              array_push($this->childrens,null);
              $this->childrens[count($this->childrens)-1] = $value;
            }

          }
        }
      }
    }
    return $this;
  }
  function text($text = NULL){
    if (is_null($text)) {
      $r_txt = "";
      foreach ($this->childrens as $i => $c) if (gettype($c) === 'string') $r_txt .= $c;
      return $r_txt;
    }
    $this->childrens = [];
    array_push($this->childrens,$text."");
    return $this;
  }
  function count_asc($cg,$start = 0){
    if (is_null($this->parent)) return -1;
    if ($this === $cg) {return 0;}
    if ($this->parent === $cg) {return $start+1;}
    return $this->parent->count_asc($cg,$start+1);

  }
  private function unit($cg,$arr){
    $subarr = [];
    $par = true;
    foreach ($arr as $i => $val) {
      preg_match("/^[ ]*/i",$val,$outSpace);
      if (strlen($outSpace[0]) >= 2 && $par) array_push($subarr,substr($val,2));
      if (count($cg->childrens) > 0) {
        $last = $cg->lastChild();
        if (Dom::isDom($last)) {
          $this->unit($last,$subarr);
          $subarr = [];
        }
      }
      if (substr($val,0,1)==="|") {
        $par = false;
        $cg->append(substr($val,1));
      }
      else if(strlen($outSpace[0]) === 0){
        $par = true;
        $ca = dom($val);
        $this->unit($ca,$subarr);
        $cg->append($ca);
      }
    }
    if (count($cg->childrens) > 0) {
      $last = $cg->lastChild();
      if (Dom::isDom($last)) {
        $this->unit($last,$subarr);
        $subarr = [];
      }
    }


  }
  private function clearStxPug($value){
    $f_procces_string = [];
    $new_array_string = [];
    $f_procces_string = explode("\n",str_replace("\t","  ",$value));
    $f_val = false;
    $count_space = 0;
    foreach ($f_procces_string as $i => $val) {
      $val = str_replace(chr(13),"",$val);
      if (!(preg_match("/^[ ]*$/i",$val)?true:false)) {
        if (!$f_val) {
          preg_match("/^[ ]*/i",$val,$ad);
          $count_space = strlen($ad[0]);
          $f_val = true;
        }
        if (preg_match("/^[ ]{".$count_space."}/i",$val)) {
          array_push($new_array_string,substr($val,$count_space));
        }
      }
    }
    return $new_array_string;
  }
  private function initFind($param,$Dom,&$out) {

    $b_parameters = preg_match_all("/([.#]{0,1}[a-z\d\-_]+)+/i",$param,$found_paramameters);
    if($b_parameters){
      $ff_paramameter = $found_paramameters[0][0];
      $b_p = false;
      $b_p_attr = false;

      $b_ff_p_attr = preg_match("/([.#][a-z\d\-_]+)+/i",$ff_paramameter,$ff_p_attr);
      if ($b_ff_p_attr) { $ff_paramameter = substr($ff_paramameter,0,-strlen($ff_p_attr[0])); $b_p_attr = true;}
      $b_p = strlen($ff_paramameter) > 0;

      $typeAttr = '';
      if ($b_p_attr) {
        $e_param = substr($ff_p_attr[0],0,1);
        if($e_param === '.') $typeAttr = "class";
        elseif($e_param === '#') $typeAttr = "id";
      }


      foreach ($Dom->childrens as $i => $sel) {
        if (Dom::isDom($sel)) {
          if ($sel->typeDom !== "-") {

            if(!$b_p) $ff_paramameter = strtolower($sel->typeDom);

              if ($typeAttr === "class") {
                if (in_array(substr($ff_p_attr[0],1),explode(" ",$sel->attr("class"))) && strtolower($sel->typeDom )=== $ff_paramameter){
                  if (count($found_paramameters[0]) > 1) {
                    $param_2 = preg_replace("/^[a-z\d\-_#.>]*[ ]*/i","",$param);
                    $this->initFind($param_2,$sel,$out);
                  }
                  else array_push($out,$sel);
                 }
              }
              elseif ($typeAttr === "id") {
                if (substr($ff_p_attr[0],1) === strtolower($sel->attr("id")) && strtolower($sel->typeDom) === $ff_paramameter){
                  if (count($found_paramameters[0]) > 1) {
                    $param_2 = preg_replace("/^[a-z\d\-_#.>]*[ ]*/i","",$param);
                    $this->initFind($param_2,$sel,$out);
                  }
                  else array_push($out,$sel);
                 }
              }
              elseif ($b_p) {
                if ($sel->typeDom === $ff_paramameter){
                  if (count($found_paramameters[0]) > 1) {
                    $param_2 = preg_replace("/^[a-z\d\-_#.>]*[ ]*/i","",$param);
                    $this->initFind($param_2,$sel,$out);
                  }
                  else array_push($out,$sel);
                 }
              }

          }
          if (substr($param,0,1) !== ">") {
            $this->initFind($param,$sel,$out);
          }
        }
      }

    }

  }
  private function extractinfo($s){
    $s = trim($s);
    $space = false;
    $fo_prts = false;
    $count_prts = 0;
    $pos_s = 0;
    $pos_prts = [0,0];
    $out = ["prAt"=>"","attr"=>"","text"=>"","dom"=>"","var"=>""];

    for ($i=0; $i < strlen($s); $i++) {
      if ($s[$i] === " " || $s[$i] === "|") $space = true;
      if ($s[$i] === "(" && !$space && !$fo_prts) {$fo_prts = true;$pos_prts[0] = $i;}
      if($fo_prts){
        $count_prts += $s[$i] === "(" ? 1 : ($s[$i] === ")" ? -1 : 0);
        if ($count_prts === 0 && $pos_prts[1] === 0) {$pos_prts[1] = $i;}
      }
    }
    if($fo_prts && $pos_prts[1] !== 0){
      $out["attr"] = trim(substr($s,$pos_prts[0]+1,$pos_prts[1] - $pos_prts[0]-1));
      $out["prAt"] = substr_replace($s,"",$pos_prts[0],$pos_prts[1] - $pos_prts[0]+1);
    }
    else $out["prAt"] = $s;

    for ($i=0; $i < strlen($out["prAt"]); $i++) {
      if ($out["prAt"][$i] === "|" || $out["prAt"][$i] === " ") {
        $out["text"] = substr($out["prAt"],$i+1);
        $out["prAt"] = substr($out["prAt"],0,$i);
        break;
      }
    }

    preg_match("/(^\-|^[a-z]+[\da-z]*)/i",$out['prAt'],$dom);
    $out["dom"] = !empty($dom) ? $dom[0] : "";
    preg_match("/[#]\[[\-_\da-z]+\]/i",$out["prAt"],$out["var"]);
    $out["var"] = !empty($out["var"]) ? substr($out["var"][0],2,strlen($out["var"][0])-3): "";
    preg_match_all("/[#.][\-_\da-z]+/i",preg_replace("/^[\-_\da-z]+/i","",$out["prAt"]),$out["prAt"]);
    $out["prAt"] = $out["prAt"][0];
    return $out;
  }
  private function extractAttr($s){
    if (!empty($s)) {
      $attrs = explode('|cgseparator|',preg_replace_callback("/['\"a-z\d] *, *[a-z]/i",function ($s){return substr($s[0],0,1)."|cgseparator|".substr($s[0],-1);},$s));
      foreach ($attrs as $i => $val) {
        $fi= explode('|cgseparator|',preg_replace_callback("/[a-z\d] *\= *[a-z\d'\"]/i",function ($s){return substr($s[0],0,1)."|cgseparator|".substr($s[0],-1);},$val));
        $v_fi = implode('=',array_slice($fi,1));
        $this->attr_list[$fi[0]] = !empty($v_fi) ? preg_replace("/(^['\"]|['\"]$)/i","",$v_fi) : '';
      }
    }
  }
  private function fromAnother(&$Dom,$index){
      $add_ar = [];
      $c_child = count($this->childrens);
      if ($c_child < $index) $index = $c_child; if ($index < 0) $index = 0;

      $start = count($Dom->childrens) - 1;
      for ($i = $start; $i >= 0 ; $i--) {
        array_unshift($add_ar,NULL);
        if (Dom::isDom($Dom->childrens[$i])) {
          $add_ar[0] = &$Dom->childrens[$i];
          $add_ar[0]->removeParent();
        }
        else $add_ar[0] = $Dom->childrens[$i];
      }


      $pre_ar = array_slice($this->childrens,0,$index);
      $pst_ar = array_slice($this->childrens,$index,$c_child);
      $this->childrens = array_merge($pre_ar,$add_ar,$pst_ar);
      foreach ($this->childrens as $i=> $ch) {
        if (Dom::isDom($ch)) {
          $this->childrens[$i]->parent = &$this;
        }
      }
    return $this;
  }
  function __tostring(){
    $t_S = "";
    $self_class = (strlen($this->attr("class")) > 0 ? ".":"").(str_replace(" ",".",$this->attr("class")));
    $t_S .= $this->typeDom.$self_class.(strlen($this->attr("id")) > 0 ? "#":"").$this->attr("id")." childrens(".count($this->childrens).")\n";
    foreach ($this->childrens as $i => $c) {
      $class = Dom::isDom($c)? ((strlen($c->attr("class")) > 0 ? ".":"").(str_replace(" ",".",$c->attr("class")))) : "";
      $t_S .= gettype($c) === 'string' || gettype($c) === 'NULL'? (gettype($c) === 'NULL' ? '  NULL': "  string: ".$c)."\n" : "  typeDom: ".$c->typeDom.$class.(strlen($c->attr("id")) > 0 ? "#":"").$c->attr("id")." childrens(".count($c->childrens).")\n";
    }
    return $t_S;
  }
}

class reg{
  static $nums = "/^[0-9]+$/i";
  static $words = "/^[a-zñÑáéíóúÁÉÍÓÚü]+$/i";
  static $text = "/^[a-zñÑáéíóúÁÉÍÓÚü ]+$/i";
  static $alphanums = "/^[0-9a-zñÑáéíóúÁÉÍÓÚü ]+$/i";
  static $email = "/^[a-z]+[a-z_\.\-0-9]+@[a-z]+\.[a-z]{2,5}$/i";
  static $range = "/^[0-9]+[ ]{0,1}\-[ ]{0,1}[0-9]+$/i";
  static $bits = "/^[0-1]$/i";
  static $fecha = "/^(19|20)[0-9]{2}-[0-9]{2}-[0-9]{2}$/i";
  static $class = "/\.[\-\da-z_]+/i";
  static $id = "/\#[\-\da-z_]+/i";
}

function validator(){ return new Validator();}
function v($value = null){ if (is_null($value)) return $GLOBALS["_cgVars"]; return $GLOBALS["_cgVars"][$value]; }
function evaluator($value){   return new Evaluator($value); }
function dom($value){ return new Dom($value); }
function mDom($value){ return new MDom($value); }
function formInput($setInput){ return new FormInput($setInput); }
function formValidator($form,$nameForm){ return new FormValidator($form,$nameForm); }

  ?>
