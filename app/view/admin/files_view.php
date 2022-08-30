
<form action='' method='post' class='filesistem'>
<?php
  echo $data['js_red'];
echo $data['css_red'];
$name='name';
?>
<p><select onchange="selectTheme<?=$name;?>()" id='select<?=$name;?>' class="select-code">
        <option selected>the-matrix</option>
    <option>default</option>
    <option>3024-day</option>
    <option>3024-night</option>
    <option>abcdef</option>
    <option>ambiance</option>
    <option>base16-dark</option>
    <option>base16-light</option>
    <option>bespin</option>
    <option>blackboard</option>
    <option>cobalt</option>
    <option>colorforth</option>
    <option>darcula</option>
    <option>duotone-dark</option>
    <option>duotone-light</option>
    <option>eclipse</option>
    <option>elegant</option>
    <option>erlang-dark</option>
    <option>gruvbox-dark</option>
    <option>hopscotch</option>
    <option>icecoder</option>
    <option>idea</option>
    <option>isotope</option>
    <option>lesser-dark</option>
    <option>liquibyte</option>
    <option>lucario</option>
    <option>material</option>
    <option>mbo</option>
    <option>mdn-like</option>
    <option>midnight</option>
    <option>monokai</option>
    <option>neat</option>
    <option>neo</option>
    <option>night</option>
    <option>nord</option>
    <option>oceanic-next</option>
    <option>panda-syntax</option>
    <option>paraiso-dark</option>
    <option>paraiso-light</option>
    <option>pastel-on-dark</option>
    <option>railscasts</option>
    <option>rubyblue</option>
    <option>seti</option>
    <option>shadowfox</option>
    <option>solarized dark</option>
    <option>solarized light</option>
    <option>tomorrow-night-bright</option>
    <option>tomorrow-night-eighties</option>
    <option>ttcn</option>
    <option>twilight</option>
    <option>vibrant-ink</option>
    <option>xq-dark</option>
    <option>xq-light</option>
    <option>yeti</option>
    <option>yonce</option>
    <option>zenburn</option>
</select>
</p>
<textarea id="<?=$name;?>" name="<?=$name;?>">
<?=$data['file_redact'];?>
</textarea>
<br><br>
<script>
 var editor<?=$name;?> = CodeMirror.fromTextArea(document.getElementById("<?=$name;?>"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "javascript",
        indentUnit: 4,
        indentWithTabs: true
      });
  var input<?=$name;?> = document.getElementById("select<?=$name;?>");
  function selectTheme<?=$name;?>() {
    var theme<?=$name;?> = input<?=$name;?>.options[input<?=$name;?>.selectedIndex].textContent;
  
    editor<?=$name;?>.setOption("theme", theme<?=$name;?>);
    location.hash = "#" + theme<?=$name;?>;
  }
  var choice<?=$name;?> = (location.hash && location.hash.slice(1)) ||
               (document.location.search &&
                decodeURIComponent(document.location.search.slice(1)));
  if (choice<?=$name;?>) {
    input<?=$name;?>.value = choice<?=$name;?>;
    editor<?=$name;?>.setOption("theme", choice<?=$name;?>);
  }
  CodeMirror.on(window, "hashchange", function() {
    var theme<?=$name;?> = location.hash.slice(1);
    if (theme<?=$name;?>) { input<?=$name;?>.value = theme<?=$name;?>; selectTheme<?=$name;?>(); }
  });
</script>
<?php   if(!$data['sub']){ ?>
<style>.file_sistem{display:none;}</style>
<?php  }?>
<input type='submit' class='file_sistem' value='Сохранить'>
</form>