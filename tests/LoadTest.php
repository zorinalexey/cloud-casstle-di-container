<?php

declare(strict_types=1);

namespace CloudCastle\DI\Tests;

use CloudCastle\DI\Container;
use stdClass;

/**
 * Load testing script for Container.
 *
 * Simulates high-load scenarios.
 */
class LoadTest
{
    private readonly Container $container;

    private array $results = [];

    private static int $count = 2000000; // 2M операций (комфортно для систем с 6GB RAM)

    public function __construct()
    {
        $this->container = new Container();
    }

    /**
     * Run all load tests.
     */
    public function runAll(): void
    {
        echo "\n╔══════════════════════════════════════════════════════════════════════════╗\n";
        echo "║                   НАГРУЗОЧНОЕ ТЕСТИРОВАНИЕ                               ║\n";
        echo "╚══════════════════════════════════════════════════════════════════════════╝\n\n";

        $this->testMassiveServiceRegistration();
        $this->testHighFrequencyAccess();
        $this->testConcurrentServiceCreation();
        $this->testMemoryLeaks();
        $this->testLargeNumberOfServices();

        $this->printSummary();
    }

    /**
     * Test: Массовая регистрация сервисов.
     */
    private function testMassiveServiceRegistration(): void
    {
        echo "📊 Тест 1: Массовая регистрация сервисов\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $count = self::$count;
        $startMemory = memory_get_usage();
        $startTime = microtime(true);

        for ($i = 0; $i < $count; $i++) {
            $this->container->set('service_' . $i, fn(): stdClass => new stdClass());
        }

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $duration = ($endTime - $startTime) * 1000;
        $memoryUsed = ($endMemory - $startMemory) / 1024 / 1024;
        $opsPerSecond = $count / ($endTime - $startTime);

        echo sprintf("  Зарегистрировано: %s сервисов\n", number_format($count));
        echo sprintf("  Время: %.2f мс\n", $duration);
        echo sprintf("  Память: %.2f МБ\n", $memoryUsed);
        echo sprintf("  Скорость: %s оп/сек\n", number_format($opsPerSecond, 0));
        echo sprintf("  Среднее на операцию: %.3f мкс\n\n", ($duration * 1000) / $count);

        $this->results['registration'] = [
            'passed' => true,
            'ops_per_sec' => $opsPerSecond,
            'memory_mb' => $memoryUsed,
        ];
    }

    /**
     * Test: Высокочастотный доступ к сервисам.
     */
    private function testHighFrequencyAccess(): void
    {
        echo "📊 Тест 2: Высокочастотный доступ к сервисам\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $iterations = self::$count;
        $startTime = microtime(true);

        for ($i = 0; $i < $iterations; $i++) {
            $serviceId = 'service_' . ($i % 100); // Rotate through 100 services
            if (!$this->container->has($serviceId)) {
                $this->container->set($serviceId, fn(): stdClass => new stdClass());
            }

            $this->container->get($serviceId);
        }

        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;
        $opsPerSecond = $iterations / ($endTime - $startTime);

        echo sprintf("  Операций get(): %s\n", number_format($iterations));
        echo sprintf("  Время: %.2f мс\n", $duration);
        echo sprintf("  Скорость: %s оп/сек\n", number_format($opsPerSecond, 0));
        echo sprintf("  Среднее на операцию: %.3f мкс\n\n", ($duration * 1000) / $iterations);

        $this->results['access'] = [
            'passed' => true,
            'ops_per_sec' => $opsPerSecond,
        ];
    }

    /**
     * Test: Одновременное создание множества сервисов.
     */
    private function testConcurrentServiceCreation(): void
    {
        echo "📊 Тест 3: Одновременное создание сервисов\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $container = new Container();
        $count = self::$count;

        // Register services
        for ($i = 0; $i < $count; $i++) {
            $container->set('concurrent_' . $i, fn(): stdClass => new stdClass());
        }

        $startTime = microtime(true);

        // Get all services at once (simulating concurrent access)
        for ($i = 0; $i < $count; $i++) {
            $container->get('concurrent_' . $i);
        }

        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        echo sprintf("  Сервисов создано: %s\n", number_format($count));
        echo sprintf("  Время: %.2f мс\n", $duration);
        echo sprintf("  Среднее на сервис: %.3f мкс\n\n", ($duration * 1000) / $count);

        $this->results['concurrent'] = [
            'passed' => true,
            'time_ms' => $duration,
        ];
    }

