<?php

return [
    'task' => [
        'status' => [
            'new' => 'Новая',
            'completed' => 'Выполнена',
            'in_progress' => 'В процессе',
            'cancelled' => 'Отменена',
        ],
        'priority' => [
            'high' => 'Высокий',
            'normal' => 'Нормальный',
            'low' => 'Низкий',
        ],
    ],
    'user' => [
        'position' => [
            'manager' => 'Менеджер',
            'developer' => 'Разработчик',
            'tester' => 'Тестировщик',
        ],
    ],
    'notification' => [
        'type' => [
            'status_changed' => 'Статус изменен',
            'task_assigned' => 'Назначена',
            'overdue' => 'Просрочена',
        ],
    ],
];
