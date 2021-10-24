<?php

class TransactionFilter
{
    protected DateTime $start;
    protected DateTime $end;
    
    public function from(DateTime $start)
    {
        $this->start = $start;

        return $this;
    }

    public function to(DateTime $end)
    {
        $this->end = $end;

        return $this;
    }

}