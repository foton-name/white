<?php
$GLOBALS["foton_setting"]['orm']['lite'] = ['text' => 'TEXT NOT NULL', 'int' => 'INTEGER  PRIMARY KEY AUTOINCREMENT',"integer"=>"INTEGER", 'medium' => 'TEXT NOT NULL'];
$GLOBALS["foton_setting"]['orm']['pgsql'] = ['text' => 'VARCHAR(16000) NOT NULL', 'integer' => 'INTEGER NOT NULL','int' => 'INTEGER NOT NULL', 'date' => 'DATE NOT NULL', 'bit' => 'BIT NOT NULL', 'polygon' => 'POLYGON NOT NULL', 'real' => 'REAL NOT NULL', 'time' => 'TIME NOT NULL', 'varchar' => 'VARCHAR(16000) NOT NULL'];
$GLOBALS["foton_setting"]['orm']['mysql'] = ['text' => 'text(64300) NOT NULL', 'int' => 'int(70) AUTO_INCREMENT','integer' => 'int(70) NOT NULL', 'date' => 'DATE NOT NULL', 'bit' => 'BIT NOT NULL', 'polygon' => 'POLYGON NOT NULL', 'real' => 'REAL NOT NULL', 'time' => 'TIME NOT NULL', 'mediumtext' => 'mediumtext NOT NULL'];
$GLOBALS["foton_setting"]['orm']['where'] = ['%'=>"[field] LIKE [%value%]",':'=>'[field] NOT LIKE [%value%]','!'=>'[field]<>[value]','<'=>'[field]<[value]','>'=>'[field]>[value]','='=>'[field]=[value]','$'=>'[field] LIKE [value%]','^'=>'[field] LIKE [%value]'];
$GLOBALS["foton_setting"]['orm']['type'] = ['text', 'int','polygon', 'date', 'bit', 'real', 'time', 'mediumtext'];
$GLOBALS["foton_setting"]['orm']['custom'] = ['GL'=>'(','GR'=>')','AND'=>'AND','OR'=>'OR'];

