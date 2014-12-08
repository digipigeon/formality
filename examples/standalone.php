<?php

include('../classes/formality/form.php');
include('../classes/formality/field.php');
include('../classes/formality/fieldset.php');
include('../classes/formality/container.php');
include('../classes/formality/label.php');
include('../classes/formality/validate.php');

include('../classes/formality/field/submit.php');
include('../classes/formality/field/select.php');
include('../classes/formality/field/radio.php');
include('../classes/formality/field/checkbox.php');

class Formality extends Formality_Form{
	public static function config($key){
		return self::path(include('../config/formality.php'), $key,Array());
	}
}


function dump($value,$level=0)
{
	if ($level > 2) return;
  if ($level==-1)
  {
    $trans[' ']='&there4;';
    $trans["\t"]='&rArr;';
    $trans["\n"]='&para;;';
    $trans["\r"]='&lArr;';
    $trans["\0"]='&oplus;';
    return strtr(htmlspecialchars($value),$trans);
  }
  if ($level==0) echo '<pre>';
  $type= gettype($value);
  echo $type;
  if ($type=='string')
  {
    echo '('.strlen($value).')';
    $value= dump($value,-1);
  }
  elseif ($type=='boolean') $value= ($value?'true':'false');
  elseif ($type=='object')
  {
    $props= get_class_vars(get_class($value));
    echo '('.count($props).') <u>'.get_class($value).'</u>';
    foreach($props as $key=>$val)
    {
      echo "\n".str_repeat("\t",$level+1).$key.' => ';
      dump($value->$key,$level+1);
    }
    $value= '';
  }
  elseif ($type=='array')
  {
    echo '('.count($value).')';
    foreach($value as $key=>$val)
    {
      echo "\n".str_repeat("\t",$level+1).dump($key,-1).' => ';
      dump($val,$level+1);
    }
    $value= '';
  }
  echo " <b>$value</b>";
  if ($level==0) echo '</pre>';
}