    /**
     * Test: Проверка утечек памяти.
     */
    private function testMemoryLeaks(): void
    {
        echo "📊 Тест 4: Проверка утечек памяти\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $container = new Container();
        $iterations = self::$count;
        $memoryReadings = [];

        for ($i = 0; $i < $iterations; $i++) {
            $container->set('leak_test_' . $i, fn(): stdClass => new stdClass());
            $container->get('leak_test_' . $i);
            $container->remove('leak_test_' . $i);

            if ($i % 1000 === 0) {
                $memoryReadings[] = memory_get_usage();
            }
        }

        $memoryGrowth = end($memoryReadings) - $memoryReadings[0];
        $memoryGrowthMb = $memoryGrowth / 1024 / 1024;
        $leaked = $memoryGrowthMb > 1; // More than 1MB growth indicates potential leak

        echo sprintf("  Итераций: %s\n", number_format($iterations));
        echo sprintf("  Начальная память: %.2f МБ\n", $memoryReadings[0] / 1024 / 1024);
        echo sprintf("  Конечная память: %.2f МБ\n", end($memoryReadings) / 1024 / 1024);
        echo sprintf("  Рост памяти: %.2f МБ\n", $memoryGrowthMb);
        echo sprintf("  Статус: %s\n\n", $leaked ? "⚠️  Возможна утечка" : "✅ Утечек не обнаружено");

        $this->results['memory_leak'] = [
            'passed' => !$leaked,
            'growth_mb' => $memoryGrowthMb,
        ];
    }

    /**
     * Test: Работа с большим количеством сервисов.
     */
    private function testLargeNumberOfServices(): void
    {
        echo "📊 Тест 5: Большое количество активных сервисов\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $container = new Container();
        $serviceCount = self::$count;

        echo "  Регистрация {$serviceCount} сервисов...\n";
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        for ($i = 0; $i < $serviceCount; $i++) {
            $container->set('large_' . $i, fn(): stdClass => new stdClass());
        }

        $regTime = microtime(true) - $startTime;

        echo "  Доступ к каждому 100-му сервису...\n";
        $accessStart = microtime(true);

        for ($i = 0; $i < $serviceCount; $i += 100) {
            $container->get('large_' . $i);
        }

        $accessTime = microtime(true) - $accessStart;
        $endMemory = memory_get_usage();
        $totalMemory = ($endMemory - $startMemory) / 1024 / 1024;

        echo sprintf("  Время регистрации: %.2f мс\n", $regTime * 1000);
        echo sprintf("  Время доступа: %.2f мс\n", $accessTime * 1000);
        echo sprintf("  Общая память: %.2f МБ\n", $totalMemory);
        echo sprintf("  Память на сервис: %.2f КБ\n\n", ($totalMemory * 1024) / $serviceCount);

        $this->results['large_scale'] = [
            'passed' => true,
            'service_count' => $serviceCount,
            'total_memory_mb' => $totalMemory,
        ];
    }

    /**
     * Print summary of all tests.
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
            if ($result['passed']) {
                $passedTests++;
                echo "✅ " . ucfirst($test) . ": PASSED\n";
            } else {
                $allPassed = false;
                echo "❌ " . ucfirst($test) . ": FAILED\n";
            }
        }

        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo sprintf("Пройдено: %d/%d тестов\n", $passedTests, $totalTests);
        echo sprintf("Статус: %s\n", $allPassed ? "✅ ВСЕ ТЕСТЫ ПРОЙДЕНЫ" : "⚠️  ЕСТЬ ПРОБЛЕМЫ");
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

        if (!$allPassed) {
            exit(1);
        }
    }
}

// Run if executed directly
if (PHP_SAPI === 'cli' && basename(__FILE__) === basename($_SERVER['PHP_SELF'] ?? '')) {
    require_once __DIR__ . '/../vendor/autoload.php';

    $loadTest = new LoadTest();
    $loadTest->runAll();
}
