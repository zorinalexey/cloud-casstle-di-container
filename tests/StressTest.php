<?php

declare(strict_types=1);

namespace CloudCastle\DI\Tests;

use CloudCastle\DI\Container;
use CloudCastle\DI\Exception\NotFoundException;
use stdClass;

/**
 * Stress testing script for Container.
 *
 * Tests system behavior under extreme conditions.
 */
class StressTest
{
    private array $results = [];

    private int $memoryLimit;

    private static int $count = 15000000;
     // 15M операций (= Symfony рекорд)
    private static int $maxServicesTarget = 2000000; // 2M сервисов (оптимально для 6GB RAM)

    public function __construct()
    {
        // Get memory limit
        $memoryLimit = ini_get('memory_limit');
        if ($memoryLimit === '-1') {
            $this->memoryLimit = 1024 * 1024 * 1024; // 1GB default
        } else {
            $this->memoryLimit = $this->parseMemoryLimit($memoryLimit);
        }
    }

    /**
     * Parse memory limit string to bytes.
     */
    private function parseMemoryLimit(string $limit): int
    {
        $unit = strtoupper(substr($limit, -1));
        $value = (int) $limit;

        return match ($unit) {
            'G' => $value * 1024 * 1024 * 1024,
            'M' => $value * 1024 * 1024,
            'K' => $value * 1024,
            default => $value,
        };
    }

    /**
     * Run all stress tests.
     */
    public function runAll(): void
    {
        echo "\n╔══════════════════════════════════════════════════════════════════════════╗\n";
        echo "║                      СТРЕСС-ТЕСТИРОВАНИЕ                                 ║\n";
        echo "║                   Проверка на экстремальных нагрузках                    ║\n";
        echo "╚══════════════════════════════════════════════════════════════════════════╝\n\n";

        echo sprintf("⚙️  Memory limit: %s МБ\n\n", number_format($this->memoryLimit / 1024 / 1024, 0));

        $this->testMaximumServices();
        $this->testExtremeConcurrentAccess();
        $this->testDeepDependencyChain();
        $this->testRapidRegistrationUnregistration();
        $this->testMemoryStress();
        $this->testExceptionStorm();

        $this->printSummary();
    }

    /**
     * Stress Test 1: Максимальное количество сервисов.
     */
    private function testMaximumServices(): void
    {
        echo "💥 Стресс-тест 1: Максимальное количество сервисов\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $container = new Container();
        $maxServices = 0;
        $startMemory = memory_get_usage();
        $memoryThreshold = $this->memoryLimit * 0.8; // 80% of limit
        $startTime = microtime(true);
        $failed = false;
        $failureReason = '';

        try {
            for ($i = 0; $i < self::$maxServicesTarget; $i++) { // Try up to 5M (2× Symfony 2.5M)
                if (memory_get_usage() > $memoryThreshold) {
                    break;
                }

                $container->set('stress_service_' . $i, fn(): stdClass => new stdClass());
                $maxServices = $i + 1;

                // Check every 10k
                if ($i > 0 && $i % 10000 === 0) {
                    $currentMemory = memory_get_usage() / 1024 / 1024;
                    echo sprintf(
                        "  ✓ %s сервисов зарегистрировано, память: %.2f МБ\n",
                        number_format($i),
                        $currentMemory
                    );
                }
            }
        } catch (\Throwable $throwable) {
            $failed = true;
            $failureReason = $throwable->getMessage();
        }

        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        $duration = ($endTime - $startTime) * 1000;
        $memoryUsed = ($endMemory - $startMemory) / 1024 / 1024;

        echo sprintf("\n  Максимум сервисов: %s\n", number_format($maxServices));
        echo sprintf("  Время: %.2f мс\n", $duration);
        echo sprintf("  Память: %.2f МБ\n", $memoryUsed);
        echo sprintf("  Память на сервис: %.3f КБ\n", ($memoryUsed * 1024) / $maxServices);
        echo sprintf("  Статус: %s\n\n", $failed ? "⚠️  Достигнут лимит" : "✅ Успешно");

        if ($failed) {
            echo "  Причина остановки: {$failureReason}\n\n";
        }

        $this->results['max_services'] = [
            'passed' => !$failed,
            'max_services' => $maxServices,
            'memory_mb' => $memoryUsed,
        ];
    }

