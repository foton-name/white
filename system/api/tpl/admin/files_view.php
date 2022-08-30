
<form action='' method='post' class='filesistem'>
<?php   $this->widget->redactorkoda->index("700","100%",'name',$data['file_redact'],'redactor1',"0","0");?>
<?php   if(!$data['sub']){ ?>
<style>.file_sistem{display:none;}</style>
<?php  }?>
<input type='submit' class='file_sistem' value='Сохранить'>
</form>