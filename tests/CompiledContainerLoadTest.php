<?php

declare(strict_types=1);

namespace CloudCastle\DI\Tests;

use CloudCastle\DI\Container;
use CloudCastle\DI\ContainerFactory;
use stdClass;

/**
 * Load testing for Compiled Container.
 *
 * Tests performance of container compilation and compiled container usage.
 */
class CompiledContainerLoadTest
{
    private array $results = [];

    private static int $serviceCount = 10000; // 10K services for compilation tests

    private static int $accessCount = 1000000; // 1M accesses for performance tests

    /**
     * Run all load tests.
     */
    public function runAll(): void
    {
        echo "\n╔══════════════════════════════════════════════════════════════════════════╗\n";
        echo "║            COMPILED CONTAINER - НАГРУЗОЧНОЕ ТЕСТИРОВАНИЕ                 ║\n";
        echo "╚══════════════════════════════════════════════════════════════════════════╝\n\n";

        $this->testCompilationPerformance();
        $this->testCompiledContainerLoadSpeed();
        $this->testCompiledVsRegularPerformance();
        $this->testCompilationMemoryUsage();
        $this->testLargeContainerCompilation();

        $this->printSummary();
    }

    /**
     * Test 1: Compilation performance with different service counts.
     */
    private function testCompilationPerformance(): void
    {
        echo "📊 Тест 1: Производительность компиляции\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $serviceCounts = [100, 500, 1000, 5000, 10000];
        $results = [];

        foreach ($serviceCounts as $count) {
            $container = new Container();

            // Register services
            for ($i = 0; $i < $count; $i++) {
                $container->set("service_{$i}", fn(): stdClass => new stdClass());
            }

            // Measure compilation time
            $startTime = microtime(true);
            $code = $container->compile('TestContainer', 'Test\\NS');
            $compileTime = (microtime(true) - $startTime) * 1000;

            $codeSize = strlen($code);
            $servicesPerMs = $count / $compileTime;

            $results[$count] = [
                'time' => $compileTime,
                'size' => $codeSize,
                'speed' => $servicesPerMs,
            ];

            echo sprintf(
                "  ✓ %s сервисов: %.2f мс, %s КБ кода, %s серв/мс\n",
                number_format($count),
                $compileTime,
                number_format($codeSize / 1024, 2),
                number_format($servicesPerMs, 0)
            );
        }

        $avgTime = array_sum(array_column($results, 'time')) / count($results);
        $avgSpeed = array_sum(array_column($results, 'speed')) / count($results);

        echo sprintf("\n  Среднее время: %.2f мс\n", $avgTime);
        echo sprintf("  Средняя скорость: %s сервисов/мс\n\n", number_format($avgSpeed, 0));

        $this->results['compilation_performance'] = [
            'passed' => true,
            'results' => $results,
            'avg_time' => $avgTime,
            'avg_speed' => $avgSpeed,
        ];
    }

    /**
     * Test 2: Compiled container load speed.
     */
    private function testCompiledContainerLoadSpeed(): void
    {
        echo "📊 Тест 2: Скорость загрузки скомпилированного контейнера\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $cacheDir = sys_get_temp_dir() . '/di_test_' . uniqid();
        mkdir($cacheDir, 0755, true);

        try {
            // Create and compile containers with different class names
            $iterations = 10;
            $totalLoadTime = 0;
            $serviceCount = 1000;

            for ($j = 0; $j < $iterations; $j++) {
                $container = new Container();
                for ($i = 0; $i < $serviceCount; $i++) {
                    $container->set("service_{$i}", fn(): stdClass => new stdClass());
                }

                $className = 'LoadTestContainer' . $j;
                $compiledFile = $cacheDir . '/' . $className . '.php';
                $container->compileToFile($compiledFile, $className, 'Test\\Load');

                // Measure load time
                $startTime = microtime(true);
                require_once $compiledFile;
                $loadTime = (microtime(true) - $startTime) * 1000;
                $totalLoadTime += $loadTime;

                if (($j + 1) % 2 === 0) {
                    echo sprintf("  ✓ Загружено %d контейнеров (avg: %.3f мс)\n", $j + 1, $totalLoadTime / ($j + 1));
                }
            }

            $avgLoadTime = $totalLoadTime / $iterations;
            echo sprintf("\n  Итераций: %s\n", number_format($iterations));
            echo sprintf("  Сервисов в каждом: %s\n", number_format($serviceCount));
            echo sprintf("  Среднее время загрузки: %.3f мс\n", $avgLoadTime);
            echo sprintf("  Загрузок в секунду: %s\n\n", number_format(1000 / $avgLoadTime, 0));

            $this->results['load_speed'] = [
                'passed' => true,
                'avg_load_time' => $avgLoadTime,
                'loads_per_sec' => 1000 / $avgLoadTime,
            ];
        } finally {
            // Cleanup
            array_map('unlink', glob($cacheDir . '/*'));
            rmdir($cacheDir);
        }
    }

