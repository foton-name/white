CREATE TABLE graph (id serial NOT NULL PRIMARY KEY,  tables  VARCHAR(16000),  wheres  VARCHAR(16000),  fieldname  VARCHAR(16000),  color  VARCHAR(16000),  kod  VARCHAR(16000),  funcs  VARCHAR(16000),  names  VARCHAR(16000),  graph  VARCHAR(16000),  fields  VARCHAR(16000));

CREATE TABLE buttonred (
  id serial NOT NULL PRIMARY KEY,
  func VARCHAR(16000) NOT NULL,
  lists VARCHAR(16000) NOT NULL,
  format VARCHAR(16000) NOT NULL,
  text VARCHAR(16000) NOT NULL
);
CREATE TABLE router (
  id serial NOT NULL PRIMARY KEY,
  routs VARCHAR(16000) NOT NULL,
  view VARCHAR(16000) NOT NULL
);

CREATE TABLE mapofsite (
  id serial NOT NULL PRIMARY KEY,
  views VARCHAR(16000) NOT NULL,
  tables VARCHAR(16000) NOT NULL,
  sections VARCHAR(16000) NOT NULL
);
CREATE TABLE exfield (
  id serial NOT NULL PRIMARY KEY,
  name VARCHAR(16000) NOT NULL,
  excode VARCHAR(16000) NOT NULL
);

CREATE TABLE html (
  id serial NOT NULL PRIMARY KEY,
  name VARCHAR(16000) NOT NULL,
  kod VARCHAR(16000) NOT NULL,
  argument VARCHAR(16000) NOT NULL,
  function VARCHAR(16000) NOT NULL,
  html VARCHAR(16000) NOT NULL
);
CREATE TABLE mediateka (
  id serial NOT NULL PRIMARY KEY,
  kod VARCHAR(16000) NOT NULL,
  photos VARCHAR(16000) NOT NULL
);

CREATE TABLE menu (
  id serial NOT NULL PRIMARY KEY,
  name VARCHAR(16000) NOT NULL,
  href VARCHAR(16000) NOT NULL,
  title VARCHAR(16000) NOT NULL,
  sections VARCHAR(16000) NOT NULL,
  sorts VARCHAR(16000) NOT NULL
);

CREATE TABLE seo (
  id serial NOT NULL PRIMARY KEY,
  dir VARCHAR(16000) NOT NULL,
  title VARCHAR(16000) NOT NULL,
  keywords VARCHAR(16000) NOT NULL,
  description VARCHAR(16000) NOT NULL,
  views VARCHAR(16000) NOT NULL
);


CREATE TABLE role (
  id serial NOT NULL PRIMARY KEY,
  name VARCHAR(16000) NOT NULL,
  text VARCHAR(16000) NOT NULL
);

CREATE TABLE taxonomy (
  id serial NOT NULL PRIMARY KEY,
  name VARCHAR(16000) NOT NULL,
  kod VARCHAR(16000) NOT NULL,
  prefix VARCHAR(16000) NOT NULL,
  body VARCHAR(16000) NOT NULL,
  suffix VARCHAR(16000) NOT NULL
);

CREATE TABLE user_inc (
  id serial NOT NULL PRIMARY KEY,
  role VARCHAR(16000) NOT NULL,
  name VARCHAR(16000) NOT NULL,
  login VARCHAR(16000) NOT NULL,
  pass VARCHAR(16000) NOT NULL
);
INSERT INTO buttonred (id,func,lists,format,text) VALUES (2,'family','arial,times,verdana,tahoma','select','FontName');

INSERT INTO buttonred (id,func,lists,format,text) VALUES (3,'Color','orange,green,black,white','select','foreColor');

INSERT INTO buttonred (id,func,lists,format,text) VALUES (4,'size','2,3,4,5','select','fontSize');

INSERT INTO buttonred (id,func,lists,format,text) VALUES (5,'background','orange,red,black,white','select','hiliteColor');

INSERT INTO buttonred (id,func,lists,format,text) VALUES (6,'href','#','link','createLink');

INSERT INTO exfield (id,name,excode) VALUES (2,'mail','mail@mail.ru');

INSERT INTO exfield (id,name,excode) VALUES (3,'copy','copyright 2019 &amp;copy;');

