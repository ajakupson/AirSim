<?php

namespace AirSim\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Translations
 */
class Translations
{
    /**
     * @var integer
     */
    private $pageId;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $eng;

    /**
     * @var string
     */
    private $rus;

    /**
     * @var string
     */
    private $est;

    /**
     * @var integer
     */
    private $wordId;


    /**
     * Set pageId
     *
     * @param integer $pageId
     * @return Translations
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;

        return $this;
    }

    /**
     * Get pageId
     *
     * @return integer 
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Translations
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set eng
     *
     * @param string $eng
     * @return Translations
     */
    public function setEng($eng)
    {
        $this->eng = $eng;

        return $this;
    }

    /**
     * Get eng
     *
     * @return string 
     */
    public function getEng()
    {
        return $this->eng;
    }

    /**
     * Set rus
     *
     * @param string $rus
     * @return Translations
     */
    public function setRus($rus)
    {
        $this->rus = $rus;

        return $this;
    }

    /**
     * Get rus
     *
     * @return string 
     */
    public function getRus()
    {
        return $this->rus;
    }

    /**
     * Set est
     *
     * @param string $est
     * @return Translations
     */
    public function setEst($est)
    {
        $this->est = $est;

        return $this;
    }

    /**
     * Get est
     *
     * @return string 
     */
    public function getEst()
    {
        return $this->est;
    }

    /**
     * Get wordId
     *
     * @return integer 
     */
    public function getWordId()
    {
        return $this->wordId;
    }
}
