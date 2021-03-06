# 3.0.1

- Stage `IStageTermJiraGroupByArray` added.
  - So you can group even by array-field now.

# 3.0.0

- `ICalculationResult` added.
  - Changed interface `JiraTermCalculator::execute()`.
  - Changed interface `IStageTermJiraAfterCalculate`.
-  `JiraTermCalculator::execute()` is getting issues instead of `args` now.
- Tests for `TotalIssues` and `JiraTermCalculator` are splited now.

# 2.1.0

- Jira term calculators can be used as plugins now.
  - Note: it will change result view to an array `[source => <source.result>, <marker> => <plugin.result>]`.
  - See `tests\terms\jira\TotalIssuesTest` for example.

# 2.0.0

- Removed `ByStatusIssuesCount`. Please, use `TotalByField` or `GroupByField` instead.
- Stage `extas.term.jira.before.calculate` added to the `JiraTermCalculator`.
- Stage `extas.term.jira.after.calculate` added to the `JiraTermCalculator`.
- All calculators rewrote for using `JiraTermCalcualtor` stages.
  - Please, note `GroupByField` stage is still alive.

# 1.1.1

- Term parameter `do_run_stage` support added to the `GroupByField` calculator.

# 1.1.0

- `JiraTermCalculator` added.
- Allowed passing issues to a calculator arguments.

# 1.0.1

- Term parameters updating fixed.
- Updated packagist badges.

# 1.0.0

- Changed `IStageTermJiraGroupedBy` interface.

# 0.3.2

- Плагины группировки к своему маркеру теперь добавляют имя, которое можно указать в параметрах плагина.

# 0.3.1

- Исправлены опечатки в именах стадий.

# 0.3.0

- Добавлен плагин для математических операций со сгруппированными задачами.

# 0.2.1

- Изменено имя стратегии `MathOperationTotal`.
- На стадию группировки полей в конструктор дополнительно передаются исходные аргументы.
- Добавлен метод `setIssuesSearchResult(...)` в `IHasIssuesSearchResult`.

# 0.2.0

- Добавлен калькулятор "Сумма по полю".
- Добавлен калькулятор "Математические операции".
- Для калькулятора "Математические операции" добавлено две стратегии:
  - Сквозное значение (`MathOperationCross`)
  - Общее значение (`MathOperationTotal`)
- Для калькулятора "Математические операции" добавлена поддержка операций:
  - Сложение
  - Вычитание
  - Умножение
  - Деление
  - Среднее значение
  - Медианное значение
  - Именованное среднее - позволяет использовать любую операцию из класса `Average` пакета `markrogoyski/math-php`.
  - Округление результата
- Добавлен калькулятор "Группировка по полю".
- Для калькулятора "Группировка по полю" реализован плагин для подсчёта тикетов.

# 0.1.0

- Добавлен калькулятор "Общее количество тикетов".
- Добавлен калькулятор количества тикетов по статусу.