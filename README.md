<p align="center">
#Pokemon Control System (Leber) 
</p>


## О проекте

Проект представляет собой систему для управления покемонами. Каждый покемон связан с какой-либо локацией и может иметь несколько способностей. 

## Структура

### Способности

Создание способности требует имя способности на английском и русском (name, name_ru), а также изображение (image). Способности не уникальны. Возможно создание нескольких способностей с пересекающимися именами. 

### Регионы

Существуют список регионов. Если региона не существует в списке, он не может быть добавлен в базу данных.

### Локации

Создание локации требует указания имени, идентификатора региона (name, region_id). Поддерживается вложенность локаций (должен быть передан parent_id). При передаче идентификатора родительской локации, идентификатор региона игнорируется и для локации указывается регион родителя. 

### Покемоны

Для создания покемона обязательным являются:
- Уникальное имя
- Изображение
- Идентификатор локации
- Идентификатор одной или нескольких способностей
- Форма (строго из списка форм) 


## База данных

``` 
php artisan db:seed
``` 

По умолчанию создаёт несколько способностей. Также создаёт все доступные регионы, несколько локаций, несколько вложенных одноуровневых локаций. 
После этого создаются покемоны, с уже существующими локациями и способностями.
Может занять некоторое время, так как вместе с созданием записей загружаются картинки покемонов и заглушки для способностей.

## Тесты

Проект включает в себя тесты для каждого api ресурса. 

Для успешного выполнения тестов необходимо выполнить наполнение базы данных.


## Фильтрация и сортировка

- Реализована фильтрация покемонов по имени региона (/api/pokemon/?filter[region]=volcano)
- Фильтрация локаций по региону (/api/location/?filter\[region.name]=volcano)
- Сортировка локаций по региону (/api/location/?sort=region)
- Сортировка регионов по имени (/api/region/?sort=name)
- Сортирока способностей по имени (/api/ability/?sort=name) / (/api/ability/?sort=name_ru)



