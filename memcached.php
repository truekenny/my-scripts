<?php
/**
 * @license MIT License
 * @copyright maartendekeizer, truekenny
 */

header("Content-type: text/plain");

$memcached = new Memcached();
$memcached->addServer('127.0.0.1', 11211);
$key = 'testkey';
$value = time();
$ttl = 5;

$memcached->set($key, $value, $ttl);

echo sprintf('%7s │ %7s │ %10s │ %6s │ %3s', 'date', 'key', 'value', 'status', 'ttl') . PHP_EOL;
echo '────────┼─────────┼────────────┼────────┼─────' . PHP_EOL;
echo sprintf('%7s │ %7s │ %10s │ %6s │ %3s', date('His'), $key, $value, 'set', $ttl) . PHP_EOL;

for ($offset = 0; $offset < ($ttl + 5); $offset++) {
        $result = $memcached->get($key);
        $resultJson = json_encode($result);

        $status = (($result == $value && $offset < $ttl || $result != $value && $offset >= $ttl) ? 'good' : 'error');

        echo sprintf('%7s │ %7s │ %10s │ %6s │ %3s', date('His'), $key, $resultJson, $status, $ttl - $offset) . PHP_EOL;

        if ($offset != ($ttl + 4)) {
                  sleep(1);
        }
}
