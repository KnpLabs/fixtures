<?php

class Benchmark
{
    private $closure;
    private $numTimes;
    private $resultSet;

    public function __construct(\Closure $closure, $numTimes = 1, $autorun = false)
    {
        $numTimes = intval($numTimes);

        if ($numTimes < 1) {
            throw new InvalidArgumentException('The $numTimes must be at least one.');
        }

        $this->closure  = $closure;
        $this->numTimes = $numTimes;

        if ($autorun) {
            $this->run();
        }
    }

    public function run()
    {
        $this->resultSet = $this->doRun();
    }

    public function isRun()
    {
        return null !== $this->resultSet;
    }

    public function getSeconds()
    {
        return $this->getResultValue('seconds');
    }

    public function __toString()
    {
        if ($this->isRun()) {
            return sprintf('Run in %fs.', $this->getSeconds());
        } else {
            return 'Not run yet.';
        }
    }

    private function getResultValue($key)
    {
        if (!$this->isRun()) {
            throw new LogicException('You must run the benchmark prior to get its results.');
        }

        return $this->resultSet[$key];
    }

    private function doRun()
    {
        $resultSets = array();

        for ($i = 0; $i < $this->numTimes; $i++) {
            $resultSets[] = $this->doRunOneTime();
        }

        return $this->computeResultSetsAverage($resultSets);
    }

    private function doRunOneTime()
    {
        $timeStart = microtime(true);
        call_user_func($this->closure);
        $timeEnd = microtime(true);

        return array(
            'seconds'   => $timeEnd - $timeStart,
        );
    }

    private function computeResultSetsAverage(array $resultSets)
    {
        $combinedResultSet = array();
        foreach ($resultSets as $resultSet) {
            foreach ($resultSet as $key => $value) {
                $combinedResultSets[$key][] = $value;
            }
        }

        $results = array();
        foreach ($combinedResultSets as $key => $combinedResults) {
            $results[$key] = array_sum($combinedResults) / count($resultSets);
        }

        return $results;
    }
}