    /**
     * Stress Test 2: Экстремальный concurrent доступ.
     */
    private function testExtremeConcurrentAccess(): void
    {
        echo "💥 Стресс-тест 2: Экстремальный concurrent доступ\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $container = new Container();
        $serviceCount = 100;
        $iterations = self::$count;

        // Register services
        for ($i = 0; $i < $serviceCount; $i++) {
            $container->set('extreme_' . $i, fn(): stdClass => new stdClass());
        }

        $startTime = microtime(true);
        $errors = 0;

        for ($i = 0; $i < $iterations; $i++) {
            try {
                $serviceId = 'extreme_' . ($i % $serviceCount);
                $container->get($serviceId);
            } catch (\Throwable) {
                $errors++;
            }

            if ($i > 0 && $i % 100000 === 0) {
                $elapsed = (microtime(true) - $startTime) * 1000;
                $opsPerSec = $i / (microtime(true) - $startTime);
                echo sprintf(
                    "  ✓ %s операций выполнено (%.2f мс, %s оп/сек)\n",
                    number_format($i),
                    $elapsed,
                    number_format($opsPerSec, 0)
                );
            }
        }

        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;
        $opsPerSecond = $iterations / ($endTime - $startTime);

        echo sprintf("\n  Операций: %s\n", number_format($iterations));
        echo sprintf("  Время: %.2f мс\n", $duration);
        echo sprintf("  Скорость: %s оп/сек\n", number_format($opsPerSecond, 0));
        echo sprintf("  Ошибок: %s\n", $errors);
        echo sprintf("  Статус: %s\n\n", $errors === 0 ? "✅ Все операции успешны" : "⚠️  Есть ошибки");

        $this->results['extreme_access'] = [
            'passed' => $errors === 0,
            'ops_per_sec' => $opsPerSecond,
            'errors' => $errors,
        ];
    }

    /**
     * Stress Test 3: Глубокая цепочка зависимостей.
     */
    private function testDeepDependencyChain(): void
    {
        echo "💥 Стресс-тест 3: Глубокая цепочка зависимостей\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $container = new Container();
        $chainDepth = self::$count / 1000; // 1000 уровней вложенности для 10 миллионов операций

        // Create deep dependency chain
        $container->set('level_0', fn(): stdClass => new stdClass());

        for ($i = 1; $i < $chainDepth; $i++) {
            $prevLevel = $i - 1;
            $container->set('level_' . $i, function (Container $c) use ($prevLevel): stdClass {
                $c->get('level_' . $prevLevel); // Get previous level
                return new stdClass();
            });
        }

        $startTime = microtime(true);
        $failed = false;
        $failureLevel = 0;

        try {
            // Access deepest level (triggers full chain resolution)
            $container->get("level_" . ($chainDepth - 1));
        } catch (\Throwable) {
            $failed = true;
            $failureLevel = $chainDepth;
        }

        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        echo sprintf("  Глубина цепочки: %s уровней\n", $chainDepth);
        echo sprintf("  Время резолюции: %.2f мс\n", $duration);
        echo sprintf("  Среднее на уровень: %.3f мс\n", $duration / $chainDepth);
        echo sprintf("  Статус: %s\n\n", $failed ? '⚠️  Достигнут лимит на уровне ' . $failureLevel : "✅ Успешно");

        $this->results['deep_chain'] = [
            'passed' => !$failed,
            'depth' => $failed ? $failureLevel : $chainDepth,
            'time_ms' => $duration,
        ];
    }

