<?php

namespace Controller;

use Service\VersionService;

class VersionController {
    private VersionService $versionService;

    public function __construct()
    {
        $this->versionService = new VersionService();
    }

    public function records(): array
    {
        return $this->versionService->getUpdateRecords();
    }
}
