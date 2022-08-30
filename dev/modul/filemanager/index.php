<link href="/dev/modul/filemanager/css/fontawesome-webfont.ttf" rel="stylesheet">
<?=$data['css_red'];?>
<?=$data['js_red'];?>
<div class='top front-work'><div class='files'>
<table>
    <tr>
        <td class='filessp'>
            <?$arr=$controller_class->spisokfile($_SERVER['DOCUMENT_ROOT'].'/');  for($i=0;$i<count($arr);$i++){ $rand=rand(0,500); if(stristr($arr[$i]['name'], '.') !== FALSE) {?>
            <p class='file<?=$i.$rand;?>'><span class='del-f' ids='<?=$i.$rand;?>' path='<?=$arr[$i]['path'];?>'></span><span class='name-f names-f' path='<?=$arr[$i]['path'];?>'><?=$arr[$i]['name'];?></span></p>
            <?}else{?>
            <p class='file<?=$i.$rand;?>'><span class='del-f' ids='<?=$i.$rand;?>' path='<?=$arr[$i]['path'];?>'></span><span class='name-f dir-f' ids='<?=$i.$rand;?>' path='<?=$arr[$i]['path'];?>'><?=$arr[$i]['name'];?></span></p>
            <div class='dir<?=$i.$rand;?> dirt'></div>
        <?}}?>
        </td> 
        <td class='td-f'><div class='plus-insert'></div><span class='plus-f'></span>
        <div class="line-f"></div>
        </td>
    </tr>
</table>
</div>
</div>
<div class="over-code-description"></div>
<?php   require_once('doc.html');?>