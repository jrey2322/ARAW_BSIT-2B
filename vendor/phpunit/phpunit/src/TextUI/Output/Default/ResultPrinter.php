age:"Дошло је до грешке. Покушајте поново за који тренутак.",OutOfScopeRequestErrorMessage:"Не могу да вам помогнем око тога. Покушајте да промените тему или ме замолите да вам помогнем око нечег другог.",MultipleQuestionsErrorMessage:"Жао ми је, не обављам више задатака одједном баш добро. Пошаљите један по један захтев.",LocationParameterErrorMessage:"Још увек не могу то да урадим. Покушајте уместо тога да ми поставите питање о целом документу.",AnswerNotInDocErrorMessage:"Не могу да пронађем одговор у овом документу. Покушајте да преформулишете захтев тако да буде више усмерен на садржај.",QuestionOnDocMetadataErrorMessage:"Још увек не могу то да урадим. Најбоље је да ограничите захтеве тако да буду у вези са садржајем овог документа. Пробајте да ме питате да урадим нешто попут „идентификуј главне теме документа“.",AssistantResponseMessage_1:"Капирам. Откуцајте „шта могу рећи“ за више идеја.",AssistantResponseMessage_2:"Урадио! Око чега још могу да вам помогнем?",AssistantResponseMessage_3:"Готово. Још нешто?",AssistantResponseMessage_4:"Све је спремно. Шта следеће треба да урадим?",AssistantResponseMessage_5:"Готово, шта је следеће?",AssistantResponseMessage_6:"Готово",AssistantResponseMessage_7:"Готово.",AssistantResponseMessage_8:"Све је спремно.",AssistantResponseMessageForPartialSuccess_1:"Дао сам све од себе, али нисам могао да урадим све што сте тражили. Можете ли да покушате да преформулишете или да ме питате нешто друго?",AssistantResponseMessageForPartialSuccess_2:"Уложио сам много труда, али неке ствари и даље нису ишле по плану. Можете ли ми поставити питање на другачији начин?",AssistantResponseMessageForPartialSuccess_3:"Потпуно сам се посветио задатку, али нисам могао да прикупим све резултате које сте желели. Можете ли ми поставити неко друго питање?",AssistantResponseMessageForPartialSuccess_4:"Дао сам најбоље од себе, али није све ишло у складу са планом. Покушајте поново тако што ћете ме питати нешто друго.",WordLearnMoreLinkMessage:"Сазнајте више",OptOutMessageTitle:"Хвала што сте ми пружили шансу!",OptOutMessageSubtitle:"Знам да није до вас, већ до мене. Али ако желите да се вратим, кликните на стрелицу надоле за опције приказа траке и одаберите Бета верзију",AugloopErrorMessage:"Упс! Још увек не могу да разумем ово. Да ли постоји још неки начин да вам помогнем?",JunkQueryErrorMessage:"Здраво, ја сам робот за ћаскање на бази вештачке интелигенције обучен да реагује на основу датог одзива или контекста. Ако имате одређену радњу коју желите да предузмем, само је откуцајте у наставку и даћу све од себе!",ActionInRibbonErrorMessage:"ilsOnTestsThatTriggerErrors;
        $this->displayDetailsOnTestsThatTriggerNotices      = $displayDetailsOnTestsThatTriggerNotices;
        $this->displayDetailsOnTestsThatTriggerWarnings     = $displayDetailsOnTestsThatTriggerWarnings;
        $this->displayDefectsInReverseOrder                 = $displayDefectsInReverseOrder;
    }

    public function print(TestResult $result): void
    {
        if ($this->displayPhpunitErrors) {
            $this->printPhpunitErrors($result);
        }

        if ($this->displayPhpunitWarnings) {
            $this->printTestRunnerWarnings($result);
        }

        if ($this->displayPhpunitDeprecations) {
            $this->printTestRunnerDeprecations($result);
        }

        if ($this->displayTestsWithErrors) {
            $this->printTestsWithErrors($result);
        }

        if ($this->displayTestsWithFailedAssertions) {
            $this->printTestsWithFailedAssertions($result);
        }

        if ($this->displayPhpunitWarnings) {
            $this->printDetailsOnTestsThatTriggeredPhpunitWarnings($result);
        }

        if ($this->displayPhpunitDeprecations) {
            $this->printDetailsOnTestsThatTriggeredPhpunitDeprecations($result);
        }

        if ($this->displayRiskyTests) {
            $this->printRiskyTests($result);
        }

        if ($this->displayDetailsOnIncompleteTests) {
            $this->printIncompleteTests($result);
        }

        if ($this->displayDetailsOnSkippedTests) {
            $this->printSkippedTestSuites($result);
            $this->printSkippedTests($result);
        }

        if ($this->displayDetailsOnTestsThatTriggerErrors) {
            $this->printIssueList('error', $result->errors());
        }

        if ($this->displayDetailsOnTestsThatTriggerWarnings) {
            $this->printIssueList('PHP warning', $result->phpWarnings());
            $this->printIssueList('warning', $result->warnings());
        }

        if ($this->displayDetailsOnTestsThatTriggerNotices) {
            $this->printIssueList('PHP notice', $result->phpNotices());
            $this->printIssueList('notice', $result->notices());
        }

        if ($this->displayDetailsOnTestsThatTriggerDeprecations) {
            $this->printIssueList('PHP deprecation', $result->phpDeprecations());
            $this->printIssueList('deprecation', $result->deprecations());
        }
    }

    private function printPhpunitErrors(TestResult $result): void
    {
        if (!$result->hasTestTriggeredPhpunitErrorEvents()) {
            return;
        }

        $elements = $this->mapTestsWithIssuesEventsToElements($result->testTriggeredPhpunitErrorEvents());

        $this->printListHeaderWithNumber($elements['numberOfTestsWithIssues'], 'PHPUnit error');
        $this->printList($elements['elements']);
    }

    private function printDetailsOnTestsThatTriggeredPhpunitDeprecations(TestResult $result): void
    {
        if (!$result->hasTestTriggeredPhpunitDeprecationEvents()) {
            return;
        }

        $elements = $this->mapTestsWithIssuesEventsToElements($result->testTriggeredPhpunitDeprecationEvents());

        $this->printListHeaderWithNumberOfTestsAndNumberOfIssues(
            $elements['numberOfTestsWithIssues'],
            $elements['numberOfIssues'],
            'PHPUnit deprecation',
        );

        $this->printList($elements['elements']);
    }

    private function printTestRunnerWarnings(TestResult $result): void
    {
        if (!$result->hasTestRunnerTriggeredWarningEvents()) {
            return;
        }

        $elements = [];

        foreach ($result->testRunnerTriggeredWarningEvents() as $event) {
            $elements[] = [
                'title' => $event->message(),
                'body'  => '',
            ];
        }

        $this->printListHeaderWithNumber(count($elements), 'PHPUnit test runner warning');
        $this->printList($elements);
    }

    private function printTestRunnerDeprecations(TestResult $result): void
    {
        if (!$result->hasTestRunnerTriggeredDeprecationEvents()) {
            return;
        }

        $elements = [];

        foreach ($result->testRunnerTriggeredDeprecationEvents() as $event) {
            $elements[] = [
                'title' => $event->message(),
                'body'  => '',
            ];
        }

        $this->printListHeaderWithNumber(count($elements), 'PHPUnit test runner deprecation');
        $this->printList($elements);
    }

    private function printDetailsOnTestsThatTriggeredPhpunitWarnings(TestResult $result): void
    {
        if (!$result->hasTestTriggeredPhpunitWarningEvents()) {
            return;
        }

        $elements = $this->mapTestsWithIssuesEventsToElements($result->testTriggeredPhpunitWarningEvents());

        $this->printListHeaderWithNumberOfTestsAndNumberOfIssues(
            $elements['numberOfTestsWithIssues'],
            $elements['numberOfIssues'],
            'PHPUnit warning',
        );

        $this->printList($elements['elements']);
    }

    private function printTestsWithErrors(TestResult $result): void
    {
        if (!$result->hasTestErroredEvents()) {
            return;
        }

        $elements = [];

        foreach ($result->testErroredEvents() as $event) {
            if ($event instanceof AfterLastTestMethodErrored || $event instanceof BeforeFirstTestMethodErrored) {
                $title = $event->testClassName();
            } else {
                $title = $this->name($event->test());
            }

            $elements[] = [
                'title' => $title,
                'body'  => $event->throwable()->asString(),
            ];
        }

        $this->printListHeaderWithNumber(count($elements), 'error');
        $this->printList($elements);
    }

    private function printTestsWithFailedAssertions(TestResult $result): void
    {
        if (!$result->hasTestFailedEvents()) {
            return;
        }

        $elements = [];

        foreach ($result->testFailedEvents() as $event) {
            $body = $event->throwable()->asString();

            if (str_starts_with($body, 'AssertionError: ')) {
                $body = substr($body, strlen('AssertionError: '));
            }

            $elements[] = [
                'title' => $this->name($event->test()),
                'body'  => $body,
            ];
        }

        $this->printListHeaderWithNumber(count($elements), 'failure');
        $this->printList($elements);
    }

    private function printRiskyTests(TestResult $result): void
    {
        if (!$result->hasTestConsideredRiskyEvents()) {
            return;
        }

        $elements = $this->mapTestsWithIssuesEventsToElements($result->testConsideredRiskyEvents());

        $this->printListHeaderWithNumber($elements['numberOfTestsWithIssues'], 'risky test');
        $this->printList($elements['elements']);
    }

    private function printIncompleteTests(TestResult $result): void
    {
        if (!$result->hasTestMarkedIncompleteEvents()) {
            return;
        }

        $elements = [];

        foreach ($result->testMarkedIncompleteEvents() as $event) {
            $elements[] = [
                'title' => $this->name($event->test()),
                'body'  => $event->throwable()->asString(),
            ];
        }

        $this->printListHeaderWithNumber(count($elements), 'incomplete test');
        $this->printList($elements);
    }

    private function printSkippedTestSuites(TestResult $result): void
    {
        if (!$result->hasTestSuiteSkippedEvents()) {
            return;
        }

        $elements = [];

        foreach ($result->testSuiteSkippedEvents() as $event) {
            $elements[] = [
                'title' => $event->testSuite()->name(),
                'body'  => $event->message(),
            ];
        }

        $this->printListHeaderWithNumber(count($elements), 'skipped test suite');
        $this->printList($elements);
    }

    private function printSkippedTests(TestResult $result): void
    {
        if (!$result->hasTestSkippedEvents()) {
            return;
        }

        $elements = [];

        foreach ($result->testSkippedEvents() as $event) {
            $elements[] = [
                'title' => $this->name($event->test()),
                'body'  => $event->message(),
            ];
        }

        $this->printListHeaderWithNumber(count($elements), 'skipped test');
        $this->printList($elements);
    }

    /**
     * @psalm-param non-empty-string $type
     * @psalm-param list<Issue> $issues
     */
    private function printIssueList(string $type, array $issues): void
    {
        if (empty($issues)) {
            return;
        }

        $numberOfUniqueIssues = count($issues);
        $triggeringTests      = [];

        foreach ($issues as $issue) {
            $triggeringTests = array_merge($triggeringTests, array_keys($issue->triggeringTests()));
        }

        $numberOfTests = count(array_unique($triggeringTests));
        unset($triggeringTests);

        $this->printListHeader(
            sprintf(
                '%d test%s triggered %d %s%s:' . PHP_EOL . PHP_EOL,
                $numberOfTests,
                $numberOfTests !== 1 ? 's' : '',
                $numberOfUniqueIssues,
                $type,
                $numberOfUniqueIssues !== 1 ? 's' : '',
            ),
        );

        $i = 1;

        foreach ($issues as $issue) {
            $title = sprintf(
                '%s:%d',
                $issue->file(),
                $issue->line(),
            );

            $body = trim($issue->description()) . PHP_EOL . PHP_EOL . 'Triggered by:';

            $triggeringTests = $issue->triggeringTests();

            ksort($triggeringTests);

            foreach ($triggeringTests as $triggeringTest) {
                $body .= PHP_EOL . PHP_EOL . '* ' . $triggeringTest['test']->id();

                if ($triggeringTest['count'] > 1) {
                    $body .= sprintf(
                        ' (%d times)',
                        $triggeringTest['count'],
                    );
                }

                if ($triggeringTest['test']->isTestMethod()) {
                    $body .= PHP_EOL . '  ' . $triggeringTest['test']->file() . ':' . $triggeringTest['test']->line();
                }
            }

            $this->printIssueListElement($i++, $title, $body);

            $this->printer->print(PHP_EOL);
        }
    }

    private function printListHeaderWithNumberOfTestsAndNumberOfIssues(int $numberOfTestsWithIssues, int $numberOfIssues, string $type): void
    {
        $this->printListHeader(
            sprintf(
                "%d test%s triggered %d %s%s:\n\n",
                $numberOfTestsWithIssues,
                $numberOfTestsWithIssues !== 1 ? 's' : '',
                $numberOfIssues,
                $type,
                $numberOfIssues !== 1 ? 's' : '',
            ),
        );
    }

    private function printListHeaderWithNumber(int $number, string $type): void
    {
        $this->printListHeader(
            sprintf(
                "There %s %d %s%s:\n\n",
                ($number === 1) ? 'was' : 'were',
                $number,
                $type,
                ($number === 1) ? '' : 's',
            ),
        );
    }

    private function printListHeader(string $header): void
    {
        if ($this->listPrinted) {
            $this->printer->print("--\n\n");
        }

        $this->listPrinted = true;

        $this->printer->print($header);
    }

    /**
     * @psalm-param list<array{title: string, body: string}> $elements
     */
    private function printList(array $elements): void
    {
        $i = 1;

        if ($this->displayDefectsInReverseOrder) {
            $elements = array_reverse($elements);
        }

        foreach ($elements as $element) {
            $this->printListElement($i++, $element['title'], $element['body']);
        }

        $this->printer->print("\n");
    }

    private function printListElement(int $number, string $title, string $body): void
    {
        $body = trim($body);

        $this->printer->print(
            sprintf(
                "%s%d) %s\n%s%s",
                $number > 1 ? "\n" : '',
                $number,
                $title,
                $body,
                !empty($body) ? "\n" : '',
            ),
        );
    }

    private function printIssueListElement(int $number, string $title, string $body): void
    {
        $body = trim($body);

        $this->printer->print(
            sprintf(
                "%d) %s\n%s%s",
                $number,
                $title,
                $body,
                !empty($body) ? "\n" : '',
            ),
        );
    }

    private function name(Test $test): string
    {
        if ($test->isTestMethod()) {
            assert($test instanceof TestMethod);

            if (!$test->testData()->hasDataFromDataProvider()) {
                return $test->nameWithClass();
            }

            return $test->className() . '::' . $test->methodName() . $test->testData()->dataFromDataProvider()->dataAsStringForResultOutput();
        }

        return $test->name();
    }

    /**
     * @psalm-param array<string,list<ConsideredRisky|DeprecationTriggered|PhpDeprecationTriggered|PhpunitDeprecationTriggered|ErrorTriggered|NoticeTriggered|PhpNoticeTriggered|WarningTriggered|PhpWarningTriggered|PhpunitErrorTriggered|PhpunitWarningTriggered>> $events
     *
     * @psalm-return array{numberOfTestsWithIssues: int, numberOfIssues: int, elements: list<array{title: string, body: string}>}
     */
    private function mapTestsWithIssuesEventsToElements(array $events): array
    {
        $elements = [];
        $issues   = 0;

        foreach ($events as $reasons) {
            $test         = $reasons[0]->test();
            $testLocation = $this->testLocation($test);
            $title        = $this->name($test);
            $body         = '';
            $first        = true;
            $single       = count($reasons) === 1;

            foreach ($reasons as $reason) {
                if ($first) {
                    $first = false;
                } else {
                    $body .= PHP_EOL;
                }

                $body .= $this->reasonMessage($reason, $single);
                $body .= $this->reasonLocation($reason, $single);

                $issues++;
            }

            if (!empty($testLocation)) {
                $body .= $testLocation;
            }

            $elements[] = [
                'title' => $title,
                'body'  => $body,
            ];
        }

        return [
            'numberOfTestsWithIssues' => count($events),
            'numberOfIssues'          => $issues,
            'elements'                => $elements,
        ];
    }

    private function testLocation(Test $test): string
    {
        if (!$test->isTestMethod()) {
            return '';
        }

        assert($test instanceof TestMethod);

        return sprintf(
            '%s%s:%d%s',
            PHP_EOL,
            $test->file(),
            $test->line(),
            PHP_EOL,
        );
    }

    private function reasonMessage(ConsideredRisky|DeprecationTriggered|ErrorTriggered|NoticeTriggered|PhpDeprecationTriggered|PhpNoticeTriggered|PhpunitDeprecationTriggered|PhpunitErrorTriggered|PhpunitWarningTriggered|PhpWarningTriggered|WarningTriggered $reason, bool $single): string
    {
        $message = trim($reason->message());

        if ($single) {
            return $message . PHP_EOL;
        }

        $lines  = explode(PHP_EOL, $message);
        $buffer = '* ' . $lines[0] . PHP_EOL;

        if (count($lines) > 1) {
            foreach (range(1, count($lines) - 1) as $line) {
                $buffer .= '  ' . $lines[$line] . PHP_EOL;
            }
        }

        return $buffer;
    }

    private function reasonLocation(ConsideredRisky|DeprecationTriggered|ErrorTriggered|NoticeTriggered|PhpDeprecationTriggered|PhpNoticeTriggered|PhpunitDeprecationTriggered|PhpunitErrorTriggered|PhpunitWarningTriggered|PhpWarningTriggered|WarningTriggered $reason, bool $single): string
    {
        if (!$reason instanceof DeprecationTriggered &&
            !$reason instanceof PhpDeprecationTriggered &&
            !$reason instanceof ErrorTriggered &&
            !$reason instanceof NoticeTriggered &&
            !$reason instanceof PhpNoticeTriggered &&
            !$reason instanceof WarningTriggered &&
            !$reason instanceof PhpWarningTriggered) {
            return '';
        }

        return sprintf(
            '%s%s:%d%s',
            $single ? '' : '  ',
            $reason->file(),
            $reason->line(),
            PHP_EOL,
        );
    }
}
