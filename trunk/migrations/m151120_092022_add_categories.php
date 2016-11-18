<?php

class m151120_092022_add_categories extends \yii\mongodb\Migration
{

    public function up()
    {
        $list = [
            'Мастера по ремонту' => [
                'Благоустройство территории',
                'Бурение скважин',
                'Возведение стен и перегородок',
                'Вывоз мусора',
                'Геодезические работы',
                'Декорирование интерьера',
                'Демонтаж сооружений и конструкций',
                'Дизайн интерьера',
                'Другие услуги',
                'Земляные работы',
                'Изготовление и монтаж',
                'Изготовление рекламы',
                'Изоляционные работы',
                'Кладка печей, каминов, барбекю',
                'Кровельные работы',
                'Ландшафтный дизайн',
                'Малярные и штукатурные работы',
                'Межевание и кадастр',
                'Мелкий бытовой ремонт',
                'Монтаж вентиляции и кондиционеров',
                'Монтаж канализации',
                'Монтаж окон',
                'Монтаж потолков',
                'Монтаж систем отопления и водоснабжения',
                'Монтаж труб',
                'Наружное освещение',
                'Настройка и ремонт компьютеров',
                'Обшивка стен',
                'Оклейка обоями',
                'Остекление балконов и лоджий',
                'Переезд',
                'Плотники',
                'Подключение и ремонт бытовой техники',
                'Проектные услуги',
                'Ремонт ванной и туалета',
                'Ремонт и диагностика автомобиля',
                'Ремонт квартир и коттеджей',
                'Ремонт кухни',
                'Ремонт офисов',
                'Реставрация ванн',
                'Сантехнические работы',
                'Сварщики',
                'Слесари',
                'Сметные работы',
                'Столяры',
                'Строительство бань и саун',
                'Строительство бассейнов, водоёмов и фонтанов',
                'Строительство домов',
                'Строительство заборов',
                'Строительство и ремонт дорог',
                'Строительство фундамента',
                'Токари',
                'Укладка плитки',
                'Услуги разнорабочих',
                'Установка альтернативных источников энергии',
                'Установка ворот',
                'Установка дверей',
                'Установка и замена замков',
                'Установка лестниц',
                'Установка систем «Умный дом»',
                'Устройство полов',
                'Фасадные работы',
                'Художественная ковка и литьё',
                'Электромонтажные работы',
                'Ювелирные работы',
            ],
            'Репетиторы' => [
                'Business English',
                'FCE',
                'GMAT',
                'GRE',
                'IELTS',
                'ISTQB',
                'SAT',
                'TOEFL',
                'Актёрское мастерство',
                'Алгебра',
                'Английский язык',
                'Арабский язык',
                'Биология',
                'Бухгалтерский учёт',
                'Венгерский язык',
                'Вокал',
                'Все носители языков',
                'Высшая математика',
                'География',
                'Геометрия',
                'Гитара',
                'Голландский язык',
                'Греческий язык',
                'Датский язык',
                'Другие предметы',
                'Иврит',
                'Информатика',
                'Испанский язык',
                'История',
                'Итальянский язык',
                'Китайский язык',
                'Компьютерная графика',
                'Корейский язык',
                'Латынь',
                'Литература',
                'Логика',
                'Логопеды',
                'Макроэкономика',
                'Математика',
                'Математический анализ',
                'Менеджмент',
                'Микроэкономика',
                'Музыка',
                'Начальная школа',
                'Начертательная геометрия',
                'Немецкий язык',
                'Норвежский язык',
                'Обществознание',
                'Оригами',
                'Подготовка к школе',
                'Польский язык',
                'Португальский язык',
                'Правоведение',
                'Программирование',
                'Психология',
                'Рисование',
                'Риторика',
                'Русский язык',
                'Сербский язык',
                'Скрипка',
                'Сольфеджио',
                'Сопротивление материалов',
                'Статистика',
                'Теоретическая механика',
                'Теория вероятностей',
                'Турецкий язык',
                'Украинский язык',
                'Физика',
                'Философия',
                'Финский язык',
                'Флейта',
                'Фортепиано',
                'Французский язык',
                'Химия',
                'Хинди',
                'Черчение',
                'Чешский язык',
                'Шахматы',
                'Шведский язык',
                'Эконометрика',
                'Экономика',
                'Электротехника',
                'Японский язык',
            ],
            'Мастера красоты' => [
                'Биоревитализация',
                'Выпрямление волос',
                'Диетология',
                'Дизайн ногтей',
                'Другие услуги',
                'Загар',
                'Коррекция бровей',
                'Косметология',
                'Ламинирование волос',
                'Лечение волос',
                'Макияж',
                'Маникюр',
                'Массаж',
                'Мезотерапия',
                'Наращивание волос',
                'Наращивание ногтей',
                'Наращивание ресниц',
                'Обертывание',
                'Окрашивание волос',
                'Педикюр',
                'Пилинг',
                'Пирсинг',
                'Пошив одежды',
                'Причёски',
                'Свадебный стилист',
                'Стилист-имиджмейкер',
                'Стрижка',
                'Тату',
                'Татуаж',
                'Укладка',
                'Фотограф',
                'Химическая завивка',
                'Эпиляция',
            ],
            'Врачи' => [
                'Абдоминальная хирургия',
                'Акупрессура',
                'Акушерство',
                'Аллерген-специфическая иммунотерапия',
                'Аллергология',
                'Аллергопробы',
                'Ангиология',
                'Андрология',
                'Артрология',
                'Артроскопия',
                'Блокады',
                'Вакуумный массаж',
                'Ведение беременности',
                'Венерология',
                'Внутрисуставные инъекции',
                'Гастроскопия',
                'Гастроэнтерология',
                'Гематология',
                'Генетики',
                'Гепатология',
                'Гештальт-терапия',
                'Гинекология',
                'Денситометрия',
                'Дерматовенерология',
                'Дерматология',
                'Дерматология /косметология',
                'Дерматология /онкология',
                'Детская аллергология',
                'Детская колопроктология',
                'Диабетология',
                'Диетология',
                'Доплерография',
                'Другие услуги',
                'Иммунология',
                'Имплантация зубов',
                'Имплантология',
                'Инфекционисты',
                'Исследование функции внешнего дыхания',
                'Кардиология',
                'Кардиохирургия',
                'Кинезиология',
                'Колоноскопия',
                'Колопроктология',
                'Кольпоскопия',
                'Компьютерная томография',
                'Контурная пластика лица',
                'Коррекция морщин',
                'Косметология',
                'Криотерапия',
                'Лазерная хирургия',
                'Лазерная шлифовка кожи',
                'Лазерное омоложение лица',
                'Лазеротерапия',
                'Лапароскопия',
                'Лапароцентез',
                'Лечебная физическая культура',
                'Лечение акне',
                'Лечение артрита',
                'Лечение артроза',
                'Лечение атеросклероза',
                'Лечение болевого синдрома',
                'Лечение бронхиальной астмы',
                'Лечение бронхита',
                'Лечение варикозной болезни вен нижних конечностей',
                'Лечение варикоцеле',
                'Лечение воспалительных заболеваний органов малого таза',
                'Лечение гидроцеле',
                'Лечение гипнозом',
                'Лечение головокружения',
                'Лечение депрессии',
                'Лечение диабетической стопы',
                'Лечение дорсопатии',
                'Лечение заболеваний артерий',
                'Лечение заболеваний желудочно-кишечного тракта',
                'Лечение заболеваний кишечника',
                'Лечение заболеваний кожи',
                'Лечение заболеваний мочеполовой системы',
                'Лечение заболеваний позвоночника',
                'Лечение заболеваний прямой кишки',
                'Лечение заболеваний суставов',
                'Лечение заикания',
                'Лечение зппп',
                'Лечение климакса',
                'Лечение мастопатии',
                'Лечение межпозвонковой грыжи',
                'Лечение метаболического синдрома',
                'Лечение мигрени',
                'Лечение молочных желез',
                'Лечение мужского бесплодия',
                'Лечение нарушений эякуляции',
                'Лечение невралгии',
                'Лечение невроза',
                'Лечение недержания мочи',
                'Лечение ожирения',
                'Лечение остеоартроза',
                'Лечение остеопороза',
                'Лечение остеохондроза',
                'Лечение панических атак',
                'Лечение патологии опорно-двигательного аппарата',
                'Лечение пиелонефрита',
                'Лечение плоскостопия',
                'Лечение пневмонии',
                'Лечение преждевременного семяизвержения',
                'Лечение простатита',
                'Лечение психовегетативных расстройств',
                'Лечение психосоматических расстройств',
                'Лечение синдрома гиперактивности',
                'Лечение синдрома головной боли',
                'Лечение сколиоза',
                'Лечение спортивных травм',
                'Лечение страхов',
                'Лечение тиков',
                'Лечение трофических язв',
                'Лечение фимоза',
                'Лечение цистита',
                'Лечение эндокринного бесплодия',
                'Лечение эндометриоза',
                'Лечение энуреза',
                'Лечение эректильной дисфункции',
                'Магнитотерапия',
                'Маммография',
                'Маммология',
                'Маммология',
                'Мануальная терапия',
                'Мезотерапия',
                'Микология',
                'Микроиглотерапия',
                'Микрохирургия',
                'Минифлебэктомия',
                'Миостимуляция',
                'МРТ',
                'Наркология',
                'Неврология',
                'Нейрохирургия',
                'Неонатология',
                'Нефрология',
                'Нутрициология',
                'Озонотерапия',
                'Онкогинекология',
                'Онкология',
                'Оперативная флебология',
                'Ортодонтия',
                'Ортопедия',
                'Остеопатия',
                'Остеосинтез',
                'Отоневрология',
                'Оториноларингология',
                'Офтальмология',
                'Пародонтология',
                'Педиатрия',
                'Плазмолифтинг',
                'Планирование беременности',
                'Пластическая хирургия',
                'Плевральная пункция',
                'Постизометрическая релаксация',
                'Профпатология',
                'Психиатрия',
                'Психоанализ',
                'Психодрама',
                'Психологическая помощь',
                'Психологическая помощь при стрессах',
                'Психология',
                'Психотерапия',
                'Пульмонология',
                'ПЦР диагностика',
                'Ревматология',
                'Ректороманоскопия',
                'Рентгенография',
                'Рентгенология',
                'Реоэнцефалография',
                'Репродуктивная эндокринология',
                'Репродуктология',
                'Рефлексотерапия',
                'Риносептопластика',
                'Сексология',
                'Семейная психотерапия',
                'Септопластика',
                'Символдрама',
                'Склеротерапия',
                'СМАД',
                'Спортивная медицина',
                'Стоматология',
                'Сурдология',
                'Терапевты',
                'Тест на хеликобактер',
                'Торакальная хирургия',
                'Точечный массаж',
                'Травматология',
                'Трансфузиология',
                'Трихология',
                'Удаление атером',
                'Удаление доброкачественных новообразований кожи',
                'Удаление кондилом',
                'Удаление папиллом',
                'Удаление рубцов',
                'Ударно-волновая терапия',
                'УЗИ 3Д',
                'УЗИ 4Д',
                'УЗИ диагностика',
                'УЗИ по беременности',
                'Ультразвуковая терапия',
                'Урогинекология',
                'Урология',
                'Фармакопунктура',
                'Физиотерапия',
                'Флебология',
                'Флебэктомия',
                'Фотодинамическая терапия',
                'Функциональная диагностика',
                'Хирурги',
                'Хирургическая маммология',
                'Хирургическая онкология',
                'Хирургическая эндокринология',
                'Холтер',
                'Челюстно-лицевая хирургия',
                'ЭКГ',
                'Экспертиза временной нетрудоспособности',
                'Экстракорпоральное оплодотворение',
                'Электролиполиз',
                'Электрофорез',
                'Эндокринология',
                'Эндопротезирование',
                'Эндоскопия',
                'Эпилептология',
                'ЭхоКГ',
                'ЭЭГ',
            ],
            'Спортивные тренеры' => [
                'Айкидо',
                'Аквааэробика',
                'Акробатика',
                'Армрестлинг',
                'Аэробика',
                'Бадминтон',
                'Балет',
                'Баскетбол',
                'Беговые лыжи',
                'Бильярд',
                'Бодибилдинг',
                'Бодифлекс',
                'Бои без правил',
                'Бокс',
                'Большой теннис',
                'Брейк-данс',
                'Велоспорт',
                'Вин чун',
                'Водное поло',
                'Волейбол',
                'Вольная борьба',
                'Восточные танцы',
                'Гольф',
                'Горные лыжи',
                'Гоу-гоу',
                'Гребля',
                'Греко-римская борьба',
                'Дайвинг',
                'Детский массаж',
                'Джиу-джитсу',
                'Дзюдо',
                'Другие дисциплины',
                'Йога',
                'Йога Айенгара для начинающих',
                'Калланетика',
                'Капоэйра',
                'Карате',
                'Кендо',
                'Кикбоксинг',
                'Классический массаж',
                'Клубные танцы',
                'Кобудо',
                'Конный спорт',
                'Кроссфит',
                'Кудо',
                'Кундалини-йога для начинающих',
                'Латиноамериканские танцы',
                'Лечебный массаж',
                'ЛФК',
                'Лёгкая атлетика',
                'Массаж',
                'Микс файт',
                'Народные танцы',
                'Настольный теннис',
                'ОФП',
                'Пауэрлифтинг',
                'Пилатес',
                'Плавание',
                'Ритмика',
                'Рукопашный бой',
                'Самбо',
                'Самооборона',
                'Синхронное плавание',
                'Сквош',
                'Сноуборд',
                'Современные танцы',
                'Спортивный массаж',
                'Стретчинг',
                'Тайский бокс',
                'Тайцзицюань',
                'Танцы для начинающих',
                'Тхэквондо',
                'Тяжёлая атлетика',
                'Ушу',
                'Фехтование',
                'Фигурное катание',
                'Фитбол',
                'Фитнес',
                'Хатха-йога для начинающих',
                'Хип-хоп',
                'Хоккей',
                'Хореография',
                'Художественная гимнастика',
                'Цигун',
                'Шейпинг',
                'Экзотический массаж',
            ],
            'Автоинструкторы' => [
                'Audi',
                'BMW',
                'Chevrolet',
                'Citroen',
                'Daewoo',
                'Fiat',
                'Ford',
                'Honda',
                'Hyundai',
                'Kia',
                'Mazda',
                'Mercedes-Benz',
                'Mitsubishi',
                'Nissan',
                'Opel',
                'Peugeot',
                'Renault',
                'Skoda',
                'Subaru',
                'Suzuki',
                'Toyota',
                'Volkswagen',
                'Volvo',
                'ВАЗ',
                'Вождение (АКПП)',
                'Вождение (МКПП)',
                'Вождение квадроцикла',
                'Вождение мотоцикла',
                'Вождение снегохода',
                'Другие дисциплины',
                'ПДД (теория)',
                'Пилотирование',
                'Ремонт авто',
                'Судовождение',
                'Экстремальное вождение',
            ],
            'Артисты' => [
                'Ансамбли',
                'Ведущие/Тамада',
                'Диджеи',
                'Звезды эстрады',
                'Кейтеринг',
                'Музыкальные группы',
                'Музыканты-инструменталисты',
                'Певцы',
                'Подрядчики',
                'Прокат и аренда',
                'Танцоры',
                'Фото / Видео / Аудио',
                'Художники',
                'Цирковые артисты',
                'Шоу',
            ],
            'Ветеринары' => [
                'Акушерство животных',
                'Вакцинация животных',
                'Ветеринарная дерматология',
                'Ветеринарная неврология',
                'Ветеринарная офтальмология',
                'Ветеринарная стоматология',
                'Ветеринарная терапия',
                'Ветеринарная травматология',
                'Ветеринарная хирургия',
                'Кастрация животных',
                'Лечение грызунов',
                'Лечение кошек',
                'Лечение собак',
                'Лечение экзотических животных',
                'Онкология у животных',
                'Стерилизация животных',
                'Усыпление животных',
            ],
            'IT-фрилансеры' => [
                'SEO-специалисты',
                'Верстальщики',
                'Дизайнеры',
                'Другие услуги',
                'Копирайтеры',
                'Маркетологи',
                'Полиграфисты',
                'Программисты',
                'Системные администраторы',
                'Тестировщики',
            ],
            'Домашний персонал' => [
                'Водители',
                'Гувернантки',
                'Домработницы',
                'Другие услуги',
                'Няни',
                'Охранники',
                'Повара',
                'Садовники',
                'Сиделки',
                'Уборщицы',
                'Экономки',
            ],
            'Бухгалтеры и юристы' => [
                'Адвокаты',
                'Аудиторы',
                'Бизнес-консультанты',
                'Бизнес-тренеры',
                'Бухгалтеры',
                'Другие услуги',
                'Логисты',
                'Налоговые консультанты',
                'Хэдхантеры',
                'Юристы',
            ]
        ];
        foreach ($list as $catTitle => $subcats)
        {
            $category = new \app\models\Category();
            $category->title = $catTitle;
            $category->is_deleted = false;
            $category->parent_id = NULL;
            $category->save(false);
            foreach ($subcats as $subcatTitle)
            {
                $subcategory = new \app\models\Category();
                $subcategory->title = $subcatTitle;
                $subcategory->is_deleted = false;
                $subcategory->parent_id = $category->_id;
                $subcategory->save(false);
            }
        }
    }

    public function down()
    {
        echo "m151120_092022_add_categories cannot be reverted.\n";

        return false;
    }

}
