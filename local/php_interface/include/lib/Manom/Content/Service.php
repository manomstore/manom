<?php

namespace Manom\Content;


class Service extends SectionBindings
{

    protected static $sectionField = "UF_SERVICE_LINK";

    public function __construct(int $sectionId)
    {
        parent::__construct($sectionId);
        $this->list = $this->sectionsId;
    }
}