    /**
     * Stress Test 4: Быстрая регистрация/отмена.
     */
    private function testRapidRegistrationUnregistration(): void
    {
        echo "💥 Стресс-тест 4: Быстрая регистрация/удаление\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $container = new Container();
        $cycles = self::$count;
        $startMemory = memory_get_usage();
        $startTime = microtime(true);

        for ($i = 0; $i < $cycles; $i++) {
            $id = 'rapid_' . ($i % 1000); // Rotate through 1000 IDs
            $container->set($id, fn(): stdClass => new stdClass());
            $container->get($id);
            $container->remove($id);

            if ($i > 0 && $i % 20000 === 0) {
                $currentMemory = memory_get_usage() / 1024 / 1024;
                $memoryGrowth = ($currentMemory - ($startMemory / 1024 / 1024));
                echo sprintf(
                    "  ✓ %s циклов, память: %.2f МБ (рост: %.3f МБ)\n",
                    number_format($i),
                    $currentMemory,
                    $memoryGrowth
                );
            }
        }

        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        $duration = ($endTime - $startTime) * 1000;
        $memoryGrowth = ($endMemory - $startMemory) / 1024 / 1024;
        $cyclesPerSec = $cycles / ($endTime - $startTime);

        echo sprintf("\n  Циклов: %s\n", number_format($cycles));
        echo sprintf("  Время: %.2f мс\n", $duration);
        echo sprintf("  Скорость: %s циклов/сек\n", number_format($cyclesPerSec, 0));
        echo sprintf("  Рост памяти: %.3f МБ\n", $memoryGrowth);
        echo sprintf("  Статус: %s\n\n", abs($memoryGrowth) < 1 ? "✅ Память стабильна" : "⚠️  Возможна утечка");

        $this->results['rapid_cycles'] = [
            'passed' => abs($memoryGrowth) < 1,
            'cycles_per_sec' => $cyclesPerSec,
            'memory_growth_mb' => $memoryGrowth,
        ];
    }

    /**
     * Stress Test 5: Стресс-тест памяти.
     */
    private function testMemoryStress(): void
    {
        echo "💥 Стресс-тест 5: Стресс-тест памяти\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $container = new Container();
        $startMemory = memory_get_usage();
        $targetMemory = min($this->memoryLimit * 0.7, 100 * 1024 * 1024); // Max 100MB or 70% of limit
        $serviceCount = 0;

        echo sprintf("  Цель: Использовать ~%.0f МБ памяти\n\n", $targetMemory / 1024 / 1024);

        while (memory_get_usage() < $targetMemory) {
            $container->set('memory_stress_' . $serviceCount, fn(): stdClass => new stdClass());
            $container->get('memory_stress_' . $serviceCount); // Instantiate
            $serviceCount++;

            if ($serviceCount % 10000 === 0) {
                $currentMemory = memory_get_usage() / 1024 / 1024;
                echo sprintf(
                    "  ✓ %s сервисов, память: %.2f МБ\n",
                    number_format($serviceCount),
                    $currentMemory
                );
            }
        }

        $endMemory = memory_get_usage();
        $memoryUsed = ($endMemory - $startMemory) / 1024 / 1024;
        $memoryPerService = ($memoryUsed * 1024) / $serviceCount;

        echo sprintf("\n  Сервисов создано: %s\n", number_format($serviceCount));
        echo sprintf("  Память использовано: %.2f МБ\n", $memoryUsed);
        echo sprintf("  Память на сервис: %.3f КБ\n", $memoryPerService);

        // Test if services are accessible
        $accessErrors = 0;
        $sampleSize = min(1000, $serviceCount);

        for ($i = 0; $i < $sampleSize; $i++) {
            $randomId = 'memory_stress_' . random_int(0, $serviceCount - 1);
            try {
                $container->get($randomId);
            } catch (\Throwable) {
                $accessErrors++;
            }
        }

        echo sprintf(
            "  Проверка доступа (%s случайных): %s ошибок\n",
            number_format($sampleSize),
            $accessErrors
        );
        echo sprintf("  Статус: %s\n\n", $accessErrors === 0 ? "✅ Все сервисы доступны" : "⚠️  Есть проблемы");

        $this->results['memory_stress'] = [
            'passed' => $accessErrors === 0,
            'service_count' => $serviceCount,
            'memory_mb' => $memoryUsed,
            'errors' => $accessErrors,
        ];
    }