INSERT INTO exfield (id,name,excode) VALUES (4,'adr','Краснодар ул. Красная д. 153');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (4,'Текстовое поле','textarea','0','0','<span class="default_span">[[lang]]</span><textarea name="[[name]]">[[value]]</textarea>');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (16,'Текст','input','argument','0','<span class="default_span">[[lang]]</span><input type="text" name="[[name]]" value="[[value]]" placeholder="[[name]]" >');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (18,'Список','select','table','$mass=$this->select_db($table);$sel="<span class=\"default_span\">[[lang]]</span><select name=\"[[name]]\"><option></option>";foreach($mass as $val){    if("[[value]]"==$val["text"]){        $select="selected";    }else{        $select="";    }        $sel.="<option ".$select.">".$val["text"]."</option>";        }$sel.="</select>";return $sel;','2');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (21,'Фото','img','0','if(stristr("[[value]]","/")){$is="<span class=\"default_span\">[[lang]]</span><img src=\"/app/view/".$GLOBALS["foton_setting"]["sitedir"]."/[[value]]\" class=\"i_foto_up\">";}else{	$is="";}       $is.="<span class=\"default_span\">[[lang]]</span><input type=\"hidden\" class=\"img_up\" name=\"[[name]]\" value=\"[[value]]\"><br>"; $is.="<input type=\"file\" name=\"[[name]]\" value=\"[[value]]\"><br>";		return $is;','2');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (22,'html редактор','html','0','return $this->tpl_html("html")->htmlredactor("[[value]]","[[name]]");','2');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (23,'Видео','video','0','if("[[value]]"!="[[name]]"){$is="<span class=\"default_span\">[[lang]]</span><iframe width=\"100%\" height=\"370\" src=\"[[value]]\" frameborder=\"0\" allowfullscreen></iframe>";}else{	$is="<span class=\"default_span\">[[lang]]</span>";}       $is.="<input type=\"text\" name=\"[[name]]\" value=\"[[value]]\"><br>"; return $is;','2');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (24,'Список2','select2','table','$mass=$this->select_db($table); $sel="<span class=\"default_span\">[[lang]]</span><select name=\"[[name]]\">"; foreach($mass as $val){   if("[[value]]"==$val["name"]){        $select="selected";    }else{        $select="";   }      $sel.="<option ".$select.">".$val["name"]."</option>";}$sel.="</select>";return $sel;','2');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (25,'Текст список','text','0','0','<span class="text_sp">[[value]]</span>');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (26,'Ссылка список','href','0','0','<a href="" class="href_sp ids-include">[[value]]</a>');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (27,'Цвет','color','0','0','<span class="default_span">[[lang]]</span><input type="color" name="[[name]]" value="[[value]]">');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (28,'Таксономия','taxonomy','table','return $this->tpl_html("html")->taxonomy($table,"[[name]]","[[value]]");','2');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (29,'Число','number','0','0','<span class="default_span">[[lang]]</span><input type="number" name="[[name]]" value="[[value]]">');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (32,'E-mail','mail','0','0','<span class="default_span">[[lang]]</span><input type="mail" name="[[name]]" value="[[value]]">');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (33,'Телефон','tel','0','0','<span class="default_span">[[lang]]</span><input type="tel" name="[[name]]" value="[[value]]">');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (34,'Дата','date','0','0','<span class="default_span">[[lang]]</span><input type="date" name="[[name]]" value="[[value]]">');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (40,'Несколько фото','photos','table','$is=""; $files="";  if(stristr("[[value]]","/")){$arr_f="[[value]]"; $arr_f=explode("%%%",$arr_f); if(is_array($arr_f) && count($arr_f)>0){foreach($arr_f as $n){ if($n!=""){$files.="<div class=\"over_foto\"><div class=\"delfoto_foton\" delfotos=\"/app/view/".$GLOBALS["foton_setting"]["sitedir"]."/".$n."\"></div><img src=\"/app/view/".$GLOBALS["foton_setting"]["sitedir"]."/".$n."\" ></div>"; }}}}else{}   if(stristr("[[value]]","/")){  $files.="<hr><input type=\"hidden\" class=\"upfdels\" fname=\"[[name]]\" value=\"[[value]]\"> <div class=\"upfoto\" table=\"".$table."\" img=\"[[name]]\" ids=\"1\" pathabs=\"".$GLOBALS["foton_setting"]["path"]."\">Добавить фото</div> <input type=\"file\" class=\"upfotos\" multiple=\"true\">";  $is.="<div class=\"fotos\">".$files."</div><input type=\"hidden\" class=\"img_up\" fname=\"[[name]]\" value=\"[[value]]\"><br>"; $is.="<input type=\"file\" name=\"[[name]][]\" value=\"[[value]]\" multiple=\"multiple\" style=\"display:none;\"><br>";}else{$is.="<div class=\"fotos\">".$files."</div><input type=\"hidden\" class=\"img_up\" fname=\"[[name]]\" value=\"[[value]]\"><br>"; $is.="<input type=\"file\" name=\"[[name]][]\" value=\"[[value]]\" multiple=\"multiple\" ><br>"; }   return $is;','2');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (43,'id','hidden','0','0','<input type="hidden" name="[[name]]" value="[[value]]">');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (45,'Вывод Id','ids','0','0','[[value]]');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (46,'Кнопка сортировки','submit','0','0','<input type="submit" name="sort_field" value="[[name]]">');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (47,'Аудио','audio','0','if(stristr("[[value]]","/")){$is="<span class=\"default_span\">[[lang]]</span><audio src=\"/app/view/".$GLOBALS["foton_setting"]["sitedir"]."/[[value]]\" controls class=\"i_foto_up\"></audio>";}else{	$is="";}       $is.="<span class=\"default_span\">[[lang]]</span><input type=\"hidden\" class=\"img_up\" name=\"[[name]]\" value=\"[[value]]\"><br>"; $is.="<input type=\"file\" name=\"[[name]]\" value=\"[[value]]\"><br>";		return $is;','2');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (48,'Галочка','checkbox','0','if("[[value]]"=="on"){$check="checked";}else{$check="";}return "<br><span class=\"default_span\">[[lang]]</span><input name=\"[[name]]\" type=\"hidden\" value=\"off\"><input type=\"checkbox\" name=\"[[name]]\" value=\"on\" ".$check."><br>";','2');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (49,'Список new','selectall','table,field,id','$arr=$this->select_db($table); $sel="<span class=\"default_span\">[[lang]]</span><select name=\"[[name]]\">"; foreach($arr as $val){   if("[[value]]"==$val[$id]){        $select="selected";    }else{        $select="";   }      $sel.="<option ".$select." value=\"".$val[$id]."\">".$val[$field]."</option>";}$sel.="</select>";return $sel;','2');

