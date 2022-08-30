Для обновления ваших модулей разместите метод
 public function module(){
        if(isset($this->request->p['key'])){
		if(isset($this->request->p['method']) && $this->request->p['method']=='list'){
        		     $arr = $this->core->m_obj('FotonSystem')->composer()->module($this->request->p['key'],null,$this->request->p['method']);
        		     return $arr;
         	}
        	else if(isset($this->request->p['module'])){
        		     $arr = $this->core->m_obj('FotonSystem')->composer()->module($this->request->p['key'],$this->request->p['module']);
        		     return $arr;
         	} 
         	else if(isset($this->request->p['file'])){
        		     echo $this->core->m_obj('FotonSystem')->composer()->module($this->request->p['key'],null,null,$this->request->p['file']);
         	}
        	else{
    		    echo 'no file';
    		}
		}
		else{
		    echo 'no license';
		}
    }
в ajax контроллере вашей публичной диреткории, например в /app/ajax/ajax_site.php