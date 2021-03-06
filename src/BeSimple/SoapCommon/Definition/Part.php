<?php

/*
 * This file is part of the BeSimpleSoap.
 *
 * (c) Christian Kerl <christian-kerl@web.de>
 * (c) Francis Besset <francis.besset@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BeSimple\SoapCommon\Definition;

/**
 * @author Francis Besset <francis.besset@gmail.com>
 */
class Part
{
    protected $name;
    protected $type;
    protected $nillable;
    protected $minOccurs = null;
    protected $maxOccurs = null;
    protected $restriction;

    public function __construct(
        $name,
        $type,
        $nillable = false,
        $minOccurs = null,
        $maxOccurs = null,
        $restriction = array()
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->setNillable($nillable);
        $this->setMinOccurs($minOccurs);
        $this->setMaxOccurs($maxOccurs);
        $this->setRestriction($restriction);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function isNillable()
    {
        return $this->nillable;
    }

    public function setNillable($nillable)
    {
        $this->nillable = (boolean) $nillable;
    }

    public function getMinOccurs()
    {
        return $this->minOccurs;
    }

    public function setMinOccurs($minOccurs)
    {
        $this->minOccurs = $minOccurs;
    }

    public function getMaxOccurs()
    {
        return $this->maxOccurs;
    }

    public function setMaxOccurs($maxOccurs)
    {
        $this->maxOccurs = $maxOccurs;
    }

    /**
     * @return array
     */
    public function getRestriction()
    {
        return $this->restriction;
    }

    /**
     * @param array $restriction
     */
    public function setRestriction($restriction)
    {
        $this->restriction = $restriction;
    }
}
