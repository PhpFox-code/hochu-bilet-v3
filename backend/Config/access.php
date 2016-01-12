<?php

return array(
    array(
        'name' => 'Панель управления',
        'module' => 'index',
        'controller' => 'index',
        'edit' => false,
    ),
    array(
        'name' => 'Текстовые страницы',
        'module' => 'content',
        'controller' => 'content',
    ),
    array(
        'name' => 'Системные страницы',
        'module' => 'content',
        'controller' => 'control',
        'view' => false,
    ),
    array(
        'name' => 'Меню',
        'module' => 'menu',
        'controller' => 'menu',
    ),
    array(
        'name' => 'Слайдшоу',
        'module' => 'multimedia',
        'controller' => 'slider',
    ),
    array(
        'name' => 'Банерная система',
        'module' => 'multimedia',
        'controller' => 'banners',
    ),
    array(
        'name' => 'Афиша',
        'module' => 'afisha',
        'controller' => 'afisha',
    ),
    array(
        'name' => 'Бронировка билетов',
        'module' => 'afisha',
        'controller' => 'afisha_brone',
        'customView' => 'boolean',
    ),
    array(
        'name' => 'Распечатка билетов в афише',
        'module' => 'afisha',
        'controller' => 'afisha_print',
        'customView' => 'boolean',
    ),
    array(
        'name' => 'Распечатка билетов в заказе',
        'module' => 'afisha',
        'controller' => 'order_print',
        'customView' => 'boolean',
    ),
    array(
        'name' => 'Ограниченное кол-во печатей (1)',
        'module' => 'afisha',
        'controller' => 'afisha_print_unlimit',
        'customView' => 'boolean',
    ),
    array(
        'name' => 'Города',
        'module' => 'cities',
        'controller' => 'cities',
    ),
    array(
        'name' => 'Заказы',
        'module' => 'afisha',
        'controller' => 'orders',
    ),
    array(
        'name' => 'Подписчики на рассылку писем',
        'module' => 'subscribe',
        'controller' => 'subscribers',
    ),
    array(
        'name' => 'Рассылка писем',
        'module' => 'subscribe',
        'controller' => 'subscribe',
    ),
    array(
        'name' => 'Письма директору',
        'module' => 'contacts',
        'controller' => 'contacts',
    ),
    array(
        'name' => 'Бронирование билетов пользователей',
        'module' => 'brone',
        'controller' => 'brone',
    ),
    array(
        'name' => 'Заказы звонка',
        'module' => 'contact',
        'controller' => 'callback',
    ),
    array(
        'name' => 'Лента событий',
        'module' => 'log',
        'controller' => 'log',
        'edit' => false,
    ),
    array(
        'name' => 'Шаблоны писем',
        'module' => 'mailTemplates',
        'controller' => 'mailTemplates',
    ),
    array(
        'name' => 'Настройки сайта',
        'module' => 'config',
        'controller' => 'config',
        'view' => false,
    ),
    array(
        'name' => 'СЕО. Шаблон афиши',
        'module' => 'seo',
        'controller' => 'templates',
        'view' => false,
    ),
    array(
        'name' => 'СЕО. Теги для конкретных ссылок',
        'module' => 'seo',
        'controller' => 'links',
    ),
    array(
        'name' => 'СЕО. Метрика',
        'module' => 'seo',
        'controller' => 'metrika',
    ),
    array(
        'name' => 'СЕО. Счетчики',
        'module' => 'seo',
        'controller' => 'counters',
    ),
);