cs:
	./vendor/bin/php-cs-fixer fix --dry-run --stop-on-violation --diff

cs-fix:
	./vendor/bin/php-cs-fixer fix