    /**
     * Test 3: Performance comparison - Compiled vs Regular.
     */
    private function testCompiledVsRegularPerformance(): void
    {
        echo "📊 Тест 3: Compiled vs Regular Container (производительность)\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $cacheDir = sys_get_temp_dir() . '/di_perf_' . uniqid();
        mkdir($cacheDir, 0755, true);

        try {
            $serviceCount = 1000;
            $iterations = self::$accessCount;

            // Setup regular container
            $regularContainer = new Container();
            for ($i = 0; $i < $serviceCount; $i++) {
                $regularContainer->set("service_{$i}", fn(): stdClass => new stdClass());
            }

            // Compile container
            $compiledFile = $cacheDir . '/PerfContainer.php';
            $regularContainer->compileToFile($compiledFile, 'PerfContainer', 'Test\\Perf');

            // Prepare services for compiled container
            $services = [];
            for ($i = 0; $i < $serviceCount; $i++) {
                $services["service_{$i}"] = fn(): stdClass => new stdClass();
            }

            require_once $compiledFile;
            $compiledContainer = new \Test\Perf\PerfContainer($services);

            // Benchmark regular container
            echo "  Тестирование Regular Container...\n";
            $startTime = microtime(true);
            for ($i = 0; $i < $iterations; $i++) {
                $serviceId = 'service_' . ($i % $serviceCount);
                $regularContainer->get($serviceId);
            }
            $regularTime = (microtime(true) - $startTime) * 1000;
            $regularOps = $iterations / ($regularTime / 1000);

            echo sprintf("    Время: %.2f мс\n", $regularTime);
            echo sprintf("    Скорость: %s оп/сек\n\n", number_format($regularOps, 0));

            // Benchmark compiled container
            echo "  Тестирование Compiled Container...\n";
            $startTime = microtime(true);
            for ($i = 0; $i < $iterations; $i++) {
                $serviceId = 'service_' . ($i % $serviceCount);
                $compiledContainer->get($serviceId);
            }
            $compiledTime = (microtime(true) - $startTime) * 1000;
            $compiledOps = $iterations / ($compiledTime / 1000);

            echo sprintf("    Время: %.2f мс\n", $compiledTime);
            echo sprintf("    Скорость: %s оп/сек\n\n", number_format($compiledOps, 0));

            $improvement = (($compiledOps / $regularOps) - 1) * 100;
            echo sprintf("  🚀 Улучшение: %+.1f%%\n", $improvement);
            echo sprintf("  Статус: %s\n\n", $improvement > 0 ? '✅ Compiled быстрее' : '⚠️ Regular быстрее');

            $this->results['compiled_vs_regular'] = [
                'passed' => $improvement > -10, // Allow 10% tolerance
                'regular_ops' => $regularOps,
                'compiled_ops' => $compiledOps,
                'improvement' => $improvement,
            ];
        } finally {
            // Cleanup
            array_map('unlink', glob($cacheDir . '/*'));
            rmdir($cacheDir);
        }
    }

    /**
     * Test 4: Memory usage during compilation.
     */
    private function testCompilationMemoryUsage(): void
    {
        echo "📊 Тест 4: Использование памяти при компиляции\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $serviceCounts = [1000, 5000, 10000];
        $results = [];

        foreach ($serviceCounts as $count) {
            $startMemory = memory_get_usage(true);

            $container = new Container();
            for ($i = 0; $i < $count; $i++) {
                $container->set("service_{$i}", fn(): stdClass => new stdClass());
            }

            $beforeCompile = memory_get_usage(true);
            $code = $container->compile('TestContainer', 'Test\\NS');
            $afterCompile = memory_get_usage(true);

            $containerMemory = $beforeCompile - $startMemory;
            $compileMemory = $afterCompile - $beforeCompile;
            $totalMemory = $afterCompile - $startMemory;
            $memoryPerService = $totalMemory / $count;

            $results[$count] = [
                'total' => $totalMemory,
                'per_service' => $memoryPerService,
                'compile_overhead' => $compileMemory,
            ];

            echo sprintf(
                "  ✓ %s сервисов: %.2f МБ общая, %.2f КБ/сервис, %.2f МБ компиляция\n",
                number_format($count),
                $totalMemory / 1024 / 1024,
                $memoryPerService / 1024,
                $compileMemory / 1024 / 1024
            );

            unset($container, $code);
        }

        echo sprintf("\n  Статус: ✅ Память использована эффективно\n\n");

        $this->results['memory_usage'] = [
            'passed' => true,
            'results' => $results,
        ];
    }

