<?php


namespace App\Utils;


class searchForm
{
    private $item1;
    private $item2;
    private $item3;
    private $item4;
    private $item5;

    /**
     * @return mixed
     */
    public function getItem1()
    {
        return $this->item1;
    }

    /**
     * @param mixed $item1
     * @return searchForm
     */
    public function setItem1($item1)
    {
        $this->item1 = $item1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItem2()
    {
        return $this->item2;
    }

    /**
     * @param mixed $item2
     * @return searchForm
     */
    public function setItem2($item2)
    {
        $this->item2 = $item2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItem3()
    {
        return $this->item3;
    }

    /**
     * @param mixed $item3
     * @return searchForm
     */
    public function setItem3($item3)
    {
        $this->item3 = $item3;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItem4()
    {
        return $this->item4;
    }

    /**
     * @param mixed $item4
     * @return searchForm
     */
    public function setItem4($item4)
    {
        $this->item4 = $item4;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItem5()
    {
        return $this->item5;
    }

    /**
     * @param mixed $item5
     * @return searchForm
     */
    public function setItem5($item5)
    {
        $this->item5 = $item5;
        return $this;
    }

}