<?php
$arr = 
[
	'site'=>
	[
		'controller'=>
		[
			'Каталог'=>
			[
				'method'=>
				[
					'list'=>
					[
						'@if{isset(@this:request:g["2"])}'=>
						[
							'@arr=@core:getlist(["{Товары}","count"=>8,"page"=>(int)@this:request:g["2"]])',
							':@arr'							
						],
						'@else@'=>[
							'@arr=@core:getlist(["{Товары}","count"=>8])',
							':@arr'
						],
						'@:@'
					]
				]
			],
			'Новости'=>
			[
				'method'=>
				[
					'list'=>
					[
						'@if{isset(@this:request:g["2"])}'=>
						[
							'@arr=@core:getlist(["{Новости}","count"=>8,"page"=>(int)@this:request:g["2"]])',
							':@arr'							
						],
						'@else@'=>[
							'@arr=@core:getlist(["{Новости}","count"=>8])',
							':@arr'
						],
						'@:@'
					]
				],
				'expends'=>'Model'
			],
			'Бренды'=>
			[
				'method'=>
				[
					'list'=>
					[
						'@if{isset(@this:request:g["2"])}'=>
						[
							'@arr=@core:getlist(["{Бренды}","count"=>8,"page"=>(int)@this:request:g["2"]])',
							':@arr'							
						],
						'@else@'=>[
							'@arr=@core:getlist(["{Бренды}","count"=>8])',
							':@arr'
						],
						'@:@'
					]
				],
				'expends'=>'Model'
			],
			'Акции'=>
			[
				'method'=>
				[
					'list'=>
					[
						'@if{isset(@this:request:g["2"])}'=>
						[
							'@arr=@core:getlist(["{Акции}","count"=>8,"page"=>(int)@this:request:g["2"]])',
							':@arr'							
						],
						'@else@'=>[
							'@arr=@core:getlist(["{Акции}","count"=>8])',
							':@arr'
						],
						'@:@'
					]
				],
				'expends'=>'Model'
			]
		],
		'model'=>
		[
			'Каталог'=>
			[
				'Товары'=>
				[
					'name' => ['Название','Ссылка','Описание','Цена','Категория','Свойства','Количество','Остаток','Резерв','Фото'],
					'type' => ['text|10'],
					'field_type'=>['input|2','textarea','number','input','input','number|3','photos:{Товары}'],
					'role'=>['1','3']
				],
				'Заказы'=>
				[
					'name' => ['Пользователь','Товары','Стоимость','Оплата','Доставка','Адрес','Статус'],
					'type' => ['text|7'],
					'field_type'=>['selectall:{Пользователи},name,id','input','selectall:{Системы оплаты},{Название},{Код}','selectall:{Системы доставки},{Название},{Код}','textarea','selectall:{Статус заказа},name,code'],
					'role'=>['1','3']
				],
				'Системы оплаты'=>
				[
					'name' => ['Название','Код','Фото','Порядок'],
					'type' => ['text|4'],
					'field_type'=>['input|2','img','number'],
					'role'=>['1','3']
				],
				'Системы доставки'=>
				[
					'name' => ['Название','Код','Фото','Порядок'],
					'type' => ['text|4'],
					'field_type'=>['input|2','img','number'],
					'role'=>['1','3']
				],
				'Статус заказа'=>
				[
					'name' => ['Название','Код','Порядок'],
					'type' => ['text|3'],
					'field_type'=>['input|2','number'],
					'role'=>['1','3']
				],
				'Пользователи'=>
				[
					'name' => ["Login","ФИО","e-mail","Телефон","Адрес","Фото","Город","Избранное","Сравнение","Заказы","ИНН","КПП","Юр. адрес","Счет","Пароль","Хеш подтверждения","Активность"],
					'type' => ['text|17'],
					'field_type'=>['text|2','mail','number','textarea','img','input|3','textarea','input|2','textarea','input|3','checkbox'],
					'role'=>['1','3']
				]
			],
			'Контент'=>
			[
				'Новости'=>
				[
					'name' => ['Название','Ссылка','Описание','Категория','Фото','Дата создания'],
					'type' => ['text|5','date'],
					'field_type'=>['input|2','textarea','input','photos:{Новости}','date'],
					'role'=>['1','3']
				],
				'Страницы'=>
				[
					'name' => ['Название','Ссылка','Описание'],
					'type' => ['text|3'],
					'field_type'=>['input|2','textarea'],
					'role'=>['1','3']
				],
				'Слайдер'=>
				[
					'name' => ['Название','Ссылка','Фото','Порядок'],
					'type' => ['text|3','integer'],
					'field_type'=>['input|2','img','number'],
					'role'=>['1','3']
				],
				'Акции'=>
				[
					'name' => ['Название','Ссылка','Фото','Порядок','Описание'],
					'type' => ['text|3','integer','text'],
					'field_type'=>['input|2','img','number','textarea'],
					'role'=>['1','3']
				],
				'Бренды'=>
				[
					'name' => ['Название','Ссылка','Фото','Порядок','Описание'],
					'type' => ['text|3','integer','text'],
					'field_type'=>['input|2','img','number','textarea'],
					'role'=>['1','3']
				],
				'Соц. сети'=>
				[
					'name' => ['Название','Ссылка','Фото','Порядок'],
					'type' => ['text|3','integer'],
					'field_type'=>['input|2','img','number'],
					'role'=>['1','3']
				],
			]

		],
		'view'=>['Новости','Бренды','Акции','Каталог'],
	]
];