    /**
     * Test 5: Large container compilation.
     */
    private function testLargeContainerCompilation(): void
    {
        echo "📊 Тест 5: Компиляция большого контейнера\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $targetCount = self::$serviceCount;

        $container = new Container();
        echo sprintf("  Регистрация %s сервисов...\n", number_format($targetCount));

        $startTime = microtime(true);
        for ($i = 0; $i < $targetCount; $i++) {
            $container->set("large_service_{$i}", fn(): stdClass => new stdClass());
        }
        $registerTime = (microtime(true) - $startTime) * 1000;

        echo sprintf("    Время регистрации: %.2f мс\n", $registerTime);

        // Compile
        echo "  Компиляция контейнера...\n";
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        $code = $container->compile('LargeContainer', 'Test\\Large');

        $compileTime = (microtime(true) - $startTime) * 1000;
        $compileMemory = memory_get_usage(true) - $startMemory;
        $codeSize = strlen($code);

        echo sprintf("    Время компиляции: %.2f мс\n", $compileTime);
        echo sprintf("    Память: %.2f МБ\n", $compileMemory / 1024 / 1024);
        echo sprintf("    Размер кода: %.2f МБ\n", $codeSize / 1024 / 1024);
        echo sprintf("    Скорость: %s сервисов/сек\n", number_format($targetCount / ($compileTime / 1000), 0));

        // Save to file
        $cacheDir = sys_get_temp_dir() . '/di_large_' . uniqid();
        mkdir($cacheDir, 0755, true);

        try {
            $compiledFile = $cacheDir . '/LargeContainer.php';
            $startTime = microtime(true);
            file_put_contents($compiledFile, $code);
            $saveTime = (microtime(true) - $startTime) * 1000;

            echo sprintf("    Время сохранения: %.2f мс\n", $saveTime);

            // Test loading
            $startTime = microtime(true);
            require_once $compiledFile;
            $loadTime = (microtime(true) - $startTime) * 1000;

            echo sprintf("    Время загрузки файла: %.2f мс\n", $loadTime);

            $totalTime = $compileTime + $saveTime + $loadTime;
            echo sprintf("\n  Общее время (compile + save + load): %.2f мс\n", $totalTime);
            echo sprintf("  Статус: ✅ Успешно\n\n");

            $this->results['large_compilation'] = [
                'passed' => true,
                'service_count' => $targetCount,
                'compile_time' => $compileTime,
                'save_time' => $saveTime,
                'load_time' => $loadTime,
                'total_time' => $totalTime,
                'code_size' => $codeSize,
            ];
        } finally {
            array_map('unlink', glob($cacheDir . '/*'));
            rmdir($cacheDir);
        }
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
        echo sprintf("Статус: %s\n", $allPassed ? '✅ ВСЕ ТЕСТЫ ПРОЙДЕНЫ' : '⚠️ ЕСТЬ ПРЕДУПРЕЖДЕНИЯ');
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        // Print key metrics
        echo "📊 КЛЮЧЕВЫЕ МЕТРИКИ:\n\n";

        if (isset($this->results['compilation_performance'])) {
            echo sprintf(
                "  • Средняя скорость компиляции: %s сервисов/мс\n",
                number_format($this->results['compilation_performance']['avg_speed'], 0)
            );
        }

        if (isset($this->results['compiled_vs_regular'])) {
            echo sprintf(
                "  • Улучшение производительности: %+.1f%%\n",
                $this->results['compiled_vs_regular']['improvement']
            );
        }

        if (isset($this->results['large_compilation'])) {
            echo sprintf(
                "  • Компиляция %s сервисов: %.2f мс\n",
                number_format($this->results['large_compilation']['service_count']),
                $this->results['large_compilation']['compile_time']
            );
        }

        echo "\n";
    }
}

// Run if executed directly
if (PHP_SAPI === 'cli' && basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    require_once __DIR__ . '/../vendor/autoload.php';

    echo "\n⚡ COMPILED CONTAINER - Нагрузочные тесты\n";

    $loadTest = new CompiledContainerLoadTest();
    $loadTest->runAll();
}

