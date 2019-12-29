<h1>Команды</h1>
<h3>Elasticsearch</h3>
1)Пересоздать индексы 

```
yii elastic/create-index
```

<h5>Запросы к api elasticsearch</h5>
1)Получить документ data индекса task
```
http://127.0.0.1:9200/task/data/_search
```
2)Получить список всех индексов
```
http://127.0.0.1:9200/_aliases
```