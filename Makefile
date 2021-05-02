# Development scripting helper to wrap up Docker interactions with the development environment

.PHONY: help
help:
	@echo "Available commands:"
	@echo
	@echo "Docker control"
	@echo
	@echo "	run"
	@echo "	stop"
	@echo "	rebuild"
	@echo
	@echo "	clean - Remove caches and other temporary runtime files"

.PHONY: run
run:
	@echo "Starting development server..."
	@cd ./dev && docker-compose up -d
	@echo
	@echo "Available at http://localhost:8083"

stop:
	@echo "Stopping development server..."
	@cd ./dev && docker-compose stop

.PHONY: rebuild
rebuild:
	@echo "Rebuilding Docker containers..."
	@cd ./dev && docker-compose build --no-cache

.PHONY: clean
clean:
	@echo "Removing temporary files..."
	@rm -rf ./dev/logs/*
