# Тестовое задание для PHP-разработчика

## Технические требования
 - PHP 7.0+
 - MySQL
 
## Структура репозитория
 - `load-files` - примеры файлов, с которыми работает приложение
 - `schema` - `.sql` файлы с таблицами, которые использует приложение
 - `sources` - исходники приложения

## Описание приложения
> Изучение остальных подробностей мы предоставляем лично вам :)

Имеется абстрактное приложение, задачей которого является парсинг структурированных CSV файлов 
с загрузкой содержимого
в базу данных. 

Входная точка приложения - файл `sources/cli.php`.
Пример выполнения:

```bash
$ php sources/cli.php load-files/market.eu.20180227
```

В результате выполнения часть столбцов файла `load-files/market.eu.20180227` будут загружены в таблицу `market_data`
по порядку, описанному в таблице соответствия:

DB column | CSV column[index]
------------ | -------------
id_value | 0
price | 1
is_noon | 5
update_data | `current date`


## Описание задания
Задание разбито на две задачи, которые следует выполнять по порядку:
1. Починить приложение
2. Дополнить приложение новым функционалом


# Задача №1: Починить и улучшить
> Это задание справедливо **только** в случае выполнения скрипта с файлом `market.eu.%Ymd%`

## Требования к окружению
 - База данных с таблицей `market_data` из файла `schema/market_data.sql`
 - Файл `market.eu.20180227`

## Описание задачи
>Приветствуется рефакторинг частей кода, которые вызывают у вас сомнения

Предположим, что произошли какие-либо невероятные события, после чего проект перестал функционировать
должным образом.  Скрипт отрабатывает, но в базе данных 
новые записи не появляются.

Наши наблюдения:
 - Скрипт отрабатывает без ошибок
 - В базе данных нет записей с исходного файла
 - Лог файл не содержит сообщений типа WARN/DEBUG, значит, все отработало верно
 - Информация в лог файле перезаписывается с каждым запуском скрипта, ранее всегда дописывалась
 
 
**Необходимо** починить скрипт:
 
1. Данные из файла `market.eu.20180227` в результате выполнения скрипта должны быть успешно загруженны в базу данных
2. Ошибки должны быть верно обработаны
3. Логгер должен записывать все типы ошибок, при этом дописывая в лог-файл, а не перезаписывая его

# Задача №2: Новый функционал
>Это задание справедливо для случаев выполнения скрипта и с `market.eu.%Ymd%` и с `market.us.%Ymd%` файлами

>К этому заданию стоит приступать после выполнения Задания №1 с изменениями, которые были внесены вами

## Требования к окружению
 - База данных с таблицей `market_data` из файла `schema/market_data.sql`
 - База данных с таблицей `markets` из файла `schema/markets.sql`, а так же значениями для таблицы из того же скрипта
 - Файлы `market.eu.20180227` и `market.us.20180228`
 
## Описание задачи

Появились новые требования к приложению. Нужно:
1. Обрабатывать новый тип файлов - `market.us.%date%`. Для нового типа файла есть важная особенность - мы должны загружать `id_value` для таблицы `market_data` 
не из первой колонки, как для `market.eu.` файлов, а с последней
2. Использовать новую таблицу - `markets` для валидации сохраняемых данных из загружаемого файла. Нужно добавить новый слой валидации. В процессе парсинга файла проверять, есть ли в таблице `markets` 
значение `id_value` сопоставимое с тем, что мы загружаем для поля `id_value ` таблицы `market_data`. Если нет - строчку пропускаем, и наоборот
3. Для поля `update_date` таблицы  `market_data` брать дату из названия файла - `market.us.**20180228**`
 формата Год-месяц-день. Например, для значений из файла `market.eu.20180227` поле `update_date` таблицы `market_data` должно быть `2018-02-27`
4. Форматировать цену для поля `price` таблицы `market_data`. Сейчас значения из загружаемого файла для поля `price` таблицы `market_data` приходят как `000248` - с тремя нулями в начале.
Нужно добавить форматирование, убирающие эти нули
5. Писать в лог количество строк, которое было загружено из файла
 
Обновленная таблица соответствия:

 DB column | CSV column[index]
 ------------ | -------------
 id_value | 0 для `market.eu.` и 6 для  `market.us.`
 price | 1
 is_noon | 5
 update_date | date с названия файла
 market_id | `markets.market_id` для соответствующего `id_value`
 
