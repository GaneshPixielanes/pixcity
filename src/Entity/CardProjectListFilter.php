<?php

namespace App\Entity;

class CardProjectListFilter
{
    private $region = "";

    private $department = "";

    private $status = "";

    private $late = false;

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param string $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isLate()
    {
        return $this->late;
    }

    /**
     * @param bool $late
     */
    public function setLate($late)
    {
        $this->late = $late;
    }

}
