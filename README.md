## Task tracker (api)
### Описание:
API для таск-трекера на YII2

Установить фронт для api можно по [ссылке](https://github.com/Victor1995fed/tracker-front.git)
### Технологии:
     - Mysql:5.7
     - ElasticSearch:7.11.1
     - PHP:7.2
     - Yii PHP Framework 2
### Системные требования:
- `docker >= 18.0` _(установка: `curl -fsSL get.docker.com | sudo sh`)_
-  `docker-compose >= 3` _([installing manual](https://docs.docker.com/compose/install/#install-compose))_


### Запуск:
- Введите следующие команды в консоль:

``` 
     $ git clone https://github.com/Victor1995fed/tracker.git
     $ cd tracker
     $ docker-compose up -d
     $ docker-compose  exec api  make install 
```
- Затем укажите данные для БД в конфигах, пример: 
```
'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=mysql;dbname=yii2advanced',
                'username' => 'yii2advanced',
                'password' => 'secret',
                'charset' => 'utf8',
            ], 
```
- Выполните миграции и заполните начальными данными:
```
docker-compose exec api make run 
```
- API будет доступно по [localhost:20080](http://localhost:20080)

- Теперь можно установить [фронтенд](https://github.com/Victor1995fed/tracker-front.git)

#### Дополнительные команды:

##### Elasticsearch
- Пересоздать индексы(внутри докер-контейнера): 

```
php yii elastic/create-index
```