    /**
     * Stress Test 6: Шторм исключений.
     */
    private function testExceptionStorm(): void
    {
        echo "💥 Стресс-тест 6: Шторм исключений\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $container = new Container();
        $attempts = self::$count  / 100;
        $caughtExceptions = 0;
        $unexpectedErrors = 0;
        $startTime = microtime(true);

        for ($i = 0; $i < $attempts; $i++) {
            try {
                // Try to get non-existent service
                $container->get('non_existent_service_' . $i);
                $unexpectedErrors++; // Should not reach here
            } catch (NotFoundException) {
                $caughtExceptions++;
            } catch (\Throwable) {
                $unexpectedErrors++;
            }

            if ($i > 0 && $i % 20000 === 0) {
                echo sprintf("  ✓ %s исключений обработано\n", number_format($i));
            }
        }

        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;
        $exceptionsPerSec = $caughtExceptions / ($endTime - $startTime);

        echo sprintf("\n  Попыток: %s\n", number_format($attempts));
        echo sprintf("  Поймано NotFoundException: %s\n", number_format($caughtExceptions));
        echo sprintf("  Неожиданных ошибок: %s\n", $unexpectedErrors);
        echo sprintf("  Время: %.2f мс\n", $duration);
        echo sprintf("  Скорость: %s исключений/сек\n", number_format($exceptionsPerSec, 0));
        echo sprintf(
            "  Статус: %s\n\n",
            $unexpectedErrors === 0 && $caughtExceptions === $attempts ? "✅ Корректная обработка" : "⚠️  Есть проблемы"
        );

        $this->results['exception_storm'] = [
            'passed' => $unexpectedErrors === 0 && $caughtExceptions === $attempts,
            'exceptions_per_sec' => $exceptionsPerSec,
            'unexpected_errors' => $unexpectedErrors,
        ];
    }

    /**
     * Print summary of all stress tests.
     */
    private function printSummary(): void
    {
        echo "╔══════════════════════════════════════════════════════════════════════════╗\n";
        echo "║                         ИТОГОВЫЕ РЕЗУЛЬТАТЫ                              ║\n";
        echo "╚══════════════════════════════════════════════════════════════════════════╝\n\n";

        $allPassed = true;
        $totalTests = count($this->results);
        $passedTests = 0;

        foreach ($this->results as $test => $result) {
            $testName = str_replace('_', ' ', ucwords($test, '_'));
            if ($result['passed']) {
                $passedTests++;
                echo "✅ " . $testName . ": PASSED\n";
            } else {
                $allPassed = false;
                echo "⚠️  " . $testName . ": WARNING\n";
            }
        }

        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo sprintf("Пройдено: %d/%d тестов\n", $passedTests, $totalTests);

        if ($allPassed) {
            echo "Статус: ✅ ВСЕ СТРЕСС-ТЕСТЫ ПРОЙДЕНЫ\n";
        } else {
            echo "Статус: ⚠️  ЕСТЬ ПРЕДУПРЕЖДЕНИЯ (система работает, но достигнуты лимиты)\n";
        }

        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        // Print key metrics
        echo "📊 КЛЮЧЕВЫЕ МЕТРИКИ:\n\n";

        if (isset($this->results['max_services'])) {
            echo sprintf(
                "  • Максимум сервисов: %s\n",
                number_format($this->results['max_services']['max_services'])
            );
        }

        if (isset($this->results['extreme_access'])) {
            echo sprintf(
                "  • Экстремальный доступ: %s оп/сек\n",
                number_format($this->results['extreme_access']['ops_per_sec'], 0)
            );
        }

        if (isset($this->results['rapid_cycles'])) {
            echo sprintf(
                "  • Быстрые циклы: %s/сек (рост памяти: %.3f МБ)\n",
                number_format($this->results['rapid_cycles']['cycles_per_sec'], 0),
                $this->results['rapid_cycles']['memory_growth_mb']
            );
        }

        if (isset($this->results['exception_storm'])) {
            echo sprintf(
                "  • Обработка исключений: %s/сек\n",
                number_format($this->results['exception_storm']['exceptions_per_sec'], 0)
            );
        }

        echo "\n";
    }
}

// Run if executed directly
if (PHP_SAPI === 'cli' && basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    require_once __DIR__ . '/../vendor/autoload.php';

    echo "\n⚠️  ВНИМАНИЕ: Стресс-тесты могут занять несколько минут и использовать много памяти!\n";

    $stressTest = new StressTest();
    $stressTest->runAll();
}
