<?php

declare(strict_types=1);

namespace CloudCastle\DI\Tests;

use CloudCastle\DI\Container;
use CloudCastle\DI\ContainerFactory;
use stdClass;

/**
 * Stress testing for Compiled Container.
 *
 * Tests system behavior under extreme conditions.
 */
class CompiledContainerStressTest
{
    private array $results = [];

    private static int $maxServices = 50000; // 50K services for stress tests

    private static int $extremeAccess = 5000000; // 5M accesses

    /**
     * Run all stress tests.
     */
    public function runAll(): void
    {
        echo "\n╔══════════════════════════════════════════════════════════════════════════╗\n";
        echo "║              COMPILED CONTAINER - СТРЕСС-ТЕСТИРОВАНИЕ                    ║\n";
        echo "║                 Проверка на экстремальных нагрузках                      ║\n";
        echo "╚══════════════════════════════════════════════════════════════════════════╝\n\n";

        $this->testMaximumServicesCompilation();
        $this->testExtremeAccessSpeed();
        $this->testCompilationWithComplexDependencies();
        $this->testMultipleCompilationCycles();
        $this->testCodeSizeGrowth();

        $this->printSummary();
    }

    /**
     * Stress Test 1: Maximum number of services compilation.
     */
    private function testMaximumServicesCompilation(): void
    {
        echo "💥 Стресс-тест 1: Максимальное количество сервисов\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $container = new Container();
        $maxServices = 0;

        echo "  Регистрация и компиляция сервисов...\n";

        try {
            $startTime = microtime(true);
            $startMemory = memory_get_usage(true);

            for ($i = 0; $i < self::$maxServices; $i++) {
                $container->set("stress_service_{$i}", fn(): stdClass => new stdClass());
                $maxServices = $i + 1;

                // Compile every 10k to check progress
                if ($i > 0 && $i % 10000 === 0) {
                    $code = $container->compile('StressContainer', 'Test\\Stress');
                    $codeSize = strlen($code);
                    $currentMemory = memory_get_usage(true);

                    echo sprintf(
                        "  ✓ %s сервисов: %.2f МБ памяти, %.2f МБ код\n",
                        number_format($i),
                        $currentMemory / 1024 / 1024,
                        $codeSize / 1024 / 1024
                    );

                    unset($code);
                }
            }

            // Final compilation
            $code = $container->compile('StressContainer', 'Test\\Stress');
            $duration = (microtime(true) - $startTime) * 1000;
            $memory = memory_get_usage(true) - $startMemory;
            $codeSize = strlen($code);

            echo sprintf("\n  Максимум сервисов: %s\n", number_format($maxServices));
            echo sprintf("  Время: %.2f мс\n", $duration);
            echo sprintf("  Память: %.2f МБ\n", $memory / 1024 / 1024);
            echo sprintf("  Размер кода: %.2f МБ\n", $codeSize / 1024 / 1024);
            echo sprintf("  Скорость компиляции: %s серв/сек\n", number_format($maxServices / ($duration / 1000), 0));
            echo "  Статус: ✅ Успешно\n\n";

            $this->results['max_services'] = [
                'passed' => true,
                'max_services' => $maxServices,
                'compile_time' => $duration,
                'memory' => $memory,
                'code_size' => $codeSize,
            ];
        } catch (\Throwable $e) {
            echo "  ⚠️ Достигнут лимит: " . $e->getMessage() . "\n\n";

            $this->results['max_services'] = [
                'passed' => false,
                'max_services' => $maxServices,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Stress Test 2: Extreme access speed with compiled container.
     */
    private function testExtremeAccessSpeed(): void
    {
        echo "💥 Стресс-тест 2: Экстремальная скорость доступа\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $cacheDir = sys_get_temp_dir() . '/di_extreme_' . uniqid();
        mkdir($cacheDir, 0755, true);

        try {
            $serviceCount = 100;
            $iterations = self::$extremeAccess;

            // Create container
            $container = new Container();
            for ($i = 0; $i < $serviceCount; $i++) {
                $container->set("extreme_{$i}", fn(): stdClass => new stdClass());
            }

            // Compile
            $compiledFile = $cacheDir . '/ExtremeContainer.php';
            $container->compileToFile($compiledFile, 'ExtremeContainer', 'Test\\Extreme');

            // Load compiled container
            $services = [];
            for ($i = 0; $i < $serviceCount; $i++) {
                $services["extreme_{$i}"] = fn(): stdClass => new stdClass();
            }

            require_once $compiledFile;
            $compiledContainer = new \Test\Extreme\ExtremeContainer($services);

            // Extreme access test
            $errors = 0;
            $startTime = microtime(true);

            for ($i = 0; $i < $iterations; $i++) {
                try {
                    $serviceId = 'extreme_' . ($i % $serviceCount);
                    $compiledContainer->get($serviceId);
                } catch (\Throwable) {
                    $errors++;
                }

                // Progress report
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

            $duration = (microtime(true) - $startTime) * 1000;
            $opsPerSec = $iterations / ($duration / 1000);

            echo sprintf("\n  Операций: %s\n", number_format($iterations));
            echo sprintf("  Время: %.2f мс\n", $duration);
            echo sprintf("  Скорость: %s оп/сек\n", number_format($opsPerSec, 0));
            echo sprintf("  Ошибок: %d\n", $errors);
            echo sprintf("  Статус: %s\n\n", $errors === 0 ? '✅ Все операции успешны' : '⚠️ Есть ошибки');

            $this->results['extreme_access'] = [
                'passed' => $errors === 0,
                'operations' => $iterations,
                'ops_per_sec' => $opsPerSec,
                'errors' => $errors,
            ];
        } finally {
            array_map('unlink', glob($cacheDir . '/*'));
            rmdir($cacheDir);
        }
    }

    /**
     * Stress Test 3: Compilation with complex dependencies.
     */
    private function testCompilationWithComplexDependencies(): void
    {
        echo "💥 Стресс-тест 3: Компиляция со сложными зависимостями\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $container = new Container();
        $levels = 100;

        // Create dependency chain
        $container->set('level_0', fn(): stdClass => new stdClass());

        for ($i = 1; $i < $levels; $i++) {
            $prevLevel = $i - 1;
            $container->set("level_{$i}", function ($c) use ($prevLevel): stdClass {
                $c->get("level_{$prevLevel}");
                return new stdClass();
            });
        }

        // Compile
        $startTime = microtime(true);
        $code = $container->compile('ChainContainer', 'Test\\Chain');
        $compileTime = (microtime(true) - $startTime) * 1000;

        $codeSize = strlen($code);
        echo sprintf("  Глубина зависимостей: %d уровней\n", $levels);
        echo sprintf("  Время компиляции: %.2f мс\n", $compileTime);
        echo sprintf("  Размер кода: %.2f КБ\n", $codeSize / 1024);
        echo sprintf("  Время на уровень: %.3f мс\n", $compileTime / $levels);
        echo sprintf("  Статус: ✅ Успешно\n\n");

        $this->results['complex_dependencies'] = [
            'passed' => true,
            'levels' => $levels,
            'compile_time' => $compileTime,
            'time_per_level' => $compileTime / $levels,
        ];
    }

    /**
     * Stress Test 4: Multiple compilation cycles (memory stability).
     */
    private function testMultipleCompilationCycles(): void
    {
        echo "💥 Стресс-тест 4: Множественные циклы компиляции\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $cycles = 100;
        $servicesPerCycle = 1000;
        $startMemory = memory_get_usage(true);

        for ($cycle = 0; $cycle < $cycles; $cycle++) {
            $container = new Container();

            for ($i = 0; $i < $servicesPerCycle; $i++) {
                $container->set("service_{$cycle}_{$i}", fn(): stdClass => new stdClass());
            }

            $code = $container->compile('CycleContainer', 'Test\\Cycle');

            unset($container, $code);

            if ($cycle > 0 && $cycle % 20 === 0) {
                $currentMemory = memory_get_usage(true);
                $memoryGrowth = ($currentMemory - $startMemory) / 1024 / 1024;
                echo sprintf(
                    "  ✓ %d циклов, память: %.2f МБ (рост: %.3f МБ)\n",
                    $cycle,
                    $currentMemory / 1024 / 1024,
                    $memoryGrowth
                );
            }
        }

        $endMemory = memory_get_usage(true);
        $memoryGrowth = ($endMemory - $startMemory) / 1024 / 1024;

        echo sprintf("\n  Циклов: %d\n", $cycles);
        echo sprintf("  Сервисов скомпилировано: %s\n", number_format($cycles * $servicesPerCycle));
        echo sprintf("  Рост памяти: %.3f МБ\n", $memoryGrowth);
        echo sprintf("  Статус: %s\n\n", $memoryGrowth < 10 ? '✅ Память стабильна' : '⚠️ Рост памяти');

        $this->results['compilation_cycles'] = [
            'passed' => $memoryGrowth < 10,
            'cycles' => $cycles,
            'memory_growth' => $memoryGrowth,
        ];
    }

    /**
     * Stress Test 5: Code size growth with service count.
     */
    private function testCodeSizeGrowth(): void
    {
        echo "💥 Стресс-тест 5: Рост размера кода\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $counts = [100, 500, 1000, 5000, 10000, 25000, 50000];
        $sizes = [];

        foreach ($counts as $count) {
            $container = new Container();

            for ($i = 0; $i < $count; $i++) {
                $container->set("service_{$i}", fn(): stdClass => new stdClass());
            }

            $code = $container->compile('SizeTestContainer', 'Test\\Size');
            $size = strlen($code);
            $sizePerService = $size / $count;

            $sizes[$count] = [
                'total' => $size,
                'per_service' => $sizePerService,
            ];

            echo sprintf(
                "  ✓ %s сервисов: %.2f МБ (%.1f байт/сервис)\n",
                number_format($count),
                $size / 1024 / 1024,
                $sizePerService
            );

            unset($container, $code);
        }

        // Calculate linear coefficient
        $avgBytesPerService = array_sum(array_column($sizes, 'per_service')) / count($sizes);

        echo sprintf("\n  Средний размер на сервис: %.1f байт\n", $avgBytesPerService);
        echo sprintf("  Линейность: %s\n", $avgBytesPerService < 200 ? '✅ Отличная' : '⚠️ Приемлемая');
        echo sprintf("  Статус: ✅ Успешно\n\n");

        $this->results['code_size_growth'] = [
            'passed' => true,
            'sizes' => $sizes,
            'avg_bytes_per_service' => $avgBytesPerService,
        ];
    }

    /**
     * Print summary.
     */
    private function printSummary(): void
    {
        echo "\n╔══════════════════════════════════════════════════════════════════════════╗\n";
        echo "║                         ИТОГОВЫЕ РЕЗУЛЬТАТЫ                              ║\n";
        echo "╚══════════════════════════════════════════════════════════════════════════╝\n\n";

        $allPassed = true;
        $passedTests = 0;
        $totalTests = count($this->results);

        foreach ($this->results as $test => $result) {
            $testName = str_replace('_', ' ', ucwords($test, '_'));
            if ($result['passed']) {
                $passedTests++;
                echo "✅ {$testName}: PASSED\n";
            } else {
                $allPassed = false;
                echo "⚠️ {$testName}: WARNING\n";
            }
        }

        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo sprintf("Пройдено: %d/%d тестов\n", $passedTests, $totalTests);
        echo sprintf("Статус: %s\n", $allPassed ? '✅ ВСЕ СТРЕСС-ТЕСТЫ ПРОЙДЕНЫ' : '⚠️ ЕСТЬ ПРЕДУПРЕЖДЕНИЯ');
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

        if (isset($this->results['code_size_growth'])) {
            echo sprintf(
                "  • Средний размер кода: %.1f байт/сервис\n",
                $this->results['code_size_growth']['avg_bytes_per_service']
            );
        }

        if (isset($this->results['compilation_cycles'])) {
            echo sprintf(
                "  • Рост памяти за %d циклов: %.3f МБ\n",
                $this->results['compilation_cycles']['cycles'],
                $this->results['compilation_cycles']['memory_growth']
            );
        }

        echo "\n";
    }
}

// Run if executed directly
if (PHP_SAPI === 'cli' && basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    require_once __DIR__ . '/../vendor/autoload.php';

    echo "\n⚠️ ВНИМАНИЕ: Стресс-тесты для compiled container могут занять несколько минут!\n";

    $stressTest = new CompiledContainerStressTest();
    $stressTest->runAll();
}

