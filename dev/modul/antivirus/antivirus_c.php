<?php
  class Antivirus_c extends Antivirus_m{
    
    
static $var = '\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';
static $spaces = "[ \r\t\n]*";
	
	

public function StatVulnCheck ($str=null, $bAll=false) {
	    if(isset($str)){
		$regular = $bAll ? '#\$?[a-z_]+#i' : '#'.self::$var.'#';
		if (!preg_match_all($regular, $str, $regs))
			return false;
		$ar0 = $regs[0];
		$ar1 = array_unique($ar0);
		$uniq = count($ar1)/count($ar0);

		$ar2 = array();
		foreach($ar1 as $var)
		{
			if ($bAll && function_exists($var))
				$p = 0;
			elseif ($bAll && preg_match('#^[a-z]{1,2}$#i', $var))
				$p = 1;
			elseif (preg_match('#^\$?(function|php|csv|sql|__DIR__|__FILE__|__LINE__|DBDebug|DBType|DBName|DBPassword|DBHost|APPLICATION)$#i', $var))
				$p = 0;
			elseif (preg_match('#__#', $var))
				$p = 1;
			elseif (preg_match('#^\$(ar|str)[A-Z]#', $var, $regs))
				$p = 0;
			elseif (preg_match_all('#([qwrtpsdfghjklzxcvbnm]{3,}|[a-z]+[0-9]+[a-z]+)#i', $var, $regs))
				$p = strlen(implode('',$regs[0]))/strlen($var) > 0.3;
			else
				$p = 0;

			$ar2[] = $p;
		}
		$prob = array_sum($ar2)/count($ar2);
		if ($prob < 0.3)
			return false;

		if (!$bAll)
			return self::StatVulnCheck($str, true);

		return true;
	    }
	}
	
	
	public	function status(){
	    $arr=array('1'=>'Вирус','2'=>'Глобальные переменные','3'=>'Фильтр','4'=>'Файл включение','5'=>'Переменная как функция','6'=>'Массив функций','7'=>'Внутренний сканер','8'=>'Сканер');
	    return $arr;
	}
public function search ($str=null,$status=null) {
    
    global $LAST_REG;
	global $status_m;
	
	 if(isset($str) && isset(self::$spaces)){
    $status_m=explode(',',$status);

    $str = preg_replace('#/\*.*?\*/#s', '', $str);
		$str = preg_replace('#[\r\n][ \t]*//.*#m', '', $str);
		$str = preg_replace('/[\r\n][ \t]*#.*/m', '', $str);
		$str = preg_replace('#/\*.*?\*/#s', '', $str);
		$str = preg_replace('#[\r\n][ \t]*//.*#m', '', $str);
		$str = preg_replace('/[\r\n][ \t]*#.*/m', '', $str);
	$arr_st=$this->status();

		if (preg_match('#[^a-z:](eval|assert|create_function|ob_start)'.self::$spaces.'\(([^\)]*)\)#i', $str, $regs))
		{
			$LAST_REG = $regs[0];
			if (preg_match('#\$(GLOBALS|_COOKIE|_GET|_POST|_REQUEST|[a-z_]{2,}[0-9]+)#', $regs[2],$regs) && array_search('2',$status_m))
			{
				return $arr_st['2'];
			}
		}

		if (preg_match('#'.self::$var.self::$spaces.'='.self::$spaces.'\$(GLOBALS|_COOKIE|_GET|_POST|_REQUEST)'.self::$spaces.'[^\[]#', $str,$regs)  && array_search('2',$status_m))
		{
			$LAST_REG = $regs[0];
			if (self::StatVulnCheck($str))
				return $arr_st['2'];
		}

		if (preg_match('#[\'"]php://filter#i', $str, $regs) && array_search('3',$status_m))
		{
			$LAST_REG = $regs[0];
			return $arr_st['3'];
		}

		if (preg_match('#(include|require)(_once)?'.self::$spaces.'\([^\)]+\.([a-z0-9]+).'.self::$spaces.'\)#i', $str, $regs) && array_search('4',$status_m))
		{
			$LAST_REG = $regs[0];
			if ($regs[3] != 'php')
				return $arr_st['4'];
		}

		if (preg_match('#\$__+[^a-z_]#i', $str, $regs) && in_array(1,$status_m))
		{
			$LAST_REG = $regs[0];
			return $arr_st['1'];
		}

		if (preg_match('#\${["\']\\\\x[0-9]{2}[a-z0-9\\\\]+["\']}#i', $str, $regs) && in_array(1,$status_m))
		{
			$LAST_REG = $regs[0];
			return $arr_st['1'];
		}

		if (preg_match('#\$['."_\x80-\xff".']+'.self::$spaces.'=#i', $str, $regs) && in_array(1,$status_m))
		{
			$LAST_REG = $regs[0];
			return $arr_st['1'];
		}

		if (preg_match('#[a-z0-9+=/\n\r]{255,}#im', $str, $regs))
		{
			$LAST_REG = $regs[0];
			if (!preg_match('#data:image/[^;]+;base64,[a-z0-9+=/]{255,}#i', $str, $regs) && in_array(1,$status_m))
				return $arr_st['1'];
		}

		if (preg_match('#exif_read_data\(#i', $str, $regs) && in_array(1,$status_m))
		{
			$LAST_REG = $regs[0];
			return $arr_st['1'];
		}

	
		if (preg_match('#[^\\\\]'.self::$var.self::$spaces.'\(#i', $str, $regs) && in_array(5,$status_m))
		{
			$LAST_REG = $regs[0];
			if (self::StatVulnCheck($str))
				return $arr_st['5'];
		}

		if (preg_match('#'.self::$var.'('.self::$spaces.'\[[\'"]?[a-z0-9]+[\'"]?\])+'.self::$spaces.'\(#i', $str, $regs) && in_array(6,$status_m))
		{
			$LAST_REG = $regs[0];
			if (self::StatVulnCheck($str))
				return $arr_st['6'];
		}


		if (preg_match("#^.*([\x01-\x08\x0b\x0c\x0f-\x1f])#m", $str, $regs) && in_array(1,$status_m))
		{
			$LAST_REG = $regs[1];
			if (!preg_match('#^\$ser_content = #', $regs[0]))
				return $arr_st['1'];
		}

	
		if (preg_match_all('#(\\\\x[a-f0-9]{2}|\\\\[0-9]{2,3})#i', $str, $regs) && in_array(1,$status_m) && isset($regs) && isset($f))
		{
			$LAST_REG = implode(" ", $regs[1]);
			if (strlen(implode('', $regs[1]))/filesize($f) > 0.1)
				return $arr_st['1'];
		}

		if (preg_match('#file_get_contents\(\$[^\)]+\);[^a-z]*file_put_contents#mi', $str, $regs) && in_array(7,$status_m))
		{
			$LAST_REG = $regs[0];
			return $arr_st['7'];
		}


		if (preg_match('#file_get_contents\([\'"]https?://#mi', $str, $regs) && in_array(8,$status_m))
		{
			$LAST_REG = $regs[0];
			return $arr_st['8'];
		}

	

		if (preg_match('#('.self::$var.')'.self::$spaces.'\('.self::$spaces.self::$var.'#i', $str, $regs)  && in_array(2,$status_m))
		{
			$LAST_REG = $regs[0];
			$src_var = $regs[1];
			while(preg_match('#\$'.str_replace('$', '', $src_var).self::$spaces.'='.self::$spaces.'('.self::$var.')#i', $str, $regs))
			{
				$src_var = str_replace('$', '', $regs[1]);
			}
			if (preg_match('#^(GLOBAL|_COOKIE|_GET|_POST|_REQUEST)$#', $src_var))
				return $arr_st['2'];
		}
return false;
}
}

 public function search_dir_virus ($status=null) {
     if(isset($status)){
    $files = [];
    $this->dir_search_foton_antivirus($_SERVER['DOCUMENT_ROOT'],$status,$files);
    $file=implode(',',$files);
    $file=str_replace($_SERVER['DOCUMENT_ROOT'],'',$file);
   return $file;
     }
    }
	
	 public function dir_search_foton_antivirus ($path=null,&$status=null,&$files=null) {
if(isset($path)){
  if (is_dir($path)) {
            $cleanPath = array_diff(scandir($path), array('.', '..'));
            foreach ($cleanPath as $file) {
                $finalPath = $path . '/' . $file;
                $result = $this->dir_search_foton_antivirus($finalPath,$status,$files);
                if (!is_null($result)) $files[] = $result;
            }
        } else if (is_file($path) && $this->search(file_get_contents($path),$status) != false && (preg_match("/php$/i",$path) || preg_match("/ph$/i",$path)) && !preg_match("/antivirus_c\.php$/i",$path)) {
            return $path.'--'.$this->search(file_get_contents($path),$status);
        }
}	
}
    
}
?>