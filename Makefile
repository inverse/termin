.DEFAULT_GOAL := run-docker

run-docker:
	docker build -t inversechi/termin:local .
	docker run -it -v $(CURDIR)/config.yml:/app/config.yml inversechi/termin:local

deploy-serverless:
	composer install
	npm ci
	npx serverless deploy
