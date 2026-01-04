.PHONY: dev prod
dev-%:
	@$(MAKE) -f Makefile.dev $*
prod-%:
	@$(MAKE) -f Makefile.prod $*
dev:
	@$(MAKE) -f Makefile.dev help
prod:
	@$(MAKE) -f Makefile.prod help
