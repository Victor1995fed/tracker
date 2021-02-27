.PHONY:install
install:
	composer install; php ./init;

.PHONY:run
run:
	php yii migrate; php yii seed

