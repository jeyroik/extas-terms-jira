![tests](https://github.com/jeyroik/extas-terms-jira/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/extas-terms-jira/coverage.svg?branch=master)
<a href="https://github.com/phpstan/phpstan"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a> 
<a href="https://codeclimate.com/github/jeyroik/extas-terms-jira/maintainability"><img src="https://api.codeclimate.com/v1/badges/05819204d6ae81260413/maintainability" /></a>
[![Latest Stable Version](https://poser.pugx.org/jeyroik/extas-terms-jira/v)](//packagist.org/packages/jeyroik/extas-terms-jira)
[![Total Downloads](https://poser.pugx.org/jeyroik/extas-terms-jira/downloads)](//packagist.org/packages/jeyroik/extas-terms-jira)
[![Dependents](https://poser.pugx.org/jeyroik/extas-terms-jira/dependents)](//packagist.org/packages/jeyroik/extas-terms-jira)

# Описание

Пакет предоставляет набор термов, которые вычисляются на основании массива тикетов.

# Установка

Для установки, необходимо прописать в `extas.json` желаемую конфигурацию термов и калькуляторов.

Примеры можно посмотреть в `/resources/example.json`, а также в тестах.

Далее, используйте стандартную установку `extas'a`:

`# vendor/bin/extas init`
`# vendor/bin/extas install`

Либо алиас

`# composer up`