INSERT INTO html (id,name,kod,argument,function,html) VALUES (50,'Шаблон ссылки','link','href','if("[[value]]"==""){return "<span class=\"default_span\">[[lang]]</span><input type=\"text\" name=\"[[name]]\" value=\"[[value]]\">";}else{$href = str_replace("@href@","[[value]]",$href);return "<a href=\"".$href."\" target=\"_blank\" class=\"order_hrefs\">[[lang]]</a><input type=\"hidden\" name=\"[[name]]\" value=\"[[value]]\">";}','2');

INSERT INTO seo (id,dir, title,keywords,description,views) VALUES (1,'site', 'Главная страница','Главная страница','Главная страница','html');

INSERT INTO seo (id,dir, title,keywords,description,views) VALUES (2,'site', 'Карта сайта','Карта сайта','Карта сайта','sitemap');



INSERT INTO role (id,name,text) VALUES (1,'1','Администратор');

INSERT INTO role (id,name,text) VALUES (2,'3','Контент-менеджер');

INSERT INTO role (id,name,text) VALUES (3,'2','Сео-специалист');

INSERT INTO role (id,name,text) VALUES (4,'0','Удаленный');
INSERT INTO role (id,name,text) VALUES (5,'5','Программист');

INSERT INTO taxonomy (id,name,kod,prefix,body,suffix) VALUES (1,'Список','select','<select ||taxonomy||><option value="">---</option>','<option value="||val||">||cat||</option>','</select>');

INSERT INTO taxonomy (id,name,kod,prefix,body,suffix) VALUES (2,'Текст','text','0','&lt;p&gt;||cat||&lt;/p&gt;&lt;input type=&quot;text&quot;  ||taxonomy|| value=&quot;||val||&quot; &gt;','0');

INSERT INTO taxonomy (id,name,kod,prefix,body,suffix) VALUES (3,'Цвет','color','0','&lt;p&gt;||cat||&lt;/p&gt;&lt;input type=&quot;color&quot;  ||taxonomy|| value=&quot;||val||&quot;&gt;','0');

INSERT INTO taxonomy (id,name,kod,prefix,body,suffix) VALUES (4,'Галочка','checkbox','0','&lt;fieldset&gt;&lt;legend&gt;||cat||&lt;/legend&gt;&lt;input type=&quot;checkbox&quot;  ||taxonomy|| value=&quot;||val||&quot;&gt;&lt;/fieldset&gt;','0');

INSERT INTO taxonomy (id,name,kod,prefix,body,suffix) VALUES (5,'Радио-кнопка','radio','0','&lt;fieldset&gt;&lt;legend&gt;||cat||&lt;/legend&gt;&lt;input type=&quot;radio&quot;  ||taxonomy|| value=&quot;||val||&quot;&gt;&lt;/fieldset&gt;','0');
INSERT INTO user_inc (id,role,name,login,pass) VALUES (1,'1','программист','demo','fe01ce2a7fbac8fafaed7c982a04e229');