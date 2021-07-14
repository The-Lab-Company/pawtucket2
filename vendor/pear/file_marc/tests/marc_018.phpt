--TEST--
marc_018: iterate and print a MARC record to JSON MARC-HASH format
--SKIPIF--
<?php include('tests/skipif.inc'); ?>
--FILE--
<?php
$dir = dirname(__FILE__);
require __DIR__ . '/bootstrap.php';
$marc_file = new File_MARC($dir . '/' . 'example.mrc');

while ($marc_record = $marc_file->next()) {
  print $marc_record->toJSONHash();
  print "\n";
}
?>
--EXPECT--
{"type":"marc-hash","version":[1,0],"leader":"01850     2200517   4500","fields":[["001","0000000044"],["003","EMILDA"],["008","980120s1998    fi     j      000 0 swe"],["020"," "," ",[["a","9515008808"],["c","FIM 72:00"]]],["035"," "," ",[["9","9515008808"]]],["040"," "," ",[["a","NB"]]],["042"," "," ",[["9","NB"],["9","SEE"]]],["084"," "," ",[["a","Hcd,u"],["2","kssb\/6"]]],["084"," "," ",[["5","NB"],["a","uHc"],["2","kssb"]]],["084"," "," ",[["5","SEE"],["a","Hcf"],["2","kssb\/6"]]],["084"," "," ",[["5","Q"],["a","Hcd,uf"],["2","kssb\/6"]]],["100","1"," ",[["a","Jansson, Tove,"],["d","1914-2001"]]],["245","0","4",[["a","Det osynliga barnet och andra ber\u00e4ttelser \/"],["c","Tove Jansson"]]],["250"," "," ",[["a","7. uppl."]]],["260"," "," ",[["a","Helsingfors :"],["b","Schildt,"],["c","1998 ;"],["e","(Falun :"],["f","Scandbook)"]]],["300"," "," ",[["a","166, [4] s. :"],["b","ill. ;"],["c","21 cm"]]],["440"," ","0",[["a","Mumin-biblioteket,"],["x","99-0698931-9"]]],["500"," "," ",[["a","Originaluppl. 1962"]]],["599"," "," ",[["a","Li: S"]]],["740","4"," ",[["a","Det osynliga barnet"]]],["775","1"," ",[["z","951-50-0385-7"],["w","9515003857"],["9","07"]]],["841"," "," ",[["5","Li"],["a","xa"],["b","0201080u    0   4000uu   |000000"],["e","1"]]],["841"," "," ",[["5","SEE"],["a","xa"],["b","0201080u    0   4000uu   |000000"],["e","1"]]],["841"," "," ",[["5","L"],["a","xa"],["b","0201080u    0   4000uu   |000000"],["e","1"]]],["841"," "," ",[["5","NB"],["a","xa"],["b","0201080u    0   4000uu   |000000"],["e","1"]]],["841"," "," ",[["5","Q"],["a","xa"],["b","0201080u    0   4000uu   |000000"],["e","1"]]],["841"," "," ",[["5","S"],["a","xa"],["b","0201080u    0   4000uu   |000000"],["e","1"]]],["852"," "," ",[["5","NB"],["b","NB"],["c","NB98:12"],["h","plikt"],["j","R, 980520"]]],["852"," "," ",[["5","Li"],["b","Li"],["c","CNB"],["h","h,u"]]],["852"," "," ",[["5","SEE"],["b","SEE"]]],["852"," "," ",[["5","Q"],["b","Q"],["j","98947"]]],["852"," "," ",[["5","L"],["b","L"],["c","0100"],["h","98\/"],["j","3043 H"]]],["852"," "," ",[["5","S"],["b","S"],["h","Sv97"],["j","7235"]]],["900","1","s",[["a","Yanson, Tobe,"],["d","1914-2001"],["u","Jansson, Tove,"],["d","1914-2001"]]],["900","1","s",[["a","Janssonov\u00e1, Tove,"],["d","1914-2001"],["u","Jansson, Tove,"],["d","1914-2001"]]],["900","1","s",[["a","Jansone, Tuve,"],["d","1914-2001"],["u","Jansson, Tove,"],["d","1914-2001"]]],["900","1","s",[["a","Janson, Tuve,"],["d","1914-2001"],["u","Jansson, Tove,"],["d","1914-2001"]]],["900","1","s",[["a","Jansson, Tuve,"],["d","1914-2001"],["u","Jansson, Tove,"],["d","1914-2001"]]],["900","1","s",[["a","Janssonova, Tove,"],["d","1914-2001"],["u","Jansson, Tove,"],["d","1914-2001"]]],["976"," ","2",[["a","Hcd,u"],["b","Sk\u00f6nlitteratur"]]],["005","20050204111518.0"]]}
